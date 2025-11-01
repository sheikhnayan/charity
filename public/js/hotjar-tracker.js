/**
 * Hotjar-style Session Recording & Heatmap Tracker
 * Uses rrweb for session recording
 */

(function() {
    'use strict';

    // Configuration
    const TRACKER_CONFIG = {
        apiBaseUrl: '/api',
        websiteId: null,
        sampleRate: 1.0, // 100% of sessions
        privacy: {
            maskAllInputs: true,
            maskTextSelector: '[data-mask]',
            blockSelector: '[data-block]'
        },
        batchSize: 50, // Events per batch
        batchInterval: 10000, // 10 seconds
        inactivityThreshold: 30000, // 30 seconds
        heatmapSampleRate: 1.0, // 100% for heatmap (change to 0.1 for production)
        mouseMoveThrottle: 500, // Track mouse every 500ms
    };

    class HotjarTracker {
        constructor(websiteId, config = {}) {
            this.config = { ...TRACKER_CONFIG, websiteId, ...config };
            this.sessionId = this.generateSessionId();
            this.visitorId = this.getOrCreateVisitorId();
            this.recordingId = null;
            this.events = [];
            this.isRecording = false;
            this.stopRecordingFn = null;
            this.sessionStartTime = Date.now();
            this.lastActivityTime = Date.now();
            this.inactivityTimer = null;
            
            // Heatmap tracking
            this.shouldTrackHeatmap = Math.random() < this.config.heatmapSampleRate;
            this.lastMouseMove = { x: 0, y: 0, time: Date.now() };
            this.attentionZones = [];
            
            this.init();
        }

        init() {
            if (!this.config.websiteId) {
                console.error('Hotjar Tracker: websiteId is required');
                return;
            }

            // Check if we should record this session
            if (Math.random() > this.config.sampleRate) {
                console.log('Hotjar Tracker: Session not sampled');
                return;
            }

            this.startRecording();
            this.setupHeatmapTracking();
            this.setupInactivityDetection();
            this.setupBeforeUnload();
        }

        generateSessionId() {
            return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        }

        getOrCreateVisitorId() {
            let visitorId = localStorage.getItem('hotjar_visitor_id');
            if (!visitorId) {
                visitorId = 'visitor_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('hotjar_visitor_id', visitorId);
            }
            return visitorId;
        }

        async startRecording() {
            try {
                // Check if rrweb is loaded
                if (typeof rrweb === 'undefined') {
                    console.error('Hotjar Tracker: rrweb library not loaded');
                    return;
                }

                // Start session on server
                const response = await fetch(`${this.config.apiBaseUrl}/session-recording/start`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        website_id: this.config.websiteId,
                        visitor_id: this.visitorId,
                        url: window.location.href,
                        page_title: document.title,
                        viewport_width: window.innerWidth,
                        viewport_height: window.innerHeight,
                        device_type: this.getDeviceType(),
                        browser: this.getBrowser(),
                        os: this.getOS(),
                        ip_address: null, // Server will capture
                    })
                });

                const data = await response.json();
                this.recordingId = data.recording_id;

                // Start rrweb recording
                this.stopRecordingFn = rrweb.record({
                    emit: (event) => this.handleRrwebEvent(event),
                    maskAllInputs: this.config.privacy.maskAllInputs,
                    maskTextSelector: this.config.privacy.maskTextSelector,
                    blockSelector: this.config.privacy.blockSelector,
                    checkoutEveryNms: 5 * 60 * 1000, // Full snapshot every 5 minutes
                });

                this.isRecording = true;
                console.log('Hotjar Tracker: Recording started', this.sessionId);

            } catch (error) {
                console.error('Hotjar Tracker: Failed to start recording', error);
            }
        }

        handleRrwebEvent(event) {
            this.events.push({
                timestamp: event.timestamp - this.sessionStartTime,
                type: event.type,
                data: event,
            });

            this.lastActivityTime = Date.now();

            // Batch send events
            if (this.events.length >= this.config.batchSize) {
                this.sendEvents();
            }
        }

        async sendEvents() {
            if (this.events.length === 0) return;

            const eventsToSend = [...this.events];
            this.events = [];

            try {
                await fetch(`${this.config.apiBaseUrl}/session-recording/events`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        website_id: this.config.websiteId,
                        events: eventsToSend,
                    })
                });
            } catch (error) {
                console.error('Hotjar Tracker: Failed to send events', error);
                // Re-add events to queue
                this.events = [...eventsToSend, ...this.events];
            }
        }

        setupHeatmapTracking() {
            if (!this.shouldTrackHeatmap) return;

            // Track clicks
            document.addEventListener('click', (e) => {
                this.trackHeatmapEvent({
                    event_type: 'click',
                    x: e.clientX + window.scrollX,
                    y: e.clientY + window.scrollY,
                    element_selector: this.getElementSelector(e.target),
                    element_text: e.target.textContent?.substring(0, 100),
                    element_class: e.target.className,
                    element_id: e.target.id,
                });
            }, true);

            // Track mouse moves (throttled)
            let mouseMoveTimeout;
            document.addEventListener('mousemove', (e) => {
                const now = Date.now();
                if (now - this.lastMouseMove.time < this.config.mouseMoveThrottle) return;

                clearTimeout(mouseMoveTimeout);
                mouseMoveTimeout = setTimeout(() => {
                    const duration = now - this.lastMouseMove.time;
                    // Cap duration at 60 seconds to prevent overflow
                    const cappedDuration = Math.min(duration, 60000);
                    
                    this.trackHeatmapEvent({
                        event_type: 'move',
                        x: e.clientX + window.scrollX,
                        y: e.clientY + window.scrollY,
                        duration_ms: cappedDuration,
                    });

                    this.lastMouseMove = { x: e.clientX, y: e.clientY, time: now };
                }, 100);
            });

            // Track scrolls
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    const scrollDepth = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
                    this.trackHeatmapEvent({
                        event_type: 'scroll',
                        scroll_depth: scrollDepth,
                        max_scroll: document.body.scrollHeight,
                        y: window.scrollY,
                    });
                }, 250);
            });
        }

        async trackHeatmapEvent(eventData) {
            try {
                await fetch(`${this.config.apiBaseUrl}/heatmap/track`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        website_id: this.config.websiteId,
                        page_url: window.location.href,
                        viewport_width: window.innerWidth,
                        viewport_height: window.innerHeight,
                        device_type: this.getDeviceType(),
                        session_id: this.sessionId,
                        visitor_id: this.visitorId,
                        ...eventData
                    })
                });
            } catch (error) {
                console.error('Hotjar Tracker: Failed to track heatmap event', error);
            }
        }

        getElementSelector(element) {
            if (!element || element === document) return null;
            
            if (element.id) return `#${element.id}`;
            
            let path = [];
            while (element && element.nodeType === Node.ELEMENT_NODE) {
                let selector = element.nodeName.toLowerCase();
                if (element.className) {
                    selector += '.' + element.className.trim().split(/\s+/).join('.');
                }
                path.unshift(selector);
                if (path.length > 5) break; // Limit depth
                element = element.parentNode;
            }
            
            return path.join(' > ');
        }

        setupInactivityDetection() {
            this.inactivityTimer = setInterval(() => {
                const inactiveTime = Date.now() - this.lastActivityTime;
                if (inactiveTime > this.config.inactivityThreshold) {
                    console.log('Hotjar Tracker: Session inactive, completing...');
                    this.completeSession();
                }
            }, 10000); // Check every 10 seconds
        }

        setupBeforeUnload() {
            window.addEventListener('beforeunload', () => {
                this.completeSession();
            });

            // Also handle visibility change
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.sendEvents(); // Send pending events
                }
            });
        }

        async completeSession() {
            if (!this.isRecording) return;

            // Stop recording
            if (this.stopRecordingFn) {
                this.stopRecordingFn();
            }

            // Send any remaining events
            await this.sendEvents();

            // Complete session on server
            const duration = Date.now() - this.sessionStartTime;
            try {
                await fetch(`${this.config.apiBaseUrl}/session-recording/complete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        website_id: this.config.websiteId,
                        duration_ms: duration,
                    })
                });
            } catch (error) {
                console.error('Hotjar Tracker: Failed to complete session', error);
            }

            this.isRecording = false;
            clearInterval(this.inactivityTimer);
            console.log('Hotjar Tracker: Session completed', duration, 'ms');
        }

        getDeviceType() {
            const ua = navigator.userAgent;
            if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) return 'tablet';
            if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) return 'mobile';
            return 'desktop';
        }

        getBrowser() {
            const ua = navigator.userAgent;
            if (ua.includes('Firefox')) return 'Firefox';
            if (ua.includes('Chrome')) return 'Chrome';
            if (ua.includes('Safari')) return 'Safari';
            if (ua.includes('Edge')) return 'Edge';
            return 'Other';
        }

        getOS() {
            const ua = navigator.userAgent;
            if (ua.includes('Win')) return 'Windows';
            if (ua.includes('Mac')) return 'MacOS';
            if (ua.includes('Linux')) return 'Linux';
            if (ua.includes('Android')) return 'Android';
            if (ua.includes('iOS')) return 'iOS';
            return 'Other';
        }
    }

    // Expose to global scope
    window.HotjarTracker = HotjarTracker;

    // Auto-initialize if data attribute exists
    document.addEventListener('DOMContentLoaded', () => {
        const trackerElement = document.querySelector('[data-hotjar-tracker]');
        if (trackerElement) {
            const websiteId = trackerElement.getAttribute('data-website-id');
            if (websiteId) {
                window.hotjarTracker = new HotjarTracker(parseInt(websiteId));
            }
        }
    });

})();

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
            this.batchTimer = null;
            
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
            this.captureScreenshotIfNeeded();
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
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
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

                // Start rrweb recording with comprehensive settings
                this.stopRecordingFn = rrweb.record({
                    emit: (event) => this.handleRrwebEvent(event),
                    
                    // CRITICAL: Don't mask any text - we want to see everything
                    maskAllText: false,
                    maskAllInputs: false, // Don't mask input values either
                    maskTextSelector: null,
                    blockSelector: this.config.privacy.blockSelector,
                    checkoutEveryNms: 5 * 60 * 1000, // Full snapshot every 5 minutes
                    
                    // CRITICAL: Capture all styles and CSS properly
                    inlineStylesheet: true,
                    inlineImages: true, // Capture images to show them in replay
                    recordCanvas: true,
                    collectFonts: true,
                    
                    // Don't block any CSS or assets
                    blockClass: 'rr-block',
                    ignoreClass: 'rr-ignore',
                    maskTextClass: 'rr-mask',
                    maskInputOptions: {
                        password: true,
                    },
                    
                    // Sampling configs
                    sampling: {
                        // Don't skip any mouse movements
                        mousemove: true,
                        mouseInteraction: true,
                        scroll: 150, // Capture scroll every 150ms
                        input: 'last', // Capture last input value
                    },
                });

                this.isRecording = true;
                console.log('Hotjar Tracker: Recording started', this.sessionId);

                // Setup batch timer to send events periodically
                this.batchTimer = setInterval(() => {
                    if (this.events.length > 0) {
                        this.sendEvents();
                    }
                }, this.config.batchInterval);

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

            // CRITICAL: Send full snapshot (type 2) immediately
            // Without this, the player has no DOM structure to replay on
            if (event.type === 2) {
                this.sendEvents();
                return;
            }

            // Batch send other events
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
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
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
            clearInterval(this.batchTimer);
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

        async captureScreenshotIfNeeded() {
            // Wait for page to fully load
            if (document.readyState !== 'complete') {
                window.addEventListener('load', () => this.captureScreenshotIfNeeded());
                return;
            }

            try {
                // Check if screenshot exists
                const pagePath = window.location.pathname;
                const checkResponse = await fetch(`${this.config.apiBaseUrl}/heatmap/screenshot?website_id=${this.config.websiteId}&page_path=${encodeURIComponent(pagePath)}`, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (checkResponse.ok) {
                    console.log('Hotjar Tracker: Screenshot already exists');
                    return;
                }

                // Capture screenshot using html2canvas
                console.log('Hotjar Tracker: Capturing screenshot...');
                
                // Load html2canvas if not already loaded
                if (typeof html2canvas === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                    script.onload = () => this.doScreenshotCapture();
                    document.head.appendChild(script);
                } else {
                    this.doScreenshotCapture();
                }
            } catch (error) {
                console.log('Hotjar Tracker: Screenshot check failed:', error);
            }
        }

        async doScreenshotCapture() {
            try {
                const canvas = await html2canvas(document.body, {
                    allowTaint: true,
                    useCORS: true,
                    logging: false,
                    width: window.innerWidth,
                    height: document.documentElement.scrollHeight,
                    windowWidth: window.innerWidth,
                    windowHeight: document.documentElement.scrollHeight,
                });

                const screenshotData = canvas.toDataURL('image/png');
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch(`${this.config.apiBaseUrl}/heatmap/screenshot/capture`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        website_id: this.config.websiteId,
                        page_url: window.location.href,
                        page_path: window.location.pathname,
                        screenshot_data: screenshotData,
                        viewport_width: window.innerWidth,
                        viewport_height: document.documentElement.scrollHeight,
                        device_type: this.getDeviceType(),
                    })
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Hotjar Tracker: Screenshot save failed:', response.status, errorText);
                    return;
                }

                console.log('Hotjar Tracker: Screenshot captured successfully');
            } catch (error) {
                console.error('Hotjar Tracker: Screenshot capture failed:', error);
            }
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

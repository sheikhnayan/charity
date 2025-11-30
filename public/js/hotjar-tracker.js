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
                    
                    // CRITICAL: Don't mask any text - capture everything as-is
                    // Remove ALL masking to prevent "null" text issue
                    checkoutEveryNms: 5 * 60 * 1000, // Full snapshot every 5 minutes
                    
                    // CRITICAL: Capture all styles and CSS properly
                    inlineStylesheet: true,
                    inlineImages: false, // Disable to avoid CORS errors with external images
                    recordCanvas: false, // Disable to avoid cross-origin canvas errors
                    collectFonts: false, // Disable to avoid font loading issues
                    
                    // Don't block any CSS or assets
                    blockClass: 'rr-block',
                    ignoreClass: 'rr-ignore',
                    maskTextClass: 'rr-mask',
                    maskInputOptions: {
                        password: true,
                    },
                    
                    // Sampling configs - capture frequently for smooth replay
                    sampling: {
                        mousemove: 50,  // Capture mouse position every 50ms
                        scroll: 150,    // Capture scroll every 150ms
                        input: 'last',  // Capture last input value
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

        cleanNullTextNodes(node) {
            // Recursively remove "null" text from all text nodes
            if (!node) return;
            
            // rrweb uses type 3 for text nodes
            // Check both textContent and childNodes[0] for the text value
            if (node.type === 3) {
                // Text node - check if it contains "null"
                if (node.textContent === 'null') {
                    node.textContent = '';
                }
                // Also check childNodes array (some versions use this)
                if (node.childNodes && node.childNodes[0] === 'null') {
                    node.childNodes[0] = '';
                }
            }
            
            // Recurse through children
            if (node.childNodes && Array.isArray(node.childNodes)) {
                node.childNodes.forEach(child => this.cleanNullTextNodes(child));
            }
        }

        handleRrwebEvent(event) {
            // Clean "null" text nodes from snapshots
            if (event.type === 2 && event.data?.node) {
                // Debug: Find first text node to see its structure
                const findTextNode = (node, depth = 0) => {
                    if (depth > 10) return null;
                    if (node.type === 3) return node;
                    if (node.childNodes) {
                        for (let child of node.childNodes) {
                            const found = findTextNode(child, depth + 1);
                            if (found) return found;
                        }
                    }
                    return null;
                };
                // Find a text node with "null" content
                const findNullTextNode = (node, depth = 0) => {
                    if (depth > 15) return null;
                    if (node.type === 3 && node.textContent === 'null') {
                        return node;
                    }
                    if (node.childNodes) {
                        for (let child of node.childNodes) {
                            const found = findNullTextNode(child, depth + 1);
                            if (found) return found;
                        }
                    }
                    return null;
                };
                const nullText = findNullTextNode(event.data.node);
                if (nullText) {
                    console.log('⚠️ Found "null" text node:', JSON.stringify(nullText, null, 2));
                } else {
                    console.log('✅ No "null" text nodes found in snapshot');
                }
                
                this.cleanNullTextNodes(event.data.node);
            }
            
            // Debug logging for first few events
            if (event.type === 2) {
                console.log('Full snapshot captured:', {
                    type: event.type,
                    hasNode: !!event.data?.node,
                    nodeType: event.data?.node?.type,
                    nodeId: event.data?.node?.id,
                    childNodeCount: event.data?.node?.childNodes?.length
                });
                
                // Debug the actual DOM structure
                console.log('Root node structure:', {
                    rootChildren: event.data?.node?.childNodes?.length,
                    firstChild: event.data?.node?.childNodes?.[0]?.type,
                    firstChildTag: event.data?.node?.childNodes?.[0]?.tagName
                });
                
                // Try to find HTML node
                const htmlNode = event.data?.node?.childNodes?.find(n => n.tagName === 'html');
                if (htmlNode) {
                    console.log('HTML node found:', {
                        htmlChildren: htmlNode.childNodes?.length,
                        childTags: htmlNode.childNodes?.map(n => n.tagName)
                    });
                    
                    // Find body
                    const bodyNode = htmlNode.childNodes?.find(n => n.tagName === 'body');
                    if (bodyNode) {
                        console.log('Body node captured:', {
                            bodyId: bodyNode.id,
                            bodyChildren: bodyNode.childNodes?.length,
                            firstFewChildren: bodyNode.childNodes?.slice(0, 5).map(n => ({
                                tag: n.tagName,
                                id: n.attributes?.id,
                                class: n.attributes?.class
                            }))
                        });
                        
                        // Check for rendered-page div
                        const checkForContent = (nodes, depth = 0) => {
                            if (depth > 10) return false;
                            for (let node of (nodes || [])) {
                                if (node.attributes?.id === 'rendered-page') {
                                    console.log('✅ Found #rendered-page with', node.childNodes?.length, 'children');
                                    return true;
                                }
                                if (node.childNodes && checkForContent(node.childNodes, depth + 1)) {
                                    return true;
                                }
                            }
                            return false;
                        };
                        
                        if (!checkForContent(bodyNode.childNodes)) {
                            console.warn('⚠️ #rendered-page not found in snapshot!');
                        }
                    } else {
                        console.error('❌ Body node not found!');
                    }
                } else {
                    console.error('❌ HTML node not found!');
                }
            }
            
            this.events.push({
                timestamp: event.timestamp - this.sessionStartTime,
                type: event.type,
                data: event,
            });

            this.lastActivityTime = Date.now();

            // CRITICAL: Send full snapshot (type 2) immediately
            // Without this, the player has no DOM structure to replay on
            if (event.type === 2) {
                console.log('Sending full snapshot immediately');
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
                    const scrollY = window.scrollY || 0;
                    const scrollHeight = document.body.scrollHeight || document.documentElement.scrollHeight || 1;
                    const innerHeight = window.innerHeight || 1;
                    const maxScroll = scrollHeight - innerHeight;
                    const scrollDepth = maxScroll > 0 ? Math.round((scrollY / maxScroll) * 100) : 0;
                    
                    this.trackHeatmapEvent({
                        event_type: 'scroll',
                        scroll_depth: Math.min(100, Math.max(0, scrollDepth)), // Clamp between 0-100
                        max_scroll: scrollHeight,
                        y: Math.round(scrollY),
                    });
                }, 250);
            });
        }

        async trackHeatmapEvent(eventData) {
            try {
                // Validate and clean coordinates
                const cleanedData = { ...eventData };
                
                // Ensure x and y are valid integers or null
                if (cleanedData.x !== undefined && cleanedData.x !== null) {
                    cleanedData.x = Math.round(Number(cleanedData.x));
                    if (isNaN(cleanedData.x)) cleanedData.x = null;
                }
                if (cleanedData.y !== undefined && cleanedData.y !== null) {
                    cleanedData.y = Math.round(Number(cleanedData.y));
                    if (isNaN(cleanedData.y)) cleanedData.y = null;
                }
                
                // Ensure numeric fields are valid
                if (cleanedData.scroll_depth !== undefined) {
                    cleanedData.scroll_depth = Math.round(Number(cleanedData.scroll_depth));
                    if (isNaN(cleanedData.scroll_depth)) cleanedData.scroll_depth = null;
                }
                if (cleanedData.max_scroll !== undefined) {
                    cleanedData.max_scroll = Math.round(Number(cleanedData.max_scroll));
                    if (isNaN(cleanedData.max_scroll)) cleanedData.max_scroll = null;
                }
                if (cleanedData.duration_ms !== undefined) {
                    cleanedData.duration_ms = Math.round(Number(cleanedData.duration_ms));
                    if (isNaN(cleanedData.duration_ms)) cleanedData.duration_ms = null;
                }
                
                // Get page path from URL
                const pagePath = window.location.pathname || '/';
                
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
                        page_path: pagePath,
                        viewport_width: window.innerWidth,
                        viewport_height: window.innerHeight,
                        device_type: this.getDeviceType(),
                        session_id: this.sessionId,
                        visitor_id: this.visitorId,
                        ...cleanedData
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

            // Additional wait for dynamic content (page builder components, images, etc.)
            await new Promise(resolve => setTimeout(resolve, 15000));

            try {
                // For dynamic page-builder content, check if screenshot is recent (last 24 hours)
                const pagePath = window.location.pathname;
                const checkResponse = await fetch(`${this.config.apiBaseUrl}/heatmap/screenshot?website_id=${this.config.websiteId}&page_path=${encodeURIComponent(pagePath)}`, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (checkResponse.ok) {
                    const data = await checkResponse.json();
                    // Check if screenshot is recent (within last 24 hours for dynamic content)
                    const screenshotAge = Date.now() - new Date(data.created_at).getTime();
                    const maxAge = 24 * 60 * 60 * 1000; // 24 hours in milliseconds
                    
                    if (screenshotAge < maxAge) {
                        console.log('Hotjar Tracker: Recent screenshot exists (age: ' + Math.round(screenshotAge / 1000 / 60) + ' minutes)');
                        return;
                    } else {
                        console.log('Hotjar Tracker: Screenshot outdated, capturing new one...');
                    }
                } else {
                    console.log('Hotjar Tracker: No screenshot found, capturing new one...');
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
                // If check fails, try to capture anyway
                if (typeof html2canvas !== 'undefined') {
                    this.doScreenshotCapture();
                }
            }
        }

        async doScreenshotCapture() {
            try {
                // Wait for any dynamic content to load
                await new Promise(resolve => setTimeout(resolve, 15000)); // Wait 15 seconds for content
                
                // Scroll to top before capturing
                window.scrollTo(0, 0);
                await new Promise(resolve => setTimeout(resolve, 500)); // Wait for scroll
                
                const canvas = await html2canvas(document.body, {
                    useCORS: true, // Try to use CORS for images that support it
                    allowTaint: false, // Don't taint canvas with cross-origin images
                    logging: false,
                    width: window.innerWidth,
                    height: document.documentElement.scrollHeight,
                    windowWidth: window.innerWidth,
                    windowHeight: document.documentElement.scrollHeight,
                    scale: 1, // Use 1:1 scale for accuracy
                    backgroundColor: '#ffffff', // White background
                    removeContainer: true, // Clean up after capture
                    imageTimeout: 15000, // 15 second timeout for images
                    foreignObjectRendering: true, // Use foreignObject rendering for better cross-origin handling
                    onclone: function(clonedDoc) {
                        // Remove or replace cross-origin images that might cause issues
                        const images = clonedDoc.getElementsByTagName('img');
                        for (let img of images) {
                            if (!img.complete) {
                                img.style.display = 'none'; // Hide incomplete images
                            }
                            // Remove crossOrigin attribute to avoid CORS issues
                            img.removeAttribute('crossorigin');
                        }
                        
                        // Remove any iframes that might cause security issues
                        const iframes = clonedDoc.getElementsByTagName('iframe');
                        for (let iframe of iframes) {
                            iframe.style.display = 'none';
                        }
                    }
                });

                const screenshotData = canvas.toDataURL('image/png', 0.8); // 80% quality to reduce size
                
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
    // Use 'load' event instead of 'DOMContentLoaded' to ensure all content is rendered
    window.addEventListener('load', () => {
        // Add small delay to ensure all dynamic content is loaded
        setTimeout(() => {
            const trackerElement = document.querySelector('[data-hotjar-tracker]');
            if (trackerElement) {
                const websiteId = trackerElement.getAttribute('data-website-id');
                if (websiteId) {
                    window.hotjarTracker = new HotjarTracker(parseInt(websiteId));
                }
            }
        }, 5000); // Wait 5 seconds after page load to start recording
    });

})();

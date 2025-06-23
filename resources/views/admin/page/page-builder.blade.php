<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Page Builder</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    #studentTable {
        background-color: #fff !important; /* Set the table background to white */
        border: none !important; /* Remove the table border */
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important; /* Set the background of table cells to white */
        border: none !important; /* Remove borders from table cells */
    }

    #studentTable tbody tr {
        background-color: #fff !important; /* Set the background of table rows to white */
    }

    #studentTable_filter {
        display: none;
    }

    #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none; /* Hide the table header */
    }
</style>

  <style>
    :root {
      --primary-color: #3B82F6;
      --border-color: #e5e7eb;
      --bg-color: #f9fafb;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.5;
    }

    .app {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 250px;
      background: white;
      border-right: 1px solid var(--border-color);
      padding: 20px;
      overflow-y: auto;
    }

    .sidebar h2 {
      margin-bottom: 20px;
      color: var(--primary-color);
    }

    .component-list {
      display: grid;
      gap: 10px;
    }

    .component-item {
      padding: 15px;
      background: var(--bg-color);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      cursor: move;
      transition: all 0.2s;
    }

    .component-item:hover {
      background: white;
      transform: translateY(-2px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .canvas {
      flex: 1;
      background: var(--bg-color);
      padding: 40px;
      overflow-y: auto;
    }

    .page {
      max-width: 800px;
      min-height: 1000px;
      margin: 0 auto;
      background: white;
      padding: 40px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      border-radius: 8px;
    }

    .dropzone {
      min-height: 100px;
      border: 2px dashed var(--border-color);
      border-radius: 8px;
      margin: 10px 0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #666;
      transition: all 0.2s;
    }

    .dropzone.dragover {
      background: #f0f9ff;
      border-color: var(--primary-color);
    }

    .component {
      position: relative;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid transparent;
      border-radius: 4px;
    }

    .component:hover {
      border-color: var(--primary-color);
    }

    .component.selected {
      outline: 2px solid var(--primary-color);
    }

    .component-controls {
      position: absolute;
      top: -30px;
      right: 0;
      display: none;
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      padding: 4px;
      z-index: 10;
    }

    .component:hover .component-controls {
      display: flex;
      gap: 4px;
    }

    .btn {
      padding: 4px 8px;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .properties {
      width: 300px;
      background: white;
      border-left: 1px solid var(--border-color);
      padding: 20px;
      overflow-y: auto;
    }

    .properties h3 {
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #666;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--border-color);
      border-radius: 4px;
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }

    .image-preview {
      width: 100%;
      margin-top: 10px;
      border-radius: 4px;
    }

    .component[data-type="gallery"] img:hover {
        opacity: 0.6;
        outline: 2px dashed red;
    }
  </style>
</head>
<body>
    @php
        $groups = \App\Models\User::where('website_id', $data->website->id)->where('role','group_leader')->get();
    @endphp
    <input type="hidden" name="page_id" id="page_id" value="{{ $data->id }}">
  <div class="app">
    <div class="sidebar">
        <h2>Builder</h2>
        <div style="margin-bottom: 10px;">
            <button class="btn" onclick="showTab('componentsTab')">Components</button>
            <button class="btn" onclick="showTab('featuresTab')">Features</button>
            <button class="btn btn-success" onclick="saveBuilderState()">Save</button>
        </div>

        <div id="componentsTab" class="tab-section">
            <h3>Components</h3>
            <div class="component-list">
            <div class="component-item" draggable="true" data-type="text-images">Text & Images</div>
            <div class="component-item" draggable="true" data-type="section-title">Section Title</div>
            <div class="component-item" draggable="true" data-type="divider">Divider</div>
            <div class="component-item" draggable="true" data-type="site-banner">Site Banner</div>
            <div class="component-item" draggable="true" data-type="custom-banner">Custom Banner</div>
            <div class="component-item" draggable="true" data-type="gallery">Gallery</div>
            <div class="component-item" draggable="true" data-type="slider">Slider</div>
            <div class="component-item" draggable="true" data-type="visitor-upload">Visitor Upload</div>
            <div class="component-item" draggable="true" data-type="video">Video</div>
            <div class="component-item" draggable="true" data-type="faq">FAQ</div>
            <div class="component-item" draggable="true" data-type="buttons">Buttons</div>
            <div class="component-item" draggable="true" data-type="display-assets">Display Assets</div>
            <div class="component-item" draggable="true" data-type="cards">Cards</div>
            <div class="component-item" draggable="true" data-type="full-width-text-image">Full Width Text & Image</div>
            <div class="component-item" draggable="true" data-type="alert-message">Alert Message</div>
            </div>
        </div>

        <div id="featuresTab" class="tab-section" style="display: none;">
            <h3>Features</h3>
            <div class="component-list">
            <div class="component-item" draggable="true" data-type="event-countdown">Event Countdown</div>
            <div class="component-item" draggable="true" data-type="event-information">Event Information</div>
            <div class="component-item" draggable="true" data-type="sell-tickets">Sell Tickets</div>
            <div class="component-item" draggable="true" data-type="whos-coming">Who's Coming</div>
            <div class="component-item" draggable="true" data-type="donation-form">Donation Form</div>
            <div class="component-item" draggable="true" data-type="donor-list">Donor List</div>
            <div class="component-item" draggable="true" data-type="donation-slider">Donation Slider</div>
            <div class="component-item" draggable="true" data-type="custom-form">Custom Form</div>
            <div class="component-item" draggable="true" data-type="contact-form">Contact Form</div>
            <div class="component-item" draggable="true" data-type="social-share">Sharing Buttons</div>
            <div class="component-item" draggable="true" data-type="auth-form">Authentication Form</div>
            <div class="component-item" draggable="true" data-type="student-leaderboard">Student Leaderboard</div>
            <div class="component-item" draggable="true" data-type="student-listing">Student Listing</div>
            <div class="component-item" draggable="true" data-type="updates">Updates</div>
            <div class="component-item" draggable="true" data-type="facebook-comments">Facebook Comments</div>
            <div class="component-item" draggable="true" data-type="sponsorships">Sponsorships</div>
            <div class="component-item" draggable="true" data-type="contact-us">Contact Us</div>
            <div class="component-item" draggable="true" data-type="site-goal">Site Goal</div>
            </div>
        </div>
    </div>


    <div class="canvas" id="canvas">
      <div class="page" id="page">
        <div class="dropzone">Drop components here</div>
      </div>
    </div>

    <div class="properties" id="properties">
      <h3>Properties</h3>
      <div id="propertyControls"></div>
    </div>
  </div>

  <div id="imageManagerModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="closeImageManager()">&times;</span>
      <h3>Select or Upload an Image</h3>

      <!-- Upload Form -->
      <input type="file" id="imageUploadInput" accept="image/*" onchange="handleImageUpload(event)">
      <div id="uploadStatus"></div>

      <!-- Image Gallery -->
      <div class="image-grid" id="imageGallery">
        <!-- Dynamically added thumbnails will appear here -->
      </div>
    </div>
  </div>

    <!-- File Manager Modal -->
    <div id="fileManagerModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; border:1px solid #ccc; padding:20px; z-index:1000; width:600px; max-height:80vh; overflow:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Select a File</h3>
            <button onclick="closeFileManager()">✖</button>
        </div>
        <input type="file" accept="image/*,video/*" onchange="handleFileUpload(event)">
        <p id="uploadStatus"></p>
        <div id="fileGallery" style="display:flex; flex-wrap:wrap; margin-top:10px; gap:10px;"></div>
    </div>

  <style>
    .modal {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6); display: flex; justify-content: center; align-items: center;
      z-index: 9999;
    }
    .modal-content {
      background: white; padding: 20px; border-radius: 8px;
      width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;
      position: relative;
    }
    .image-grid {
      display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px;
    }
    .image-grid img {
      width: 100px; height: 100px; object-fit: cover;
      cursor: pointer; border-radius: 4px; border: 2px solid transparent;
    }
    .image-grid img:hover {
      border-color: #007bff;
    }
    .close {
      position: absolute; top: 10px; right: 20px; cursor: pointer; font-size: 24px;
    }
  </style>
  @php
    $sponsors = \App\Models\Sponsor::where('website_id', $data->website_id)->get();
  @endphp


  <script>

    window.currentSponsors = @json($sponsors->map(function($s) {
        return [
            'id' => $s->id,
            'image' => asset($s->image),
        ];
    }));
    let selectedComponent = null;

    let lastSelectedComponent = null;

    const canvas = document.getElementById('canvas');

    // Store FAQ data per component (WeakMap to avoid memory leaks)
    const faqComponentData = new WeakMap();

    // Handle drag start
    document.querySelectorAll('.component-item').forEach(item => {
        item.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('type', item.dataset.type);
        });
    });


    // Handle drag over
    document.addEventListener('dragover', (e) => {
      e.preventDefault();
      const dropzone = e.target.closest('.dropzone');
      if (dropzone) {
        dropzone.classList.add('dragover');
      }
    });

    // Handle drag leave
    document.addEventListener('dragleave', (e) => {
      const dropzone = e.target.closest('.dropzone');
      if (dropzone) {
        dropzone.classList.remove('dragover');
      }
    });

    // Handle drop
    document.addEventListener('drop', (e) => {
      e.preventDefault();
      const dropzone = e.target.closest('.dropzone');
      if (dropzone) {
        dropzone.classList.remove('dragover');
        const type = e.dataTransfer.getData('type');
        const component = createComponent(type);

        // Insert the component before the dropzone
        dropzone.parentNode.insertBefore(component, dropzone);

        // Create a new dropzone after the component
        const newDropzone = createDropzone();
        dropzone.parentNode.insertBefore(newDropzone, dropzone);

        // Remove the original dropzone if it's not the last one
        const dropzones = document.querySelectorAll('.dropzone');
        if (dropzones.length > 1) {
          dropzone.remove();
        }

        // Select the new component
        selectComponent(component);
      }
    });

    // Create a new component
    function createComponent(type) {
      const component = document.createElement('div');
      component.className = 'component';
      component.dataset.type = type;

      const controls = document.createElement('div');
      controls.className = 'component-controls';
      controls.innerHTML = `
        <button class="btn" onclick="deleteComponent(this)">Delete</button>
      `;

      let content;
      switch (type) {
        // case 'heading':
        //   content = document.createElement('h2');
        //   content.textContent = 'New Heading';
        //   content.contentEditable = true;
        //   content.style.fontSize = '24px'; // still set a default
        //   content.setAttribute('data-style-fontSize', '24px');
        //   content.style.fontWeight = 'bold';
        //   break;
        // case 'text':
        //   content = document.createElement('p');
        //   content.textContent = 'New text block. Click to edit.';
        //   content.contentEditable = true;
        //   content.style.fontSize = '16px';
        //   break;
        // case 'image':
        //   content = document.createElement('img');
        //   content.src = 'https://images.pexels.com/photos/1591447/pexels-photo-1591447.jpeg';
        //   content.style.width = '100%';
        //   content.style.height = 'auto';
        //   content.style.objectFit = 'cover';
        //   break;
        // case 'button':
        //     const wrapper = document.createElement('div');
        //     const button = document.createElement('button');
        //     button.textContent = 'Click Me';
        //     button.style.padding = '10px 20px';
        //     button.style.fontSize = '16px';
        //     button.style.backgroundColor = '#007bff';
        //     button.style.color = '#fff';
        //     button.style.border = 'none';
        //     button.style.borderRadius = '4px';
        //     button.style.cursor = 'pointer';

        //     wrapper.appendChild(button);
        //     wrapper.style.textAlign = 'center';

        //     content = wrapper; // Important!
        //     break;

        case 'section-title':
            content = document.createElement('h3');
            content.textContent = 'Section Title';
            content.contentEditable = true;
            content.style.fontWeight = 'bold';
            content.style.fontSize = '20px';
        break;

        case 'divider':
            content = document.createElement('hr');
            content.style.border = '1px solid #ccc';
        break;

        case 'site-banner':
            content = document.createElement('img');
            content.src = 'https://via.placeholder.com/800x200?text=Site+Banner';
            content.style.width = '100%';
            content.style.height = 'auto';
            content.style.objectFit = 'cover';
        break;

        case 'custom-banner':
            content = document.createElement('div');
            content.style.position = 'relative';
            content.style.width = '100%';
            content.style.display = 'flex';
            content.style.alignItems = 'center';
            content.style.justifyContent = 'center';
            content.style.overflow = 'hidden';
            // Banner image
            const imgCustom = document.createElement('img');
            imgCustom.src = '';
            imgCustom.style.width = '100%';
            imgCustom.style.height = 'auto';
            imgCustom.style.objectFit = 'cover';
            // Overlay text
            const h3Custom = document.createElement('h3');
            h3Custom.textContent = 'Custom Banner Title';
            h3Custom.contentEditable = true;
            h3Custom.style.position = 'absolute';
            h3Custom.style.top = '50%';
            h3Custom.style.left = '50%';
            h3Custom.style.transform = 'translate(-50%, -50%)';
            h3Custom.style.margin = '0';
            h3Custom.style.color = 'white';
            h3Custom.style.textShadow = '0 2px 8px rgba(0,0,0,0.5)';
            h3Custom.style.fontSize = '2.2em';
            h3Custom.style.textAlign = 'center';
            h3Custom.style.width = '90%';
            h3Custom.style.pointerEvents = 'auto';
            content.appendChild(imgCustom);
            content.appendChild(h3Custom);
        break;

        case 'gallery':
            content = document.createElement('div');
            content.textContent = 'Gallery Placeholder';
            content.style.border = '1px dashed #ccc';
            content.style.padding = '40px';
            content.style.textAlign = 'center';
        break;

        case 'slider':
            content = document.createElement('div');
            content.textContent = 'Slider Placeholder';
            content.style.border = '1px dashed #ccc';
            content.style.padding = '40px';
            content.style.textAlign = 'center';
        break;

        case 'visitor-upload':
            content = document.createElement('div');
            const uploadInput = document.createElement('input');
            uploadInput.type = 'file';
            uploadInput.multiple = true;
            uploadInput.accept = 'image/*';
            content.appendChild(uploadInput);
        break;

        case 'video':
            content = document.createElement('div');
            content.innerHTML = `
                <div class="component-controls">
                <button class="btn" onclick="deleteComponent(this)">Delete</button>
                </div>
                <div class="video-container"></div>
            `;
        break;

        case 'faq':
            content = document.createElement('div');
            content.textContent = 'FAQ Placeholder';
            content.style.border = '1px dashed #ccc';
            content.style.padding = '40px';
            content.style.textAlign = 'center';
        break;

        case 'display-assets':
            content = document.createElement('div');
            content.textContent = 'Display Assets Placeholder';
            content.style.border = '1px dashed #ccc';
            content.style.padding = '40px';
            content.style.textAlign = 'center';
        break;

        case 'cards':
            content = document.createElement('div');
            content.textContent = 'Cards Placeholder';
            content.style.border = '1px dashed #ccc';
            content.style.padding = '40px';
            content.style.textAlign = 'center';
        break;

        case 'full-width-text-image':
            content = document.createElement('div');
            content.innerHTML = `
                <h3 contenteditable="true" style="margin-bottom: 10px;">Full Width Title</h3>
                <p contenteditable="true">This block takes the entire section and screen width.</p>
                <img src="https://via.placeholder.com/1200x400" style="width:100%; height:auto; object-fit: cover;" />
            `;
        break;

        case 'alert-message':
            content = document.createElement('div');
            content.textContent = 'Alert Message';
            content.style.backgroundColor = '#fdecea';
            content.style.color = '#b91c1c';
            content.style.padding = '15px';
            content.style.borderRadius = '4px';
            content.style.border = '1px solid #fca5a5';
            content.contentEditable = true;
        break;

        case 'event-countdown':
            content = document.createElement('div');
            // Store countdown data per component
            content._countdownData = {
                date: '2025-04-30T00:00',
                label: 'Remaining to Apr 30, 2025 (00:00 PST)'
            };
            content.renderCountdown = function() {
                const { date, label } = content._countdownData;
                content.innerHTML = `
                <div class="timer text-center mt-5">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="mx-3"><h1 id="months" class="display-4">0</h1><p>Months</p></div>
                        <div class="mx-3"><h1 id="days" class="display-4">0</h1><p>Days</p></div>
                        <div class="mx-3"><h1 id="hours" class="display-4">0</h1><p>Hours</p></div>
                        <div class="mx-3"><h1 id="minutes" class="display-4">0</h1><p>Minutes</p></div>
                        <div class="mx-3"><h1 id="seconds" class="display-4">0</h1><p>Seconds</p></div>
                    </div>
                    <p style="font-size: .8em;">${label}</p>
                </div>
                `;
                // Start/update timer
                if (content._timerInterval) clearInterval(content._timerInterval);
                function updateTimer() {
                    const now = new Date();
                    const target = new Date(date);
                    let diff = target - now;
                    let months = 0, days = 0, hours = 0, minutes = 0, seconds = 0;
                    if (diff > 0) {
                        // Calculate months
                        let tempNow = new Date(now);
                        months = (target.getFullYear() - tempNow.getFullYear()) * 12 + (target.getMonth() - tempNow.getMonth());
                        tempNow.setMonth(tempNow.getMonth() + months);
                        if (tempNow > target) {
                            months--;
                            tempNow.setMonth(tempNow.getMonth() - 1);
                        }
                        // Remaining diff after months
                        let ms = target - tempNow;
                        days = Math.floor(ms / (1000 * 60 * 60 * 24));
                        ms -= days * (1000 * 60 * 60 * 24);
                        hours = Math.floor(ms / (1000 * 60 * 60));
                        ms -= hours * (1000 * 60 * 60);
                        minutes = Math.floor(ms / (1000 * 60));
                        ms -= minutes * (1000 * 60);
                        seconds = Math.floor(ms / 1000);
                    }
                    content.querySelector('#months').textContent = months;
                    content.querySelector('#days').textContent = days;
                    content.querySelector('#hours').textContent = hours;
                    content.querySelector('#minutes').textContent = minutes;
                    content.querySelector('#seconds').textContent = seconds;
                }
                updateTimer();
                content._timerInterval = setInterval(updateTimer, 1000);
            };
            content.renderCountdown();
        break;

        case 'event-information':
            content = document.createElement('div');
            // Default data for event info
            content._eventInfoData = content._eventInfoData || {
                date: '2025-05-18',
                address: '950 Rue Ottawa, Montréal, QC, CA, H3C 1S4',
                time: '21:43',
                mapEmbed: 'https://www.google.com/maps?q=950+Rue+Ottawa,+Montréal,+QC,+H3C+1S4&output=embed',
                showMap: true,
                mapPosition: 'right' // up, down, left, right
            };
            content.renderEventInfo = function() {
                const { date, address, time, mapEmbed, showMap, mapPosition } = content._eventInfoData;
                // Info block HTML
                const infoHtml = `
                <div class="icons">
                    <div class="row gy-3 gy-md-4 row-cols-1 flex-column">
                        <div class="col">
                            <div class="row gy-3 justify-content-center text-center text-">
                                <div class="col-md-4 col-xl-2">
                                    <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="fa-solid fa-calendar-days fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                        <h4 class="fs-1.5 fw-light mb-1">When</h4>
                                        <p class="fs-.75 opacity-75 fw-light">${date}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-3">
                                    <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="fa-solid fa-signs-post fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                        <h4 class="fs-1.5 fw-light mb-1">Where</h4>
                                        <p class="fs-.75 opacity-75 fw-light">${address}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-2">
                                    <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="fa-solid fa-clock fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                        <h4 class="fs-1.5 fw-light mb-1">Time</h4>
                                        <p class="fs-.75 opacity-75 fw-light">${time} PST</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                // Map HTML
                const mapHtml = showMap ? `<div class="event-map" style="margin:16px 0;"><iframe class="map embed-responsive-item rounded border border-2 border-" style="height: 300px; width: 100%; position: relative; overflow: hidden;" src="${mapEmbed}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>` : '';
                // Layout by mapPosition
                let finalHtml = '';
                if (showMap) {
                    if (mapPosition === 'up') {
                        finalHtml = mapHtml + infoHtml;
                    } else if (mapPosition === 'down') {
                        finalHtml = infoHtml + mapHtml;
                    } else if (mapPosition === 'left') {
                        finalHtml = `<div style='display:flex;gap:24px;align-items:flex-start;'><div style='flex:1;max-width:50%'>${mapHtml}</div><div style='flex:1;'>${infoHtml}</div></div>`;
                    } else {
                        finalHtml = `<div style='display:flex;gap:24px;align-items:flex-start;'><div style='flex:1;'>${infoHtml}</div><div style='flex:1;max-width:50%'>${mapHtml}</div></div>`;
                    }
                } else {
                    finalHtml = infoHtml;
                }
                content.innerHTML = finalHtml;
            };
            content.renderEventInfo();
        break;

        case 'sell-tickets':
            content = document.createElement('div');
            content.innerHTML = `<h4 contenteditable="true">Buy Tickets</h4><button class="btn">Buy Now</button>`;
        break;
        case 'whos-coming':
            content = document.createElement('ul');
            content.innerHTML = `<li>Person A</li><li>Person B</li>`;
        break;
        case 'donation-form':
            content = document.createElement('div');
            content.innerHTML = `
            <section class="text- bg- section-border- " id="b2dd141f-e084-45c7-ba93-d8b6158d65af" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-086fc842-f2e9-4d56-af2e-be42317d11e7"
                        data-block="" data-template="7e729e7e3c534cbf918a45b5540afa84"
                        data-action="https://gmu-events.com/ajax/block/b2dd141f-e084-45c7-ba93-d8b6158d65af/086fc842-f2e9-4d56-af2e-be42317d11e7"
                        style="margin-top: 3rem;">


                        <form method="POST" action="/donation" class="donation-form-block">
                            @csrf
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                                <div class="card border-primary shadow" style="border-width: 3px; border-color: #2e4053 !important;">
                                    <div class="card-header bg-primary border-primary rounded-0 text-center text-white fs-2"
                                        style="border-width: 3px; border-color: #2e4053 !important; background-color: #2e4053 !important;">
                                        Make a general donation
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="profile_uuid" value="">

                                        <input type="hidden" name="team_uuid" value="">

                                        <div class="row gy-3">
                                            <div
                                                class="col-12 d-flex flex-column justify-content-center align-items-center">
                                                <label
                                                    for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b2"
                                                    class="form-label " style="color: #000; font-weight: bold;">
                                                    Donate To the SHPS PTO
                                                </label>
                                                <div></div>

                                                <div class="d-flex justify-content-center flex-wrap">
                                                    <input type="radio" data-change-amount="1"
                                                        data-name="4e963109-9506-49a8-b609-a0929944c1b2" data-amount="500"
                                                        class="form-check btn-check select-amount"
                                                        name="question_4e963109-9506-49a8-b609-a0929944c1b2"
                                                        id="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63"
                                                        value="4479f3e5-aac8-4044-ac77-7c3192197e63" autocomplete="off">
                                                    <label class="btn btn-outline-primary m-1"
                                                    style="color: #2e4053 !important; border-color: #2e4053 !important;"
                                                        for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63">Donate
                                                        to the PTO</label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-right-width: 0; border-color: #2e4053 !important;">$</span>
                                                    <input type="number" placeholder="0"
                                                        class="form-control fs-2 fs-lg-4 text-center border-primary"
                                                        style="border-width: 2px; border-color: #2e4053 !important;" name="donation_amount" value="">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-left-width: 0; border-color: #2e4053 !important;">.00</span>
                                                </div>
                                                <input type="hidden" name="amount" value="">
                                                <div class="text-center">
                                                    <small class="form-text text-muted">
                                                        * The minimum donation amount is 8.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center align-items-center">
                                                <div class="card border-primary shadow p-2" style="border-width: 2px; border-color: #2e4053 !important;">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="pay_fees" name="pay_fees" checked="">
                                                        <label class="form-check-label fw-semibold" for="pay_fees">
                                                            I elect to pay the fees
                                                        </label>
                                                        <i role="button"
                                                            class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                            data-title="I elect to pay the fees"
                                                            data-description="By selecting this option, you elect to pay the credit card and transaction fees for this donation.The fees will be displayed in the next step."></i>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <label for="first_name" class="form-label fw-semibold required">
                                                    First name
                                                </label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="">
                                            </div>

                                            <div class="col-12">
                                                <label for="last_name" class="form-label fw-semibold required">
                                                    Last name
                                                </label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="">
                                            </div>


                                            <div class="col-12">
                                                <label for="email" class="form-label fw-semibold required">
                                                    Email address
                                                </label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    value="">
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="anonymous_donation" name="anonymous_donation">
                                                    <label class="form-check-label fw-semibold" for="anonymous_donation">
                                                        Anonymous
                                                    </label>
                                                    <i role="button"
                                                        class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                        data-title="Anonymous"
                                                        data-description="Selecting this option will hide your name from everyone but the organizer."></i>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                                    comment
                                                </label>
                                                <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="hear_from_myevent_086fc842-f2e9-4d56-af2e-be42317d11e7"
                                                        name="hear_from_myevent">
                                                    <label class="form-check-label"
                                                        for="hear_from_myevent_086fc842-f2e9-4d56-af2e-be42317d11e7">Hear
                                                        from MyEvent</label>
                                                    <i role="button"
                                                        class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                        data-title="Hear from MyEvent"
                                                        data-description="In compliance with the new Anti-Spam CASL legislation, we need your permission to continue communicating
with you. Please confirm your interest in hearing from MyEvent."></i>
                                                </div>
                                            </div>



                                            <input type="hidden" name="template"
                                                value="7e729e7e3c534cbf918a45b5540afa84">

                                            <div class="col-12">
                                                <small class="text-muted">This form is protected by reCAPTCHA and the
                                                    Google <a href="https://policies.google.com/privacy">Privacy Policy</a>
                                                    and <a href="https://policies.google.com/terms">Terms of Service</a>
                                                    apply.</small>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer bg-primary border-primary rounded-0 p-0"
                                        style="border-width: 3px; border-color: #2e4053 !important;">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg w-100 h-100 text-white rounded-0 shadow-none" style="background: #2e4053 !important; border-color: #2e4053 !important;">
                                            Donate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>`;
        break;
        case 'donor-list':
            content = document.createElement('div');
            content.innerHTML = `<div class="col-12 mt-4">
                <table id="studentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $donation = App\Models\Donation::all();
                        @endphp
                        @foreach($donation->chunk(3) as $donate)
                            <tr>
                                @foreach($donate as $don)
                                    <td>
                                        <div class="col-lg-12" style="font-size: 12px;">
                                            <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">


                                                <h4 class="fw-semibold">
                                                    $ {{ $don->amount }}
                                                </h4>

                                                <small class="d-block opacity-75 mt-2">
                                                    <span title="Donor">{{ $don->first_name }} {{ $don->last_name }}</span>
                                                                            <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                                        <span title="Participant">{{ $don->user->name }}</span>
                                                                    </small>


                                                <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                                    <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                                    {{ $don->created_at->format('M d, Y') }}
                                                </small>

                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>`;
        break;
        case 'donation-slider':
            content = document.createElement('div');
            content.innerHTML = `<input type="range" min="0" max="1000" value="500">`;
        break;
        case 'custom-form':
            content = document.createElement('div');
            // Default form fields if not present
            content._customFormFields = content._customFormFields || [
                { label: 'Field 1', type: 'text', name: 'field1', value: '' },
                { label: 'Email', type: 'email', name: 'email', value: '' }
            ];
            content.renderCustomForm = function() {
                let formHtml = `<form class='dynamic-custom-form' onsubmit='event.preventDefault();'>`;
                content._customFormFields.forEach((field, idx) => {
                    formHtml += `<div class='mb-2'>
                        <label>${field.label}${field.required ? ' <span style=\'color:red\'>*</span>' : ''}</label>`;
                    if(field.type === 'textarea') {
                        formHtml += `<textarea name='${field.name}' class='form-control' data-idx='${idx}' ${field.required ? 'required' : ''}>${field.value || ''}</textarea>`;
                    } else {
                        formHtml += `<input type='${field.type}' name='${field.name}' value='${field.value || ''}' class='form-control' data-idx='${idx}' ${field.required ? 'required' : ''}/>`;
                    }
                    // formHtml += `<button type='button' class='btn btn-sm btn-danger ms-2' onclick='removeCustomFormField(this, ${idx})'>Remove</button>`;
                    formHtml += `</div>`;
                });
                // formHtml += `<button type='button' class='btn btn-sm btn-primary mt-2' onclick='addCustomFormField(this)'>Add Field</button>`;
                formHtml += `<button type='submit' class='btn btn-success mt-2 ms-2'>Submit</button>`;
                formHtml += `</form>`;
                content.innerHTML = formHtml;
            };
            content.renderCustomForm();
        break;
        case 'contact-form':
            content = document.createElement('form');
            content.innerHTML = `
                                <section class="text- bg- section-border- " id="23c0fa9f-1b3e-4ac9-88a8-ac7e0b9ef0d8" data-section=""
            style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
            <div class="block-container container " id="block-c55189a5-30b0-4b5d-93fa-c09ba3ea7ae4" data-block=""
                data-template="38b2386a3ff24269986eb67b1a7316ae"
                data-action="https://gmu-events.com/ajax/block/23c0fa9f-1b3e-4ac9-88a8-ac7e0b9ef0d8/c55189a5-30b0-4b5d-93fa-c09ba3ea7ae4"
                style="--block-name:''">


                <div class="p-4

col-12 col-xl-6 col-lg-7 col-md-9 mx-auto
">

                    <div class="row align-items-center gy-3 gy-md-4">
                        <div class="col-">
                            <div class="row row-cols-1 gy-3">







                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="block-container container " id="block-0b11d839-1966-464e-b7e2-8f30bdd5d69d" data-block=""
                data-template="e7d0b613d125406ea714907d6507c2a9"
                data-action="https://gmu-events.com/ajax/block/23c0fa9f-1b3e-4ac9-88a8-ac7e0b9ef0d8/0b11d839-1966-464e-b7e2-8f30bdd5d69d"
                style="--block-name:''">


                <div class="form-submission">
                    <form method="POST" action="/contact-form">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label fw-semibold">
                                            Your name
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold">
                                            Email address
                                        </label>
                                        <input type="text" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold">
                                            Message
                                        </label>
                                        <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                                    </div>

                                    <input type="hidden" name="template" value="e7d0b613d125406ea714907d6507c2a9">

                                    <div class="col-12">
                                        <small class="text-muted">This form is protected by reCAPTCHA and the Google <a
                                                href="https://policies.google.com/privacy" style="color: #2e4053">Privacy Policy</a>
                                            and <a href="https://policies.google.com/terms" style="color: #2e4053">Terms of Service</a>
                                            apply.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mt-md-4">
                            <button type="submit" class="btn btn-primary btn-lg text-white" style="background-color: #2e4053; border-color: #2e4053">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>`;
        break;
        case 'social-share':
            content = document.createElement('div');
            content.innerHTML = `
                @php
                        $url = url()->current();
                        $doamin = parse_url($url, PHP_URL_HOST);
                        $check = App\Models\Website::where('domain', $doamin)->first();
                        $user_id = $check->user_id ?? null;
                        $setting = App\Models\Setting::where('user_id', $user_id)->first();
                @endphp
            <section class="text- bg- section-border- " id="a62f69b9-8d0f-4213-b070-a977a437c020" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-406491f2-28a9-46fa-be92-5ba2842c8b73" data-block=""
                        data-template="f60cc48059a24febb0a7cb603b78845d"
                        data-action="{{ $setting->facebook ?? null}}"
                        style="--block-name:''">


                        <h2 class="display-5 fw-normal text-center">
                            I Just Want to Help!
                        </h2>
                    </div>
                </section>
                <section class="text- bg- section-border- " id="facb2c3e-5c13-4096-90e0-30de8e263ba8" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: 0%; --section-name: '';">
                    <div class="block-container container " id="block-a24d795a-5479-4e64-8111-729e5a6fd2d5" data-block=""
                        data-template="f397b6192371496897c61c21339f90a0"
                        data-action="{{ $setting->linkedin ?? null}}"
                        style="--block-name:''">


                        <div class="row gy-3 gy-md-5 justify-content-center align-items-center">

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="d-flex justify-content-center align-items-center">

                                    <a class="text-center btn-facebook-share" href="#" role="button"
                                        data-title="The SHPS PTO Fundraiser 2025" data-url="{{ $setting->facebook ?? null}}" style="color: #3b5998">
                                        <i class="fab fa-facebook-square fs-4 text-facebook" role="img"
                                            aria-hidden="true" style="font-size: 4rem !important"></i>

                                        <h4 class="text-dark mt-2 mt-md-3 fs-1.5">
                                            Share on Facebook
                                        </h4>
                                    </a>

                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="d-flex justify-content-center align-items-center">

                                    <a class="text-center btn-linkedin-share" href="#" role="button"
                                        data-title="The SHPS PTO Fundraiser 2025" data-url="{{ $setting->linkedin ?? null}}" style="color: #0077b5">
                                        <i class="fa-brands fa-linkedin fs-4 text-linkedin" role="img"
                                            aria-hidden="true" style="font-size: 4rem !important"></i>

                                        <h4 class="text-dark mt-2 mt-md-3 fs-1.5">
                                            Share on LinkedIn
                                        </h4>
                                    </a>

                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button class="text-center btn btn-link btn-clipboard" type="button" role="button"
                                        data-clipboard-text="{{ $doamin }}">
                                        <i class="fa-solid fa-copy fs-4 text-primary" role="img" aria-hidden="true" style="font-size: 4rem !important; color: #2e4053 !important;"></i>

                                        <h4 class="text-dark mt-2 mt-md-3 fs-1.5">
                                            Copy to clipboard
                                        </h4>
                                    </button>

                                </div>
                            </div>

                        </div>
                    </div>
                </section>`;
        break;
        case 'auth-form':
            content = document.createElement('div');
            // Use the same custom HTML and JS as the public view
            // Assume customAuthFormHtml and customAuthFormJs are available (from builder state or default)
            let customHtml = '';
            let customJs = '';
            if (window.customAuthFormHtml !== undefined) {
                customHtml = window.customAuthFormHtml;
            } else {
                // Default fallback HTML (edit as needed)
                customHtml = `<div class="row">
            <div class="col-md-12 mt-4 mb-4 text-center">
                <i class="fa-solid fa-circle-user fa-fw text-primary mb-3" aria-hidden="true" style="font-size: 8rem; color: #2e4053 !important;"></i>
                <h2 class="display-6 tit">Register</h2>
            </div>
        </div>
        <div class="register">
            <div class="container">
                <form action="/register" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First name</label>
                            <input type="text" class="form-control" id="first_name" name="name">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Last name</label>
                            <input type="text" class="form-control" id="first_name" name="last_name">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="first_name" name="email">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Confirm email address</label>
                            <input type="email" class="form-control" id="first_name" name="confirm_email">
                        </div>
                    </div>
                    <!-- Add this to your register form in the auth-form component -->
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="register_as" class="form-label">Register as</label>
                            <select class="form-select" id="register_as" name="register_as" onchange="toggleGroupSelect(this)">
                                <option value="individual">Individual</option>
                                <option value="group">Group Member</option>
                                <option value="group_leader">Group Leader</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="group_select_wrapper" style="display:none;">
                            <label for="group_id" class="form-label">Select Group</label>
                            <select class="form-select" id="group_id" name="group_id">
                                <option value="">Select a group</option>
                                <!-- Dynamically populate this with your groups -->
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Password</label>
                            <input type="password" class="form-control" id="first_name" name="password">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Confirm password</label>
                            <input type="password" class="form-control" id="first_name" name="confirm_password">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="d-grid gap-3 mt-2">
                                <button class="btn btn-primary btn-lg text-white" type="submit" style="background-color: #2e4053 !important; border-color: transparent;">
                                    <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                    Register
                                </button>
                                <button class="btn text-primary btn-lg p-0 shadow-none view-login-form" type="button" style="color: #2e4053 !important;">
                                    Login
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="login" style="display: none;">
            <div class="container">
                <form action="/login" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="first_name" name="email">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Password</label>
                            <input type="password" class="form-control" id="first_name" name="password">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="d-grid gap-3 mt-2">
                                <button class="btn btn-primary btn-lg text-white" type="submit" style="background-color: #2e4053 !important; border-color: transparent;">
                                    <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                    Login
                                </button>
                                <button class="btn text-primary btn-lg p-0 shadow-none view-register-form" type="button" style="color: #2e4053 !important;">
                                    Register
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>`;
            }
            if (window.customAuthFormJs !== undefined) {
                customJs = window.customAuthFormJs;
            }
            content.innerHTML = customHtml;
            // Inject custom JS for live preview (sandboxed)
            if (customJs) {
                const script = document.createElement('script');
                script.type = 'text/javascript';
                script.textContent = customJs;
                content.appendChild(script);
            }
        break;
        case 'student-leaderboard':
            content = document.createElement('ol');
            content.innerHTML = `<div class="col-md-12 mt-4">
                @php
                    $st = App\Models\User::limit(10)->where('role','user')->get();
                @endphp

                @foreach($st as $student)
                    <div class="col-lg-12" style="font-size: 12px;">
                        <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                            style="width: 100%; max-width: 580px; margin-inline: auto; background: #ebebeb;">
                            <div class="row gy-3 ">
                                <div class="col-lg-3 d-flex align-items-center">
                                    <span style="font-size: 1.5rem !important; font-weight: bold; margin-right: 1rem;">1</span>
                                    <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                        <img src="{{ asset($student->photo) }}" style="border-radius: 50%; width: 70px; min-width: 70px; height: 70px; min-height: 70px;">
                                    </div>
                                </div>

                                <div class="col-lg-7 d-flex flex-column justify-content-center" style="margin-top: 0px !important;">
                                    <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                        {{ $student->name }}
                                    </h2>

                                    {{-- <span class="opacity-75 text-center text-lg-start mt-2"></span> --}}

                                    <div class="progress" role="progressbar" aria-valuenow="{{ $student->donations->sum('amount') }}"
                                        aria-valuemin="0" aria-valuemax="{{ $student->goal }}" data-primary-color="#2e4053"
                                        data-secondary-color="#28a745" data-duration="5"
                                        data-goal-reached="true" style="height: 14px">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                            style="width: 100%; background-color: #28a745 !important;" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                                <i class="fa-solid fa-award fa-2xl fa-fw position-absolute" aria-hidden="true" style="color: #FFDf01; top: 30px; right: 25px; font-size: 2.5rem !important;"></i>
                                <span class="small fw-bold" style="top: 57px; position: relative; left: -36px; right: unset; font-size: 0.74rem; color: #000;">
                                    $ {{ $student->donations->sum('amount') }}
                                </span>
                            </span>
                            <a href="{{ env('APP_URL') }}/student/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}"
                                class="stretched-link" target=""></a>
                        </div>
                    </div>

                @endforeach
            </div>
            <div class="col-md-12 mt-4">
                <p class="lead text-center mt-3">
                    @php
                        $count = App\Models\Donation::count();
                    @endphp
                    {{ $count }} donations have been made to this site
                </p>
            </div>`;
        break;
        case 'student-listing':
            content = document.createElement('div');
            content.innerHTML = `
            <div class="col-12 col-md-11 col-lg-9 col-xl-7 d-flex align-items-center" style="margin: auto;">
                <div class="input-group input-group-lg">
                    <span class="input-group-text">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                </div>
            </div>
            <div class="col-12 mt-4">
                <table id="studentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $students = App\Models\User::limit(10)->where('role','user')->get();
                        @endphp
                        @foreach ($students->chunk(2) as $item)

                        <tr>
                            @foreach ($item as $student)
                            <td>
                                <div class="col-lg-12" style="font-size: 12px;">
                                    <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                                        style="width: 100%; max-width: 580px; margin-inline: auto;">
                                        <div class="row gy-3 ">
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                                    <img src="{{ asset($student->photo) }}" style="width: 80px; min-width: 80px; height: 80px; min-height: 80px;">
                                                </div>
                                            </div>

                                            <div class="col-lg-9 d-flex flex-column justify-content-center">
                                                <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                                    {{ $student->name }}
                                                </h2>
                                                <span class="opacity-75 text-center text-lg-start mt-2"></span>
                                                <div class="progress mt-3" role="progressbar" aria-valuenow="{{ $student->donations->sum('amount') }}"
                                                    aria-valuemin="0" aria-valuemax="{{ $student->goal }}" data-primary-color="#2e4053"
                                                    data-secondary-color="#b7bcc4" data-duration="5"
                                                    data-goal-reached="true" style="height: 6px">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                                        style="width: 100%">
                                                    </div>
                                                </div>
                                                <span class="fw-semibold d-block text-center mt-2">
                                                    @php
                                                        $to = $student->donations->sum('amount');
                                                    @endphp
                                                    ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $student->goal ?? 0}} <small
                                                        class="opacity-75 fw-light">raised</small>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                                            Last updated {{ $student->updated_at->diffForHumans() }}
                                        </span>
                                        <a href="{{ env('APP_URL') }}/student/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}"
                                            class="stretched-link" target="_blank"></a>
                                    </div>
                                </div>
                            </td>
                            @endforeach
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            `;
        break;
        case 'updates':
            content = document.createElement('div');
            content.innerHTML = `<h4 contenteditable="true">Update Title</h4><p contenteditable="true">Update content...</p>`;
        break;
        case 'facebook-comments':
            content = document.createElement('div');
            content.innerHTML = `<p>Facebook Comments Plugin</p>`;
        break;
        case 'sponsorships':
            content = document.createElement('div');
            // Example: sponsors is an array of sponsor objects with an image property
            // You should fetch or pass this array to your builder context
            const sponsors = window.currentSponsors || []; // Replace with your actual data source

            let html = `<h4>Sponsors</h4><div class="row justify-content-center align-items-center g-4">`;
            if (sponsors.length) {
                sponsors.forEach(sponsor => {
                    html += `
                        <div class="col-6 col-md-3 text-center">
                            <img src="${sponsor.image}" alt="Sponsor" class="img-fluid rounded shadow-sm" style="max-height:100px;object-fit:contain;">
                        </div>
                    `;
                });
            } else {
                html += `<div class="col-12 text-center text-muted">No sponsors found.</div>`;
            }
            html += `</div>`;
            content.innerHTML = html;
        break;
        case 'contact-us':
            content = document.createElement('div');
            content.innerHTML = `<h4>Contact Us</h4><p>Email: example@example.com</p>`;
        break;
        case 'site-goal':
            content = document.createElement('div');
            content.className = 'thermometer';
            // Default data for thermometer
            content._goalData = {
                goal: 5000,
                raised: 500,
                ticks: [1250, 2500, 4000]
            };
            content.renderThermometer = function() {
                const { goal, raised, ticks } = content._goalData;
                // Responsive: set bar to 100% width, fill will be set after render
                content.innerHTML = `
                <div class="thermometer-wrapper">
                    <div class="bulb"><div class="bulb-inner"></div></div>
                    <div class="bar" style="width:100%;position:relative;min-width:120px;max-width:100%;">
                        <div class="fill" id="fill" style="height:100%;position:absolute;left:0;top:8px; margin-left: 2rem;"></div>
                        <div class="label goal-label" id="goal-label">Goal: $${goal}</div>
                        <div class="label raised-label" id="raised-label">Raised: $${raised}</div>
                    </div>
                    <div class="ticks">
                        ${ticks.map(t => `<div class='tick'>$${t}</div>`).join('')}
                    </div>
                </div>
                `;
                // After render, set fill width responsively
                setTimeout(() => {
                    const bar = content.querySelector('.bar');
                    const fill = content.querySelector('.fill');
                    if (bar && fill) {
                        const barRect = bar.getBoundingClientRect();
                        const barWidth = barRect.width;
                        const percent = Math.min(raised / goal, 1);
                        fill.style.width = (barWidth * percent) + 'px';
                        fill.style.background = '#6f7c8b';
                        fill.style.borderRadius = '8px';
                        fill.style.transition = 'width 0.4s';
                        fill.style.height = '16px';
                    }
                }, 10);
                // Add resize observer for responsiveness
                if (!content._resizeObs) {
                    content._resizeObs = new ResizeObserver(() => content.renderThermometer());
                    content._resizeObs.observe(content);
                }
            };
            content.renderThermometer();
        break;
        case 'text-images':
            content = document.createElement('div');
            content.className = 'text-images-component';
            // Default state
            content._textImagesData = {
                text: 'Your text here',
                imgSrc: 'https://via.placeholder.com/400x250',
                imgPosition: 'left', // up, down, left, right
                imgSize: 200, // px
                showImage: true // NEW: image visibility
            };
            content.renderTextImages = function() {
                const { text, imgSrc, imgPosition, imgSize, showImage } = content._textImagesData;
                let layout = '';
                const imgTag = showImage && imgSrc ? `<img src='${imgSrc}' style='max-width:100%;width:${imgSize}px;'/>` : '';
                if (imgPosition === 'up') {
                    layout = `${imgTag ? `<div style='text-align:center;'>${imgTag}</div>` : ''}<div style='text-align:center;'><p contenteditable='true' oninput='updateTextImagesField(this.innerText, "text")' style='margin:0;'>${text}</p></div>`;
                } else if (imgPosition === 'down') {
                    layout = `<div style='text-align:center;'><p contenteditable='true' oninput='updateTextImagesField(this.innerText, "text")' style='margin:0;'>${text}</p></div>${imgTag ? `<div style='text-align:center;'>${imgTag}</div>` : ''}`;
                } else if (imgPosition === 'right') {
                    layout = `<div style='display:flex;align-items:center;gap:16px;'><div style='flex:1;'><p contenteditable='true' oninput='updateTextImagesField(this.innerText, "text")' style='margin:0;'>${text}</p></div>${imgTag}</div>`;
                } else {
                    layout = `<div style='display:flex;align-items:center;gap:16px;'>${imgTag}<div style='flex:1;'><p contenteditable='true' oninput='updateTextImagesField(this.innerText, "text")' style='margin:0;'>${text}</p></div></div>`;
                }
                content.innerHTML = layout;
            };
            content.renderTextImages();
        break;
      }

      component.appendChild(controls);
      component.appendChild(content);

      // Add click handler for selection
      component.addEventListener('click', (e) => {
        e.stopPropagation();
        selectComponent(component);
      });

      return component;
    }

    // Create a new dropzone
    function createDropzone() {
      const dropzone = document.createElement('div');
      dropzone.className = 'dropzone';
      dropzone.textContent = 'Drop components here';
      return dropzone;
    }

    // Delete a component
    function deleteComponent(btn) {
      const component = btn.closest('.component');
      component.remove();

      // If there's no dropzone after this component, add one
      const dropzones = document.querySelectorAll('.dropzone');
      if (dropzones.length === 0) {
        const page = document.getElementById('page');
        page.appendChild(createDropzone());
      }

      // Clear properties panel if this was the selected component
      if (selectedComponent === component) {
        selectedComponent = null;
        updatePropertyPanel();
      }
    }

    // Select a component
    function selectComponent(component) {
      // Deselect previous component
      if (selectedComponent) {
        selectedComponent.classList.remove('selected');
      }

      lastSelectedComponent = component; // Backup

      // Select new component
      selectedComponent = component;
      component.classList.add('selected');

      // Update property panel
      updatePropertyPanel();
    }

    // Update property panel based on component type
    function updatePropertyPanel() {
        const propertyControls = document.getElementById('propertyControls');

        if (!selectedComponent) {
            propertyControls.innerHTML = '<p>Select a component to edit its properties</p>';
            return;
        }

        const content = getContentElement(selectedComponent);
        const type = selectedComponent.dataset.type;
        let specificControls = '';

        switch (type) {
            case 'image':
            specificControls = `
                <div class="form-group">
                <label>Upload Image</label>
                <input type="file" accept="image/*" onchange="uploadImage(event)">
                <img src="${content.src}" class="image-preview">
                </div>
                <div class="form-group">
                <label>Object Fit</label>
                <select oninput="updateStyle(this, 'objectFit')">
                    <option value="cover" ${content.style.objectFit === 'cover' ? 'selected' : ''}>Cover</option>
                    <option value="contain" ${content.style.objectFit === 'contain' ? 'selected' : ''}>Contain</option>
                    <option value="fill" ${content.style.objectFit === 'fill' ? 'selected' : ''}>Fill</option>
                </select>
                </div>
            `;
            break;

            case 'button':
            specificControls = `
                <div class="form-group">
                <label>Alignment</label>
                <select oninput="updateButtonAlignment(this)">
                    <option value="center" ${content.parentElement.style.textAlign === 'center' ? 'selected' : ''}>Center</option>
                    <option value="left" ${content.parentElement.style.textAlign === 'left' ? 'selected' : ''}>Left</option>
                    <option value="right" ${content.parentElement.style.textAlign === 'right' ? 'selected' : ''}>Right</option>
                </select>
                </div>
            `;
            break;

            case 'text':
            specificControls = `
                <div class="form-group">
                <label>Content</label>
                <textarea oninput="updateContent(this.value)">${content.textContent}</textarea>
                </div>
            `;
            break;

            case 'heading':
            specificControls = `
                <div class="form-group">
                <label>Heading Level</label>
                <select oninput="updateHeadingLevel(this.value)">
                    <option value="h1" ${content.tagName === 'H1' ? 'selected' : ''}>H1</option>
                    <option value="h2" ${content.tagName === 'H2' ? 'selected' : ''}>H2</option>
                    <option value="h3" ${content.tagName === 'H3' ? 'selected' : ''}>H3</option>
                    <option value="h4" ${content.tagName === 'H4' ? 'selected' : ''}>H4</option>
                </select>
                </div>
            `;
            break;

            case 'section-title':
            specificControls = `
                <div class="form-group">
                <label>Section Title</label>
                <input type="text" value="${content.textContent}" oninput="updateContent(this.value)">
                </div>
            `;
            break;

            case 'divider':
            specificControls = `
                <div class="form-group">
                <label>Divider Thickness</label>
                <input type="text" value="${content.style.height || '2px'}" oninput="updateStyle(this, 'height')">
                </div>
                <div class="form-group">
                <label>Color</label>
                <input type="color" value="${rgbToHex(content.style.backgroundColor || '#000000')}" oninput="updateStyle(this, 'backgroundColor')">
                </div>
            `;
            break;

            case 'site-banner':
            case 'custom-banner':
            specificControls = `
                <div class="form-group">
                <label>Banner Image</label>
                <input type="file" accept="image/*" onchange="uploadBannerImage(event)">
                </div>
                <div class="form-group">
                <label>Banner Text</label>
                <input type="text" oninput="updateBannerText(this.value)"
                </div>
                <div class="form-group">
                <label>Banner Alt Text</label>
                <input type="text" value="${content.alt || ''}" oninput="updateAltText(this.value)">
                </div>
            `;
            break;

            case 'gallery':
            case 'slider':
            // Default to 1 if not set
            const slidesToShow = content.dataset.slidesToShow ? parseInt(content.dataset.slidesToShow, 10) : 1;
            specificControls = `
                <div class="form-group">
                    <label>Manage Images</label>
                    <button onclick="openImageManager()">Edit Gallery</button>
                </div>
                <div class="form-group">
                    <label>Slides to Show</label>
                    <input type="number" min="1" max="10" value="${slidesToShow}" oninput="updateSliderSlidesToShow(this)">
                </div>
            `;
            break;

            case 'visitor-upload':
            specificControls = `
                <div class="form-group">
                <label>Upload Label</label>
                <input type="text" value="${content.textContent}" onChange="updateContent(this.value)">
                </div>
            `;
            break;

            case 'video':
                const videoContainer = content.querySelector('.video-container');
                specificControls = `
                    <div class="form-group">
                    <label>Video URL</label>
                    <input type="text" value="" oninput="updateVideoEmbed(this.value)">
                    </div>
                `;
                // Show current embed preview
                specificControls += `
                    <div class="form-group">
                    <label>Preview</label>
                    <div class="video-preview">${videoContainer.innerHTML}</div>
                    </div>
                `;
            break;

            case 'faq':
                specificControls = `
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" id="faq_status" name="faq_status">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Number of questions</label>
                        <select class="form-select" id="faq_number_of_questions" name="faq_number_of_questions">
                            ${Array.from({length: 20}, (_, i) => `<option value="${i+1}">${i+1}</option>`).join('')}
                        </select>
                    </div>
                    <div id="faq_entries"></div>
                `;
                if (type === 'faq') {
                    const numSelect = document.getElementById('faq_number_of_questions');
                    if (numSelect) {
                        numSelect.addEventListener('change', function() {
                            renderFaqEntries(this.value);
                        });
                        renderFaqEntries(numSelect.value || 1);
                    }
                }
            break;
            case 'display-assets':
            specificControls = `
                <div class="form-group">
                <label>Add Files</label>
                <button onclick="openFileManager()">Manage Files</button>
                </div>
            `;
            break;

            case 'cards':
            specificControls = `
                <div class="form-group">
                <label>Manage Cards</label>
                <button onclick="openCardEditor()">Edit Cards</button>
                </div>
            `;
            break;

            case 'full-width-text-image':
            specificControls = `
                <div class="form-group">
                <label>Text Content</label>
                <textarea oninput="updateContent(this.value)">${content.textContent}</textarea>
                </div>
                <div class="form-group">
                <label>Background Image</label>
                <input type="file" accept="image/*" onchange="uploadBackgroundImage(event)">
                </div>
            `;
            break;

            case 'alert-message':
            specificControls = `
                <div class="form-group">
                <label>Alert Text</label>
                <input type="text" value="${content.textContent}" oninput="updateContent(this.value)">
                </div>
                <div class="form-group">
                <label>Alert Type</label>
                <select oninput="updateAlertType(this.value)">
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                </select>
                </div>
            `;
            break;
            case 'event-countdown':
            const countdownData = content._countdownData || { date: '', label: '' };
            specificControls = `
                <div class="form-group">
                    <label>Event Date & Time</label>
                    <input type="datetime-local" value="${countdownData.date}" oninput="updateCountdownDate(this.value)">
                </div>
                <div class="form-group">
                    <label>Countdown Label</label>
                    <input type="text" value="${countdownData.label}" oninput="updateCountdownLabel(this.value)">
                </div>
            `;
            break;
            case 'event-information':
            const eventInfoData = content._eventInfoData || { date: '', address: '', time: '', mapEmbed: '', showMap: true, mapPosition: 'right' };
            specificControls = `
                <div class="form-group">
                    <label>Date (When)</label>
                    <input type="date" value="${eventInfoData.date}" oninput="updateEventInfoField(this, 'date')">
                </div>
                <div class="form-group">
                    <label>Address (Where)</label>
                    <textarea oninput="updateEventInfoField(this, 'address')">${eventInfoData.address}</textarea>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <input type="text" value="${eventInfoData.time}" oninput="updateEventInfoField(this, 'time')">
                </div>
                <div class="form-group">
                    <label>Show Map</label>
                    <input type="checkbox" ${eventInfoData.showMap ? 'checked' : ''} onchange="updateEventInfoField(this, 'showMap')">
                </div>
                <div class="form-group">
                    <label>Map Embed URL</label>
                    <input type="text" value="${eventInfoData.mapEmbed}" oninput="updateEventInfoField(this, 'mapEmbed')">
                </div>
                <div class="form-group">
                    <label>Map Position</label>
                    <select oninput="updateEventInfoField(this, 'mapPosition')">
                        <option value="up" ${eventInfoData.mapPosition==='up'?'selected':''}>Up</option>
                        <option value="down" ${eventInfoData.mapPosition==='down'?'selected':''}>Down</option>
                        <option value="left" ${eventInfoData.mapPosition==='left'?'selected':''}>Left</option>
                        <option value="right" ${eventInfoData.mapPosition==='right'?'selected':''}>Right</option>
                    </select>
                </div>
            `;
            break;
            case 'sell-tickets':
                specificControls = `
                    <div class="form-group">
                        <label>Button Text</label>
                        <input type="text" value="${content.querySelector('button') ? content.querySelector('button').textContent : ''}" oninput="content.querySelector('button').textContent = this.value">
                    </div>
                `;
                break;
            case 'whos-coming':
                specificControls = `
                    <div class="form-group">
                        <label>Attendees (comma separated)</label>
                        <input type="text" value="${Array.from(content.querySelectorAll('li')).map(li => li.textContent).join(', ')}" oninput="updateWhosComing(this.value)">
                    </div>
                `;
                break;
            case 'donation-form':
                specificControls = `
                    <div class="form-group">
                        <label>Form Title</label>
                        <input type="text" value="Donation" oninput="updateDonationFormTitle(this.value)">
                    </div>
                `;
                break;
            case 'donor-list':
                specificControls = `
                    <div class="form-group">
                        <label>Donors (format: Name - Amount, comma separated)</label>
                        <input type="text" value="${Array.from(content.querySelectorAll('li')).map(li => li.textContent).join(', ')}" oninput="updateDonorList(this.value)">
                    </div>
                `;
                break;
            case 'donation-slider':
                specificControls = `
                    <div class="form-group">
                        <label>Min Amount</label>
                        <input type="number" value="${content.querySelector('input[type=range]') ? content.querySelector('input[type=range]').min : 0}" oninput="content.querySelector('input[type=range]').min = this.value">
                    </div>
                    <div class="form-group">
                        <label>Max Amount</label>
                        <input type="number" value="${content.querySelector('input[type=range]') ? content.querySelector('input[type=range]').max : 1000}" oninput="content.querySelector('input[type=range]').max = this.value">
                    </div>
                    <div class="form-group">
                        <label>Default Value</label>
                        <input type="number" value="${content.querySelector('input[type=range]') ? content.querySelector('input[type=range]').value : 500}" oninput="content.querySelector('input[type=range]').value = this.value">
                    </div>
                `;
                break;
            case 'custom-form':
                const customFormFields = content._customFormFields || [];
                specificControls = `
                    <div class="form-group">
                        <label>Form Fields</label>
                        <div id="customFormFieldsPanel">
                            ${customFormFields.map((field, idx) => `
                                <div class='mb-2'>
                                    <input type='text' value='${field.label}' placeholder='Label' data-idx='${idx}' oninput='updateCustomFormFieldLabel(this, ${idx})' class='form-control mb-1' />
                                    <select data-idx='${idx}' onchange='updateCustomFormFieldType(this, ${idx})' class='form-select mb-1'>
                                        <option value='text' ${field.type==='text'?'selected':''}>Text</option>
                                        <option value='email' ${field.type==='email'?'selected':''}>Email</option>
                                        <option value='number' ${field.type==='number'?'selected':''}>Number</option>
                                        <option value='date' ${field.type==='date'?'selected':''}>Date</option>
                                        <option value='textarea' ${field.type==='textarea'?'selected':''}>Textarea</option>
                                    </select>
                                    <input type='checkbox' ${field.required?'checked':''} onchange='updateCustomFormFieldRequired(this, ${idx})' /> Required
                                    <input type='text' value='${field.value||''}' placeholder='Default Value' data-idx='${idx}' oninput='updateCustomFormFieldDefault(this, ${idx})' class='form-control mb-1' />
                                </div>
                            `).join('')}
                        </div>
                        <button type='button' class='btn btn-sm btn-primary mt-2' onclick='addCustomFormField(this)'>Add Field</button>
                    </div>
                `;
            break;
            case 'contact-form':
            case 'auth-form':
                specificControls = `
                    <div class="form-group">
                        <label>Form Fields</label>
                        <p>Edit fields directly in the component preview.</p>
                    </div>
                `;
                break;
            case 'social-share':
                specificControls = `
                    <div class="form-group">
                        <label>Share Button Text</label>
                        <input type="text" value="${content.querySelector('button') ? content.querySelector('button').textContent : ''}" oninput="content.querySelector('button').textContent = this.value">
                    </div>
                `;
                break;
            case 'student-leaderboard':
            case 'student-listing':
                specificControls = `
                    <div class="form-group">
                        <label>Students (format: Name - Score, comma separated)</label>
                        <input type="text" value="${Array.from(content.querySelectorAll('li')).map(li => li.textContent).join(', ')}" oninput="updateStudentList(this.value)">
                    </div>
                `;
                break;
            case 'updates':
                specificControls = `
                    <div class="form-group">
                        <label>Update Title</label>
                        <input type="text" value="${content.querySelector('h4') ? content.querySelector('h4').textContent : ''}" oninput="content.querySelector('h4').textContent = this.value">
                    </div>
                    <div class="form-group">
                        <label>Update Content</label>
                        <textarea oninput="content.querySelector('p').textContent = this.value">${content.querySelector('p') ? content.querySelector('p').textContent : ''}</textarea>
                    </div>
                `;
                break;
            case 'facebook-comments':
                specificControls = `<div class="form-group"><label>Facebook Comments Plugin</label><p>Configure via Facebook.</p></div>`;
                break;
            case 'sponsorships':
                specificControls = `<div class="form-group"><label>Sponsors</label><p>Edit sponsors directly in the component preview.</p></div>`;
                break;
            case 'contact-us':
                specificControls = `<div class="form-group"><label>Contact Info</label><p>Edit contact info directly in the component preview.</p></div>`;
                break;
            case 'site-goal':
                const goalData = content._goalData || { goal: 5000, raised: 500, ticks: [1250, 2500, 4000] };
                specificControls = `
                    <div class="form-group">
                        <label>Goal Amount</label>
                        <input type="number" value="${goalData.goal}" min="1" oninput="updateSiteGoalField(this, 'goal')">
                    </div>
                    <div class="form-group">
                        <label>Raised Amount</label>
                        <input type="number" value="${goalData.raised}" min="0" oninput="updateSiteGoalField(this, 'raised')">
                    </div>
                    <div class="form-group">
                        <label>Tick Marks (comma separated)</label>
                        <input type="text" value="${goalData.ticks.join(', ')}" oninput="updateSiteGoalField(this, 'ticks')">
                    </div>
                `;
            break;
            case 'text-images':
                const textImagesData = content._textImagesData || { text: '', imgSrc: '', imgPosition: 'left', imgSize: 200, showImage: true };
                specificControls = `
                    <div class="form-group">
                        <label>Text</label>
                        <textarea oninput="updateTextImagesField(this.value, 'text')">${textImagesData.text}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" accept="image/*" onchange="uploadTextImagesImage(event)">
                        <img src="${textImagesData.imgSrc}" style="max-width:100%;margin-top:8px;border-radius:4px;"/>
                    </div>
                    <div class="form-group">
                        <label>Image Position</label>
                        <select oninput="updateTextImagesField(this.value, 'imgPosition')">
                            <option value="up" ${textImagesData.imgPosition==='up'?'selected':''}>Up</option>
                            <option value="down" ${textImagesData.imgPosition==='down'?'selected':''}>Down</option>
                            <option value="left" ${textImagesData.imgPosition==='left'?'selected':''}>Left</option>
                            <option value="right" ${textImagesData.imgPosition==='right'?'selected':''}>Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image Size (px)</label>
                        <input type="number" min="50" max="1000" value="${textImagesData.imgSize}" oninput="updateTextImagesField(this.value, 'imgSize')">
                    </div>
                    <div class="form-group">
                        <label>Show Image</label>
                        <input type="checkbox" ${textImagesData.showImage ? 'checked' : ''} onchange="toggleTextImagesShowImage(this)">
                    </div>
                `;
            break;
        }

        // Common styling controls
        propertyControls.innerHTML = `
            ${specificControls}
            <div class="form-group">
            <label>Font Size</label>
            <input type="text" value="${content.style.fontSize || '16px'}" oninput="updateStyle(this, 'fontSize')">
            </div>
            <div class="form-group">
            <label>Color</label>
            <input type="color" value="${rgbToHex(content.style.color || '#000000')}" oninput="updateStyle(this, 'color')">
            </div>
            <div class="form-group">
            <label>Background Color</label>
            <input type="color" value="${rgbToHex(content.style.backgroundColor || '#ffffff')}" oninput="updateStyle(this, 'backgroundColor')">
            </div>
            <div class="form-group">
            <label>Padding</label>
            <input type="text" value="${content.style.padding || '0px'}" oninput="updateStyle(this, 'padding')">
            </div>
            <div class="form-group">
            <label>Text Align</label>
            <select oninput="updateStyle(this, 'textAlign')">
                <option value="left" ${content.style.textAlign === 'left' ? 'selected' : ''}>Left</option>
                <option value="center" ${content.style.textAlign === 'center' ? 'selected' : ''}>Center</option>
                <option value="right" ${content.style.textAlign === 'right' ? 'selected' : ''}>Right</option>
            </select>
            </div>
            <div class="form-group">
            <label>Margin Left</label>
            <input type="text" value="${content.style.marginLeft || ''}" oninput="updateStyle(this, 'marginLeft')">
            </div>
            <div class="form-group">
            <label>Margin Right</label>
            <input type="text" value="${content.style.marginRight || ''}" oninput="updateStyle(this, 'marginRight')">
            </div>
            <div class="form-group">
            <label>Margin Top</label>
            <input type="text" value="${content.style.marginTop || ''}" oninput="updateStyle(this, 'marginTop')">
            </div>
            <div class="form-group">
            <label>Margin Bottom</label>
            <input type="text" value="${content.style.marginBottom || ''}" oninput="updateStyle(this, 'marginBottom')">
            </div>
        `;

        if (selectedComponent && selectedComponent.dataset.type === 'faq') {
            const numSelect = document.getElementById('faq_number_of_questions');
            if (numSelect) {
                numSelect.addEventListener('change', function() {
                    renderFaqEntries(this.value);
                });
                renderFaqEntries(numSelect.value || 1);
            }
        }
    }


    // ...add this function outside updatePropertyPanel:
    function updateSliderSlidesToShow(input) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        content.dataset.slidesToShow = input.value;
        // Re-render the slider preview if images are present
        // (You may want to store selected images in content._sliderImages or similar)
        // For now, just trigger selectImagesForComponent if needed
        if (typeof selectImagesForComponent === 'function') {
            selectImagesForComponent();
        }
    }

    // Helper function to convert RGB to Hex
    function rgbToHex(rgb) {
      if (!rgb) return '#000000';
      const match = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
      if (!match) return rgb;
      const r = parseInt(match[1]).toString(16).padStart(2, '0');
      const g = parseInt(match[2]).toString(16).padStart(2, '0');
      const b = parseInt(match[3]).toString(16).padStart(2, '0');
      return `#${r}${g}${b}`;
    }

    // Update component style
    function updateStyle(input, property) {
      if (selectedComponent) {
        const content = getContentElement(selectedComponent);

        content.style[property] = input.value;
      }
    }

    // Update image source
    function updateImage(src) {
      if (selectedComponent) {
        const img = selectedComponent.querySelector('img');
        img.src = src;
        const preview = selectedComponent.closest('.properties').querySelector('.image-preview');
        if (preview) {
          preview.src = src;
        }
      }
    }

    // Update button link
    function updateButtonLink(href) {
      if (selectedComponent) {
        const button = selectedComponent.querySelector('button');
        button.dataset.href = href;
        if (href) {
          button.onclick = (e) => {
            e.preventDefault();
            const target = button.dataset.target || '_self';
            window.open(href, target);
          };
        } else {
          button.onclick = null;
        }
      }
    }

    // Update button target
    function updateButtonTarget(newTab) {
      if (selectedComponent) {
        const button = selectedComponent.querySelector('button');
        button.dataset.target = newTab ? '_blank' : '_self';
      }
    }

    // Update content
    function updateContent(text) {
      if (selectedComponent) {
        const content = getContentElement(selectedComponent);

        con = getContentElement(content);

        console.log(con);

        if (con) {
            if (con.type === 'file' || con.type === 'text' || con.type === 'number') {
                document.querySelectorAll('.compo').forEach(el => el.remove());

                con.insertAdjacentHTML('beforebegin', '<label class="compo">' + text + '</label> <br class="compo">');
            }
        }else{
            content.textContent = text;
        }

      }
    }

    // Update heading level
    function updateHeadingLevel(level) {
      if (selectedComponent) {
        const oldHeading = selectedComponent.querySelector('h1, h2, h3, h4');
        const newHeading = document.createElement(level);
        newHeading.textContent = oldHeading.textContent;
        newHeading.style.cssText = oldHeading.style.cssText;
        newHeading.contentEditable = true;
        oldHeading.replaceWith(newHeading);
      }
    }

    function getContentElement(component) {
        // return Array.from(component.children).find(child => !child.classList.contains('component-controls'));
        return Array.from(component.children).find(child =>
            !child.classList.contains('component-controls') &&
            !child.classList.contains('compo') // add more classes as needed
        );
    }

    // Clear selection when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.component') && !e.target.closest('.properties')) {
        if (selectedComponent) {
          selectedComponent.classList.remove('selected');
          selectedComponent = null;
          updatePropertyPanel();
        }
      }
    });

    function uploadImage(event) {
        const file = event.target.files[0];
        if (file && selectedComponent) {
            const reader = new FileReader();
            reader.onload = function(e) {
            const img = selectedComponent.querySelector('img');
            img.src = e.target.result;

            // Update preview if exists
            const preview = selectedComponent.closest('.properties').querySelector('.image-preview');
            if (preview) {
                preview.src = e.target.result;
            }
            };
            reader.readAsDataURL(file);
        }
        }

        function updateButtonAlignment(select) {
            if (selectedComponent && selectedComponent.dataset.type === 'button') {
                const wrapper = getContentElement(selectedComponent); // This returns your wrapper div
                if (wrapper) {
                wrapper.style.textAlign = select.value;
                }
            }
        }

        function updateVideoEmbed(url) {
            if (!selectedComponent) return;
            const container = selectedComponent.querySelector('.video-container');

            if (!container) return;

            // Simple YouTube embed
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                let videoId = '';
                if (url.includes('youtu.be')) {
                videoId = url.split('/').pop();
                } else {
                const urlParams = new URLSearchParams(new URL(url).search);
                videoId = urlParams.get('v');
                }
                if (videoId) {
                container.innerHTML = `<iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
                } else {
                container.innerHTML = 'Invalid YouTube URL';
                }
            } else {
                container.innerHTML = 'Unsupported video URL';
            }
        }



        function uploadBannerImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                // For site-banner and custom-banner, content is the banner div
                const content = getContentElement(selectedComponent);
                if (content) {
                    // Find the img inside the banner div
                    const img = content.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                    }
                }
            };
            reader.readAsDataURL(file);
        }

        function updateBannerText(text) {
            // For site-banner and custom-banner, content is the banner div
            const content = getContentElement(selectedComponent);
            if (content) {
                // Find the h3 overlay inside the banner div
                const h3 = content.querySelector('h3');
                if (h3) {
                    h3.textContent = text;
                }
            }
        }

        function updateVideoSrc(url) {
            if (!selectedComponent) return;

            const video = getContentElement(selectedComponent).querySelector('video');
            if (video) {
                video.src = url;
            }
        }


        function openImageManager() {
            alert('Open image manager (not implemented)');
            // You can replace this with a custom modal logic
        }

        function uploadGalleryImages(event) {
            const files = Array.from(event.target.files);
            const content = getContentElement(selectedComponent);
            content.innerHTML = ''; // Clear existing gallery

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'gallery-image';
                content.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
        let uploadedImages = []; // Store uploaded image URLs

        function openImageManager() {
        document.getElementById('imageManagerModal').style.display = 'flex';
        renderImageGallery();
        }

        function closeImageManager() {
        document.getElementById('imageManagerModal').style.display = 'none';
        }

        function selectImage(src) {
            const target = selectedComponent || lastSelectedComponent;

            if (!target) {
                alert("No component selected.");
                closeImageManager();
                return;
            }

            const type = target.dataset.type;

            if (type === 'gallery') {
                const content = getContentElement(target);

                // Clear placeholder if first image
                if (content.innerText.includes("Gallery Placeholder")) {
                    content.innerHTML = '';
                }

                // Create the image element
                const img = document.createElement('img');
                img.src = src;
                img.style.maxWidth = '100px';
                img.style.margin = '5px';
                img.style.cursor = 'pointer';
                img.title = 'Click to remove';

                // Add remove-on-click functionality
                img.onclick = function () {
                    if (confirm("Remove this image?")) {
                        img.remove();

                        // If gallery is now empty, restore placeholder
                        if (content.children.length === 0) {
                            content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Gallery Placeholder</div>';
                        }
                    }
                };

                content.appendChild(img);
            }
            else if(type === 'slider'){
                const content = getContentElement(target);

                // Clear placeholder if first image
                if (content.innerText.includes("Slider Placeholder")) {
                    content.innerHTML = '';
                }

                // Create the image element
                const img = document.createElement('img');
                img.src = src;
                img.style.maxWidth = '100px';
                img.style.margin = '5px';
                img.style.cursor = 'pointer';
                img.title = 'Click to remove';

                // Add remove-on-click functionality
                img.onclick = function () {
                    if (confirm("Remove this image?")) {
                        img.remove();

                        // If gallery is now empty, restore placeholder
                        if (content.children.length === 0) {
                            content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Slider Placeholder</div>';
                        }
                    }
                };

                content.appendChild(img);
            } else {
                alert("Selected component is not a gallery.");
            }

            closeImageManager();
        }





        // Handle multiple image upload for gallery/slider
        function handleImageUpload(event) {
            const files = Array.from(event.target.files);
            if (!files.length) return;
            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImages.push(e.target.result);
                    renderImageGallery();
                };
                reader.readAsDataURL(file);
            });
            document.getElementById('uploadStatus').innerText = 'Images uploaded!';
        }

        // Render image gallery for selection (multiple select)
        function renderImageGallery() {
            const gallery = document.getElementById('imageGallery');
            gallery.innerHTML = '';
            uploadedImages.forEach((src, idx) => {
                const img = document.createElement('img');
                img.src = src;
                img.style.width = '120px';
                img.style.height = '90px';
                img.style.objectFit = 'cover';
                img.style.border = '2px solid transparent';
                img.style.cursor = 'pointer';
                img.classList.add('gallery-select-img');
                img.onclick = function(e) {
                    e.stopPropagation();
                    img.classList.toggle('selected');
                };
                gallery.appendChild(img);
            });

            // Add a "Select Images" button for confirming selection
            let selectBtn = document.getElementById('selectImagesBtn');
            if (!selectBtn) {
                selectBtn = document.createElement('button');
                selectBtn.id = 'selectImagesBtn';
                selectBtn.textContent = 'Select Images';
                selectBtn.className = 'btn btn-primary mt-3';
                selectBtn.onclick = function(e) {
                    e.preventDefault();
                    selectImagesForComponent();
                };
                gallery.parentNode.appendChild(selectBtn);
            }
        }

        // Select images for gallery/slider and update preview
        function selectImagesForComponent() {
            const selectedImgs = Array.from(document.querySelectorAll('#imageGallery img.selected')).map(img => img.src);
            // if (!selectedComponent || !lastSelectedComponent) return;
            const type = lastSelectedComponent.dataset.type;
            const content = getContentElement(lastSelectedComponent);

            if (type === 'gallery') {
                // Show images in col-md-4 grid, fixed width
                content.innerHTML = `<div class="row"></div>`;
                const row = content.querySelector('.row');
                selectedImgs.forEach(src => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-3';
                    col.innerHTML = `<img src="${src}" style="width:100%;height:160px;object-fit:cover;border-radius:8px;border: 1px solid #000;">`;
                    row.appendChild(col);
                });
                if (!selectedImgs.length) {
                    content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Gallery Placeholder</div>';
                }
            } else if (type === 'slider') {
                // Use Owl Carousel for slider preview
                const slidesToShow = content.dataset.slidesToShow ? parseInt(content.dataset.slidesToShow, 10) : 1;
                content.innerHTML = `
                    <div class="owl-carousel owl-theme" id="sliderPreview" data-slides-to-show="${slidesToShow}">
                        ${selectedImgs.map(src => `<div class="item"><img src="${src}" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"></div>`).join('')}
                    </div>
                `;
                // Initialize Owl Carousel (requires jQuery and Owl Carousel)
                setTimeout(() => {
                    if (window.$ && $.fn.owlCarousel) {
                        $('#sliderPreview').owlCarousel({
                            items: slidesToShow,
                            loop: true,
                            margin: 10,
                            nav: true,
                            dots: true,
                        });
                    }
                }, 100);
                if (!selectedImgs.length) {
                    content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Slider Placeholder</div>';
                }
            }
            closeImageManager();
        }

        // Add Owl Carousel CSS/JS if not already present
        (function ensureOwlCarousel() {
            if (!document.getElementById('owl-carousel-css')) {
                const link = document.createElement('link');
                link.id = 'owl-carousel-css';
                link.rel = 'stylesheet';
                link.href = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css';
                document.head.appendChild(link);
            }
            if (!document.getElementById('owl-carousel-js')) {
                const script = document.createElement('script');
                script.id = 'owl-carousel-js';
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
                document.body.appendChild(script);
            }
        })();

        // Update the file input to allow multiple files
        document.getElementById('imageUploadInput').setAttribute('multiple', 'multiple');

        canvas.addEventListener('click', function (e) {
            const component = e.target.closest('[data-type]');
            lastSelectedComponent = e; // Backup
            if (component) {
            selectedComponent = component;
            updatePropertyPanel();
            }
        });

    // Place this function outside updatePropertyPanel
    function renderFaqEntries(count) {
        const container = document.getElementById('faq_entries');
        if (!container) return;
        const data = getFaqDataForSelectedComponent(count);
        container.innerHTML = '';
        for(let i=0; i<count; i++) {
            const entry = data[i];
            container.innerHTML += `
                <div class="faq-entry" style="border:1px solid #eee; padding:10px; margin-bottom:10px;">
                    <h5>Entry ${i+1}</h5>
                    <div class="form-group">
                        <label>Sort order</label>
                        <select class="form-select" id="faq_order_${i}" name="faq_order_${i}">
                            ${Array.from({length: 6}, (_, j) => `<option value="${j+1}" ${j+1===i?'selected':''}>${j+1}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Label color</label>
                        <select class="form-select" id="faq_label_color_${i}" name="faq_label_color_${i}">
                            <option value="">Default</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="aqua">Aqua</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="brown">Brown</option>
                            <option value="cyan">Cyan</option>
                            <option value="fuchsia">Fuchsia</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="indigo">Indigo</option>
                            <option value="lime">Lime</option>
                            <option value="magenta">Magenta</option>
                            <option value="maroon">Maroon</option>
                            <option value="navy">Navy</option>
                            <option value="olive">Olive</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="silver">Silver</option>
                            <option value="tan">Tan</option>
                            <option value="teal">Teal</option>
                            <option value="turquoise">Turquoise</option>
                            <option value="violet">Violet</option>
                            <option value="white">White</option>
                            <option value="light">Light</option>
                            <option value="yellow">Yellow</option>
                            <option value="gold">Gold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Background color</label>
                        <select class="form-select" id="faq_background_color_${i}" name="faq_background_color_${i}">
                            <option value="">Default</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="aqua">Aqua</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="brown">Brown</option>
                            <option value="cyan">Cyan</option>
                            <option value="fuchsia">Fuchsia</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="indigo">Indigo</option>
                            <option value="lime">Lime</option>
                            <option value="magenta">Magenta</option>
                            <option value="maroon">Maroon</option>
                            <option value="navy">Navy</option>
                            <option value="olive">Olive</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="silver">Silver</option>
                            <option value="tan">Tan</option>
                            <option value="teal">Teal</option>
                            <option value="turquoise">Turquoise</option>
                            <option value="violet">Violet</option>
                            <option value="white">White</option>
                            <option value="light">Light</option>
                            <option value="yellow">Yellow</option>
                            <option value="gold">Gold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Text color</label>
                        <select class="form-select" id="faq_text_color_${i}" name="faq_text_color_${i}">
                            <option value="">Default</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="aqua">Aqua</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="brown">Brown</option>
                            <option value="cyan">Cyan</option>
                            <option value="fuchsia">Fuchsia</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="indigo">Indigo</option>
                            <option value="lime">Lime</option>
                            <option value="magenta">Magenta</option>
                            <option value="maroon">Maroon</option>
                            <option value="navy">Navy</option>
                            <option value="olive">Olive</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="silver">Silver</option>
                            <option value="tan">Tan</option>
                            <option value="teal">Teal</option>
                            <option value="turquoise">Turquoise</option>
                            <option value="violet">Violet</option>
                            <option value="white">White</option>
                            <option value="light">Light</option>
                            <option value="yellow">Yellow</option>
                            <option value="gold">Gold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" class="form-control" name="faq_question_${i}" value="">
                    </div>
                    <div class="form-group">
                        <label>Answer</label>
                        <textarea class="form-control text-editor" name="faq_answer_${i}" rows="3"></textarea>
                    </div>
                </div>
            `;
        }
    }

    // // Store FAQ data per component (WeakMap to avoid memory leaks)
    // const faqComponentData = new WeakMap();

    function getFaqDataForSelectedComponent(count) {
        if (!selectedComponent) return [];
        let data = faqComponentData.get(selectedComponent);
        if (!data || data.length !== Number(count)) {
            // Initialize or resize
            data = Array.from({length: Number(count)}, (_, i) => data && data[i] ? data[i] : {
                order: i+1,
                labelColor: '',
                backgroundColor: '',
                textColor: '',
                question: '',
                answer: ''
            });
            faqComponentData.set(selectedComponent, data);
        }
        return data;
    }

    function renderFaqEntries(count) {
        const container = document.getElementById('faq_entries');
        if (!container) return;
        const data = getFaqDataForSelectedComponent(count);
        container.innerHTML = '';
        for(let i=0; i<count; i++) {
            const entry = data[i];
            container.innerHTML += `
                <div class="faq-entry" style="border:1px solid #eee; padding:10px; margin-bottom:10px;">
                    <h5>Entry ${i+1}</h5>
                    <div class="form-group">
                        <label>Sort order</label>
                        <select class="form-select" id="faq_order_${i}" name="faq_order_${i}">
                            ${Array.from({length: 6}, (_, j) => `<option value="${j+1}" ${j+1===i?'selected':''}>${j+1}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Label color</label>
                        <select class="form-select" id="faq_label_color_${i}" name="faq_label_color_${i}">
                            <option value="">Default</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="aqua">Aqua</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="brown">Brown</option>
                            <option value="cyan">Cyan</option>
                            <option value="fuchsia">Fuchsia</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="indigo">Indigo</option>
                            <option value="lime">Lime</option>
                            <option value="magenta">Magenta</option>
                            <option value="maroon">Maroon</option>
                            <option value="navy">Navy</option>
                            <option value="olive">Olive</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="silver">Silver</option>
                            <option value="tan">Tan</option>
                            <option value="teal">Teal</option>
                            <option value="turquoise">Turquoise</option>
                            <option value="violet">Violet</option>
                            <option value="white">White</option>
                            <option value="light">Light</option>
                            <option value="yellow">Yellow</option>
                            <option value="gold">Gold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Background color</label>
                        <select class="form-select" id="faq_background_color_${i}" name="faq_background_color_${i}">
                            <option value="">Default</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="aqua">Aqua</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="brown">Brown</option>
                            <option value="cyan">Cyan</option>
                            <option value="fuchsia">Fuchsia</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="indigo">Indigo</option>
                            <option value="lime">Lime</option>
                            <option value="magenta">Magenta</option>
                            <option value="maroon">Maroon</option>
                            <option value="navy">Navy</option>
                            <option value="olive">Olive</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="silver">Silver</option>
                            <option value="tan">Tan</option>
                            <option value="teal">Teal</option>
                            <option value="turquoise">Turquoise</option>
                            <option value="violet">Violet</option>
                            <option value="white">White</option>
                            <option value="light">Light</option>
                            <option value="yellow">Yellow</option>
                            <option value="gold">Gold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Text color</label>
                        <select class="form-select" id="faq_text_color_${i}" name="faq_text_color_${i}">
                            <option value="">Default</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="aqua">Aqua</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="brown">Brown</option>
                            <option value="cyan">Cyan</option>
                            <option value="fuchsia">Fuchsia</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="indigo">Indigo</option>
                            <option value="lime">Lime</option>
                            <option value="magenta">Magenta</option>
                            <option value="maroon">Maroon</option>
                            <option value="navy">Navy</option>
                            <option value="olive">Olive</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="silver">Silver</option>
                            <option value="tan">Tan</option>
                            <option value="teal">Teal</option>
                            <option value="turquoise">Turquoise</option>
                            <option value="violet">Violet</option>
                            <option value="white">White</option>
                            <option value="light">Light</option>
                            <option value="yellow">Yellow</option>
                            <option value="gold">Gold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" class="form-control" name="faq_question_${i}" value="">
                    </div>
                    <div class="form-group">
                        <label>Answer</label>
                        <textarea class="form-control text-editor" name="faq_answer_${i}" rows="3"></textarea>
                    </div>
                </div>
            `;
        }
        updateFaqPreview();
    }

    function updateFaqPreview() {
        if (!selectedComponent || selectedComponent.dataset.type !== 'faq') return;
        const faqData = faqComponentData.get(selectedComponent) || [];
        // Find the content element inside the selectedComponent
        const content = getContentElement(selectedComponent);
        // Render the FAQ as a list of questions/answers
        let html = '<div class="faq-preview-list">';
        // Sort by order
        const sorted = [...faqData].sort((a, b) => a.order - b.order);
        sorted.forEach(entry => {
            html += `<div class="faq-preview-item" style="margin-bottom:16px;">
                <div style="font-weight:bold; color:${entry.labelColor||'#333'}; background:${entry.backgroundColor||'transparent'}; padding:4px 0;">
                    ${entry.question ? entry.question : '<em>Question</em>'}
                </div>
                <div style="color:${entry.textColor||'#333'}; padding:4px 0 0 0;">
                    ${entry.answer ? entry.answer : '<em>Answer</em>'}
                </div>
            </div>`;
        });
        html += '</div>';
        content.innerHTML = html;
    }

    function renderFaqEntries(count) {
        count = parseInt(count);
        const container = document.getElementById('faq_entries');
        if (!container) return;
        const faqData = getFaqDataForSelectedComponent(count);
        // Sort by order
        const sorted = [...faqData].sort((a, b) => a.order - b.order);
        container.innerHTML = '';
        sorted.forEach((entry, idx) => {
            const i = idx + 1;
            const entryDiv = document.createElement('div');
            entryDiv.className = 'faq-entry';
            entryDiv.style = 'border:1px solid #eee; padding:10px; margin-bottom:10px;';
            entryDiv.innerHTML = `
                <h5>Entry ${i}</h5>
                <div class="form-group">
                    <label>Sort order</label>
                    <select class="form-select faq-order" data-idx="${idx}">
                        ${Array.from({length: count}, (_, j) => `<option value="${j+1}" ${entry.order===(j+1)?'selected':''}>${j+1}</option>`).join('')}
                    </select>
                </div>
                <div class="form-group">
                    <label>Label color</label>
                    <select class="form-select faq-label-color" data-idx="${idx}">
                        <option value="">Default</option>
                        <option value="primary" ${entry.labelColor==='primary'?'selected':''}>Primary</option>
                        <option value="secondary" ${entry.labelColor==='secondary'?'selected':''}>Secondary</option>
                        <option value="aqua" ${entry.labelColor==='aqua'?'selected':''}>Aqua</option>
                        <option value="black" ${entry.labelColor==='black'?'selected':''}>Black</option>
                        <option value="blue" ${entry.labelColor==='blue'?'selected':''}>Blue</option>
                        <option value="brown" ${entry.labelColor==='brown'?'selected':''}>Brown</option>
                        <option value="cyan" ${entry.labelColor==='cyan'?'selected':''}>Cyan</option>
                        <option value="fuchsia" ${entry.labelColor==='fuchsia'?'selected':''}>Fuchsia</option>
                        <option value="gray" ${entry.labelColor==='gray'?'selected':''}>Gray</option>
                        <option value="green" ${entry.labelColor==='green'?'selected':''}>Green</option>
                        <option value="indigo" ${entry.labelColor==='indigo'?'selected':''}>Indigo</option>
                        <option value="lime" ${entry.labelColor==='lime'?'selected':''}>Lime</option>
                        <option value="magenta" ${entry.labelColor==='magenta'?'selected':''}>Magenta</option>
                        <option value="maroon" ${entry.labelColor==='maroon'?'selected':''}>Maroon</option>
                        <option value="navy" ${entry.labelColor==='navy'?'selected':''}>Navy</option>
                        <option value="olive" ${entry.labelColor==='olive'?'selected':''}>Olive</option>
                        <option value="orange" ${entry.labelColor==='orange'?'selected':''}>Orange</option>
                        <option value="pink" ${entry.labelColor==='pink'?'selected':''}>Pink</option>
                        <option value="purple" ${entry.labelColor==='purple'?'selected':''}>Purple</option>
                        <option value="red" ${entry.labelColor==='red'?'selected':''}>Red</option>
                        <option value="silver" ${entry.labelColor==='silver'?'selected':''}>Silver</option>
                        <option value="tan" ${entry.labelColor==='tan'?'selected':''}>Tan</option>
                        <option value="teal" ${entry.labelColor==='teal'?'selected':''}>Teal</option>
                        <option value="turquoise" ${entry.labelColor==='turquoise'?'selected':''}>Turquoise</option>
                        <option value="violet" ${entry.labelColor==='violet'?'selected':''}>Violet</option>
                        <option value="white" ${entry.labelColor==='white'?'selected':''}>White</option>
                        <option value="light" ${entry.labelColor==='light'?'selected':''}>Light</option>
                        <option value="yellow" ${entry.labelColor==='yellow'?'selected':''}>Yellow</option>
                        <option value="gold" ${entry.labelColor==='gold'?'selected':''}>Gold</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Background color</label>
                    <select class="form-select faq-background-color" data-idx="${idx}">
                        <option value="">Default</option>
                        <option value="primary" ${entry.backgroundColor==='primary'?'selected':''}>Primary</option>
                        <option value="secondary" ${entry.backgroundColor==='secondary'?'selected':''}>Secondary</option>
                        <option value="aqua" ${entry.backgroundColor==='aqua'?'selected':''}>Aqua</option>
                        <option value="black" ${entry.backgroundColor==='black'?'selected':''}>Black</option>
                        <option value="blue" ${entry.backgroundColor==='blue'?'selected':''}>Blue</option>
                        <option value="brown" ${entry.backgroundColor==='brown'?'selected':''}>Brown</option>
                        <option value="cyan" ${entry.backgroundColor==='cyan'?'selected':''}>Cyan</option>
                        <option value="fuchsia" ${entry.backgroundColor==='fuchsia'?'selected':''}>Fuchsia</option>
                        <option value="gray" ${entry.backgroundColor==='gray'?'selected':''}>Gray</option>
                        <option value="green" ${entry.backgroundColor==='green'?'selected':''}>Green</option>
                        <option value="indigo" ${entry.backgroundColor==='indigo'?'selected':''}>Indigo</option>
                        <option value="lime" ${entry.backgroundColor==='lime'?'selected':''}>Lime</option>
                        <option value="magenta" ${entry.backgroundColor==='magenta'?'selected':''}>Magenta</option>
                        <option value="maroon" ${entry.backgroundColor==='maroon'?'selected':''}>Maroon</option>
                        <option value="navy" ${entry.backgroundColor==='navy'?'selected':''}>Navy</option>
                        <option value="olive" ${entry.backgroundColor==='olive'?'selected':''}>Olive</option>
                        <option value="orange" ${entry.backgroundColor==='orange'?'selected':''}>Orange</option>
                        <option value="pink" ${entry.backgroundColor==='pink'?'selected':''}>Pink</option>
                        <option value="purple" ${entry.backgroundColor==='purple'?'selected':''}>Purple</option>
                        <option value="red" ${entry.backgroundColor==='red'?'selected':''}>Red</option>
                        <option value="silver" ${entry.backgroundColor==='silver'?'selected':''}>Silver</option>
                        <option value="tan" ${entry.backgroundColor==='tan'?'selected':''}>Tan</option>
                        <option value="teal" ${entry.backgroundColor==='teal'?'selected':''}>Teal</option>
                        <option value="turquoise" ${entry.backgroundColor==='turquoise'?'selected':''}>Turquoise</option>
                        <option value="violet" ${entry.backgroundColor==='violet'?'selected':''}>Violet</option>
                        <option value="white" ${entry.backgroundColor==='white'?'selected':''}>White</option>
                        <option value="light" ${entry.backgroundColor==='light'?'selected':''}>Light</option>
                        <option value="yellow" ${entry.backgroundColor==='yellow'?'selected':''}>Yellow</option>
                        <option value="gold" ${entry.backgroundColor==='gold'?'selected':''}>Gold</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Text color</label>
                    <select class="form-select faq-text-color" data-idx="${idx}">
                        <option value="">Default</option>
                        <option value="primary" ${entry.textColor==='primary'?'selected':''}>Primary</option>
                        <option value="secondary" ${entry.textColor==='secondary'?'selected':''}>Secondary</option>
                        <option value="aqua" ${entry.textColor==='aqua'?'selected':''}>Aqua</option>
                        <option value="black" ${entry.textColor==='black'?'selected':''}>Black</option>
                        <option value="blue" ${entry.textColor==='blue'?'selected':''}>Blue</option>
                        <option value="brown" ${entry.textColor==='brown'?'selected':''}>Brown</option>
                        <option value="cyan" ${entry.textColor==='cyan'?'selected':''}>Cyan</option>
                        <option value="fuchsia" ${entry.textColor==='fuchsia'?'selected':''}>Fuchsia</option>
                        <option value="gray" ${entry.textColor==='gray'?'selected':''}>Gray</option>
                        <option value="green" ${entry.textColor==='green'?'selected':''}>Green</option>
                        <option value="indigo" ${entry.textColor==='indigo'?'selected':''}>Indigo</option>
                        <option value="lime" ${entry.textColor==='lime'?'selected':''}>Lime</option>
                        <option value="magenta" ${entry.textColor==='magenta'?'selected':''}>Magenta</option>
                        <option value="maroon" ${entry.textColor==='maroon'?'selected':''}>Maroon</option>
                        <option value="navy" ${entry.textColor==='navy'?'selected':''}>Navy</option>
                        <option value="olive" ${entry.textColor==='olive'?'selected':''}>Olive</option>
                        <option value="orange" ${entry.textColor==='orange'?'selected':''}>Orange</option>
                        <option value="pink" ${entry.textColor==='pink'?'selected':''}>Pink</option>
                        <option value="purple" ${entry.textColor==='purple'?'selected':''}>Purple</option>
                        <option value="red" ${entry.textColor==='red'?'selected':''}>Red</option>
                        <option value="silver" ${entry.textColor==='silver'?'selected':''}>Silver</option>
                        <option value="tan" ${entry.textColor==='tan'?'selected':''}>Tan</option>
                        <option value="teal" ${entry.textColor==='teal'?'selected':''}>Teal</option>
                        <option value="turquoise" ${entry.textColor==='turquoise'?'selected':''}>Turquoise</option>
                        <option value="violet" ${entry.textColor==='violet'?'selected':''}>Violet</option>
                        <option value="white" ${entry.textColor==='white'?'selected':''}>White</option>
                        <option value="light" ${entry.textColor==='light'?'selected':''}>Light</option>
                        <option value="yellow" ${entry.textColor==='yellow'?'selected':''}>Yellow</option>
                        <option value="gold" ${entry.textColor==='gold'?'selected':''}>Gold</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" class="form-control faq-question" data-idx="${idx}" value="${entry.question.replace(/"/g, '&quot;')}">
                </div>
                <div class="form-group">
                    <label>Answer</label>
                    <textarea class="form-control faq-answer" data-idx="${idx}" rows="3">${entry.answer}</textarea>
                </div>
            `;
            container.appendChild(entryDiv);
        });
        // Attach event listeners after DOM insertion
        container.querySelectorAll('.faq-order').forEach(sel => {
            sel.addEventListener('change', function() {
                const idx = parseInt(this.dataset.idx);
                const val = parseInt(this.value);
                faqData[idx].order = val;
                renderFaqEntries(count);
            });
        });
        container.querySelectorAll('.faq-label-color').forEach(sel => {
            sel.addEventListener('change', function() {
                const idx = parseInt(this.dataset.idx);
                faqData[idx].labelColor = this.value;
                updateFaqPreview();
            });
        });
        container.querySelectorAll('.faq-background-color').forEach(sel => {
            sel.addEventListener('change', function() {
                const idx = parseInt(this.dataset.idx);
                faqData[idx].backgroundColor = this.value;
                updateFaqPreview();
            });
        });
        container.querySelectorAll('.faq-text-color').forEach(sel => {
            sel.addEventListener('change', function() {
                const idx = parseInt(this.dataset.idx);
                faqData[idx].textColor = this.value;
                updateFaqPreview();
            });
        });
        container.querySelectorAll('.faq-question').forEach(input => {
            input.addEventListener('input', function() {
                const idx = parseInt(this.dataset.idx);
                faqData[idx].question = this.value;
                updateFaqPreview();
            });
        });
        container.querySelectorAll('.faq-answer').forEach(input => {
            input.addEventListener('input', function() {
                const idx = parseInt(this.dataset.idx);
                faqData[idx].answer = this.value;
                updateFaqPreview();
            });
        });
        // Save back to WeakMap
        faqComponentData.set(selectedComponent, faqData);
        // Update the preview in real time
        updateFaqPreview();
    }

    let countdownInterval = null;

    function updateCountdownDate(value) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._countdownData) return;
        content._countdownData.date = value;
        if (typeof content.renderCountdown === 'function') content.renderCountdown();
    }

    function updateCountdownLabel(value) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._countdownData) return;
        content._countdownData.label = value;
        if (typeof content.renderCountdown === 'function') content.renderCountdown();
    }

    function updateEventInfoField(input, field) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._eventInfoData) return;
        if (field === 'showMap') {
            content._eventInfoData.showMap = input.checked;
        } else {
            content._eventInfoData[field] = input.value;
        }
        if (typeof content.renderEventInfo === 'function') content.renderEventInfo();
    }

    function updateSiteGoalField(input, field) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._goalData) return;
        if (field === 'ticks') {
            // Parse comma separated values
            content._goalData.ticks = input.value.split(',').map(v => parseInt(v.trim(), 10)).filter(v => !isNaN(v));
        } else {
            content._goalData[field] = parseInt(input.value, 10) || 0;
        }
        if (typeof content.renderThermometer === 'function') content.renderThermometer();
    }

    function updateTextImagesField(value, field) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._textImagesData) return;
        if (field === 'imgSize') {
            content._textImagesData.imgSize = parseInt(value, 10) || 200;
        } else {
            content._textImagesData[field] = value;
        }
        if (typeof content.renderTextImages === 'function') content.renderTextImages();
    }

    function uploadTextImagesImage(event) {
        if (!selectedComponent) return;
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const content = getContentElement(selectedComponent);
            if (!content._textImagesData) return;
            content._textImagesData.imgSrc = e.target.result;
            if (typeof content.renderTextImages === 'function') content.renderTextImages();
        };
        reader.readAsDataURL(file);
    }

    function toggleTextImagesShowImage(checkbox) {
        if (selectedComponent && selectedComponent.dataset.type === 'text-images') {
            const content = getContentElement(selectedComponent);
            content._textImagesData.showImage = checkbox.checked;
            content.renderTextImages();
        }
    }

    // --- Custom Form Builder Logic ---
    // Remove add/remove field controls from the preview panel. Only allow from property panel.
    function addCustomFormField(btn) {
        // Only allow adding from the property panel, not from preview
        let content = selectedComponent ? getContentElement(selectedComponent) : null;
        if (!content || !content._customFormFields) return;
        content._customFormFields.push({ label: 'New Field', type: 'text', name: 'field'+(content._customFormFields.length+1), value: '' });
        content.renderCustomForm();
        // Do NOT call updatePropertyPanel() here, just re-render the property panel controls for the new field
        // Instead, manually update the property panel to add the new field controls, but keep selection and focus
        setTimeout(() => {
            updatePropertyPanel();
            // Focus the label input of the newly added field
            const idx = content._customFormFields.length - 1;
            const labelInput = document.querySelector(`#propertyControls input[data-idx='${idx}']`);
            if (labelInput) labelInput.focus();
        }, 0);
    }
    function removeCustomFormField(btn, idx) {
        // Only allow removing from the property panel, not from preview
        let content = selectedComponent ? getContentElement(selectedComponent) : null;
        if (!content || !content._customFormFields) return;
        content._customFormFields.splice(idx, 1);
        content.renderCustomForm();
        setTimeout(() => {
            updatePropertyPanel();
        }, 0);
    }
    function updateCustomFormFieldLabel(input, idx) {
        let content = selectedComponent ? getContentElement(selectedComponent) : null;
        if (!content || !content._customFormFields) return;
        content._customFormFields[idx].label = input.value;
        content.renderCustomForm();
        // Do NOT call updatePropertyPanel() here to avoid losing focus
        // Instead, only update the preview, not the property panel
        // input.focus(); // Not needed, focus is preserved
    }
    function updateCustomFormFieldType(select, idx) {
        let content = selectedComponent ? getContentElement(selectedComponent) : null;
        if (!content || !content._customFormFields) return;
        content._customFormFields[idx].type = select.value;
        content.renderCustomForm();
        updatePropertyPanel();
    }
    function updateCustomFormFieldRequired(checkbox, idx) {
        let content = selectedComponent ? getContentElement(selectedComponent) : null;
        if (!content || !content._customFormFields) return;
        content._customFormFields[idx].required = checkbox.checked;
        content.renderCustomForm();
        updatePropertyPanel();
    }
    function updateCustomFormFieldDefault(input, idx) {
        let content = selectedComponent ? getContentElement(selectedComponent) : null;
        if (!content || !content._customFormFields) return;
        content._customFormFields[idx].value = input.value;
        content.renderCustomForm();
        updatePropertyPanel();
    }
    // --- SERIALIZATION & DB SAVE/LOAD LOGIC ---
    function serializeBuilder() {
      const page = document.getElementById('page');
      const components = Array.from(page.querySelectorAll('.component'));
      return components.map(component => {
        const type = component.dataset.type;
        const content = getContentElement(component);
        let data = { type };
        // Save common styles using CSS property names
        if (content && content.style) {
          data.style = {
            color: content.style.color || '',
            backgroundColor: content.style.backgroundColor || '',
            fontSize: content.style.fontSize || '',
            padding: content.style.padding || '',
            textAlign: content.style.textAlign || '',
            border: content.style.border || '',
            borderRadius: content.style.borderRadius || '',
            margin: content.style.margin || '',
            width: content.style.width || '',
            height: content.style.height || '',
            boxShadow: content.style.boxShadow || '',
            fontWeight: content.style.fontWeight || '',
            fontFamily: content.style.fontFamily || '',
            letterSpacing: content.style.letterSpacing || '',
            lineHeight: content.style.lineHeight || '',
            textDecoration: content.style.textDecoration || '',
            // Add more as needed
          };
        }
        // Serialize per type
        switch (type) {
          case 'section-title':
            data.text = content.textContent;
            break;
          case 'divider':
            data.style = Object.assign({}, data.style, { height: content.style.height, backgroundColor: content.style.backgroundColor });
            break;
          case 'site-banner':
            data.src = content.src;
            data.alt = content.alt;
            break;
          case 'custom-banner':
            data.imgSrc = content.querySelector('img').src;
            data.text = content.querySelector('h3').textContent;
            break;
          case 'faq':
            data.faqData = content._faqData || {};
            break;
          case 'event-countdown':
            data.countdownData = content._countdownData;
            break;
          case 'event-information':
            data.eventInfoData = content._eventInfoData;
            break;
          case 'site-goal':
            data.goalData = content._goalData;
            break;
          case 'text-images':
            data.textImagesData = content._textImagesData;
            break;
          case 'custom-form':
            data.customFormFields = content._customFormFields;
            break;
          // ...add other types as needed...
          default:
            data.html = content.innerHTML;
        }
        return data;
      });
    }

    function deserializeBuilder(state) {
      const page = document.getElementById('page');
      page.innerHTML = '';
      state.forEach((data, idx) => {
        const component = createComponent(data.type);
        const content = getContentElement(component);
        // Restore per type
        switch (data.type) {
          case 'section-title':
            content.textContent = data.text;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'divider':
            if (data.style) {
              content.style.height = data.style.height;
              content.style.backgroundColor = data.style.backgroundColor;
            }
            break;
          case 'site-banner':
            content.src = data.src;
            content.alt = data.alt;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'custom-banner':
            content.querySelector('img').src = data.imgSrc;
            content.querySelector('h3').textContent = data.text;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'faq':
            content._faqData = data.faqData;
            // ...re-render FAQ if needed...
            break;
          case 'event-countdown':
            content._countdownData = data.countdownData;
            content.renderCountdown();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'event-information':
            content._eventInfoData = data.eventInfoData;
            content.renderEventInfo();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'site-goal':
            content._goalData = data.goalData;
            content.renderThermometer();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'text-images':
            content._textImagesData = data.textImagesData;
            content.renderTextImages();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          case 'custom-form':
            content._customFormFields = data.customFormFields || [];
            content.renderCustomForm();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            break;
          // ...add other types as needed...
          default:
            content.innerHTML = data.html;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
        }
        page.appendChild(component);
        // Add dropzone after each component except the last
        if (idx < state.length - 1) {
          page.appendChild(createDropzone());
        }
      });
      // Always ensure a dropzone at the end
      page.appendChild(createDropzone());
    }

    function saveBuilderState() {
      id = document.getElementById('page_id').value;

      const state = serializeBuilder();
      fetch('/admins/page/save/'+id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ state })
      })
      .then(res => res.json())
      .then(data => { alert('Page saved!'); })
      .catch(() => alert('Save failed'));
    }

    window.onload = function() {

        const id = document.getElementById('page_id').value;

        fetch('/admins/page/load/'+id)
            .then(res => res.json())
            .then(data => {
            if (data && data.state) {
                let state = data.state;
                if (typeof state === 'string') {
                    try {
                        state = JSON.parse(state);
                    } catch (e) {
                        alert('Failed to parse saved page data.');
                        return;
                    }
                }
                deserializeBuilder(state);
            } else {
                alert('No saved page found.');
            }
            })
            .catch(() => alert('Load failed'));
    };

    // Restore the showTab function (regression fix)
    function showTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-section').forEach(tab => {
            tab.style.display = 'none';
        });
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // Show the selected tab content
        const tabContent = document.getElementById(tabId);
        if (tabContent) tabContent.style.display = 'block';
        // Add active class to the selected tab button
        const tabBtn = document.querySelector(`[data-tab='${tabId}']`);
        if (tabBtn) tabBtn.classList.add('active');
    }
</script>

<!-- Include DataTables and jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with default search disabled
        const table = $('#studentTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });

        // Link the custom search input to the DataTable search
        $('#search').on('keyup', function() {
            const value = $(this).val();
            table.search(value).draw();
        });
    });
</script>
</body>
</html>

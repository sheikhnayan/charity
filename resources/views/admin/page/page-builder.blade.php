<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Page Builder</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('auction.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Google Fonts - Outfit -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Quill Rich Text Editor with Font Controls -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SortableJS for drag and drop functionality -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
/* Custom Quill styles for better font size and family support */
.ql-snow .ql-picker.ql-size .ql-picker-label::before,
.ql-snow .ql-picker.ql-size .ql-picker-item::before {
  content: '14px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="10px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="10px"]::before {
  content: '10px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="12px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="12px"]::before {
  content: '12px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="14px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="14px"]::before {
  content: '14px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="16px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="16px"]::before {
  content: '16px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="18px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="18px"]::before {
  content: '18px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="20px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="20px"]::before {
  content: '20px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="24px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="24px"]::before {
  content: '24px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="28px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="28px"]::before {
  content: '28px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="32px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="32px"]::before {
  content: '32px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="36px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="36px"]::before {
  content: '36px';
}
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="48px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="48px"]::before {
  content: '48px';
}

/* Font family dropdown styles */
.ql-snow .ql-picker.ql-font .ql-picker-label::before,
.ql-snow .ql-picker.ql-font .ql-picker-item::before {
  content: 'Sans Serif';
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="arial"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="arial"]::before {
  content: 'Arial';
  font-family: Arial, sans-serif;
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="helvetica"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="helvetica"]::before {
  content: 'Helvetica';
  font-family: Helvetica, sans-serif;
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="times"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="times"]::before {
  content: 'Times New Roman';
  font-family: 'Times New Roman', serif;
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="georgia"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="georgia"]::before {
  content: 'Georgia';
  font-family: Georgia, serif;
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="verdana"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="verdana"]::before {
  content: 'Verdana';
  font-family: Verdana, sans-serif;
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="courier"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="courier"]::before {
  content: 'Courier New';
  font-family: 'Courier New', monospace;
}
.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="outfit"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="outfit"]::before {
  content: 'Outfit';
  font-family: 'Outfit', sans-serif;
}

/* Apply font families to content */
.ql-font-arial {
  font-family: Arial, sans-serif !important;
}
.ql-font-helvetica {
  font-family: Helvetica, sans-serif !important;
}
.ql-font-times {
  font-family: 'Times New Roman', serif !important;
}
.ql-font-georgia {
  font-family: Georgia, serif !important;
}
.ql-font-verdana {
  font-family: Verdana, sans-serif !important;
}
.ql-font-courier {
  font-family: 'Courier New', monospace !important;
}
.ql-font-outfit {
  font-family: 'Outfit', sans-serif !important;
}

/* Apply font sizes to content */
.ql-size-10px { font-size: 10px !important; }
.ql-size-12px { font-size: 12px !important; }
.ql-size-14px { font-size: 14px !important; }
.ql-size-16px { font-size: 16px !important; }
.ql-size-18px { font-size: 18px !important; }
.ql-size-20px { font-size: 20px !important; }
.ql-size-24px { font-size: 24px !important; }
.ql-size-28px { font-size: 28px !important; }
.ql-size-32px { font-size: 32px !important; }
.ql-size-36px { font-size: 36px !important; }
.ql-size-48px { font-size: 48px !important; }

/* Custom toolbar styles */
.ql-toolbar {
    border: 1px solid #ccc;
    border-bottom: none;
}

.ql-container {
    border: 1px solid #ccc;
    font-family: inherit;
}
</style>

<script>
// Add global flag to track Quill loading
window.quillReady = false;

// Custom Quill font size configuration using classes (better compatibility)
var SizeClass = Quill.import('attributors/class/size');
SizeClass.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px'];
Quill.register(SizeClass, true);

// Custom font family configuration using classes
var FontClass = Quill.import('attributors/class/font');
FontClass.whitelist = ['arial', 'helvetica', 'times', 'georgia', 'verdana', 'courier', 'outfit'];
Quill.register(FontClass, true);

window.addEventListener('load', function() {
    if (typeof Quill !== 'undefined') {
        window.quillReady = true;
        console.log('Quill is ready');
    }
});
</script>

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

    .c-node-ap__auction-results{
        margin-right: 36px;
        margin-bottom: 24px;
        display: inline-block;
        background-color: #f8f9fa;
        border-color: #DBDCDD;
        border: 1px solid;
        border-radius: 4px;
        padding: 24px;
        font-size: 1rem;
    }

    .c-node-ap__fundraising-target{
        margin-bottom: 12px;
    }

    .c-node-ap__auction-total-label {
        margin-bottom: 12px;
        font-size: 1.25rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #355159
    }
    .c-node-ap__auction-total-amount {
        font-size: 2rem;
        line-height: 1.5;
        color: #d9b730;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
    }

    .c-node-ap__totalizer{
        height: 18px;
        border-radius: 12px;
        --color-ui: #d9b730;
    }

    .c-node-ap__auction-total-component-label{
        color: #6d6e71
    }

    .c-node-ap__auction-total-component-amount{
        font-size: 1rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #000
    }
    .c-view__item.c-view__item--teaser {
        width: 100% !important;
        max-width: 100% !important;
        flex-basis: 100% !important;
        min-width: 330px !important;
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
      margin: 0;
      padding: 0;
    }

    .app {
      display: flex;
      height: calc(100vh - 60px); /* Subtract header height */
    }

    .sidebar {
      width: 280px;
      background: #ffffff;
      border-right: 1px solid var(--border-color);
      padding: 0;
      overflow-y: auto;
      box-shadow: 2px 0 4px rgba(0,0,0,0.05);
    }

    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid var(--border-color);
      background: #f8f9fa;
    }

    .sidebar-header h2 {
      margin: 0;
      color: var(--primary-color);
      font-size: 18px;
      font-weight: 600;
    }

    .sidebar-content {
      padding: 20px;
    }

    .sidebar-controls {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .sidebar-tab-btn {
      flex: 1;
      padding: 8px 12px;
      background: #f8f9fa;
      border: 1px solid var(--border-color);
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      color: #6b7280;
      transition: all 0.2s;
      text-align: center;
      min-width: 80px;
    }

    .sidebar-tab-btn:hover {
      background: #e5e7eb;
      color: #374151;
    }

    .sidebar-tab-btn.active {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
    }

    .save-btn {
      width: 100%;
      padding: 10px;
      background: #10b981;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 8px;
      transition: all 0.2s;
    }

    .save-btn:hover {
      background: #059669;
    }

    .back-btn {
      padding: 8px 12px;
      background: #6b7280;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      margin-bottom: 12px;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .back-btn:hover {
      background: #4b5563;
    }

    .back-btn a {
      color: white;
      text-decoration: none;
    }

    .tab-section {
      margin-top: 20px;
    }

    .tab-section h3 {
      font-size: 16px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 1px solid #e5e7eb;
    }

    .component-list {
      display: grid;
      gap: 8px;
    }

    .component-item {
      padding: 12px 16px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      cursor: move;
      transition: all 0.2s;
      font-size: 14px;
      font-weight: 500;
      color: #374151;
      position: relative;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .component-item:before {
      content: "⋮⋮";
      color: #9ca3af;
      font-size: 12px;
      letter-spacing: -2px;
      line-height: 1;
    }

    .component-item:hover {
      background: #f8f9fa;
      border-color: var(--primary-color);
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
      color: var(--primary-color);
    }

    .component-item:active {
      transform: translateY(0);
      box-shadow: 0 1px 3px rgba(59, 130, 246, 0.1);
    }

    .canvas {
      flex: 1;
      background: var(--bg-color);
      padding: 40px;
      overflow-y: auto;
    }

    .page {
      max-width: 100%;
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
      cursor: grab;
    }

    .component:hover {
      border-color: var(--primary-color);
    }

    .component.selected {
      outline: 2px solid var(--primary-color);
    }

    /* Enhanced drag handle indicator */
    .component::before {
      content: '⋮⋮';
      position: absolute;
      top: 5px;
      right: 5px;
      color: #6c757d;
      font-size: 12px;
      opacity: 0;
      transition: opacity 0.2s ease;
      pointer-events: none;
      letter-spacing: -2px;
    }

    .component:hover::before {
      opacity: 0.7;
    }

    .component:active {
      cursor: grabbing;
    }

    /* Video component specific styling in page builder */
    .component[data-type="video"] {
      cursor: pointer;
      border: 2px dashed transparent;
      transition: all 0.3s ease;
    }

    .component[data-type="video"]:hover {
      border-color: var(--primary-color) !important;
      background-color: rgba(0, 123, 255, 0.05);
    }

    .component[data-type="video"].selected {
      border-color: var(--primary-color) !important;
      background-color: rgba(0, 123, 255, 0.1);
    }

    /* Disable video interaction in page builder */
    .component[data-type="video"] video,
    .component[data-type="video"] iframe {
      pointer-events: none !important;
      user-select: none !important;
    }

    .component[data-type="video"] .video-container {
      position: relative;
    }

    .inner-section-component {
      position: relative;
      transition: all 0.3s ease;
    }

    .inner-section-component:hover {
      box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
    }

    .column-container {
      margin-top: 25px;
      min-height: 60px;
    }

    .inner-column {
      border: 1px dashed #adb5bd;
      border-radius: 4px;
      background-color: transparent;
      min-height: 60px;
      padding: 10px;
      position: relative;
      transition: all 0.3s ease;
      margin-bottom: 15px;
    }

    .inner-column:hover {
      border-color: var(--primary-color);
      background-color: #f0f9ff;
    }

    /* Enhanced styles for drag and drop functionality */
    .inner-column.sortable-drag-over {
      border-color: #22c55e !important;
      background-color: #f0fdf4 !important;
      transform: scale(1.02);
    }

    .component.sortable-ghost {
      opacity: 0.5;
      background-color: #e5e7eb;
      border: 2px dashed #9ca3af;
    }

    .component.sortable-chosen {
      cursor: grabbing !important;
      transform: rotate(3deg);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      z-index: 1000;
    }

    .component {
      cursor: grab;
    }

    .component:active {
      cursor: grabbing;
    }

    .column-dropzone {
      text-align: center;
      color: #6c757d;
      font-size: 12px;
      padding: 20px 5px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 80%;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }

    .inner-column .component {
      margin: 5px 0;
      width: 100%;
    }

    .inner-dropzone {
      transition: all 0.3s ease;
    }

    .inner-dropzone:hover {
      border-color: var(--primary-color) !important;
      background-color: #f0f9ff !important;
    }

    .inner-section-component .component {
      margin: 5px 0;
    }

    .section-label {
      position: absolute;
      top: 5px;
      left: 10px;
      font-size: 12px;
      color: #6c757d;
      background-color: #fff;
      padding: 2px 6px;
      border-radius: 4px;
      border: 1px solid #ddd;
      z-index: 10;
    }

    /* Responsive adjustments for mobile and tablet */
    @media (max-width: 768px) {
      .inner-column {
        min-height: 40px;
        margin-bottom: 10px;
      }
      
      .column-dropzone {
        padding: 15px 5px;
        font-size: 11px;
      }
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

    .component-controls .btn {
      cursor: pointer !important;
      pointer-events: auto !important;
      position: relative;
      z-index: 1000;
    }

    .btn {
      padding: 4px 8px;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-bottom: 5px
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

    .gallery-thumb.selected, .slider-thumb.selected {
        border: 2px solid #007bff !important;
        box-shadow: 0 0 0 2px #007bff33;
    }
    .deselect-btn {
        display: none;
    }
    .gallery-thumb-wrapper:hover .deselect-btn,
    .slider-thumb-wrapper:hover .deselect-btn {
        display: block !important;
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

    .page-settings-controls {
      padding: 15px 0;
    }

    .page-settings-controls .form-group {
      margin-bottom: 20px;
    }

    .page-settings-controls label {
      font-weight: 600;
      margin-bottom: 8px;
      color: #374151;
    }

    .page-settings-controls input[type="color"] {
      height: 40px;
      padding: 2px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      cursor: pointer;
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

    /* Numbered Timeline Component Styles */
    .numbered-timeline-component {
        width: 100%;
        max-width: 600px;
    }

    .timeline-container {
        position: relative;
    }

    .timeline-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        margin-bottom: 40px;
    }

    .timeline-number {
        background-color: transparent;
        border: 3px solid #22c55e;
        color: #22c55e;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .timeline-content {
        flex: 1;
        margin-left: 20px;
        margin-top: 5px;
    }

    .timeline-content p {
        margin: 0;
        line-height: 1.6;
    }

    .timeline-dash-top,
    .timeline-dash-middle,
    .timeline-dash-bottom {
        position: absolute;
        left: 19px;
        width: 2px;
        height: 10px;
        border-left: 2px dashed #22c55e;
    }

    .timeline-dash-top {
        top: 40px;
    }

    .timeline-dash-middle {
        top: 55px;
    }

    .timeline-dash-bottom {
        top: 70px;
    }

    /* Timeline responsive styles */
    @media (max-width: 768px) {
        .numbered-timeline-component {
            max-width: 100%;
        }
        
        .timeline-content {
            margin-left: 15px;
        }
        
        .timeline-number {
            width: 35px;
            height: 35px;
            font-size: 16px;
            border-width: 2px;
        }
        
        .timeline-dash-top,
        .timeline-dash-middle,
        .timeline-dash-bottom {
            left: 16px;
        }
    }

    /* Enhanced Mobile and Tablet Responsive Fixes */
    @media screen and (max-width: 1199px) and (min-width: 768px) {
        /* Tablet specific styles */
        .container { max-width: 100%; padding: 0 20px; }
        .inner-column { margin-bottom: 15px; }
        .component { margin-bottom: 15px; }
        
        /* Fix column spacing on tablets */
        .row { margin: 0 -10px; }
        .col, [class*="col-"] { padding: 0 10px; }
        
        /* Investment/Charity components tablet fixes */
        .investment-tier-component, .perk-wrap { 
            margin-bottom: 20px;
            max-width: 100%;
        }
        
        /* Image components */
        .image-component img { max-width: 100%; height: auto; }
        
        /* Button components */
        .button-component { 
            margin: 10px 0; 
            display: block;
            width: 100%;
        }
    }

    @media screen and (max-width: 767px) {
        /* Mobile specific styles */
        .container, .container-fluid { 
            padding: 0 10px !important; 
            margin: 0 !important;
            max-width: 100% !important;
        }
        
        .row { 
            margin: 0 -5px !important; 
            display: flex;
            flex-direction: column;
        }
        
        .col, [class*="col-"] { 
            padding: 0 5px !important; 
            flex: 1;
            max-width: 100% !important;
            width: 100% !important;
        }
        
        /* Inner columns mobile behavior */
        .inner-column {
            display: block !important;
            width: 100% !important;
            float: none !important;
            margin-bottom: 10px;
            min-height: 30px;
        }
        
        /* Component mobile fixes */
        .component {
            margin-bottom: 10px !important;
            max-width: 100% !important;
            overflow: hidden;
        }
        
        /* Text components */
        .text-component, .heading-component {
            font-size: 14px !important;
            line-height: 1.4 !important;
            word-wrap: break-word;
            hyphens: auto;
        }
        
        .heading-component {
            font-size: 18px !important;
        }
        
        /* Button components mobile */
        .button-component {
            display: block !important;
            width: 100% !important;
            margin: 10px 0 !important;
            padding: 12px 20px !important;
            text-align: center;
            box-sizing: border-box;
        }
        
        /* Image components mobile */
        .image-component {
            text-align: center;
            margin: 10px 0;
        }
        
        .image-component img {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
            display: block;
            margin: 0 auto;
        }
        
        /* Investment/Charity specific mobile fixes */
        .investment-tier-component, .perk-wrap {
            display: block !important;
            width: 100% !important;
            margin: 10px 0 !important;
            padding: 15px 10px !important;
            box-sizing: border-box;
            overflow: hidden;
        }
        
        .investment-tier, .charity-tier {
            width: 100% !important;
            margin-bottom: 15px !important;
            padding: 10px !important;
        }
        
        /* Form components mobile */
        .form-component, .custom-form-component {
            width: 100% !important;
            margin: 10px 0 !important;
        }
        
        .form-component input,
        .form-component select,
        .form-component textarea,
        .custom-form-component input,
        .custom-form-component select,
        .custom-form-component textarea {
            width: 100% !important;
            margin-bottom: 10px !important;
            padding: 10px !important;
            box-sizing: border-box;
            font-size: 16px !important; /* Prevent zoom on iOS */
        }
        
        /* Timeline components mobile */
        .numbered-timeline-component {
            margin: 10px 0 !important;
            padding: 10px !important;
        }
        
        .timeline-content {
            margin-left: 10px !important;
            font-size: 14px !important;
        }
        
        /* Grid components mobile */
        .grid-component {
            display: block !important;
        }
        
        .grid-component > * {
            width: 100% !important;
            margin-bottom: 10px !important;
        }
        
        /* Prevent horizontal overflow */
        * {
            max-width: 100%;
            box-sizing: border-box;
        }
        
        /* Hidden elements on mobile if needed */
        .hide-mobile {
            display: none !important;
        }
        
        /* Show mobile-only elements */
        .show-mobile {
            display: block !important;
        }
        
        /* Fix canvas mobile view */
        .canvas.mobile-view {
            width: 375px !important;
            padding: 10px !important;
        }
        
        .canvas.mobile-view .component {
            max-width: 100% !important;
        }
    }

    /* Canvas Preview Mode Styles */
    .canvas.tablet-view {
        width: 768px;
        max-width: 768px;
        padding: 15px;
    }
    
    .canvas.tablet-view .component {
        max-width: 100%;
    }
    
    .canvas.tablet-view .inner-column {
        margin-bottom: 15px;
    }
    
    .canvas.mobile-view {
        width: 375px;
        max-width: 375px;
        padding: 10px;
        overflow-x: hidden;
    }
    
    .canvas.mobile-view .component {
        max-width: 100% !important;
        margin-bottom: 10px;
        overflow: hidden;
    }
    
    .canvas.mobile-view .inner-column {
        width: 100% !important;
        display: block !important;
        float: none !important;
        margin-bottom: 10px;
    }
    
    .canvas.mobile-view .row {
        margin: 0 -5px;
        display: flex;
        flex-direction: column;
    }
    
    .canvas.mobile-view .col,
    .canvas.mobile-view [class*="col-"] {
        padding: 0 5px;
        flex: 1;
        max-width: 100% !important;
        width: 100% !important;
    }

    /* image modal css */

    #largeImageModal {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.7); display: none; justify-content: center; align-items: center; z-index: 99999;
}
#largeImageModal .modal-content {
    background: #fff; border-radius: 10px; padding: 20px; position: relative;
}
#largeImageModal .close {
    position: absolute; top: 10px; right: 20px; cursor: pointer; font-size: 32px; color: #333;
}

/* css for gallery */

/* Responsive Preview Header Styles */
.preview-header {
  background: white;
  border-bottom: 1px solid var(--border-color);
  padding: 12px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.preview-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.device-preview {
  display: flex;
  gap: 8px;
  background: #f8f9fa;
  padding: 4px;
  border-radius: 8px;
  border: 1px solid var(--border-color);
}

.device-btn {
  padding: 8px 12px;
  background: transparent;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  color: #6b7280;
  transition: all 0.2s;
  font-size: 16px;
}

.device-btn:hover {
  background: white;
  color: var(--primary-color);
}

.device-btn.active {
  background: var(--primary-color);
  color: white;
  box-shadow: 0 1px 3px rgba(59, 130, 246, 0.3);
}

.preview-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--border-color);
  color: #6b7280;
}

.btn-outline:hover {
  background: #f8f9fa;
  border-color: var(--primary-color);
  color: var(--primary-color);
}

.btn-outline:disabled {
  background: transparent;
  border-color: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
}

.btn-outline:disabled:hover {
  background: transparent;
  border-color: #e5e7eb;
  color: #9ca3af;
}

.btn-primary {
  background: var(--primary-color);
  border: 1px solid var(--primary-color);
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
  border-color: #2563eb;
}

/* Device specific canvas styles */
.canvas.tablet-view .page {
  max-width: 768px;
  border: 2px solid #6c757d;
  border-radius: 8px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  background: #fff;
}

.canvas.mobile-view .page {
  max-width: 375px;
  border: 2px solid #6c757d;
  border-radius: 12px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  background: #fff;
}

/* Add device frame styling */
.canvas.tablet-view {
  background: #f8f9fa;
  padding: 20px;
}

.canvas.mobile-view {
  background: #f8f9fa;
  padding: 30px 20px;
}

/* Force Bootstrap responsive behavior in preview modes */
/* Tablet View (md breakpoint) - simulate 768px viewport */
.canvas.tablet-view .inner-column.col-lg-6.col-md-6 {
  width: 50% !important;
}

.canvas.tablet-view .inner-column.col-lg-4.col-md-6 {
  width: 50% !important;
}

.canvas.tablet-view .inner-column.col-lg-3.col-md-6 {
  width: 50% !important;
}

.canvas.tablet-view .inner-column.col-lg-2.col-md-4 {
  width: 33.333333% !important;
}

/* Mobile View (sm breakpoint) - simulate 375px viewport */
.canvas.mobile-view .inner-column.col-lg-6.col-md-6.col-sm-12,
.canvas.mobile-view .inner-column.col-lg-4.col-md-6.col-sm-12,
.canvas.mobile-view .inner-column.col-lg-3.col-md-6.col-sm-12,
.canvas.mobile-view .inner-column.col-lg-2.col-md-4.col-sm-6,
.canvas.mobile-view .inner-column.col-12 {
  width: 100% !important;
}

/* Full-width section visualization in canvas */
.component.inner-section-component[data-full-width="true"] {
  position: relative;
  margin-left: -40px !important; /* Offset canvas padding */
  margin-right: -40px !important;
  border-left: 3px solid #007bff !important;
  border-right: 3px solid #007bff !important;
  padding-left: 40px;
  padding-right: 40px;
  background: linear-gradient(90deg, #f8f9ff 0%, #fff 20%, #fff 80%, #f8f9ff 100%) !important;
}

.component.inner-section-component[data-full-width="true"]::before {
  content: "⬌ FULL WIDTH SECTION ⬌";
  position: absolute;
  top: -1px;
  left: 50%;
  transform: translateX(-50%);
  background: #007bff;
  color: white;
  padding: 2px 12px;
  font-size: 10px;
  font-weight: bold;
  border-radius: 0 0 6px 6px;
  z-index: 10;
}

/* Full-width sections break out of page container in canvas */
.page .component.inner-section-component[data-full-width="true"] {
  width: calc(100% + 80px) !important; /* Account for page padding */
  margin-left: -40px !important;
  margin-right: -40px !important;
  position: relative;
}

/* Mobile canvas full-width adjustments */
.canvas.mobile-view .component.inner-section-component[data-full-width="true"] {
  margin-left: -40px !important;
  margin-right: -40px !important;
  width: calc(100% + 80px) !important;
}

/* Tablet canvas full-width adjustments */
.canvas.tablet-view .component.inner-section-component[data-full-width="true"] {
  margin-left: -40px !important;
  margin-right: -40px !important;
  width: calc(100% + 80px) !important;
}

/* Special case for 5-6 column layouts on mobile that use col-sm-6 */
.canvas.mobile-view .inner-column.col-sm-6 {
  width: 50% !important;
}

/* Ensure proper spacing in responsive views */
.canvas.tablet-view .inner-column,
.canvas.mobile-view .inner-column {
  margin-bottom: 15px !important;
}


.gallery-select-img.selected {
    border: 3px solid #007bff !important;
    box-shadow: 0 0 0 2px #007bff33;
}


button a {
    color: white;
    text-decoration: none;
}

button a:hover {
    color: black;

}

.ticket-mask {
        --mask: conic-gradient(from 45deg at left,#0000,#000 1deg 89deg,#0000 90deg) left/51% 16.00px repeat-y,conic-gradient(from -135deg at right,#0000,#000 1deg 89deg,#0000 90deg) 100% calc(50% + 8px)/51% 16.00px repeat-y;
        -webkit-mask: var(--mask);
        mask: var(--mask);
        padding: 1.5rem;
        background-color: #eee;
        border: unset;
    }

    .page {
      max-width: 100%;
      min-height: 1000px;
      margin: 0 auto;
      background: white;
      padding: 40px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      border-radius: 8px;
      --page-bg-color: {{ $data->background_color }}; /* CSS variable for dynamic page background */
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
    
    /* Insertion zones between components */
    .insertion-zone {
      height: 8px;
      margin: 5px 0;
      border-radius: 4px;
      background: transparent;
      border: 2px dashed transparent;
      transition: all 0.2s ease;
      opacity: 0;
      position: relative;
    }
    
    .insertion-zone.drag-active {
      opacity: 1;
      background: #e3f2fd;
      border-color: #2196f3;
    }
    
    .insertion-zone.drag-over {
      height: 20px;
      background: #1976d2;
      border-color: #1976d2;
      box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
    }
    
    .insertion-zone::before {
      content: "Drop here to insert";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 11px;
      color: #1976d2;
      background: #fff;
      padding: 2px 8px;
      border-radius: 12px;
      white-space: nowrap;
      opacity: 0;
      transition: opacity 0.2s ease;
    }
    
    .insertion-zone.drag-over::before {
      opacity: 1;
      color: #fff;
      background: rgba(255, 255, 255, 0.2);
    }
    
    /* SortableJS drag states */
    .sortable-ghost {
      opacity: 0.3;
    }
    
    .sortable-chosen {
      cursor: grabbing;
    }
    
    .sortable-drag {
      cursor: grabbing;
      transform: rotate(2deg);
    }
    
    .sortable-drag-over {
      background: #e3f2fd !important;
      border-color: #2196f3 !important;
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
      margin-bottom: 5px
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

    /* Responsive Spacing Controls */
    .responsive-spacing-group {
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        background: #f8f9fa;
    }

    .spacing-header {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .device-tabs {
        display: flex;
        margin-bottom: 12px;
        background: #fff;
        border-radius: 6px;
        padding: 3px;
        border: 1px solid #dee2e6;
    }

    .device-tab {
        flex: 1;
        padding: 8px 12px;
        background: transparent;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: #6c757d;
        font-size: 12px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .device-tab:hover {
        background: #e9ecef;
        color: #495057;
    }

    .device-tab.active {
        background: #007bff;
        color: white;
        box-shadow: 0 1px 3px rgba(0, 123, 255, 0.3);
    }

    .spacing-controls {
        position: relative;
    }

    .device-content {
        display: none;
    }

    .device-content.active {
        display: block;
    }

    .spacing-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .spacing-item {
        display: flex;
        flex-direction: column;
    }

    .spacing-item label {
        font-size: 11px;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .spacing-item input {
        padding: 6px 8px;
        font-size: 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: white;
    }

    .spacing-item input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
    }
    
    .border-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .border-item {
        display: flex;
        flex-direction: column;
    }

    .border-item label {
        font-size: 11px;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .border-item input,
    .border-item select {
        padding: 6px 8px;
        font-size: 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: white;
    }

    .border-item input:focus,
    .border-item select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
    }

    .border-item input[type="color"] {
        width: 100%;
        height: 32px;
        padding: 2px;
        cursor: pointer;
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
    
    /* image modal css */
    
    #largeImageModal {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.7); display: none; justify-content: center; align-items: center; z-index: 99999;
    }
    #largeImageModal .modal-content {
    background: #fff; border-radius: 10px; padding: 20px; position: relative;
    }
    #largeImageModal .close {
    position: absolute; top: 10px; right: 20px; cursor: pointer; font-size: 32px; color: #333;
    }
    
    /* css for gallery */
    
    
    .gallery-select-img.selected {
    border: 3px solid #007bff !important;
    box-shadow: 0 0 0 2px #007bff33;
    }
    
    
    button a {
    color: white;
    text-decoration: none;
    }
    
    button a:hover {
    color: black;
    
    }

    /* Structure Toggle Button */
    .structure-toggle-btn {
        width: 100%;
        padding: 10px;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        margin-top: 8px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .structure-toggle-btn:hover {
        background: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    /* Floating Structure Panel */
    .floating-structure-panel {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 350px;
        max-width: 90vw;
        max-height: 80vh;
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        z-index: 1050;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: box-shadow 0.3s ease;
    }

    .floating-structure-panel:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .floating-structure-panel.dragging {
        user-select: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        transform: none;
    }

    .floating-panel-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        cursor: move;
        user-select: none;
    }

    .floating-panel-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .close-panel-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 18px;
    }

    .close-panel-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .floating-panel-content {
        padding: 20px;
        max-height: calc(80vh - 80px);
        overflow-y: auto;
    }

    /* Updated Structure Panel Styles */
    .structure-header {
        margin-bottom: 16px;
    }

    .structure-header small {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .structure-tree {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        background: #f9fafb;
        margin-bottom: 16px;
    }

    .structure-item {
        padding: 8px 12px;
        margin: 4px 0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        background: white;
        border: 1px solid transparent;
        user-select: none;
    }

    .structure-item:hover {
        background: #f3f4f6;
        border-color: var(--primary-color);
        transform: translateX(3px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .structure-item.selected {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .structure-item.dragging {
        opacity: 0.6;
        transform: rotate(2deg);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .structure-item.drag-over {
        border-top: 3px solid var(--primary-color);
        background: #f0f9ff;
    }

    .structure-item[data-level="0"] {
        font-weight: 600;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .structure-item[data-level="0"]:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: none;
    }

    .structure-item[data-level="1"] {
        margin-left: 16px;
        border-left: 3px solid #e5e7eb;
        padding-left: 16px;
        position: relative;
    }

    .structure-item[data-level="1"]:before {
        content: '';
        position: absolute;
        left: -3px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--primary-color);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .structure-item[data-level="1"]:hover:before {
        opacity: 1;
    }

    .structure-item[data-level="2"] {
        margin-left: 32px;
        border-left: 3px solid #e5e7eb;
        padding-left: 16px;
    }

    .structure-item[data-level="3"] {
        margin-left: 48px;
        border-left: 2px solid #d1d5db;
        padding-left: 12px;
        background: rgba(16, 185, 129, 0.05);
        border-radius: 4px;
        margin-bottom: 2px;
    }

    .structure-item[data-level="4"] {
        margin-left: 64px;
        border-left: 1px solid #e5e7eb;
        padding-left: 8px;
        background: rgba(16, 185, 129, 0.03);
        border-radius: 3px;
        margin-bottom: 1px;
        font-size: 0.85em;
    }

    .structure-item.section-header {
        background: rgba(139, 92, 246, 0.1);
        border-radius: 4px;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .structure-item.section-header:hover {
        background: rgba(139, 92, 246, 0.15);
    }

    .structure-item.nested-column {
        background: rgba(139, 92, 246, 0.1);
        border-radius: 4px;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .structure-item.nested-component {
        font-size: 0.9em;
    }

    .structure-item.nested-component:hover {
        background: rgba(16, 185, 129, 0.1);
    }

    .structure-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .structure-actions .btn {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .structure-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Panel Animation */
    .floating-structure-panel {
        animation: fadeInScale 0.3s ease-out;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    /* Overlay for floating panel */
    .structure-panel-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        backdrop-filter: blur(2px);
    }
  </style>



  </style>
</head>
<body>
    @php
        $groups = \App\Models\User::where('website_id', $data->website ? $data->website->id : 0)->where('role','group_leader')->get();
    @endphp
    <input type="hidden" name="page_id" id="page_id" value="{{ $data->id }}">
    <input type="hidden" name="website_type" id="website_type" value="{{ $data->website ? $data->website->type : 'fundraiser' }}">
  
  <!-- Responsive Preview Header -->
  <div class="preview-header">
    <div class="preview-controls">
      <div class="device-preview">
        <button class="device-btn active" data-device="desktop" title="Desktop View">
          <i class="bi bi-laptop"></i>
        </button>
        <button class="device-btn" data-device="tablet" title="Tablet View">
          <i class="bi bi-tablet"></i>
        </button>
        <button class="device-btn" data-device="mobile" title="Mobile View">
          <i class="bi bi-phone"></i>
        </button>
      </div>
      
      <div class="preview-actions">
        <button class="btn btn-outline" onclick="undoLastAction()" title="Undo">
          <i class="bi bi-arrow-counterclockwise"></i>
        </button>
        <button class="btn btn-outline" onclick="redoLastAction()" title="Redo">
          <i class="bi bi-arrow-clockwise"></i>
        </button>
        <button class="btn btn-primary" onclick="previewPage()" title="Preview">
          <i class="bi bi-eye"></i> Preview
        </button>
      </div>
    </div>
  </div>

  <div class="app">
    <div class="sidebar">
        <div class="sidebar-header">
            <button class="back-btn">
                <a href="/admins/page">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </button>
            <h2><i class="bi bi-tools"></i> Page Builder</h2>
        </div>
        
        <div class="sidebar-content">
            <div class="sidebar-controls">
                <button class="sidebar-tab-btn active" onclick="showTab('componentsTab')">
                    <i class="bi bi-puzzle"></i> Components
                </button>
                <button class="sidebar-tab-btn" onclick="showTab('featuresTab')">
                    <i class="bi bi-star"></i> Features
                </button>
                <button class="sidebar-tab-btn" onclick="showTab('pageSettingsTab')">
                    <i class="bi bi-gear"></i> Page Settings
                </button>
            </div>
            
            <button class="structure-toggle-btn" onclick="toggleStructurePanel()">
                <i class="bi bi-diagram-3"></i> Structure
            </button>
            
            <button class="save-btn" onclick="saveBuilderState()">
                <i class="bi bi-check-circle"></i> Save Page
            </button>
            
            <button class="save-btn" onclick="showSaveAsTemplateModal()" style="background: #28a745; margin-left: 10px;">
                <i class="bi bi-file-earmark-plus"></i> Save as Template
            </button>
            
            <button class="save-btn" onclick="showApplyTemplateModal()" style="background: #17a2b8; margin-left: 10px;">
                <i class="bi bi-clipboard-plus"></i> Apply Template
            </button>

            <div id="componentsTab" class="tab-section">
                <h3><i class="bi bi-collection"></i> Components</h3>
                <div class="drag-drop-info" style="background: #e3f2fd; border: 1px solid #90caf9; border-radius: 4px; padding: 8px; margin-bottom: 15px; font-size: 12px; color: #0d47a1;">
                    <i class="fas fa-info-circle"></i> <strong>New:</strong> Drag components between columns! Hover over components in columns to see the drag handle (⋮⋮). You can also drop components between existing ones - look for blue insertion zones while dragging.
                </div>
                <div class="component-list">
                <div class="component-item" draggable="true" data-type="inner-section"><i class="fas fa-layer-group me-2"></i>Inner Section</div>
                <div class="component-item" draggable="true" data-type="text-images"><i class="fas fa-align-left me-2"></i>Text & Images</div>
                <div class="component-item" draggable="true" data-type="feature-grid"><i class="fas fa-th-large me-2"></i>Feature Grid</div>
                <div class="component-item" draggable="true" data-type="investment-tier"><i class="fas fa-coins me-2"></i>Investment Tier</div>
                <div class="component-item" draggable="true" data-type="section-title"><i class="fas fa-heading me-2"></i>Section Title</div>
                <div class="component-item" draggable="true" data-type="text"><i class="fas fa-font me-2"></i>Text Box</div>
                <div class="component-item" draggable="true" data-type="divider"><i class="fas fa-minus me-2"></i>Divider</div>
                <div class="component-item" draggable="true" data-type="custom-banner"><i class="fas fa-flag me-2"></i>Custom Banner</div>
                <div class="component-item" draggable="true" data-type="gallery"><i class="fas fa-images me-2"></i>Gallery</div>
                <div class="component-item" draggable="true" data-type="slider"><i class="fas fa-sliders-h me-2"></i>Slider</div>
                <div class="component-item" draggable="true" data-type="video"><i class="fas fa-video me-2"></i>Video</div>
                <div class="component-item" draggable="true" data-type="faq"><i class="fas fa-question-circle me-2"></i>FAQ</div>
                <div class="component-item" draggable="true" data-type="simple-comments"><i class="fas fa-comment-dots me-2"></i>Simple Comments</div>
                <div class="component-item" draggable="true" data-type="disqus"><i class="fas fa-comments me-2"></i>Disqus Comments</div>
                <div class="component-item" draggable="true" data-type="button"><i class="fas fa-square me-2"></i>Buttons</div>
                <div class="component-item" draggable="true" data-type="full-width-text-image"><i class="fas fa-image me-2"></i>Full Width Text & Image</div>
                <div class="component-item" draggable="true" data-type="alert-message"><i class="fas fa-exclamation-triangle me-2"></i>Alert Message</div>
                <div class="component-item" draggable="true" data-type="press-card"><i class="fas fa-newspaper me-2"></i>Press Card</div>
                <div class="component-item" draggable="true" data-type="heading"><i class="fas fa-heading me-2"></i>Heading</div>
                </div>
            </div>

            <div id="featuresTab" class="tab-section" style="display: none;">
                <h3><i class="bi bi-lightning"></i> Features</h3>
                <div class="component-list">
                <div class="component-item" draggable="true" data-type="auction-list"><i class="fas fa-gavel me-2"></i>Auction List</div>
                <div class="component-item" draggable="true" data-type="event-countdown"><i class="fas fa-clock me-2"></i>Event Countdown</div>
                <div class="component-item" draggable="true" data-type="event-information"><i class="fas fa-calendar me-2"></i>Event Information</div>
                <div class="component-item" draggable="true" data-type="sell-tickets"><i class="fas fa-ticket-alt me-2"></i>Sell Tickets</div>
                <div class="component-item" draggable="true" data-type="whos-coming"><i class="fas fa-users me-2"></i>Who's Coming</div>
                <div class="component-item" draggable="true" data-type="donation-form"><i class="fas fa-heart me-2"></i>Donation Form</div>
                <div class="component-item" draggable="true" data-type="donor-list"><i class="fas fa-list me-2"></i>Donor List</div>
                {{-- <div class="component-item" draggable="true" data-type="donation-slider">Donation Slider</div> --}}
                <div class="component-item" draggable="true" data-type="custom-form"><i class="fas fa-wpforms me-2"></i>Custom Form</div>
                <div class="component-item" draggable="true" data-type="contact-form"><i class="fas fa-envelope me-2"></i>Contact Form</div>
                <div class="component-item" draggable="true" data-type="social-share"><i class="fas fa-share-alt me-2"></i>Sharing Buttons</div>
                <div class="component-item" draggable="true" data-type="auth-form"><i class="fas fa-user-plus me-2"></i>Registration Form</div>
                <div class="component-item" draggable="true" data-type="student-leaderboard"><i class="fas fa-trophy me-2"></i>Leaderboard</div>
                <div class="component-item" draggable="true" data-type="student-listing"><i class="fas fa-graduation-cap me-2"></i>Registered User Listing</div>
                {{-- <div class="component-item" draggable="true" data-type="updates">Updates</div> --}}
                {{-- <div class="component-item" draggable="true" data-type="facebook-comments">Facebook Comments</div> --}}
                <div class="component-item" draggable="true" data-type="sponsorships"><i class="fas fa-handshake me-2"></i>Sponsorships</div>
                <div class="component-item" draggable="true" data-type="site-goal"><i class="fas fa-thermometer-half me-2"></i>Site Goal</div>
                <div class="component-item" draggable="true" data-type="invest-cta"><i class="fas fa-dollar-sign me-2"></i>Investment CTA</div>
                <div class="component-item" draggable="true" data-type="image"><i class="fas fa-image me-2"></i>Image</div>
                <div class="component-item" draggable="true" data-type="numbered-timeline"><i class="fas fa-list-ol me-2"></i>Numbered Timeline</div>
                </div>
            </div>

            <div id="pageSettingsTab" class="tab-section" style="display: none;">
                <h3><i class="bi bi-gear"></i> Page Settings</h3>
                <div class="page-settings-controls">
                    <div class="form-group">
                        <label>Page Background Color</label>
                        <input type="color" id="pageBackgroundColor" value="{{ $data->background_color ?? '#ffffff' }}" oninput="updatePageBackground(this.value)">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="canvas" id="canvas">
      <div class="page" id="page" style="background-color: {{ $data->background_color ?? '#fff' }}">
        <div class="dropzone">Drop components here</div>
      </div>
    </div>

    <div class="properties" id="properties">
      <h3>Properties</h3>
      <div id="property-panel-content">Select a component to edit its properties</div>
    </div>

    <!-- Floating Structure Panel -->
    <div id="floating-structure-panel" class="floating-structure-panel" style="display: none;">
      <div class="floating-panel-header">
        <h4><i class="bi bi-diagram-3"></i> Page Structure</h4>
        <button class="close-panel-btn" onclick="toggleStructurePanel()">
          <i class="bi bi-x"></i>
        </button>
      </div>
      <div class="floating-panel-content">
        <div class="structure-header">
          <small class="text-muted">Click any element to select it on the page</small>
        </div>
        <div id="page-structure-tree" class="structure-tree">
          <div class="structure-item" data-level="0">
            <i class="fas fa-file-alt me-2"></i>
            <span>Page Root</span>
          </div>
          <!-- Dynamic structure will be populated here -->
        </div>
        <div class="structure-actions">
          <button class="btn btn-sm btn-outline-primary" onclick="refreshStructure()">
            <i class="fas fa-sync-alt me-1"></i>Refresh
          </button>
          <button class="btn btn-sm btn-outline-secondary" onclick="expandAllStructure()">
            <i class="fas fa-expand-alt me-1"></i>Expand All
          </button>
          <button class="btn btn-sm btn-outline-secondary" onclick="collapseAllStructure()">
            <i class="fas fa-compress-alt me-1"></i>Collapse All
          </button>
        </div>
      </div>
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

    <div id="fileManagerModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; border:1px solid #ccc; padding:20px; z-index:1000; width:600px; max-height:80vh; overflow:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Select a File</h3>
            <button onclick="closeFileManager()">✖</button>
        </div>
        <input type="file" accept="image/*,video/*" onchange="handleFileUpload(event)">
        <p id="uploadStatus"></p>
        <div id="fileGallery" style="display:flex; flex-wrap:wrap; margin-top:10px; gap:10px;"></div>
    </div>

    <!-- Gallery Large Image Modal -->
    <div id="galleryLargeModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center;">
        <div class="modal-content" style="position:relative; max-width:90vw; max-height:90vh; background:#fff; border-radius:8px; padding:20px;">
            <span style="position:absolute; top:10px; right:20px; cursor:pointer; font-size:24px; color:#333;" onclick="closeGalleryLargeModal()">&times;</span>
            <img id="galleryLargeModalImg" style="max-width:100%; max-height:80vh; object-fit:contain;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px;">
                <button id="galleryPrevBtn" class="btn" style="font-size:18px;">&#8249; Previous</button>
                <button id="galleryNextBtn" class="btn" style="font-size:18px;">Next &#8250;</button>
            </div>
        </div>
    </div>

    <!-- Large Image Modal -->
    <div id="largeImageModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); z-index:99999; justify-content:center; align-items:center;">
        <div class="modal-content" style="background:#fff; border-radius:10px; padding:20px; position:relative; max-width:90vw; max-height:90vh;">
            <span class="close" onclick="closeLargeImageModal()" style="position:absolute; top:10px; right:20px; cursor:pointer; font-size:32px; color:#333;">&times;</span>
            <img id="largeImageModalImg" style="max-width:100%; max-height:80vh; object-fit:contain;">
            <p id="largeImageModalAlt" style="text-align:center; margin-top:10px; color:#666;"></p>
        </div>
    </div>

    <!-- Save as Template Modal -->
    <div id="saveAsTemplateModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); z-index:99999; justify-content:center; align-items:center;">
        <div class="modal-content" style="background:#fff; border-radius:10px; padding:30px; position:relative; max-width:500px; width:90vw;">
            <span class="close" onclick="closeSaveAsTemplateModal()" style="position:absolute; top:15px; right:25px; cursor:pointer; font-size:28px; color:#333;">&times;</span>
            
            <h3 style="margin-bottom:20px; color:#333;">
                <i class="bi bi-file-earmark-plus" style="margin-right:10px; color:#28a745;"></i>
                Save Page as Template
            </h3>
            
            <form id="saveAsTemplateForm" style="margin-bottom:0;">
                <div style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:bold; color:#555;">Template Name</label>
                    <input type="text" id="templateName" name="template_name" 
                           style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; font-size:14px;"
                           placeholder="Enter template name..." required>
                </div>
                
                <div style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:bold; color:#555;">Description</label>
                    <textarea id="templateDescription" name="template_description" 
                              style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; font-size:14px; resize:vertical;"
                              rows="3" placeholder="Describe what this template is for..."></textarea>
                </div>
                
                <div style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:bold; color:#555;">Category</label>
                    <select id="templateCategory" name="template_category" 
                            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; font-size:14px;">
                        <option value="general">General</option>
                        <option value="landing">Landing Page</option>
                        <option value="about">About</option>
                        <option value="contact">Contact</option>
                        <option value="services">Services</option>
                        <option value="portfolio">Portfolio</option>
                        <option value="blog">Blog</option>
                        <option value="e-commerce">E-commerce</option>
                    </select>
                </div>
                
                <div style="margin-bottom:20px;">
                    <label style="display:flex; align-items:center; cursor:pointer;">
                        <input type="checkbox" id="templateIsPublic" name="is_public" checked
                               style="margin-right:8px; transform:scale(1.2);">
                        <span style="color:#555;">Make this template available to all users</span>
                    </label>
                </div>
                
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" onclick="closeSaveAsTemplateModal()" 
                            style="padding:10px 20px; background:#6c757d; color:white; border:none; border-radius:5px; cursor:pointer;">
                        Cancel
                    </button>
                    <button type="button" onclick="savePageAsTemplate()" 
                            style="padding:10px 20px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer;">
                        <i class="bi bi-check-circle" style="margin-right:5px;"></i>
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Apply Template Modal -->
    <div id="applyTemplateModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); z-index:99999; justify-content:center; align-items:center;">
        <div class="modal-content" style="background:#fff; border-radius:10px; padding:30px; position:relative; max-width:700px; width:90vw; max-height:80vh; overflow-y:auto;">
            <span class="close" onclick="closeApplyTemplateModal()" style="position:absolute; top:15px; right:25px; cursor:pointer; font-size:28px; color:#333;">&times;</span>
            
            <h3 style="margin-bottom:20px; color:#333;">
                <i class="bi bi-clipboard-plus" style="margin-right:10px; color:#17a2b8;"></i>
                Apply Template to Page
            </h3>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:10px; font-weight:bold; color:#555;">Filter by Category</label>
                <select id="templateCategoryFilter" onchange="loadTemplatesForApply()" 
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; font-size:14px;">
                    <option value="">All Categories</option>
                    <option value="general">General</option>
                    <option value="landing">Landing Page</option>
                    <option value="about">About</option>
                    <option value="contact">Contact</option>
                    <option value="services">Services</option>
                    <option value="portfolio">Portfolio</option>
                    <option value="blog">Blog</option>
                    <option value="e-commerce">E-commerce</option>
                </select>
            </div>
            
            <div id="templatesContainer" style="max-height:400px; overflow-y:auto; border:1px solid #ddd; border-radius:5px; padding:10px;">
                <p style="text-align:center; color:#666;">Loading templates...</p>
            </div>
            
            <div id="applyWarning" style="display:none; background:#fff3cd; border:1px solid #ffeaa7; color:#856404; padding:10px; border-radius:5px; margin-top:15px;">
                <strong>Warning:</strong> Applying a template will replace all current page content. 
                Make sure to save any important changes first.
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button type="button" onclick="closeApplyTemplateModal()" 
                        style="padding:10px 20px; background:#6c757d; color:white; border:none; border-radius:5px; cursor:pointer;">
                    Cancel
                </button>
                <button type="button" id="applyTemplateBtn" onclick="applySelectedTemplate()" disabled
                        style="padding:10px 20px; background:#17a2b8; color:white; border:none; border-radius:5px; cursor:pointer; opacity:0.5;">
                    <i class="bi bi-clipboard-plus" style="margin-right:5px;"></i>
                    Apply Template
                </button>
            </div>
        </div>
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

    // Responsive Preview Header Functions
    function initDevicePreview() {
      const deviceBtns = document.querySelectorAll('.device-btn');
      const canvas = document.getElementById('canvas');
      
      deviceBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          // Remove active from all buttons
          deviceBtns.forEach(b => b.classList.remove('active'));
          // Add active to clicked button
          this.classList.add('active');
          
          // Remove all device classes
          canvas.classList.remove('tablet-view', 'mobile-view');
          
          // Add appropriate class based on selected device
          const device = this.dataset.device;
          if (device === 'tablet') {
            canvas.classList.add('tablet-view');
          } else if (device === 'mobile') {
            canvas.classList.add('mobile-view');
          }
          
          // Add visual feedback
          showDeviceNotification(device);
          
          // Apply responsive styles for the new device
          setTimeout(() => {
            applyResponsiveStyles();
            updateResponsiveCSS();
            refreshComponentSizes();
          }, 50);
          
          // Refresh layout to ensure proper responsive behavior
          setTimeout(() => {
            triggerLayoutRefresh();
            fixComponentOverflow();
          }, 100);
        });
      });
    }
    
    function showDeviceNotification(device) {
      // Remove existing notification
      const existingNotification = document.querySelector('.device-notification');
      if (existingNotification) {
        existingNotification.remove();
      }
      
      // Create new notification
      const notification = document.createElement('div');
      notification.className = 'device-notification';
      notification.textContent = `Preview: ` + device.charAt(0).toUpperCase() + device.slice(1) + ` View`;
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        z-index: 1000;
        font-size: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: opacity 0.3s ease;
      `;
      
      document.body.appendChild(notification);
      
      // Auto-remove after 2 seconds
      setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
          if (notification.parentNode) {
            notification.remove();
          }
        }, 300);
      }, 2000);
    }
    
    function triggerLayoutRefresh() {
      // Force a reflow to ensure responsive classes are applied correctly
      const innerSections = document.querySelectorAll('.inner-section-component');
      innerSections.forEach(section => {
        const columns = section.querySelectorAll('.inner-column');
        columns.forEach(column => {
          // Force reflow by reading offsetHeight
          const height = column.offsetHeight;
        });
      });
    }

    // Enhanced responsive functions for mobile fixes
    function refreshComponentSizes() {
      const components = document.querySelectorAll('.component');
      components.forEach(component => {
        const content = getContentElement(component);
        if (content) {
          // Force width recalculation
          content.style.width = '';
          content.style.maxWidth = '';
          
          // Apply device-specific constraints
          const canvas = document.getElementById('canvas');
          if (canvas.classList.contains('mobile-view')) {
            content.style.maxWidth = '100%';
            content.style.overflow = 'hidden';
          } else if (canvas.classList.contains('tablet-view')) {
            content.style.maxWidth = '100%';
          }
        }
      });
    }
    
    function fixComponentOverflow() {
      const canvas = document.getElementById('canvas');
      const components = document.querySelectorAll('.component');
      
      components.forEach(component => {
        const content = getContentElement(component);
        if (content) {
          // Fix common overflow issues
          content.style.boxSizing = 'border-box';
          content.style.wordWrap = 'break-word';
          content.style.overflowWrap = 'break-word';
          
          // Device-specific fixes
          if (canvas.classList.contains('mobile-view')) {
            // Mobile specific overflow fixes
            content.style.maxWidth = '100%';
            content.style.minWidth = '0';
            
            // Fix images
            const images = content.querySelectorAll('img');
            images.forEach(img => {
              img.style.maxWidth = '100%';
              img.style.height = 'auto';
              img.style.width = 'auto';
            });
            
            // Fix tables
            const tables = content.querySelectorAll('table');
            tables.forEach(table => {
              table.style.maxWidth = '100%';
              table.style.overflow = 'auto';
            });
            
            // Fix forms
            const inputs = content.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
              input.style.maxWidth = '100%';
              input.style.boxSizing = 'border-box';
            });
          }
        }
      });
    }
    
    function getCurrentPreviewDevice() {
      const canvas = document.getElementById('canvas');
      if (canvas.classList.contains('mobile-view')) return 'mobile';
      if (canvas.classList.contains('tablet-view')) return 'tablet';
      return 'desktop';
    }

    // History Management System for Undo/Redo
    class HistoryManager {
      constructor(maxStates = 50) {
        this.states = [];
        this.currentIndex = -1;
        this.maxStates = maxStates;
        this.isRedoing = false;
        this.isUndoing = false;
      }

      // Save current state to history
      saveState(description = 'Action') {
        // Don't save state during undo/redo operations
        if (this.isRedoing || this.isUndoing) return;

        try {
          const currentState = serializeBuilder();
          const stateData = {
            state: JSON.parse(JSON.stringify(currentState)),
            description: description,
            timestamp: Date.now()
          };

          // Remove any states after current index (when user makes new action after undo)
          this.states = this.states.slice(0, this.currentIndex + 1);
          
          // Add new state
          this.states.push(stateData);
          this.currentIndex++;

          // Limit history size
          if (this.states.length > this.maxStates) {
            this.states.shift();
            this.currentIndex--;
          }

          this.updateUndoRedoButtons();
          console.log(`History saved: ${description} (${this.states.length} states)`);
        } catch (error) {
          console.error('Error saving history state:', error);
        }
      }

      // Undo last action
      undo() {
        if (this.currentIndex <= 0) {
          console.log('Nothing to undo');
          return false;
        }

        try {
          this.isUndoing = true;
          this.currentIndex--;
          const stateToRestore = this.states[this.currentIndex];
          
          console.log(`Undoing to: ${stateToRestore.description}`);
          deserializeBuilder(stateToRestore.state);
          
          // Clear selection to avoid issues with restored components
          if (window.selectedComponent) {
            window.selectedComponent.classList.remove('selected');
            window.selectedComponent = null;
            updatePropertyPanel();
          }

          this.updateUndoRedoButtons();
          
          // Refresh insertion zones and structure panel
          setTimeout(() => {
            refreshInsertionZones();
            updateStructurePanel();
            this.isUndoing = false;
          }, 100);

          return true;
        } catch (error) {
          console.error('Error during undo:', error);
          this.isUndoing = false;
          return false;
        }
      }

      // Redo last undone action
      redo() {
        if (this.currentIndex >= this.states.length - 1) {
          console.log('Nothing to redo');
          return false;
        }

        try {
          this.isRedoing = true;
          this.currentIndex++;
          const stateToRestore = this.states[this.currentIndex];
          
          console.log(`Redoing to: ${stateToRestore.description}`);
          deserializeBuilder(stateToRestore.state);
          
          // Clear selection to avoid issues with restored components
          if (window.selectedComponent) {
            window.selectedComponent.classList.remove('selected');
            window.selectedComponent = null;
            updatePropertyPanel();
          }

          this.updateUndoRedoButtons();
          
          // Refresh insertion zones and structure panel
          setTimeout(() => {
            refreshInsertionZones();
            updateStructurePanel();
            this.isRedoing = false;
          }, 100);

          return true;
        } catch (error) {
          console.error('Error during redo:', error);
          this.isRedoing = false;
          return false;
        }
      }

      // Update undo/redo button states
      updateUndoRedoButtons() {
        const undoBtn = document.querySelector('[onclick="undoLastAction()"]');
        const redoBtn = document.querySelector('[onclick="redoLastAction()"]');

        if (undoBtn) {
          undoBtn.disabled = this.currentIndex <= 0;
          undoBtn.style.opacity = this.currentIndex <= 0 ? '0.5' : '1';
          undoBtn.title = this.currentIndex > 0 ? 
            `Undo: ${this.states[this.currentIndex].description}` : 'Nothing to undo';
        }

        if (redoBtn) {
          redoBtn.disabled = this.currentIndex >= this.states.length - 1;
          redoBtn.style.opacity = this.currentIndex >= this.states.length - 1 ? '0.5' : '1';
          redoBtn.title = this.currentIndex < this.states.length - 1 ? 
            `Redo: ${this.states[this.currentIndex + 1].description}` : 'Nothing to redo';
        }
      }

      // Clear history
      clear() {
        this.states = [];
        this.currentIndex = -1;
        this.updateUndoRedoButtons();
      }

      // Get current state info for debugging
      getInfo() {
        return {
          statesCount: this.states.length,
          currentIndex: this.currentIndex,
          canUndo: this.currentIndex > 0,
          canRedo: this.currentIndex < this.states.length - 1
        };
      }
    }

    // Global history manager instance
    const historyManager = new HistoryManager();

    // Throttled history save for frequent property changes
    let saveHistoryTimeout = null;
    function saveHistoryThrottled(description = 'Property changed', delay = 1000) {
      if (saveHistoryTimeout) {
        clearTimeout(saveHistoryTimeout);
      }
      saveHistoryTimeout = setTimeout(() => {
        historyManager.saveState(description);
        saveHistoryTimeout = null;
      }, delay);
    }

    function undoLastAction() {
      historyManager.undo();
    }

    function redoLastAction() {
      historyManager.redo();
    }

    function previewPage() {
      // Get the current page content
      const pageContent = document.getElementById('page').innerHTML;
      
      // Create preview window
      const previewWindow = window.open('', '_blank', 'width=1200,height=800');
      
      // Get current website type for proper styling
      const websiteTypeElement = document.querySelector('input[name="website_type"]:checked');
      const websiteType = websiteTypeElement ? websiteTypeElement.value : 'charity';
      
      // Frontend CSS (same as page-investment.blade.php)
      const frontendCSS = `
        /* Global full-width support */
        html, body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        #rendered-page {
            width: 100%;
            overflow-x: hidden;
        }
        
        /* Full-width section support - Enhanced */
        .inner-section-fullwidth {
            width: 100vw !important;
            position: relative !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            box-sizing: border-box !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        /* Enable borders and padding for inner-sections */
        .inner-section-fullwidth,
        .inner-section-frontend {
            border: inherit !important;
            padding: inherit !important;
            margin: inherit !important;
        }
        
        /* Force apply styles that might be ignored */
        .inner-section-fullwidth[style],
        .inner-section-frontend[style] {
            border: inherit !important;
            padding: inherit !important;
            margin: inherit !important;
            background: inherit !important;
            background-color: inherit !important;
            background-image: inherit !important;
            background-attachment: inherit !important;
        }
        
        /* Parallax background fix - Enhanced implementation with higher specificity */
        .inner-section-fullwidth[style*="background-attachment: fixed"],
        .inner-section-frontend[style*="background-attachment: fixed"],
        .inner-section-fullwidth[style*="background"][style*="url"],
        .inner-section-frontend[style*="background"][style*="url"] {
            background-attachment: fixed !important;
            background-repeat: no-repeat !important;
            background-position: center center !important;
            background-size: cover !important;
        }
        
        /* Video component comprehensive responsive fixes */
        .video-component,
        .video-container {
            width: 100% !important;
            max-width: 100% !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        .video-component iframe,
        .video-component video,
        .video-container iframe,
        .video-container video {
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
            display: block !important;
        }
        
        /* Force responsive behavior for videos with custom dimensions */
        .video-container[style*="width"],
        .video-container[style*="height"] {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .video-container[style*="width"] iframe,
        .video-container[style*="width"] video,
        .video-container[style*="height"] iframe,
        .video-container[style*="height"] video {
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
            aspect-ratio: 16/9 !important;
        }
        
        /* Investment tier auto-amount fix for full-width sections */
        .inner-section-fullwidth .investment-tier a[href*="/invest?amount"],
        .inner-section-frontend .investment-tier a[href*="/invest?amount"] {
            pointer-events: auto !important;
            display: inline-block !important;
            position: relative !important;
            z-index: 10 !important;
        }
        
        /* Mobile specific fixes */
        @media (max-width: 768px) {
            .inner-section-fullwidth {
                width: 100vw !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                margin-left: calc(-50vw + 50%) !important;
                margin-right: calc(-50vw + 50%) !important;
                max-width: none !important;
            }
            
            .inner-section-fullwidth[style*="background-attachment"],
            .inner-section-frontend[style*="background-attachment"] {
                background-attachment: scroll !important;
            }
            
            .video-component,
            .video-container {
                width: 100% !important;
                height: auto !important;
            }
            
            .video-component iframe,
            .video-component video,
            .video-container iframe,
            .video-container video {
                width: 100% !important;
                height: auto !important;
                min-height: 200px !important;
                max-height: 300px !important;
                aspect-ratio: 16/9 !important;
            }
            
            .video-container[style] {
                width: 100% !important;
                height: auto !important;
                padding-bottom: 56.25% !important;
                position: relative !important;
            }
            
            .video-container[style] iframe,
            .video-container[style] video {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
        }
        
        @media (max-width: 480px) {
            .inner-section-fullwidth {
                width: 100vw !important;
                left: 0 !important;
                transform: none !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        
        /* Hide page-builder specific elements */
        .dropzone { display: none !important; }
        .component-controls { display: none !important; }
        .sortable-placeholder { display: none !important; }
        .ui-sortable-handle { cursor: default !important; }
        .drag-handle { display: none !important; }
        .section-label { display: none !important; }
        .component-type-label { display: none !important; }
      `;
      
      let themeStyles = '';
      if (websiteType === 'investment') {
        themeStyles = '.investment-theme { --primary-color: #d4af37; --secondary-color: #2c3e50; --accent-color: #e8c547; } .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); } .text-primary { color: var(--primary-color) !important; }';
      } else {
        themeStyles = '.charity-theme { --primary-color: #28a745; --secondary-color: #17a2b8; --accent-color: #ffc107; } .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); } .text-primary { color: var(--primary-color) !important; }';
      }
      
      // Build HTML content that matches page-investment.blade.php structure
      let htmlContent = '<!DOCTYPE html><html><head><title>Page Preview - Frontend View</title>';
      htmlContent += '<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
      htmlContent += '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
      htmlContent += '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">';
      htmlContent += '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">';
      htmlContent += '<style>body { background: #f9fafb; margin: 0; padding: 0; }' + frontendCSS + themeStyles + '</style></head>';
      htmlContent += '<body class="' + websiteType + '-theme" style="margin: 0; padding: 0;"><main style="margin-top: 0;"><div id="rendered-page">' + pageContent + '</div></main>';
      htmlContent += '</body></html>';
      
      previewWindow.document.write(htmlContent);
      previewWindow.document.close();
      
      // Focus the preview window
      previewWindow.focus();
    }

    // Client-side Form Data Storage System
    window.formDataStorage = {
        data: {},
        
        // Store form data
        storeFormData: function(formId, fieldName, value) {
            if (!this.data[formId]) {
                this.data[formId] = {};
            }
            this.data[formId][fieldName] = value;
            this.saveToStorage();
        },
        
        // Get form data
        getFormData: function(formId) {
            return this.data[formId] || {};
        },
        
        // Get all stored data
        getAllData: function() {
            return this.data;
        },
        
        // Clear form data
        clearFormData: function(formId) {
            if (formId) {
                delete this.data[formId];
            } else {
                this.data = {};
            }
            this.saveToStorage();
        },
        
        // Save to localStorage
        saveToStorage: function() {
            try {
                localStorage.setItem('pageBuilderFormData', JSON.stringify(this.data));
            } catch (e) {
                console.warn('Could not save form data to localStorage:', e);
            }
        },
        
        // Load from localStorage
        loadFromStorage: function() {
            try {
                const stored = localStorage.getItem('pageBuilderFormData');
                if (stored) {
                    this.data = JSON.parse(stored);
                }
            } catch (e) {
                console.warn('Could not load form data from localStorage:', e);
                this.data = {};
            }
        },
        
        // Submit all collected data with investor information
        submitInvestmentWithAllData: function(investorData) {
            const allData = {
                ...investorData,
                form_data: this.getAllData()
            };
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                allData._token = csrfToken.getAttribute('content');
            }
            
            return fetch('/invest/save-info', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': allData._token
                },
                body: JSON.stringify(allData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear stored data on successful submission
                    this.clearFormData();
                    return data;
                } else {
                    throw new Error(data.message || 'Submission failed');
                }
            });
        }
    };
    
    // Initialize form data storage
    window.formDataStorage.loadFromStorage();
    
    // Auto-save form inputs
    function initFormDataCapture() {
        document.addEventListener('input', function(e) {
            if (e.target.matches('input, select, textarea')) {
                const form = e.target.closest('form');
                if (form) {
                    const formId = form.id || 'default_form';
                    const fieldName = e.target.name || e.target.id || `field_${Date.now()}`;
                    const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value;
                    
                    window.formDataStorage.storeFormData(formId, fieldName, value);
                    
                    // Show visual feedback
                    showFormDataSavedIndicator();
                }
            }
        });
        
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[type="radio"], input[type="checkbox"], select')) {
                const form = e.target.closest('form');
                if (form) {
                    const formId = form.id || 'default_form';
                    const fieldName = e.target.name || e.target.id || `field_${Date.now()}`;
                    const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value;
                    
                    window.formDataStorage.storeFormData(formId, fieldName, value);
                    showFormDataSavedIndicator();
                }
            }
        });
    }
    
    function showFormDataSavedIndicator() {
        // Remove existing indicator
        const existing = document.querySelector('.form-data-saved-indicator');
        if (existing) existing.remove();
        
        // Create new indicator
        const indicator = document.createElement('div');
        indicator.className = 'form-data-saved-indicator';
        indicator.textContent = 'Form data saved';
        indicator.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            z-index: 10000;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        
        document.body.appendChild(indicator);
        
        // Animate in
        setTimeout(() => indicator.style.opacity = '1', 10);
        
        // Animate out
        setTimeout(() => {
            indicator.style.opacity = '0';
            setTimeout(() => indicator.remove(), 300);
        }, 2000);
    }

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
      
      // Handle dropzones
      const dropzone = e.target.closest('.dropzone');
      if (dropzone) {
        dropzone.classList.add('dragover');
      }
      
      // Handle insertion zones
      const insertionZone = e.target.closest('.insertion-zone');
      if (insertionZone) {
        insertionZone.classList.add('drag-over');
      }
      
      // Show all insertion zones when dragging
      const allInsertionZones = document.querySelectorAll('.insertion-zone');
      allInsertionZones.forEach(zone => {
        zone.classList.add('drag-active');
      });
    });

    // Handle drag leave
    document.addEventListener('dragleave', (e) => {
      const dropzone = e.target.closest('.dropzone');
      if (dropzone) {
        dropzone.classList.remove('dragover');
      }
      
      const insertionZone = e.target.closest('.insertion-zone');
      if (insertionZone) {
        insertionZone.classList.remove('drag-over');
      }
      
      // Check if we're leaving the entire canvas area
      if (!e.relatedTarget || !document.getElementById('canvas').contains(e.relatedTarget)) {
        const allInsertionZones = document.querySelectorAll('.insertion-zone');
        allInsertionZones.forEach(zone => {
          zone.classList.remove('drag-active', 'drag-over');
        });
      }
    });

    // Handle drop
    document.addEventListener('drop', (e) => {
      e.preventDefault();
      
      // Clear all drag states
      const allInsertionZones = document.querySelectorAll('.insertion-zone');
      allInsertionZones.forEach(zone => {
        zone.classList.remove('drag-active', 'drag-over');
      });
      
      const dropzone = e.target.closest('.dropzone');
      const insertionZone = e.target.closest('.insertion-zone');
      
      if (dropzone || insertionZone) {
        if (dropzone) {
          dropzone.classList.remove('dragover');
        }
        
        const type = e.dataTransfer.getData('type');
        
        let componentToAdd;
        
        // Check if this is an inner-section component
        if (type === 'inner-section') {
          // Create inner-section normally
          componentToAdd = createComponent(type);
        } else {
          // For any other component, create an inner-section wrapper with 1 column
          // and place the component inside it
          const innerSection = createComponent('inner-section');
          const innerContent = getContentElement(innerSection);
          
          // Set to 1 column
          innerContent.updateColumns(1);
          innerContent._innerSectionData.columns = 1;
          
          // Update the label to indicate it's auto-created
          const sectionLabel = innerContent.querySelector('.section-label');
          if (sectionLabel) {
            sectionLabel.textContent = 'Auto Section (1 Column)';
            sectionLabel.style.display = 'none'; // Hide label for auto sections
          }
          
          // Create the actual component
          const actualComponent = createComponent(type);
          
          // Add the component to the first (and only) column
          const firstColumn = innerContent.querySelector('.inner-column');
          if (firstColumn) {
            firstColumn.appendChild(actualComponent);
            
            // Hide the dropzone text
            const columnDropzone = firstColumn.querySelector('.column-dropzone');
            if (columnDropzone) {
              columnDropzone.style.display = 'none';
            }
          }
          
          componentToAdd = innerSection;
        }

        if (insertionZone) {
          // Insert at the specific position indicated by the insertion zone
          const parentContainer = insertionZone.parentNode;
          parentContainer.insertBefore(componentToAdd, insertionZone.nextSibling);
          
          // Add new insertion zones around the new component
          refreshInsertionZones();
        } else if (dropzone) {
          // Insert the component (either inner-section or wrapped component) before the dropzone
          dropzone.parentNode.insertBefore(componentToAdd, dropzone);

          // Create a new dropzone after the component
          const newDropzone = createDropzone();
          dropzone.parentNode.insertBefore(newDropzone, dropzone);

          // Remove the original dropzone if it's not the last one
          const dropzones = document.querySelectorAll('.dropzone');
          if (dropzones.length > 1) {
            dropzone.remove();
          }
          
          // Add insertion zones around the new component
          refreshInsertionZones();
        }

        // Select the appropriate component
        let componentToSelect;
        if (type === 'inner-section') {
          componentToSelect = componentToAdd;
        } else {
          // Select the actual component inside the auto-created inner-section
          componentToSelect = componentToAdd.querySelector('.inner-column .component');
        }

        // Select the component after a brief delay to ensure DOM is updated
        setTimeout(() => {
          if (componentToSelect) {
            selectComponent(componentToSelect);
          }
          // Update structure panel when a new component is added
          updateStructurePanel();
          
          // Save state to history after component is added
          historyManager.saveState(`Added ${type} component`);
        }, 10);
      }
    });

    // Create a new component
    function createComponent(type) {
      const component = document.createElement('div');
      component.className = 'component';
      component.dataset.type = type;
      
      // Assign unique ID to component
      const existingComponents = document.querySelectorAll('.component').length;
      component.id = `component-` + existingComponents;

      const controls = document.createElement('div');
      controls.className = 'component-controls';
      controls.innerHTML = `
        <button class="btn" onclick="deleteComponent(this, event)">Delete</button>
      `;

      let content;
      switch (type) {

        case 'image':
    content = document.createElement('div');
    content.className = 'single-image-component';
    // Store image data for this component
    content._imageData = {
        src: 'https://via.placeholder.com/400x250',
        alt: 'Image',
        width: '100%',
        height: 'auto',
        objectFit: 'cover',
        link: '',
        openInNewTab: false,
    };
    content.renderImage = function() {
        const d = content._imageData;
        if (!d) return;
        
        // Ensure we have valid values
        const src = d.src || 'https://via.placeholder.com/400x250';
        const alt = d.alt || 'Image';
        const width = d.width || '100%';
        const height = d.height || 'auto';
        const objectFit = d.objectFit || 'cover';
        
        try {
            content.innerHTML = `
                <div class="image-link" style="display:inline-block;">
                    <img src="${src}" alt="${alt}" style="width:${width};height:${height};object-fit:${objectFit};cursor:default;transition:box-shadow .2s;" class="img-preview"/>
                </div>
            `;
            
            // Disable click events in page builder - no modal, no links
            const img = content.querySelector('img');
            if (img) {
                img.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Do nothing in page builder
                };
            }
        } catch (error) {
            console.error('Error rendering image:', error);
            content.innerHTML = '<div style="padding:20px;text-align:center;border:1px dashed #ccc;">Error loading image</div>';
        }
    };
    content.renderImage();
break;

        case 'numbered-timeline':
            content = document.createElement('div');
            content.className = 'numbered-timeline-component';
            // Store timeline data
            content._timelineData = {
                items: [
                    {
                        number: '1',
                        title: 'Nashville:',
                        description: 'Our Nashville CloseCompany project is slated for an early Q2 2025 opening a fully funded project budget.'
                    },
                    {
                        number: '2',
                        title: 'Atlanta:',
                        description: 'In Atlanta, we\'ve secured $400 per square foot in Tenant Improvement funds for our second Close Company, coming to the city in 2025.'
                    },
                    {
                        number: '3',
                        title: 'Municipal Grand Savannah:',
                        description: 'A44 room boutique hotel in the heart of downtown Savannah with three different F&B outlets and a rooftop'
                    }
                ],
                colors: {
                    numberBackground: '#22c55e',
                    numberText: '#22c55e',
                    titleColor: '#22c55e',
                    descriptionColor: '#374151',
                    lineColor: '#22c55e'
                }
            };
            
            content.renderTimeline = function() {
                const d = content._timelineData;
                if (!d || !d.items) return;
                
                // Group items into columns of 4
                const itemsPerColumn = 4;
                const columns = [];
                for (let i = 0; i < d.items.length; i += itemsPerColumn) {
                    columns.push(d.items.slice(i, i + itemsPerColumn));
                }
                
                let columnsHtml = '';
                
                columns.forEach((columnItems, columnIndex) => {
                    let itemsHtml = '';
                    
                    columnItems.forEach((item, index) => {
                        const isLastInColumn = index === columnItems.length - 1;
                        const isLastOverall = (columnIndex * itemsPerColumn + index) === d.items.length - 1;
                        
                        itemsHtml += `
                            <div class="timeline-item" style="position: relative; display: flex; align-items: flex-start; margin-bottom: 60px;">
                                <!-- Single dashed connecting line using border trick (except for last item in column) -->
                                ${!isLastInColumn ? `
                                    <div class="dash-green-line" style="
                                        border-style: none dashed none none;
                                        border-width: 1px 4px 1px 1px;
                                        border-color: transparent ${d.colors.lineColor} transparent transparent;
                                        width: 3px;
                                        height: calc(100% + 60px);
                                        position: absolute;
                                        top: 40px;
                                        left: 19px;
                                        z-index: 1;
                                    "></div>
                                ` : ''}
                                
                                <!-- Number circle with dynamic background matching page background -->
                                <div class="timeline-number" style="
                                    background-color: var(--page-bg-color, #ffffff);
                                    color: ${d.colors.numberText};
                                    border: 3px solid ${d.colors.numberBackground};
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-weight: bold;
                                    font-size: 18px;
                                    flex-shrink: 0;
                                    position: relative;
                                    z-index: 2;
                                ">
                                    ${item.number}
                                </div>
                                
                                <!-- Content -->
                                <div class="timeline-content" style="flex: 1; margin-left: 20px; margin-top: 5px;">
                                    <p style="margin: 0; line-height: 1.6; color: ${d.colors.descriptionColor};">
                                        <strong style="color: ${d.colors.titleColor};">${item.title}</strong> ${item.description}
                                    </p>
                                </div>
                            </div>
                        `;
                    });
                    
                    columnsHtml += `
                        <div class="timeline-column" style="flex: 0 0 auto; min-width: 300px; margin-right: 0; max-width: 380px;">
                            ${itemsHtml}
                        </div>
                    `;
                });
                
                content.innerHTML = `
                    <div class="numbered-timeline-wrapper" style="
                        background: transparent;
                        padding: 20px;
                        border-radius: 8px;
                        max-width: 100%;
                    ">
                        <div class="timeline-container" style="display: flex; flex-wrap: nowrap; gap: 40px; align-items: flex-start;">
                            ${columnsHtml}
                        </div>
                    </div>
                `;
            };
            
            content.renderTimeline();
            break;

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

        case 'heading':
            content = document.createElement('h2');
            content.textContent = 'New Heading';
            content.contentEditable = true;
            content.style.fontSize = '24px';
            content.setAttribute('data-style-fontSize', '24px');
            content.style.fontWeight = 'bold';
        break;


        case 'text':
            content = document.createElement('div');
            const textId = 'text-content-' + Date.now();
            content.innerHTML = `<div id="${textId}" style="min-height: 50px; padding: 10px;" oninput="updateTextImagesField(this.innerHTML, 'text')">New text block. Click to edit.</div>`;
            content.style.fontSize = '16px';
        break;

        case 'inner-section':
            content = document.createElement('div');
            content.className = 'inner-section-component';
            content.style.border = '2px dashed #ddd';
            content.style.padding = '20px';
            content.style.margin = '10px 0';
            content.style.backgroundColor = 'transparent';
            content.style.minHeight = '100px';
            content.style.position = 'relative';
            
            // Add section label
            const label = document.createElement('div');
            label.textContent = 'Inner Section (2 Columns)';
            label.className = 'section-label';
            label.style.position = 'absolute';
            label.style.top = '5px';
            label.style.left = '10px';
            label.style.fontSize = '12px';
            label.style.color = '#6c757d';
            label.style.backgroundColor = '#fff';
            label.style.padding = '2px 6px';
            label.style.borderRadius = '4px';
            label.style.border = '1px solid #ddd';
            label.style.zIndex = '10';
            
            // Create column container using Bootstrap row
            const columnContainer = document.createElement('div');
            columnContainer.className = 'column-container row';
            columnContainer.style.marginTop = '25px';
            columnContainer.style.minHeight = '60px';
            
            // Function to create columns
            const createColumns = (numColumns) => {
                // Calculate Bootstrap column classes based on number of columns
                const getBootstrapClasses = (totalCols) => {
                    switch(totalCols) {
                        case 1: return 'col-12';
                        case 2: return 'col-lg-6 col-md-6 col-sm-12';
                        case 3: return 'col-lg-4 col-md-6 col-sm-12';
                        case 4: return 'col-lg-3 col-md-6 col-sm-12';
                        case 5: return 'col-lg-2 col-md-4 col-sm-6 col-12';
                        case 6: return 'col-lg-2 col-md-4 col-sm-6 col-12';
                        default: return 'col-lg-4 col-md-6 col-sm-12';
                    }
                };
                const bootstrapClass = getBootstrapClasses(numColumns);
                const existingColumns = Array.from(columnContainer.querySelectorAll('.inner-column'));
                // If increasing columns, keep existing and add new to the right
                if (numColumns > existingColumns.length) {
                    // Update classes for all existing columns
                    existingColumns.forEach(col => col.className = `inner-column ${bootstrapClass}`);
                    // Add new columns to the right
                    for (let i = existingColumns.length; i < numColumns; i++) {
                        const column = document.createElement('div');
                        column.className = `inner-column ${bootstrapClass}`;
                        column.style.border = '1px dashed #adb5bd';
                        column.style.borderRadius = '4px';
                        column.style.backgroundColor = 'transparent';
                        column.style.minHeight = '60px';
                        column.style.padding = '10px';
                        column.style.position = 'relative';
                        column.style.transition = 'all 0.3s ease';
                        column.style.marginBottom = '15px';
                        // Add column dropzone
                        const columnDropzone = document.createElement('div');
                        columnDropzone.className = 'column-dropzone';
                        columnDropzone.textContent = `Column ${i + 1}`;
                        columnDropzone.style.textAlign = 'center';
                        columnDropzone.style.color = '#6c757d';
                        columnDropzone.style.fontSize = '12px';
                        columnDropzone.style.padding = '20px 5px';
                        columnDropzone.style.position = 'absolute';
                        columnDropzone.style.top = '50%';
                        columnDropzone.style.left = '50%';
                        columnDropzone.style.transform = 'translate(-50%, -50%)';
                        columnDropzone.style.width = '80%';
                        columnDropzone.style.pointerEvents = 'none';
                        column.appendChild(columnDropzone);
                        // Enable drag and drop for each column
                        column.addEventListener('dragover', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            column.style.backgroundColor = '#e3f2fd';
                            column.style.borderColor = '#007bff';
                        });
                        column.addEventListener('dragleave', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            column.style.backgroundColor = 'transparent';
                            column.style.borderColor = '#adb5bd';
                        });
                        column.addEventListener('drop', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            column.style.backgroundColor = 'transparent';
                            column.style.borderColor = '#adb5bd';
                            const componentType = e.dataTransfer.getData('type');
                            if (componentType) {
                                const newComponent = createComponent(componentType);
                                newComponent.style.width = '100%';
                                // Add component to column
                                column.appendChild(newComponent);
                                // Hide dropzone text if column has components
                                const hasComponents = column.querySelectorAll('.component').length > 0;
                                columnDropzone.style.display = hasComponents ? 'none' : 'block';
                                // Refresh insertion zones after adding component to column
                                setTimeout(() => {
                                    refreshInsertionZones();
                                }, 10);
                                // Update structure panel and select the new component
                                setTimeout(() => {
                                    updateStructurePanel();
                                    selectComponent(newComponent);
                                }, 10);
                            }
                        });
                        
                        // Initialize SortableJS for drag and drop between columns
                        setTimeout(() => {
                            initializeColumnSortable(column);
                        }, 100);
                        columnContainer.appendChild(column);
                    }
                } else {
                    // Reducing columns: keep leftmost, remove rightmost
                    // Remove extra columns from the right
                    for (let i = existingColumns.length - 1; i >= numColumns; i--) {
                        columnContainer.removeChild(existingColumns[i]);
                    }
                    // Update classes for remaining columns
                    for (let i = 0; i < numColumns; i++) {
                        existingColumns[i].className = `inner-column ${bootstrapClass}`;
                    }
                }
            };

          
            
            content.appendChild(label);
            content.appendChild(columnContainer);
            
            // Store component data with enhanced background options
            content._innerSectionData = {
                backgroundColor: 'transparent',
                borderColor: '#ddd',
                borderStyle: 'dashed',
                borderWidth: '2px',
                borderRadius: '',
                padding: '20px',
                margin: '10px 0',
                columns: 2,
                gap: '15px',
                fullWidth: false, // Full width stretch option
                contentWidth: 'full', // 'full' or 'boxed' for full width sections
                // Background options
                backgroundType: 'color', // 'color' or 'image'
                backgroundImage: '',
                backgroundAttachment: 'scroll', // 'scroll' or 'fixed'
                // Menu options for investment websites
                addToMenu: false,
                menuTitle: '',
                sectionId: ''
            };
            
            // Function to update background
            content.updateBackground = function() {
                const data = content._innerSectionData;
                if (data.backgroundType === 'color') {
                    content.style.backgroundColor = data.backgroundColor;
                    content.style.backgroundImage = 'none';
                    content.style.backgroundPosition = '';
                    content.style.backgroundSize = '';
                    content.style.backgroundAttachment = '';
                } else if (data.backgroundType === 'image' && data.backgroundImage) {
                    // Fixed gradient with your exact format
                    content.style.backgroundColor = 'transparent';
                    content.style.backgroundImage = `linear-gradient(#000,#000c 18%),url(${data.backgroundImage})`;
                    content.style.backgroundPosition = '0 0,0 0';
                    content.style.backgroundSize = 'auto,cover';
                    content.style.backgroundAttachment = `scroll,${data.backgroundAttachment}`;
                }
            };
            
            // Function to update columns
            content.updateColumns = function(numColumns) {
                content._innerSectionData.columns = numColumns;
                createColumns(numColumns);
                
                // Update label
                const sectionLabel = content.querySelector('.section-label');
                if (sectionLabel) {
                    sectionLabel.textContent = `Inner Section (${numColumns} Column${numColumns > 1 ? 's' : ''})`;
                }
            };
            
            // Function to update column gap
            content.updateGap = function(gap) {
                content._innerSectionData.gap = gap;
                
                // Convert gap value to number for Bootstrap spacing
                const gapValue = parseInt(gap.replace(/[^\d]/g, '')) || 15;
                const gapUnit = gap.replace(/[\d]/g, '') || 'px';
                
                // Update margin-bottom for all columns to create visual gap
                const columns = content.querySelectorAll('.inner-column');
                columns.forEach(column => {
                    column.style.marginBottom = gapValue + gapUnit;
                });
                
                // Update the row to use custom CSS variables for gap if needed
                const container = content.querySelector('.column-container');
                if (container) {
                    container.style.setProperty('--bs-gutter-x', gap);
                    container.style.setProperty('--bs-gutter-y', gap);
                }
            };
            
            // Function to render inner section with full width support
            content.renderInnerSection = function() {
                const data = content._innerSectionData;
                
                // Update visual appearance based on full width setting
                if (data.fullWidth) {
                    // Add data attribute for CSS targeting
                    content.setAttribute('data-full-width', 'true');
                    
                    // Visual indication that this will be full width on frontend
                    content.style.borderStyle = 'solid';
                    content.style.borderColor = '#007bff';
                    content.style.backgroundColor = '#f8f9ff';
                    
                    // Update label to show full width
                    const sectionLabel = content.querySelector('.section-label');
                    if (sectionLabel) {
                        sectionLabel.textContent = `Inner Section (${data.columns} Column${data.columns > 1 ? 's' : ''}) - FULL WIDTH`;
                        sectionLabel.style.backgroundColor = '#007bff';
                        sectionLabel.style.color = '#fff';
                    }
                } else {
                    // Remove data attribute for regular sections
                    content.removeAttribute('data-full-width');
                    
                    // Regular appearance
                    content.style.borderStyle = 'dashed';
                    content.style.borderColor = '#ddd';
                    content.style.backgroundColor = 'transparent';
                    
                    // Update label to show regular
                    const sectionLabel = content.querySelector('.section-label');
                    if (sectionLabel) {
                        sectionLabel.textContent = `Inner Section (${data.columns} Column${data.columns > 1 ? 's' : ''})`;
                        sectionLabel.style.backgroundColor = '#fff';
                        sectionLabel.style.color = '#6c757d';
                    }
                }
            };
            
            // Initialize with 2 columns and apply initial background
            createColumns(2);
            content.updateBackground();
        break;

        case 'button':
            const wrapper = document.createElement('div');
            const button = document.createElement('button');
            button.textContent = 'Click Me';
            button.style.padding = '10px 20px';
            button.style.fontSize = '16px';
            button.style.backgroundColor = '#007bff';
            button.style.color = '#fff';
            button.style.border = 'none';
            button.style.borderRadius = '4px';
            button.style.cursor = 'pointer';

            wrapper.appendChild(button);
            wrapper.style.textAlign = 'center';

            content = wrapper;
        break;

        case 'invest-cta':
            content = document.createElement('div');
            let investCtaData = content._investCtaData || {
                buttonText: 'INVEST NOW',
                buttonUrl: '/invest',
                buttonTarget: '_self',
                leftValue: '$2.13',
                leftLabel: 'Share Price',
                rightValue: '$1001.10',
                rightLabel: 'Min. Investment',
                buttonBgColor: '#2e7d3e',
                buttonTextColor: '#ffffff',
                valueColor: '#333333',
                labelColor: '#666666',
                dividerColor: '#e0e0e0',
                bgColor: '#f8f9fa'
            };
            content._investCtaData = investCtaData;
            content.innerHTML = `
                <div class="invest-cta-wrapper" style="background-color: ${investCtaData.bgColor}; border-radius: 0px; padding: 20px; display: flex; align-items: center; gap: 20px; max-width: 500px; margin: 0px;">
                    <div class="invest-cta-button-wrap">
                        <a href="/invest" 
                           target="_self" 
                           class="invest-cta-button"
                           style="display: inline-block; background-color: #2e7d3e; color: #ffffff; text-decoration: none; padding: 15px 30px; border-radius: 4px; font-size: 14px; font-weight: 600; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; border: none; cursor: pointer; white-space: nowrap; flex-shrink: 0;"
                           aria-label="INVEST NOW">
                            INVEST NOW
                        </a>
                    </div>
                    
                    <div class="investment-info-wrapper" style="display: flex; align-items: center; justify-content: center; gap: 20px; flex: 1;">
                        <div class="investment-info-item" style="text-align: center; flex: 1;">
                            <div class="investment-value" style="color: #333333; font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 5px;">$2.13</div>
                            <div class="investment-label" style="color: #666666; font-size: 14px; font-weight: 400; line-height: 1.2;">Share Price</div>
                        </div>
                        
                        <div class="investment-divider" style="width: 1px; height: 40px; background-color: #e0e0e0; flex-shrink: 0;"></div>
                        
                        <div class="investment-info-item" style="text-align: center; flex: 1;">
                            <div class="investment-value" style="color: #333333; font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 5px;">$1001.10</div>
                            <div class="investment-label" style="color: #666666; font-size: 14px; font-weight: 400; line-height: 1.2;">Min. Investment</div>
                        </div>
                    </div>
                </div>
            `;
        break;

        case 'auction-list':
            content = document.createElement('div');
            content.innerHTML = `
                <div class="auction-list-component">
                    <h4>Live Auction Items</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Auction Item">
                                <div class="card-body">
                                    <h5 class="card-title">Auction Item 1</h5>
                                    <p class="card-text">Starting bid: $100</p>
                                    <a href="#" class="btn btn-primary">Bid Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Auction Item">
                                <div class="card-body">
                                    <h5 class="card-title">Auction Item 2</h5>
                                    <p class="card-text">Starting bid: $150</p>
                                    <a href="#" class="btn btn-primary">Bid Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Auction Item">
                                <div class="card-body">
                                    <h5 class="card-title">Auction Item 3</h5>
                                    <p class="card-text">Starting bid: $200</p>
                                    <a href="#" class="btn btn-primary">Bid Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        break;

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
            // Overlay title
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
            // Overlay subtitle
            const subCustom = document.createElement('div');
            subCustom.textContent = 'Custom Banner Subtitle';
            subCustom.contentEditable = true;
            subCustom.style.position = 'absolute';
            subCustom.style.top = '55%';
            subCustom.style.left = '50%';
            subCustom.style.transform = 'translate(-50%, -50%)';
            subCustom.style.margin = '0';
            subCustom.style.color = 'white';
            subCustom.style.textShadow = '0 2px 8px rgba(0,0,0,0.5)';
            subCustom.style.fontSize = '1.2em';
            subCustom.style.textAlign = 'center';
            subCustom.style.width = '90%';
            subCustom.style.pointerEvents = 'auto';
            content.appendChild(imgCustom);
            content.appendChild(h3Custom);
            content.appendChild(subCustom);

            // Store settings for serialization
            content._customBannerData = {
                imgSrc: '',
                title: 'Custom Banner Title',
                subtitle: 'Custom Banner Subtitle',
                titleShadow: '0 2px 8px rgba(0,0,0,0.5)',
                subtitleShadow: '0 2px 8px rgba(0,0,0,0.5)',
                titleColor: '#ffffff',
                subtitleColor: '#ffffff',
                titleFontSize: '2.2em',
                subtitleFontSize: '1.2em',
                textAlign: 'center'
            };

            // Render function for custom banner
            content.renderCustomBanner = function() {
                const d = content._customBannerData;
                imgCustom.src = d.imgSrc || '';
                h3Custom.textContent = d.title || '';
                h3Custom.style.textShadow = d.titleShadow || '';
                h3Custom.style.color = d.titleColor || '#ffffff';
                h3Custom.style.fontSize = d.titleFontSize || '2.2em';
                h3Custom.style.textAlign = d.textAlign || 'center';
                subCustom.textContent = d.subtitle || '';
                subCustom.style.textShadow = d.subtitleShadow || '';
                subCustom.style.color = d.subtitleColor || '#ffffff';
                subCustom.style.fontSize = d.subtitleFontSize || '1.2em';
                subCustom.style.textAlign = d.textAlign || 'center';
            };
            // Live update on edit
            h3Custom.oninput = function() {
                content._customBannerData.title = h3Custom.textContent;
            };
            subCustom.oninput = function() {
                content._customBannerData.subtitle = subCustom.textContent;
            };
            content.renderCustomBanner();
        break;

        case 'gallery':
            content = document.createElement('div');
            content.className = 'gallery-component';
            // Default state
            content._galleryData = {
                images: [],
                columns: 3
            };
            content.renderGallery = function() {
                const { images, columns } = content._galleryData;
                if (!images.length) {
                    content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Gallery Placeholder</div>';
                    return;
                }
                if (images.length === 1) {
                    content.innerHTML = `
                        <div style="display:flex;justify-content:center;">
                            <img src="${'${images[0]}'}" data-idx="0" class="gallery-img" style="width:auto;max-width:100%;height:220px;object-fit:contain;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,0.08);" onclick="openGalleryModal(this)">
                        </div>
                    `;
                    return;
                }
                // Responsive grid for multiple images
                let html = `<div class="gallery-row" style="display: flex; flex-wrap: wrap; gap: 10px;">`;
                images.forEach((src, idx) => {
                    html += `
                        <div class="gallery-img-col" style="flex: 0 0 calc(${'${100/columns}'}% - 10px); max-width: calc(${'${100/columns}'}% - 10px); display: flex; justify-content: center;">
                            <img src="${'${src}'}" data-idx="${'${idx}'}" class="gallery-img" style="width:100%;max-width:100%;height:160px;object-fit:cover;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,0.08);" onclick="openGalleryModal(this)">
                        </div>
                    `;
                });
                html += `</div>`;
                content.innerHTML = html;
            };
            content.renderGallery();
        break;

        case 'slider':
    content = document.createElement('div');
    content.className = 'slider-component';
    // Default state
    content._sliderData = {
        images: [],
        slidesToShow: 1,
        slideSpeed: 2000 // ms
    };
    content._sliderInterval = null;
    content._sliderStartIdx = 0;
    content.renderSlider = function() {
        const { images, slidesToShow } = content._sliderData;
        if (!images.length) {
            content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Slider Placeholder</div>';
            return;
        }
        // Clamp slidesToShow
        let showCount = Math.max(1, Math.min(slidesToShow, images.length));
        // Track start index for manual/auto slide
        let startIdx = content._sliderStartIdx || 0;
        // Build slider HTML
        let html = `<div class="slider-row" style="display: flex; overflow: hidden; gap: 10px; position:relative;">`;
        for (let s = 0; s < showCount; s++) {
            const idx = (startIdx + s) % images.length;
            html += `
                <div class="slider-img-col" style="flex: 0 0 calc(${'${100/showCount}'}% - 10px); max-width: calc(${'${100/showCount}'}% - 10px); display: flex; justify-content: center;">
                    <img src="${'${images[idx]}'}" data-idx="${'${idx}'}" class="slider-img"
                        style="display:block;width:auto;max-width:100%;height:220px;max-height:100%;object-fit:contain;border-radius:8px;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,0.08);"
                        onclick="openSliderModal(this)">
                </div>
            `;
        }
        html += `</div>`;
        content.innerHTML = html;
        // Start auto-slide
        if (content._sliderInterval) clearInterval(content._sliderInterval);
        if (images.length > showCount) {
            content._sliderInterval = setInterval(() => {
                content._sliderStartIdx = (content._sliderStartIdx + 1) % images.length;
                content.renderSlider();
            }, content._sliderData.slideSpeed);
        }
    };
    content.renderSlider();
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
                <div class="video-container"></div>
            `;
            
            // Store video data
            content._videoData = {
                url: '',
                type: 'youtube',
                isUploadedFile: false,
                autoplay: false,
                width: null,
                height: null
            };
            
            // Function to update video embed
            content.updateVideo = function(url, type = 'youtube') {
                console.log('updateVideo called with URL:', url, 'type:', type);
                content._videoData.url = url;
                content._videoData.type = type;
                const container = content.querySelector('.video-container');
                console.log('Container found:', container);
                
                // Get custom dimensions
                const customWidth = content._videoData.width ? content._videoData.width + 'px' : '100%';
                const customHeight = content._videoData.height ? content._videoData.height + 'px' : '200';
                
                if (url) {
                    console.log('URL is valid, updating video display');
                    if (type === 'uploaded') {
                        // Handle uploaded video files - disable interaction in page builder
                        const autoplayAttr = content._videoData.autoplay ? 'autoplay muted' : '';
                        const videoHTML = `
                            <video width="${customWidth}" height="${customHeight}" controls ${autoplayAttr} style="border-radius: 8px; max-width: 100%; pointer-events: none; opacity: 0.8;" preload="metadata">
                                <source src="${url}" type="video/mp4">
                                <source src="${url}" type="video/webm">
                                <source src="${url}" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.7); color: white; padding: 8px 12px; border-radius: 4px; font-size: 12px; pointer-events: none;">Video Preview (Click to Edit)</div>
                        `;
                        console.log('Setting video HTML:', videoHTML);
                        container.innerHTML = videoHTML;
                        container.style.position = 'relative'; // For overlay positioning
                    } else {
                        // Handle YouTube videos
                        let videoId = '';
                        if (url.includes('youtu.be/')) {
                            videoId = url.split('/').pop().split('?')[0];
                        } else if (url.includes('youtube.com/watch?v=')) {
                            const urlParams = new URLSearchParams(url.split('?')[1]);
                            videoId = urlParams.get('v');
                        } else if (url.includes('youtube.com/embed/')) {
                            videoId = url.split('/embed/')[1].split('?')[0];
                        }
                        
                        if (videoId) {
                            const autoplayParam = content._videoData.autoplay ? '&autoplay=1&mute=1' : '';
                            container.innerHTML = `
                                <iframe width="${customWidth}" height="${customHeight}" src="https://www.youtube.com/embed/${videoId}?rel=0${autoplayParam}" frameborder="0" allowfullscreen style="max-width: 100%; pointer-events: none; opacity: 0.8;"></iframe>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.7); color: white; padding: 8px 12px; border-radius: 4px; font-size: 12px; pointer-events: none;">YouTube Video Preview (Click to Edit)</div>
                            `;
                            container.style.position = 'relative'; // For overlay positioning
                        } else {
                            container.innerHTML = `<div style="padding: 20px; background: #f3f4f6; text-align: center;">Invalid video URL</div>`;
                        }
                    }
                } else {
                    console.log('No URL provided, showing placeholder');
                    container.innerHTML = `<div style="padding: 20px; background: #f3f4f6; text-align: center;">No video provided</div>`;
                }
            };
        break;

        case 'faq':
            content = document.createElement('div');
            content.className = 'faq-component';
            
            // Initialize FAQ data
            if (!content._faqData) {
                content._faqData = {
                    questions: [
                        {
                            question: 'How much can I invest?',
                            answer: 'Accredited investors can invest as much as they want. But if you are NOT an accredited investor, your investment limit depends on either your annual income or net worth, whichever is greater.',
                            expanded: false
                        },
                        {
                            question: 'Why Should I Invest?',
                            answer: 'This is a great opportunity to be part of an innovative project.',
                            expanded: false
                        }
                    ],
                    questionBackgroundColor: '#f3f4f6',
                    questionTextColor: '#1f2937',
                    answerBackgroundColor: '#ffffff',
                    answerTextColor: '#374151',
                    iconColor: '#059669',
                    borderRadius: '8px',
                    spacing: '10px'
                };
            }
            
            content.renderFaq = function() {
                const faqData = this._faqData;
                this.innerHTML = `
                    <div class="faq-container" style="max-width: 100%;">
                        ${faqData.questions.map((item, index) => `
                            <div class="faq-item" style="
                                margin-bottom: ${faqData.spacing};
                                border-radius: ${faqData.borderRadius};
                                overflow: hidden;
                                border: 1px solid #e5e7eb;
                            ">
                                <div class="faq-question" style="
                                    background-color: ${faqData.questionBackgroundColor};
                                    color: ${faqData.questionTextColor};
                                    padding: 16px 20px;
                                    cursor: pointer;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    font-weight: 500;
                                    font-size: 16px;
                                    user-select: none;
                                " onclick="toggleFaqItem(this, ${index})">
                                    <span>${item.question}</span>
                                    <div class="faq-icon" style="
                                        width: 32px;
                                        height: 32px;
                                        border-radius: 50%;
                                        background-color: ${faqData.iconColor};
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                        font-weight: bold;
                                        font-size: 18px;
                                        flex-shrink: 0;
                                        margin-left: 15px;
                                    ">${item.expanded ? '−' : '+'}</div>
                                </div>
                                <div class="faq-answer" style="
                                    background-color: ${faqData.answerBackgroundColor};
                                    color: ${faqData.answerTextColor};
                                    padding: ${item.expanded ? '20px' : '0 20px'};
                                    max-height: ${item.expanded ? '1000px' : '0'};
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    font-size: 15px;
                                    line-height: 1.6;
                                ">${item.answer}</div>
                            </div>
                        `).join('')}
                    </div>
                `;
            };
            
            content.renderFaq();
        break;

        case 'simple-comments':
            content = document.createElement('div');
            content.className = 'simple-comments-component';
            
            // Initialize Simple Comments data
            if (!content._simpleCommentsData) {
                content._simpleCommentsData = {
                    title: 'Comments',
                    showTitle: true,
                    allowAnonymous: true,
                    moderationEnabled: false,
                    requireEmail: true,
                    maxComments: 100,
                    sortOrder: 'newest',
                    backgroundColor: '#ffffff',
                    borderColor: '#e0e0e0',
                    textColor: '#333333',
                    buttonColor: '#007bff'
                };
            }
            
            content.renderSimpleComments = function() {
                const data = this._simpleCommentsData;
                
                this.innerHTML = `
                    <div style="
                        background: ${data.backgroundColor};
                        border: 1px solid ${data.borderColor};
                        border-radius: 8px;
                        padding: 20px;
                        color: ${data.textColor};
                    ">
                        ${data.showTitle ? `<h3 style="margin: 0 0 20px 0; color: ${data.textColor};">${data.title}</h3>` : ''}
                        
                        <!-- Comment Form Preview -->
                        <div style="
                            background: #f8f9fa;
                            border: 1px dashed #dee2e6;
                            border-radius: 6px;
                            padding: 16px;
                            margin-bottom: 20px;
                        ">
                            <div style="display: flex; align-items: center; margin-bottom: 12px;">
                                <i class="fas fa-plus-circle" style="color: ${data.buttonColor}; margin-right: 8px;"></i>
                                <strong>Comment Form</strong>
                            </div>
                            <div style="font-size: 14px; color: #666;">
                                ${data.allowAnonymous ? '• Anonymous comments allowed' : '• Registration required'}<br>
                                ${data.requireEmail ? '• Email required' : '• Email optional'}<br>
                                ${data.moderationEnabled ? '• Comments moderated' : '• Comments auto-approved'}
                            </div>
                        </div>
                        
                        <!-- Sample Comments Preview -->
                        <div style="border-top: 1px solid ${data.borderColor}; padding-top: 16px;">
                            <div style="font-size: 14px; color: #666; margin-bottom: 16px;">
                                <i class="fas fa-comments" style="margin-right: 6px;"></i>
                                Sample comments will appear here (sorted by ${data.sortOrder})
                            </div>
                            
                            <div style="
                                background: #f8f9fa;
                                border-left: 4px solid ${data.buttonColor};
                                padding: 12px 16px;
                                border-radius: 4px;
                                margin-bottom: 12px;
                            ">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="color: ${data.textColor};">Sample User</strong>
                                    <small style="color: #666;">2 hours ago</small>
                                </div>
                                <p style="margin: 0; color: ${data.textColor};">This is a sample comment to show how comments will look on your page.</p>
                            </div>
                            
                            <div style="
                                background: #f8f9fa;
                                border-left: 4px solid ${data.buttonColor};
                                padding: 12px 16px;
                                border-radius: 4px;
                            ">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="color: ${data.textColor};">Another User</strong>
                                    <small style="color: #666;">1 day ago</small>
                                </div>
                                <p style="margin: 0; color: ${data.textColor};">Great article! Thanks for sharing this information.</p>
                            </div>
                        </div>
                    </div>
                `;
            };
            
            content.renderSimpleComments();
        break;

        case 'disqus':
            content = document.createElement('div');
            content.className = 'disqus-component';
            
            // Initialize Disqus data
            if (!content._disqusData) {
                content._disqusData = {
                    shortname: '',
                    identifier: '',
                    title: '',
                    url: '',
                    showInPreview: true
                };
            }
            
            content.renderDisqus = function() {
                const disqusData = this._disqusData;
                
                if (!disqusData.shortname) {
                    this.innerHTML = `
                        <div style="
                            padding: 40px 20px;
                            text-align: center;
                            background: #f8f9fa;
                            border: 2px dashed #dee2e6;
                            border-radius: 8px;
                            color: #6c757d;
                        ">
                            <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; color: #adb5bd;"></i>
                            <h4 style="margin: 0 0 8px 0; color: #495057;">Disqus Comments</h4>
                            <p style="margin: 0; font-size: 14px;">Configure your Disqus shortname in the properties panel to enable comments.</p>
                        </div>
                    `;
                } else if (disqusData.showInPreview) {
                    this.innerHTML = `
                        <div style="
                            padding: 20px;
                            background: #ffffff;
                            border: 1px solid #dee2e6;
                            border-radius: 8px;
                        ">
                            <div style="
                                display: flex;
                                align-items: center;
                                margin-bottom: 16px;
                                padding-bottom: 12px;
                                border-bottom: 1px solid #e9ecef;
                            ">
                                <i class="fas fa-comments" style="font-size: 20px; margin-right: 8px; color: #2e9fff;"></i>
                                <strong style="color: #495057;">Disqus Comments Preview</strong>
                                <span style="
                                    margin-left: 8px;
                                    padding: 2px 8px;
                                    background: #e7f3ff;
                                    color: #0066cc;
                                    border-radius: 12px;
                                    font-size: 12px;
                                ">Site: ${disqusData.shortname}</span>
                            </div>
                            <p style="margin: 0; color: #6c757d; font-style: italic;">
                                Comments will appear here when visitors view the page. 
                                The actual Disqus interface will be loaded on the frontend.
                            </p>
                        </div>
                    `;
                } else {
                    this.innerHTML = `
                        <div style="
                            padding: 20px;
                            text-align: center;
                            background: #f8f9fa;
                            border: 1px dashed #adb5bd;
                            border-radius: 8px;
                            color: #6c757d;
                        ">
                            <i class="fas fa-eye-slash" style="margin-right: 8px;"></i>
                            Disqus comments hidden in preview
                        </div>
                    `;
                }
            };
            
            content.renderDisqus();
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

        case 'press-card':
            content = document.createElement('div');
            content.className = 'press-card-component';
            
            // Store press card data
            content._pressCardData = {
                logoSrc: 'https://via.placeholder.com/200x80?text=Press+Logo',
                logoAlt: 'Press Logo',
                title: 'Savannah Gets a Stunning New Hotel and "Living Room" in Municipal Grand',
                url: '#',
                date: 'July 8, 2025',
                target: '_blank',
                cardBackgroundColor: '#ffffff',
                cardBorderRadius: '8px',
                cardBoxShadow: '0 2px 8px rgba(0,0,0,0.1)',
                overlayOpacity: '0.1',
                logoBackgroundColor: '#f8f9fa',
                titleColor: '#1a1a1a',
                dateColor: '#666666'
            };
            
            content.renderPressCard = function() {
                const d = content._pressCardData;
                content.innerHTML = `
                    <div class="press-card-2" style="
                        position: relative;
                        background: ${d.cardBackgroundColor || '#fff'};
                        border-radius: ${d.cardBorderRadius || '8px'};
                        overflow: hidden;
                        box-shadow: ${d.cardBoxShadow || '0 2px 8px rgba(0,0,0,0.1)'};
                        transition: all 0.3s ease;
                        cursor: pointer;
                        max-width: 400px;
                        margin: 0 auto;
                    ">
                        <!-- Press Logo -->
                        <div style="padding: 20px; text-align: center; background: ${d.logoBackgroundColor || '#f8f9fa'};">
                            <img src="${d.logoSrc}" 
                                 alt="${d.logoAlt}" 
                                 style="max-width: 150px; height: auto; filter: brightness(0);" 
                                 class="press-logo">
                        </div>
                        
                        <!-- Press Content -->
                        <a href="${d.url}" 
                           target="${d.target}" 
                           style="
                               display: block;
                               text-decoration: none;
                               color: inherit;
                               padding: 20px;
                               position: relative;
                           "
                           class="press-link">
                            <div class="press-text-wrapper" style="margin-bottom: 15px;">
                                <div style="
                                    font-size: 16px;
                                    font-weight: 600;
                                    line-height: 1.4;
                                    color: ${d.titleColor || '#1a1a1a'};
                                    margin-bottom: 10px;
                                    display: flex;
                                    align-items: flex-start;
                                    justify-content: space-between;
                                    gap: 10px;
                                ">
                                    <span>${d.title}</span>
                                    <div style="
                                        width: 16px;
                                        height: 16px;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        color: ${d.titleColor || '#1a1a1a'};
                                    ">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             width="100%" height="100%" 
                                             viewBox="0 0 32 32" 
                                             fill="currentColor">
                                            <path d="M10 6v2h12.59L6 24.59L7.41 26L24 9.41V22h2V6z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="press-date" style="
                                color: ${d.dateColor || '#666'};
                                font-size: 14px;
                                font-weight: 400;
                            ">${d.date}</div>
                        </a>
                        
                        <!-- Black Overlay (Always visible with adjustable opacity) -->
                        <div class="black-overlay" style="
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: rgba(0,0,0,${d.overlayOpacity || '0.1'});
                            transition: opacity 0.3s ease;
                            pointer-events: none;
                        "></div>
                    </div>
                    
                    <style>
                        .press-card-2:hover .black-overlay {
                            opacity: 1;
                            background: rgba(0,0,0,${(parseFloat(d.overlayOpacity || '0.1') + 0.1).toFixed(1)});
                        }
                        
                        .press-card-2:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                        }
                        
                        .press-link:hover {
                            text-decoration: none !important;
                        }
                    </style>
                `;
            };
            
            content.renderPressCard();
        break;

        case 'full-width-text-image':
            content = document.createElement('div');
            content.className = 'text-images-component';
            // Default state with two texts and image
            content._fwtiData = {
                text1: 'Full Width Title',
                text2: 'This block takes the entire section and screen width.',
                fontSize1: '32px',
                fontSize2: '18px',
                color1: '#222222',
                color2: '#444444',
                imgSrc: 'https://via.placeholder.com/1200x400',
                imgAlt: '',
                imgHeight: 400,
                imgCustomWidth: '100%',
                imgCustomHeight: 'auto',
                imgObjectFit: 'cover'
            };
            content.renderFWTI = function() {
                const d = content._fwtiData;
                content.innerHTML = `
                    <h3
                        style="margin-bottom: 10px; font-size:${'${d.fontSize1}'}; color:${'${d.color1}'};"
                    >${'${d.text1}'}</h3>
                    <p
                        style="font-size:${'${d.fontSize2}'}; color:${'${d.color2}'};"
                    >${'${d.text2}'}</p>
                    <img src="${'${d.imgSrc}'}" alt="${'${d.imgAlt}'}"
                        style="width:${'${d.imgCustomWidth}'};height:${'${d.imgCustomHeight}'};object-fit:${'${d.imgObjectFit}'};max-width:100%;margin-top:10px;${'${d.imgSrc ? \'\' : \'display:none;\'}'}" />
                `;
            };
            content.renderFWTI();
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
            // Store data for serialization and live editing
            content._sellTicketsData = {
                title: 'Buy Tickets',
                buttonText: 'Buy Now',
                buttonBg: '#007bff',
                buttonColor: '#fff',
                buttonPadding: '10px 20px',
                buttonRadius: '4px'
            };
            content.renderSellTickets = function() {
                const d = content._sellTicketsData;
                content.innerHTML = `
                    <h4 contenteditable="true" style="margin-bottom:10px;">${d.title}</h4>
                    <button class="btn" style="
                        background:${d.buttonBg};
                        color:${d.buttonColor};
                        padding:${d.buttonPadding};
                        border-radius:${d.buttonRadius};
                        border:none;
                        font-size:16px;
                    ">${d.buttonText}</button>
                `;
                // Live update on edit
                const h4 = content.querySelector('h4');
                h4.oninput = function() {
                    content._sellTicketsData.title = h4.textContent;
                };
                const btn = content.querySelector('button');
                btn.onclick = function() {
                    // You can add your ticket logic here
                };
            };
            content.renderSellTickets();
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
                                                    Donate To the {{ $data->website->name}}
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
                                                        to the {{ $data->website->name}}</label>
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
                imgPosition: 'left',
                imgSize: 200,
                imgWidth: '200',
                imgHeight: 'auto',
                showImage: true
            };
            content.renderTextImages = function() {
                const d = content._textImagesData;
                // Ensure width/height are always valid strings for style
                let width = d.imgWidth || d.imgSize || '200';
                let height = d.imgHeight || 'auto';
                // If width/height are numbers, add px
                if (/^\d+$/.test(width)) width = width + 'px';
                if (/^\d+$/.test(height)) height = height + 'px';
                const imgTag = d.showImage && d.imgSrc ? `<img src='${d.imgSrc}' style='max-width:100%;width:${width};height:${height};object-fit:cover;'/>` : '';
                let layout = '';
                if (d.imgPosition === 'up') {
                    layout = `${imgTag ? `<div style='text-align:center;'>${imgTag}</div>` : ''}<div style='text-align:center;'><div id='text-content-${Date.now()}' contenteditable='true' oninput='updateTextImagesField(this.innerHTML, "text")' style='margin:0;min-height:20px;'>${d.text}</div></div>`;
                } else if (d.imgPosition === 'down') {
                    layout = `<div style='text-align:center;'><div id='text-content-${Date.now()}' contenteditable='true' oninput='updateTextImagesField(this.innerHTML, "text")' style='margin:0;min-height:20px;'>${d.text}</div></div>${imgTag ? `<div style='text-align:center;'>${imgTag}</div>` : ''}`;
                } else if (d.imgPosition === 'right') {
                    layout = `<div style='display:flex;align-items:center;gap:16px;'><div style='flex:1;'><div id='text-content-${Date.now()}' contenteditable='true' oninput='updateTextImagesField(this.innerHTML, "text")' style='margin:0;min-height:20px;'>${d.text}</div></div>${imgTag}</div>`;
                } else {
                    layout = `<div style='display:flex;align-items:center;gap:16px;'>${imgTag}<div style='flex:1;'><div id='text-content-${Date.now()}' contenteditable='true' oninput='updateTextImagesField(this.innerHTML, "text")' style='margin:0;min-height:20px;'>${d.text}</div></div></div>`;
                }
                content.innerHTML = layout;
            };
            content.renderTextImages();
        break;
        case 'feature-grid':
            content = document.createElement('div');
            content.className = 'feature-grid-component';
            // Default state with 6 feature items
            content._featureGridData = {
                iconColor: '#3b82f6',
                titleColor: '#1f2937',
                descriptionColor: '#6b7280',
                features: [
                    {
                        icon: 'fas fa-chart-line',
                        title: 'Exceptional Growth Potential',
                        description: 'The scalability of our company positions us for a massive exit opportunity and an elevated, high-multiple valuation.'
                    },
                    {
                        icon: 'fas fa-trophy',
                        title: 'The Most Recognizable Brand',
                        description: 'With years of operational excellence, industry-defining products, and award-winning services, we are the premiere brand in our industry.'
                    },
                    {
                        icon: 'fas fa-expand-arrows-alt',
                        title: 'Scalable Model',
                        description: 'Strong partnerships and brand strength reduce initial capital requirements, enabling rapid growth in high-demand markets.'
                    },
                    {
                        icon: 'fas fa-gem',
                        title: 'Diverse Revenue Streams',
                        description: 'Multiple income sources position us for sustained growth and an elevated, high-multiple valuation.'
                    },
                    {
                        icon: 'fas fa-star',
                        title: 'Proven Success',
                        description: 'Long-developed systems and processes have enabled successful operations in diverse markets, generating significant annual revenue.'
                    },
                    {
                        icon: 'fas fa-shield-alt',
                        title: 'Unmatched Marketing Engines',
                        description: 'Backed by a large social media following, powerful PR engine, industry-leading marketplace, and engagement platforms.'
                    }
                ]
            };
            content.renderFeatureGrid = function() {
                const features = content._featureGridData.features;
                const iconColor = content._featureGridData.iconColor || '#3b82f6';
                const titleColor = content._featureGridData.titleColor || '#1f2937';
                const descriptionColor = content._featureGridData.descriptionColor || '#6b7280';
                let html = '<div class="feature-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; padding: 2rem;">';
                
                features.forEach((feature, index) => {
                    html += `
                        <div class="feature-item" style="display: block;">
                            <div class="feature-icon" style="width: 48px; height: 48px; color: ${iconColor}; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <i class="${feature.icon}" style="font-size: 24px;"></i>
                            </div>
                            <div class="feature-content">
                                <h3 class="feature-title" style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; color: ${titleColor};" contenteditable="true">${feature.title}</h3>
                                <p class="feature-description" style="margin: 0; color: ${descriptionColor}; line-height: 1.5;" contenteditable="true">${feature.description}</p>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                
                // Add responsive CSS
                html += `
                    <style>
                        @media (max-width: 768px) {
                            .feature-grid {
                                grid-template-columns: 1fr !important;
                                gap: 1.5rem !important;
                                padding: 1rem !important;
                            }
                        }
                    </style>
                `;
                
                content.innerHTML = html;
                
                // Add event listeners for content editing
                setTimeout(() => {
                    const titles = content.querySelectorAll('.feature-title');
                    const descriptions = content.querySelectorAll('.feature-description');
                    
                    titles.forEach((title, index) => {
                        title.addEventListener('blur', () => {
                            content._featureGridData.features[index].title = title.textContent;
                        });
                    });
                    
                    descriptions.forEach((desc, index) => {
                        desc.addEventListener('blur', () => {
                            content._featureGridData.features[index].description = desc.textContent;
                        });
                    });
                }, 0);
            };
            content.renderFeatureGrid();
        break;
        
        case 'investment-tier':
                                    content = document.createElement('div');
                                    content.className = 'investment-tier-component';
                                    content._investmentTierData = {
                                            tierName: 'TIER 1',
                                            tierPrice: '$2,500',
                                            tierDescription: 'Priority reservations at all Death & Co properties; invites to all investor events (investor happy hours held annually at each door as well as investor invite-only pre-opening events); and free access to Fashioned, Death & Co’s forthcoming cocktail education platform and community hub (to launch late July 2025).',
                                            buttonText: 'INVEST NOW',
                                            buttonUrl: '/portal-start?utm_source=direct&utm_medium=(none)&utm_campaign=(none)&tnames=referral&utm_content=none&utm_term=1Bnone&utm_page=home',
                                            buttonTarget: '_self',
                                            backgroundColor: '#111',
                                            backgroundImage: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80', // Example image, replace as needed
                                            backgroundType: 'image',
                                            textColor: '#fff',
                                            buttonBgColor: '#23b04a',
                                            buttonTextColor: '#fff'
                                    };
                                    content.renderInvestmentTier = function() {
                                            const d = content._investmentTierData;
                                            let bg = d.backgroundType === 'image' && d.backgroundImage
                                                    ? `background: linear-gradient(0deg, rgba(0,0,0,0.85) 80%, rgba(0,0,0,0.85) 100%), url('${d.backgroundImage}') center/cover no-repeat;`
                                                    : `background: ${d.backgroundColor};`;
                                            content.innerHTML = `
                                            <div class="perk-wrap is-full _2" style="${bg} color: ${d.textColor}; border-radius: 8px; padding: 32px 28px 24px 28px; max-width: 370px; margin: 0 auto !important; box-shadow: 0 4px 24px rgba(0,0,0,0.18);">
                                                <div class="z-index-1">
                                                    <div class="cell-top-2-2" style="margin-bottom: 12px;">
                                                        <div class="text-block-94 text-color-white" style="font-size: 1.1rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">${d.tierName}</div>
                                                        <div class="number-larger-2 text-color-white" style="font-size: 2rem; font-weight: 700; margin-top: 2px;">${d.tierPrice}</div>
                                                    </div>
                                                    <div class="cell-top-2-2 no-line" style="margin-bottom: 18px;">
                                                        <div class="w-layout-grid grid-45">
                                                            <div class="div-block-173 text-color-white">
                                                                <div style="font-weight: 700; font-size: 1.05rem; margin-bottom: 4px;">Receive</div>
                                                                <div style="font-size: 0.98rem; line-height: 1.5; color: #fff;">${d.tierDescription}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="main_cta_button_wrap" style="margin-top: 18px;">
                                                    <a dmr-checkout-start="1" tag="button" aria-label="Proceed to checkout" href="/invest?amount=${encodeURIComponent(d.tierPrice)}" class="button w-button is-small hide-tablet" style="display: block; width: 100%; background: ${d.buttonBgColor}; color: ${d.buttonTextColor}; font-weight: 700; font-size: 1.1rem; border-radius: 4px; padding: 13px 0 11px 0; text-align: center; letter-spacing: 1px; text-transform: uppercase; border: none;">${d.buttonText}</a>
                                                </div>
                                            </div>
                                            `;
                                    };
                                    content.renderInvestmentTier();
                                    break;
      }

      component.appendChild(controls);
      component.appendChild(content);

      // Add click handler for selection
      component.addEventListener('click', (e) => {
        // Don't select component if clicking on controls
        if (e.target.closest('.component-controls')) {
          return;
        }
        e.stopPropagation();
        selectComponent(component);
      });

      // Prevent drag events from starting when clicking on component controls
      controls.addEventListener('mousedown', (e) => {
        e.stopPropagation();
        e.stopImmediatePropagation();
      });

      controls.addEventListener('dragstart', (e) => {
        e.preventDefault();
        e.stopPropagation();
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

    // Create an insertion zone
    function createInsertionZone() {
      const insertionZone = document.createElement('div');
      insertionZone.className = 'insertion-zone';
      
      // Add event handlers for insertion zones
      insertionZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Clear drag states
        insertionZone.classList.remove('drag-over');
        
        const type = e.dataTransfer.getData('type');
        if (!type) return;
        
        const parentColumn = insertionZone.closest('.inner-column');
        const parentPage = insertionZone.closest('#page');
        
        if (parentColumn) {
          // This is an insertion within a column
          const newComponent = createComponent(type);
          newComponent.style.width = '100%';
          
          // Insert after this insertion zone
          parentColumn.insertBefore(newComponent, insertionZone.nextSibling);
          
          // Hide dropzone text if column has components
          const columnDropzone = parentColumn.querySelector('.column-dropzone');
          if (columnDropzone) {
            columnDropzone.style.display = 'none';
          }
          
          // Refresh insertion zones and update structure
          setTimeout(() => {
            refreshInsertionZones();
            updateStructurePanel();
            selectComponent(newComponent);
            
            // Save state to history after component is inserted in column
            historyManager.saveState(`Added ${type} to column`);
          }, 10);
          
        } else if (parentPage) {
          // This is an insertion at the main page level
          let componentToAdd;
          
          // Check if this is an inner-section component
          if (type === 'inner-section') {
            // Create inner-section normally
            componentToAdd = createComponent(type);
          } else {
            // For any other component, create an inner-section wrapper with 1 column
            // and place the component inside it
            const innerSection = createComponent('inner-section');
            const innerContent = getContentElement(innerSection);
            
            // Set to 1 column
            innerContent.updateColumns(1);
            innerContent._innerSectionData.columns = 1;
            
            // Update the label to indicate it's auto-created
            const sectionLabel = innerContent.querySelector('.section-label');
            if (sectionLabel) {
              sectionLabel.textContent = 'Auto Section (1 Column)';
              sectionLabel.style.display = 'none'; // Hide label for auto sections
            }
            
            // Create the actual component
            const actualComponent = createComponent(type);
            
            // Add the component to the first (and only) column
            const firstColumn = innerContent.querySelector('.inner-column');
            if (firstColumn) {
              firstColumn.appendChild(actualComponent);
              
              // Hide the dropzone text
              const columnDropzone = firstColumn.querySelector('.column-dropzone');
              if (columnDropzone) {
                columnDropzone.style.display = 'none';
              }
            }
            
            componentToAdd = innerSection;
          }
          
          // Insert at the specific position indicated by the insertion zone
          parentPage.insertBefore(componentToAdd, insertionZone.nextSibling);
          
          // Select the appropriate component
          let componentToSelect;
          if (type === 'inner-section') {
            componentToSelect = componentToAdd;
          } else {
            // Select the actual component inside the auto-created inner-section
            componentToSelect = componentToAdd.querySelector('.inner-column .component');
          }
          
          // Refresh insertion zones and select component
          setTimeout(() => {
            refreshInsertionZones();
            updateStructurePanel();
            if (componentToSelect) {
              selectComponent(componentToSelect);
            }
            
            // Save state to history after component is inserted
            historyManager.saveState(`Inserted ${type} component`);
          }, 10);
        }
      });
      
      insertionZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        insertionZone.classList.add('drag-over');
      });
      
      insertionZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        insertionZone.classList.remove('drag-over');
      });
      
      return insertionZone;
    }

    // Refresh insertion zones throughout the page
    function refreshInsertionZones() {
      // Remove existing insertion zones
      const existingInsertionZones = document.querySelectorAll('.insertion-zone');
      existingInsertionZones.forEach(zone => zone.remove());
      
      // Add insertion zones in main canvas between components
      const page = document.getElementById('page');
      const components = Array.from(page.children).filter(child => child.classList.contains('component'));
      
      components.forEach((component, index) => {
        // Add insertion zone before each component
        const insertionZone = createInsertionZone();
        page.insertBefore(insertionZone, component);
        
        // Add insertion zone after the last component
        if (index === components.length - 1) {
          const finalInsertionZone = createInsertionZone();
          page.insertBefore(finalInsertionZone, component.nextSibling);
        }
      });
      
      // If no components exist, add one insertion zone before the dropzone
      if (components.length === 0) {
        const firstDropzone = page.querySelector('.dropzone');
        if (firstDropzone) {
          const insertionZone = createInsertionZone();
          page.insertBefore(insertionZone, firstDropzone);
        }
      }
      
      // Add insertion zones in inner section columns
      const innerColumns = document.querySelectorAll('.inner-column');
      innerColumns.forEach(column => {
        const columnComponents = column.querySelectorAll('.component');
        
        columnComponents.forEach((component, index) => {
          // Add insertion zone before each component
          const insertionZone = createInsertionZone();
          column.insertBefore(insertionZone, component);
          
          // Add insertion zone after the last component
          if (index === columnComponents.length - 1) {
            const finalInsertionZone = createInsertionZone();
            column.insertBefore(finalInsertionZone, component.nextSibling);
          }
        });
        
        // If column has no components, add one insertion zone at the beginning
        if (columnComponents.length === 0) {
          const insertionZone = createInsertionZone();
          // Insert before the dropzone text or at the beginning
          const columnDropzone = column.querySelector('.column-dropzone');
          if (columnDropzone) {
            column.insertBefore(insertionZone, columnDropzone);
          } else {
            column.insertBefore(insertionZone, column.firstChild);
          }
        }
      });
    }

    // Delete a component
    function deleteComponent(btn, event) {
      // Prevent any drag or other event bubbling
      if (event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
      }
      
      const component = btn.closest('.component');
      const componentType = component.dataset.type || 'component';
      const parentColumn = component.closest('.inner-column');
      const parentSection = component.closest('.inner-section-component');
      
      // Save state before deletion
      historyManager.saveState(`Delete ${componentType} component`);
      
      component.remove();

      // If this component was inside a column, check if we need to show the dropzone
      if (parentColumn) {
        const columnDropzone = parentColumn.querySelector('.column-dropzone');
        const hasComponents = parentColumn.querySelectorAll('.component').length > 0;
        if (columnDropzone) {
          columnDropzone.style.display = hasComponents ? 'none' : 'block';
        }
      }
      // Legacy support: If this component was inside an inner section (old style), check if we need to show the dropzone
      else if (parentSection) {
        const innerDropzone = parentSection.querySelector('.inner-dropzone');
        const hasComponents = parentSection.querySelectorAll('.component').length > 0;
        if (innerDropzone) {
          innerDropzone.style.display = hasComponents ? 'none' : 'block';
        }
      }

      // If there's no dropzone after this component, add one
      const dropzones = document.querySelectorAll('.dropzone');
      if (dropzones.length === 0) {
        const page = document.getElementById('page');
        page.appendChild(createDropzone());
      }

      // Refresh insertion zones after component deletion
      setTimeout(() => {
        refreshInsertionZones();
      }, 10);

      // Clear properties panel if this was the selected component
      if (selectedComponent === component) {
        selectedComponent = null;
        updatePropertyPanel();
      }

      // Update structure panel
      updateStructurePanel();
    }

    // Select a component
    function selectComponent(component) {
      // Safety check
      if (!component) {
        console.warn('No component provided to selectComponent');
        return;
      }

      // Deselect previous component
      if (selectedComponent) {
        selectedComponent.classList.remove('selected');
      }

      lastSelectedComponent = component; // Backup

      // Select new component
      selectedComponent = component;
      component.classList.add('selected');

      // Update property panel with error handling
      try {
        updatePropertyPanel();
      } catch (error) {
        console.error('Error updating property panel:', error);
      }
    }

    // Get component icon and name
    function getComponentInfo(type) {
        const componentInfo = {
            'inner-section': { icon: 'fa-layer-group', name: 'Inner Section' },
            'text': { icon: 'fa-font', name: 'Text' },
            'heading': { icon: 'fa-heading', name: 'Heading' },
            'button': { icon: 'fa-square', name: 'Button' },
            'image': { icon: 'fa-image', name: 'Image' },
            'numbered-timeline': { icon: 'fa-list-ol', name: 'Numbered Timeline' },
            'video': { icon: 'fa-video', name: 'Video' },
            'divider': { icon: 'fa-minus', name: 'Divider' },
            'gallery': { icon: 'fa-images', name: 'Gallery' },
            'slider': { icon: 'fa-sliders-h', name: 'Slider' },
            'section-title': { icon: 'fa-heading', name: 'Section Title' },
            'visitor-upload': { icon: 'fa-upload', name: 'Visitor Upload' },
            'faq': { icon: 'fa-question-circle', name: 'FAQ' },
            'display-assets': { icon: 'fa-folder-open', name: 'Display Assets' },
            'cards': { icon: 'fa-th-large', name: 'Cards' },
            'site-banner': { icon: 'fa-flag', name: 'Site Banner' },
            'custom-banner': { icon: 'fa-flag', name: 'Custom Banner' },
            'full-width-text-image': { icon: 'fa-image', name: 'Full Width Text Image' },
            'alert-message': { icon: 'fa-exclamation-triangle', name: 'Alert Message' },
            'press-card': { icon: 'fa-newspaper', name: 'Press Card' },
            'auction-list': { icon: 'fa-gavel', name: 'Auction List' },
            'event-countdown': { icon: 'fa-clock', name: 'Event Countdown' },
            'event-information': { icon: 'fa-calendar', name: 'Event Information' },
            'sell-tickets': { icon: 'fa-ticket-alt', name: 'Sell Tickets' },
            'whos-coming': { icon: 'fa-users', name: 'Who\'s Coming' },
            'donation-form': { icon: 'fa-heart', name: 'Donation Form' },
            'donor-list': { icon: 'fa-list', name: 'Donor List' },
            'donation-slider': { icon: 'fa-sliders-h', name: 'Donation Slider' },
            'custom-form': { icon: 'fa-wpforms', name: 'Custom Form' },
            'contact-form': { icon: 'fa-envelope', name: 'Contact Form' },
            'social-share': { icon: 'fa-share-alt', name: 'Social Share' },
            'auth-form': { icon: 'fa-user-plus', name: 'Auth Form' },
            'student-leaderboard': { icon: 'fa-trophy', name: 'Student Leaderboard' },
            'student-listing': { icon: 'fa-graduation-cap', name: 'Student Listing' },
            'updates': { icon: 'fa-bullhorn', name: 'Updates' },
            'facebook-comments': { icon: 'fa-facebook', name: 'Facebook Comments' },
            'sponsorships': { icon: 'fa-handshake', name: 'Sponsorships' },
            'contact-us': { icon: 'fa-phone', name: 'Contact Us' },
            'site-goal': { icon: 'fa-thermometer-half', name: 'Site Goal' },
            'invest-cta': { icon: 'fa-dollar-sign', name: 'Investment CTA' },
            'text-images': { icon: 'fa-align-left', name: 'Text & Images' },
            'feature-grid': { icon: 'fa-th-large', name: 'Feature Grid' },
            'investment-tier': { icon: 'fa-coins', name: 'Investment Tier' }
        };
        
        return componentInfo[type] || { icon: 'fa-cube', name: 'Component' };
    }

    // Update property panel based on component type
    function updatePropertyPanel() {
        const propertyControls = document.getElementById('property-panel-content');

        if (!propertyControls) {
            console.warn('Property panel element not found');
            return;
        }

        if (!selectedComponent) {
            propertyControls.innerHTML = '<p>Select a component to edit its properties</p>';
            return;
        }
        
        const content = getContentElement(selectedComponent);
        console.log('UpdatePropertyPanel called for component:', selectedComponent.id);
        console.log('Content element:', content);
        console.log('Responsive styles data:', content ? content._responsiveStyles : 'No content');
        console.log('Current active device:', document.querySelector('.device-tabs .device-tab.active')?.dataset?.device || 'none');

        const type = selectedComponent.dataset.type;
        
        if (!content) {
            propertyControls.innerHTML = '<p>Component content not ready. Please try selecting again.</p>';
            return;
        }

        // Get component info for header
        const componentInfo = getComponentInfo(type);
        
        let specificControls = '';


        switch (type) {

            case 'image':
            const d = content._imageData || {};
            specificControls = `
                <div class="form-group">
                    <label>Upload Image</label>
                    <input type="file" accept="image/*" onchange="uploadSingleImage(event)">
                    <img src="${d.src}" class="image-preview" style="margin-top:8px;max-width:100%;border-radius:4px;"/>
                </div>
                <div class="form-group">
                    <label>Alt Text</label>
                    <input type="text" value="${d.alt || ''}" oninput="updateImageField(this.value, 'alt')">
                </div>
                <div class="form-group">
                    <label>Width</label>
                    <input type="text" value="${d.width || '100%'}" oninput="updateImageField(this.value, 'width')" placeholder="e.g. 100%, 300px, auto">
                    <small style="color: #666; font-size: 12px;">Use %, px, or auto</small>
                </div>
                <div class="form-group">
                    <label>Height</label>
                    <input type="text" value="${d.height || 'auto'}" oninput="updateImageField(this.value, 'height')" placeholder="e.g. auto, 200px, 50%">
                    <small style="color: #666; font-size: 12px;">Use auto, px, or %</small>
                </div>
                <div class="form-group">
                    <label>Object Fit</label>
                    <select oninput="updateImageField(this.value, 'objectFit')">
                        <option value="cover" ${d.objectFit==='cover'?'selected':''}>Cover</option>
                        <option value="contain" ${d.objectFit==='contain'?'selected':''}>Contain</option>
                        <option value="fill" ${d.objectFit==='fill'?'selected':''}>Fill</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="text" value="${d.link || ''}" oninput="updateImageField(this.value, 'link')">
                </div>
                <div class="form-group">
                    <label>Open in new tab</label>
                    <input type="checkbox" ${d.openInNewTab ? 'checked' : ''} onchange="updateImageField(this.checked, 'openInNewTab')">
                </div>
            `;
        break;

            case 'numbered-timeline':
            const timelineData = content._timelineData || {};
            const colors = timelineData.colors || {};
            let itemsHtml = '';
            
            if (timelineData.items) {
                timelineData.items.forEach((item, index) => {
                    itemsHtml += `
                        <div class="timeline-item-editor" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 4px;">
                            <div class="form-group">
                                <label>Item ${index + 1} - Number</label>
                                <input type="text" value="${item.number || ''}" oninput="updateTimelineItem(${index}, 'number', this.value)">
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" value="${item.title || ''}" oninput="updateTimelineItem(${index}, 'title', this.value)">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea style="min-height: 80px;" oninput="updateTimelineItem(${index}, 'description', this.value)">${item.description || ''}</textarea>
                            </div>
                            <button type="button" onclick="removeTimelineItem(${index})" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove Item</button>
                        </div>
                    `;
                });
            }
            
            specificControls = `
                <h4 style="margin: 20px 0 10px 0; color: #333;">Colors</h4>
                <div class="form-group">
                    <label>Number Text Color</label>
                    <input type="color" value="${colors.numberText || '#22c55e'}" oninput="updateTimelineColor('numberText', this.value)">
                </div>
                <div class="form-group">
                    <label>Title Color</label>
                    <input type="color" value="${colors.titleColor || '#22c55e'}" oninput="updateTimelineColor('titleColor', this.value)">
                </div>
                <div class="form-group">
                    <label>Description Color</label>
                    <input type="color" value="${colors.descriptionColor || '#374151'}" oninput="updateTimelineColor('descriptionColor', this.value)">
                </div>
                <div class="form-group">
                    <label>Line Color</label>
                    <input type="color" value="${colors.lineColor || '#22c55e'}" oninput="updateTimelineColor('lineColor', this.value)">
                </div>
                
                <h4 style="margin: 20px 0 10px 0; color: #333;">Timeline Items</h4>
                <div id="timeline-items-container">
                    ${itemsHtml}
                </div>
                <button type="button" onclick="addTimelineItem()" style="background: #22c55e; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-top: 10px;">Add New Item</button>
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
            const textElement = content.querySelector('[id^="text-content-"]');
            const textContent = textElement ? textElement.innerHTML : content.textContent;
            const textEditorId = 'text-editor-' + Date.now();
            ckeditorinitTextBox(textEditorId);
            specificControls = `
                <div class="form-group">
                    <label>Content</label>
                    <textarea id="${textEditorId}" class="ck5-inline-editor" style="border: 1px solid #ddd; min-height: 100px; padding: 10px;">${textContent}</textarea>
                </div>
            `;

            break;

            case 'inner-section':
            const innerSectionData = content._innerSectionData || {};
            const websiteType = document.getElementById('website_type')?.value || 'fundraiser';
            
            // Add to Menu option only for investment websites
            const menuOption = websiteType === 'investment' ? `
                <div class="form-group" style="border: 2px solid #007bff; border-radius: 8px; padding: 15px; margin-bottom: 20px; background-color: #f8f9ff;">
                    <h5 style="color: #007bff; margin-bottom: 10px;">
                        <i class="fas fa-bars"></i> Navigation Menu
                    </h5>
                    <label>
                        <input type="checkbox" ${innerSectionData.addToMenu ? 'checked' : ''} onchange="updateInnerSectionField(this.checked, 'addToMenu'); updateMenuSection(this.checked)">
                        Add this section to navigation menu
                    </label>
                    <small class="text-muted d-block mt-2">When enabled, this section will appear in the website's navigation menu for smooth scrolling.</small>
                    
                    <div class="mt-3" style="display: ${innerSectionData.addToMenu ? 'block' : 'none'};" id="menuTitleGroup">
                        <label>Menu Title</label>
                        <input type="text" value="${innerSectionData.menuTitle || ''}" oninput="updateInnerSectionField(this.value, 'menuTitle')" placeholder="e.g., About Us, Services, Contact">
                        <small class="text-muted">This text will appear in the navigation menu</small>
                    </div>
                    
                    <div class="mt-3" style="display: ${innerSectionData.addToMenu ? 'block' : 'none'};" id="sectionIdGroup">
                        <label>Section ID (Auto-generated)</label>
                        <input type="text" value="${innerSectionData.sectionId || ''}" readonly style="background-color: #f8f9fa;">
                        <small class="text-muted">This ID is used for navigation links. Auto-generated from menu title.</small>
                    </div>
                </div>
            ` : '';
            
            specificControls = `
                ${menuOption}
                <div class="form-group">
                    <label>Number of Columns</label>
                    <select oninput="updateInnerSectionColumns(this.value)">
                        <option value="1" ${innerSectionData.columns === 1 ? 'selected' : ''}>1 Column</option>
                        <option value="2" ${innerSectionData.columns === 2 ? 'selected' : ''}>2 Columns</option>
                        <option value="3" ${innerSectionData.columns === 3 ? 'selected' : ''}>3 Columns</option>
                        <option value="4" ${innerSectionData.columns === 4 ? 'selected' : ''}>4 Columns</option>
                        <option value="5" ${innerSectionData.columns === 5 ? 'selected' : ''}>5 Columns</option>
                        <option value="6" ${innerSectionData.columns === 6 ? 'selected' : ''}>6 Columns</option>
                    </select>
                    <small class="text-muted">Responsive breakpoints:</small>
                    <small class="text-muted">• Desktop (lg): Full columns</small>
                    <small class="text-muted">• Tablet (md): 2 columns max</small>
                    <small class="text-muted">• Mobile (sm): 1 column stacked</small>
                </div>
                <div class="form-group">
                    <label>Column Gap</label>
                    <input type="text" value="${innerSectionData.gap || '15px'}" oninput="updateInnerSectionGap(this.value)">
                    <small>Spacing between columns (e.g., 10px, 1rem, 20px)</small>
                </div>
                
                <h4 style="margin: 20px 0 10px 0; color: #333;">Layout Settings</h4>
                <div class="form-group">
                    <label>
                        <input type="checkbox" ${innerSectionData.fullWidth ? 'checked' : ''} onchange="updateInnerSectionField(this.checked, 'fullWidth')">
                        Stretch to Full Width
                    </label>
                    <small class="text-muted">Make this section span the full width of the page (like Elementor)</small>
                </div>
                <div class="form-group" style="display: ${innerSectionData.fullWidth ? 'block' : 'none'};" id="contentWidthGroup">
                    <label>Content Width</label>
                    <select oninput="updateInnerSectionField(this.value, 'contentWidth')">
                        <option value="full" ${innerSectionData.contentWidth === 'full' ? 'selected' : ''}>Full Width (components spread across full width)</option>
                        <option value="boxed" ${innerSectionData.contentWidth === 'boxed' ? 'selected' : ''}>Boxed (components stay centered)</option>
                    </select>
                    <small class="text-muted">Choose how content should behave within the full-width section</small>
                </div>
                
                <h4 style="margin: 20px 0 10px 0; color: #333;">Background Settings</h4>
                <div class="form-group">
                    <label>Background Type</label>
                    <select oninput="updateInnerSectionField(this.value, 'backgroundType'); toggleInnerSectionBackgroundType(this.value)">
                        <option value="color" ${innerSectionData.backgroundType === 'color' ? 'selected' : ''}>Color</option>
                        <option value="image" ${innerSectionData.backgroundType === 'image' ? 'selected' : ''}>Image</option>
                    </select>
                </div>
                
                <div class="form-group" id="innerSectionBackgroundColor" style="display: ${innerSectionData.backgroundType === 'image' ? 'none' : 'block'};">
                    <label>Background Color</label>
                    <input type="color" value="${innerSectionData.backgroundColor || '#f8f9fa'}" oninput="updateInnerSectionField(this.value, 'backgroundColor')">
                </div>
                
                <div id="innerSectionBackgroundImageSettings" style="display: ${innerSectionData.backgroundType === 'image' ? 'block' : 'none'};">
                    <div class="form-group">
                        <label>Upload Background Image</label>
                        <input type="file" accept="image/*" onchange="uploadInnerSectionBackgroundImage(event)">
                        ${innerSectionData.backgroundImage ? `<div style="margin-top: 8px;"><img src="${innerSectionData.backgroundImage}" style="max-width: 100%; max-height: 100px; border-radius: 4px; border: 1px solid #ddd;"></div>` : ''}
                    </div>
                    
                    <div class="form-group">
                        <label>Background Attachment</label>
                        <select oninput="updateInnerSectionField(this.value, 'backgroundAttachment')">
                            <option value="scroll" ${innerSectionData.backgroundAttachment === 'scroll' ? 'selected' : ''}>Scroll with content</option>
                            <option value="fixed" ${innerSectionData.backgroundAttachment === 'fixed' ? 'selected' : ''}>Fixed (parallax effect)</option>
                        </select>
                        <small>Fixed creates a parallax scrolling effect</small>
                    </div>
                </div>
                
                <h4 style="margin: 20px 0 10px 0; color: #333;">Spacing</h4>
                <div class="form-group">
                    <label>Padding</label>
                    <input type="text" value="${innerSectionData.padding || '20px'}" oninput="updateInnerSectionField(this.value, 'padding')">
                </div>
                <div class="form-group">
                    <label>Margin</label>
                    <input type="text" value="${innerSectionData.margin || '10px 0'}" oninput="updateInnerSectionField(this.value, 'margin')">
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

            case 'auction-list':
            specificControls = `
                <div class="form-group">
                    <label>Auction List Settings</label>
                    <p>This component displays live auction items from your auction system.</p>
                </div>
                <div class="form-group">
                    <label>Items per Row</label>
                    <select oninput="updateAuctionListColumns(this.value)">
                        <option value="1">1 Column</option>
                        <option value="2">2 Columns</option>
                        <option value="3" selected>3 Columns</option>
                        <option value="4">4 Columns</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Show Starting Bid</label>
                    <input type="checkbox" checked onchange="toggleAuctionStartingBid(this.checked)">
                </div>
            `;
            break;

            case 'section-title':
            specificControls = `
                <div class="form-group">
                <label>Section Title</label>
                <input type="text" value="${content.textContent}" oninput="updateContent(this.value)">
                </div>
                <div class="form-group">
                <small class="text-muted">💡 Use the Color control in the Style section above to change the text color.</small>
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
                const bannerData = content._customBannerData || {
                    imgSrc: '',
                    title: 'Custom Banner Title',
                    subtitle: 'Custom Banner Subtitle',
                    titleShadow: '0 2px 8px rgba(0,0,0,0.5)',
                    subtitleShadow: '0 2px 8px rgba(0,0,0,0.5)',
                    titleColor: '#ffffff',
                    subtitleColor: '#ffffff',
                    titleFontSize: '2.2em',
                    subtitleFontSize: '1.2em',
                    textAlign: 'center'
                };
                specificControls = `
                    <div class="form-group">
                        <label>Banner Image</label>
                        <input type="file" accept="image/*" onchange="uploadCustomBannerImage(event)">
                    </div>
                    <div class="form-group">
                        <label>Banner Title</label>
                        <input type="text" value="${bannerData.title || ''}" oninput="updateCustomBannerField(this.value, 'title')">
                    </div>
                    <div class="form-group">
                        <label>Title Color</label>
                        <input type="color" value="${bannerData.titleColor || '#ffffff'}" oninput="updateCustomBannerField(this.value, 'titleColor')">
                    </div>
                    <div class="form-group">
                        <label>Title Font Size</label>
                        <input type="text" value="${bannerData.titleFontSize || '2.2em'}" oninput="updateCustomBannerField(this.value, 'titleFontSize')">
                    </div>
                    <div class="form-group">
                        <label>Banner Subtitle</label>
                        <input type="text" value="${bannerData.subtitle || ''}" oninput="updateCustomBannerField(this.value, 'subtitle')">
                    </div>
                    <div class="form-group">
                        <label>Subtitle Color</label>
                        <input type="color" value="${bannerData.subtitleColor || '#ffffff'}" oninput="updateCustomBannerField(this.value, 'subtitleColor')">
                    </div>
                    <div class="form-group">
                        <label>Subtitle Font Size</label>
                        <input type="text" value="${bannerData.subtitleFontSize || '1.2em'}" oninput="updateCustomBannerField(this.value, 'subtitleFontSize')">
                    </div>
                    <div class="form-group">
                        <label>Title Drop Shadow</label>
                        <input type="text" value="${bannerData.titleShadow || ''}" oninput="updateCustomBannerField(this.value, 'titleShadow')">
                        <small>e.g. 0 2px 8px rgba(0,0,0,0.5)</small>
                    </div>
                    <div class="form-group">
                        <label>Subtitle Drop Shadow</label>
                        <input type="text" value="${bannerData.subtitleShadow || ''}" oninput="updateCustomBannerField(this.value, 'subtitleShadow')">
                        <small>e.g. 0 2px 8px rgba(0,0,0,0.5)</small>
                    </div>
                    <div class="form-group">
                        <label>Text Align</label>
                        <select oninput="updateCustomBannerField(this.value, 'textAlign')">
                            <option value="left" ${bannerData.textAlign === 'left' ? 'selected' : ''}>Left</option>
                            <option value="center" ${bannerData.textAlign === 'center' ? 'selected' : ''}>Center</option>
                            <option value="right" ${bannerData.textAlign === 'right' ? 'selected' : ''}>Right</option>
                        </select>
                    </div>
                `;
            break;

            case 'gallery':
                const galleryData = content._galleryData || { images: [], columns: 3 };
                specificControls = `
                    <div class="form-group">
                        <label>Upload Images</label>
                        <input type="file" accept="image/*" multiple onchange="uploadGalleryImages(event)">
                        <div style="margin-top:8px;">
                            ${'${galleryData.images.map((src, idx) => `<img src="${src}" style="width:60px;height:40px;object-fit:cover;border-radius:4px;margin-right:4px;cursor:pointer;" onclick="openGalleryModalFromPanel(${idx})">`).join(\'\')}'}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Columns</label>
                        <input type="number" min="1" max="6" value="${'${galleryData.columns}'}" oninput="updateGalleryColumns(this.value)">
                    </div>
                `;
            break;
            case 'slider':
                const sliderData = content._sliderData || { images: [], slidesToShow: 1, slideSpeed: 2000 };
                specificControls = `
                    <div class="form-group">
                        <label>Upload Images</label>
                        <input type="file" accept="image/*" multiple onchange="uploadSliderImages(event)">
                        <div style="margin-top:8px;display:flex;gap:4px;flex-wrap:wrap;">
                            ${'${sliderData.images.map((src, idx) => `<img src="${src}" style="width:60px;height:40px;object-fit:cover;border-radius:4px;cursor:pointer;" onclick="openSliderModalFromPanel(${idx})">`).join(\'\')}'}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Slides to Show</label>
                        <input type="number" min="1" max="10" value="${'${sliderData.slidesToShow}'}" oninput="updateSliderSlidesToShow(this.value)">
                    </div>
                    <div class="form-group">
                        <label>Slide Speed (ms)</label>
                        <input type="number" min="500" max="10000" value="${'${sliderData.slideSpeed}'}" oninput="updateSliderSlideSpeed(this.value)">
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
                const currentUrl = content._videoData ? content._videoData.url : '';
                const currentType = content._videoData ? content._videoData.type : 'youtube';
                const autoplayEnabled = content._videoData ? content._videoData.autoplay : false;
                const currentWidth = content._videoData ? content._videoData.width : null;
                const currentHeight = content._videoData ? content._videoData.height : null;
                specificControls = `
                    <div class="form-group">
                        <label>Video Type</label>
                        <select onchange="switchVideoType(this.value)" id="videoTypeSelect">
                            <option value="youtube" ${currentType === 'youtube' ? 'selected' : ''}>YouTube Video</option>
                            <option value="uploaded" ${currentType === 'uploaded' ? 'selected' : ''}>Upload Video File</option>
                        </select>
                    </div>
                    
                    <div id="youtubeControls" style="display: ${currentType === 'youtube' ? 'block' : 'none'};">
                        <div class="form-group">
                            <label>YouTube Video URL</label>
                            <input type="text" value="${currentType === 'youtube' ? currentUrl : ''}" oninput="updateVideoEmbed(this.value, 'youtube')" placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted">Paste a YouTube video URL</small>
                        </div>
                    </div>
                    
                    <div id="uploadControls" style="display: ${currentType === 'uploaded' ? 'block' : 'none'};">
                        <div class="form-group">
                            <label>Upload Video File</label>
                            <input type="file" accept="video/*" onchange="uploadVideoFile(event)" class="form-control mb-2">
                            <input type="text" value="${currentType === 'uploaded' ? currentUrl : ''}" oninput="updateVideoEmbed(this.value, 'uploaded')" placeholder="Or enter video file URL">
                            <small class="text-muted">Upload a video file (MP4, WebM, OGG) up to 10MB or enter a URL</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" ${autoplayEnabled ? 'checked' : ''} onchange="updateVideoAutoplay(this.checked)"> 
                            Enable Autoplay (Frontend Only)
                        </label>
                        <small class="text-muted">Video will autoplay when loaded on the frontend</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Width (px)</label>
                                <input type="number" class="form-control" value="${currentWidth || ''}" onchange="updateVideoSize('width', this.value)" placeholder="Auto" min="100" max="1200">
                                <small class="text-muted">Leave empty for responsive width</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Height (px)</label>
                                <input type="number" class="form-control" value="${currentHeight || ''}" onchange="updateVideoSize('height', this.value)" placeholder="Auto" min="100" max="800">
                                <small class="text-muted">Leave empty for responsive height</small>
                            </div>
                        </div>
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
                const faqData = content._faqData || {
                    questions: [],
                    questionBackgroundColor: '#f3f4f6',
                    questionTextColor: '#1f2937',
                    answerBackgroundColor: '#ffffff',
                    answerTextColor: '#374151',
                    iconColor: '#059669',
                    borderRadius: '8px',
                    spacing: '10px'
                };
                
                specificControls = `
                    <h4 style="margin: 20px 0 10px 0; color: #333;">FAQ Questions</h4>
                    <div class="form-group">
                        <button type="button" onclick="addFaqQuestion()" style="background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-bottom: 10px;">
                            + Add Question
                        </button>
                    </div>
                    <div id="faq-questions-list">
                        ${faqData.questions.map((q, index) => `
                            <div class="faq-question-item" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 4px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong>Question ${index + 1}</strong>
                                    <button type="button" onclick="removeFaqQuestion(${index})" style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 12px;">Remove</button>
                                </div>
                                <div class="form-group">
                                    <label>Question:</label>
                                    <input type="text" value="${q.question}" oninput="updateFaqQuestion(${index}, 'question', this.value)" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 3px;">
                                </div>
                                <div class="form-group">
                                    <label>Answer:</label>
                                    <textarea oninput="updateFaqQuestion(${index}, 'answer', this.value)" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 3px; min-height: 60px; resize: vertical;">${q.answer}</textarea>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <h4 style="margin: 20px 0 10px 0; color: #333;">Styling Options</h4>
                    <div class="form-group">
                        <label>Question Background Color</label>
                        <input type="color" value="${faqData.questionBackgroundColor}" oninput="updateFaqStyle('questionBackgroundColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Question Text Color</label>
                        <input type="color" value="${faqData.questionTextColor}" oninput="updateFaqStyle('questionTextColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Answer Background Color</label>
                        <input type="color" value="${faqData.answerBackgroundColor}" oninput="updateFaqStyle('answerBackgroundColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Answer Text Color</label>
                        <input type="color" value="${faqData.answerTextColor}" oninput="updateFaqStyle('answerTextColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Icon Color</label>
                        <input type="color" value="${faqData.iconColor}" oninput="updateFaqStyle('iconColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Border Radius</label>
                        <input type="text" value="${faqData.borderRadius}" oninput="updateFaqStyle('borderRadius', this.value)" placeholder="e.g. 8px">
                    </div>
                    <div class="form-group">
                        <label>Spacing Between Items</label>
                        <input type="text" value="${faqData.spacing}" oninput="updateFaqStyle('spacing', this.value)" placeholder="e.g. 10px">
                    </div>
                `;
            break;

            case 'simple-comments':
                const simpleCommentsData = content._simpleCommentsData || {
                    title: 'Comments',
                    showTitle: true,
                    allowAnonymous: true,
                    moderationEnabled: false,
                    requireEmail: true,
                    maxComments: 100,
                    sortOrder: 'newest',
                    backgroundColor: '#ffffff',
                    borderColor: '#e0e0e0',
                    textColor: '#333333',
                    buttonColor: '#007bff'
                };
                
                specificControls = `
                    <h4 style="margin: 20px 0 10px 0; color: #333;">
                        <i class="fas fa-comment-dots" style="margin-right: 8px; color: #007bff;"></i>
                        Simple Comments Settings
                    </h4>
                    
                    <div class="alert alert-info" style="margin-bottom: 16px; padding: 12px; background: #e3f2fd; border: 1px solid #2196f3; border-radius: 6px;">
                        <i class="fas fa-info-circle" style="margin-right: 6px; color: #1976d2;"></i>
                        <strong>No Setup Required!</strong> Comments are stored in your database and work immediately.
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-heading" style="margin-right: 6px;"></i>
                            Comments Section Title
                        </label>
                        <input type="text" value="${simpleCommentsData.title}" 
                               oninput="updateSimpleCommentsField('title', this.value)" 
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; display: flex; align-items: center;">
                            <input type="checkbox" ${simpleCommentsData.showTitle ? 'checked' : ''} 
                                   onchange="updateSimpleCommentsField('showTitle', this.checked)"
                                   style="margin-right: 8px;">
                            <i class="fas fa-eye" style="margin-right: 6px;"></i>
                            Show section title
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; display: flex; align-items: center;">
                            <input type="checkbox" ${simpleCommentsData.allowAnonymous ? 'checked' : ''} 
                                   onchange="updateSimpleCommentsField('allowAnonymous', this.checked)"
                                   style="margin-right: 8px;">
                            <i class="fas fa-user-secret" style="margin-right: 6px;"></i>
                            Allow anonymous comments
                        </label>
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px; margin-left: 24px;">
                            Users can comment without creating an account
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; display: flex; align-items: center;">
                            <input type="checkbox" ${simpleCommentsData.requireEmail ? 'checked' : ''} 
                                   onchange="updateSimpleCommentsField('requireEmail', this.checked)"
                                   style="margin-right: 8px;">
                            <i class="fas fa-envelope" style="margin-right: 6px;"></i>
                            Require email address
                        </label>
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px; margin-left: 24px;">
                            Email is required for posting comments
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; display: flex; align-items: center;">
                            <input type="checkbox" ${simpleCommentsData.moderationEnabled ? 'checked' : ''} 
                                   onchange="updateSimpleCommentsField('moderationEnabled', this.checked)"
                                   style="margin-right: 8px;">
                            <i class="fas fa-shield-alt" style="margin-right: 6px;"></i>
                            Enable comment moderation
                        </label>
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px; margin-left: 24px;">
                            Comments need approval before appearing
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-sort" style="margin-right: 6px;"></i>
                            Comment Sort Order
                        </label>
                        <select onchange="updateSimpleCommentsField('sortOrder', this.value)" 
                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="newest" ${simpleCommentsData.sortOrder === 'newest' ? 'selected' : ''}>Newest First</option>
                            <option value="oldest" ${simpleCommentsData.sortOrder === 'oldest' ? 'selected' : ''}>Oldest First</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-list-ol" style="margin-right: 6px;"></i>
                            Maximum Comments to Show
                        </label>
                        <input type="number" value="${simpleCommentsData.maxComments}" min="10" max="1000"
                               oninput="updateSimpleCommentsField('maxComments', parseInt(this.value))" 
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">
                            Older comments will be paginated
                        </small>
                    </div>
                    
                    <h5 style="margin: 20px 0 10px 0; color: #333;">Styling Options</h5>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">Background Color</label>
                        <input type="color" value="${simpleCommentsData.backgroundColor}" 
                               oninput="updateSimpleCommentsField('backgroundColor', this.value)"
                               style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">Border Color</label>
                        <input type="color" value="${simpleCommentsData.borderColor}" 
                               oninput="updateSimpleCommentsField('borderColor', this.value)"
                               style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">Text Color</label>
                        <input type="color" value="${simpleCommentsData.textColor}" 
                               oninput="updateSimpleCommentsField('textColor', this.value)"
                               style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">Button Color</label>
                        <input type="color" value="${simpleCommentsData.buttonColor}" 
                               oninput="updateSimpleCommentsField('buttonColor', this.value)"
                               style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                `;
            break;

            case 'disqus':
                const disqusData = content._disqusData || {
                    shortname: '',
                    identifier: '',
                    title: '',
                    url: '',
                    showInPreview: true
                };
                
                specificControls = `
                    <h4 style="margin: 20px 0 10px 0; color: #333;">
                        <i class="fas fa-comments" style="margin-right: 8px; color: #2e9fff;"></i>
                        Disqus Comments Configuration
                    </h4>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-cog" style="margin-right: 6px;"></i>
                            Disqus Shortname <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" value="${disqusData.shortname}" 
                               oninput="updateDisqusField('shortname', this.value)" 
                               placeholder="your-site-shortname"
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace;">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">
                            <i class="fas fa-info-circle" style="margin-right: 4px;"></i>
                            Find your shortname in Disqus Admin → Settings → General
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-fingerprint" style="margin-right: 6px;"></i>
                            Page Identifier (Optional)
                        </label>
                        <input type="text" value="${disqusData.identifier}" 
                               oninput="updateDisqusField('identifier', this.value)" 
                               placeholder="unique-page-id"
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">
                            Unique identifier for this page. Leave empty to use the page URL.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-heading" style="margin-right: 6px;"></i>
                            Discussion Title (Optional)
                        </label>
                        <input type="text" value="${disqusData.title}" 
                               oninput="updateDisqusField('title', this.value)" 
                               placeholder="Custom discussion title"
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">
                            Custom title for the discussion. Leave empty to use the page title.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333;">
                            <i class="fas fa-link" style="margin-right: 6px;"></i>
                            Canonical URL (Optional)
                        </label>
                        <input type="url" value="${disqusData.url}" 
                               oninput="updateDisqusField('url', this.value)" 
                               placeholder="https://example.com/page"
                               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">
                            Canonical URL for this page. Leave empty to use the current page URL.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; display: flex; align-items: center;">
                            <input type="checkbox" ${disqusData.showInPreview ? 'checked' : ''} 
                                   onchange="updateDisqusField('showInPreview', this.checked)"
                                   style="margin-right: 8px;">
                            <i class="fas fa-eye" style="margin-right: 6px;"></i>
                            Show preview in page builder
                        </label>
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px; margin-left: 24px;">
                            Display a preview box in the page builder. Uncheck to show only a minimal placeholder.
                        </small>
                    </div>
                    
                    <div style="
                        background: #e3f2fd; 
                        border: 1px solid #2196f3; 
                        border-radius: 6px; 
                        padding: 12px; 
                        margin: 16px 0;
                        font-size: 13px;
                    ">
                        <div style="display: flex; align-items: flex-start;">
                            <i class="fas fa-lightbulb" style="color: #2196f3; margin-right: 8px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #1976d2;">Setup Required:</strong><br>
                                Make sure you have a Disqus account and have registered your website at 
                                <a href="https://disqus.com/admin/" target="_blank" style="color: #1976d2;">disqus.com/admin</a>
                            </div>
                        </div>
                    </div>
                `;
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
                const fwti = content._fwtiData || {};
                specificControls = `
                    <div class="form-group">
                        <label>Title Text</label>
                        <input type="text" value="${fwti.text1 || ''}" oninput="updateFWTIField(this.value, 'text1')">
                    </div>
                    <div class="form-group">
                        <label>Title Font Size</label>
                        <input type="text" value="${fwti.fontSize1 || '32px'}" oninput="updateFWTIField(this.value, 'fontSize1')">
                    </div>
                    <div class="form-group">
                        <label>Title Color</label>
                        <input type="color" value="${fwti.color1 || '#222222'}" oninput="updateFWTIField(this.value, 'color1')">
                    </div>
                    <div class="form-group">
                        <label>Subtitle Text</label>
                        <input type="text" value="${fwti.text2 || ''}" oninput="updateFWTIField(this.value, 'text2')">
                    </div>
                    <div class="form-group">
                        <label>Subtitle Font Size</label>
                        <input type="text" value="${fwti.fontSize2 || '18px'}" oninput="updateFWTIField(this.value, 'fontSize2')">
                    </div>
                    <div class="form-group">
                        <label>Subtitle Color</label>
                        <input type="color" value="${fwti.color2 || '#444444'}" oninput="updateFWTIField(this.value, 'color2')">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" accept="image/*" onchange="uploadFWTIImage(event)">
                        <img src="${fwti.imgSrc}" style="max-width:100%;margin-top:8px;border-radius:4px;${fwti.imgSrc ? '' : 'display:none;'}"/>
                    </div>
                    <div class="form-group">
                        <label>Image Alt Text</label>
                        <input type="text" value="${fwti.imgAlt || ''}" oninput="updateFWTIField(this.value, 'imgAlt')">
                    </div>
                    <div class="form-group">
                        <label>Image Width</label>
                        <input type="text" value="${fwti.imgCustomWidth || '100%'}" oninput="updateFWTIField(this.value, 'imgCustomWidth')">
                    </div>
                    <div class="form-group">
                        <label>Image Height</label>
                        <input type="text" value="${fwti.imgCustomHeight || 'auto'}" oninput="updateFWTIField(this.value, 'imgCustomHeight')">
                    </div>
                    <div class="form-group">
                        <label>Image Object Fit</label>
                        <select oninput="updateFWTIField(this.value, 'imgObjectFit')">
                            <option value="cover" ${fwti.imgObjectFit==='cover'?'selected':''}>Cover</option>
                            <option value="contain" ${fwti.imgObjectFit==='contain'?'selected':''}>Contain</option>
                            <option value="fill" ${fwti.imgObjectFit==='fill'?'selected':''}>Fill</option>
                        </select>
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

            case 'press-card':
                const pressData = content._pressCardData || {
                    logoSrc: '',
                    logoAlt: 'Press Logo',
                    title: 'Press Article Title',
                    url: '#',
                    date: 'Date',
                    target: '_blank',
                    cardBackgroundColor: '#ffffff',
                    cardBorderRadius: '8px',
                    cardBoxShadow: '0 2px 8px rgba(0,0,0,0.1)',
                    overlayOpacity: '0.1',
                    logoBackgroundColor: '#f8f9fa',
                    titleColor: '#1a1a1a',
                    dateColor: '#666666'
                };
                specificControls = `
                    <div class="form-group">
                        <label>Press Logo</label>
                        <input type="file" accept="image/*" onchange="uploadPressCardImage(event)" class="form-control mb-2">
                        <input type="text" value="${pressData.logoSrc}" oninput="updatePressCardField('logoSrc', this.value)" placeholder="Or enter image URL">
                        <small class="text-muted">Upload an image or enter a URL</small>
                    </div>
                    <div class="form-group">
                        <label>Logo Alt Text</label>
                        <input type="text" value="${pressData.logoAlt}" oninput="updatePressCardField('logoAlt', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Article Title</label>
                        <textarea oninput="updatePressCardField('title', this.value)">${pressData.title}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Article URL</label>
                        <input type="url" value="${pressData.url}" oninput="updatePressCardField('url', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Publication Date</label>
                        <input type="text" value="${pressData.date}" oninput="updatePressCardField('date', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Link Target</label>
                        <select oninput="updatePressCardField('target', this.value)">
                            <option value="_blank" ${pressData.target === '_blank' ? 'selected' : ''}>New Tab</option>
                            <option value="_self" ${pressData.target === '_self' ? 'selected' : ''}>Same Tab</option>
                        </select>
                    </div>
                    
                    <h5 style="margin-top: 20px; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Card Design</h5>
                    <div class="form-group">
                        <label>Card Background Color</label>
                        <input type="color" value="${pressData.cardBackgroundColor}" oninput="updatePressCardField('cardBackgroundColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Logo Area Background</label>
                        <input type="color" value="${pressData.logoBackgroundColor}" oninput="updatePressCardField('logoBackgroundColor', this.value)">
                        <small class="text-muted">Background color for the logo section</small>
                    </div>
                    <div class="form-group">
                        <label>Card Shadow</label>
                        <select oninput="updatePressCardField('cardBoxShadow', this.value)">
                            <option value="none" ${pressData.cardBoxShadow === 'none' ? 'selected' : ''}>No Shadow</option>
                            <option value="0 2px 8px rgba(0,0,0,0.1)" ${pressData.cardBoxShadow === '0 2px 8px rgba(0,0,0,0.1)' ? 'selected' : ''}>Light Shadow</option>
                            <option value="0 4px 16px rgba(0,0,0,0.15)" ${pressData.cardBoxShadow === '0 4px 16px rgba(0,0,0,0.15)' ? 'selected' : ''}>Medium Shadow</option>
                            <option value="0 8px 32px rgba(0,0,0,0.2)" ${pressData.cardBoxShadow === '0 8px 32px rgba(0,0,0,0.2)' ? 'selected' : ''}>Strong Shadow</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Black Overlay Opacity</label>
                        <input type="range" min="0" max="0.5" step="0.05" value="${pressData.overlayOpacity}" oninput="updatePressCardField('overlayOpacity', this.value)">
                        <small class="text-muted">Current: ${(parseFloat(pressData.overlayOpacity) * 100).toFixed(0)}%</small>
                    </div>
                    
                    <h5 style="margin-top: 20px; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Text Colors</h5>
                    <div class="form-group">
                        <label>Title Color</label>
                        <input type="color" value="${pressData.titleColor}" oninput="updatePressCardField('titleColor', this.value)">
                    </div>
                    <div class="form-group">
                        <label>Date Color</label>
                        <input type="color" value="${pressData.dateColor}" oninput="updatePressCardField('dateColor', this.value)">
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
                const sellData = content._sellTicketsData || {
                    title: 'Buy Tickets',
                    buttonText: 'Buy Now',
                    buttonBg: '#007bff',
                    buttonColor: '#fff',
                    buttonPadding: '10px 20px',
                    buttonRadius: '4px'
                };
                specificControls = `
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="${sellData.title}" oninput="updateSellTicketsField(this.value, 'title')">
                    </div>
                    <div class="form-group">
                        <label>Button Text</label>
                        <input type="text" value="${sellData.buttonText}" oninput="updateSellTicketsField(this.value, 'buttonText')">
                    </div>
                    <div class="form-group">
                        <label>Button Background</label>
                        <input type="color" value="${sellData.buttonBg}" oninput="updateSellTicketsField(this.value, 'buttonBg')">
                    </div>
                    <div class="form-group">
                        <label>Button Text Color</label>
                        <input type="color" value="${sellData.buttonColor}" oninput="updateSellTicketsField(this.value, 'buttonColor')">
                    </div>
                    <div class="form-group">
                        <label>Button Padding</label>
                        <input type="text" value="${sellData.buttonPadding}" oninput="updateSellTicketsField(this.value, 'buttonPadding')">
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
                const textImagesData = content._textImagesData || { text: '', imgSrc: '', imgPosition: 'left', imgSize: 200, imgWidth: '200', imgHeight: 'auto', showImage: true };
                const editorId = 'text-images-editor-' + Date.now();

                ckeditorinit(editorId);

                // Always default select to 'left' if not set
                let imgPosition = textImagesData.imgPosition || 'left';
                let imgWidth = textImagesData.imgWidth || textImagesData.imgSize || '200';
                let imgHeight = textImagesData.imgHeight || 'auto';
                specificControls = `
                    <div class="form-group">
                        <label>Text Content</label>
                        <textarea oninput="updateTextImagesField(this.value, 'text')" id="${editorId}" class="ck5-inline-editor" style="border: 1px solid #ddd; min-height: 100px; padding: 10px;">${textImagesData.text || ''}</textarea>
                    </div>
                
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" accept="image/*" onchange="uploadTextImagesImage(event)">
                        <img src="${textImagesData.imgSrc}" style="max-width:100%;margin-top:8px;border-radius:4px;"/>
                    </div>
                    <div class="form-group">
                        <label>Image Position</label>
                        <select oninput="updateTextImagesField(this.value, 'imgPosition')">
                            <option value="up" ${imgPosition==='up'?'selected':''}>Up</option>
                            <option value="down" ${imgPosition==='down'?'selected':''}>Down</option>
                            <option value="left" ${imgPosition==='left'?'selected':''}>Left</option>
                            <option value="right" ${imgPosition==='right'?'selected':''}>Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image Width (px or %)</label>
                        <input type="text" value="${imgWidth}" oninput="updateTextImagesField(this.value, 'imgWidth')" placeholder="200px or 100%">
                    </div>
                    <div class="form-group">
                        <label>Image Height (px or auto)</label>
                        <input type="text" value="${imgHeight}" oninput="updateTextImagesField(this.value, 'imgHeight')" placeholder="auto or 150px">
                    </div>
                    <div class="form-group">
                        <label>Show Image</label>
                        <input type="checkbox" ${textImagesData.showImage ? 'checked' : ''} onchange="toggleTextImagesShowImage(this)">
                    </div>
                `;
            break;
            case 'feature-grid':
                const featureGridData = content._featureGridData || { 
                    features: [],
                    iconColor: '#3b82f6',
                    titleColor: '#1f2937',
                    descriptionColor: '#6b7280'
                };
                // Ensure color properties exist in the actual data
                if (!content._featureGridData) {
                    content._featureGridData = featureGridData;
                } else {
                    if (!content._featureGridData.iconColor) content._featureGridData.iconColor = '#3b82f6';
                    if (!content._featureGridData.titleColor) content._featureGridData.titleColor = '#1f2937';
                    if (!content._featureGridData.descriptionColor) content._featureGridData.descriptionColor = '#6b7280';
                }
                let featureItems = '';
                featureGridData.features.forEach((feature, index) => {
                    featureItems += `
                        <div class="feature-item-editor" style="border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                            <h4>Feature ${index + 1}</h4>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" value="${feature.title}" oninput="updateFeatureGridField(${index}, this.value, 'title')">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea oninput="updateFeatureGridField(${index}, this.value, 'description')">${feature.description}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select onchange="updateFeatureGridField(${index}, this.value, 'icon')" style="width: 100%;">
                                    <option value="fas fa-chart-line" ${feature.icon === 'fas fa-chart-line' ? 'selected' : ''}>📈 Chart Line</option>
                                    <option value="fas fa-trophy" ${feature.icon === 'fas fa-trophy' ? 'selected' : ''}>🏆 Trophy</option>
                                    <option value="fas fa-expand-arrows-alt" ${feature.icon === 'fas fa-expand-arrows-alt' ? 'selected' : ''}>🔄 Expand</option>
                                    <option value="fas fa-gem" ${feature.icon === 'fas fa-gem' ? 'selected' : ''}>💎 Gem</option>
                                    <option value="fas fa-star" ${feature.icon === 'fas fa-star' ? 'selected' : ''}>⭐ Star</option>
                                    <option value="fas fa-shield-alt" ${feature.icon === 'fas fa-shield-alt' ? 'selected' : ''}>🛡️ Shield</option>
                                    <option value="fas fa-rocket" ${feature.icon === 'fas fa-rocket' ? 'selected' : ''}>🚀 Rocket</option>
                                    <option value="fas fa-lightbulb" ${feature.icon === 'fas fa-lightbulb' ? 'selected' : ''}>💡 Lightbulb</option>
                                    <option value="fas fa-cogs" ${feature.icon === 'fas fa-cogs' ? 'selected' : ''}>⚙️ Cogs</option>
                                    <option value="fas fa-heart" ${feature.icon === 'fas fa-heart' ? 'selected' : ''}>❤️ Heart</option>
                                    <option value="fas fa-users" ${feature.icon === 'fas fa-users' ? 'selected' : ''}>👥 Users</option>
                                    <option value="fas fa-dollar-sign" ${feature.icon === 'fas fa-dollar-sign' ? 'selected' : ''}>💰 Dollar</option>
                                    <option value="fas fa-lock" ${feature.icon === 'fas fa-lock' ? 'selected' : ''}>🔒 Lock</option>
                                    <option value="fas fa-thumbs-up" ${feature.icon === 'fas fa-thumbs-up' ? 'selected' : ''}>👍 Thumbs Up</option>
                                    <option value="fas fa-check-circle" ${feature.icon === 'fas fa-check-circle' ? 'selected' : ''}>✅ Check Circle</option>
                                    <option value="fas fa-fire" ${feature.icon === 'fas fa-fire' ? 'selected' : ''}>🔥 Fire</option>
                                    <option value="fas fa-globe" ${feature.icon === 'fas fa-globe' ? 'selected' : ''}>🌍 Globe</option>
                                    <option value="fas fa-mobile-alt" ${feature.icon === 'fas fa-mobile-alt' ? 'selected' : ''}>📱 Mobile</option>
                                    <option value="fas fa-cloud" ${feature.icon === 'fas fa-cloud' ? 'selected' : ''}>☁️ Cloud</option>
                                    <option value="fas fa-handshake" ${feature.icon === 'fas fa-handshake' ? 'selected' : ''}>🤝 Handshake</option>
                                </select>
                                <div style="margin-top: 8px; padding: 8px; background: #f8f9fa; border-radius: 4px; text-align: center;">
                                    <i class="${feature.icon}" style="font-size: 24px; color: ${featureGridData.iconColor || '#3b82f6'};"></i>
                                    <small style="display: block; margin-top: 4px; color: #666;">Preview</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeFeatureGridItem(${index})">Remove Feature</button>
                        </div>
                    `;
                });
                specificControls = `
                    <div class="form-group">
                        <label>Icon Color</label>
                        <input type="color" value="${featureGridData.iconColor || '#3b82f6'}" onchange="updateFeatureGridColor(this.value, 'iconColor')">
                    </div>
                    <div class="form-group">
                        <label>Title Color</label>
                        <input type="color" value="${featureGridData.titleColor || '#1f2937'}" onchange="updateFeatureGridColor(this.value, 'titleColor')">
                    </div>
                    <div class="form-group">
                        <label>Description Color</label>
                        <input type="color" value="${featureGridData.descriptionColor || '#6b7280'}" onchange="updateFeatureGridColor(this.value, 'descriptionColor')">
                    </div>
                    <hr style="margin: 1rem 0;">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" onclick="addFeatureGridItem()">Add Feature</button>
                    </div>
                    <div class="feature-grid-items">
                        ${featureItems}
                    </div>
                `;
            break;

            case 'investment-tier':
                const investmentTierData = content._investmentTierData || {};
                specificControls = `
                    <div class="form-group">
                        <label>Tier Name</label>
                        <input type="text" value="${investmentTierData.tierName || 'TIER 1'}" oninput="updateInvestmentTierField(this.value, 'tierName')">
                    </div>
                    <div class="form-group">
                        <label>Tier Price</label>
                        <input type="text" value="${investmentTierData.tierPrice || '$2,500'}" oninput="updateInvestmentTierField(this.value, 'tierPrice')">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea rows="4" oninput="updateInvestmentTierField(this.value, 'tierDescription')">${investmentTierData.tierDescription || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Button Text</label>
                        <input type="text" value="${investmentTierData.buttonText || 'INVEST NOW'}" oninput="updateInvestmentTierField(this.value, 'buttonText')">
                    </div>
                    <div class="form-group">
                        <label>Button URL</label>
                        <input type="text" value="${investmentTierData.buttonUrl || '#'}" oninput="updateInvestmentTierField(this.value, 'buttonUrl')" placeholder="Leave as # to auto-redirect to /invest with tier price">
                        <small style="color: #666; font-size: 0.9em; margin-top: 4px; display: block;">
                            Leave as "#" to automatically redirect to /invest page with tier price as amount parameter
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Button Target</label>
                        <select onchange="updateInvestmentTierField(this.value, 'buttonTarget')">
                            <option value="_self" ${investmentTierData.buttonTarget === '_self' ? 'selected' : ''}>Same Window</option>
                            <option value="_blank" ${investmentTierData.buttonTarget === '_blank' ? 'selected' : ''}>New Window</option>
                        </select>
                    </div>
                    <hr style="margin: 1rem 0;">
                    <div class="form-group">
                        <label>Background Type</label>
                        <select onchange="updateInvestmentTierField(this.value, 'backgroundType')">
                            <option value="color" ${investmentTierData.backgroundType === 'color' ? 'selected' : ''}>Background Color</option>
                            <option value="image" ${investmentTierData.backgroundType === 'image' ? 'selected' : ''}>Background Image</option>
                        </select>
                    </div>
                    <div class="form-group" id="backgroundColor-group" style="display: ${investmentTierData.backgroundType === 'image' ? 'none' : 'block'};">
                        <label>Background Color</label>
                        <input type="color" value="${investmentTierData.backgroundColor || '#1a1a1a'}" onchange="updateInvestmentTierField(this.value, 'backgroundColor')">
                    </div>
                    <div class="form-group" id="backgroundImage-group" style="display: ${investmentTierData.backgroundType === 'image' ? 'block' : 'none'};">
                        <label>Background Image</label>
                        <div style="border: 2px dashed #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; text-align: center; background-color: #f9f9f9;">
                            <input type="file" accept="image/*" onchange="uploadInvestmentTierImage(this)" style="margin-bottom: 8px; width: 100%;">
                            <small style="display: block; color: #666; font-size: 12px;">Upload an image file (JPEG, PNG, GIF, SVG, WebP - Max: 2MB)</small>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 0.9em; color: #666; margin-bottom: 5px; display: block;">Or enter image URL:</label>
                            <input type="text" value="${investmentTierData.backgroundImage || ''}" oninput="updateInvestmentTierField(this.value, 'backgroundImage'); updateInvestmentTierBackgroundImagePreview(this.value);" placeholder="https://example.com/image.jpg" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div id="backgroundImagePreview" style="margin-top: 10px; text-align: center;"></div>
                    </div>
                    <div class="form-group">
                        <label>Text Color</label>
                        <input type="color" value="${'${investmentTierData.textColor || \'#ffffff\'}'}" onchange="updateInvestmentTierField(this.value, 'textColor')">
                    </div>
                    <div class="form-group">
                        <label>Button Background Color</label>
                        <input type="color" value="${'${investmentTierData.buttonBgColor || \'#28a745\'}'}" onchange="updateInvestmentTierField(this.value, 'buttonBgColor')">
                    </div>
                    <div class="form-group">
                        <label>Button Text Color</label>
                        <input type="color" value="${investmentTierData.buttonTextColor || '#ffffff'}" onchange="updateInvestmentTierField(this.value, 'buttonTextColor')">
                    </div>
                    <div class="form-group">
                        <label>Padding</label>
                        <input type="text" value="${investmentTierData.padding || '2rem'}" oninput="updateInvestmentTierField(this.value, 'padding')">
                    </div>
                `;
            break;

            case 'invest-cta':
                const investCtaData = content._investCtaData || {
                    buttonText: 'INVEST NOW',
                    buttonUrl: '#',
                    buttonTarget: '_self',
                    leftValue: '$2.13',
                    leftLabel: 'Share Price',
                    rightValue: '$1001.10',
                    rightLabel: 'Min. Investment',
                    buttonBgColor: '#2e7d3e',
                    buttonTextColor: '#ffffff',
                    valueColor: '#333333',
                    labelColor: '#666666',
                    dividerColor: '#e0e0e0',
                    bgColor: '#f8f9fa'
                };
                specificControls = `
                    <div class="form-group">
                        <label>Background Color</label>
                        <input type="color" value="${investCtaData.bgColor}" oninput="updateInvestCtaField(this.value, 'bgColor')">
                    </div>
                    <div class="form-group">
                        <label>Button Text</label>
                        <input type="text" value="${investCtaData.buttonText}" oninput="updateInvestCtaField(this.value, 'buttonText')">
                    </div>
                    <div class="form-group">
                        <label>Button URL</label>
                        <input type="text" value="${investCtaData.buttonUrl}" oninput="updateInvestCtaField(this.value, 'buttonUrl')">
                    </div>
                    <div class="form-group">
                        <label>Button Target</label>
                        <select oninput="updateInvestCtaField(this.value, 'buttonTarget')">
                            <option value="_self" ${investCtaData.buttonTarget === '_self' ? 'selected' : ''}>Same Window</option>
                            <option value="_blank" ${investCtaData.buttonTarget === '_blank' ? 'selected' : ''}>New Window</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Left Value</label>
                        <input type="text" value="${investCtaData.leftValue}" oninput="updateInvestCtaField(this.value, 'leftValue')">
                    </div>
                    <div class="form-group">
                        <label>Left Label</label>
                        <input type="text" value="${investCtaData.leftLabel}" oninput="updateInvestCtaField(this.value, 'leftLabel')">
                    </div>
                    <div class="form-group">
                        <label>Right Value</label>
                        <input type="text" value="${investCtaData.rightValue}" oninput="updateInvestCtaField(this.value, 'rightValue')">
                    </div>
                    <div class="form-group">
                        <label>Right Label</label>
                        <input type="text" value="${investCtaData.rightLabel}" oninput="updateInvestCtaField(this.value, 'rightLabel')">
                    </div>
                    <div class="form-group">
                        <label>Button Background Color</label>
                        <input type="color" value="${investCtaData.buttonBgColor}" oninput="updateInvestCtaField(this.value, 'buttonBgColor')">
                    </div>
                    <div class="form-group">
                        <label>Button Text Color</label>
                        <input type="color" value="${investCtaData.buttonTextColor}" oninput="updateInvestCtaField(this.value, 'buttonTextColor')">
                    </div>
                    <div class="form-group">
                        <label>Value Text Color</label>
                        <input type="color" value="${investCtaData.valueColor}" oninput="updateInvestCtaField(this.value, 'valueColor')">
                    </div>
                    <div class="form-group">
                        <label>Label Text Color</label>
                        <input type="color" value="${investCtaData.labelColor}" oninput="updateInvestCtaField(this.value, 'labelColor')">
                    </div>
                    <div class="form-group">
                        <label>Divider Color</label>
                        <input type="color" value="${investCtaData.dividerColor}" oninput="updateInvestCtaField(this.value, 'dividerColor')">
                    </div>
                `;
            break;
        }

        // Common styling controls - skip background color for invest-cta as it has its own
        const commonControls = type === 'invest-cta' ? '' : `
            <div class="form-group">
            <label>Font Size</label>
            <input type="text" value="${content.style.fontSize || '16px'}" oninput="updateStyle(this, 'fontSize')">
            </div>
            <div class="form-group">
            <label>Color</label>
            <input type="color" value="${rgbToHex(content.style.color || '#000000')}" oninput="updateStyle(this, 'color')">
            </div>`;
        
        propertyControls.innerHTML = `
            <div class="component-header mb-3" style="border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
                <h5 class="mb-0" style="color: #495057; font-weight: 600;">
                    <i class="fas ${componentInfo.icon} me-2" style="color: #007bff;"></i>
                    ${componentInfo.name}
                </h5>
            </div>
            ${specificControls}
            ${commonControls}
            <!-- Responsive Margin Controls -->
            <div class="responsive-spacing-group">
                <h6 class="spacing-header">
                    <i class="fas fa-arrows-alt"></i> Responsive Margin
                </h6>
                <div class="device-tabs">
                    <button type="button" class="device-tab active" data-device="desktop" onclick="switchSpacingDevice(this, 'margin')">
                        <i class="fas fa-desktop"></i> Desktop
                    </button>
                    <button type="button" class="device-tab" data-device="tablet" onclick="switchSpacingDevice(this, 'margin')">
                        <i class="fas fa-tablet-alt"></i> Tablet
                    </button>
                    <button type="button" class="device-tab" data-device="mobile" onclick="switchSpacingDevice(this, 'margin')">
                        <i class="fas fa-mobile-alt"></i> Mobile
                    </button>
                </div>
                <div class="spacing-controls margin-controls">
                    <div class="device-content active" data-device="desktop">
                        <div class="spacing-grid">
                            <div class="spacing-item">
                                <label>Top</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-top', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-top', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Right</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-right', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-right', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Bottom</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-bottom', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-bottom', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Left</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-left', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-left', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                        </div>
                    </div>
                    <div class="device-content" data-device="tablet">
                        <div class="spacing-grid">
                            <div class="spacing-item">
                                <label>Top</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-top', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-top', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Right</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-right', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-right', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Bottom</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-bottom', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-bottom', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Left</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-left', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-left', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                        </div>
                    </div>
                    <div class="device-content" data-device="mobile">
                        <div class="spacing-grid">
                            <div class="spacing-item">
                                <label>Top</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-top', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-top', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Right</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-right', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-right', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Bottom</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-bottom', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-bottom', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Left</label>
                                <input type="text" value="${getResponsiveStyle(content, 'margin-left', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'margin-left', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Responsive Padding Controls -->
            <div class="responsive-spacing-group">
                <h6 class="spacing-header">
                    <i class="fas fa-expand-arrows-alt"></i> Responsive Padding
                </h6>
                <div class="device-tabs">
                    <button type="button" class="device-tab active" data-device="desktop" onclick="switchSpacingDevice(this, 'padding')">
                        <i class="fas fa-desktop"></i> Desktop
                    </button>
                    <button type="button" class="device-tab" data-device="tablet" onclick="switchSpacingDevice(this, 'padding')">
                        <i class="fas fa-tablet-alt"></i> Tablet
                    </button>
                    <button type="button" class="device-tab" data-device="mobile" onclick="switchSpacingDevice(this, 'padding')">
                        <i class="fas fa-mobile-alt"></i> Mobile
                    </button>
                </div>
                <div class="spacing-controls padding-controls">
                    <div class="device-content active" data-device="desktop">
                        <div class="spacing-grid">
                            <div class="spacing-item">
                                <label>Top</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-top', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-top', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Right</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-right', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-right', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Bottom</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-bottom', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-bottom', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Left</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-left', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-left', 'desktop')" 
                                       placeholder="e.g. 10px">
                            </div>
                        </div>
                    </div>
                    <div class="device-content" data-device="tablet">
                        <div class="spacing-grid">
                            <div class="spacing-item">
                                <label>Top</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-top', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-top', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Right</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-right', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-right', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Bottom</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-bottom', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-bottom', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Left</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-left', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-left', 'tablet')" 
                                       placeholder="e.g. 10px">
                            </div>
                        </div>
                    </div>
                    <div class="device-content" data-device="mobile">
                        <div class="spacing-grid">
                            <div class="spacing-item">
                                <label>Top</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-top', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-top', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Right</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-right', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-right', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Bottom</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-bottom', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-bottom', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                            <div class="spacing-item">
                                <label>Left</label>
                                <input type="text" value="${getResponsiveStyle(content, 'padding-left', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'padding-left', 'mobile')" 
                                       placeholder="e.g. 10px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Responsive Border Controls -->
            <div class="responsive-spacing-group">
                <h6 class="spacing-header">
                    <i class="fas fa-border-style"></i> Responsive Border
                </h6>
                <div class="device-tabs">
                    <button type="button" class="device-tab active" data-device="desktop" onclick="switchSpacingDevice(this, 'border')">
                        <i class="fas fa-desktop"></i> Desktop
                    </button>
                    <button type="button" class="device-tab" data-device="tablet" onclick="switchSpacingDevice(this, 'border')">
                        <i class="fas fa-tablet-alt"></i> Tablet
                    </button>
                    <button type="button" class="device-tab" data-device="mobile" onclick="switchSpacingDevice(this, 'border')">
                        <i class="fas fa-mobile-alt"></i> Mobile
                    </button>
                </div>
                <div class="spacing-controls border-controls">
                    <div class="device-content active" data-device="desktop">
                        <div class="border-grid">
                            <div class="border-item">
                                <label>Width</label>
                                <input type="text" value="${getResponsiveStyle(content, 'border-width', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'border-width', 'desktop')" 
                                       placeholder="e.g. 1px">
                            </div>
                            <div class="border-item">
                                <label>Style</label>
                                <select onchange="updateResponsiveStyle(this, 'border-style', 'desktop')">
                                    <option value="">None</option>
                                    <option value="solid" ${getResponsiveStyle(content, 'border-style', 'desktop') === 'solid' ? 'selected' : ''}>Solid</option>
                                    <option value="dashed" ${getResponsiveStyle(content, 'border-style', 'desktop') === 'dashed' ? 'selected' : ''}>Dashed</option>
                                    <option value="dotted" ${getResponsiveStyle(content, 'border-style', 'desktop') === 'dotted' ? 'selected' : ''}>Dotted</option>
                                    <option value="double" ${getResponsiveStyle(content, 'border-style', 'desktop') === 'double' ? 'selected' : ''}>Double</option>
                                </select>
                            </div>
                            <div class="border-item">
                                <label>Color</label>
                                <input type="color" value="${getResponsiveStyle(content, 'border-color', 'desktop') || '#000000'}" 
                                       oninput="updateResponsiveStyle(this, 'border-color', 'desktop')">
                            </div>
                            <div class="border-item">
                                <label>Radius</label>
                                <input type="text" value="${getResponsiveStyle(content, 'border-radius', 'desktop') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'border-radius', 'desktop')" 
                                       placeholder="e.g. 5px">
                            </div>
                        </div>
                    </div>
                    <div class="device-content" data-device="tablet">
                        <div class="border-grid">
                            <div class="border-item">
                                <label>Width</label>
                                <input type="text" value="${getResponsiveStyle(content, 'border-width', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'border-width', 'tablet')" 
                                       placeholder="e.g. 1px">
                            </div>
                            <div class="border-item">
                                <label>Style</label>
                                <select onchange="updateResponsiveStyle(this, 'border-style', 'tablet')">
                                    <option value="">None</option>
                                    <option value="solid" ${getResponsiveStyle(content, 'border-style', 'tablet') === 'solid' ? 'selected' : ''}>Solid</option>
                                    <option value="dashed" ${getResponsiveStyle(content, 'border-style', 'tablet') === 'dashed' ? 'selected' : ''}>Dashed</option>
                                    <option value="dotted" ${getResponsiveStyle(content, 'border-style', 'tablet') === 'dotted' ? 'selected' : ''}>Dotted</option>
                                    <option value="double" ${getResponsiveStyle(content, 'border-style', 'tablet') === 'double' ? 'selected' : ''}>Double</option>
                                </select>
                            </div>
                            <div class="border-item">
                                <label>Color</label>
                                <input type="color" value="${getResponsiveStyle(content, 'border-color', 'tablet') || '#000000'}" 
                                       oninput="updateResponsiveStyle(this, 'border-color', 'tablet')">
                            </div>
                            <div class="border-item">
                                <label>Radius</label>
                                <input type="text" value="${getResponsiveStyle(content, 'border-radius', 'tablet') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'border-radius', 'tablet')" 
                                       placeholder="e.g. 5px">
                            </div>
                        </div>
                    </div>
                    <div class="device-content" data-device="mobile">
                        <div class="border-grid">
                            <div class="border-item">
                                <label>Width</label>
                                <input type="text" value="${getResponsiveStyle(content, 'border-width', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'border-width', 'mobile')" 
                                       placeholder="e.g. 1px">
                            </div>
                            <div class="border-item">
                                <label>Style</label>
                                <select onchange="updateResponsiveStyle(this, 'border-style', 'mobile')">
                                    <option value="">None</option>
                                    <option value="solid" ${getResponsiveStyle(content, 'border-style', 'mobile') === 'solid' ? 'selected' : ''}>Solid</option>
                                    <option value="dashed" ${getResponsiveStyle(content, 'border-style', 'mobile') === 'dashed' ? 'selected' : ''}>Dashed</option>
                                    <option value="dotted" ${getResponsiveStyle(content, 'border-style', 'mobile') === 'dotted' ? 'selected' : ''}>Dotted</option>
                                    <option value="double" ${getResponsiveStyle(content, 'border-style', 'mobile') === 'double' ? 'selected' : ''}>Double</option>
                                </select>
                            </div>
                            <div class="border-item">
                                <label>Color</label>
                                <input type="color" value="${getResponsiveStyle(content, 'border-color', 'mobile') || '#000000'}" 
                                       oninput="updateResponsiveStyle(this, 'border-color', 'mobile')">
                            </div>
                            <div class="border-item">
                                <label>Radius</label>
                                <input type="text" value="${getResponsiveStyle(content, 'border-radius', 'mobile') || ''}" 
                                       oninput="updateResponsiveStyle(this, 'border-radius', 'mobile')" 
                                       placeholder="e.g. 5px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
            <label>Text Align</label>
            <select oninput="updateStyle(this, 'textAlign')">
                <option value="left" ${content.style.textAlign === 'left' ? 'selected' : ''}>Left</option>
                <option value="center" ${content.style.textAlign === 'center' ? 'selected' : ''}>Center</option>
                <option value="right" ${content.style.textAlign === 'right' ? 'selected' : ''}>Right</option>
            </select>
            </div>
        `;

        // After DOM update, initialize background image preview and CKEditor if needed
        if (typeof updateInvestmentTierBackgroundImagePreview === 'function') {
            var bgInput = propertyControls.querySelector('input[oninput*="updateInvestmentTierField"][oninput*="backgroundImage"]');
            if (bgInput) {
                updateInvestmentTierBackgroundImagePreview(bgInput.value);
                bgInput.addEventListener('input', function() {
                    updateInvestmentTierBackgroundImagePreview(this.value);
                });
            }
        }
        if (typeof initInlineCKEditors === 'function') {
            setTimeout(initInlineCKEditors, 0);
        }
// Global function to initialize CKEditor for all inline editors
// CKEditor 4 inline editor initialization removed. CKEditor 5 is now used directly where needed.
            

        if (selectedComponent && selectedComponent.dataset.type === 'faq') {
            const numSelect = document.getElementById('faq_number_of_questions');
            if (numSelect) {
                numSelect.addEventListener('change', function() {
                    renderFaqEntries(this.value);
                });
                renderFaqEntries(numSelect.value || 1);
            }
        }
        
        // Update responsive CSS after property panel is updated
        updateResponsiveCSS();
    }

    // Global function to update background image preview for investment tier
    function updateInvestmentTierBackgroundImagePreview(imageUrl) {
        var previewDiv = document.getElementById('backgroundImagePreview');
        if (!previewDiv) return;
        if (imageUrl && imageUrl !== 'undefined') {
            previewDiv.innerHTML = '<img src="' + imageUrl + '" style="max-width: 120px; max-height: 80px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd;" alt="Preview"><br><small style="color: #666; margin-top: 5px; display: block;">Current background image</small>';
        } else {
            previewDiv.innerHTML = '';
        }
    }

// helper function for sell tickets

function updateSellTicketsField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._sellTicketsData) return;
    content._sellTicketsData[field] = value;
    if (typeof content.renderSellTickets === 'function') content.renderSellTickets();
    updatePropertyPanel();
}




    // helper function for custom banner

function uploadCustomBannerImage(event) {
    if (!selectedComponent) return;
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const content = getContentElement(selectedComponent);
        if (!content._customBannerData) return;
        content._customBannerData.imgSrc = e.target.result;
        if (typeof content.renderCustomBanner === 'function') content.renderCustomBanner();
    };
    reader.readAsDataURL(file);
}

function updateCustomBannerField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._customBannerData) return;
    // Font size: ensure px/em if not present
    if (field === 'titleFontSize' || field === 'subtitleFontSize') {
        content._customBannerData[field] = value.match(/(px|em|rem|%)$/) ? value : value + 'px';
    } else {
        content._customBannerData[field] = value;
    }
    if (typeof content.renderCustomBanner === 'function') content.renderCustomBanner();
}


//  helper function for mergin

function updateStyleWithUnit(input, property) {
    if (!selectedComponent) return;
    // Always use px for margin fields
    selectedComponent.style[property] = input.value + 'px';
    // Update the input to reflect the actual value (removes any non-numeric input)
    setTimeout(() => {
        input.value = parseInt(selectedComponent.style[property]) || 0;
    }, 0);
}


// slider IMAGE UPLOAD & COLUMN UPDATE HELPERS

function uploadSliderImages(event) {
    if (!selectedComponent) return;
    const files = Array.from(event.target.files);
    if (!files.length) return;
    const content = getContentElement(selectedComponent);
    if (!content._sliderData) return;
    let loaded = 0;
    files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            content._sliderData.images.push(e.target.result);
            loaded++;
            if (loaded === files.length) {
                content.renderSlider();
                updatePropertyPanel();
            }
        };
        reader.readAsDataURL(file);
    });
}

function updateSliderSlidesToShow(val) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._sliderData) return;
    // Update the slidesToShow value in the slider data
    content._sliderData.slidesToShow = Math.max(1, Math.min(10, parseInt(val, 10) || 1));
    content._sliderStartIdx = 0; // Reset to first slide
    // Re-render the slider with the new slidesToShow value
    content.renderSlider();
    updatePropertyPanel();
}

function updateSliderSlideSpeed(val) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._sliderData) return;
    content._sliderData.slideSpeed = Math.max(500, Math.min(10000, parseInt(val, 10) || 2000));
    content.renderSlider();
    updatePropertyPanel();
}

function openSliderModalFromPanel(idx) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._sliderData) return;
    sliderModalComponent = content;
    sliderModalImages = content._sliderData.images;
    sliderModalIndex = idx;
    showSliderModalImage();
    document.getElementById('galleryLargeModal').style.display = 'flex';
}








let sliderModalImages = [];
let sliderModalIndex = 0;
let sliderModalComponent = null;

function openSliderModal(imgElem) {
    let comp = imgElem.closest('.component');
    if (!comp) return;
    const content = getContentElement(comp);
    if (!content._sliderData) return;
    sliderModalComponent = content;
    sliderModalImages = content._sliderData.images;
    sliderModalIndex = parseInt(imgElem.dataset.idx, 10) || 0;
    showSliderModalImage();
    document.getElementById('galleryLargeModal').style.display = 'flex';
}

function showSliderModalImage() {
    const img = document.getElementById('galleryLargeModalImg');
    if (!sliderModalImages.length) return;
    if (sliderModalIndex < 0) sliderModalIndex = sliderModalImages.length - 1;
    if (sliderModalIndex >= sliderModalImages.length) sliderModalIndex = 0;
    img.src = sliderModalImages[sliderModalIndex];
    // Add or update delete button
    let delBtn = document.getElementById('galleryDeleteBtn');
    if (!delBtn) {
        delBtn = document.createElement('button');
        delBtn.id = 'galleryDeleteBtn';
        delBtn.textContent = 'Delete';
        delBtn.className = 'btn btn-danger';
        delBtn.style.position = 'absolute';
        delBtn.style.bottom = '20px';
        delBtn.style.right = '50%';
        delBtn.style.transform = 'translateX(50%)';
        delBtn.onclick = deleteSliderModalImage;
        document.querySelector('#galleryLargeModal .modal-content').appendChild(delBtn);
    } else {
        delBtn.style.display = 'block';
        delBtn.onclick = deleteSliderModalImage;
    }
}

function deleteSliderModalImage() {
    if (!sliderModalComponent || !sliderModalImages.length) return;
    if (!confirm('Remove this image from slider?')) return;
    sliderModalComponent._sliderData.images.splice(sliderModalIndex, 1);
    if (sliderModalComponent.renderSlider) sliderModalComponent.renderSlider();
    if (!sliderModalComponent._sliderData.images.length) {
        closeGalleryLargeModal();
        return;
    }
    if (sliderModalIndex >= sliderModalComponent._sliderData.images.length) {
        sliderModalIndex = sliderModalComponent._sliderData.images.length - 1;
    }
    sliderModalImages = sliderModalComponent._sliderData.images;
    showSliderModalImage();
}

// 3. Attach navigation for slider modal (and gallery modal) after DOM loaded
window.addEventListener('DOMContentLoaded', function() {
    // Initialize device preview functionality
    initDevicePreview();
    
    document.getElementById('galleryPrevBtn').onclick = function(e) {
        e.stopPropagation();
        if (sliderModalComponent && sliderModalImages.length) {
            sliderModalIndex--;
            showSliderModalImage();
        } else if (galleryModalComponent && galleryModalImages.length) {
            galleryModalIndex = (galleryModalIndex - 1 + galleryModalImages.length) % galleryModalImages.length;
            showGalleryModalImage();
        }
    };
    document.getElementById('galleryNextBtn').onclick = function(e) {
        e.stopPropagation();
        if (sliderModalComponent && sliderModalImages.length) {
            sliderModalIndex++;
            showSliderModalImage();
        } else if (galleryModalComponent && galleryModalImages.length) {
            galleryModalIndex = (galleryModalIndex + 1) % galleryModalImages.length;
            showGalleryModalImage();
        }
    };
});





    // GALLERY IMAGE UPLOAD & COLUMN UPDATE HELPERS

    function uploadGalleryImages(event) {
    if (!selectedComponent) return;
    const files = Array.from(event.target.files);
    if (!files.length) return;
    const content = getContentElement(selectedComponent);
    if (!content._galleryData) return;
    let loaded = 0;
    files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            content._galleryData.images.push(e.target.result);
            loaded++;
            if (loaded === files.length) {
                content.renderGallery();
                updatePropertyPanel();
            }
        };
        reader.readAsDataURL(file);
    });
}

function updateGalleryColumns(val) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._galleryData) return;
    content._galleryData.columns = Math.max(1, Math.min(6, parseInt(val, 10) || 3));
    content.renderGallery();
    updatePropertyPanel();
}

let galleryModalImages = [];
let galleryModalIndex = 0;
let galleryModalComponent = null;

function openGalleryModal(imgElem) {
    let comp = imgElem.closest('.component');
    if (!comp) return;
    const content = getContentElement(comp);
    if (!content._galleryData) return;
    galleryModalComponent = content;
    galleryModalImages = content._galleryData.images;
    galleryModalIndex = parseInt(imgElem.dataset.idx, 10) || 0;
    showGalleryModalImage();
    document.getElementById('galleryLargeModal').style.display = 'flex';
}

function openGalleryModalFromPanel(idx) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._galleryData) return;
    galleryModalComponent = content;
    galleryModalImages = content._galleryData.images;
    galleryModalIndex = idx;
    showGalleryModalImage();
    document.getElementById('galleryLargeModal').style.display = 'flex';
}

function showGalleryModalImage() {
    const img = document.getElementById('galleryLargeModalImg');
    if (!galleryModalImages.length) return;
    galleryModalIndex = (galleryModalIndex + galleryModalImages.length) % galleryModalImages.length;
    img.src = galleryModalImages[galleryModalIndex];

    let delBtn = document.getElementById('galleryDeleteBtn');
    if (!delBtn) {
        delBtn = document.createElement('button');
        delBtn.id = 'galleryDeleteBtn';
        delBtn.textContent = 'Delete';
        delBtn.className = 'btn btn-danger';
        delBtn.style.position = 'absolute';
        delBtn.style.bottom = '20px';
        delBtn.style.right = '50%';
        delBtn.style.transform = 'translateX(50%)';
        delBtn.onclick = deleteGalleryModalImage;
        document.querySelector('#galleryLargeModal .modal-content').appendChild(delBtn);
    } else {
        delBtn.style.display = 'block';
    }
}

function closeGalleryLargeModal() {
    document.getElementById('galleryLargeModal').style.display = 'none';
}

// --- FIX: Attach event listeners after DOM is loaded ---
window.addEventListener('DOMContentLoaded', function() {
    document.getElementById('galleryPrevBtn').onclick = function(e) {
        e.stopPropagation();
        if (!galleryModalImages.length) return;
        galleryModalIndex--;
        showGalleryModalImage();
    };
    document.getElementById('galleryNextBtn').onclick = function(e) {
        e.stopPropagation();
        if (!galleryModalImages.length) return;
        galleryModalIndex++;
        showGalleryModalImage();
    };
});


// document.getElementById('galleryPrevBtn').onclick = function(e) {
//     e.stopPropagation();
//     if (!galleryModalImages.length) return;
//     galleryModalIndex = (galleryModalIndex - 1 + galleryModalImages.length) % galleryModalImages.length;
//     showGalleryModalImage();
// };
// document.getElementById('galleryNextBtn').onclick = function(e) {
//     e.stopPropagation();
//     if (!galleryModalImages.length) return;
//     galleryModalIndex = (galleryModalIndex + 1) % galleryModalImages.length;
//     showGalleryModalImage();
// };

function deleteGalleryModalImage() {
    if (!galleryModalComponent || !galleryModalImages.length) return;
    if (!confirm('Remove this image from gallery?')) return;
    galleryModalComponent._galleryData.images.splice(galleryModalIndex, 1);
    if (galleryModalComponent.renderGallery) galleryModalComponent.renderGallery();
    // If no images left, close modal
    if (!galleryModalComponent._galleryData.images.length) {
        closeGalleryLargeModal();
        return;
    }
    // Adjust index if needed
    if (galleryModalIndex >= galleryModalComponent._galleryData.images.length) {
        galleryModalIndex = galleryModalComponent._galleryData.images.length - 1;
    }
    galleryModalImages = galleryModalComponent._galleryData.images;
    showGalleryModalImage();
}



    // helper function for image component

        function openLargeImageModal(src, alt) {
    let modal = document.getElementById('largeImageModal');
    document.getElementById('largeImageModalImg').src = src;
    document.getElementById('largeImageModalAlt').textContent = alt || '';
    modal.style.display = 'flex';
}
function closeLargeImageModal() {
    const modal = document.getElementById('largeImageModal');
    if (modal) modal.style.display = 'none';
}


function uploadSingleImage(event) {
    if (!selectedComponent) return;
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const content = getContentElement(selectedComponent);
        if (!content._imageData) return;
        content._imageData.src = e.target.result;
        content.renderImage();
        updatePropertyPanel();
    };
    reader.readAsDataURL(file);
}
function updateImageField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._imageData) return;
    
    // Validate width and height values
    if (field === 'width' || field === 'height') {
        if (value === '') {
            // Allow empty values for auto sizing
            value = field === 'width' ? '100%' : 'auto';
        } else if (value && !value.includes('%') && !value.includes('px') && !value.includes('auto') && !value.includes('em') && !value.includes('rem')) {
            // If it's a number without units, add px
            if (!isNaN(parseFloat(value))) {
                value = value + 'px';
            }
        }
    }
    
    content._imageData[field] = value;
    content.renderImage();
    updatePropertyPanel();
    
    // Save throttled history for image field changes
    saveHistoryThrottled(`Image ${field} updated`);
}

// Timeline functions
function updateTimelineField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._timelineData) return;
    
    content._timelineData[field] = parseInt(value);
    content.renderTimeline();
    updatePropertyPanel();
}

function updateTimelineColor(colorField, value) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._timelineData) return;
    
    content._timelineData.colors[colorField] = value;
    content.renderTimeline();
}

function updateTimelineItem(index, field, value) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._timelineData || !content._timelineData.items) return;
    
    content._timelineData.items[index][field] = value;
    content.renderTimeline();
}

function addTimelineItem() {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._timelineData) return;
    
    const newItem = {
        number: (content._timelineData.items.length + 1).toString(),
        title: 'New Title:',
        description: 'Enter description here...'
    };
    
    content._timelineData.items.push(newItem);
    content.renderTimeline();
    updatePropertyPanel();
}

function removeTimelineItem(index) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._timelineData || !content._timelineData.items) return;
    
    content._timelineData.items.splice(index, 1);
    content.renderTimeline();
    updatePropertyPanel();
}

function updateInvestCtaField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    
    // Initialize invest CTA data if it doesn't exist
    if (!content._investCtaData) {
        content._investCtaData = {
            buttonText: 'INVEST NOW',
            buttonUrl: '/invest',
            buttonTarget: '_self',
            leftValue: '$2.13',
            leftLabel: 'Share Price',
            rightValue: '$1001.10',
            rightLabel: 'Min. Investment',
            buttonBgColor: '#2e7d3e',
            buttonTextColor: '#ffffff',
            valueColor: '#333333',
            labelColor: '#666666',
            dividerColor: '#e0e0e0'
        };
    }
    
    // Update the field
    content._investCtaData[field] = value;
    // Also update background_color property for front-end compatibility
    if (field === 'bgColor') {
        // Find the component in the state and update its properties
        if (selectedComponent && selectedComponent.dataset && selectedComponent.dataset.index) {
            const idx = parseInt(selectedComponent.dataset.index);
            if (!isNaN(idx) && window.state && window.state[idx]) {
                if (!window.state[idx].properties) window.state[idx].properties = {};
                window.state[idx].properties['background_color'] = value;
            }
        }
    }
    
    // Re-render the component
    const wrapper = content.querySelector('.invest-cta-wrapper');
    if (wrapper) {
        const d = content._investCtaData;
        wrapper.style.backgroundColor = d.bgColor;
        wrapper.innerHTML = `
            <div class="invest-cta-button-wrap">
                <a href="${d.buttonUrl}" 
                   target="${d.buttonTarget}" 
                   class="invest-cta-button"
                   style="display: inline-block; background-color: ${d.buttonBgColor}; color: ${d.buttonTextColor}; text-decoration: none; padding: 15px 30px; border-radius: 4px; font-size: 14px; font-weight: 600; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; border: none; cursor: pointer; white-space: nowrap; flex-shrink: 0;"
                   aria-label="${d.buttonText}">
                    ${d.buttonText}
                </a>
            </div>
            
            <div class="investment-info-wrapper" style="display: flex; align-items: center; justify-content: center; gap: 20px; flex: 1;">
                <div class="investment-info-item" style="text-align: center; flex: 1;">
                    <div class="investment-value" style="color: ${d.valueColor}; font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 5px;">${d.leftValue}</div>
                    <div class="investment-label" style="color: ${d.labelColor}; font-size: 14px; font-weight: 400; line-height: 1.2;">${d.leftLabel}</div>
                </div>
                
                <div class="investment-divider" style="width: 1px; height: 40px; background-color: ${d.dividerColor}; flex-shrink: 0;"></div>
                
                <div class="investment-info-item" style="text-align: center; flex: 1;">
                    <div class="investment-value" style="color: ${d.valueColor}; font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 5px;">${d.rightValue}</div>
                    <div class="investment-label" style="color: ${d.labelColor}; font-size: 14px; font-weight: 400; line-height: 1.2;">${d.rightLabel}</div>
                </div>
            </div>
        `;
    }
}

function uploadInnerSectionBackgroundImage(event) {
    if (!selectedComponent) return;
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const content = getContentElement(selectedComponent);
        if (!content._innerSectionData) return;
        content._innerSectionData.backgroundImage = e.target.result;
        if (content.updateBackground) {
            content.updateBackground();
        }
        updatePropertyPanel();
    };
    reader.readAsDataURL(file);
}

function updateInnerSectionField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._innerSectionData) return;
    
    content._innerSectionData[field] = value;
    
    // Apply the style changes to the component
    switch (field) {
        case 'backgroundColor':
            content.style.backgroundColor = value;
            break;
        case 'padding':
            content.style.padding = value;
            break;
        case 'margin':
            content.style.margin = value;
            break;
        case 'fullWidth':
            // Full width changes require re-rendering the inner section
            if (content.renderInnerSection) {
                content.renderInnerSection();
            }
            // Show/hide content width option
            const contentWidthGroup = document.getElementById('contentWidthGroup');
            if (contentWidthGroup) {
                contentWidthGroup.style.display = value ? 'block' : 'none';
            }
            break;
        case 'contentWidth':
            // Content width changes require re-rendering
            if (content.renderInnerSection) {
                content.renderInnerSection();
            }
            break;
        case 'backgroundType':
        case 'backgroundImage':
        case 'backgroundAttachment':
            // Update background using the updateBackground function
            if (content.updateBackground) {
                content.updateBackground();
            }
            break;
        case 'addToMenu':
            // Handle menu addition with visual feedback
            updateSectionVisualIndicator(content, value);
            break;
        case 'menuTitle':
            // Auto-generate section ID when menu title changes
            if (value && value.trim()) {
                updateSectionId(value.trim());
            }
            break;
    }
}

function toggleInnerSectionBackgroundType(type) {
    const colorGroup = document.getElementById('innerSectionBackgroundColor');
    const imageGroup = document.getElementById('innerSectionBackgroundImageSettings');
    
    if (type === 'color') {
        if (colorGroup) colorGroup.style.display = 'block';
        if (imageGroup) imageGroup.style.display = 'none';
    } else if (type === 'image') {
        if (colorGroup) colorGroup.style.display = 'none';
        if (imageGroup) imageGroup.style.display = 'block';
    }
}

function updateInnerSectionColumns(numColumns) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content.updateColumns) return;
    
    content.updateColumns(parseInt(numColumns));
    
    // Re-initialize SortableJS for all columns after structure change
    setTimeout(() => {
        const columns = content.querySelectorAll('.inner-column');
        columns.forEach(column => {
            // Remove old sortable instance if exists
            if (column._sortableInstance) {
                column._sortableInstance.destroy();
                column.removeAttribute('data-sortable-initialized');
            }
            initializeColumnSortable(column);
        });
        console.log('SortableJS re-initialized after column update');
    }, 100);
    
    updatePropertyPanel();
}

function updateInnerSectionGap(gap) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content.updateGap) return;
    
    content.updateGap(gap);
    content._innerSectionData.gap = gap;
}

// Menu section functionality for investment websites
function updateMenuSection(addToMenu) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._innerSectionData) return;
    
    content._innerSectionData.addToMenu = addToMenu;
    
    // Show/hide menu title and section ID fields
    const menuTitleGroup = document.getElementById('menuTitleGroup');
    const sectionIdGroup = document.getElementById('sectionIdGroup');
    
    if (menuTitleGroup) menuTitleGroup.style.display = addToMenu ? 'block' : 'none';
    if (sectionIdGroup) sectionIdGroup.style.display = addToMenu ? 'block' : 'none';
    
    // If adding to menu and no title set, focus on the title input
    if (addToMenu && !content._innerSectionData.menuTitle) {
        setTimeout(() => {
            const titleInput = menuTitleGroup?.querySelector('input[type="text"]');
            if (titleInput) titleInput.focus();
        }, 100);
    }
    
    // Update section ID when adding to menu
    if (addToMenu && content._innerSectionData.menuTitle) {
        updateSectionId(content._innerSectionData.menuTitle);
    }
    
    // Add visual indicator to the section
    updateSectionVisualIndicator(content, addToMenu);
}

function updateSectionId(menuTitle) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._innerSectionData) return;
    
    // Generate section ID from menu title
    const sectionId = menuTitle.toLowerCase()
        .replace(/[^a-z0-9\s]/g, '') // Remove special characters
        .replace(/\s+/g, '-')        // Replace spaces with hyphens
        .replace(/^-+|-+$/g, '');    // Remove leading/trailing hyphens
    
    content._innerSectionData.sectionId = sectionId;
    
    // Update the readonly input field
    const sectionIdInput = document.querySelector('#sectionIdGroup input[readonly]');
    if (sectionIdInput) {
        sectionIdInput.value = sectionId;
    }
    
    // Update the content element's ID for navigation
    if (sectionId) {
        content.setAttribute('data-section-id', sectionId);
    }
}

function updateSectionVisualIndicator(content, addToMenu) {
    // Remove existing indicator
    const existingIndicator = content.querySelector('.menu-section-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Add indicator if this section is in menu
    if (addToMenu) {
        const indicator = document.createElement('div');
        indicator.className = 'menu-section-indicator';
        indicator.innerHTML = '<i class="fas fa-bars"></i> Menu Section';
        indicator.style.cssText = `
            position: absolute;
            top: 5px;
            right: 10px;
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            z-index: 15;
            pointer-events: none;
        `;
        content.appendChild(indicator);
    }
}

    // helper function for full width text and image


    function updateFWTIField(value, field) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    if (!content._fwtiData) return;
    if (field === 'fontSize1' || field === 'fontSize2') {
        content._fwtiData[field] = value.endsWith('px') || value.endsWith('em') || value.endsWith('%') ? value : value + 'px';
    } else {
        content._fwtiData[field] = value;
    }
    if (typeof content.renderFWTI === 'function') content.renderFWTI();
}

function updatePressCardField(field, value) {
    console.log('=== updatePressCardField called ===');
    console.log('Field:', field, 'Value:', value);
    console.log('Selected component:', selectedComponent);
    
    if (!selectedComponent) {
        console.log('No selected component');
        return;
    }
    
    const content = getContentElement(selectedComponent);
    console.log('Content element:', content);
    
    if (!content._pressCardData) {
        console.log('No _pressCardData found');
        return;
    }
    
    console.log('Current _pressCardData before update:', content._pressCardData);
    content._pressCardData[field] = value;
    console.log('Updated _pressCardData:', content._pressCardData);
    
    if (typeof content.renderPressCard === 'function') {
        console.log('Calling renderPressCard function');
        content.renderPressCard();
    } else {
        console.log('renderPressCard function not found');
    }
    
    // Auto-save the page data to ensure changes persist
    // setTimeout(() => {
    //     console.log('Auto-saving page data after press card field change');
    //     saveBuilderState();
    // }, 1000);
    
    console.log('=== END updatePressCardField ===');
}

function uploadPressCardImage(event) {
    if (!selectedComponent) return;
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        updatePressCardField('logoSrc', e.target.result);
    };
    reader.readAsDataURL(file);
}

// Debug helper function for press card
window.debugPressCard = function() {
    if (selectedComponent) {
        console.log('=== DEBUG PRESS CARD ===');
        console.log('Selected component:', selectedComponent);
        console.log('Selected component type:', selectedComponent.dataset.type);
        
        const content = getContentElement(selectedComponent);
        console.log('Content element:', content);
        console.log('Content _pressCardData:', content ? content._pressCardData : 'no content');
        console.log('renderPressCard function exists:', content && typeof content.renderPressCard === 'function');
        console.log('=== END DEBUG ===');
    } else {
        console.log('No component selected');
    }
};

// Test serialization function
window.testPressCardSave = function() {
    console.log('=== TEST PRESS CARD SAVE ===');
    const state = serializeBuilder();
    console.log('Full serialized state:', state);
    
    // Look for press cards in the data
    if (state.components) {
        state.components.forEach((comp, idx) => {
            console.log(`Component ${idx}: type=${comp.type}`);
            if (comp.type === 'press-card') {
                console.log(`  Press card data:`, comp.pressCardData);
            }
            if (comp.nestedComponents) {
                comp.nestedComponents.forEach((column, colIdx) => {
                    if (Array.isArray(column)) {
                        column.forEach((nested, nestedIdx) => {
                            if (nested.type === 'press-card') {
                                console.log(`  Nested press card in column ${colIdx}, item ${nestedIdx}:`, nested.pressCardData);
                            }
                        });
                    }
                });
            }
        });
    }
    console.log('=== END TEST ===');
    return state;
};

function uploadFWTIImage(event) {
    if (!selectedComponent) return;
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const content = getContentElement(selectedComponent);
        if (!content._fwtiData) return;
        content._fwtiData.imgSrc = e.target.result;
        if (typeof content.renderFWTI === 'function') content.renderFWTI();
    };
    reader.readAsDataURL(file);
}






    // // ...add this function outside updatePropertyPanel:
    // function updateSliderSlidesToShow(input) {
    //     if (!selectedComponent) return;
    //     const content = getContentElement(selectedComponent);
    //     content.dataset.slidesToShow = input.value;
    //     // Re-render the slider preview if images are present
    //     // (You may want to store selected images in content._sliderImages or similar)
    //     // For now, just trigger selectImagesForComponent if needed
    //     if (typeof selectImagesForComponent === 'function') {
    //         selectImagesForComponent();
    //     }
    // }

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
//     function updateStyle(input, property) {
//     if (!selectedComponent) return;
//     const content = getContentElement(selectedComponent);

//     // Update the style on the content element
//     content.style[property] = input.value;

//     // --- FIX: Also update the style on the parent .component for margin properties ---
//     // This ensures margin changes are saved and restored correctly
//     if (
//         property === 'marginLeft' ||
//         property === 'marginRight' ||
//         property === 'marginTop' ||
//         property === 'marginBottom' ||
//         property === 'margin'
//     ) {
//         // Set the margin on the .component wrapper, not just the content
//         selectedComponent.style[property] = input.value;
//     }
// }

    function updateStyle(input, property) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);

    // Margin properties: set on wrapper
    if (
        property === 'marginLeft' ||
        property === 'marginRight' ||
        property === 'marginTop' ||
        property === 'marginBottom' ||
        property === 'margin'
    ) {
        selectedComponent.style[property] = input.value;
    }
    // Text align: set on wrapper AND content for best compatibility
    else if (property === 'textAlign') {
        selectedComponent.style.textAlign = input.value;
        content.style.textAlign = input.value;
    }
    // All other styles: set on content
    else {
        content.style[property] = input.value;
    }

    // Save throttled history for style changes
    saveHistoryThrottled(`Style changed: ${property}`);

    // Update the property panel input to reflect the actual applied margin
    setTimeout(() => {
        if (
            property === 'marginLeft' ||
            property === 'marginRight' ||
            property === 'marginTop' ||
            property === 'marginBottom' ||
            property === 'margin'
        ) {
            const propertyControls = document.getElementById('propertyControls');
            if (propertyControls) {
                const inputEl = propertyControls.querySelector(`input[oninput*="updateStyle"][oninput*="'${property}'"]`);
                if (inputEl) {
                    inputEl.value = selectedComponent.style[property] || '';
                }
            }
        }
    }, 0);
}

// Responsive Spacing Functions
function getResponsiveStyle(element, property, device) {
    if (!element || !element._responsiveStyles) {
        return '';
    }
    
    console.log(`GetResponsiveStyle debug - device: ${device}, property: ${property}`);
    console.log(`  element._responsiveStyles:`, element._responsiveStyles);
    console.log(`  element._responsiveStyles[${device}]:`, element._responsiveStyles[device]);
    console.log(`  Type of element._responsiveStyles[${device}]:`, typeof element._responsiveStyles[device]);
    console.log(`  Is array:`, Array.isArray(element._responsiveStyles[device]));
    
    // Fix: Ensure device property is an object, not an array
    if (Array.isArray(element._responsiveStyles[device])) {
        console.log(`  Converting ${device} from array to object`);
        element._responsiveStyles[device] = {};
    }
    
    const value = element._responsiveStyles[device] ? element._responsiveStyles[device][property] || '' : '';
    console.log(`Getting responsive style: ${device}.${property} = "${value}"`);
    return value;
}

function updateResponsiveStyle(input, property, device) {
    if (!selectedComponent) return;
    const content = getContentElement(selectedComponent);
    
    // Initialize responsive styles object if it doesn't exist
    if (!content._responsiveStyles) {
        content._responsiveStyles = {
            desktop: {},
            tablet: {},
            mobile: {}
        };
    }
    
    // Ensure each device property is an object, not an array
    if (!content._responsiveStyles[device] || Array.isArray(content._responsiveStyles[device])) {
        console.log(`Fixing ${device} structure - was:`, content._responsiveStyles[device]);
        content._responsiveStyles[device] = {};
    }
    
    console.log(`UpdateResponsiveStyle: ${device}.${property} = "${input.value}"`);
    
    // Store the responsive style (only if not empty)
    if (input.value && input.value.trim() !== '') {
        content._responsiveStyles[device][property] = input.value.trim();
    } else {
        // Remove the property if value is empty
        delete content._responsiveStyles[device][property];
    }
    
    // Apply the style based on current preview mode
    const currentDevice = getCurrentPreviewDevice();
    if (currentDevice === device) {
        // Apply margin properties to wrapper, padding to content
        if (property.startsWith('margin-')) {
            if (input.value && input.value.trim() !== '') {
                selectedComponent.style[property] = input.value.trim();
            } else {
                selectedComponent.style[property] = '';
            }
        } else if (property.startsWith('padding-')) {
            if (input.value && input.value.trim() !== '') {
                content.style[property] = input.value.trim();
            } else {
                content.style[property] = '';
            }
        }
    }
    
    // Update responsive CSS
    updateResponsiveCSS();
    
    // Auto-save removed - manual save only
}

function switchSpacingDevice(button, spacingType) {
    const group = button.closest('.responsive-spacing-group');
    const tabs = group.querySelectorAll('.device-tab');
    const contents = group.querySelectorAll('.device-content');
    const device = button.dataset.device;
    
    // Update tab states
    tabs.forEach(tab => tab.classList.remove('active'));
    button.classList.add('active');
    
    // Update content visibility
    contents.forEach(content => {
        content.classList.remove('active');
        if (content.dataset.device === device) {
            content.classList.add('active');
        }
    });
    
    // Update input values for the selected device
    if (selectedComponent) {
        const content = getContentElement(selectedComponent);
        const activeContent = group.querySelector(`.device-content[data-device="${device}"]`);
        
        if (activeContent && content) {
            const inputs = activeContent.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                const oninputAttr = input.getAttribute('oninput');
                if (oninputAttr) {
                    // Extract property name from oninput attribute
                    const match = oninputAttr.match(/updateResponsiveStyle\(this,\s*'([^']+)'/);
                    if (match) {
                        const property = match[1];
                        const value = getResponsiveStyle(content, property, device);
                        input.value = value;
                        console.log(`Switched to ${device}, updating ${property} input to: "${value}"`);
                    }
                }
            });
        }
    }
}

function getCurrentPreviewDevice() {
    const canvas = document.querySelector('.canvas');
    if (canvas.classList.contains('mobile-view')) return 'mobile';
    if (canvas.classList.contains('tablet-view')) return 'tablet';
    return 'desktop';
}

function updateResponsiveCSS() {
    // Remove existing responsive style element
    const existingStyle = document.getElementById('responsive-spacing-styles');
    if (existingStyle) {
        existingStyle.remove();
    }
    
    // Create new style element
    const style = document.createElement('style');
    style.id = 'responsive-spacing-styles';
    
    let css = '';
    
    // Collect all components with responsive styles
    const componentsWithStyles = document.querySelectorAll('.component');
    componentsWithStyles.forEach((component, index) => {
        const content = getContentElement(component);
        if (content && content._responsiveStyles) {
            const componentId = component.id || `component-${index}`;
            if (!component.id) {
                component.id = componentId;
            }
            
            const styles = content._responsiveStyles;
            
            // Desktop styles (default)
            if (styles.desktop && Object.keys(styles.desktop).length > 0) {
                const desktopMargins = Object.entries(styles.desktop)
                    .filter(([prop, value]) => prop.startsWith('margin-') && value && value.trim() !== '')
                    .map(([prop, value]) => `${prop}: ${value}`)
                    .join('; ');
                const desktopPaddings = Object.entries(styles.desktop)
                    .filter(([prop, value]) => prop.startsWith('padding-') && value && value.trim() !== '')
                    .map(([prop, value]) => `${prop}: ${value}`)
                    .join('; ');
                
                if (desktopMargins) {
                    css += `#${componentId} { ${desktopMargins}; }\n`;
                }
                if (desktopPaddings) {
                    // Apply padding directly to the content element, but exclude investment tier components
                    css += `#${componentId} > *:not(.component-controls):not(.perk-wrap):not(.investment-tier):not(.investment-tier-component) { ${desktopPaddings}; }\n`;
                }
            }
            
            // Tablet styles
            if (styles.tablet && Object.keys(styles.tablet).length > 0) {
                const tabletMargins = Object.entries(styles.tablet)
                    .filter(([prop, value]) => prop.startsWith('margin-') && value && value.trim() !== '')
                    .map(([prop, value]) => `${prop}: ${value} !important`)
                    .join('; ');
                const tabletPaddings = Object.entries(styles.tablet)
                    .filter(([prop, value]) => prop.startsWith('padding-') && value && value.trim() !== '')
                    .map(([prop, value]) => `${prop}: ${value} !important`)
                    .join('; ');
                
                if (tabletMargins) {
                    css += `@media screen and (max-width: 991px) and (min-width: 768px) {\n`;
                    css += `  #${componentId} { ${tabletMargins}; }\n`;
                    css += `}\n`;
                }
                if (tabletPaddings) {
                    css += `@media screen and (max-width: 991px) and (min-width: 768px) {\n`;
                    css += `  #${componentId} > *:not(.component-controls):not(.perk-wrap):not(.investment-tier):not(.investment-tier-component) { ${tabletPaddings}; }\n`;
                    css += `}\n`;
                }
                
                // Also apply to tablet preview mode
                if (tabletMargins) {
                    css += `.canvas.tablet-view #${componentId} { ${tabletMargins}; }\n`;
                }
                if (tabletPaddings) {
                    css += `.canvas.tablet-view #${componentId} > *:not(.component-controls):not(.perk-wrap):not(.investment-tier):not(.investment-tier-component) { ${tabletPaddings}; }\n`;
                }
            }
            
            // Mobile styles
            if (styles.mobile && Object.keys(styles.mobile).length > 0) {
                const mobileMargins = Object.entries(styles.mobile)
                    .filter(([prop, value]) => prop.startsWith('margin-') && value && value.trim() !== '')
                    .map(([prop, value]) => `${prop}: ${value} !important`)
                    .join('; ');
                const mobilePaddings = Object.entries(styles.mobile)
                    .filter(([prop, value]) => prop.startsWith('padding-') && value && value.trim() !== '')
                    .map(([prop, value]) => `${prop}: ${value} !important`)
                    .join('; ');
                
                if (mobileMargins) {
                    css += `@media screen and (max-width: 767px) {\n`;
                    css += `  #${componentId} { ${mobileMargins}; }\n`;
                    css += `}\n`;
                }
                if (mobilePaddings) {
                    css += `@media screen and (max-width: 767px) {\n`;
                    css += `  #${componentId} > *:not(.component-controls):not(.perk-wrap):not(.investment-tier):not(.investment-tier-component) { ${mobilePaddings}; }\n`;
                    css += `}\n`;
                }
                
                // Also apply to mobile preview mode
                if (mobileMargins) {
                    css += `.canvas.mobile-view #${componentId} { ${mobileMargins}; }\n`;
                }
                if (mobilePaddings) {
                    css += `.canvas.mobile-view #${componentId} > *:not(.component-controls):not(.perk-wrap):not(.investment-tier):not(.investment-tier-component) { ${mobilePaddings}; }\n`;
                }
            }
        }
    });
    
    style.textContent = css;
    document.head.appendChild(style);
}

// Apply responsive styles when device view changes
function applyResponsiveStyles() {
    const currentDevice = getCurrentPreviewDevice();
    const components = document.querySelectorAll('.component');
    
    components.forEach(component => {
        const content = getContentElement(component);
        if (content && content._responsiveStyles && content._responsiveStyles[currentDevice]) {
            const styles = content._responsiveStyles[currentDevice];
            
            // Apply margin styles to component wrapper
            Object.entries(styles).forEach(([property, value]) => {
                if (property.startsWith('margin-')) {
                    component.style[property] = value;
                } else if (property.startsWith('padding-')) {
                    content.style[property] = value;
                }
            });
        }
    });
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
        
        // Save state for image change
        historyManager.saveState('Image source updated');
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
            if (!selectedComponent) {
                console.warn('updateContent: No selectedComponent');
                return;
            }
            const content = getContentElement(selectedComponent);
            if (!content) {
                console.warn('updateContent: getContentElement returned null for', selectedComponent);
                return;
            }

            // Try to find the text box preview element
            const textBoxPreview = content.querySelector('[id^="text-content-"]');
            if (textBoxPreview) {
                console.log('updateContent: Updating text box preview', textBoxPreview, 'with', text);
                textBoxPreview.innerHTML = text;
                
                // Save throttled history for content changes
                saveHistoryThrottled('Content updated');
                return;
            }

            // Fallback: update content.textContent (for other types)
            console.log('updateContent: Fallback, updating content.textContent for', content, 'with', text);
            content.textContent = text;
            
            // Save throttled history for content changes
            saveHistoryThrottled('Content updated');
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
        
        // Save state for heading level change
        historyManager.saveState(`Changed heading to ${level.toUpperCase()}`);
      }
    }

    function getContentElement(component) {
        if (!component || !component.children) {
            console.warn('Invalid component passed to getContentElement');
            return null;
        }
        
        const content = Array.from(component.children).find(child =>
            !child.classList.contains('component-controls') &&
            !child.classList.contains('compo') // add more classes as needed
        );
        
        // Ensure proper responsive styles structure
        if (content && content._responsiveStyles) {
            // Fix any arrays that should be objects
            ['desktop', 'tablet', 'mobile'].forEach(device => {
                if (Array.isArray(content._responsiveStyles[device])) {
                    console.log(`Fixing ${device} structure in getContentElement - converting array to object`);
                    content._responsiveStyles[device] = {};
                }
            });
        }
        
        return content;
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

        function updateAuctionListColumns(columns) {
            if (selectedComponent && selectedComponent.dataset.type === 'auction-list') {
                const content = getContentElement(selectedComponent);
                const cards = content.querySelectorAll('.col-md-4');
                cards.forEach(card => {
                    card.className = `col-md-${12/columns}`;
                });
            }
        }

        function toggleAuctionStartingBid(show) {
            if (selectedComponent && selectedComponent.dataset.type === 'auction-list') {
                const content = getContentElement(selectedComponent);
                const bidElements = content.querySelectorAll('.card-text');
                bidElements.forEach(el => {
                    el.style.display = show ? 'block' : 'none';
                });
            }
        }

        function uploadSingleImage(event) {
            const file = event.target.files[0];
            if (file && selectedComponent && selectedComponent.dataset.type === 'image') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const content = getContentElement(selectedComponent);
                    if (content && content._imageData) {
                        content._imageData.src = e.target.result;
                        content.renderImage();
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function updateImageField(value, field) {
            if (selectedComponent && selectedComponent.dataset.type === 'image') {
                const content = getContentElement(selectedComponent);
                if (content && content._imageData) {
                    content._imageData[field] = value;
                    content.renderImage();
                }
            }
        }

        function updateVideoEmbed(url, type = 'youtube') {
            console.log('updateVideoEmbed called with:', { url, type, selectedComponent });
            if (!selectedComponent) {
                console.log('No selected component!');
                return;
            }
            const content = getContentElement(selectedComponent);
            console.log('Content element:', content);
            if (content && content.updateVideo) {
                // Properly update the video data
                content._videoData.url = url;
                content._videoData.type = type;
                console.log('Calling content.updateVideo with:', url, type);
                content.updateVideo(url, type);
                console.log('Video component updated, videoData:', content._videoData);
                
                // Refresh properties panel to show correct type
                updatePropertyPanel();
            } else {
                console.log('Content element or updateVideo method not found');
            }
        }

        function uploadVideoFile(event) {
            if (!selectedComponent) return;
            const file = event.target.files[0];
            if (!file) return;
            
            // Check file size (10MB = 10 * 1024 * 1024 bytes)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('File size must be less than 10MB');
                event.target.value = '';
                return;
            }
            
            // Show upload progress
            const progressDiv = document.createElement('div');
            progressDiv.innerHTML = '<div style="padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; margin: 10px 0;">Uploading video... Please wait.</div>';
            event.target.parentNode.appendChild(progressDiv);
            
            // Create FormData and upload to server
            const formData = new FormData();
            formData.append('video', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/test-upload-video', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    // If response is not ok, read as text to see what was returned
                    return response.text().then(text => {
                        console.log('Error response body:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                progressDiv.remove();
                console.log('Upload response data:', data);
                if (data.success) {
                    console.log('Video URL received:', data.url);
                    console.log('Selected component:', selectedComponent);
                    console.log('Content element:', getContentElement(selectedComponent));
                    updateVideoEmbed(data.url, 'uploaded');
                    console.log('Video embed updated');
                } else {
                    alert('Upload failed: ' + (data.message || 'Unknown error'));
                    event.target.value = '';
                }
            })
            .catch(error => {
                progressDiv.remove();
                console.error('Upload error:', error);
                alert('Upload failed: ' + error.message);
                event.target.value = '';
            });
        }

        function switchVideoType(type) {
            const youtubeControls = document.getElementById('youtubeControls');
            const uploadControls = document.getElementById('uploadControls');
            
            if (type === 'youtube') {
                youtubeControls.style.display = 'block';
                uploadControls.style.display = 'none';
            } else {
                youtubeControls.style.display = 'none';
                uploadControls.style.display = 'block';
            }
            
            // Only clear current video if actually switching to a different type
            if (selectedComponent) {
                const content = getContentElement(selectedComponent);
                if (content && content._videoData) {
                    const currentType = content._videoData.type;
                    content._videoData.type = type;
                    
                    // Only clear URL and refresh if type actually changed
                    if (currentType !== type) {
                        content._videoData.url = '';
                        content.updateVideo('', type);
                    }
                }
            }
        }

        function updateVideoAutoplay(enabled) {
            if (!selectedComponent) return;
            const content = getContentElement(selectedComponent);
            if (content && content._videoData) {
                content._videoData.autoplay = enabled;
                // Re-render video with new autoplay setting
                content.updateVideo(content._videoData.url, content._videoData.type);
            }
        }

        function updateVideoSize(dimension, value) {
            console.log('=== updateVideoSize called ===');
            console.log('updateVideoSize called with:', dimension, value);
            
            if (!selectedComponent) {
                console.log('No selected component');
                return;
            }
            
            // Find the actual video component and content
            let videoComponent = selectedComponent;
            let videoContent = getContentElement(selectedComponent);
            
            // If selected component is not a video, look for video inside it
            if (selectedComponent.dataset.type !== 'video') {
                console.log('Selected component is not video, searching inside...');
                const nestedVideo = selectedComponent.querySelector('[data-type="video"]');
                if (nestedVideo) {
                    videoComponent = nestedVideo;
                    videoContent = getContentElement(nestedVideo);
                    console.log('Found nested video component');
                } else {
                    console.log('No video component found');
                    return;
                }
            }
            
            if (!videoContent) {
                console.log('No video content found');
                return;
            }
            
            console.log('Working with video content:', videoContent);
            
            // Initialize _videoData if it doesn't exist
            if (!videoContent._videoData) {
                console.log('Initializing _videoData object');
                videoContent._videoData = {
                    url: '',
                    type: 'youtube',
                    autoplay: false,
                    width: null,
                    height: null
                };
            }
            
            console.log('Current _videoData before update:', videoContent._videoData);
            
            // Update the dimension
            if (value && value > 0) {
                // Apply to video content element
                videoContent.style[dimension] = value + 'px';
                videoContent._videoData[dimension] = parseInt(value);
                console.log('Set', dimension, 'to', parseInt(value));
                
                // Apply to video container
                const container = videoContent.querySelector('.video-container');
                if (container) {
                    container.style[dimension] = value + 'px';
                    console.log('Applied', dimension, 'to container');
                }
                
                // Apply to actual video/iframe element
                const videoElement = videoContent.querySelector('video, iframe');
                if (videoElement) {
                    if (dimension === 'width') {
                        videoElement.style.width = value + 'px';
                        videoElement.setAttribute('width', value);
                    } else if (dimension === 'height') {
                        videoElement.style.height = value + 'px';
                        videoElement.setAttribute('height', value);
                    }
                    console.log('Applied', dimension, 'to video element');
                }
            } else {
                // Clear the dimension
                videoContent.style[dimension] = '';
                delete videoContent._videoData[dimension];
                console.log('Cleared', dimension);
                
                const container = videoContent.querySelector('.video-container');
                if (container) {
                    container.style[dimension] = '';
                }
                
                const videoElement = videoContent.querySelector('video, iframe');
                if (videoElement) {
                    videoElement.style[dimension] = '';
                    if (dimension === 'width') {
                        videoElement.setAttribute('width', '100%');
                    } else if (dimension === 'height') {
                        videoElement.setAttribute('height', 'auto');
                    }
                }
            }
            
            console.log('Updated _videoData:', videoContent._videoData);
            
            // Force save the page data
            setTimeout(() => {
                console.log('Auto-saving page data after dimension change');
                saveBuilderState();
            }, 100);
            
            console.log('=== END updateVideoSize ===');
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

        // function uploadGalleryImages(event) {
        //     const files = Array.from(event.target.files);
        //     const content = getContentElement(selectedComponent);
        //     content.innerHTML = ''; // Clear existing gallery

        //     files.forEach(file => {
        //         const reader = new FileReader();
        //         reader.onload = function (e) {
        //         const img = document.createElement('img');
        //         img.src = e.target.result;
        //         img.className = 'gallery-image';
        //         content.appendChild(img);
        //         };
        //         reader.readAsDataURL(file);
        //     });
        // }
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
                    col.innerHTML = `<img src="${'${src}'}" style="width:100%;height:160px;object-fit:cover;border-radius:8px;border: 1px solid #000;">`;
                    row.appendChild(col);
                });
                if (!selectedImgs.length) {
                    content.innerHTML = '<div style="border: 1px dashed #ccc; padding: 40px; text-align: center;">Gallery Placeholder</div>';
                }
            } else if (type === 'slider') {
                // Use Owl Carousel for slider preview
                const slidesToShow = content.dataset.slidesToShow ? parseInt(content.dataset.slidesToShow, 10) : 1;
                content.innerHTML = `
                    <div class="owl-carousel owl-theme" id="sliderPreview" data-slides-to-show="${'${slidesToShow}'}">
                        ${'${selectedImgs.map(src => `<div class="item"><img src="${src}" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"></div>`).join(\'\')}'}
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
                    <h5>Entry ${'${i+1}'}</h5>
                    <div class="form-group">
                        <label>Sort order</label>
                        <select class="form-select" id="faq_order_${'${i}'}" name="faq_order_${'${i}'}">
                            ${'${Array.from({length: 6}, (_, j) => `<option value="${j+1}" ${j+1===i?\'selected\':\'\'}">${j+1}</option>`).join(\'\')}'}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Label color</label>
                        <select class="form-select" id="faq_label_color_${'${i}'}" name="faq_label_color_${'${i}'}">
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
                        <select class="form-select" id="faq_text_color_${'${i}'}" name="faq_text_color_${'${i}'}">
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
                        <input type="text" class="form-control" name="faq_question_${'${i}'}" value="">
                    </div>
                    <div class="form-group">
                        <label>Answer</label>
                        <textarea class="form-control text-editor" name="faq_answer_${'${i}'}" rows="3"></textarea>
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
                    <h5>Entry ${'${i+1}'}</h5>
                    <div class="form-group">
                        <label>Sort order</label>
                        <select class="form-select" id="faq_order_${'${i}'}" name="faq_order_${'${i}'}">
                            ${'${Array.from({length: 6}, (_, j) => `<option value="${j+1}" ${j+1===i?\'selected\':\'\'}">${j+1}</option>`).join(\'\')}'}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Label color</label>
                        <select class="form-select" id="faq_label_color_${'${i}'}" name="faq_label_color_${'${i}'}">
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
                        <select class="form-select" id="faq_text_color_${'${i}'}" name="faq_text_color_${'${i}'}">
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
                        <input type="text" class="form-control" name="faq_question_${'${i}'}" value="">
                    </div>
                    <div class="form-group">
                        <label>Answer</label>
                        <textarea class="form-control text-editor" name="faq_answer_${'${i}'}" rows="3"></textarea>
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
            
            // Create entry header
            const header = document.createElement('h5');
            header.textContent = 'Entry ' + i;
            entryDiv.appendChild(header);
            
            // Create sort order select
            const orderGroup = document.createElement('div');
            orderGroup.className = 'form-group';
            orderGroup.innerHTML = '<label>Sort order</label>';
            const orderSelect = document.createElement('select');
            orderSelect.className = 'form-select faq-order';
            orderSelect.dataset.idx = idx;
            for(let j = 0; j < count; j++) {
                const option = document.createElement('option');
                option.value = j + 1;
                option.textContent = j + 1;
                if(entry.order === (j + 1)) option.selected = true;
                orderSelect.appendChild(option);
            }
            orderGroup.appendChild(orderSelect);
            entryDiv.appendChild(orderGroup);
            
            // Function to create color selects
            function createColorSelect(label, className, currentValue, dataIdx) {
                const group = document.createElement('div');
                group.className = 'form-group';
                const labelEl = document.createElement('label');
                labelEl.textContent = label;
                group.appendChild(labelEl);
                
                const select = document.createElement('select');
                select.className = 'form-select ' + className;
                select.dataset.idx = dataIdx;
                
                const colors = ['', 'primary', 'secondary', 'aqua', 'black', 'blue', 'brown', 'cyan', 'fuchsia', 'gray', 'green', 'indigo', 'lime', 'magenta', 'maroon', 'navy', 'olive', 'orange', 'pink', 'purple', 'red', 'silver', 'tan', 'teal', 'turquoise', 'violet', 'white', 'light', 'yellow', 'gold'];
                const colorLabels = ['Default', 'Primary', 'Secondary', 'Aqua', 'Black', 'Blue', 'Brown', 'Cyan', 'Fuchsia', 'Gray', 'Green', 'Indigo', 'Lime', 'Magenta', 'Maroon', 'Navy', 'Olive', 'Orange', 'Pink', 'Purple', 'Red', 'Silver', 'Tan', 'Teal', 'Turquoise', 'Violet', 'White', 'Light', 'Yellow', 'Gold'];
                
                colors.forEach((color, i) => {
                    const option = document.createElement('option');
                    option.value = color;
                    option.textContent = colorLabels[i];
                    if(currentValue === color) option.selected = true;
                    select.appendChild(option);
                });
                
                group.appendChild(select);
                return group;
            }
            
            // Add color selects
            entryDiv.appendChild(createColorSelect('Label color', 'faq-label-color', entry.labelColor, idx));
            entryDiv.appendChild(createColorSelect('Background color', 'faq-background-color', entry.backgroundColor, idx));
            entryDiv.appendChild(createColorSelect('Text color', 'faq-text-color', entry.textColor, idx));
            
            // Add question input
            const questionGroup = document.createElement('div');
            questionGroup.className = 'form-group';
            questionGroup.innerHTML = '<label>Question</label>';
            const questionInput = document.createElement('input');
            questionInput.type = 'text';
            questionInput.className = 'form-control faq-question';
            questionInput.dataset.idx = idx;
            questionInput.value = entry.question || '';
            questionGroup.appendChild(questionInput);
            entryDiv.appendChild(questionGroup);
            
            // Add answer textarea
            const answerGroup = document.createElement('div');
            answerGroup.className = 'form-group';
            answerGroup.innerHTML = '<label>Answer</label>';
            const answerTextarea = document.createElement('textarea');
            answerTextarea.className = 'form-control faq-answer';
            answerTextarea.dataset.idx = idx;
            answerTextarea.rows = 3;
            answerTextarea.value = entry.answer || '';
            answerGroup.appendChild(answerTextarea);
            entryDiv.appendChild(answerGroup);
            
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

    // --- Feature Grid Functions ---
    function updateFeatureGridField(index, value, field) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._featureGridData || !content._featureGridData.features[index]) return;
        
        content._featureGridData.features[index][field] = value;
        if (typeof content.renderFeatureGrid === 'function') content.renderFeatureGrid();
    }

    function addFeatureGridItem() {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._featureGridData) return;
        
        // Initialize color properties if they don't exist
        if (!content._featureGridData.iconColor) content._featureGridData.iconColor = '#3b82f6';
        if (!content._featureGridData.titleColor) content._featureGridData.titleColor = '#1f2937';
        if (!content._featureGridData.descriptionColor) content._featureGridData.descriptionColor = '#6b7280';
        
        const newFeature = {
            icon: 'fas fa-star',
            title: 'New Feature',
            description: 'Enter feature description here'
        };
        
        content._featureGridData.features.push(newFeature);
        if (typeof content.renderFeatureGrid === 'function') content.renderFeatureGrid();
        updatePropertyPanel(); // Refresh property panel to show new item
    }

    function removeFeatureGridItem(index) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._featureGridData || !content._featureGridData.features[index]) return;
        
        content._featureGridData.features.splice(index, 1);
        if (typeof content.renderFeatureGrid === 'function') content.renderFeatureGrid();
        updatePropertyPanel(); // Refresh property panel to remove item controls
    }

    function updateFeatureGridColor(color, field) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._featureGridData) return;
        
        // Initialize color properties if they don't exist
        if (!content._featureGridData.iconColor) content._featureGridData.iconColor = '#3b82f6';
        if (!content._featureGridData.titleColor) content._featureGridData.titleColor = '#1f2937';
        if (!content._featureGridData.descriptionColor) content._featureGridData.descriptionColor = '#6b7280';
        
        console.log(`Updating feature grid color: ${field} = ${color}`);
        console.log('Before update:', JSON.stringify(content._featureGridData));
        
        content._featureGridData[field] = color;
        
        console.log('After update:', JSON.stringify(content._featureGridData));
        
        if (typeof content.renderFeatureGrid === 'function') content.renderFeatureGrid();
        
        // Delay property panel update to ensure data is saved first
        setTimeout(() => {
            updatePropertyPanel();
        }, 10);
    }

    // --- Investment Tier Functions ---
    function updateInvestmentTierField(value, field) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._investmentTierData) return;
        
        content._investmentTierData[field] = value;
        
        // Handle background type switching
        if (field === 'backgroundType') {
            const backgroundColorGroup = document.getElementById('backgroundColor-group');
            const backgroundImageGroup = document.getElementById('backgroundImage-group');
            
            if (backgroundColorGroup && backgroundImageGroup) {
                if (value === 'image') {
                    backgroundColorGroup.style.display = 'none';
                    backgroundImageGroup.style.display = 'block';
                } else {
                    backgroundColorGroup.style.display = 'block';
                    backgroundImageGroup.style.display = 'none';
                }
            }
        }
        
        if (typeof content.renderInvestmentTier === 'function') content.renderInvestmentTier();
    }

    // Upload image for investment tier background
    function uploadInvestmentTierImage(input) {
        if (!selectedComponent || !input.files || !input.files[0]) return;
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Show loading state
        const originalText = input.parentElement.querySelector('small').textContent;
        input.parentElement.querySelector('small').textContent = 'Uploading...';
        input.disabled = true;
        
        fetch('/admins/upload-image', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the background image URL
                updateInvestmentTierField(data.url, 'backgroundImage');
                // Update the property panel to show the new image
                updatePropertyPanel();
                // Reset the file input
                input.value = '';
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Upload failed. Please try again.');
        })
        .finally(() => {
            // Reset loading state
            input.parentElement.querySelector('small').textContent = originalText;
            input.disabled = false;
        });
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
      // Only select direct component children, not nested ones inside inner-sections
      const components = Array.from(page.children).filter(child => 
        child.classList.contains('component')
      );
      
      const componentsData = components.map(component => {
        const type = component.dataset.type;
        console.log('=== SERIALIZING COMPONENT ===');
        console.log('Component element tagName:', component.tagName);
        console.log('Component element className:', component.className);
        console.log('Component dataset.type:', type);
        console.log('Component id:', component.id);
        console.log('Component outerHTML (first 200 chars):', component.outerHTML.substring(0, 200));
        
        const content = getContentElement(component);
        let data = { type };
        console.log('Initial data object type property:', data.type);
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
          data.wrapperStyle = {
            margin: component.style.margin || '',
            marginLeft: component.style.marginLeft || '',
            marginRight: component.style.marginRight || '',
            marginTop: component.style.marginTop || '',
            marginBottom: component.style.marginBottom || ''
            };
            
          // Save responsive styles
          if (content._responsiveStyles) {
            data.responsiveStyles = content._responsiveStyles;
            console.log(`Saving responsive styles for component ${type}:`, content._responsiveStyles);
          } else {
            console.log(`No responsive styles found for component ${type}`);
          }
        }
        // Serialize per type
        switch (type) {

            case 'sell-tickets':
                data.sellTicketsData = content._sellTicketsData;
                break;

            case 'slider':
                data.sliderData = content._sliderData;
                break;


            case 'gallery':
                data.galleryData = content._galleryData;
                break;


            case 'image':
                data.imageData = content._imageData;
                break;

            case 'numbered-timeline':
                data.timelineData = content._timelineData;
                break;

            case 'invest-cta':
                data.investCtaData = content._investCtaData;
                // Also save to properties for front-end compatibility
                if (content._investCtaData) {
                    data.properties = data.properties || {};
                    data.properties.background_color = content._investCtaData.bgColor || '#f8f9fa';
                    data.properties.button_text = content._investCtaData.buttonText || 'INVEST NOW';
                    data.properties.button_url = content._investCtaData.buttonUrl || '#';
                    data.properties.button_target = content._investCtaData.buttonTarget || '_self';
                    data.properties.left_value = content._investCtaData.leftValue || '$2.13';
                    data.properties.left_label = content._investCtaData.leftLabel || 'Share Price';
                    data.properties.right_value = content._investCtaData.rightValue || '$1001.10';
                    data.properties.right_label = content._investCtaData.rightLabel || 'Min. Investment';
                    data.properties.button_bg_color = content._investCtaData.buttonBgColor || '#2e7d3e';
                    data.properties.button_text_color = content._investCtaData.buttonTextColor || '#ffffff';
                    data.properties.value_color = content._investCtaData.valueColor || '#333333';
                    data.properties.label_color = content._investCtaData.labelColor || '#666666';
                    data.properties.divider_color = content._investCtaData.dividerColor || '#e0e0e0';
                }
                break;

            case 'full-width-text-image':
                data.fwtiData = content._fwtiData;
                break;

            case 'press-card':
                console.log('Serializing press-card component');
                console.log('Content _pressCardData:', content._pressCardData);
                data.pressCardData = content._pressCardData;
                console.log('Serialized pressCardData:', data.pressCardData);
                break;

          case 'section-title':
            data.text = content.textContent;
            break;
          case 'video':
            console.log('=== SERIALIZING VIDEO COMPONENT ===');
            console.log('Content element:', content);
            console.log('Content _videoData:', content._videoData);
            console.log('Content style:', content.style.cssText);
            
            // Ensure _videoData exists with proper structure
            if (!content._videoData) {
                console.log('Creating new _videoData object');
                content._videoData = { url: '', type: 'youtube', width: null, height: null };
            }
            
            // Also try to capture dimensions from component style if _videoData is missing them
            if (!content._videoData.width && content.style.width && content.style.width !== '100%') {
                const widthValue = parseInt(content.style.width);
                if (widthValue > 0) {
                    content._videoData.width = widthValue;
                    console.log('Captured width from style:', widthValue);
                }
            }
            if (!content._videoData.height && content.style.height && content.style.height !== 'auto') {
                const heightValue = parseInt(content.style.height);
                if (heightValue > 0) {
                    content._videoData.height = heightValue;
                    console.log('Captured height from style:', heightValue);
                }
            }
            
            data.videoData = content._videoData;
            console.log('Final serialized video data:', data.videoData);
            console.log('=== END VIDEO SERIALIZATION ===');
            break;
          case 'divider':
            data.style = Object.assign({}, data.style, { height: content.style.height, backgroundColor: content.style.backgroundColor });
            break;
          case 'site-banner':
            data.src = content.src;
            data.alt = content.alt;
            break;
          case 'custom-banner':
            data.customBannerData = content._customBannerData;
            break;
          case 'faq':
            data.faqData = content._faqData || {};
            break;
          case 'simple-comments':
            data.simpleCommentsData = content._simpleCommentsData || {};
            break;
          case 'disqus':
            data.disqusData = content._disqusData || {};
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
          case 'feature-grid':
            console.log('Serializing feature-grid with data:', content._featureGridData);
            data.featureGridData = content._featureGridData;
            break;
          case 'investment-tier':
            data.investmentTierData = content._investmentTierData;
            break;
          case 'custom-form':
            data.customFormFields = content._customFormFields;
            break;
          case 'inner-section':
            data.innerSectionData = content._innerSectionData;
            // Also save the nested components structure
            const columns = content.querySelectorAll('.inner-column');
            data.nestedComponents = Array.from(columns).map(column => {
              const columnComponents = Array.from(column.querySelectorAll('.component'));
              return columnComponents.map(comp => {
                const compType = comp.dataset.type;
                const compContent = getContentElement(comp);
                let compData = { type: compType };
                
                // Save complete component data (same as main components)
                if (compContent) {
                  compData.html = compContent.innerHTML;
                  compData.style = {
                    color: compContent.style.color || '',
                    backgroundColor: compContent.style.backgroundColor || '',
                    fontSize: compContent.style.fontSize || '',
                    padding: compContent.style.padding || '',
                    textAlign: compContent.style.textAlign || '',
                    border: compContent.style.border || '',
                    borderRadius: compContent.style.borderRadius || '',
                    margin: compContent.style.margin || '',
                    width: compContent.style.width || '',
                    height: compContent.style.height || '',
                    boxShadow: compContent.style.boxShadow || '',
                    fontWeight: compContent.style.fontWeight || '',
                    fontFamily: compContent.style.fontFamily || '',
                    letterSpacing: compContent.style.letterSpacing || '',
                    lineHeight: compContent.style.lineHeight || '',
                    textDecoration: compContent.style.textDecoration || ''
                  };
                  compData.wrapperStyle = {
                    margin: comp.style.margin || '',
                    marginLeft: comp.style.marginLeft || '',
                    marginRight: comp.style.marginRight || '',
                    marginTop: comp.style.marginTop || '',
                    marginBottom: comp.style.marginBottom || ''
                  };
                  
                  // Save component-specific data based on type
                  switch (compType) {
                    case 'image':
                      if (compContent._imageData) {
                        compData.imageData = compContent._imageData;
                      }
                      break;
                    case 'numbered-timeline':
                      if (compContent._timelineData) {
                        compData.timelineData = compContent._timelineData;
                      }
                      break;
                    case 'gallery':
                      if (compContent._galleryData) {
                        compData.galleryData = compContent._galleryData;
                      }
                      break;
                    case 'slider':
                      if (compContent._sliderData) {
                        compData.sliderData = compContent._sliderData;
                      }
                      break;
                    case 'text-images':
                      if (compContent._textImagesData) {
                        compData.textImagesData = compContent._textImagesData;
                      }
                      break;
                    case 'custom-form':
                      if (compContent._customFormFields) {
                        compData.customFormFields = compContent._customFormFields;
                      }
                      break;
                    case 'event-countdown':
                      if (compContent._countdownData) {
                        compData.countdownData = compContent._countdownData;
                      }
                      break;
                    case 'event-information':
                      if (compContent._eventInfoData) {
                        compData.eventInfoData = compContent._eventInfoData;
                      }
                      break;
                    case 'site-goal':
                      if (compContent._goalData) {
                        compData.goalData = compContent._goalData;
                      }
                      break;
                    case 'custom-banner':
                      if (compContent._customBannerData) {
                        compData.customBannerData = compContent._customBannerData;
                      }
                      break;
                    case 'faq':
                      if (compContent._faqData) {
                        compData.faqData = compContent._faqData;
                      }
                      break;
                    case 'sell-tickets':
                      if (compContent._sellTicketsData) {
                        compData.sellTicketsData = compContent._sellTicketsData;
                      }
                      break;
                    case 'feature-grid':
                      if (compContent._featureGridData) {
                        console.log('Serializing nested feature-grid with data:', compContent._featureGridData);
                        compData.featureGridData = compContent._featureGridData;
                      }
                      break;
                    case 'investment-tier':
                      if (compContent._investmentTierData) {
                        compData.investmentTierData = compContent._investmentTierData;
                      }
                      break;
                    case 'full-width-text-image':
                      if (compContent._fwtiData) {
                        compData.fwtiData = compContent._fwtiData;
                      }
                      break;
                    case 'press-card':
                      console.log('Serializing nested press-card component');
                      console.log('Nested content _pressCardData:', compContent._pressCardData);
                      if (compContent._pressCardData) {
                        compData.pressCardData = compContent._pressCardData;
                        console.log('Nested pressCardData saved:', compData.pressCardData);
                      }
                      break;
                    case 'video':
                      if (compContent._videoData) {
                        console.log('Serializing nested video with data:', compContent._videoData);
                        compData.videoData = compContent._videoData;
                      }
                      break;
                  }
                  
                  // Save responsive styles for nested components
                  if (compContent._responsiveStyles) {
                    compData.responsiveStyles = compContent._responsiveStyles;
                  }
                }
                
                return compData;
              });
            });
            break;
          // ...add other types as needed...
          default:
            data.html = content.innerHTML;
        }
        console.log('Final component data type:', data.type);
        console.log('Final component data keys:', Object.keys(data));
        if (data.type === 'video') {
          console.log('VIDEO COMPONENT - Final videoData:', data.videoData);
        }
        console.log('=== END COMPONENT SERIALIZATION ===');
        return data;
      });
      
      // Return both components and page settings
      return {
        components: componentsData,
        pageSettings: {
          backgroundColor: page.style.backgroundColor || '#ffffff'
        }
      };
    }

    function deserializeBuilder(state) {
      console.log('DeserializeBuilder called with state:', state);
      const page = document.getElementById('page');
      page.innerHTML = '';
      
      // Handle both legacy format (array) and new format (object with components and pageSettings)
      let components = Array.isArray(state) ? state : (state.components || []);
      let pageSettings = state.pageSettings || {};
      
      console.log('DeserializeBuilder - Total components to load:', components.length);
      console.log('DeserializeBuilder - Components array:', components.map(c => ({ type: c.type, hasVideoData: !!c.videoData })));
      
      // Apply page settings
      if (pageSettings.backgroundColor) {
        updatePageBackground(pageSettings.backgroundColor);
        const colorInput = document.getElementById('pageBackgroundColor');
        if (colorInput) {
          colorInput.value = pageSettings.backgroundColor;
        }
      }
      
      components.forEach((data, idx) => {
        console.log(`Loading component ${idx}:`, data.type, 'responsiveStyles:', data.responsiveStyles);
        
        let component;
        
        // Check if this is a legacy component (not wrapped in inner-section)
        if (data.type !== 'inner-section') {
          // Create an auto inner-section wrapper for legacy components
          component = createComponent('inner-section');
          const innerContent = getContentElement(component);
          
          // Set to 1 column for auto-section
          innerContent.updateColumns(1);
          innerContent._innerSectionData.columns = 1;
          
          // Update the label to indicate it's auto-created and hide it
          const sectionLabel = innerContent.querySelector('.section-label');
          if (sectionLabel) {
            sectionLabel.textContent = 'Auto Section (1 Column)';
            sectionLabel.style.display = 'none'; // Hide label for auto sections
          }
          
          // Create the actual component and add it to the auto-section
          const actualComponent = createComponent(data.type);
          actualComponent.id = `component-${idx}`;
          const actualContent = getContentElement(actualComponent);
          
          // Restore the component data based on type
          switch (data.type) {
            case 'sell-tickets':
                actualContent._sellTicketsData = data.sellTicketsData || {
                    title: 'Buy Tickets',
                    buttonText: 'Buy Now',
                    buttonBg: '#007bff',
                    buttonColor: '#fff',
                    buttonPadding: '10px 20px',
                    buttonRadius: '4px'
                };
                actualContent.renderSellTickets();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'slider':
                actualContent._sliderData = data.sliderData || { images: [], slidesToShow: 1, slideSpeed: 2000 };
                actualContent.renderSlider();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'gallery':
                actualContent._galleryData = data.galleryData || { images: [], columns: 3 };
                actualContent.renderGallery();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'image':
                actualContent._imageData = data.imageData;
                actualContent.renderImage();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'numbered-timeline':
                actualContent._timelineData = data.timelineData;
                actualContent.renderTimeline();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'invest-cta':
                // Load from properties if available (for front-end compatibility)
                let investCtaDefaults = {
                    buttonText: 'INVEST NOW',
                    buttonUrl: '/invest',
                    buttonTarget: '_self',
                    leftValue: '$2.13',
                    leftLabel: 'Share Price',
                    rightValue: '$1001.10',
                    rightLabel: 'Min. Investment',
                    buttonBgColor: '#2e7d3e',
                    buttonTextColor: '#ffffff',
                    valueColor: '#333333',
                    labelColor: '#666666',
                    dividerColor: '#e0e0e0',
                    bgColor: '#f8f9fa'
                };
                
                // If we have properties from front-end, map them back to builder format
                if (data.properties) {
                    investCtaDefaults.bgColor = data.properties.background_color || investCtaDefaults.bgColor;
                    investCtaDefaults.buttonText = data.properties.button_text || investCtaDefaults.buttonText;
                    investCtaDefaults.buttonUrl = data.properties.button_url || investCtaDefaults.buttonUrl;
                    investCtaDefaults.buttonTarget = data.properties.button_target || investCtaDefaults.buttonTarget;
                    investCtaDefaults.leftValue = data.properties.left_value || investCtaDefaults.leftValue;
                    investCtaDefaults.leftLabel = data.properties.left_label || investCtaDefaults.leftLabel;
                    investCtaDefaults.rightValue = data.properties.right_value || investCtaDefaults.rightValue;
                    investCtaDefaults.rightLabel = data.properties.right_label || investCtaDefaults.rightLabel;
                    investCtaDefaults.buttonBgColor = data.properties.button_bg_color || investCtaDefaults.buttonBgColor;
                    investCtaDefaults.buttonTextColor = data.properties.button_text_color || investCtaDefaults.buttonTextColor;
                    investCtaDefaults.valueColor = data.properties.value_color || investCtaDefaults.valueColor;
                    investCtaDefaults.labelColor = data.properties.label_color || investCtaDefaults.labelColor;
                    investCtaDefaults.dividerColor = data.properties.divider_color || investCtaDefaults.dividerColor;
                }
                
                actualContent._investCtaData = data.investCtaData || investCtaDefaults;
                // Re-render the component with saved data
                const investWrapper = actualContent.querySelector('.invest-cta-wrapper');
                if (investWrapper) {
                    const d = actualContent._investCtaData;
                    investWrapper.style.backgroundColor = d.bgColor;
                    investWrapper.innerHTML = `
                        <div class="invest-cta-button-wrap">
                            <a href="${d.buttonUrl}" 
                               target="${d.buttonTarget}" 
                               class="invest-cta-button"
                               style="display: inline-block; background-color: ${d.buttonBgColor}; color: ${d.buttonTextColor}; text-decoration: none; padding: 15px 30px; border-radius: 4px; font-size: 14px; font-weight: 600; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; border: none; cursor: pointer; white-space: nowrap; flex-shrink: 0;"
                               aria-label="${d.buttonText}">
                                ${d.buttonText}
                            </a>
                        </div>
                        
                        <div class="investment-info-wrapper" style="display: flex; align-items: center; justify-content: center; gap: 20px; flex: 1;">
                            <div class="investment-info-item" style="text-align: center; flex: 1;">
                                <div class="investment-value" style="color: ${d.valueColor}; font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 5px;">${d.leftValue}</div>
                                <div class="investment-label" style="color: ${d.labelColor}; font-size: 14px; font-weight: 400; line-height: 1.2;">${d.leftLabel}</div>
                            </div>
                            
                            <div class="investment-divider" style="width: 1px; height: 40px; background-color: ${d.dividerColor}; flex-shrink: 0;"></div>
                            
                            <div class="investment-info-item" style="text-align: center; flex: 1;">
                                <div class="investment-value" style="color: ${d.valueColor}; font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 5px;">${d.rightValue}</div>
                                <div class="investment-label" style="color: ${d.labelColor}; font-size: 14px; font-weight: 400; line-height: 1.2;">${d.rightLabel}</div>
                            </div>
                        </div>
                    `;
                }
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'full-width-text-image':
                actualContent._fwtiData = data.fwtiData;
                actualContent.renderFWTI();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'section-title':
                actualContent.textContent = data.text;
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'divider':
                if (data.style) {
                  actualContent.style.height = data.style.height;
                  actualContent.style.backgroundColor = data.style.backgroundColor;
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'site-banner':
                actualContent.src = data.src;
                actualContent.alt = data.alt;
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'custom-banner':
                actualContent._customBannerData = data.customBannerData || {
                    imgSrc: '',
                    title: 'Custom Banner Title',
                    subtitle: 'Custom Banner Subtitle',
                    titleShadow: '0 2px 8px rgba(0,0,0,0.5)',
                    subtitleShadow: '0 2px 8px rgba(0,0,0,0.5)'
                };
                actualContent.renderCustomBanner();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'faq':
                actualContent._faqData = data.faqData || data._faqData || { // Support both old and new format
                    questions: [
                        {
                            question: 'How much can I invest?',
                            answer: 'Accredited investors can invest as much as they want. But if you are NOT an accredited investor, your investment limit depends on either your annual income or net worth, whichever is greater.',
                            expanded: false
                        },
                        {
                            question: 'Why Should I Invest?',
                            answer: 'This is a great opportunity to be part of an innovative project.',
                            expanded: false
                        }
                    ],
                    questionBackgroundColor: '#f3f4f6',
                    questionTextColor: '#1f2937',
                    answerBackgroundColor: '#ffffff',
                    answerTextColor: '#374151',
                    iconColor: '#059669',
                    borderRadius: '8px',
                    spacing: '10px'
                };
                actualContent.renderFaq();
                if (data.style) Object.assign(actualContent.style, data.style);
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'event-countdown':
                actualContent._countdownData = data.countdownData;
                actualContent.renderCountdown();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'event-information':
                actualContent._eventInfoData = data.eventInfoData;
                actualContent.renderEventInfo();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'site-goal':
                actualContent._goalData = data.goalData;
                actualContent.renderThermometer();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'text-images':
                actualContent._textImagesData = Object.assign({
                    text: '',
                    imgSrc: '',
                    imgPosition: 'left',
                    imgSize: 200,
                    imgWidth: '200',
                    imgHeight: 'auto',
                    showImage: true
                }, data.textImagesData || {});
                actualContent.renderTextImages();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'feature-grid':
                console.log('Deserializing feature-grid with data:', data.featureGridData);
                actualContent._featureGridData = data.featureGridData || { features: [] };
                // Ensure color properties exist
                if (!actualContent._featureGridData.iconColor) actualContent._featureGridData.iconColor = '#3b82f6';
                if (!actualContent._featureGridData.titleColor) actualContent._featureGridData.titleColor = '#1f2937';
                if (!actualContent._featureGridData.descriptionColor) actualContent._featureGridData.descriptionColor = '#6b7280';
                console.log('After initialization:', actualContent._featureGridData);
                actualContent.renderFeatureGrid();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'investment-tier':
                actualContent._investmentTierData = Object.assign({
                    tierName: 'TIER 1',
                    tierPrice: '$2,500',
                    tierDescription: 'Investment tier description',
                    buttonText: 'INVEST NOW',
                    buttonUrl: '#',
                    buttonTarget: '_self',
                    backgroundColor: '#1a1a1a',
                    backgroundImage: '',
                    backgroundType: 'color',
                    textColor: '#ffffff',
                    buttonBgColor: '#28a745',
                    buttonTextColor: '#ffffff',
                    borderRadius: '12px',
                    padding: '2rem'
                }, data.investmentTierData || {});
                actualContent.renderInvestmentTier();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            case 'custom-form':
                actualContent._customFormFields = data.customFormFields || [];
                actualContent.renderCustomForm();
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) Object.assign(actualComponent.style, data.wrapperStyle);
                if (data.responsiveStyles) actualContent._responsiveStyles = data.responsiveStyles;
                break;

            default:
                actualContent.innerHTML = data.html;
                if (data.style) {
                  Object.assign(actualContent.style, data.style);
                }
                if (data.wrapperStyle) {
                  Object.assign(actualComponent.style, data.wrapperStyle);
                }
                if (data.responsiveStyles) {
                  actualContent._responsiveStyles = data.responsiveStyles;
                }
          }
          
          // Add the actual component to the first column
          const firstColumn = innerContent.querySelector('.inner-column');
          if (firstColumn) {
            firstColumn.appendChild(actualComponent);
            
            // Hide the dropzone text
            const columnDropzone = firstColumn.querySelector('.column-dropzone');
            if (columnDropzone) {
              columnDropzone.style.display = 'none';
            }
          }
        } else {
          // This is already an inner-section, create it normally
          component = createComponent(data.type);
          component.id = `component-${idx}`;
        }
        
        // Define content variable for both cases
        const content = getContentElement(component);
        
        // Only run this switch for existing inner-sections
        if (data.type === 'inner-section') {
          // Restore per type
          switch (data.type) {

            case 'sell-tickets':
                content._sellTicketsData = data.sellTicketsData || {
                    title: 'Buy Tickets',
                    buttonText: 'Buy Now',
                    buttonBg: '#007bff',
                    buttonColor: '#fff',
                    buttonPadding: '10px 20px',
                    buttonRadius: '4px'
                };
                content.renderSellTickets();
                if (data.style) Object.assign(content.style, data.style);
                if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
                if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
                break;

            case 'slider':
                content._sliderData = data.sliderData || { images: [], slidesToShow: 1, slideSpeed: 2000 };
                content.renderSlider();
                if (data.style) Object.assign(content.style, data.style);
                if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
                if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
                break;

            case 'gallery':
                content._galleryData = data.galleryData || { images: [], columns: 3 };
                content.renderGallery();
                if (data.style) Object.assign(content.style, data.style);
                if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
                if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
                break;


            case 'image':
                content._imageData = data.imageData;
                content.renderImage();
                if (data.style) Object.assign(content.style, data.style);
                if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
                if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
                break;

            // case 'image':
            //         content = document.createElement('div');
            //         content.className = 'single-image-component';
            //         // Store image data for this component
            //         content._imageData = {
            //             src: 'https://via.placeholder.com/400x250',
            //             alt: 'Image',
            //             width: '100%',
            //             height: 'auto',
            //             objectFit: 'cover',
            //             link: '',
            //             openInNewTab: false,
            //         };
            //         content.renderImage = function() {
            //             const d = content._imageData;
            //             content.innerHTML = `
            //                 <a href="${d.link || '#'}" ${d.link ? (d.openInNewTab ? 'target="_blank"' : '') : ''} class="image-link" style="display:inline-block;">
            //                     <img src="${d.src}" alt="${d.alt}" style="width:${d.width};height:${d.height};object-fit:${d.objectFit};border-radius:8px;cursor:pointer;transition:box-shadow .2s;" class="img-preview"/>
            //                 </a>
            //             `;
            //             // Click to open modal
            //             const img = content.querySelector('img');
            //             img.onclick = function(e) {
            //                 e.preventDefault();
            //                 openLargeImageModal(d.src, d.alt);
            //             };
            //         };
            //         content.renderImage();
            //     break;


            case 'full-width-text-image':
                content._fwtiData = data.fwtiData;
                content.renderFWTI();
                if (data.style) Object.assign(content.style, data.style);
                if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
                if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
                break;

            case 'press-card':
                if (data.pressCardData) {
                    content._pressCardData = data.pressCardData;
                    content.renderPressCard();
                }
                if (data.style) Object.assign(content.style, data.style);
                if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
                if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
                break;

          case 'section-title':
            content.textContent = data.text;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'video':
            console.log('=== DESERIALIZING VIDEO COMPONENT ===');
            console.log('Deserializing video component with data:', data);
            console.log('Content element:', content);
            console.log('Content current style before:', content.style.cssText);
            if (data.videoData) {
              console.log('Video data found:', data.videoData);
              
              // Initialize _videoData if it doesn't exist
              if (!content._videoData) {
                content._videoData = {
                  url: '',
                  type: 'youtube',
                  autoplay: false,
                  width: null,
                  height: null
                };
              }
              
              // Merge the saved data
              Object.assign(content._videoData, data.videoData);
              
              // Apply custom width and height if set - DO THIS FIRST
              if (data.videoData.width && data.videoData.width > 0) {
                content.style.width = data.videoData.width + 'px';
                console.log('Applied width:', data.videoData.width + 'px');
              }
              if (data.videoData.height && data.videoData.height > 0) {
                content.style.height = data.videoData.height + 'px';
                console.log('Applied height:', data.videoData.height + 'px');
              }
              
              // Ensure updateVideo function exists before calling it
              if (content.updateVideo && typeof content.updateVideo === 'function') {
                console.log('Calling updateVideo with:', data.videoData.url, data.videoData.type);
                content.updateVideo(data.videoData.url, data.videoData.type);
              } else {
                console.warn('updateVideo function not found, recreating video component functionality');
                // Recreate video functionality if missing
                const container = content.querySelector('.video-container');
                if (container && data.videoData.url) {
                  const { url, type, autoplay, width, height } = data.videoData;
                  const customWidth = width && width > 0 ? width + 'px' : '100%';
                  const customHeight = height && height > 0 ? height + 'px' : '200';
                  
                  if (type === 'uploaded') {
                    const autoplayAttr = autoplay ? 'autoplay muted' : '';
                    container.innerHTML = `
                      <video width="${customWidth}" height="${customHeight}" controls ${autoplayAttr} max-width: 100%;">
                        <source src="${url}" type="video/mp4">
                        <source src="${url}" type="video/webm">
                        <source src="${url}" type="video/ogg">
                        Your browser does not support the video tag.
                      </video>
                    `;
                  } else {
                    // Handle YouTube videos
                    let videoId = '';
                    if (url.includes('youtu.be/')) {
                      videoId = url.split('/').pop().split('?')[0];
                    } else if (url.includes('youtube.com/watch?v=')) {
                      const urlParams = new URLSearchParams(url.split('?')[1]);
                      videoId = urlParams.get('v');
                    } else if (url.includes('youtube.com/embed/')) {
                      videoId = url.split('/embed/')[1].split('?')[0];
                    }
                    
                    if (videoId) {
                      const autoplayParam = autoplay ? '&autoplay=1&mute=1' : '';
                      container.innerHTML = `<iframe width="${customWidth}" height="${customHeight}" src="https://www.youtube.com/embed/${videoId}?rel=0${autoplayParam}" frameborder="0" allowfullscreen style="max-width: 100%;"></iframe>`;
                    } else {
                      container.innerHTML = `<div style="padding: 20px; background: #f3f4f6; text-align: center;">Invalid video URL</div>`;
                    }
                  }
                  
                  // Apply dimensions to the container as well
                  if (width && width > 0) {
                    container.style.width = width + 'px';
                    console.log('Applied container width:', width + 'px');
                  }
                  if (height && height > 0) {
                    container.style.height = height + 'px';
                    console.log('Applied container height:', height + 'px');
                  }
                }
              }
            } else {
              console.log('No video data found in component data');
            }
            console.log('=== END VIDEO DESERIALIZATION ===');
            if (data.style) Object.assign(content.style, data.style);
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'divider':
            if (data.style) {
              content.style.height = data.style.height;
              content.style.backgroundColor = data.style.backgroundColor;
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'site-banner':
            content.src = data.src;
            content.alt = data.alt;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'custom-banner':
            content._customBannerData = data.customBannerData || {
                imgSrc: '',
                title: 'Custom Banner Title',
                subtitle: 'Custom Banner Subtitle',
                titleShadow: '0 2px 8px rgba(0,0,0,0.5)',
                subtitleShadow: '0 2px 8px rgba(0,0,0,0.5)'
            };
            content.renderCustomBanner();
            if (data.style) Object.assign(content.style, data.style);
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'faq':
            content._faqData = data.faqData || data._faqData || { // Support both old and new format
                questions: [
                    {
                        question: 'How much can I invest?',
                        answer: 'Accredited investors can invest as much as they want. But if you are NOT an accredited investor, your investment limit depends on either your annual income or net worth, whichever is greater.',
                        expanded: false
                    },
                    {
                        question: 'Why Should I Invest?',
                        answer: 'This is a great opportunity to be part of an innovative project.',
                        expanded: false
                    }
                ],
                questionBackgroundColor: '#f3f4f6',
                questionTextColor: '#1f2937',
                answerBackgroundColor: '#ffffff',
                answerTextColor: '#374151',
                iconColor: '#059669',
                borderRadius: '8px',
                spacing: '10px'
            };
            content.renderFaq();
            if (data.style) Object.assign(content.style, data.style);
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'simple-comments':
            content._simpleCommentsData = data.simpleCommentsData || {
                title: 'Comments',
                showTitle: true,
                allowAnonymous: true,
                moderationEnabled: false,
                requireEmail: true,
                maxComments: 100,
                sortOrder: 'newest',
                backgroundColor: '#ffffff',
                borderColor: '#e0e0e0',
                textColor: '#333333',
                buttonColor: '#007bff'
            };
            content.renderSimpleComments();
            if (data.style) Object.assign(content.style, data.style);
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'disqus':
            content._disqusData = data.disqusData || {
                shortname: '',
                identifier: '',
                title: '',
                url: '',
                showInPreview: true
            };
            content.renderDisqus();
            if (data.style) Object.assign(content.style, data.style);
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'event-countdown':
            content._countdownData = data.countdownData;
            content.renderCountdown();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'event-information':
            content._eventInfoData = data.eventInfoData;
            content.renderEventInfo();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'site-goal':
            content._goalData = data.goalData;
            content.renderThermometer();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'text-images':
            content._textImagesData = data.textImagesData;
            content.renderTextImages();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'feature-grid':
            content._featureGridData = data.featureGridData || { features: [] };
            // Ensure color properties exist
            if (!content._featureGridData.iconColor) content._featureGridData.iconColor = '#3b82f6';
            if (!content._featureGridData.titleColor) content._featureGridData.titleColor = '#1f2937';
            if (!content._featureGridData.descriptionColor) content._featureGridData.descriptionColor = '#6b7280';
            content.renderFeatureGrid();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'investment-tier':
            content._investmentTierData = data.investmentTierData || {};
            content.renderInvestmentTier();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'custom-form':
            content._customFormFields = data.customFormFields || [];
            content.renderCustomForm();
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          case 'inner-section':
            // Restore inner section data
            content._innerSectionData = data.innerSectionData || {
              backgroundColor: 'transparent',
              borderColor: '#ddd',
              borderStyle: 'dashed',
              borderWidth: '2px',
              borderRadius: '',
              padding: '20px',
              margin: '10px 0',
              columns: 2,
              gap: '15px'
            };
            
            // Update the section with saved data
            content.updateColumns(content._innerSectionData.columns);
            content.updateGap(content._innerSectionData.gap);
            
            // CRITICAL: Apply background after data is restored
            if (content.updateBackground && typeof content.updateBackground === 'function') {
              content.updateBackground();
              console.log('Applied background for inner-section on reload:', content._innerSectionData);
            }
            
            // Restore nested components if they exist
            if (data.nestedComponents && Array.isArray(data.nestedComponents)) {
              const columns = content.querySelectorAll('.inner-column');
              data.nestedComponents.forEach((columnData, columnIndex) => {
                if (columns[columnIndex] && Array.isArray(columnData)) {
                  columnData.forEach((compData, compIndex) => {
                    const nestedComponent = createComponent(compData.type);
                    const nestedContent = getContentElement(nestedComponent);
                    
                    // Set unique ID for nested component
                    nestedComponent.id = `nested-${columnIndex}-${compIndex}`;
                    
                    // Restore component-specific data based on type
                    switch (compData.type) {
                      case 'image':
                        if (compData.imageData) {
                          nestedContent._imageData = compData.imageData;
                          nestedContent.renderImage();
                        }
                        break;
                      case 'numbered-timeline':
                        if (compData.timelineData) {
                          nestedContent._timelineData = compData.timelineData;
                          nestedContent.renderTimeline();
                        }
                        break;
                      case 'gallery':
                        if (compData.galleryData) {
                          nestedContent._galleryData = compData.galleryData;
                          nestedContent.renderGallery();
                        }
                        break;
                      case 'slider':
                        if (compData.sliderData) {
                          nestedContent._sliderData = compData.sliderData;
                          nestedContent.renderSlider();
                        }
                        break;
                      case 'text-images':
                        if (compData.textImagesData) {
                          nestedContent._textImagesData = compData.textImagesData;
                          nestedContent.renderTextImages();
                        }
                        break;
                      case 'feature-grid':
                        if (compData.featureGridData) {
                          console.log('Deserializing nested feature-grid with data:', compData.featureGridData);
                          nestedContent._featureGridData = compData.featureGridData;
                          // Ensure color properties exist
                          if (!nestedContent._featureGridData.iconColor) nestedContent._featureGridData.iconColor = '#3b82f6';
                          if (!nestedContent._featureGridData.titleColor) nestedContent._featureGridData.titleColor = '#1f2937';
                          if (!nestedContent._featureGridData.descriptionColor) nestedContent._featureGridData.descriptionColor = '#6b7280';
                          console.log('After nested initialization:', nestedContent._featureGridData);
                          nestedContent.renderFeatureGrid();
                        }
                        break;
                      case 'custom-form':
                        if (compData.customFormFields) {
                          nestedContent._customFormFields = compData.customFormFields;
                          nestedContent.renderCustomForm();
                        }
                        break;
                      case 'event-countdown':
                        if (compData.countdownData) {
                          nestedContent._countdownData = compData.countdownData;
                          nestedContent.renderCountdown();
                        }
                        break;
                      case 'event-information':
                        if (compData.eventInfoData) {
                          nestedContent._eventInfoData = compData.eventInfoData;
                          nestedContent.renderEventInfo();
                        }
                        break;
                      case 'site-goal':
                        if (compData.goalData) {
                          nestedContent._goalData = compData.goalData;
                          nestedContent.renderThermometer();
                        }
                        break;
                      case 'custom-banner':
                        if (compData.customBannerData) {
                          nestedContent._customBannerData = compData.customBannerData;
                          nestedContent.renderCustomBanner();
                        }
                        break;
                      case 'faq':
                        if (compData.faqData || compData._faqData) {
                          nestedContent._faqData = compData.faqData || compData._faqData;
                          nestedContent.renderFaq();
                        }
                        break;
                      case 'sell-tickets':
                        if (compData.sellTicketsData) {
                          nestedContent._sellTicketsData = compData.sellTicketsData;
                          nestedContent.renderSellTickets();
                        }
                        break;
                      case 'full-width-text-image':
                        if (compData.fwtiData) {
                          nestedContent._fwtiData = compData.fwtiData;
                          nestedContent.renderFWTI();
                        }
                        break;
                      case 'press-card':
                        if (compData.pressCardData) {
                          nestedContent._pressCardData = compData.pressCardData;
                          nestedContent.renderPressCard();
                        }
                        break;
                      case 'video':
                        console.log('=== DESERIALIZING NESTED VIDEO COMPONENT ===');
                        if (compData.videoData) {
                          console.log('Nested video data found:', compData.videoData);
                          
                          // Initialize _videoData if it doesn't exist
                          if (!nestedContent._videoData) {
                            nestedContent._videoData = {
                              url: '',
                              type: 'youtube',
                              autoplay: false,
                              width: null,
                              height: null
                            };
                          }
                          
                          // Merge the saved data
                          Object.assign(nestedContent._videoData, compData.videoData);
                          console.log('Merged nested _videoData:', nestedContent._videoData);
                          
                          // Apply custom width and height if set
                          if (compData.videoData.width && compData.videoData.width > 0) {
                            nestedContent.style.width = compData.videoData.width + 'px';
                            console.log('Applied nested width:', compData.videoData.width + 'px');
                          }
                          if (compData.videoData.height && compData.videoData.height > 0) {
                            nestedContent.style.height = compData.videoData.height + 'px';
                            console.log('Applied nested height:', compData.videoData.height + 'px');
                          }
                          
                          // Call updateVideo function to render the video
                          if (nestedContent.updateVideo && typeof nestedContent.updateVideo === 'function') {
                            console.log('Calling nested updateVideo with:', compData.videoData.url, compData.videoData.type);
                            nestedContent.updateVideo(compData.videoData.url, compData.videoData.type);
                          } else {
                            console.warn('updateVideo function not found on nested component');
                          }
                        }
                        console.log('=== END NESTED VIDEO DESERIALIZATION ===');
                        break;
                      default:
                        // For basic components like text, heading, etc., just restore HTML
                        if (nestedContent && compData.html) {
                          nestedContent.innerHTML = compData.html;
                        }
                        break;
                    }
                    
                    // Restore styles (only if not already handled by component-specific rendering)
                    if (nestedContent && compData.style && !['image', 'gallery', 'slider', 'custom-form', 'event-countdown', 'event-information', 'site-goal', 'custom-banner', 'sell-tickets', 'full-width-text-image', 'press-card', 'video'].includes(compData.type)) {
                      Object.assign(nestedContent.style, compData.style);
                    }
                    
                    // Restore wrapper styles
                    if (compData.wrapperStyle) {
                      Object.assign(nestedComponent.style, compData.wrapperStyle);
                    }
                    
                    // Restore responsive styles
                    if (compData.responsiveStyles) {
                      nestedContent._responsiveStyles = compData.responsiveStyles;
                    }
                    
                    // Add to column
                    columns[columnIndex].appendChild(nestedComponent);
                    
                    // Hide dropzone text
                    const dropzone = columns[columnIndex].querySelector('.column-dropzone');
                    if (dropzone) dropzone.style.display = 'none';
                  });
                }
              });
            }
            
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) Object.assign(component.style, data.wrapperStyle);
            if (data.responsiveStyles) content._responsiveStyles = data.responsiveStyles;
            break;
          // ...add other types as needed...
          default:
            content.innerHTML = data.html;
            if (data.style) {
              Object.assign(content.style, data.style);
            }
            if (data.wrapperStyle) {
              Object.assign(component.style, data.wrapperStyle);
            }
            if (data.responsiveStyles) {
              content._responsiveStyles = data.responsiveStyles;
            }
          }
        }
        
        // Restore responsive styles for all component types (fallback)
        if (data.responsiveStyles) {
          content._responsiveStyles = data.responsiveStyles;
          console.log(`Fallback: Restored responsive styles for component ${idx}:`, content._responsiveStyles);
        } else {
          console.log(`No responsive styles to restore for component ${idx}`);
        }
        
        page.appendChild(component);
        console.log(`Component ${idx} final responsive styles:`, content._responsiveStyles);
        // Add dropzone after each component except the last
        if (idx < state.length - 1) {
          page.appendChild(createDropzone());
        }
      });
      // Always ensure a dropzone at the end
      page.appendChild(createDropzone());
      
      // Apply responsive CSS after all components are loaded
      setTimeout(() => {
        updateResponsiveCSS();
        
        // CRITICAL: Refresh all backgrounds after page load to ensure they appear in canvas
        const innerSections = document.querySelectorAll('.inner-section-component');
        innerSections.forEach(section => {
          const content = getContentElement(section);
          if (content && content.updateBackground && typeof content.updateBackground === 'function') {
            content.updateBackground();
            console.log('Refreshed background for section after full page load:', content._innerSectionData);
          }
        });
      }, 100);
    }

    function saveBuilderState() {
      console.log('=== SAVE BUILDER STATE CALLED ===');
      id = document.getElementById('page_id').value;
      console.log('Page ID:', id);

      const state = serializeBuilder();
      console.log('Serialized state:', state);
      console.log('State components count:', state.components ? state.components.length : 'no components');
      
      // Log press card data specifically
      if (state.components) {
        state.components.forEach((comp, idx) => {
          if (comp.type === 'press-card' || (comp.nestedComponents && comp.nestedComponents.some(col => col.some(nested => nested.type === 'press-card')))) {
            console.log(`Component ${idx} has press card data:`, comp);
          }
        });
      }

      fetch('/admins/page/save/'+id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ state })
      })
      .then(res => {
        console.log('Save response status:', res.status);
        return res.json();
      })
      .then(data => { 
        console.log('Save response data:', data);
        console.log('Page saved successfully!'); 
      })
      .catch(error => {
        console.error('Save failed:', error);
        alert('Save failed: ' + error.message);
      });
      
      console.log('=== END SAVE BUILDER STATE ===');
    }

    // Template Functions
    function showSaveAsTemplateModal() {
      const modal = document.getElementById('saveAsTemplateModal');
      modal.style.display = 'flex';
      
      // Set default template name based on page name if available
      const pageNameInput = document.querySelector('[name="name"]');
      const templateNameInput = document.getElementById('templateName');
      if (pageNameInput && pageNameInput.value) {
        templateNameInput.value = pageNameInput.value + ' Template';
      }
    }

    function closeSaveAsTemplateModal() {
      const modal = document.getElementById('saveAsTemplateModal');
      modal.style.display = 'none';
      
      // Clear form
      document.getElementById('saveAsTemplateForm').reset();
    }

    function savePageAsTemplate() {
      const pageId = document.getElementById('page_id').value;
      const templateName = document.getElementById('templateName').value.trim();
      const templateDescription = document.getElementById('templateDescription').value.trim();
      const templateCategory = document.getElementById('templateCategory').value;
      const isPublic = document.getElementById('templateIsPublic').checked;

      if (!templateName) {
        alert('Please enter a template name');
        return;
      }

      // Get current page state
      const currentState = serializeBuilder();
      
      const formData = new FormData();
      formData.append('template_name', templateName);
      formData.append('template_description', templateDescription);
      formData.append('template_category', templateCategory);
      if (isPublic) {
        formData.append('is_public', '1');
      }

      fetch(`/admins/templates/save-from-page/${pageId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('✅ ' + data.message);
          closeSaveAsTemplateModal();
          
          // Ask if user wants to view templates
          const viewTemplates = confirm('Template saved successfully! Would you like to view the templates page?');
          if (viewTemplates) {
            window.open('/admins/templates', '_blank');
          }
        } else {
          alert('❌ Error saving template: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('❌ Error saving template: ' + error.message);
      });
    }

    // Close modal when clicking outside of it
    document.addEventListener('click', function(event) {
      const modal = document.getElementById('saveAsTemplateModal');
      if (event.target === modal) {
        closeSaveAsTemplateModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        const saveModal = document.getElementById('saveAsTemplateModal');
        const applyModal = document.getElementById('applyTemplateModal');
        if (saveModal.style.display === 'flex') {
          closeSaveAsTemplateModal();
        }
        if (applyModal.style.display === 'flex') {
          closeApplyTemplateModal();
        }
      }
    });

    // Apply Template Functions
    let selectedTemplateId = null;

    function showApplyTemplateModal() {
      const modal = document.getElementById('applyTemplateModal');
      modal.style.display = 'flex';
      loadTemplatesForApply();
    }

    function closeApplyTemplateModal() {
      const modal = document.getElementById('applyTemplateModal');
      modal.style.display = 'none';
      selectedTemplateId = null;
      
      // Reset apply button
      const applyBtn = document.getElementById('applyTemplateBtn');
      applyBtn.disabled = true;
      applyBtn.style.opacity = '0.5';
      
      // Hide warning
      document.getElementById('applyWarning').style.display = 'none';
    }

    function loadTemplatesForApply() {
      const category = document.getElementById('templateCategoryFilter').value;
      const container = document.getElementById('templatesContainer');
      
      container.innerHTML = '<p style="text-align:center; color:#666;">Loading templates...</p>';
      
      fetch(`/admins/templates/get-templates?category=${category}`)
      .then(response => response.json())
      .then(templates => {
        if (templates.length === 0) {
          container.innerHTML = '<p style="text-align:center; color:#999;">No templates found in this category.</p>';
          return;
        }
        
        let html = '<div style="display:grid; gap:10px;">';
        templates.forEach(template => {
          html += `
            <div class="template-card" onclick="selectTemplateForApply(${template.id}, '${template.name}')" 
                 style="border:1px solid #ddd; border-radius:5px; padding:15px; cursor:pointer; transition:all 0.2s;
                        background:#f8f9fa;" 
                 onmouseover="this.style.borderColor='#17a2b8'; this.style.background='#e3f2fd';"
                 onmouseout="this.style.borderColor='#ddd'; this.style.background='#f8f9fa';">
              <div style="display:flex; justify-content:between; align-items:start;">
                <div style="flex:1;">
                  <h6 style="margin:0 0 5px 0; color:#333; font-weight:bold;">${template.name}</h6>
                  <p style="margin:0 0 8px 0; color:#666; font-size:13px; line-height:1.4;">
                    ${template.description || 'No description provided'}
                  </p>
                  <div style="display:flex; gap:10px; align-items:center;">
                    <span style="background:#17a2b8; color:white; padding:3px 8px; border-radius:3px; font-size:11px;">
                      ${template.category ? template.category.charAt(0).toUpperCase() + template.category.slice(1) : 'General'}
                    </span>
                    <small style="color:#999;">
                      <i class="bi bi-download" style="margin-right:3px;"></i>Used ${template.usage_count} times
                    </small>
                  </div>
                </div>
              </div>
            </div>
          `;
        });
        html += '</div>';
        
        container.innerHTML = html;
      })
      .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<p style="text-align:center; color:#dc3545;">Error loading templates.</p>';
      });
    }

    function selectTemplateForApply(templateId, templateName) {
      // Remove previous selection
      document.querySelectorAll('.template-card').forEach(card => {
        card.style.borderColor = '#ddd';
        card.style.background = '#f8f9fa';
        card.style.borderWidth = '1px';
      });
      
      // Highlight selected template
      event.currentTarget.style.borderColor = '#28a745';
      event.currentTarget.style.borderWidth = '2px';
      event.currentTarget.style.background = '#d4edda';
      
      selectedTemplateId = templateId;
      
      // Enable apply button
      const applyBtn = document.getElementById('applyTemplateBtn');
      applyBtn.disabled = false;
      applyBtn.style.opacity = '1';
      
      // Show warning
      document.getElementById('applyWarning').style.display = 'block';
    }

    function applySelectedTemplate() {
      if (!selectedTemplateId) {
        alert('Please select a template first');
        return;
      }
      
      if (!confirm('⚠️ Are you sure you want to apply this template? This will replace ALL current page content and cannot be undone.')) {
        return;
      }
      
      const pageId = document.getElementById('page_id').value;
      
      fetch(`/admins/templates/apply-to-page/${selectedTemplateId}/${pageId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('✅ ' + data.message);
          closeApplyTemplateModal();
          
          // Reload page to show applied template content
          window.location.reload();
        } else {
          alert('❌ Error applying template: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('❌ Error applying template: ' + error.message);
      });
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
      const saveModal = document.getElementById('saveAsTemplateModal');
      const applyModal = document.getElementById('applyTemplateModal');
      if (event.target === saveModal) {
        closeSaveAsTemplateModal();
      }
      if (event.target === applyModal) {
        closeApplyTemplateModal();
      }
    });

    window.onload = function() {

        const id = document.getElementById('page_id').value;

        fetch('/admins/page/load/'+id)
            .then(res => res.json())
            .then(data => {
            console.log('Raw page data loaded:', data);
            if (data && data.state) {
                let state = data.state;
                if (typeof state === 'string') {
                    try {
                        state = JSON.parse(state);
                        console.log('Parsed state:', state);
                    } catch (e) {
                        alert('Failed to parse saved page data.');
                        return;
                    }
                }
                deserializeBuilder(state);
                // Apply responsive CSS after loading
                updateResponsiveCSS();
                // Initialize device preview functionality
                initDevicePreview();
                // Initialize form data capture
                initFormDataCapture();
                // Initialize SortableJS for all columns after page loads
                setTimeout(() => {
                    initializeAllColumnSortables();
                    console.log('SortableJS initialized for loaded page');
                    // Initialize insertion zones for existing components
                    refreshInsertionZones();
                    console.log('Insertion zones initialized');
                    
                    // Initialize history manager and save initial state
                    historyManager.clear();
                    historyManager.saveState('Page loaded');
                    console.log('History manager initialized');
                }, 500);
            } else {
                console.log('No saved page found.');
                // Initialize device preview even if no saved page
                initDevicePreview();
                // Initialize form data capture
                initFormDataCapture();
                // Initialize insertion zones for empty page
                setTimeout(() => {
                    refreshInsertionZones();
                    
                    // Initialize history manager for empty page
                    historyManager.clear();
                    historyManager.saveState('Empty page loaded');
                    console.log('History manager initialized for empty page');
                }, 100);
            }
            })
            .catch(err => {
                console.error('Load failed:', err);
                alert('Load failed');
            });
            
        // Initialize page background color to match canvas
        const page = document.getElementById('page');
        if (page && page.style.backgroundColor) {
            const currentBgColor = page.style.backgroundColor;
            updatePageBackground(currentBgColor);
            const colorInput = document.getElementById('pageBackgroundColor');
            if (colorInput) {
                // Convert rgb to hex if needed
                const rgbToHex = (rgb) => {
                    if (rgb.startsWith('#')) return rgb;
                    const match = rgb.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\)/);
                    if (match) {
                        const r = parseInt(match[1]);
                        const g = parseInt(match[2]);
                        const b = parseInt(match[3]);
                        return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
                    }
                    return rgb;
                };
                colorInput.value = rgbToHex(currentBgColor);
            }
        }
    };

    // Add keyboard shortcuts for undo/redo
    document.addEventListener('keydown', function(e) {
        // Ctrl+Z for undo
        if (e.ctrlKey && e.key === 'z' && !e.shiftKey) {
            e.preventDefault();
            undoLastAction();
        }
        // Ctrl+Y or Ctrl+Shift+Z for redo
        else if (e.ctrlKey && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
            e.preventDefault();
            redoLastAction();
        }
    });

    // Restore the showTab function (regression fix)
    function showTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-section').forEach(tab => {
            tab.style.display = 'none';
        });
        // Remove active class from all tab buttons
        document.querySelectorAll('.sidebar-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // Show the selected tab content
        const tabContent = document.getElementById(tabId);
        if (tabContent) tabContent.style.display = 'block';
        // Add active class to the clicked button
        event.target.classList.add('active');
    }

    // Page Background Color Update Function
    function updatePageBackground(color) {
        const page = document.getElementById('page');
        const canvas = document.getElementById('canvas');
        const root = document.documentElement;
        
        // Update page background
        if (page) {
            page.style.backgroundColor = color;
        }
        
        // Update canvas background to match page background
        if (canvas) {
            canvas.style.backgroundColor = color;
        }
        
        // Update CSS variable for consistent theming
        root.style.setProperty('--bg-color', color);
        
        // Save to page data if available
        if (window.pageData) {
            window.pageData.background_color = color;
        }
    }

    // FAQ Component Functions
    function toggleFaqItem(questionElement, index) {
        // Find the FAQ component
        const faqComponent = questionElement.closest('.faq-component');
        if (!faqComponent || !faqComponent._faqData) return;
        
        // Toggle the specific item
        faqComponent._faqData.questions[index].expanded = !faqComponent._faqData.questions[index].expanded;
        
        // Close all other items (accordion behavior)
        faqComponent._faqData.questions.forEach((item, i) => {
            if (i !== index) {
                item.expanded = false;
            }
        });
        
        // Re-render the FAQ
        faqComponent.renderFaq();
    }

    function addFaqQuestion() {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._faqData) return;
        
        content._faqData.questions.push({
            question: 'New Question',
            answer: 'New Answer',
            expanded: false
        });
        
        content.renderFaq();
        updatePropertyPanel();
    }

    function removeFaqQuestion(index) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._faqData) return;
        
        if (confirm('Remove this FAQ question?')) {
            content._faqData.questions.splice(index, 1);
            content.renderFaq();
            updatePropertyPanel();
        }
    }

    function updateFaqQuestion(index, field, value) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._faqData) return;
        
        content._faqData.questions[index][field] = value;
        content.renderFaq();
    }

    function updateFaqStyle(property, value) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._faqData) return;
        
        content._faqData[property] = value;
        content.renderFaq();
    }

    // Disqus component functions
    function updateDisqusField(field, value) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._disqusData) {
            content._disqusData = {
                shortname: '',
                identifier: '',
                title: '',
                url: '',
                showInPreview: true
            };
        }
        
        content._disqusData[field] = value;
        content.renderDisqus();
        
        // Update the property panel to reflect changes
        updatePropertyPanel();
    }

    // Simple Comments component functions
    function updateSimpleCommentsField(field, value) {
        if (!selectedComponent) return;
        const content = getContentElement(selectedComponent);
        if (!content._simpleCommentsData) {
            content._simpleCommentsData = {
                title: 'Comments',
                showTitle: true,
                allowAnonymous: true,
                moderationEnabled: false,
                requireEmail: true,
                maxComments: 100,
                sortOrder: 'newest',
                backgroundColor: '#ffffff',
                borderColor: '#e0e0e0',
                textColor: '#333333',
                buttonColor: '#007bff'
            };
        }
        
        content._simpleCommentsData[field] = value;
        content.renderSimpleComments();
        
        // Update the property panel to reflect changes
        updatePropertyPanel();
    }

    // Floating Structure Panel Functions
    function toggleStructurePanel() {
        const panel = document.getElementById('floating-structure-panel');
        
        if (panel.style.display === 'none' || panel.style.display === '') {
            showStructurePanel();
        } else {
            hideStructurePanel();
        }
    }

    function showStructurePanel() {
        const panel = document.getElementById('floating-structure-panel');
        
        // Reset panel position to center when opening
        panel.style.left = '';
        panel.style.top = '';
        panel.style.transform = 'translate(-50%, -50%)';
        
        // Show panel
        panel.style.display = 'block';
        
        // Refresh structure when showing
        refreshStructure();
        
        // Initialize draggable functionality
        initDraggablePanel();
    }

    function hideStructurePanel() {
        const panel = document.getElementById('floating-structure-panel');
        
        // Hide panel
        panel.style.display = 'none';
    }

    // Structure Panel Functions
    function refreshStructure() {
        const treeContainer = document.getElementById('page-structure-tree');
        const page = document.getElementById('page');
        
        if (!treeContainer || !page) return;
        
        // Clear existing structure
        treeContainer.innerHTML = `
            <div class="structure-item" data-level="0">
                <i class="fas fa-file-alt me-2"></i>
                <span>Page Root</span>
            </div>
        `;
        
        // Get all components on the page (only direct children, not nested ones)
        const components = page.querySelectorAll(':scope > .component');
        
        components.forEach((component, index) => {
            const type = component.dataset.type || 'unknown';
            const componentInfo = getComponentInfo(type);
            
            const structureItem = document.createElement('div');
            structureItem.className = 'structure-item';
            structureItem.setAttribute('data-level', '1');
            structureItem.setAttribute('data-component-id', component.id || index);
            structureItem.setAttribute('data-component-index', index);
            structureItem.draggable = true;
            structureItem.innerHTML = `
                <i class="fas fa-grip-vertical me-2" style="color: #9ca3af; cursor: grab;"></i>
                <i class="fas ${componentInfo.icon} me-2" style="color: #6366f1;"></i>
                <span>${componentInfo.name}</span>
                <small class="ms-auto text-muted">#${index + 1}</small>
            `;
            
            // Add click handler to select component
            structureItem.addEventListener('click', (e) => {
                // Don't trigger click if dragging the grip handle
                if (e.target.classList.contains('fa-grip-vertical')) return;
                e.stopPropagation();
                selectComponentFromStructure(component);
                highlightStructureItem(structureItem);
            });
            
            // Add drag and drop handlers
            structureItem.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', index);
                structureItem.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });
            
            structureItem.addEventListener('dragend', (e) => {
                structureItem.classList.remove('dragging');
                // Remove drag-over class from all items
                document.querySelectorAll('.structure-item').forEach(item => {
                    item.classList.remove('drag-over');
                });
            });
            
            structureItem.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                structureItem.classList.add('drag-over');
            });
            
            structureItem.addEventListener('dragleave', (e) => {
                structureItem.classList.remove('drag-over');
            });
            
            structureItem.addEventListener('drop', (e) => {
                e.preventDefault();
                const draggedIndex = parseInt(e.dataTransfer.getData('text/plain'));
                const dropIndex = index;
                
                if (draggedIndex !== dropIndex) {
                    reorderComponents(draggedIndex, dropIndex);
                }
                
                structureItem.classList.remove('drag-over');
            });
            
            treeContainer.appendChild(structureItem);
            
            // Check if this is an inner-section component and add its nested components
            if (type === 'inner-section') {
                const columns = component.querySelectorAll('.inner-column');
                const totalComponents = Array.from(columns).reduce((total, col) => total + col.querySelectorAll('.component').length, 0);
                
                if (totalComponents > 0) {
                    // Add collapsible section header
                    const sectionHeader = document.createElement('div');
                    sectionHeader.className = 'structure-item section-header';
                    sectionHeader.setAttribute('data-level', '2');
                    sectionHeader.style.paddingLeft = '30px';
                    sectionHeader.style.cursor = 'pointer';
                    sectionHeader.innerHTML = `
                        <i class="fas fa-chevron-down me-2 collapse-icon" style="color: #8b5cf6; transition: transform 0.2s;"></i>
                        <i class="fas fa-layer-group me-2" style="color: #8b5cf6;"></i>
                        <span style="color: #6b7280; font-weight: 500;">Columns (${totalComponents} items)</span>
                    `;
                    
                    const columnsContainer = document.createElement('div');
                    columnsContainer.className = 'columns-container';
                    columnsContainer.style.display = 'block';
                    
                    // Toggle functionality
                    sectionHeader.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const icon = sectionHeader.querySelector('.collapse-icon');
                        const container = columnsContainer;
                        
                        if (container.style.display === 'none') {
                            container.style.display = 'block';
                            icon.style.transform = 'rotate(0deg)';
                        } else {
                            container.style.display = 'none';
                            icon.style.transform = 'rotate(-90deg)';
                        }
                    });
                    
                    treeContainer.appendChild(sectionHeader);
                    
                    columns.forEach((column, columnIndex) => {
                        const columnComponents = column.querySelectorAll('.component');
                        if (columnComponents.length > 0) {
                            // Add column header
                            const columnHeader = document.createElement('div');
                            columnHeader.className = 'structure-item nested-column';
                            columnHeader.setAttribute('data-level', '3');
                            columnHeader.style.paddingLeft = '50px';
                            columnHeader.innerHTML = `
                                <i class="fas fa-columns me-2" style="color: #8b5cf6;"></i>
                                <span style="color: #6b7280;">Column ${columnIndex + 1}</span>
                                <small class="ms-auto text-muted">${columnComponents.length} item${columnComponents.length !== 1 ? 's' : ''}</small>
                            `;
                            columnsContainer.appendChild(columnHeader);
                            
                            // Add components in this column
                            columnComponents.forEach((nestedComponent, nestedIndex) => {
                                const nestedType = nestedComponent.dataset.type || 'unknown';
                                const nestedComponentInfo = getComponentInfo(nestedType);
                                
                                const nestedStructureItem = document.createElement('div');
                                nestedStructureItem.className = 'structure-item nested-component';
                                nestedStructureItem.setAttribute('data-level', '4');
                                nestedStructureItem.setAttribute('data-component-id', nestedComponent.id || nestedIndex);
                                nestedStructureItem.style.paddingLeft = '70px';
                                
                                nestedStructureItem.innerHTML = `
                                    <i class="fas fa-level-up-alt me-2" style="color: #9ca3af; transform: rotate(90deg); font-size: 10px;"></i>
                                    <i class="fas ${nestedComponentInfo.icon} me-2" style="color: #10b981;"></i>
                                    <span style="color: #374151;">${nestedComponentInfo.name}</span>
                                    <small class="ms-auto text-muted">#${nestedIndex + 1}</small>
                                `;
                                
                                nestedStructureItem.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    selectComponentFromStructure(nestedComponent);
                                    highlightStructureItem(nestedStructureItem);
                                });
                                
                                columnsContainer.appendChild(nestedStructureItem);
                            });
                        }
                    });
                    
                    treeContainer.appendChild(columnsContainer);
                }
            }
        });
        
        // Show empty message if no components
        if (components.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'text-center text-muted';
            emptyMessage.style.padding = '20px';
            emptyMessage.innerHTML = '<i class="fas fa-inbox me-2"></i>No components added yet';
            treeContainer.appendChild(emptyMessage);
        }
    }

    function selectComponentFromStructure(component) {
        // Don't hide the floating panel anymore - keep it open
        
        // Scroll component into view
        component.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Select the component
        selectComponent(component);
        
        // Update property panel to show component properties
        updatePropertyPanel();
    }

    function highlightStructureItem(item) {
        // Remove previous highlights
        document.querySelectorAll('.structure-item').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Highlight current item
        item.classList.add('selected');
    }

    function expandAllStructure() {
        // Future implementation for collapsible structure items
        console.log('Expand all structure items');
    }

    function collapseAllStructure() {
        // Future implementation for collapsible structure items
        console.log('Collapse all structure items');
    }

    // Update structure when components are added/removed/moved
    function updateStructurePanel() {
        const panel = document.getElementById('floating-structure-panel');
        if (panel && panel.style.display !== 'none') {
            refreshStructure();
        }
    }

    // Reorder components in the page
    function reorderComponents(fromIndex, toIndex) {
        const page = document.getElementById('page');
        const components = Array.from(page.querySelectorAll('.component'));
        
        if (fromIndex < 0 || fromIndex >= components.length || toIndex < 0 || toIndex >= components.length) {
            console.warn('Invalid reorder indices:', fromIndex, toIndex);
            return;
        }
        
        if (fromIndex === toIndex) {
            console.log('No reorder needed - same position');
            return;
        }
        
        const draggedComponent = components[fromIndex];
        const targetComponent = components[toIndex];
        
        // Validate that both components still exist
        if (!draggedComponent || !targetComponent) {
            console.error('Components not found for reorder');
            return;
        }
        
        // Validate that they're still children of the page
        if (!page.contains(draggedComponent) || !page.contains(targetComponent)) {
            console.error('Components are not children of page');
            return;
        }
        
        try {
            // Find and remove the dragged component's associated dropzone
            const draggedDropzone = draggedComponent.nextElementSibling;
            if (draggedDropzone && draggedDropzone.classList.contains('dropzone')) {
                draggedDropzone.remove();
            }
            
            // Remove the dragged component but keep a reference
            const draggedParent = draggedComponent.parentNode;
            draggedComponent.remove();
            
            // Re-get the target component since DOM might have changed
            const updatedComponents = Array.from(page.querySelectorAll('.component'));
            let newTargetComponent = null;
            
            // Find target by comparing with original target
            for (let comp of updatedComponents) {
                if (comp === targetComponent) {
                    newTargetComponent = comp;
                    break;
                }
            }
            
            if (!newTargetComponent) {
                console.error('Target component not found after removal');
                // Re-add the dragged component back to its original position
                draggedParent.appendChild(draggedComponent);
                return;
            }
            
            // Insert the dragged component at the new position
            if (fromIndex < toIndex) {
                // Moving down - insert after target
                const nextSibling = newTargetComponent.nextElementSibling;
                if (nextSibling && nextSibling.classList.contains('dropzone')) {
                    // Insert after the dropzone
                    const afterDropzone = nextSibling.nextElementSibling;
                    if (afterDropzone) {
                        page.insertBefore(draggedComponent, afterDropzone);
                    } else {
                        page.appendChild(draggedComponent);
                    }
                } else if (nextSibling) {
                    page.insertBefore(draggedComponent, nextSibling);
                } else {
                    page.appendChild(draggedComponent);
                }
            } else {
                // Moving up - insert before target
                page.insertBefore(draggedComponent, newTargetComponent);
            }
            
            // Add a new dropzone after the moved component if it doesn't have one
            const componentNextSibling = draggedComponent.nextElementSibling;
            if (!componentNextSibling || !componentNextSibling.classList.contains('dropzone')) {
                const newDropzone = createDropzone();
                if (componentNextSibling) {
                    page.insertBefore(newDropzone, componentNextSibling);
                } else {
                    page.appendChild(newDropzone);
                }
            }
            
            // Refresh the structure panel to reflect new order
            refreshStructure();
            
            // Select the moved component
            selectComponent(draggedComponent);
            
            console.log('Component reordered successfully');
        } catch (error) {
            console.error('Error during component reorder:', error);
        }
    }

    // Close panel with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideStructurePanel();
        }
    });

    // Draggable Panel Functionality
    function initDraggablePanel() {
        const panel = document.getElementById('floating-structure-panel');
        const header = panel.querySelector('.floating-panel-header');
        
        if (!header || header.dataset.draggableInit) return; // Avoid duplicate initialization
        header.dataset.draggableInit = 'true';
        
        let isDragging = false;
        let startX, startY, startLeft, startTop;

        header.addEventListener('mousedown', function(e) {
            // Don't start drag if clicking on close button
            if (e.target.classList.contains('close-panel-btn') || e.target.closest('.close-panel-btn')) {
                return;
            }
            
            isDragging = true;
            panel.classList.add('dragging');
            
            // Get initial mouse position
            startX = e.clientX;
            startY = e.clientY;
            
            // Get panel's current position
            const rect = panel.getBoundingClientRect();
            startLeft = rect.left;
            startTop = rect.top;
            
            // Remove transform to use absolute positioning
            panel.style.transform = 'none';
            panel.style.left = startLeft + 'px';
            panel.style.top = startTop + 'px';
            
            // Add event listeners
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
            
            // Prevent text selection
            e.preventDefault();
        });

        function handleMouseMove(e) {
            if (!isDragging) return;
            
            // Calculate new position
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            let newLeft = startLeft + deltaX;
            let newTop = startTop + deltaY;
            
            // Keep panel within viewport bounds
            const panelRect = panel.getBoundingClientRect();
            const maxLeft = window.innerWidth - panelRect.width;
            const maxTop = window.innerHeight - panelRect.height;
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(0, Math.min(newTop, maxTop));
            
            // Apply new position
            panel.style.left = newLeft + 'px';
            panel.style.top = newTop + 'px';
        }

        function handleMouseUp() {
            if (isDragging) {
                isDragging = false;
                panel.classList.remove('dragging');
                
                // Remove event listeners
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', handleMouseUp);
            }
        }
    }
</script>

<script>
    function ckeditorinit(editorId) {
        // Initialize Quill editor for the text-images editor after DOM update
        let retryCount = 0;
        const maxRetries = 10; // Maximum 5 seconds (10 * 500ms)
        
        function initEditor() {
            const textEditor = document.getElementById(editorId);
            if (textEditor && !textEditor._quillInstance) {
                if (typeof Quill === 'undefined' || !window.quillReady) {
                    retryCount++;
                    if (retryCount >= maxRetries) {
                        console.error('Quill failed to load after maximum retries');
                        return;
                    }
                    console.log(`Quill not ready, retrying... ${retryCount}/${maxRetries}`);
                    setTimeout(initEditor, 500);
                    return;
                }
                
                console.log('Initializing Quill for:', editorId);
                
                // Hide the original textarea
                textEditor.style.display = 'none';
                
                // Create a div for Quill editor
                const quillContainer = document.createElement('div');
                quillContainer.id = editorId + '_quill';
                quillContainer.style.minHeight = '200px';
                quillContainer.style.backgroundColor = 'white';
                textEditor.parentNode.insertBefore(quillContainer, textEditor.nextSibling);
                
                // Create Quill editor with font size and color controls
                const quill = new Quill('#' + quillContainer.id, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'font': FontClass.whitelist }],
                            [{ 'size': SizeClass.whitelist }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Enter your text...',
                    formats: ['font', 'size', 'color', 'background', 'bold', 'italic', 'underline', 'list', 'bullet', 'link']
                });
                
                textEditor._quillInstance = quill;
                textEditor._quillContainer = quillContainer;
                
                // Set initial content from textarea
                if (textEditor.value && textEditor.value.trim() !== '') {
                    quill.root.innerHTML = textEditor.value;
                }
                
                // Listen for Quill changes and update textarea
                quill.on('text-change', function() {
                    const content = quill.root.innerHTML;
                    textEditor.value = content;
                    
                    // Call updateTextImagesField for text-images component
                    if (typeof updateTextImagesField === 'function') {
                        updateTextImagesField(content, 'text');
                    }
                    
                    // Also try to call updateContent for general updates
                    if (typeof updateContent === 'function') {
                        updateContent(content);
                    }
                });
            }
        }
        
        setTimeout(initEditor, 100);
    }
</script>

<script>
    function ckeditorinitTextBox(editorId) {
        // Initialize Quill editor for text boxes
        let retryCount = 0;
        const maxRetries = 10; // Maximum 5 seconds (10 * 500ms)
        
        function initEditor() {
            const textEditor = document.getElementById(editorId);
            if (textEditor && !textEditor._quillInstance) {
                if (typeof Quill === 'undefined' || !window.quillReady) {
                    retryCount++;
                    if (retryCount >= maxRetries) {
                        console.error('Quill failed to load after maximum retries');
                        return;
                    }
                    console.log(`Quill TextBox not ready, retrying... ${retryCount}/${maxRetries}`);
                    setTimeout(initEditor, 500);
                    return;
                }
                
                console.log('Initializing Quill TextBox for:', editorId);
                
                // Hide the original textarea
                textEditor.style.display = 'none';
                
                // Create a div for Quill editor
                const quillContainer = document.createElement('div');
                quillContainer.id = editorId + '_quill';
                quillContainer.style.minHeight = '200px';
                quillContainer.style.backgroundColor = 'white';
                textEditor.parentNode.insertBefore(quillContainer, textEditor.nextSibling);
                
                // Create Quill editor with font size and color controls
                const quill = new Quill('#' + quillContainer.id, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'font': FontClass.whitelist }],
                            [{ 'size': SizeClass.whitelist }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Enter your text...',
                    formats: ['font', 'size', 'color', 'background', 'bold', 'italic', 'underline', 'list', 'bullet', 'link']
                });
                
                textEditor._quillInstance = quill;
                textEditor._quillContainer = quillContainer;
                
                // Set initial content from textarea
                if (textEditor.value && textEditor.value.trim() !== '') {
                    quill.root.innerHTML = textEditor.value;
                }
                
                // Listen for Quill changes and update textarea and preview
                quill.on('text-change', function() {
                    const content = quill.root.innerHTML;
                    textEditor.value = content;
                    
                    // Call updateContent for general updates
                    if (typeof updateContent === 'function') {
                        updateContent(content);
                    }
                });
            }
        }
        
        setTimeout(initEditor, 100);
    }

    // Function to cleanup Quill instances
    function cleanupQuillEditor(editorId) {
        const textEditor = document.getElementById(editorId);
        if (textEditor && textEditor._quillInstance) {
            textEditor._quillInstance = null;
            if (textEditor._quillContainer) {
                textEditor._quillContainer.remove();
                textEditor._quillContainer = null;
            }
            textEditor.style.display = '';
        }
    }

    // Function to refresh Quill editor with new content
    function refreshQuillEditor(editorId, content) {
        const textEditor = document.getElementById(editorId);
        if (textEditor && textEditor._quillInstance) {
            textEditor._quillInstance.root.innerHTML = content || '';
            textEditor.value = content || '';
        }
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
            pageLength: 25
        });

        // Link the custom search input to the DataTable search
        $('#search').on('keyup', function() {
            const value = $(this).val();
            table.search(value).draw();
        });
    });
</script>

<script>
      document.addEventListener("DOMContentLoaded", function() {
          const parallaxElements = document.querySelectorAll('[style*="background-attachment: fixed"], [style*="background-attachment:fixed"]');
          parallaxElements.forEach(function(element) {
              element.style.backgroundAttachment = "fixed";
              element.style.backgroundPosition = "center center";
              element.style.backgroundSize = "cover";
              element.style.backgroundRepeat = "no-repeat";
              element.classList.add("parallax-element");
          });
      });
      </script>

{{-- modal html for image modal start --}}

<div id="largeImageModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:90vw;max-height:90vh;display:flex;flex-direction:column;align-items:center;">
    <span class="close" onclick="closeLargeImageModal()">&times;</span>
    <img id="largeImageModalImg" src="" alt="" style="max-width:80vw;max-height:80vh;border-radius:8px;box-shadow:0 4px 24px rgba(0,0,0,0.2);"/>
    <div id="largeImageModalAlt" style="margin-top:8px;color:#444;font-size:1.1em;"></div>
  </div>
</div>
{{-- modal html for image modal end --}}


{{-- modal html for gallery image modal start --}}

<div id="galleryLargeModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:90vw;max-height:90vh;display:flex;flex-direction:column;align-items:center;position:relative;">
    <span class="close" onclick="closeGalleryLargeModal()">&times;</span>
    <img id="galleryLargeModalImg" src="" alt="" style="max-width:80vw;max-height:80vh;border-radius:8px;box-shadow:0 4px 24px rgba(0,0,0,0.2);"/>
    <button id="galleryPrevBtn" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:2em;background:none;border:none;color:#333;cursor:pointer;">&#8592;</button>
    <button id="galleryNextBtn" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:2em;background:none;border:none;color:#333;cursor:pointer;">&#8594;</button>
  </div>
</div>




{{-- modal html for gallery image modal end --}}

<script>
// Initialize SortableJS for drag and drop between columns
function initializeColumnSortable(column) {
    if (!column || column.hasAttribute('data-sortable-initialized')) {
        return;
    }
    
    // Mark as initialized to prevent duplicate initialization
    column.setAttribute('data-sortable-initialized', 'true');
    
    try {
        const sortable = Sortable.create(column, {
            group: {
                name: 'columns',
                pull: true,
                put: true
            },
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            filter: '.column-dropzone, .insertion-zone, .component-controls, .component-controls *',
            onChoose: function(evt) {
                evt.item.style.cursor = 'grabbing';
                // Add visual feedback to all columns
                document.querySelectorAll('.inner-column').forEach(col => {
                    if (col !== evt.from) {
                        col.classList.add('sortable-drag-over');
                    }
                });
            },
            onUnchoose: function(evt) {
                evt.item.style.cursor = 'grab';
                // Remove visual feedback from all columns
                document.querySelectorAll('.inner-column').forEach(col => {
                    col.classList.remove('sortable-drag-over');
                });
            },
            onStart: function(evt) {
                evt.item.style.opacity = '0.5';
                // Hide all column dropzones during drag
                document.querySelectorAll('.column-dropzone').forEach(dropzone => {
                    dropzone.style.opacity = '0.3';
                });
            },
            onEnd: function(evt) {
                evt.item.style.opacity = '1';
                evt.item.style.cursor = 'grab';
                
                // Show all column dropzones after drag
                document.querySelectorAll('.column-dropzone').forEach(dropzone => {
                    const parentColumn = dropzone.closest('.inner-column');
                    const hasComponents = parentColumn && parentColumn.querySelectorAll('.component').length > 0;
                    dropzone.style.opacity = hasComponents ? '0' : '1';
                    dropzone.style.display = hasComponents ? 'none' : 'block';
                });
                
                // Remove visual feedback from all columns
                document.querySelectorAll('.inner-column').forEach(col => {
                    col.classList.remove('sortable-drag-over');
                });
                
                // Update structure panel to reflect new order
                setTimeout(() => {
                    updateStructurePanel();
                    // Refresh insertion zones after drag and drop
                    refreshInsertionZones();
                    
                    // Save state to history after drag and drop
                    historyManager.saveState('Component moved between columns');
                }, 100);
                
                // Auto-save the page state after drag and drop
                if (typeof autoSavePage === 'function') {
                    setTimeout(() => {
                        autoSavePage();
                    }, 500);
                }
            },
            onAdd: function(evt) {
                const targetColumn = evt.to;
                const sourceColumn = evt.from;
                
                // Hide dropzone in target column since it now has components
                const targetDropzone = targetColumn.querySelector('.column-dropzone');
                if (targetDropzone) {
                    targetDropzone.style.display = 'none';
                }
                
                // Show dropzone in source column if it's empty
                const sourceDropzone = sourceColumn.querySelector('.column-dropzone');
                if (sourceDropzone && sourceColumn.querySelectorAll('.component').length === 0) {
                    sourceDropzone.style.display = 'block';
                }
                
                // Select the moved component
                if (evt.item && evt.item.classList.contains('component')) {
                    setTimeout(() => {
                        selectComponent(evt.item);
                    }, 50);
                }
            },
            onRemove: function(evt) {
                const sourceColumn = evt.from;
                
                // Show dropzone in source column if it's now empty
                const sourceDropzone = sourceColumn.querySelector('.column-dropzone');
                if (sourceDropzone && sourceColumn.querySelectorAll('.component').length === 0) {
                    sourceDropzone.style.display = 'block';
                }
            }
        });
        
        // Store the sortable instance on the column for potential cleanup
        column._sortableInstance = sortable;
        
        console.log('SortableJS initialized for column');
        
    } catch (error) {
        console.error('Error initializing SortableJS:', error);
    }
}

// Initialize SortableJS for all existing columns on page load
function initializeAllColumnSortables() {
    const columns = document.querySelectorAll('.inner-column');
    columns.forEach(column => {
        initializeColumnSortable(column);
    });
}

// Auto-save functionality
function autoSavePage() {
    try {
        const pageData = getPageState();
        if (pageData && typeof savePage === 'function') {
            savePage();
            console.log('Page auto-saved after component drag');
        }
    } catch (error) {
        console.error('Error auto-saving page:', error);
    }
}

// Call initialization when the page is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize existing columns after a short delay to ensure DOM is ready
    setTimeout(() => {
        initializeAllColumnSortables();
    }, 1000);
});
</script>



</body>
</html>

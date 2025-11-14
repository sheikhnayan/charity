{{-- filepath: resources/views/admin/ticket/edit.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <h4>Edit Ticket</h4>
                <form action="{{ route('admin.ticket.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <select name="website_id" id="website" class="form-select" onchange="filterCategories()">
                            <option value="">Select Website</option>
                            @foreach ($websites as $website)
                            <option value="{{ $website->id }}" {{ old('website_id', $data->website_id) == $website->id ? 'selected' : '' }}>
                                {{ $website->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category_id" id="category" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                    data-website="{{ $category->website_id }}"
                                    {{ old('category_id', $data->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->website->name }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Ticket Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $data->name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="ticket" {{ (old('type', $data->type ?? '') == 'ticket') ? 'selected' : '' }}>Ticket</option>
                            <option value="product" {{ (old('type', $data->type ?? '') == 'product') ? 'selected' : '' }}>Product</option>
                            <option value="property" {{ (old('type', $data->type ?? '') == 'property') ? 'selected' : '' }}>Property</option>
                        </select>
                    </div>
                    
                    <!-- Regular Price (for ticket and product) -->
                    <div class="mb-3 regular-price-field" style="display: {{ (old('type', $data->type ?? '') != 'property') ? 'block' : 'none' }};">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price" value="{{ old('price', $data->price ?? '') }}">
                    </div>
                    
                    <!-- Property Share Fields (only for property type) -->
                    <div class="property-fields" style="display: {{ (old('type', $data->type ?? '') == 'property') ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label for="price_per_share" class="form-label">Price Per Share <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price_per_share" class="form-control" id="price_per_share" 
                                   value="{{ old('price_per_share', $data->price_per_share ?? '') }}" placeholder="e.g., 100.00">
                        </div>
                        <div class="mb-3">
                            <label for="total_shares" class="form-label">Total Shares Available <span class="text-danger">*</span></label>
                            <input type="number" name="total_shares" class="form-control" id="total_shares" 
                                   value="{{ old('total_shares', $data->total_shares ?? '') }}" placeholder="e.g., 1000">
                            <small class="text-muted">Total number of shares for this property</small>
                        </div>
                        @if(isset($data) && $data->type === 'property')
                        <div class="alert alert-info">
                            <strong>Available Shares:</strong> {{ $data->available_shares ?? 0 }} out of {{ $data->total_shares ?? 0 }}<br>
                            <strong>Sold Shares:</strong> {{ ($data->total_shares ?? 0) - ($data->available_shares ?? 0) }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Regular Quantity (for ticket and product) -->
                    <div class="mb-3 regular-quantity-field" style="display: {{ (old('type', $data->type ?? '') != 'property') ? 'block' : 'none' }};">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity" value="{{ old('quantity', $data->quantity ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">hide Until</label>
                        <input type="date" name="hide_until" class="form-control" id="valid_from" value="{{ old('valid_from', $data->hide_until ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="valid_to" class="form-label">Hide After</label>
                        <input type="date" name="hide_after" class="form-control" id="valid_to" value="{{ old('valid_to', $data->hide_after ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image[]" class="form-control" id="image" multiple>
                    </div>
                    
                    <!-- Property Documents (only for property type) -->
                    <div class="property-documents-field" style="display: {{ (old('type', $data->type ?? '') == 'property') ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label for="documents" class="form-label">Property Documents</label>
                            <input type="file" name="documents[]" class="form-control" id="documents" multiple 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx">
                            <small class="text-muted">Upload legal documents, prospectus, financial reports, etc.</small>
                        </div>
                        
                        @if(isset($data->documents) && $data->documents)
                        <div class="mb-3">
                            <label class="form-label">Existing Documents</label>
                            <div class="list-group">
                                @php
                                    $documents = is_string($data->documents) ? json_decode($data->documents, true) : $data->documents;
                                @endphp
                                @if(is_array($documents))
                                    @foreach($documents as $doc)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            @if(is_array($doc))
                                                {{ $doc['name'] ?? basename($doc['path'] ?? '') }}
                                            @else
                                                {{ basename($doc) }}
                                            @endif
                                        </span>
                                        <a href="{{ is_array($doc) ? asset($doc['path'] ?? '') : asset($doc) }}" class="btn btn-sm btn-primary" target="_blank">View</a>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description">{{ old('description', $data->description ?? '') }}</textarea>
                    </div>
                    {{-- Replace the is_active checkbox with this select in your create/edit forms --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1" {{ (old('status', $data->status ?? 1) == 1) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (old('status', $data->status ?? 1) == 0) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="product" style="display: {{ (old('type', $data->type ?? '') == 'product') ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label for="size" class="form-label">Size</label>
                            <input type="text" name="size" class="form-control" id="size" value="{{ old('size', $data->size ?? '') }}">
                        </div>
                    </div>

                    <!-- Features section - available for both products and properties -->
                    <div class="features-section mt-4" style="display: {{ (old('type', $data->type ?? '') == 'product' || old('type', $data->type ?? '') == 'property') ? 'block' : 'none' }};">
                        <h4 class="mb-2">Features</h4>

                        <div id="features-container">
                            @foreach ($data->features as $item)
                            @php
                                $rand = mt_rand(00123, 5462364923156);
                            @endphp
                                <div class="feature-row flex items-center gap-2 mb-2">
                                    <input type="text" name="features[{{ $rand }}][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2" value="{{ old('features.'.$loop->index.'.name', $item->name ?? '') }}">
                                    <input type="text" name="features[{{ $rand }}][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2" value="{{ old('features.'.$loop->index.'.value', $item->value ?? '') }}">
                                    <button type="button" class="remove-feature text-red-500 hover:text-red-700">✕</button>
                                </div>
                            @endforeach
                            
                            <div class="feature-row flex items-center gap-2 mb-2">
                                <input type="text" name="features[0][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2">
                                <input type="text" name="features[0][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2">
                            </div>
                        </div>

                        <button type="button" id="add-feature-btn" class="add-feature-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            + Add Feature
                        </button>
                    </div>

                    <script>
                    let featureIndex = 1;

                    // Add event listeners to existing remove buttons
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('.remove-feature').forEach(function(button) {
                            button.addEventListener('click', function() {
                                this.closest('.feature-row').remove();
                            });
                        });
                    });

                    document.getElementById('add-feature-btn').addEventListener('click', function() {
                        const container = document.getElementById('features-container');

                        const newRow = document.createElement('div');
                        newRow.classList.add('feature-row', 'flex', 'items-center', 'gap-2', 'mb-2');
                        newRow.innerHTML = `
                        <input type="text" name="features[${featureIndex}][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2">
                        <input type="text" name="features[${featureIndex}][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2">
                        <button type="button" class="remove-feature text-red-500 hover:text-red-700">✕</button>
                        `;

                        container.appendChild(newRow);

                        // Remove feature row
                        newRow.querySelector('.remove-feature').addEventListener('click', () => newRow.remove());

                        featureIndex++;
                    });
                    </script>

                    document.getElementById('add-feature-btn').addEventListener('click', function() {
                        const container = document.getElementById('features-container');

                        const newRow = document.createElement('div');
                        newRow.classList.add('feature-row', 'flex', 'items-center', 'gap-2', 'mb-2');
                        newRow.innerHTML = `
                        <input type="text" name="features[${featureIndex}][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2">
                        <input type="text" name="features[${featureIndex}][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2">
                        <button type="button" class="remove-feature text-red-500 hover:text-red-700">✕</button>
                        `;

                        container.appendChild(newRow);

                        // Remove feature row
                        newRow.querySelector('.remove-feature').addEventListener('click', () => newRow.remove());

                        featureIndex++;
                    });
                    </script>


                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.ticket.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
    $('#type').on('change', function() {
        var selectedType = $(this).val();
        var productDiv = $('.product');
        var propertyFields = $('.property-fields');
        var propertyDocuments = $('.property-documents-field');
        var featuresSection = $('.features-section');
        var regularPriceField = $('.regular-price-field');
        var regularQuantityField = $('.regular-quantity-field');
        var categoryField = $('#category').closest('.mb-3');

        // Hide all conditional sections first
        productDiv.hide();
        propertyFields.hide();
        propertyDocuments.hide();
        featuresSection.hide();
        regularPriceField.show();
        regularQuantityField.show();

        if (selectedType === 'product') {
            productDiv.show();
            featuresSection.show();
        } else if (selectedType === 'property') {
            featuresSection.show();
            propertyFields.show();
            propertyDocuments.show();
            regularPriceField.hide();
            regularQuantityField.hide();
            categoryField.show(); // Show category for properties
            // Make property fields required
            $('#category').prop('required', true);
            $('#price_per_share').prop('required', true);
            $('#total_shares').prop('required', true);
        } else {
            // Remove required from property fields for non-property types
            $('#price_per_share').prop('required', false);
            $('#total_shares').prop('required', false);
        }
    });

    // Filter categories by selected website
    function filterCategories() {
        const websiteId = document.getElementById('website').value;
        const categorySelect = document.getElementById('category');
        const categoryOptions = categorySelect.querySelectorAll('option');
        
        categoryOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Show "Select Category" option
            } else {
                const optionWebsiteId = option.getAttribute('data-website');
                if (websiteId === '' || optionWebsiteId === websiteId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
        });
    }

    // Initialize category filtering on page load
    document.addEventListener('DOMContentLoaded', function() {
        filterCategories();
    });
</script>
@endsection

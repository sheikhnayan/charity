{{-- filepath: resources/views/admin/ticket/create.blade.php --}}
@extends('admin.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <h4>Add Ticket</h4>
                <form action="{{ route('admin.ticket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <select name="website_id" id="website" class="form-select">
                            @foreach ($data as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="ticket">Ticket</option>
                            <option value="product">Product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Ticket Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity">
                    </div>
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">hide Until</label>
                        <input type="date" name="hide_until" class="form-control" id="valid_from">
                    </div>
                    <div class="mb-3">
                        <label for="valid_to" class="form-label">Hide After</label>
                        <input type="date" name="hide_after" class="form-control" id="valid_to">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image[]" class="form-control" id="image" multiple>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description"></textarea>
                    </div>
                    {{-- Replace the is_active checkbox with this select in your create/edit forms --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="product">
                        <div class="mb-3">
                            <label for="size" class="form-label">Size</label>
                            <input type="text" name="size" class="form-control" id="size">
                        </div>

                        <div class="features-section mt-4">
                            <h4 class="mb-2">Product Features</h4>

                            <div id="features-container">
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

                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
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
        // productDiv.empty(); // Clear previous content

        if (selectedType === 'product') {
            productDiv.show();
        } else {
            productDiv.hide();
        }
    });
</script>
@endsection

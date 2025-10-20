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
                    {{-- ...inside your <form> --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Ticket Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $data->name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="ticket" {{ (old('type', $data->type ?? '') == 'ticket') ? 'selected' : '' }}>Ticket</option>
                            <option value="product" {{ (old('type', $data->type ?? '') == 'product') ? 'selected' : '' }}>Product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price" value="{{ old('price', $data->price ?? '') }}">
                    </div>
                    <div class="mb-3">
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

                        <div class="features-section mt-4">
                            <h4 class="mb-2">Product Features</h4>

                            @foreach ($data->features as $item)
                                <div id="features-container">
                                    <div class="feature-row flex items-center gap-2 mb-2">
                                    <input type="text" name="features[{{ $loop->index }}][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2" value="{{ old('features.'.$loop->index.'.name', $item->name ?? '') }}">
                                    <input type="text" name="features[{{ $loop->index }}][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2" value="{{ old('features.'.$loop->index.'.value', $item->value ?? '') }}">
                                    <button type="button" class="remove-feature text-red-500 hover:text-red-700">✕</button>
                                    </div>
                                </div>
                            @endforeach

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
        // productDiv.empty(); // Clear previous content

        if (selectedType === 'product') {
            productDiv.show();
        } else {
            productDiv.hide();
        }
    });
</script>
@endsection

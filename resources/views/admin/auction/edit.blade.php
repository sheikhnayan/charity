@extends('admin.main')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="card p-4">
                        <form action="{{ route('admin.auction.update',[$data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-3" bis_skin_checked="1">

                                <div class="col-md-12 col-lg-12" data-step="1" data-title="Header status"
                                    data-intro="To completely remove the header section from your website, change the status to disabled."
                                    bis_skin_checked="1">
                                    <label for="status" class="form-label">
                                        Status
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                        data-title="Header status"
                                        data-description="To completely remove the header section from your website, change the status to disabled."></i>
                                    <select class="form-select" id="status" name="status">
                                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                            Enabled
                                        </option>
                                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                                            Disabled
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Title
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="title" class="form-control" value="{{ $data->title }}">
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label for="description" class="form-label text-capitalize">
                                        Description
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <textarea name="description" id="description" class="form-control">{{ $data->description }}</textarea>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Value
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="number" name="value" value="{{ $data->value }}" class="form-control">
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Deadline
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="date" name="deadline" value="{{ $data->dead_line }}" class="form-control">
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="images" class="form-label text-capitalize">
                                        Images
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"></i>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewAuctionImages(event)">
                                    <div id="auction-images-preview" class="mt-3 d-flex flex-wrap gap-2">
                                        {{-- Show existing images --}}
                                        @if($data->images && count($data->images))
                                            @foreach($data->images as $img)
                                                <div class="position-relative m-1 auction-image-thumb" data-image-id="{{ $img->id }}">
                                                    <img src="{{ asset('/uploads/'.$img->image) }}" style="max-width: 120px; max-height: 120px; object-fit: cover;" class="rounded border">
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeExistingAuctionImage(this, {{ $img->id }})">&times;</button>
                                                    <input type="hidden" name="keep_images[]" value="{{ $img->id }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>


                            </div>

                            <div class="sticky-save-button-container mt-4" bis_skin_checked="1">
                                <div class="sticky-save-button-inner" bis_skin_checked="1">
                                    <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                        type="submit" id="">
                                        Save
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- / Content -->
            <script>
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .catch(error => {
                        console.error(error);
                    });
            </script>

            <script>
                function previewAuctionImages(event) {
                    const preview = document.getElementById('auction-images-preview');
                    // Remove any previous new previews (but keep existing images)
                    Array.from(preview.querySelectorAll('.new-auction-image')).forEach(el => el.remove());

                    const files = event.target.files;
                    if (!files.length) return;
                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'position-relative m-1 new-auction-image';
                            wrapper.innerHTML = `
                                <img src="${e.target.result}" style="max-width: 120px; max-height: 120px; object-fit: cover;" class="rounded border">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="this.parentNode.remove()">&times;</button>
                            `;
                            preview.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                // Remove existing image from preview and mark for deletion
                function removeExistingAuctionImage(btn, id) {
                    // Remove the image preview
                    btn.closest('.auction-image-thumb').remove();
                    // Optionally, add a hidden input to tell backend to delete this image
                    let delInput = document.createElement('input');
                    delInput.type = 'hidden';
                    delInput.name = 'delete_images[]';
                    delInput.value = id;
                    document.querySelector('form').appendChild(delInput);
                }
            </script>
        @endsection

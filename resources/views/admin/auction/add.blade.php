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
                        <form action="{{ route('admin.auction.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$website->id}}">
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
                                        <option value="1">
                                            Enabled
                                        </option>
                                        <option value="0">
                                            Disabled
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Title
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="title" class="form-control">
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label for="description" class="form-label text-capitalize">
                                        Description
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Value
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="number" name="value" class="form-control">
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Deadline
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="date" name="deadline" class="form-control">
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="images" class="form-label text-capitalize">
                                        Images
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"></i>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewAuctionImages(event)">
                                    <div id="auction-images-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
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
                    preview.innerHTML = '';
                    const files = event.target.files;
                    if (!files.length) return;
                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '120px';
                            img.style.maxHeight = '120px';
                            img.style.objectFit = 'cover';
                            img.className = 'rounded border';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            </script>
        @endsection

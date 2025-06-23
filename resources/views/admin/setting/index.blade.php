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
                <form action="/admins/store" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data->id ?? null }}">

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Logo
                    </label>
                    <br>

                    <img src="{{ asset('uploads/'.$data->logo) ?? null}}" alt="" width="200px">

                    <br>

                    <input type="file" class="form-control" id="last_name" name="logo">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Banner
                    </label>
                    <br>
                    <img src="{{ asset('uploads/'.$data->banner) ?? null}}" alt="" width="200px">
                    <br>
                    <input type="file" class="form-control" id="last_name" name="banner">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Title
                    </label>

                    <input type="text" class="form-control" id="last_name" name="title" value="{{ $data->title ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Title 2
                    </label>

                    <input type="text" class="form-control" id="last_name" name="title2" value="{{ $data->title2 ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Sub Title
                    </label>

                    <input type="text" class="form-control" id="last_name" name="sub_title" value="{{ $data->sub_title ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Date
                    </label>

                    <input type="date" class="form-control" id="last_name" name="date" value="{{ $data->date ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Location
                    </label>

                    <input type="text" class="form-control" id="last_name" name="location" value="{{ $data->location ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Time
                    </label>

                    <input type="time" class="form-control" id="last_name" name="time" value="{{ $data->time ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Description
                    </label>

                    <textarea name="description" id="description" cols="30" rows="10" class="form-control">
                        {{ $data->description ?? null}}
                    </textarea>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Participant Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="participant_name" value="{{ $data->participant_name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Team Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="team_name" value="{{ $data->team_name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Organization Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="organization" value="{{ $data->organization ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Phone
                    </label>

                    <input type="text" class="form-control" id="last_name" name="phone" value="{{ $data->phone ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Charitable ID
                    </label>

                    <input type="text" class="form-control" id="last_name" name="charitable_id" value="{{ $data->charitable_id ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Address
                    </label>

                    <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        ZIP
                    </label>

                    <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        City
                    </label>

                    <input type="text" class="form-control" id="last_name" name="city" value="{{ $data->city ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Country
                    </label>

                    <input type="text" class="form-control" id="last_name" name="country" value="{{ $data->country ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        State
                    </label>

                    <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success">Update</button>
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

@endsection



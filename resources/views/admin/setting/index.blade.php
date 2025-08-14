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
                        Goal
                    </label>

                    <input type="number" class="form-control" id="last_name" name="goal" value="{{ $data->goal ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Payout Method
                    </label>

                    <select class="form-select" name="payout_method">
                        <option value="direct_deposits" {{ ($data->payout_method ?? null) == 'direct_deposits' ? 'selected' : '' }}>Direct Deposits</option>
                        <option value="mailed_checks" {{ ($data->payout_method ?? null) == 'mailed_checks' ? 'selected' : '' }}>Mailed Checks</option>
                        <option value="wire_transfers" {{ ($data->payout_method ?? null) == 'wire_transfers' ? 'selected' : '' }}>Wire Transfers</option>
                    </select>
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
                        ZIP / Postal Code
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
                        State / Province
                    </label>

                    <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Site Status
                    </label>

                    <select class="form-select" name="site_status">
                        <option value="1" {{ ($data->site_status ?? null) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($data->site_status ?? null) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Payment Method
                    </label>

                    <select class="form-select" name="payment_method">
                        <option value="authorize" {{ ($data->payment_method ?? null) == 'authorize' ? 'selected' : '' }}>Authorize.net</option>
                        <option value="stripe" {{ ($data->payment_method ?? null) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Stripe api key
                    </label>

                    <input type="text" class="form-control" id="last_name" name="api_key" value="{{ $data->api_key ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Stripe api secret
                    </label>

                    <input type="text" class="form-control" id="last_name" name="api_secret" value="{{ $data->api_secret ?? null}}">
                </div>

                @php
                    $pages = \App\Models\Page::where('website_id',$data->user->website_id)->where('status',1)->get();
                @endphp

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Privacy Policy Page
                    </label>

                    <select class="form-select" name="privacy">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->privacy == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Terms & Condition Page
                    </label>

                    <select class="form-select" name="terms">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->terms == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Refund Policy
                    </label>

                    <select class="form-select" name="refund">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->refund == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
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



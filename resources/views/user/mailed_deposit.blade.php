@extends('user.main')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <form action="/user/mailed_deposit/store" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data->id ?? null }}">

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Name
                    </label>
                    <input type="text" class="form-control" id="last_name" name="name" value="{{ $data->name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Email
                    </label>
                    <input type="email" class="form-control" id="last_name" name="email" value="{{ $data->email ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Phone
                    </label>
                    <input type="text" class="form-control" id="last_name" name="phone" value="{{ $data->phone ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Address
                    </label>
                    <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        city
                    </label>
                    <input type="text" class="form-control" id="last_name" name="city" value="{{ $data->city ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        country
                    </label>
                    <input type="text" class="form-control" id="last_name" name="country" value="{{ $data->country ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        state
                    </label>
                    <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        zip
                    </label>
                    <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Make the check payable to
                    </label>
                    <input type="text" class="form-control" id="last_name" name="paybale_to" value="{{ $data->paybale_to ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Person to send the check to
                    </label>
                    <input type="text" class="form-control" id="last_name" name="send_check_to" value="{{ $data->send_check_to ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Address to send the check to
                    </label>
                    <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $data->address_to_send ?? null}}">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection

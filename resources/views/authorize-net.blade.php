<!DOCTYPE html>
<html>
<head>
    <title>Authorize.net Payment Getway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card mt-5">
                <h3 class="card-header p-3">Authorize.net Payment Getway</h3>
                <div class="card-body">

                    @session('success')
                        <div class="alert alert-success" role="alert">
                            {{ $value }}
                        </div>
                    @endsession

                    @session('error')
                        <div class="alert alert-danger" role="alert">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('authorize.payment') }}">
                        @csrf
                        <input type="hidden" name="donation_id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="card_number" class="col-form-label text-md-right">{{ __('Card Number') }}</label>

                                <input id="card_number" type="text" class="form-control @error('card_number') is-invalid @enderror" name="card_number" required autocomplete="off" maxlength="16" placeholder="4111111111111111">

                                @error('card_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="expiration_date" class="col-form-label text-md-right">{{ __('Expiration Date (MM/YY)') }}</label>

                                <input id="expiration_date" type="text" class="form-control @error('expiration_date') is-invalid @enderror" name="expiration_date" required autocomplete="off" maxlength="5" placeholder="12/27">

                                @error('expiration_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>

                            <div class="col-md-6">
                                <label for="cvv" class="col-form-label text-md-right">{{ __('CVV') }}</label>

                                <input id="cvv" type="text" class="form-control @error('cvv') is-invalid @enderror" name="cvv" required autocomplete="off" maxlength="4" placeholder="1234">

                                @error('cvv')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Make Payment') }}
                                </button>
                            </div>
                            <div class="col-md-6 text-center">
                                <a href="/student/{{ $data->user->id }}-{{ $data->user->name }}-{{ $data->user->last_name }}" class="btn btn-danger">
                                    Back
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>

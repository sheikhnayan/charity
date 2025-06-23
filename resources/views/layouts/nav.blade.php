<!-- Navigation Bar -->
    <nav class="navbar navbar-expand-xl {{ $header->floating == 1 ? 'fixed-top' : 'non-float'}} bg-primary" style="background-color: {{ $header->background }} !important;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('uploads/'.$setting->logo) }}" alt="Logo" width="{{ $header->logo_size }}" height="{{ $header->logo_size }}" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @foreach ($check->pages as $item)
                    {{-- @php
                        dd($item->status);
                    @endphp --}}
                        @if ($item->status == 1)
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/page/{{ $item->id }}" style="color:{{ $header->color }} !important">{{ $item->name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>

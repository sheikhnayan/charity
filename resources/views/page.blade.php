@php
$state = $data && $data->state ? (is_string($data->state) ? json_decode($data->state, true) : $data->state) : [];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data->name ?? 'Page' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <style>
    #studentTable {
        background-color: #fff !important; /* Set the table background to white */
        border: none !important; /* Remove the table border */
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important; /* Set the background of table cells to white */
        border: none !important; /* Remove borders from table cells */
    }

    #studentTable tbody tr {
        background-color: #fff !important; /* Set the background of table rows to white */
    }

    #studentTable_filter {
        display: none;
    }

    #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none; /* Hide the table header */
    }

    .non-float{
        margin-bottom: -111px;
    }
</style>
</head>
<body>
    @php
        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $doamin)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <main style="margin-top: 7rem;">
        @foreach($state as $data)
                @php $type = $data['type'] ?? ''; @endphp
                @switch($type)
                    @case('site-banner')
                        <img src="{{ $data['src'] ?? '' }}" alt="{{ $data['alt'] ?? '' }}" style="width:100%;height:auto;">
                    @break
                    @case('custom-banner')
                        <div style="position:relative;">
                            <img src="{{ $data['imgSrc'] ?? '' }}" style="width:100%;height:auto;">
                            <h3 style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:white;text-shadow:1px 1px 4px #000;
                             @foreach($data['style'] as $key => $style)
                                {{ strtolower(preg_replace('/([A-Z])/', '-$1', $key)) }}:{{ $style ?? 'unset'}};
                            @endforeach
                        ">{{ $data['text'] ?? '' }}</h3>
                        </div>
                    @break
                @endswitch
        @endforeach
    <div class="container my-5" id="rendered-page">
        <div class="row">
            @foreach($state as $data)
                @php $type = $data['type'] ?? ''; @endphp
                <div class="col-md-12">
                    <div class="component mb-4" data-type="{{ $type }}">
                        <div class="component-content">
                            @switch($type)
                                @case('section-title')
                                    <h2 style="
                                    @foreach($data['style'] as $key => $style)
                                        {{ strtolower(preg_replace('/([A-Z])/', '-$1', $key)) }}:{{ $style ?? 'unset'}};
                                    @endforeach
                                    ">{{ $data['text'] ?? '' }}</h2>
                                    @break
                                @case('divider')
                                    <hr style="height:{{ $data['style']['height'] ?? '2px' }};background:{{ $data['style']['backgroundColor'] ?? '#eee' }};border:none;">
                                    @break
                                @case('faq')
                                    @if(isset($data['faqData']) && is_array($data['faqData']))
                                        <div class="faq-list">
                                        @foreach($data['faqData'] as $entry)
                                            <div class="mb-3">
                                                <strong>{{ $entry['question'] ?? '' }}</strong>
                                                <div>{!! nl2br(e($entry['answer'] ?? '')) !!}</div>
                                            </div>
                                        @endforeach
                                        </div>
                                    @endif
                                    @break
                                @case('event-countdown')
                                    @if(isset($data['countdownData']))
                                        @php
                                            $label = $data['countdownData']['label'] ?? '';
                                            $date = $data['countdownData']['date'] ?? '';
                                        @endphp
                                        <div class="event-countdown" style="padding:24px 16px;border-radius:8px;text-align:center;margin-bottom:24px;">
                                            <div class="timer text-center mt-5">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="mx-3"><h1 id="months" class="display-4">0</h1><p>Months</p></div>
                                                    <div class="mx-3"><h1 id="days" class="display-4">0</h1><p>Days</p></div>
                                                    <div class="mx-3"><h1 id="hours" class="display-4">0</h1><p>Hours</p></div>
                                                    <div class="mx-3"><h1 id="minutes" class="display-4">0</h1><p>Minutes</p></div>
                                                    <div class="mx-3"><h1 id="seconds" class="display-4">0</h1><p>Seconds</p></div>
                                                </div>
                                                <p style="font-size: .8em;">{{ $label }}</p>
                                            </div>
                                            <input type="hidden" id="timer" class="date-countdown" value="{{ $date }}">
                                        </div>

                                        <script>
                                            da = document.getElementById("timer").value;
                                            // Set the target date for the countdown
                                            const targetDate = new Date(da).getTime();

                                            function updateCountdown() {
                                                const now = new Date().getTime();
                                                const timeLeft = targetDate - now;

                                                if (timeLeft <= 0) {
                                                    document.getElementById("months").textContent = 0;
                                                    document.getElementById("days").textContent = 0;
                                                    document.getElementById("hours").textContent = 0;
                                                    document.getElementById("minutes").textContent = 0;
                                                    document.getElementById("seconds").textContent = 0;
                                                    return;
                                                }

                                                // Calculate time components
                                                const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
                                                const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
                                                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                                                // Update the HTML
                                                document.getElementById("months").textContent = months;
                                                document.getElementById("days").textContent = days;
                                                document.getElementById("hours").textContent = hours;
                                                document.getElementById("minutes").textContent = minutes;
                                                document.getElementById("seconds").textContent = seconds;
                                            }

                                            // Update the countdown every second
                                            setInterval(updateCountdown, 1000);
                                        </script>
                                    @endif
                                    @break
                                @case('event-information')
                                    @if(isset($data['eventInfoData']))
                                        @php
                                            $info = $data['eventInfoData'];
                                        @endphp
                                        <div class="event-information" style="padding:20px 16px;border-radius:8px;margin-bottom:24px;">
                                            <div class="icons">
                                                <div class="row gy-3 gy-md-4 row-cols-1 flex-column">
                                                    <div class="col">
                                                        <div class="row gy-3 justify-content-center text-center text-">
                                                            <div class="col-md-4 col-xl-2">
                                                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                                                    <i class="fa-solid fa-calendar-days fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                                                    <h4 class="fs-1.5 fw-light mb-1">When</h4>
                                                                    <p class="fs-.75 opacity-75 fw-light">{{ $info['date'] }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-xl-3">
                                                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                                                    <i class="fa-solid fa-signs-post fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                                                    <h4 class="fs-1.5 fw-light mb-1">Where</h4>
                                                                    <p class="fs-.75 opacity-75 fw-light">{{ $info['address'] }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-xl-2">
                                                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                                                    <i class="fa-solid fa-clock fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                                                    <h4 class="fs-1.5 fw-light mb-1">Time</h4>
                                                                    <p class="fs-.75 opacity-75 fw-light">{{ $info['time'] }} PST</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            content = document.querySelector('.event-information');
                                            const mapEmbed = "{{ $info['mapEmbed'] ?? '' }}";
                                            const showMap = "{{ $data['eventInfoData']['showMap'] }}";
                                            const mapPosition = "{{ $info['mapPosition'] ?? 'down' }}";
                                            const infoHtml = document.querySelector('.event-information').innerHTML;
                                            // Map HTML
                                            const mapHtml = showMap ? `<div class="event-map" style="margin:16px 0;"><iframe class="map embed-responsive-item rounded border border-2 border-" style="height: 300px; width: 100%; position: relative; overflow: hidden;" src="${mapEmbed}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>` : '';
                                            // Layout by mapPosition
                                            let finalHtml = '';
                                            if (showMap) {
                                                if (mapPosition === 'up') {
                                                    finalHtml = mapHtml + infoHtml;
                                                } else if (mapPosition === 'down') {
                                                    finalHtml = infoHtml + mapHtml;
                                                } else if (mapPosition === 'left') {
                                                    finalHtml = `<div style='display:flex;gap:24px;align-items:flex-start;'><div style='flex:1;max-width:50%'>${mapHtml}</div><div style='flex:1;'>${infoHtml}</div></div>`;
                                                } else {
                                                    finalHtml = `<div style='display:flex;gap:24px;align-items:flex-start;'><div style='flex:1;'>${infoHtml}</div><div style='flex:1;max-width:50%'>${mapHtml}</div></div>`;
                                                }
                                            } else {
                                                finalHtml = infoHtml;
                                            }
                                            content.innerHTML = finalHtml;
                                        </script>
                                    @endif
                                    @break
                                @case('site-goal')
                                    @if(isset($data['goalData']))
                                        @php
                                            $goal = (float)($data['goalData']['goal'] ?? 0);
                                            $raised = (float)($data['goalData']['raised'] ?? 0);
                                            $percent = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                                            $ticks = $data['goalData']['ticks'] ?? [];
                                        @endphp
                                        <div class="site-goal" style="padding:20px 16px;border-radius:8px;margin-bottom:24px;">
                                            <div class="thermometer-wrapper">
                                                <div class="bulb"><div class="bulb-inner"></div></div>
                                                <div class="bar" style="width:100%;position:relative;min-width:120px;max-width:100%;">
                                                    <div class="fill" id="fill" style="height: 16px; position: absolute; left: 0px; top: 8px; margin-left: 2rem; width: 69.8px; background: rgb(111, 124, 139); border-radius: 8px; transition: width 0.4s;"></div>
                                                    <div class="label goal-label" id="goal-label" style="top: -50px">Goal: {{ $goal }}</div>
                                                    <div class="label raised-label" id="raised-label" style="top: 50px">Raised: {{ $raised }}</div>
                                                </div>
                                                <div class="ticks">
                                                    @foreach ($ticks as $item)
                                                        <div class='tick'>{{ $item }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <input type="hidden" id="goal" value="{{ $goal }}">
                                            <input type="hidden" id="raised" value="{{ $raised }}">
                                        </div>
                                        <script>
                                            const goal = document.getElementById('goal').value;
                                            const raised = document.getElementById('raised').value;

                                            const fill = document.getElementById('fill');
                                            const bar = document.querySelector('.bar');

                                            function updateFill() {
                                            const barWidth = bar.clientWidth;
                                            const fillWidth = Math.min(raised / goal, 1) * barWidth;
                                            fill.style.width = `${fillWidth}px`;
                                            }

                                            updateFill();
                                            window.addEventListener('resize', updateFill);

                                            document.getElementById('goal-label').textContent = `Goal: $${goal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                                            document.getElementById('raised-label').textContent = `Raised: $${raised.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                                        </script>
                                    @endif
                                    @break
                                @case('text-images')
                                    @if(isset($data['textImagesData']))
                                        @php
                                            $img = $data['textImagesData']['imgSrc'] ?? '';
                                            $imgSize = $data['textImagesData']['imgSize'] ?? 200;
                                            $imgPosition = $data['textImagesData']['imgPosition'] ?? 'left';
                                            $text = $data['textImagesData']['text'] ?? '';
                                            $showImage = array_key_exists('showImage', $data['textImagesData']) ? (bool)$data['textImagesData']['showImage'] : true;
                                        @endphp
                                        <div style="display:flex;align-items:center;flex-direction:{{ in_array($imgPosition,['up','down']) ? 'column' : ($imgPosition=='right'?'row-reverse':'row') }};">
                                            @if($showImage && $img)
                                                <img src="{{ $img }}" style="width:{{ $imgSize }}px;margin:8px;">
                                            @endif
                                            <div style="
                                            @foreach($data['style'] as $key => $style)
                                                {{ strtolower(preg_replace('/([A-Z])/', '-$1', $key)) }}:{{ $style ?? 'unset'}};
                                            @endforeach
                                            width: 100%;
                                            ">{!! nl2br(e($text)) !!}</div>
                                        </div>
                                    @endif
                                    @break
                                @case('auth-form')
                                    <div class="row">
                                        <div class="col-md-12 mt-4 mb-4 text-center">
                                            <i class="fa-solid fa-circle-user fa-fw text-primary mb-3" aria-hidden="true" style="font-size: 8rem; color: #2e4053 !important;"></i>
                                            <h2 class="display-6 tit">Register</h2>
                                        </div>
                                    </div>
                                    <div class="register">
                                        <div class="container">
                                            <form action="/register" method="POST">
                                                @csrf
                                                <input type="hidden" name="website_id" value="{{ $check->id }}">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">First name</label>
                                                        <input type="text" class="form-control" id="first_name" name="name">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Last name</label>
                                                        <input type="text" class="form-control" id="first_name" name="last_name">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Email address</label>
                                                        <input type="email" class="form-control" id="first_name" name="email">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Confirm email address</label>
                                                        <input type="email" class="form-control" id="first_name" name="confirm_email">
                                                    </div>
                                                </div>
                                                <!-- Add this to your register form in the auth-form component -->
                                                <div class="row justify-content-center">
                                                    <div class="col-md-4">
                                                        <label for="register_as" class="form-label">Register as</label>
                                                        <select class="form-select" id="register_as" name="register_as" onchange="toggleGroupSelect(this)">
                                                            <option value="individual">Individual</option>
                                                            <option value="member">Group Member</option>
                                                            <option value="group_leader">Group Leader</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4" id="group_select_wrapper" style="display:none;">
                                                        <label for="group_id" class="form-label">Select Group</label>
                                                        <select class="form-select" id="group_id" name="group_id">
                                                            <option value="">Select a group</option>
                                                            <!-- Dynamically populate this with your groups -->
                                                            @foreach($groups as $group)
                                                                <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4" id="group_name_wrapper" style="display:none;">
                                                        <label for="group_name" class="form-label">Group Name</label>
                                                        <input type="text" class="form-control" id="group_name" name="group_name">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="first_name" name="password">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Confirm password</label>
                                                        <input type="password" class="form-control" id="first_name" name="confirm_password">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-8">
                                                        <div class="d-grid gap-3 mt-2">
                                                            <button class="btn btn-primary btn-lg text-white" type="submit" style="background-color: #2e4053 !important; border-color: transparent;">
                                                                <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                                                Register
                                                            </button>
                                                            <button class="btn text-primary btn-lg p-0 shadow-none view-login-form" type="button" style="color: #2e4053 !important;">
                                                                Login
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="login" style="display: none;">
                                        <div class="container">
                                            <form action="/login" method="POST">
                                                @csrf
                                                <div class="row justify-content-center">
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Email address</label>
                                                        <input type="email" class="form-control" id="first_name" name="email">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="first_name" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="first_name" name="password">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-8">
                                                        <div class="d-grid gap-3 mt-2">
                                                            <button class="btn btn-primary btn-lg text-white" type="submit" style="background-color: #2e4053 !important; border-color: transparent;">
                                                                <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                                                Login
                                                            </button>
                                                            <button class="btn text-primary btn-lg p-0 shadow-none view-register-form" type="button" style="color: #2e4053 !important;">
                                                                Register
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <script>
                                        $('.view-login-form').click(function() {
                                            $('.register').toggle();
                                            $('.login').toggle();
                                            $('.tit').html('Login');
                                        });
                                        $('.view-register-form').click(function() {
                                            $('.login').toggle();
                                            $('.register').toggle();
                                            $('.tit').html('Register');
                                        });
                                    </script>
                                    @break
                                @case('custom-form')
                                    @if(isset($data['customFormFields']) && is_array($data['customFormFields']))
                                        <form method="POST" action="#" class="custom-form-component">
                                            @csrf
                                            @foreach($data['customFormFields'] as $field)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $field['label'] ?? '' }}@if(!empty($field['required'])) <span style="color:red">*</span>@endif</label>
                                                    @if(($field['type'] ?? 'text') === 'textarea')
                                                        <textarea class="form-control" name="{{ $field['name'] ?? '' }}" @if(!empty($field['required'])) required @endif>{{ $field['value'] ?? '' }}</textarea>
                                                    @else
                                                        <input class="form-control" type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] ?? '' }}" value="{{ $field['value'] ?? '' }}" @if(!empty($field['required'])) required @endif />
                                                    @endif
                                                </div>
                                            @endforeach
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    @endif
                                    @break
                                @default
                                    {!! $data['html'] ?? '' !!}
                            @endswitch
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </main>
</body>


<script>
        const goal = document.getElementById('goal').value;
        const raised = document.getElementById('raised').value;

        const fill = document.getElementById('fill');
        const bar = document.querySelector('.bar');

        function updateFill() {
        const barWidth = bar.clientWidth;
        const fillWidth = Math.min(raised / goal, 1) * barWidth;
        fill.style.width = `${fillWidth}px`;
        }

        updateFill();
        window.addEventListener('resize', updateFill);

        document.getElementById('goal-label').textContent = `Goal: $${goal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('raised-label').textContent = `Raised: $${raised.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
</script>

<script>
    (() => {
        "use strict";

        // Utility functions grouped into a single object
        const Utils = {
            // Parse pixel values to numeric values
            parsePx: (value) => parseFloat(value.replace(/px/, "")),

            // Generate a random number between two values, optionally with a fixed precision
            getRandomInRange: (min, max, precision = 0) => {
            const multiplier = Math.pow(10, precision);
            const randomValue = Math.random() * (max - min) + min;
            return Math.floor(randomValue * multiplier) / multiplier;
            },

            // Pick a random item from an array
            getRandomItem: (array) => array[Math.floor(Math.random() * array.length)],

            // Scaling factor based on screen width
            getScaleFactor: () => Math.log(window.innerWidth) / Math.log(1920),

            // Debounce function to limit event firing frequency
            debounce: (func, delay) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), delay);
            };
            },
        };

        // Precomputed constants
        const DEG_TO_RAD = Math.PI / 180;

        // Centralized configuration for default values
        const defaultConfettiConfig = {
            confettiesNumber: 120,
            confettiRadius: 4,
            confettiColors: [
            "#2e4053", "#b7bcc4"
            ],
            emojies: [],
            svgIcon: null, // Example SVG link
        };

        // Confetti class representing individual confetti pieces
        class Confetti {
            constructor({ initialPosition, direction, radius, colors, emojis, svgIcon }) {
            const speedFactor = Utils.getRandomInRange(0.9, 1.7, 3) * Utils.getScaleFactor();
            this.speed = { x: speedFactor, y: speedFactor };
            this.finalSpeedX = Utils.getRandomInRange(0.2, 0.6, 3);
            this.rotationSpeed = emojis.length || svgIcon ? 0.01 : Utils.getRandomInRange(0.03, 0.07, 3) * Utils.getScaleFactor();
            this.dragCoefficient = Utils.getRandomInRange(0.0005, 0.0009, 6);
            this.radius = { x: radius, y: radius };
            this.initialRadius = radius;
            this.rotationAngle = direction === "left" ? Utils.getRandomInRange(0, 0.2, 3) : Utils.getRandomInRange(-0.2, 0, 3);
            this.emojiRotationAngle = Utils.getRandomInRange(0, 2 * Math.PI);
            this.radiusYDirection = "down";

            const angle = direction === "left" ? Utils.getRandomInRange(82, 15) * DEG_TO_RAD : Utils.getRandomInRange(-15, -82) * DEG_TO_RAD;
            this.absCos = Math.abs(Math.cos(angle));
            this.absSin = Math.abs(Math.sin(angle));

            const offset = Utils.getRandomInRange(-150, 0);
            const position = {
                x: initialPosition.x + (direction === "left" ? -offset : offset) * this.absCos,
                y: initialPosition.y - offset * this.absSin
            };

            this.position = { ...position };
            this.initialPosition = { ...position };
            this.color = emojis.length || svgIcon ? null : Utils.getRandomItem(colors);
            this.emoji = emojis.length ? Utils.getRandomItem(emojis) : null;
            this.svgIcon = null;

            // Preload SVG if provided
            if (svgIcon) {
                this.svgImage = new Image();
                this.svgImage.src = svgIcon;
                this.svgImage.onload = () => {
                this.svgIcon = this.svgImage; // Mark as ready once loaded
                };
            }

            this.createdAt = Date.now();
            this.direction = direction;
            }

            draw(context) {
            const { x, y } = this.position;
            const { x: radiusX, y: radiusY } = this.radius;
            const scale = window.devicePixelRatio;

            if (this.svgIcon) {
                context.save();
                context.translate(scale * x, scale * y);
                context.rotate(this.emojiRotationAngle);
                context.drawImage(this.svgIcon, -radiusX, -radiusY, radiusX * 2, radiusY * 2);
                context.restore();
            } else if (this.color) {
                context.fillStyle = this.color;
                context.beginPath();
                context.ellipse(x * scale, y * scale, radiusX * scale, radiusY * scale, this.rotationAngle, 0, 2 * Math.PI);
                context.fill();
            } else if (this.emoji) {
                context.font = `${radiusX * scale}px serif`;
                context.save();
                context.translate(scale * x, scale * y);
                context.rotate(this.emojiRotationAngle);
                context.textAlign = "center";
                context.fillText(this.emoji, 0, radiusY / 2); // Adjust vertical alignment
                context.restore();
            }
            }

            updatePosition(deltaTime, currentTime) {
            const elapsed = currentTime - this.createdAt;

            if (this.speed.x > this.finalSpeedX) {
                this.speed.x -= this.dragCoefficient * deltaTime;
            }

            this.position.x += this.speed.x * (this.direction === "left" ? -this.absCos : this.absCos) * deltaTime;
            this.position.y = this.initialPosition.y - this.speed.y * this.absSin * elapsed + 0.00125 * Math.pow(elapsed, 2) / 2;

            if (!this.emoji && !this.svgIcon) {
                this.rotationSpeed -= 1e-5 * deltaTime;
                this.rotationSpeed = Math.max(this.rotationSpeed, 0);

                if (this.radiusYDirection === "down") {
                this.radius.y -= deltaTime * this.rotationSpeed;
                if (this.radius.y <= 0) {
                    this.radius.y = 0;
                    this.radiusYDirection = "up";
                }
                } else {
                this.radius.y += deltaTime * this.rotationSpeed;
                if (this.radius.y >= this.initialRadius) {
                    this.radius.y = this.initialRadius;
                    this.radiusYDirection = "down";
                }
                }
            }
            }

            isVisible(canvasHeight) {
            return this.position.y < canvasHeight + 100;
            }
        }

        class ConfettiManager {
            constructor() {
            this.canvas = document.createElement("canvas");
            this.canvas.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; pointer-events: none;";
            document.body.appendChild(this.canvas);
            this.context = this.canvas.getContext("2d");
            this.confetti = [];
            this.lastUpdated = Date.now();
            window.addEventListener("resize", Utils.debounce(() => this.resizeCanvas(), 200));
            this.resizeCanvas();
            requestAnimationFrame(() => this.loop());
            }

            resizeCanvas() {
            this.canvas.width = window.innerWidth * window.devicePixelRatio;
            this.canvas.height = window.innerHeight * window.devicePixelRatio;
            }

            addConfetti(config = {}) {
            const { confettiesNumber, confettiRadius, confettiColors, emojies, svgIcon } = {
                ...defaultConfettiConfig,
                ...config,
            };

            const baseY = (5 * window.innerHeight) / 7;
            for (let i = 0; i < confettiesNumber / 2; i++) {
                this.confetti.push(new Confetti({
                initialPosition: { x: 0, y: baseY },
                direction: "right",
                radius: confettiRadius,
                colors: confettiColors,
                emojis: emojies,
                svgIcon,
                }));
                this.confetti.push(new Confetti({
                initialPosition: { x: window.innerWidth, y: baseY },
                direction: "left",
                radius: confettiRadius,
                colors: confettiColors,
                emojis: emojies,
                svgIcon,
                }));
            }
            }

            resetAndStart(config = {}) {
            // Clear existing confetti
            this.confetti = [];
            // Add new confetti
            this.addConfetti(config);
            }

            loop() {
            const currentTime = Date.now();
            const deltaTime = currentTime - this.lastUpdated;
            this.lastUpdated = currentTime;

            this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

            this.confetti = this.confetti.filter((item) => {
                item.updatePosition(deltaTime, currentTime);
                item.draw(this.context);
                return item.isVisible(this.canvas.height);
            });

            requestAnimationFrame(() => this.loop());
            }
        }

        // Trigger confetti 5 times
        function triggerConfettiMultipleTimes(times, delay) {
            let count = 0;
            const intervalId = setInterval(() => {
                const manager = new ConfettiManager();
        // manager.addConfetti();
                manager.addConfetti(); // Trigger confetti
                count++;
                if (count >= times) {
                    clearInterval(intervalId); // Stop after triggering 5 times
                }
            }, delay);
        }

        triggerConfettiMultipleTimes(5, 500);



        const triggerButton = document.getElementById("show-again");
        if (triggerButton) {
            triggerButton.addEventListener("click", () => manager.addConfetti());
        }

        const resetInput = document.getElementById("reset");
        if (resetInput) {
            resetInput.addEventListener("input", () => manager.resetAndStart());
        }
        })();



</script>

<script>
da = document.getElementById("time").value;
// Set the target date for the countdown
const targetDate = new Date(da).getTime();

function updateCountdown() {
const now = new Date().getTime();
const timeLeft = targetDate - now;

if (timeLeft <= 0) {
    document.getElementById("months").textContent = 0;
    document.getElementById("days").textContent = 0;
    document.getElementById("hours").textContent = 0;
    document.getElementById("minutes").textContent = 0;
    document.getElementById("seconds").textContent = 0;
    return;
}

// Calculate time components
const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

// Update the HTML
document.getElementById("months").textContent = months;
document.getElementById("days").textContent = days;
document.getElementById("hours").textContent = hours;
document.getElementById("minutes").textContent = minutes;
document.getElementById("seconds").textContent = seconds;
}

// Update the countdown every second
setInterval(updateCountdown, 1000);
</script>
<!-- Include DataTables and jQuery CDN -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with default search disabled
        const table = $('#studentTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });

        // Link the custom search input to the DataTable search
        $('#search').on('keyup', function() {
            const value = $(this).val();
            table.search(value).draw();
        });
    });
</script>

<script>
    slidesToShow = $('#sliderPreview').data('slides-to-show') || 12; // Default to 3 if not set
    $('#sliderPreview').owlCarousel({
        items: slidesToShow,
        loop: true,
        margin: 10,
        // nav: true,
        // dots: true,
        autoplay: true,
    });
</script>

<script>
    function toggleGroupSelect(sel) {
        var wrapper = document.getElementById('group_select_wrapper');
        var wrapper_name = document.getElementById('group_name_wrapper');
        if (sel.value === 'group') {
            wrapper.style.display = '';
            wrapper_name.style.display = 'none';
        }else if (sel.value === 'group_leader') {
            wrapper_name.style.display = '';
            wrapper.style.display = 'none';
        } else {
            wrapper.style.display = 'none';
            wrapper_name.style.display = 'none';
        }
    }
</script>


</html>

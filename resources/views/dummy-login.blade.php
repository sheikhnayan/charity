<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <style>

    .non-float{
        margin-bottom: -111px;
    }
    p {
        font-size: 1rem;
        line-height: 1.5;
        font-family: AvenirLTPro-Black,sans-serif;
        margin-bottom: 1.5rem;
    }

    .c-node-ap__auction-results{
        margin-right: 36px;
        margin-bottom: 24px;
        display: inline-block;
        background-color: #f8f9fa;
        border-color: #DBDCDD;
        border: 1px solid;
        border-radius: 4px;
        padding: 24px;
        font-size: 1rem;
    }

    .c-node-ap__fundraising-target{
        margin-bottom: 12px;
    }

    .c-node-ap__auction-total-label {
        margin-bottom: 12px;
        font-size: 1.25rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #355159
    }
    .c-node-ap__auction-total-amount {
        font-size: 2rem;
        line-height: 1.5;
        color: #d9b730;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
    }

    .c-node-ap__totalizer{
        height: 18px;
        border-radius: 12px;
        --color-ui: #d9b730;
    }

    .c-node-ap__auction-total-component-label{
        color: #6d6e71
    }

    .c-node-ap__auction-total-component-amount{
        font-size: 1rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #000
    }
    .c-view__item.c-view__item--teaser {
        width: 100% !important;
        max-width: 100% !important;
        flex-basis: 100% !important;
        min-width: 330px !important;
    }
</style>
</head>
<body style="background-color: #fff;">
    @php
$url = url()->current();
$doamin = parse_url($url, PHP_URL_HOST);
$check = \App\Models\Website::where('domain', $doamin)->first();
$groups = \App\Models\User::where('website_id', $check->id)->where('role', 'group_leader')->get();
$header = \App\Models\Header::where('website_id', $check->id)->first();
$setting = \App\Models\Setting::where('user_id', $check->user_id)->first();
$user = \App\Models\User::where('id', $check->user_id)->first();
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <main style="padding: 5rem; padding-top: 0rem; margin-top: 7rem; max-width: 90em; margin-left: auto; margin-right: auto; background-color: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="#" method="post">
                        <h4>This content is protected. To view it please enter your password below:</h4>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
function startAuctionTimer(deadline, id) {
    function update() {
        const now = new Date().getTime();
        const target = new Date(deadline).getTime();
        let timeLeft = target - now;

        if (timeLeft <= 0) {
            document.getElementById('days-' + id).textContent = 0;
            document.getElementById('hours-' + id).textContent = 0;
            document.getElementById('minutes-' + id).textContent = 0;
            document.getElementById('seconds-' + id).textContent = 0;
            return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        document.getElementById('days-' + id).textContent = days;
        document.getElementById('hours-' + id).textContent = hours;
        document.getElementById('minutes-' + id).textContent = minutes;
        document.getElementById('seconds-' + id).textContent = seconds;
    }
    update();
    setInterval(update, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    @foreach ($data as $item)
        startAuctionTimer("{{ $item->dead_line }}", "{{ $item->id }}");
    @endforeach
});
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
import { getFirestore, collection, query, where, orderBy, getDocs, limit } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

// Your Firebase config
const firebaseConfig = {
    apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
    authDomain: "charity-390ca.firebaseapp.com",
    projectId: "charity-390ca",
    storageBucket: "charity-390ca.firebasestorage.app",
    messagingSenderId: "875958450032",
    appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
    measurementId: "G-FC73HL5XF3"
};

const app = initializeApp(firebaseConfig);
const firestore = getFirestore(app);

document.addEventListener('DOMContentLoaded', async function() {
    @foreach ($data as $item)
        {
            const auctionId = "{{ $item->id }}";
            const priceDiv = document.getElementById('auction-price-{{ $item->id }}');
            if (priceDiv) {
                const bidsRef = collection(firestore, "bid");
                const q = query(
                    bidsRef,
                    where("auction_id", "==", auctionId),
                    orderBy("amount", "desc"),
                    limit(1)
                );
                const querySnapshot = await getDocs(q);
                if (!querySnapshot.empty) {
                    const doc = querySnapshot.docs[0];
                    const latestAmount = doc.data().amount;
                    priceDiv.textContent = '$' + latestAmount;
                }
            }
        }
    @endforeach
});
</script>

@extends('user.main')

@section('content')
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

<style>
    .dt-buttons button span {
        color: #000 !important;
    }

    .paginate_buttons a {
        color: #000 !important;
    }
</style>

@php
    $globalPayment = \App\Models\PaymentSetting::first();
    $defaultFee = $globalPayment ? $globalPayment->fee : 2.9;
@endphp
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-12 mb-6 order-0">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">

                                <div class="page-title-icon">
                                    <i class="fas fa-credit-card icon-gradient bg-arielle-smile"></i>
                                </div>

                                <div>
                                    <span class="text-capitalize">
                                        Payments
                                    </span>
                                    <div class="page-title-subheading">
                                        View all payments made by you.
                                    </div>
                                </div>

                            </div>
                            <div class="page-title-actions">
                                @if(Auth::user()->role == 'parents')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                        <i class="fas fa-plus me-2"></i>Add Participants
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="page-title-subheading opacity-10 mt-3"
                            style="white-space: nowrap; overflow-x: auto;">
                            <nav class="" aria-label="breadcrumb">
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item opacity-10">
                                        <a href="#">
                                            <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                            <span class="visually-hidden">Home</span>
                                        </a>
                                        <i class="fas fa-chevron-right ms-1"></i>
                                    </li>

                                    <li class="breadcrumb-item ">
                                        Reports
                                        <i class="fas fa-chevron-right ms-1"></i>
                                    </li>
                                    <li class="active breadcrumb-item" aria-current="page">
                                        Payments
                                    </li>

                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg">
                            <div class="card-shadow-primary card-border text-white mb-3 card bg-primary p-4" style="background: #fff !important;">
                                <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Transaction ID</th>
                                                <th>Donor Name</th>
                                                <th>Individual Name</th>
                                                <th>Team Name</th>
                                                <th>Amount Entered</th>
                                                <th>Platform Fee</th>
                                                <th>Tip Amount</th>
                                                <th>Total Amount</th>
                                                {{-- <th>Amount Net</th> --}}
                                                <th>Payment Method</th>
                                                <th>Website</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="16" class="text-center">No payments found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td><input type="checkbox" class="row-check" value="{{ $item->id }}"></td>
                                                        <td class="text-break"> {{ $item->transaction_id }} </td>
                                                        <td>{{ $item->first_name ?? $item->name }} {{ $item->last_name }}</td>
                                                        @if ($item->type == 'student')
                                                            <td>{{ $item->donation->user->name ?? null}}</td>
                                                        @elseif($item->type == 'general')
                                                            <td>{{ $item->website->name }}</td>
                                                        @elseif($item->type == 'sponsor')
                                                            <td>{{ $item->name }}</td>
                                                        @elseif($item->type == 'auction')
                                                            <td>{{ $item->auction->title }}</td>
                                                        @elseif($item->type == 'ticket')
                                                            <td>@if ($item->ticket->details[0]->ticket)
                                                                {{ $item->ticket->details[0]->ticket->name }}
                                                                @else
                                                                N/A
                                                            @endif

                                                            </td>
                                                            {{-- <td>{{ $item->ticket->details[0]->ticket->name }}</td> --}}
                                                        @elseif ($item->type == 'investment')
                                                            <td>{{ $item->investment->investor_name }}</td>
                                                        @elseif ($item->type == 'product')
                                                            <td>{{ $item->name }}</td>
                                                        @endif
                                                        @if ($item->type == 'student')
                                                            <td>{{ $item->donation->user->group_name ?? null}}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        @if ($item->type == 'investment')
                                                        <td>${{ number_format($item->amount, 2) }}</td>
                                                            
                                                        @else
                                                        <td>${{ number_format($item->amount, 2) }}</td>
                                                            
                                                        @endif
                                                        <td>
                                                            @php
                                                                // Calculate fee for Donation objects, use existing fee for Transaction objects
                                                                if (isset($item->fee)) {
                                                                    $fee = $item->fee;
                                                                } else {
                                                                    // For Donation objects, calculate fee based on website settings
                                                                    $website = \App\Models\Website::find($item->website_id);
                                                                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                                                                    $fee = ($item->amount / 100) * $processingFeePercentage;
                                                                }
                                                            @endphp
                                                            ${{ number_format($fee, 2) }}
                                                        </td>
                                                        <td>${{ number_format($item->tip_amount ?? 0, 2) }}</td>
                                                        @if ($item->type == 'investment')
                                                        <td>${{ number_format($item->amount + $fee + ($item->tip_amount ?? 0), 2) }}</td>
                                                        @else
                                                            
                                                        <td>${{ number_format($item->amount + $fee + ($item->tip_amount ?? 0), 2) }}</td>
                                                        @endif
                                                        {{-- <td>${{ number_format($item->amount, 2) }}</td> --}}
                                                        <td>
                                                            @if ($item->type != 'sponsor')
                                                            @if ($item->transaction_id)
                                                                {{ ctype_digit($item->transaction_id[0]) ? 'Authorize.net' : 'Stripe' }}
                                                            @endif
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->website->name }}</td>
                                                        <td>{{ $item->type }}</td>
                                                        <td>
                                                            @if ($item->type == 'auction')
                                                                Pending
                                                            @elseif ($item->status == 1)
                                                                Approved
                                                            @else
                                                                Pending
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d h:i A') }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-sm view-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewDonationModal"
                                                                data-transaction="{{ $item->transaction_id }}"
                                                                data-ip-address="{{ $item->ip_address ?? 'N/A' }}"
                                                                data-first-name="{{ $item->first_name ?? $item->name }}"
                                                                data-last-name="{{ $item->last_name }}"
                                                                data-email="{{ $item->email }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-address="{{ $item->apartment }}, {{ $item->address }}, {{ $item->state }}, {{ $item->city }}, {{ $item->zip }} {{ $item->country }}"
                                                                @if ($item->type == 'investment')
                                                                data-gross="${{ number_format($item->amount, 2) }}"                                                                    
                                                                @else
                                                                data-gross="${{ number_format($item->amount, 2) }}"                                                                    
                                                                @endif
                                                                data-fee="${{ number_format($fee, 2) }}"
                                                                data-tip-amount="${{ number_format($item->tip_amount ?? 0, 2) }}"
                                                                data-status="{{ $item->type == 'auction' ? 'Pending' : ($item->status == 1 ? 'Approved' : 'Pending') }}"
                                                                data-website="{{ $item->website->name }}"
                                                                data-type="{{ $item->type }}"
                                                                data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d h:i A') }}"
                                                                data-timestamp="{{ $item->created_at->getTimestamp() }}"
                                                                @if($item->type === 'investment' && $item->investment)
                                                                    data-investor-name="{{ $item->investment->investor_name ?? 'N/A' }}"
                                                                    data-investor-email="{{ $item->investment->investor_email ?? 'N/A' }}"
                                                                    data-investor-phone="{{ $item->investment->investor_phone ?? 'N/A' }}"
                                                                    data-investor-type="{{ $item->investment->investor_type ?? 'N/A' }}"
                                                                    data-share-quantity="{{ $item->investment->share_quantity ?? 'N/A' }}"
                                                                    data-investment-amount="${{ number_format($item->investment->investment_amount ?? 0, 2) }}"
                                                                    data-investment-notes="{{ $item->investment->notes ?? 'N/A' }}"
                                                                    data-investor-data="{{ $item->investment->investor_data ? json_encode($item->investment->investor_data) : '{}' }}"
                                                                @endif
                                                                data-payment-first-name="{{ $item->payment_first_name ?? $item->name }}"
                                                                data-payment-last-name="{{ $item->payment_last_name ?? $item->last_name }}"
                                                                data-payment-phone="{{ $item->payment_phone ?? $item->phone }}"
                                                                data-payment-email="{{ $item->payment_email ?? $item->email }}"
                                                                data-payment-address="{{ $item->payment_address ?? $item->address }}"
                                                                data-payment-city="{{ $item->payment_city ?? $item->city }}"
                                                                data-payment-state="{{ $item->payment_state ?? $item->state }}"
                                                                data-payment-country="{{ $item->payment_country ?? $item->country }}"
                                                                data-payment-zip="{{ $item->payment_zip_code ?? $item->zip }}"
                                                                data-total-amount="${{ number_format($item->total_amount ?? $item->amount, 2) }}"
                                                                data-total-due="${{ number_format($item->total_due ?? 0, 2) }}"
                                                                @if ($item->type == 'investment')
                                                                data-total-paid="${{ number_format($item->amount + $fee, 2) }}"
                                                                @else
                                                                data-total-paid="${{ number_format($item->amount + $item->fee, 2) }}"
                                                                @endif
                
                
                                                                title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="8" class="text-end">Total:</th>
                                                <th id="amount-total"></th>
                                                <th colspan="6"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- View Transaction Modal -->
    <div class="modal fade" id="viewDonationModal" tabindex="-1" aria-labelledby="viewDonationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewDonationModalLabel">Transaction Details</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" id="downloadPdfBtn">
                    <i class="fas fa-download"></i> Download PDF
                </button>
                <button type="button" class="btn btn-info btn-sm" id="resendInvoiceBtn">
                    <i class="fas fa-envelope"></i> Resend Invoice
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                <!-- Transaction Details Column -->
                <div class="col-md-6">
                    <h6 class="mb-3 text-primary">Transaction Details</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Transaction ID:</strong> <span id="modal-transaction"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>IP Address:</strong> <span id="modal-ip-address"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>First Name:</strong> <span id="modal-first-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Last Name:</strong> <span id="modal-last-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Email:</strong> <span id="modal-email"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Phone:</strong> <span id="modal-phone"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Type:</strong> <span id="modal-type"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Status:</strong> <span id="modal-status"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Website ID:</strong> <span id="modal-website"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Date:</strong> <span id="modal-date"></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Payment Information Column -->
                <div class="col-md-6">
                    <h6 class="mb-3 text-success">Payment Information</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment First Name:</strong> <span id="modal-payment-first-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment Last Name:</strong> <span id="modal-payment-last-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment Phone:</strong> <span id="modal-payment-phone"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment Email:</strong> <span id="modal-payment-email"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment Address:</strong> <span id="modal-payment-address"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment City:</strong> <span id="modal-payment-city"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment State:</strong> <span id="modal-payment-state"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment Country:</strong> <span id="modal-payment-country"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Payment Zip Code:</strong> <span id="modal-payment-zip"></span>
                        </li>
                        {{-- <li class="list-group-item d-flex justify-content-between">
                            <strong>Amount Entered:</strong> <span id="modal-total-amount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total Due:</strong> <span id="modal-total-due"></span>
                        </li> --}}
                    </ul>
                </div>
            </div>
            
            <!-- Investment Information (Only shown for investment type) -->
            <div class="row mt-4" id="investment-section" style="display: none;">
                <div class="col-12">
                    <h6 class="mb-3 text-warning">Investment Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Investor Name:</strong> <span id="modal-investor-name"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Investor Email:</strong> <span id="modal-investor-email"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Investor Phone:</strong> <span id="modal-investor-phone"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Investor Type:</strong> <span id="modal-investor-type"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Share Quantity:</strong> <span id="modal-share-quantity"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Investment Amount:</strong> <span id="modal-investment-amount"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Notes:</strong>
                                    <div id="modal-investment-notes" class="mt-2"></div>
                                </li>
                                <li class="list-group-item">
                                    <strong>Investor Data:</strong>
                                    <div id="modal-investor-data" class="mt-2 small"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Financial Details -->
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="mb-3 text-info">Financial Breakdown</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Amount Entered:</strong> <span id="modal-gross"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Platform Fee:</strong> <span id="modal-fee"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Tip Amount:</strong> <span id="modal-tip-amount"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total Amount Paid:</strong> <span id="modal-total-paid"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="d-flex justify-content-between w-100">
                {{-- <div>
                    <button type="button" class="btn btn-success btn-sm status-btn" data-status="completed">
                        <i class="fas fa-check"></i> Mark Completed
                    </button>
                    <button type="button" class="btn btn-warning btn-sm status-btn" data-status="cancelled">
                        <i class="fas fa-times"></i> Mark Cancelled
                    </button>
                    <button type="button" class="btn btn-danger btn-sm status-btn" data-status="refunded">
                        <i class="fas fa-undo"></i> Mark Refunded
                    </button>
                </div> --}}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    color: #000 !important;
                }
    </style>
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <!-- Moment.js (MUST be before daterangepicker) -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <!-- Date Range Picker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            // Only initialize DataTable if there are rows with data
            @if (!$data->isEmpty())
            let table = new DataTable('.table', {
                dom: 'Bfrtip',
                pageLength: 25,
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            rows: function(idx, data, node) {
                                let checked = $('.row-check:checked');
                                if (checked.length === 0) return true; // export all if none checked
                                return $(node).find('.row-check').prop('checked');
                            },
                            columns: ':visible:not(:first-child):not(:last-child)' // Exclude checkbox and action columns
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Export Excel',
                        exportOptions: {
                            rows: function(idx, data, node) {
                                let checked = $('.row-check:checked');
                                if (checked.length === 0) return true;
                                return $(node).find('.row-check').prop('checked');
                            },
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF',
                        exportOptions: {
                            rows: function(idx, data, node) {
                                let checked = $('.row-check:checked');
                                if (checked.length === 0) return true;
                                return $(node).find('.row-check').prop('checked');
                            },
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            rows: function(idx, data, node) {
                                let checked = $('.row-check:checked');
                                if (checked.length === 0) return true;
                                return $(node).find('.row-check').prop('checked');
                            },
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    }
                ]
            });



            // Checklist: Select all
            $('#selectAll').on('change', function() {
                $('.row-check').prop('checked', this.checked);
            });

            function updateAmountTotal() {
                let total = 0;
                table.rows({ search: 'applied' }).every(function () {
                    let data = this.data();
                    let amountCell = data[8]; // Column 8: Total Amount (0-indexed, after adding Tip Amount column)
                    // Remove HTML tags if present
                    let tempDiv = document.createElement('div');
                    tempDiv.innerHTML = amountCell;
                    let text = tempDiv.textContent || tempDiv.innerText || "";
                    // Remove $ and commas, parse as float
                    let amount = parseFloat(text.replace(/[^0-9.-]+/g,"")) || 0;
                    total += amount;
                });
                $('#amount-total').html('$' + total.toLocaleString(undefined, {minimumFractionDigits: 2}));

                console.log('s');

            }

            table.on('draw', updateAmountTotal);
            updateAmountTotal();
            @endif
        });
        </script>

        <script>
        let currentTransactionData = {};
        
        function getUTCOffset(date, timeZone) {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: timeZone,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            
            const parts = formatter.formatToParts(date);
            const timeZoneDate = new Date(
                parts.find(p => p.type === 'year').value,
                parts.find(p => p.type === 'month').value - 1,
                parts.find(p => p.type === 'day').value,
                parts.find(p => p.type === 'hour').value,
                parts.find(p => p.type === 'minute').value,
                parts.find(p => p.type === 'second').value
            );
            
            const offset = date.getTime() - timeZoneDate.getTime();
            const hours = Math.floor(Math.abs(offset) / 3600000);
            const minutes = Math.floor((Math.abs(offset) % 3600000) / 60000);
            const sign = offset <= 0 ? '+' : '-';
            
            return sign + String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
        }
        
        $(document).on('click', '.view-btn', function() {
            const $btn = $(this);
            currentTransactionData = $btn.data();
            
            // Get transaction date and timestamp
            const transactionDate = $btn.data('date') || 'N/A';
            const timestamp = $btn.data('timestamp');
            
            // Display date
            let dateText = transactionDate;
            
            // Calculate timezone from transaction timestamp
            if (timestamp) {
                const appTimezone = '{{ config('app.timezone') }}';
                const date = new Date(timestamp * 1000);
                const formatter = new Intl.DateTimeFormat('en-US', {
                    timeZone: appTimezone,
                    timeZoneName: 'short'
                });
                const parts = formatter.formatToParts(date);
                const tzPart = parts.find(p => p.type === 'timeZoneName');
                const tzName = tzPart ? tzPart.value : 'UTC';
                const offset = getUTCOffset(date, appTimezone);
                dateText += ` (${tzName} ${offset})`;
            }
            
            $('#modal-date').text(dateText);
            
            // Basic transaction details
            $('#modal-transaction').text($btn.data('transaction') || 'N/A');
            $('#modal-ip-address').text($btn.data('ip-address') || 'N/A');
            $('#modal-first-name').text($btn.data('first-name') || 'N/A');
            $('#modal-last-name').text($btn.data('last-name') || 'N/A');
            $('#modal-email').text($btn.data('email') || 'N/A');
            $('#modal-phone').text($btn.data('phone') || 'N/A');
            $('#modal-type').text($btn.data('type') || 'N/A');
            $('#modal-status').text($btn.data('status') || 'N/A');
            $('#modal-website').text($btn.data('website') || 'N/A');
            
            // Payment information
            $('#modal-payment-first-name').text($btn.data('payment-first-name') || 'N/A');
            $('#modal-payment-last-name').text($btn.data('payment-last-name') || 'N/A');
            $('#modal-payment-phone').text($btn.data('payment-phone') || 'N/A');
            $('#modal-payment-email').text($btn.data('payment-email') || 'N/A');
            $('#modal-payment-address').text($btn.data('payment-address') || 'N/A');
            $('#modal-payment-city').text($btn.data('payment-city') || 'N/A');
            $('#modal-payment-state').text($btn.data('payment-state') || 'N/A');
            $('#modal-payment-country').text($btn.data('payment-country') || 'N/A');
            $('#modal-payment-zip').text($btn.data('payment-zip') || 'N/A');
            
            // Financial details
            const grossAmount = parseFloat(($btn.data('gross') || '$0.00').replace(/[$,]/g, '')) || 0;
            const feeAmount = parseFloat(($btn.data('fee') || '$0.00').replace(/[$,]/g, '')) || 0;
            const tipAmount = parseFloat(($btn.data('tip-amount') || '$0.00').replace(/[$,]/g, '')) || 0;
            
            // Calculate totals
            const totalAmountWithFee = grossAmount + feeAmount; // For Payment Information section
            const totalPaid = grossAmount + feeAmount + tipAmount; // For Financial Breakdown section
            
            $('#modal-gross').text('$' + grossAmount.toFixed(2));
            $('#modal-fee').text('$' + feeAmount.toFixed(2));
            $('#modal-tip-amount').text('$' + tipAmount.toFixed(2));
            $('#modal-total-amount').text('$' + grossAmount.toFixed(2)); // Total Amount (gross + fee)
            $('#modal-total-due').text($btn.data('total-due') || '$0.00');
            $('#modal-total-paid').text('$' + totalPaid.toFixed(2)); // Total Paid (gross + fee + tip)
            
            // Show/hide investment section - always show payment info, only show investment details for investment type
            if ($btn.data('type') === 'investment') {
                $('#investment-section').show();
                $('#modal-investor-name').text($btn.data('investor-name') || 'N/A');
                $('#modal-investor-email').text($btn.data('investor-email') || 'N/A');
                $('#modal-investor-phone').text($btn.data('investor-phone') || 'N/A');
                $('#modal-investor-type').text($btn.data('investor-type') || 'N/A');
                $('#modal-share-quantity').text($btn.data('share-quantity') || 'N/A');
                $('#modal-investment-amount').text($btn.data('investment-amount') || '$0.00');
                $('#modal-investment-notes').text($btn.data('investment-notes') || 'N/A');
                
                // Parse and display investor data
                try {
                    let investorData = $btn.data('investor-data');
                    if (typeof investorData === 'string') {
                        investorData = JSON.parse(investorData);
                    }
                    if (investorData && typeof investorData === 'object') {
                        let dataHtml = '<div class="border p-2 rounded bg-light">';
                        Object.keys(investorData).forEach(key => {
                            dataHtml += `<div><strong>${key}:</strong> ${investorData[key]}</div>`;
                        });
                        dataHtml += '</div>';
                        $('#modal-investor-data').html(dataHtml);
                    } else {
                        $('#modal-investor-data').text('No additional data available');
                    }
                } catch (e) {
                    $('#modal-investor-data').text('Invalid data format');
                }
            } else {
                $('#investment-section').hide();
            }
        });
        
        // PDF Download functionality
        $('#downloadPdfBtn').on('click', function() {
            const transactionId = $('#modal-transaction').text();
            if (transactionId && transactionId !== 'N/A') {
                window.open(`/admins/transactions/${transactionId}/download-invoice`, '_blank');
            } else {
                alert('Transaction ID not found');
            }
        });

        // Resend Invoice functionality
        $('#resendInvoiceBtn').on('click', function() {
            const transactionId = $('#modal-transaction').text();
            const email = $('#modal-email').text();
            
            if (transactionId && transactionId !== 'N/A') {
                if (confirm(`Are you sure you want to resend the invoice to ${email}?`)) {
                    $.ajax({
                        url: `/admins/transactions/${transactionId}/resend-invoice`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert('Invoice resent successfully!');
                        },
                        error: function(error) {
                            alert('Error resending invoice');
                        }
                    });
                }
            } else {
                alert('Transaction ID not found');
            }
        });
        </script>
</div>

<!-- Add Student Modal -->
@if(Auth::user()->role == 'parents')
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addStudentForm" action="{{ route('parent.add-student') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add Participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                        <div class="form-text">Credentials are automatically generated for system use only and are not shared or tracked outside the fundraiser.</div>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                        <select class="form-select teacher-select" id="teacher_id" name="teacher_id" required>
                            <option value="">Choose a teacher</option>
                            @if(isset($teachers))
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_goal" class="form-label">Fundraising Goal</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="modal_goal" name="goal" min="0" step="0.01">
                            <span class="input-group-text">.00 USD</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modal_tshirt_size" class="form-label">T-Shirt Size</label>
                        <select class="form-select" id="modal_tshirt_size" name="tshirt_size">
                            <option value="">Select a size</option>
                            <option value="Youth XS">Youth XS</option>
                            <option value="Youth Small">Youth Small</option>
                            <option value="Youth Medium">Youth Medium</option>
                            <option value="Youth Large">Youth Large</option>
                            <option value="Adult Small">Adult Small</option>
                            <option value="Adult Medium">Adult Medium</option>
                            <option value="Adult Large">Adult Large</option>
                            <option value="Adult XL">Adult XL</option>
                            <option value="Adult XXL">Adult XXL</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_description" class="form-label">Profile Description</label>
                        <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="modal_photo" class="form-label">Upload Photo</label>
                        <input class="form-control" type="file" id="modal_photo" name="photo" accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                        <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                        <div class="invalid-feedback" id="modal_photo_error" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Wait for jQuery and Select2 to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Check if jQuery and Select2 are loaded
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            // Initialize Select2 for teacher select with search
            jQuery('.teacher-select').select2({
                placeholder: 'Search and select a teacher',
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>

<!-- Photo Upload Validation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('modal_photo');
        const photoError = document.getElementById('modal_photo_error');
        const form = document.getElementById('addStudentForm');
        let hasFileError = false;
        
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                // Clear previous errors
                photoInput.classList.remove('is-invalid');
                photoError.style.display = 'none';
                photoError.textContent = '';
                hasFileError = false;
                
                if (file) {
                    // Check file size (5MB = 5 * 1024 * 1024 bytes)
                    const maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        photoInput.classList.add('is-invalid');
                        photoError.style.display = 'block';
                        photoError.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                        photoInput.value = '';
                        hasFileError = true;
                        return;
                    }
                    
                    // Check file type
                    const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                    if (!allowedTypes.includes(file.type)) {
                        photoInput.classList.add('is-invalid');
                        photoError.style.display = 'block';
                        photoError.textContent = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
                        photoInput.value = '';
                        hasFileError = true;
                        return;
                    }
                }
            });
        }
        
        // Prevent form submission if there's a file error
        if (form) {
            form.addEventListener('submit', function(e) {
                if (hasFileError || photoInput.classList.contains('is-invalid')) {
                    e.preventDefault();
                    photoError.style.display = 'block';
                    photoError.textContent = photoError.textContent || 'Please fix the file upload error before submitting.';
                    return false;
                }
            });
        }
    });
</script>

@endsection

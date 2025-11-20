@extends('user.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

<style>
    .forms-wizard li.done em::before, .lnr-checkmark-circle::before {
  content: "\e87f";
}

.forms-wizard li.done em::before {
  display: block;
  font-size: 1.2rem;
  height: 42px;
  line-height: 40px;
  text-align: center;
  width: 42px;
}

.forms-wizard li.done em {
  font-family: Linearicons-Free;
}
</style>
@php
        $payment = \App\Models\PaymentSetting::first();
    @endphp
    <!-- Content wrapper -->
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
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            Transactions
                                        </span>
                                        <div class="page-title-subheading">
                                            View the received Transactions.
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
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
                                            Transactions
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary p-4" style="background: #fff !important;">
                                    <div class="row mb-3">
                                        @if (Auth::user()->role == 'user')
                                            <div class="col-md-3">
                                                <label>Filter by Type:</label>
                                                <select id="typeFilter" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="student">Student</option>
                                                    <option value="general">General</option>
                                                    <option value="sponsor">Sponsor</option>
                                                    <option value="auction">Auction</option>
                                                    <option value="ticket">Ticket</option>
                                                    <option value="investment">Investment</option>
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Transaction ID</th>
                                                {{-- <th>Donor Name</th> --}}
                                                <th>Name</th>
                                                {{-- <th>Individual Name</th> --}}
                                                <th>Product Name</th>
                                                {{-- <th>Team Name</th> --}}
                                                <th>Quantity</th>
                                                <th>Amount Gross</th>
                                                <th>Amount Entered</th>
                                                <th>Amount Net</th>
                                                <th>Processing Fee</th>
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
                                                    <td colspan="11" class="text-center">No donations found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td><input type="checkbox" class="row-check" value="{{ $item->id }}"></td>
                                                        <td class="text-break"> {{ $item->transaction_id }} </td>
                                                        <td>{{ $item->name }} {{ $item->last_name }}</td>
                                                        @if ($item->type == 'student')
                                                            <td>{{ $item->donation->user->name }}</td>
                                                        @elseif($item->type == 'general')
                                                            <td>{{ $item->website->name }}</td>
                                                        @elseif($item->type == 'sponsor')
                                                            <td>{{ $item->name }}</td>
                                                        @elseif($item->type == 'auction')
                                                            <td>{{ $item->auction->title }}</td>
                                                        @elseif($item->type == 'ticket')
                                                            <td>{{ $item->ticket->details[0]->ticket->name }}</td>
                                                        @elseif ($item->type == 'investment')
                                                            <td>{{ $item->investment->investor_name }}</td>
                                                        @endif
                                                        {{-- @if ($item->type == 'student')
                                                            <td>{{ $item->donation->user->group_name }}</td>
                                                        @else
                                                            <td></td>
                                                        @endif --}}
                                                        <td>
                                                            @php
                                                                $quantity = \App\Models\TicektSell::where('id', $item->reference_id)->first();
                                                            @endphp
                                                            {{ $quantity->quantity }}
                                                        </td>
                                                        <td>${{ $item->amount + (($item->amount / 100)*$payment->fee)}}</td>
                                                        <td>${{ $item->amount }}</td>
                                                        <td>${{ $item->amount }}</td>
                                                        <td>${{ ($item->amount / 100)*$payment->fee }}</td>
                                                        <td>
                                                            @if ($item->type != 'sponsor')
                                                            {{ ctype_digit($item->transaction_id[0]) ? 'Authorize.net' : 'Stripe' }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->website->name }}</td>
                                                        <td>{{ $item->type }}</td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                Approved
                                                            @else
                                                                Pending
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-sm view-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewDonationModal"
                                                                data-transaction="{{ $item->transaction_id }}"
                                                                data-ip-address="{{ $item->ip_address ?? 'N/A' }}"
                                                                data-first-name="{{ $item->name }}"
                                                                data-last-name="{{ $item->last_name }}"
                                                                data-email="{{ $item->email }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-address="{{ $item->apartment }}, {{ $item->address }}, {{ $item->state }}, {{ $item->city }}, {{ $item->zip }} {{ $item->country }}"
                                                                data-gross="${{ number_format($item->amount + (($item->amount / 100)*(($item->website->paymentSettings && $item->website->paymentSettings->fee) ? $item->website->paymentSettings->fee : $defaultFee)), 2) }}"
                                                                data-fee="${{ ($item->amount / 100)*(($item->website->paymentSettings && $item->website->paymentSettings->fee) ? $item->website->paymentSettings->fee : $defaultFee) }}"
                                                                data-status="{{ $item->status == 1 ? 'Approved' : 'Pending' }}"
                                                                data-website="{{ $item->website->name }}"
                                                                data-type="{{ $item->type }}"
                                                                data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}"
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
                                                                data-total-paid="${{ number_format($item->total_amount_paid ?? ($item->fee_paid ? $item->amount + (($item->amount / 100)*(($item->website->paymentSettings && $item->website->paymentSettings->fee) ? $item->website->paymentSettings->fee : $defaultFee)) : $item->amount), 2) }}"
                
                
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
                                                <th colspan="5" class="text-end">Total:</th>
                                                <th id="amount-total"></th>
                                                <th colspan="8"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total Amount:</strong> <span id="modal-total-amount"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total Due:</strong> <span id="modal-total-due"></span>
                                </li>
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
                                            <strong>Gross Amount:</strong> <span id="modal-gross"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Processing Fee:</strong> <span id="modal-fee"></span>
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
                        <div>
                            <button type="button" class="btn btn-success btn-sm status-btn" data-status="completed">
                                <i class="fas fa-check"></i> Mark Completed
                            </button>
                            <button type="button" class="btn btn-warning btn-sm status-btn" data-status="cancelled">
                                <i class="fas fa-times"></i> Mark Cancelled
                            </button>
                            <button type="button" class="btn btn-danger btn-sm status-btn" data-status="refunded">
                                <i class="fas fa-undo"></i> Mark Refunded
                            </button>
                        </div>
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
                    // $.fn.dataTable.ext.errMode = 'none';
                    // Initialize DataTable
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

                    // Type filter
                    $('#typeFilter').on('change', function() {
                        table.column(11).search(this.value).draw();
                    });



                    // Checklist: Select all
                    $('#selectAll').on('change', function() {
                        $('.row-check').prop('checked', this.checked);
                    });

                    function updateAmountTotal() {
                        let total = 0;
                        table.rows({ search: 'applied' }).every(function () {
                            let data = this.data();
                            let amountCell = data[3];
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
                });
                </script>

                <script>
                $(document).on('click', '.view-btn', function() {
                    $('#modal-transaction').text($(this).data('transaction'));
                    $('#modal-name').text($(this).data('name'));
                    $('#modal-email').text($(this).data('email'));
                    $('#modal-address').text($(this).data('address'));
                    $('#modal-gross').text($(this).data('gross'));
                    $('#modal-fee').text($(this).data('fee'));
                    $('#modal-website').text($(this).data('website'));
                    $('#modal-amount').text($(this).data('amount'));
                    $('#modal-type').text($(this).data('type'));
                    $('#modal-status').text($(this).data('status'));
                    $('#modal-date').text($(this).data('date'));
                });
                </script>
        @endsection

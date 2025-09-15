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
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Transaction ID</th>
                                                <th>Donor Name</th>
                                                <th>Individual Name</th>
                                                <th>Team Name</th>
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
                                                        <td class="text-break">{{ $item->transaction_id }}</td>
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
                                                        @endif
                                                        @if ($item->type == 'student')
                                                            <td>{{ $item->donation->user->group_name }}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>${{ $item->amount + (($item->amount / 100)*$payment->fee)}}</td>
                                                        <td>${{ $item->amount }}</td>
                                                        <td>${{ $item->amount }}</td>
                                                        <td>${{ ($item->amount / 100)*$payment->fee }}</td>
                                                        <td>{{ ctype_digit($item->transaction_id[0]) ? 'Authorize.net' : 'Stripe' }}</td>
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
                                                                data-name="{{ $item->name }} {{ $item->last_name }}"
                                                                data-email="{{ $item->email }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-address="{{ $item->apartment }}, {{ $item->address }}, {{ $item->state }}, {{ $item->city }}, {{ $item->zip }} {{ $item->country }}"
                                                                data-gross="${{ $item->amount }}"
                                                                data-fee="${{ ($item->amount / 100)*$payment->fee }}"
                                                                data-status="{{ $item->status == 1 ? 'Approved' : 'Pending' }}"
                                                                data-website="{{ $item->website->name }}"
                                                                data-type="{{ $item->type }}"
                                                                data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}"
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

            <!-- View Donation Modal -->
            <div class="modal fade" id="viewDonationModal" tabindex="-1" aria-labelledby="viewDonationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDonationModalLabel">Donation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                    <li class="list-group-item"><strong>Transaction ID:</strong> <span id="modal-transaction"></span></li>
                    <li class="list-group-item"><strong>Name:</strong> <span id="modal-name"></span></li>
                    <li class="list-group-item"><strong>Email:</strong> <span id="modal-email"></span></li>
                    <li class="list-group-item"><strong>Phone:</strong> <span id="modal-phone"></span></li>
                    <li class="list-group-item"><strong>Billing Address:</strong> <span id="modal-address"></span></li>
                    <li class="list-group-item"><strong>Gross:</strong> <span id="modal-gross"></span></li>
                    <li class="list-group-item"><strong>Fee:</strong> <span id="modal-fee"></span></li>
                    <li class="list-group-item"><strong>Website:</strong> <span id="modal-website"></span></li>
                    <li class="list-group-item"><strong>Type:</strong> <span id="modal-type"></span></li>
                    <li class="list-group-item"><strong>Status:</strong> <span id="modal-status"></span></li>
                    <li class="list-group-item"><strong>Date:</strong> <span id="modal-date"></span></li>
                    </ul>
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

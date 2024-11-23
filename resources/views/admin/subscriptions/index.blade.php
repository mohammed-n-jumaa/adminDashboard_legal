@include('layouts.header')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscriptions</title>
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   
   <style>
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table td,
        .table th {
            text-align: center;
            vertical-align: middle;
        }

        .status-active {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="font-weight-bold">Manage Subscriptions</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="subscriptionsTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Lawyer</th>
                                            <th>Plan</th>
                                            <th>Price</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscriptions as $subscription)
                                            <tr>
                                                <td>{{ $subscription->id }}</td>
                                                <td>
                                                    @if ($subscription->lawyer)
                                                        {{ $subscription->lawyer->first_name }}
                                                        {{ $subscription->lawyer->last_name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($subscription->plan) }}</td>
                                                <td>${{ number_format($subscription->price, 2) }}</td>
                                                <td>{{ $subscription->start_date }}</td>
                                                <td>{{ $subscription->end_date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#subscriptionsTable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        });
    </script>
</body>

</html>

@include('layouts.header')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Consultations</title>
    <!-- Plugins: CSS -->
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .table thead th {
            background-color: #343a40;
            color: #fff;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table tbody td {
            vertical-align: middle;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn {
            margin: 0 2px;
        }
        
        .modal-header.bg-primary {
        background-color: #4b49ac; 
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: bold;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-body h5 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .modal-body p {
        font-size: 1rem;
        line-height: 1.5;
    }

    .modal-footer {
        border-top: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 0.5rem 1.25rem;
        font-size: 1rem;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar Section -->
            @include('layouts.sidebar')
<!-- Main Panel Section -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Consultations</h4>
                <div class="table-responsive">
                    <table id="consultationsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($consultations as $consultation)
                                <tr>
                                    <td>{{ $consultation->id }}</td>
                                    <td>{{ $consultation->user->name ?? 'N/A' }}</td>
                                    <td>{{ $consultation->category->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($consultation->deleted_at)
                                            <span class="badge bg-danger">Deleted</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <!-- View Button -->
                                            <button class="btn btn-info btn-sm" onclick="viewConsultation({{ $consultation->id }}, '{{ $consultation->title }}', '{{ $consultation->content }}')" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                    
                                            @if (!$consultation->deleted_at)
                                                <!-- Soft Delete Button -->
                                                <button class="btn btn-danger btn-sm" onclick="deleteConsultation({{ $consultation->id }})" title="Soft Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <!-- Restore Button -->
                                                <button class="btn btn-success btn-sm" onclick="restoreConsultation({{ $consultation->id }})" title="Restore">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                    
                                                @if (auth()->user()->role === 'super_admin')
                                                    <!-- Force Delete Button (Visible only for Super Admin) -->
                                                    <button class="btn btn-dark btn-sm" onclick="forceDeleteConsultation({{ $consultation->id }})" title="Force Delete">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Consultation Modal -->
<div class="modal fade" id="viewConsultationModal" tabindex="-1" aria-labelledby="viewConsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- تعديل لون الهيدر -->
            <div class="modal-header text-white" style="background-color: #4b49ac;">
                <h5 class="modal-title" id="viewConsultationModalLabel">
                    <i class="bi bi-card-text"></i> Consultation Details
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Consultation Title -->
                <div class="mb-4">
                    <h5 class="text-primary" style="color: #4b49ac;">Title:</h5>
                    <p id="consultationTitle" class="fs-6 text-muted"></p>
                </div>
                <!-- Consultation Content -->
                <div>
                    <h5 class="text-primary" style="color: #4b49ac;">Content:</h5>
                    <p id="consultationContent" class="fs-6 text-muted"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #6c757d; color: white;">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- External JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

   <script>
     
    
        function viewConsultation(id, title, content) {
            // تعبئة بيانات المودال
            document.getElementById('consultationTitle').innerText = title;
            document.getElementById('consultationContent').innerText = content;
    
            // إظهار المودال
            var modal = new bootstrap.Modal(document.getElementById('viewConsultationModal'));
            modal.show();
        }
    
        function deleteConsultation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You can restore this consultation later.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/consultations/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Deleted!', 'The consultation has been soft deleted.', 'success').then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred while deleting the consultation.', 'error');
                        }
                    });
                }
            });
        }
    
        function restoreConsultation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will restore the consultation.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/consultations/${id}/restore`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Restored!', 'The consultation has been restored.', 'success').then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred while restoring the consultation.', 'error');
                        }
                    });
                }
            });
        }
    
        function forceDeleteConsultation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will delete the Consultation forever!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete permanently!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/consultations/${id}/force-delete`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Deleted!', 'The consultation has been permanently deleted.', 'success').then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred while deleting the consultation permanently.', 'error');
                        }
                    });
                }
            });
        }
        
    $(document).ready(function() {
        $('#consultationsTable').DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]], 
            language: {
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries available",
                search: "Search:",
            }
        });
    });

    </script>
    
</body>

</html>

@include('layouts.header')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
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
                            <h4 class="card-title">Manage Comments</h4>
                            <div class="table-responsive">
                                <table id="commentsTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Lawyer</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($comments as $comment)
                                            <tr>
                                                <td>{{ $comment->id }}</td>
                                                <td>{{ $comment->user->name ?? 'N/A' }}</td>
                                                <td>{{ $comment->lawyer->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($comment->deleted_at)
                                                        <span class="badge bg-danger">Deleted</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <!-- View Button -->
                                                        <button class="btn btn-info btn-sm" onclick="viewComment('{{ $comment->content }}')" title="View Details">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                
                                                        <!-- Soft Delete Button -->
                                                        @if (!$comment->deleted_at)
                                                            <button class="btn btn-danger btn-sm" onclick="deleteComment({{ $comment->id }})" title="Soft Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @else
                                                            <!-- Restore Button -->
                                                            <button class="btn btn-success btn-sm" onclick="restoreComment({{ $comment->id }})" title="Restore">
                                                                <i class="bi bi-arrow-clockwise"></i>
                                                            </button>
                                                
                                                            <!-- Force Delete Button (Visible only for Super Admin) -->
                                                            @if (auth()->user()->role === 'super_admin')
                                                                <button class="btn btn-dark btn-sm" onclick="forceDeleteComment({{ $comment->id }})" title="Force Delete">
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
        </div>
    </div>

    <!-- View Comment Modal -->
    <div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #4b49ac;">
                    <h5 class="modal-title" id="viewCommentModalLabel">
                        <i class="bi bi-card-text"></i> Comment Details
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="commentContent" class="fs-6 text-muted"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        function viewComment(content) {
            document.getElementById('commentContent').innerText = content;
            var modal = new bootstrap.Modal(document.getElementById('viewCommentModal'));
            modal.show();
        }

        function deleteComment(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You can restore this comment later.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/comments/${id}/delete`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while deleting the comment.', 'error');
                }
            });
        }
    });
}


function restoreComment(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will restore the comment.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/comments/${id}/restore`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    Swal.fire('Restored!', response.message, 'success').then(() => location.reload());
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while restoring the comment.', 'error');
                }
            });
        }
    });
}


function forceDeleteComment(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will permanently delete the comment!",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete permanently!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/comments/${id}/force-delete`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while permanently deleting the comment.', 'error');
                }
            });
        }
    });
}


        $(document).ready(function() {
            $('#commentsTable').DataTable({
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

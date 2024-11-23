@include('layouts.header')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
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
            vertical-align: middle;
            text-align: center;
        }

        .btn-sm i {
            margin-right: 5px;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: black;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
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
                        <h3 class="font-weight-bold">Manage Categories</h3>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    </div>

                    <!-- Categories Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="categoriesTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $category->id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->description }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <!-- Edit Button -->
                                                        <button class="btn btn-warning btn-sm mx-1" data-toggle="modal"
                                                            data-target="#editCategoryModal"
                                                            onclick="populateEditForm({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                
                                                        @if ($category->deleted_at)
                                                            <!-- Restore Button -->
                                                            <button class="btn btn-success btn-sm" onclick="restoreCategory({{ $category->id }})">
                                                                <i class="fas fa-recycle"></i>
                                                            </button>
                                                
                                                            <!-- Force Delete Button (Visible only for Super Admin) -->
                                                            @if (auth()->user()->role === 'super_admin')
                                                                <button class="btn btn-dark btn-sm" onclick="forceDeleteCategory({{ $category->id }})">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            @endif
                                                        @else
                                                            <!-- Soft Delete Button -->
                                                            <button class="btn btn-danger btn-sm mx-1" onclick="deleteCategory({{ $category->id }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
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

                    <!-- Add Category Modal -->
                    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="addCategoryForm" action="{{ route('admin.categories.store') }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Category Modal -->
                    <div class="modal fade" id="editCategoryModal" tabindex="-1"
                        aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editCategoryForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="edit_name">Name</label>
                                            <input type="text" class="form-control" id="edit_name" name="name"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_description">Description</label>
                                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- External JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#categoriesTable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 20],
                language: {
                    search: "Search:",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                }
            });

            // Add Category
            $('#addCategoryForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.categories.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            title: 'Added!',
                            text: response.success,
                            icon: 'success',
                            timer: 5000, 
                            showConfirmButton: true 
                        }).then(() => {
                            location.reload(); 
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to add category.',
                            icon: 'error',
                            timer: 5000, 
                            showConfirmButton: true 
                        });
                    }
                });
            });


            // Populate Edit Form with Details
            window.populateEditForm = function(id, name, description) {
                $('#editCategoryForm').attr('action', '{{ route('admin.categories.update', ':id') }}'.replace(
                    ':id', id));
                $('#edit_name').val(name);
                $('#edit_description').val(description);
            };

            // Update Category
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();

                const actionUrl = $(this).attr('action');
                $.ajax({
                    url: actionUrl,
                    method: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            title: 'Updated!',
                            text: response.success,
                            icon: 'success',
                            timer: 5000,
                            showConfirmButton: true 
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update category.',
                            icon: 'error',
                            timer: 5000, 
                            showConfirmButton: true 
                        });
                    }
                });
            });


            // Soft Delete Category
            window.deleteCategory = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You can restore this category later.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.categories.destroy', ':id') }}".replace(
                                ':id', id),
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.success,
                                    icon: 'success',
                                    timer: 5000, 
                                    showConfirmButton: true 
                                }).then(() => {
                                    location
                                .reload(); 
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to delete category.',
                                    icon: 'error',
                                    timer: 5000, 
                                    showConfirmButton: true 
                                });
                            }
                        });
                    }
                });
            };


            // Restore Soft Deleted Category
            window.restoreCategory = function(id) {
                $.ajax({
                    url: "{{ route('admin.categories.restore', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Restored!',
                            text: response.success,
                            icon: 'success',
                            timer: 5000, 
                            showConfirmButton: true 
                        }).then(() => {
                            location.reload(); 
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to restore category.',
                            icon: 'error',
                            timer: 5000, 
                            showConfirmButton: true 
                        });
                    }
                });
            };

            // Permanently Delete Category
            window.forceDeleteCategory = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will delete this category from database forever!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, permanently delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.categories.forceDelete', ':id') }}".replace(
                                ':id', id),
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Permanently Deleted!',
                                    text: response.success,
                                    icon: 'success',
                                    timer: 5000,
                                    showConfirmButton: true
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to permanently delete category.',
                                    icon: 'error',
                                    timer: 5000,
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            };

        });
    </script>


</body>

</html>

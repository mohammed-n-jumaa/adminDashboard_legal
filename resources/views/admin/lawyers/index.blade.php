<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lawyers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

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
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4 text-center">Manage Lawyers</h2>

        <!-- Add Lawyer Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLawyerModal">
            <i class="bi bi-person-plus"></i> Add Lawyer
        </button>

        <!-- Lawyer Table -->
        <table id="lawyerTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Specialization</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lawyers as $lawyer)
                <tr>
                    <td>{{ $lawyer->id }}</td>
                    <td>{{ $lawyer->first_name }} {{ $lawyer->last_name }}</td>
                    <td>{{ $lawyer->email }}</td>
                    <td>{{ $lawyer->phone_number }}</td>
                    <td>{{ $lawyer->specialization }}</td>
                    <td>
                        @if ($lawyer->trashed())
                        <span class="badge bg-danger">Deleted</span>
                        @else
                        <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm mx-1" onclick="viewLawyer({{ json_encode($lawyer) }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        @if (!$lawyer->trashed())
                        <button class="btn btn-warning btn-sm mx-1" onclick="populateEditForm({{ json_encode($lawyer) }})"
                            data-bs-toggle="modal" data-bs-target="#editLawyerModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm mx-1" onclick="confirmDelete({{ $lawyer->id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        @else
                        <button class="btn btn-success btn-sm mx-1" onclick="confirmRestore({{ $lawyer->id }})">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Lawyer Modal -->
    <div class="modal fade" id="addLawyerModal" tabindex="-1" aria-labelledby="addLawyerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addLawyerForm" method="POST" action="{{ route('lawyers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLawyerModalLabel">Add Lawyer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization" required>
                        </div>
                        <div class="mb-3">
                            <label for="lawyer_certificate" class="form-label">Certificate</label>
                            <input type="file" class="form-control" name="lawyer_certificate" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="syndicate_card" class="form-label">Syndicate Card</label>
                            <input type="file" class="form-control" name="syndicate_card" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Lawyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Lawyer Modal -->
    <div class="modal fade" id="editLawyerModal" tabindex="-1" aria-labelledby="editLawyerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editLawyerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLawyerModalLabel">Edit Lawyer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone_number" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control" id="edit_specialization" name="specialization" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_lawyer_certificate" class="form-label">Certificate</label>
                            <input type="file" class="form-control" id="edit_lawyer_certificate" name="lawyer_certificate" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="edit_syndicate_card" class="form-label">Syndicate Card</label>
                            <input type="file" class="form-control" id="edit_syndicate_card" name="syndicate_card" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="edit_profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="edit_profile_picture" name="profile_picture" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Lawyer Modal -->
    <div class="modal fade" id="viewLawyerModal" tabindex="-1" aria-labelledby="viewLawyerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewLawyerModalLabel"><i class="bi bi-person-circle"></i> Lawyer Details</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="viewProfilePicture" src="" alt="Profile Picture" class="img-thumbnail rounded-circle shadow-lg" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>First Name:</strong> <span id="viewFirstName" class="text-muted"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Last Name:</strong> <span id="viewLastName" class="text-muted"></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Email:</strong> <span id="viewEmail" class="text-muted"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phone:</strong> <span id="viewPhone" class="text-muted"></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Specialization:</strong> <span id="viewSpecialization" class="text-muted"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span id="viewStatus" class="badge bg-success"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#lawyerTable').DataTable();
        });

        // Populate Edit Form
        function populateEditForm(lawyer) {
            const form = document.getElementById('editLawyerForm');
            form.action = `/lawyers/${lawyer.id}`;

            document.getElementById('edit_first_name').value = lawyer.first_name || '';
            document.getElementById('edit_last_name').value = lawyer.last_name || '';
            document.getElementById('edit_email').value = lawyer.email || '';
            document.getElementById('edit_phone_number').value = lawyer.phone_number.replace('+962', '') || '';
            document.getElementById('edit_specialization').value = lawyer.specialization || '';
        }

        // View Lawyer Details
        function viewLawyer(lawyer) {
            document.getElementById('viewFirstName').textContent = lawyer.first_name || 'N/A';
            document.getElementById('viewLastName').textContent = lawyer.last_name || 'N/A';
            document.getElementById('viewEmail').textContent = lawyer.email || 'N/A';
            document.getElementById('viewPhone').textContent = lawyer.phone_number ? `+962${lawyer.phone_number}` : 'N/A';
            document.getElementById('viewSpecialization').textContent = lawyer.specialization || 'N/A';
            document.getElementById('viewStatus').textContent = lawyer.deleted_at ? 'Deleted' : 'Active';

            const profilePicture = document.getElementById('viewProfilePicture');
            profilePicture.src = lawyer.profile_picture ? `/storage/${lawyer.profile_picture}` : 'https://via.placeholder.com/150';

            const modal = new bootstrap.Modal(document.getElementById('viewLawyerModal'));
            modal.show();
        }

        // Confirm Soft Delete
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will soft delete the lawyer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/lawyers/${id}/delete`,
                        method: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function () {
                            Swal.fire("Deleted!", "Lawyer deleted successfully.", "success");
                            location.reload();
                        }
                    });
                }
            });
        }

        // Confirm Restore
        function confirmRestore(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will restore the lawyer.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/lawyers/${id}/restore`,
                        method: "POST",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function () {
                            Swal.fire("Restored!", "Lawyer restored successfully.", "success");
                            location.reload();
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>

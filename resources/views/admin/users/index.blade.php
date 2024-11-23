<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
        
    </style>


</head>

<body>
    <div class="container-scroller">
        <!-- Include Navbar -->
        @include('layouts.header')

        <div class="container-fluid page-body-wrapper">
            <!-- Include Sidebar -->
            @include('layouts.sidebar')

            <div class="bg-light py-4" style="min-height: 100vh;">
                <div class="container">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0">Manage Users</h4>
                            <p class="text-muted mb-0">Manage all registered users here.</p>
                            <div class="text-end">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-person-plus"></i> Add User
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="userTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($user->role) }}</td>
                                                <td>
                                                    @if ($user->deleted_at)
                                                        <span class="badge bg-danger">Deleted</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <!-- View Button -->
                                                        <button class="btn btn-info btn-sm mx-1"
                                                            onclick="viewUser({{ json_encode($user) }})">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                
                                                        @if (!$user->deleted_at)
                                                            <!-- Edit Button -->
                                                            <button class="btn btn-warning btn-sm mx-1"
                                                                onclick="populateEditForm({{ json_encode($user) }})"
                                                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                
                                                            <!-- Soft Delete Button -->
                                                            <form id="delete-form-{{ $user->id }}"
                                                                action="{{ route('admin.users.destroy', $user->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                
                                                            <button type="button" class="btn btn-danger btn-sm mx-1"
                                                                onclick="confirmDelete({{ $user->id }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @else
                                                            <!-- Restore Button -->
                                                            <form id="restore-form-{{ $user->id }}"
                                                                action="{{ route('admin.users.restore', $user->id) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm mx-1"
                                                                    onclick="confirmRestore({{ $user->id }})">
                                                                    <i class="bi bi-arrow-clockwise"></i>
                                                                </button>
                                                            </form>
                                                
                                                            @if (auth()->user()->role === 'super_admin')
                                                                <!-- Force Delete Button (Visible Only to Super Admin) -->
                                                                <form id="force-delete-form-{{ $user->id }}"
                                                                    action="{{ route('admin.users.forceDelete', $user->id) }}"
                                                                    method="POST" style="display:inline-block;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-dark btn-sm"
                                                                        onclick="confirmForceDelete({{ $user->id }})">
                                                                        <i class="bi bi-trash-fill"></i>
                                                                    </button>
                                                                </form>
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

        <!-- Add User Modal -->
   <!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" id="userForm">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter full name" value="{{ old('name') }}" required>
                            <span class="text-danger small" data-error-for="name"></span>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email address" value="{{ old('email') }}" required>
                            <span class="text-danger small" data-error-for="email"></span>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter a strong password" required>
                            <span class="text-danger small" data-error-for="password"></span>
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm your password" required>
                            <span class="text-danger small" data-error-for="password_confirmation"></span>
                            @error('password_confirmation')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text">+962</span>
                                <input type="text" class="form-control" id="phone"
                                    name="phone_number" maxlength="9" placeholder="Enter 9 digits"
                                    oninput="validatePhoneInput(this)" required>
                            </div>
                            <span class="text-danger small" data-error-for="phone_number"></span>
                            @error('phone_number')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            <span class="text-danger small" data-error-for="date_of_birth"></span>
                            @error('date_of_birth')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture"
                                name="profile_picture" accept="image/*">
                            <span class="text-danger small" data-error-for="profile_picture"></span>
                            @error('profile_picture')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (auth()->user()->role === 'super_admin')
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="role" value="user">
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>



        
       <!-- Edit User Modal -->
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <form method="POST" action="" id="editUserForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name"
                            placeholder="Enter full name" required>
                        <!-- Error message -->
                        <span class="text-danger small" data-error-for="name"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email"
                            placeholder="Enter email address" required>
                        <!-- Error message -->
                        <span class="text-danger small" data-error-for="email"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">+962</span>
                            <input type="text" class="form-control" id="edit_phone"
                                name="phone_number" maxlength="9" placeholder="Enter 9 digits"
                                required>
                        </div>
                        <!-- Error message -->
                        <span class="text-danger small" data-error-for="phone_number"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="edit_date_of_birth" name="date_of_birth" required>
                        <!-- Error message -->
                        <span class="text-danger small" data-error-for="date_of_birth"></span>
                    </div>
                </div>
                @if (auth()->user()->role === 'super_admin')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="" disabled>Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                            <!-- Error message -->
                            <span class="text-danger small" data-error-for="role"></span>
                        </div>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="edit_profile_picture"
                            name="profile_picture" accept="image/*">
                    </div>
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

        <!-- View User Modal -->
       <!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewUserModalLabel"><i class="bi bi-person-circle"></i> User Details</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="viewProfilePicture" src="" alt="Profile Picture"
                        class="img-thumbnail rounded-circle shadow-lg" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <span id="viewName" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <span id="viewEmail" class="text-muted"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> <span id="viewPhone" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> <span id="viewRole" class="badge bg-info text-dark"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> <span id="viewDateOfBirth" class="text-muted"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Created At:</strong> <span id="viewCreatedAt" class="text-muted"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Updated At:</strong> <span id="viewUpdatedAt" class="text-muted"></span></p>
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


    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        // Real-time validation for both add and edit forms
        document.addEventListener('DOMContentLoaded', function () {
            const validateField = (name, value, errorMessages) => {
                let error = '';
                switch (name) {
                    case 'name':
                    case 'edit_name':
                        if (!value || value.length > 255) error = errorMessages.name;
                        break;
                    case 'email':
                    case 'edit_email':
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) error = errorMessages.email;
                        break;
                    case 'phone_number':
                    case 'edit_phone_number':
                        if (value.length !== 9 || !/^\d{9}$/.test(value)) {
                            error = errorMessages.phone_number; // تحقق من الرقم (9 أرقام فقط)
                        }
                        break;
                    case 'password':
                        if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value))
                            error = errorMessages.password;
                        break;
                    case 'password_confirmation':
                        const password = document.querySelector('[name="password"]').value;
                        if (value !== password) error = errorMessages.password_confirmation;
                        break;
                    case 'date_of_birth':
                        const dob = new Date(value);
                        const now = new Date();
                        let age = now.getFullYear() - dob.getFullYear();
                        const monthDiff = now.getMonth() - dob.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && now.getDate() < dob.getDate())) age--;
                        if (age < 18) error = errorMessages.date_of_birth;
                        break;
                }
                return error;
            };
    
            const attachValidation = (formId, errorMessages, fields) => {
                const form = document.getElementById(formId);
                fields.forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    const errorElement = form.querySelector(`[data-error-for="${field}"]`);
                    input.addEventListener('input', () => {
                        const error = validateField(field, input.value, errorMessages);
                        errorElement.textContent = error;
                    });
                });
    
                // Add restrictions to phone number field
                const phoneField = form.querySelector('[name="phone_number"]');
                if (phoneField) {
                    phoneField.addEventListener('input', function () {
                        // Limit the phone number to exactly 9 digits
                        this.value = this.value.replace(/\D/g, ''); // Remove non-digit characters
                        if (this.value.length > 9) {
                            this.value = this.value.slice(0, 9); // Limit to 9 digits
                        }
                    });
                }
            };
    
            const errorMessages = {
                name: "Name must not exceed 255 characters.",
                email: "Invalid email format.",
                phone_number: "Phone number must contain exactly 9 digits.",
                password: "Password must include uppercase, lowercase, numbers, and symbols.",
                password_confirmation: "Passwords do not match.",
                date_of_birth: "Must be at least 18 years old."
            };
    
            // Attach validation to Add User Form
            attachValidation('userForm', errorMessages, ['name', 'email', 'phone_number', 'password', 'password_confirmation', 'date_of_birth']);
    const userForm = document.getElementById('userForm');
userForm.addEventListener('submit', function (e) {
    e.preventDefault(); // منع الإرسال الفوري للنموذج

    // التأكد من عدم وجود أخطاء
    const errors = Array.from(userForm.querySelectorAll('[data-error-for]')).filter(el => el.textContent.trim());
    if (errors.length > 0) {
        Swal.fire({
            title: 'Error!',
            text: 'Please correct the errors before submitting.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Success!',
        text: 'User has been added successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
        timer: 5000, // اختياري: اجعلها تختفي بعد 5 ثوانٍ
        timerProgressBar: true,
        allowOutsideClick: false
    }).then(() => {
        userForm.submit(); // إرسال النموذج بعد عرض التنبيه
    });
});

            // Attach validation to Edit User Form
            attachValidation('editUserForm', errorMessages, ['name', 'email', 'phone_number']);
            
        });
    
        // Function to populate the Edit User Form
        function populateEditForm(user) {
            const form = document.getElementById('editUserForm');
            form.action = `/admin/users/${user.id}`;
    
            // Fill existing fields
            document.getElementById('edit_name').value = user.name || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone').value = user.phone_number ? user.phone_number.replace('+962', '') : '';
    
            // Format date
            const dateOfBirthField = document.getElementById('edit_date_of_birth');
            dateOfBirthField.value = user.date_of_birth ? new Date(user.date_of_birth).toISOString().split('T')[0] : '';
    
            document.getElementById('edit_role').value = user.role || '';
    
            // Show the modal
            $('#editUserModal').modal('show');
        }
    
        // View User Details
        function viewUser(user) {
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            };
    
            // Assign values to modal
            document.getElementById('viewName').textContent = user.name || 'N/A';
            document.getElementById('viewEmail').textContent = user.email || 'N/A';
            document.getElementById('viewPhone').textContent = user.phone_number ? '+962' + user.phone_number : 'N/A';
            document.getElementById('viewRole').textContent = user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : 'N/A';
            document.getElementById('viewDateOfBirth').textContent = formatDate(user.date_of_birth);
            document.getElementById('viewCreatedAt').textContent = formatDate(user.created_at);
            document.getElementById('viewUpdatedAt').textContent = formatDate(user.updated_at);
    
            const profilePicture = document.getElementById('viewProfilePicture');
            profilePicture.src = user.profile_picture ? `/storage/${user.profile_picture}` : 'https://www.w3schools.com/w3images/avatar2.png';
    
            // Show modal
            $('#viewUserModal').modal('show');
        }
    
        function confirmDelete(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // عرض رسالة النجاح
            Swal.fire({
                title: 'Success!',
                text: 'User has been soft deleted successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 5000, // 10 ثوانٍ
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(() => {
                // إرسال النموذج بعد انتهاء الرسالة
                document.getElementById(`delete-form-${userId}`).submit();
            });
        }
    });
}

function confirmForceDelete(userId) {
    Swal.fire({
        title: 'Are you absolutely sure?',
        html: '<span style="color: red; font-weight: bold;">THIS USER WILL BE PERMANENTLY DELETED!</span>',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete permanently!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Success!',
                text: 'User has been permanently deleted successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 10000,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(() => {
                document.getElementById(`force-delete-form-${userId}`).submit();
            });
        }
    });
}

    
function confirmRestore(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will restore the user.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Success!',
                text: 'User has been restored successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 10000,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(() => {
                document.getElementById(`restore-form-${userId}`).submit();
            });
        }
    });
}

    
        // Initialize DataTable
        $(document).ready(function () {
            $('#userTable').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 20, 50],
                language: {
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    },
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                    
                }
            });
        });
    </script>
    


</body>

</html>

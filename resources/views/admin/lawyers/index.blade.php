<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lawyers</title>
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <!-- ملفات CSS المعدّلة -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

    .profile-pic {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }

    .form-control.error {
        border-color: red;
    }

    .text-danger {
        font-size: 0.875rem;
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
                            <h4 class="mb-0">Manage Lawyers</h4>
                            <p class="text-muted mb-0">Manage all registered lawyers here.</p>
                            <div class="text-end">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLawyerModal">
                                    <i class="bi bi-person-plus"></i> Add Lawyer
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="lawyerTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
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
                                                <td>+962{{ $lawyer->phone_number }}</td>
                                                <td>{{ ucfirst($lawyer->gender) }}</td>
                                                <td>{{ $lawyer->specialization ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($lawyer->deleted_at)
                                                        <span class="badge bg-danger">Deleted</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <!-- View Button -->
                                                        <button class="btn btn-info btn-sm"
                                                            onclick="viewLawyer({{ json_encode($lawyer) }})"
                                                            data-bs-toggle="modal" data-bs-target="#viewLawyerModal">
                                                            <i class="bi bi-eye"></i>
                                                        </button>

                                                        @if (!$lawyer->deleted_at)
                                                            <!-- Edit Button -->
                                                            <button class="btn btn-warning btn-sm mx-1"
                                                            onclick="editLawyer({{ json_encode($lawyer) }})"
                                                            data-bs-toggle="modal" data-bs-target="#editLawyerModal">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        
                                                            <!-- Soft Delete Button -->
                                                            <button class="btn btn-danger btn-sm mx-1"
                                                                onclick="confirmDelete({{ $lawyer->id }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @else
                                                            <!-- Restore Button -->
                                                            <button class="btn btn-success btn-sm mx-1"
                                                                onclick="confirmRestore({{ $lawyer->id }})">
                                                                <i class="bi bi-arrow-clockwise"></i>
                                                            </button>
                                                            <!-- Force Delete Button -->
                                                            <button class="btn btn-dark btn-sm mx-1"
                                                                onclick="confirmForceDelete({{ $lawyer->id }})">
                                                                <i class="bi bi-trash-fill"></i>
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
                </div>
            </div>

        </div>

        <!-- Add Lawyer Modal -->
        <div class="modal fade" id="addLawyerModal" tabindex="-1" aria-labelledby="addLawyerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLawyerModalLabel">Add New Lawyer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addLawyerForm" method="POST" action="{{ route('admin.lawyers.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="Enter first name" required>
                                    <span class="text-danger small" id="first_name_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="Enter last name" required>
                                    <span class="text-danger small" id="last_name_error"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter email address" required>
                                    <span class="text-danger small" id="email_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+962</span>
                                        <input type="text" class="form-control" id="phone_number"
                                            name="phone_number" maxlength="9" placeholder="Enter phone number"
                                            required>
                                    </div>
                                    <span class="text-danger small" id="phone_number_error"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password" required>
                                    <span class="text-danger small" id="password_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm password" required>
                                    <span class="text-danger small" id="password_confirmation_error"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth"
                                        name="date_of_birth" required>
                                    <span class="text-danger small" id="date_of_birth_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <span class="text-danger small" id="gender_error"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization"
                                        name="specialization" placeholder="Enter specialization">
                                    <span class="text-danger small" id="specialization_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_picture"
                                        name="profile_picture">
                                    <span class="text-danger small" id="profile_picture_error"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="lawyer_certificate" class="form-label">Lawyer Certificate</label>
                                    <input type="file" class="form-control" id="lawyer_certificate"
                                        name="lawyer_certificate" required>
                                    <span class="text-danger small" id="lawyer_certificate_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="syndicate_card" class="form-label">Syndicate Card</label>
                                    <input type="file" class="form-control" id="syndicate_card"
                                        name="syndicate_card" required>
                                    <span class="text-danger small" id="syndicate_card_error"></span>
                                </div>
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

<!-- View Lawyer Modal -->
<!-- View Lawyer Modal -->
<div class="modal fade" id="viewLawyerModal" tabindex="-1" aria-labelledby="viewLawyerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #007bff;">
                <h5 class="modal-title fw-bold" id="viewLawyerModalLabel">
                    <i class="bi bi-person-circle"></i> Lawyer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- صورة المحامي -->
                <img id="viewProfilePicture" src="https://via.placeholder.com/150" 
                     alt="Profile Picture" 
                     class="img-thumbnail rounded-circle mb-3" 
                     style="width: 150px; height: 150px; object-fit: cover;">
                <h4 class="fw-bold" id="viewFullName">Not Provided</h4>
                <p class="text-muted mb-2" id="viewEmail">Not Provided</p>
                
                <!-- معلومات المحامي -->
                <div class="row text-start mt-4">
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> <span id="viewPhone">Not Provided</span></p>
                        <p><strong>Date of Birth:</strong> <span id="viewDateOfBirth">Not Provided</span></p>
                        <p><strong>Specialization:</strong> <span id="viewSpecialization">Not Specified</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Gender:</strong> <span id="viewGender">Not Provided</span></p>
                        <p><strong>Created At:</strong> <span id="viewCreatedAt">Not Provided</span></p>
                        <p><strong>Updated At:</strong> <span id="viewUpdatedAt">Not Provided</span></p>
                    </div>
                </div>

                <!-- الشهادات والبطاقات -->
                <div class="row mt-4">
                    <div class="col-md-6 text-start">
                        <p><strong>Lawyer Certificate:</strong> 
                            <a id="viewLawyerCertificate" href="#" target="_blank" class="text-decoration-none text-primary">
                                View Certificate
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 text-start">
                        <p><strong>Syndicate Card:</strong> 
                            <a id="viewSyndicateCard" href="#" target="_blank" class="text-decoration-none text-primary">
                                View Syndicate Card
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Lawyer Modal -->


    </div>

   <!-- ملفات JS المعدّلة -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to show validation errors
        const showValidationError = (inputElement, message) => {
            const errorElement = document.createElement('div');
            errorElement.className = 'text-danger small mt-1';
            errorElement.textContent = message;

            const parent = inputElement.parentElement;
            if (!parent.querySelector('.text-danger')) {
                parent.appendChild(errorElement);
            }
            inputElement.classList.remove('is-valid');
            inputElement.classList.add('is-invalid');
        };

        // Function to remove validation errors
        const removeValidationError = (inputElement) => {
            const parent = inputElement.parentElement;
            const errorElement = parent.querySelector('.text-danger');
            if (errorElement) {
                parent.removeChild(errorElement);
            }
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
        };

        // Real-time field validation
        const validateField = (field) => {
            let errorMessage = '';

            if (field.name === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    errorMessage = 'Please enter a valid email address.';
                }
            } else if (field.name === 'phone_number') {
                const phoneRegex = /^\d{9}$/;
                if (!phoneRegex.test(field.value)) {
                    errorMessage = 'Phone number must be exactly 9 digits.';
                }
            } else if (field.name === 'password_confirmation') {
                const password = document.getElementById('password').value;
                if (field.value !== password) {
                    errorMessage = 'Passwords do not match.';
                }
            } else if (field.name === 'date_of_birth') {
                const dob = new Date(field.value);
                const age = new Date().getFullYear() - dob.getFullYear();
                if (age < 18) {
                    errorMessage = 'Age must be at least 18 years.';
                }
            } else if (field.type === 'file') {
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const fileExtension = field.value.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    errorMessage = 'Only images (jpg, jpeg, png) are allowed.';
                }
            } else if (!field.value.trim()) {
                errorMessage = 'This field is required.';
            }

            if (errorMessage) {
                showValidationError(field, errorMessage);
            } else {
                removeValidationError(field);
            }
        };

        // Attach validation listeners to form fields
        const attachValidationListeners = (formId) => {
            const form = document.getElementById(formId);
            const fields = form.querySelectorAll('input, select');

            fields.forEach((field) => {
                field.addEventListener('input', () => validateField(field));
                field.addEventListener('blur', () => validateField(field)); // Validate on blur
            });

            form.addEventListener('submit', (event) => {
                let hasErrors = false;

                fields.forEach((field) => {
                    validateField(field);
                    if (field.classList.contains('is-invalid')) {
                        hasErrors = true;
                    }
                });

                if (hasErrors) {
                    event.preventDefault();
                }
            });
        };

        attachValidationListeners('editLawyerForm');
        attachValidationListeners('addLawyerForm'); // Assuming you have an add form

        // Function to populate the Edit Lawyer Modal
        window.editLawyer = function (lawyer) {
    if (!lawyer || Object.keys(lawyer).length === 0) {
        console.error("Lawyer object is not defined or empty.");
        return;
    }

    // ملء بيانات النموذج بالعناصر الحالية للمحامي
    document.getElementById('editLawyerId').value = lawyer.id || '';
    document.getElementById('edit_first_name').value = lawyer.first_name || '';
    document.getElementById('edit_last_name').value = lawyer.last_name || '';
    document.getElementById('edit_email').value = lawyer.email || '';
    document.getElementById('edit_phone_number').value = lawyer.phone_number
        ? lawyer.phone_number.replace("+962", "") // إزالة مفتاح الدولة
        : '';
    document.getElementById('edit_gender').value = lawyer.gender || '';
    document.getElementById('edit_specialization').value = lawyer.specialization || '';

    // عرض الصورة الحالية إذا كانت موجودة
    const profilePicturePreview = document.getElementById('editProfilePicturePreview');
    profilePicturePreview.src = lawyer.profile_picture
        ? `/storage/${lawyer.profile_picture}`
        : 'https://via.placeholder.com/150';
    profilePicturePreview.style.display = lawyer.profile_picture ? 'block' : 'none';

    // إعداد الروابط للشهادة وبطاقة النقابة
    const certificateLink = document.getElementById('currentCertificateLink');
    certificateLink.href = lawyer.lawyer_certificate ? `/storage/${lawyer.lawyer_certificate}` : '#';
    certificateLink.textContent = lawyer.lawyer_certificate ? 'View Current Certificate' : 'No Certificate Available';

    const syndicateCardLink = document.getElementById('currentSyndicateCardLink');
    syndicateCardLink.href = lawyer.syndicate_card ? `/storage/${lawyer.syndicate_card}` : '#';
    syndicateCardLink.textContent = lawyer.syndicate_card ? 'View Current Syndicate Card' : 'No Syndicate Card Available';

    // تعديل نموذج التحديث ليعمل مع AJAX أو PUT
    const form = document.getElementById('editLawyerForm');
    form.action = `/admin/lawyers/${lawyer.id}`;
    form.onsubmit = function (event) {
        event.preventDefault(); // منع التحديث الافتراضي للصفحة

        const formData = new FormData(form); // استخراج بيانات النموذج

        $.ajax({
            url: form.action,
            type: 'PUT', // الطريقة المطلوبة
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log('Updated successfully:', response);
                $('#editLawyerModal').modal('hide'); // إغلاق النافذة
                location.reload(); // تحديث الصفحة لإظهار البيانات المحدثة
            },
            error: function (error) {
                console.error('Update failed:', error);
                // عرض رسالة خطأ للمستخدم
                alert('Failed to update lawyer. Please check the form and try again.');
            }
        });
    };

    $('#editLawyerModal').modal('show'); // عرض نافذة التعديل
};


        // Function to populate the View Lawyer Modal
        window.viewLawyer = function (lawyer) {
            if (!lawyer || Object.keys(lawyer).length === 0) {
                console.error("Lawyer object is not defined or empty.");
                return;
            }

            document.getElementById('viewFullName').textContent = lawyer.first_name && lawyer.last_name ? 
                `${lawyer.first_name} ${lawyer.last_name}` : "Not Provided";
            document.getElementById('viewEmail').textContent = lawyer.email || "Not Provided";
            document.getElementById('viewPhone').textContent = lawyer.phone_number ? `+962${lawyer.phone_number}` : "Not Provided";
            document.getElementById('viewGender').textContent = lawyer.gender ? lawyer.gender.charAt(0).toUpperCase() + lawyer.gender.slice(1) : "Not Provided";
            document.getElementById('viewSpecialization').textContent = lawyer.specialization || "Not Specified";

            // Profile picture
            const profilePicture = document.getElementById('viewProfilePicture');
            profilePicture.src = lawyer.profile_picture ? `/storage/${lawyer.profile_picture}` : 'https://via.placeholder.com/150';

            // Lawyer certificate link
            const lawyerCertificate = document.getElementById('viewLawyerCertificate');
            lawyerCertificate.textContent = lawyer.lawyer_certificate ? "View Certificate" : "No Certificate Available";
            lawyerCertificate.href = lawyer.lawyer_certificate ? `/storage/${lawyer.lawyer_certificate}` : "#";

            // Syndicate card link
            const syndicateCard = document.getElementById('viewSyndicateCard');
            syndicateCard.textContent = lawyer.syndicate_card ? "View Syndicate Card" : "No Syndicate Card Available";
            syndicateCard.href = lawyer.syndicate_card ? `/storage/${lawyer.syndicate_card}` : "#";

            // Show the modal
            $('#viewLawyerModal').modal('show');
        };

        // Confirmation for delete actions
        window.confirmDelete = function (id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will soft delete the lawyer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.getElementById(`delete-form-${id}`);
                    if (deleteForm) {
                        deleteForm.submit();
                    } else {
                        console.error('Delete form not found for ID:', id);
                    }
                }
            });
        };

        // Initialize DataTable
        $(document).ready(function () {
            $('#lawyerTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="bi bi-clipboard"></i> Copy',
                        className: 'btn btn-secondary btn-sm'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Print',
                        className: 'btn btn-info btn-sm'
                    }
                ],
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
    });
</script>




</body>

</html>

<!doctype html>
<html lang="en">


<!-- Mirrored from demo.riktheme.com/flohan/side-menu/account.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Mar 2025 19:16:18 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Title -->
    <title>Conscious App</title>
    <!-- Favicon -->
    <link rel="icon" href="img/core-img/favicon.ico">
    <!-- Plugins File -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <!-- Master Stylesheet [If you remove this CSS file, your file will be broken undoubtedly.] -->
    <link rel="stylesheet" href="style.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="scene">
            <div class="cube-wrapper">
                <div class="cube">
                    <div class="cube-faces">
                        <div class="cube-face shadow"></div>
                        <div class="cube-face bottom"></div>
                        <div class="cube-face top"></div>
                        <div class="cube-face left"></div>
                        <div class="cube-face right"></div>
                        <div class="cube-face back"></div>
                        <div class="cube-face front"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Preloader -->

    <!-- ======================================
    ******* Page Wrapper Area Start **********
    ======================================= -->
    <div class="flapt-page-wrapper">
        <!-- Sidemenu Area -->
        <div class="flapt-sidemenu-wrapper">
            <!-- Logo -->


            <!-- Side Nav -->
            @include('layouts.sidebar')
        </div>
        <!-- Side Nav End -->

        <!-- Page Content -->
        <div class="flapt-page-content">
            <!-- Top Header Area -->
            @include('layouts.topbar')
            <!-- Main Content Area -->
            <div class="main-content">
                <div class="content-wraper-area">
                    <div class="container-fluid">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body card-breadcrumb">
                                        <div class="page-title-box d-flex align-items-center justify-content-between">
                                            <h4 class="mb-0">My Account</h4>

                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-12">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                        aria-labelledby="v-pills-home-tab" tabindex="0">
                                        <div class="card mb-4">
                                            <div class="card-header-cu">
                                                <h6 class="mb-0">Basic information</h6>
                                            </div>
                                            <div class="card-body">
                                                <form id="updateProfileForm" method="POST"
                                                    action="{{ route('profile.update') }}"
                                                    enctype="multipart/form-data">

                                                    @csrf
                                                    <div class="d-flex align-items-center border-bottom pb-4 mb-4">
                                                        <div class="account-img">
                                                            <img id="profile-avatar"
                                                                src="{{ $employee->avatar ? asset('storage/' . $employee->avatar) : asset('img/bg-img/per-3.jpg') }}"
                                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 id="profile-name">{{ $employee->name }}</h6>
                                                            @if(session('employee_logged_in'))
                                                            <span class="fs-sm text-muted">( Upload a PNG or JPG, siz
                                                                limit is 15 MB. )</span>
                                                            <div class="mt-3">
                                                                <input type="file" name="avatar" class="form-control">
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- First Name -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Name</label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ old('name', $employee->name) }}"
                                                                    placeholder="First name">
                                                            </div>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email address</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ old('email', $employee->email) }}"
                                                                    placeholder="example@example.com">
                                                            </div>
                                                        </div>

                                                        <!-- Email -->
                                                        @if(session('employee_logged_in'))
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Phone number</label>
                                                                <input type="text" name="phone" class="form-control"
                                                                    value="{{ old('phone', $employee->phone) }}"
                                                                    placeholder="+91 9876543210">
                                                            </div>
                                                        </div>
                                                        <!-- Phone -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Address</label>
                                                                <input type="text" name="address" class="form-control"
                                                                    style="width: 100%;"
                                                                    value="{{ old('address', $employee->address) }}"
                                                                    placeholder="+91 9876543210">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">User Type</label>
                                                                <select name="user_type" class="form-control w-100">
                                                                    <option value="Admin" {{ old('user_type', $employee->user_type) == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                                    <option value="Operator" {{ old('user_type', $employee->user_type) == 'Operator' ? 'selected' : '' }}>Operator</option>
                                                                </select>
                                                            </div>
                                                        </div>

@endif
                                                        <!-- Submit Button -->
                                                        <div class="col-12 mt-2">
                                                            <input type="submit" class="btn btn-primary"
                                                                value="Save changes">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>

                                        <div class="card">
                                            <div class="card-header-cu">
                                                <h6 class="mb-0">Change your password</h6>
                                            </div>

                                            <div class="card-body">
                                                @if (session('success'))
                                                    <div class="alert alert-success">{{ session('success') }}</div>
                                                @endif

                                                @if (session('error'))
                                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                                @endif

                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <form method="POST" action="{{ route('employee.changePassword') }}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label">Current password</label>
                                                        <input type="password" name="current_password"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">New password</label>
                                                        <input type="password" name="new_password" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Confirm new password</label>
                                                        <input type="password" name="new_password_confirmation"
                                                            class="form-control" required>
                                                    </div>
                                                    <input type="submit" class="btn btn-primary mt-2"
                                                        value="Update password">
                                                </form>
                                            </div>

                                        </div>

                                        <div class="card mt-4">
                                            <div class="card-header-cu">
                                                <h6 class="card-header-title text-danger">
                                                    Delete your account
                                                </h6>
                                            </div>
                                         <div class="card-body">
    @if(session('admin_logged_in'))
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            @method('DELETE')
            <p><strong>Alert</strong>: Once you delete your admin session, you will be logged out.</p>
            <input type="submit" class="btn btn-danger mt-2" value="Logout Admin Session">
        </form>
    @elseif(session('employee_logged_in'))
        <form action="{{ route('employee.delete', session('employee_id')) }}" method="POST">
            @csrf
            @method('DELETE')
            <p><strong>Alert</strong>: Once you delete your account, there is no going back.</p>
            <input type="submit" class="btn btn-danger mt-2" value="Delete your account">
        </form>
    @endif
</div>


                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Area -->

            </div>

        </div>
    </div>

    <!-- ======================================
    ********* Page Wrapper Area End ***********
    ======================================= -->

    <!-- Must needed plugins to the run this Template -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/default-assets/setting.js"></script>
    <script src="js/default-assets/scrool-bar.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/todo-list.js"></script>

    <!-- Active JS -->
    <script src="js/default-assets/active.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#updateProfileForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let submitBtn = $(this).find('input[type="submit"]');
            submitBtn.prop('disabled', true).val('Saving...');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Toastify({
                        text: "Profile updated successfully!",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87"
                    }).showToast();

                    // 🔁 Live UI Update - update name, email, avatar etc
                    if (response.updatedData) {
                        // Name update
                        $('#profile-name').text(response.updatedData.name);

                        // Email update
                        $('#profile-email').text(response.updatedData.email);

                        // Avatar update
                        if (response.updatedData.avatar_url) {
                            $('#profile-avatar').attr('src', response.updatedData.avatar_url);
                        }
                    }
                },
                error: function (xhr) {
                    let error = "Something went wrong.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        error = xhr.responseJSON.message;
                    }
                    Toastify({
                        text: error,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#ff0000"
                    }).showToast();
                },
                complete: function () {
                    submitBtn.prop('disabled', false).val('Save changes');
                }
            });
        });
    </script>


</body>

</html>
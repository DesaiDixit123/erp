<link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<link rel="stylesheet" href="{{ asset('style.css') }}">







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



  <!-- Page Content -->

  <div class="flapt-page-content">

    <!-- Top Header Area -->

    @include('layouts.topbar')



    @if(session('success'))

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">

      <div class="toast align-items-center text-bg-success border-0 show" role="alert">

      <div class="d-flex">

        <div class="toast-body">

        {{ session('success') }}

        </div>

        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>

      </div>

      </div>

    </div>

  @endif



    @if($errors->any())

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">

      <div class="toast align-items-center text-bg-danger border-0 show" role="alert">

      <div class="d-flex">

        <div class="toast-body">

        @foreach ($errors->all() as $error)

      {{ $error }}<br>

      @endforeach

        </div>

        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>

      </div>

      </div>

    </div>

  @endif



    <!-- Main Content Area -->

    <div class="main-content">

      <div class="content-wraper-area">

        <div class="container-fluid">

          <div class="row g-4">

            <div class="col-12">

              <div class="card shadow-sm border-0">

                <div class="card-body py-4 px-4">

                  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">

                    <div class="mb-2 mb-md-0">

                      <h4 class="mb-1 text-primary fw-semibold">Add Dashboard User</h4>

                      <p class="text-muted mb-0">Fill out the form below to add a new dashboard user</p>

                    </div>



                  </div>

                </div>

              </div>

            </div>



            <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">

              <div class="col-md-12">

                <div class="card shadow">

                  <div class="card-body">




                    <form id="employeeForm" action="{{ route('employee.store') }}" method="POST"
                      enctype="multipart/form-data" novalidate>
                      @csrf

                      <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="name" class="form-label">Name*</label>
                          <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="col-md-6">
                          <label for="email" class="form-label">Email*</label>
                          <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number*</label>
                        <input type="text" class="form-control" name="phone" id="phone" required maxlength="10"
                          pattern="\d{10}" title="Enter 10 digit number">
                      </div>

                      <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="2"></textarea>
                      </div>

                      <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="password" class="form-label">Create Password*</label>
                          <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                          <label for="password_confirmation" class="form-label">Retype Password*</label>
                          <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" required>
                          <div class="text-danger mt-1" id="passwordError" style="display: none;">Passwords do not match
                          </div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="user_type" class="form-label">User Type*</label>
                        <select class="form-control" name="user_type" id="user_type" required>
                          <option value="">-- Select User Type --</option>
                          <option value="Admin">Admin</option>
                          <option value="Store">Store</option>
                          <option value="Production">Production</option>
                          <option value="Inspection">Inspection</option>
                        </select>
                      </div>

                      <div class="row mb-4">
                        <div class="col-md-6">
                          <label for="adhar_image" class="form-label">Upload Adhar Image</label>
                          <input type="file" class="form-control" name="adhar_image" id="adhar_image" accept="image/*">
                        </div>
                        <div class="col-md-6">
                          <label for="pan_image" class="form-label">Upload Pan Card</label>
                          <input type="file" class="form-control" name="pan_image" id="pan_image" accept="image/*">
                        </div>
                      </div>

                      <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>



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



<script src="{{ asset('js/jquery.min.js') }}"> </script>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>

<script src="{{ asset('js/default-assets/setting.js') }}"> </script>

<script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>

<script src="{{ asset('js/todo-list.js') }}"> </script>

<script src="{{ asset('js/default-assets/active.js') }}"> </script>

<script src="{{ asset('js/apexcharts.min.js') }}"> </script>

<script src="{{ asset('js/dashboard-custom-sass.js') }}"> </script>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<!-- Toast Notification & Password Match Validation -->

<script>

  $(document).ready(function () {

    $('#employeeForm').on('submit', function (e) {

      e.preventDefault(); // Stop page reload



      const pass = $('#password').val();

      const confirmPass = $('#password_confirmation').val();

      const errorDiv = $('#passwordError');



      if (pass !== confirmPass) {

        errorDiv.show();

        return;

      } else {

        errorDiv.hide();

      }



      let formData = new FormData(this);



      $.ajax({

        url: $(this).attr('action'),

        method: 'POST',

        data: formData,

        contentType: false,

        processData: false,

        success: function (response) {

          // Remove any existing toasts

          $('.toast-container').remove();



          // Show success toast

          $('body').append(`

            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">

              <div class="toast align-items-center text-bg-success border-0 show" role="alert">

                <div class="d-flex">

                  <div class="toast-body">

                    ${response.message}

                  </div>

                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>

                </div>

              </div>

            </div>

          `);



          // Reset form

          $('#employeeForm')[0].reset();



          // Redirect after 2 seconds

          setTimeout(function () {

            window.location.href = response.redirect;

          }, 2000);

        },

        error: function (xhr) {

          const errors = xhr.responseJSON.errors;

          let errorMessages = '';



          for (let key in errors) {

            errorMessages += errors[key].join('<br>') + '<br>';

          }



          // Remove any existing toasts

          $('.toast-container').remove();



          // Show error toast

          $('body').append(`

            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">

              <div class="toast align-items-center text-bg-danger border-0 show" role="alert">

                <div class="d-flex">

                  <div class="toast-body">

                    ${errorMessages}

                  </div>

                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>

                </div>

              </div>

            </div>

          `);

        }

      });

    });

  });

</script>
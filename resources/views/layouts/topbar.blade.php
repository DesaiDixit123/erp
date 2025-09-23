<!doctype html>

<html lang="en">





<!-- Mirrored from demo.riktheme.com/flohan/side-menu/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Mar 2025 19:16:08 GMT -->



<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Title -->

  <title>Industry</title>



  <link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">

  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

  <link rel="stylesheet" href="{{ asset('css/animate.css') }}">



  <!-- Master Stylesheet [If you remove this CSS file, your file will be broken undoubtedly.] -->

  <link rel="stylesheet" href="{{ asset('style.css') }}">





</head>



<body>



  <header class="top-header-area d-flex align-items-center justify-content-between">

    <div class="left-side-content-area d-flex align-items-center">

      <!-- Mobile Logo -->

      <div class="mobile-logo">

        <a href="index-2.html"><img src="i{{ asset('img/logo.png') }}" alt="Mobile Logo" /></a>

      </div>



      <!-- Triggers -->

      <div class="flapt-triggers">

        <div class="menu-collasped" id="menuCollasped">

          <i class="bx bx-grid-alt"></i>

        </div>

        <div class="mobile-menu-open" id="mobileMenuOpen">

          <i class="bx bx-grid-alt"></i>

        </div>

      </div>



      <!-- Left Side Nav -->

      <ul class="left-side-navbar d-flex align-items-center">

        <!-- Search Box -->

        <li class="hide-phone app-search">

          <input type="text" class="form-control" placeholder="Search..." />

          <span class="bx bx-search-alt"></span>

        </li>



      </ul>



    </div>



    <div class="right-side-navbar d-flex align-items-center justify-content-end">

      <!-- Mobile Trigger -->

      <div class="right-side-trigger" id="rightSideTrigger">

        <i class="bx bx-menu-alt-right"></i>

      </div>



      <!-- Top Bar Nav -->

      <ul class="right-side-content d-flex align-items-center">

        <li class="nav-item dropdown">

          <div class="btn dropdown-toggle navicon-wrap action-dark">

            <i class="bx bx-moon icon-light"></i>

            <i class="bx bx-sun icon-dark"></i>

          </div>

        </li>

        <li class="nav-item dropdown">

          <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"

            aria-expanded="false">

            <span><i class='bx bx-flag'></i></span>

          </button>

          <div class="dropdown-menu language-dropdown dropdown-menu-right">

            <div class="user-profile-area">

              <a href="#" class="dropdown-item mb-15"><img src="img/core-img/l5.jpg" alt="Image" />

                <span>USA</span></a>

              <a href="#" class="dropdown-item mb-15"><img src="img/core-img/l2.jpg" alt="Image" />

                <span>German</span></a>

              <a href="#" class="dropdown-item mb-15"><img src="img/core-img/l3.jpg" alt="Image" />

                <span>Italian</span></a>

              <a href="#" class="dropdown-item"><img src="img/core-img/l4.jpg" alt="Image" />

                <span>Russian</span></a>

            </div>

          </div>

        </li>



        <li class="nav-item dropdown">

          <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"

            aria-expanded="false">

            <i class="bx bx-envelope"></i>

          </button>

          <div class="dropdown-menu dropdown-menu-right">

            <!-- Message Area -->

            <div class="top-message-area">

              <!-- Heading -->

              <div class="message-heading">

                <div class="heading-title">

                  <h6 class="mb-0">All Messages</h6>

                </div>

                <span>10</span>

              </div>



              <div class="message-box" id="messageBox">

                <a href="#" class="dropdown-item">

                  <i class="bx bx-dollar-circle"></i>

                  <div>

                    <span>Did you know?</span>

                    <p class="mb-0 font-12">

                      Adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-shopping-bag"></i>

                  <div>

                    <span>Congratulations! </span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit.

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-wallet-alt"></i>

                  <div>

                    <span>Hello Obeta</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit.

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-border-all"></i>

                  <div>

                    <span>Your order is placed</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit.

                    </p>

                  </div>

                </a>

                <a href="#" class="dropdown-item">

                  <i class="bx bx-wallet-alt"></i>

                  <div>

                    <span>Haslina Obeta</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit.

                    </p>

                  </div>

                </a>

              </div>

            </div>

          </div>

        </li>



        <li class="nav-item dropdown">

          <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"

            aria-expanded="false">

            <i class="bx bx-bell bx-tada"></i>

            <span class="active-status"></span>

          </button>

          <div class="dropdown-menu dropdown-menu-right">

            <!-- Top Notifications Area -->

            <div class="top-notifications-area">

              <!-- Heading -->

              <div class="notifications-heading">

                <div class="heading-title">

                  <h6>Notifications</h6>

                </div>

                <span>11</span>

              </div>



              <div class="notifications-box" id="notificationsBox">

                <a href="#" class="dropdown-item">

                  <i class="bx bx-shopping-bag"></i>

                  <div>

                    <span>Your order is placed</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-wallet-alt"></i>

                  <div>

                    <span>Haslina Obeta</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-dollar-circle"></i>

                  <div>

                    <span>Your order is Dollar</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-wallet-alt"></i>

                  <div>

                    <span>Haslina Obeta</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>



                <a href="#" class="dropdown-item">

                  <i class="bx bx-border-all"></i>

                  <div>

                    <span>Your order is placed</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>

                <a href="#" class="dropdown-item">

                  <i class="bx bx-wallet-alt"></i>

                  <div>

                    <span>Haslina Obeta</span>

                    <p class="mb-0 font-12">

                      Consectetur adipisicing elit. Ipsa, porro!

                    </p>

                  </div>

                </a>

              </div>

            </div>

          </div>

        </li>



        <li class="nav-item dropdown">

          <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"

            aria-expanded="false">

            @if (session('employee_logged_in'))

        <img src="https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg" alt="Employee Image"

          style="width: 100%; background-size: cover; background-repeat: no-repeat; " />

          @else

    <img src="https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg" alt="Default Image"

        style="width: 100%; background-size: cover; background-repeat: no-repeat;" />

       

      @endif

          </button>

          <div class="dropdown-menu profile dropdown-menu-right">
            <!-- User Profile Area -->
            <div class="user-profile-area">
              <a href="{{ session('admin') ? route('adminProfile.view') : route('profile.view') }}"
                class="dropdown-item"><i class="bx bx-user font-15" aria-hidden="true"></i>
                My
                profile</a>


              <a href="{{ asset('logout') }}" class="dropdown-item">


                <i class="bx bx-power-off font-15" aria-hidden="true"></i>
                Sign-out

              </a>
            </div>
          </div>
        </li>

      </ul>

    </div>

  </header>

  <!-- ======================================

    ********* Page Wrapper Area End ***********

    ======================================= -->



  <!-- Must needed plugins to the run this Template -->

  <!-- JS -->

  <script src="{{ asset('js/jquery.min.js') }}"> </script>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>

  <script src="{{ asset('js/default-assets/setting.js') }}"> </script>

  <script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>

  <script src="{{ asset('js/todo-list.js') }}"> </script>

  <script src="{{ asset('js/default-assets/active.js') }}"> </script>

  <script src="{{ asset('js/apexcharts.min.js') }}"> </script>

  <script src="{{ asset('js/dashboard-custom-sass.js') }}"> </script>





</body>





<!-- Mirrored from demo.riktheme.com/flohan/side-menu/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Mar 2025 19:16:08 GMT -->



</html>
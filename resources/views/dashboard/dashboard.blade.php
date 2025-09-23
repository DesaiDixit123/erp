<!doctype html>

<html lang="en">





<!-- Mirrored from demo.riktheme.com/flohan/side-menu/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Mar 2025 19:16:08 GMT -->



<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Title -->

  <title>Industry</title>

  <!-- Favicon -->

  <link rel="icon" href="img/core-img/favicon.ico">

  <!-- Plugins File -->

  <link rel="stylesheet" href="css/bootstrap.min.css">

  <link rel="stylesheet" href="css/animate.css">



  <!-- Master Stylesheet [If you remove this CSS file, your file will be broken undoubtedly.] -->

  <link rel="stylesheet" href="style.css">



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



    <!-- Page Content -->

    <div class="flapt-page-content">

      <!-- Top Header Area -->

      @include('layouts.topbar')



      <!-- Main Content Area -->

      <div class="main-content introduction-farm">

        <div class="content-wraper-area">

          <div class="dashboard-area">

            <div class="container-fluid">

              <div class="row g-4">

                <div class="col-12">

                  <div class="d-flex align-items-center justify-content-between">

                    <div class="dashboard-header-title">

                      <div class="keyboard">

                        <span class="key">H</span>

                        <span class="key">e</span>

                        <span class="key">l</span>

                        <span class="key">l</span>

                        <span class="key">O</span>

                        <span class="key">,</span>



                        @if (session('employee_logged_in'))

                    @foreach (str_split(session('employee_name')) as $char)

                <span class="key">{{ $char }}</span>

              @endforeach

            @elseif (session('admin_logged_in'))

                    @foreach (str_split(session('admin_name')) as $char)

                <span class="key">{{ $char }}</span>

              @endforeach

            @else
              <span class="key">A</span>
              <span class="key">D</span>
              <span class="key">M</span>
              <span class="key">I</span>
              <span class="key">N</span>

            @endif

                      </div>





                      <p class="mb-0">Here's a summary of your account activity for this week.

                      </p>

                    </div>



                    <div class="dashboard-infor-mation">

                      <div class="dashboard-btn-group d-flex align-items-center">

                        <a href="#" class="dash-btn ms-2"><i class="ti-settings"></i></a>

                        <a href="#" class="dash-btn ms-2"><i class="ti-plus"></i></a>

                      </div>

                    </div>

                  </div>

                </div>



                <div class="col-sm-6 col-lg-6 col-xl-4 col-xxl-3">

                  <div class="card mb-25">

                    <div class="card-body" data-intro="Revenue">

                      <div class="single-widget d-flex align-items-center justify-content-between">

                        <div>

                          <div class="widget-icon">

                            <i class="bx bx-wallet"></i>

                          </div>

                          <div class="widget-desc">

                            <h5>Revenue</h5>

                            <p class="mb-0">Awating Processing</p>

                          </div>

                        </div>

                        <div class="progress-report" data-title="progress" data-intro="And this is the last step!">

                          <p>- 2.56%</p>

                        </div>

                      </div>

                    </div>

                  </div>



                  <div class="card">

                    <div class="card-body" data-intro="Growth">

                      <div class="single-widget d-flex align-items-center justify-content-between">

                        <div>

                          <div class="widget-icon">

                            <i class="bx bx-bar-chart-alt-2"></i>

                          </div>

                          <div class="widget-desc">

                            <h5>Growth</h5>

                            <p class="mb-0">Awating Processing</p>

                          </div>

                        </div>

                        <div class="progress-report" data-title="progress" data-intro="And this is the last step!">

                          <p>+ 2.56%</p>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>



                <div class="col-sm-6 col-lg-6 col-xl-8 col-xxl-9">

                  <div class="card w-100">

                    <div class="card-body pb-4">

                      <div class="card-title">

                        <h4>Monthly Earnings</h4>

                        <h6 class="text-success">$6,820</h6>

                      </div>



                      <div id="most-visited"></div>

                    </div>

                  </div>

                </div>



                <div class="col-lg-8">

                  <div class="card">

                    <div class="card-body">

                      <div class="chart-area">

                        <div id="salesOverview2"></div>

                      </div>

                    </div>

                  </div>

                </div>



                <div class="col-lg-4">

                  <div class="card mb-30">

                    <div class="card-body pb-0">

                      <div
                        class="card-header border-none bg-transparent d-flex align-items-center justify-content-between p-0 mb-30">

                        <div class="card-title mb-0">

                          <h6 class="mb-0">Monthly Income</h6>

                        </div>

                        <div class="dashboard-dropdown">

                          <div class="dropdown">

                            <button class="btn dropdown-toggle" type="button" id="dashboardDropdown5"
                              data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                class="ti-more"></i></button>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dashboardDropdown5">

                              <a class="dropdown-item" href="#"><i class="ti-pencil-alt"></i> Edit</a>

                              <a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>

                              <a class="dropdown-item" href="#"><i class="ti-eraser"></i> Remove</a>

                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>

                            </div>

                          </div>

                        </div>

                      </div>



                      <ul class="suggestions-lists">

                        <li class="d-flex justify-content-between mb-30 align-items-center">

                          <div class="d-flex align-items-center">

                            <div><i class="bx bx-check bg-primary text-white profile-icon"></i></div>

                            <div class="media-support-info">

                              <h6 class="mb-1 font-16">Jadrsha ios</h6>

                              <p class="mb-0 font-12"><span class="text-success">19 paid</span> out of 25</p>

                            </div>

                          </div>

                          <div class="media-support-amount">

                            <h6 class="mb-1 font-15"><span class="text-secondary">$</span>23,563</h6>

                            <p class="mb-0 font-12">Per month</p>

                          </div>

                        </li>



                        <li class="d-flex justify-content-between mb-30 align-items-center">

                          <div class="d-flex align-items-center">

                            <div><i class="bx bx-check bg-success text-white profile-icon"></i></div>

                            <div class="media-support-info">

                              <h6 class="mb-1 font-16">Lobanda Ltd.</h6>

                              <p class="mb-0 font-12"><span class="text-success">23 paid</span> out of 29</p>

                            </div>

                          </div>

                          <div class="media-support-amount">

                            <h6 class="mb-1 font-15"><span class="text-secondary">$</span>15,563</h6>

                            <p class="mb-0 font-12">Per month</p>

                          </div>

                        </li>



                        <li class="d-flex justify-content-between mb-30 align-items-center">

                          <div class="d-flex align-items-center">

                            <div><i class="bx bx-check bg-warning text-white profile-icon"></i></div>

                            <div class="media-support-info">

                              <h6 class="mb-1 font-16">Hiskla ios</h6>

                              <p class="mb-0 font-12"><span class="text-success">17 paid</span> out of 23</p>

                            </div>

                          </div>

                          <div class="media-support-amount">

                            <h6 class="mb-1 font-15"><span class="text-secondary">$</span>14,563</h6>

                            <p class="mb-0 font-12">Per month</p>

                          </div>

                        </li>



                        <li class="d-flex justify-content-between mb-30 align-items-center">

                          <div class="d-flex align-items-center">

                            <div><i class="bx bx-check bg-danger text-white profile-icon"></i></div>

                            <div class="media-support-info">

                              <h6 class="mb-1 font-16">Jadrsha ios</h6>

                              <p class="mb-0 font-12"><span class="text-success">19 paid</span> out of 25</p>

                            </div>

                          </div>

                          <div class="media-support-amount">

                            <h6 class="mb-1 font-15"><span class="text-secondary">$</span>23,563</h6>

                            <p class="mb-0 font-12">Per month</p>

                          </div>

                        </li>

                        <li class="d-flex justify-content-between mb-30 align-items-center">

                          <div class="d-flex align-items-center">

                            <div><i class="bx bx-check bg-success text-white profile-icon"></i></div>

                            <div class="media-support-info">

                              <h6 class="mb-1 font-16">Lobanda Ltd.</h6>

                              <p class="mb-0 font-12"><span class="text-success">23 paid</span> out of 29</p>

                            </div>

                          </div>

                          <div class="media-support-amount">

                            <h6 class="mb-1 font-15"><span class="text-secondary">$</span>15,563</h6>

                            <p class="mb-0 font-12">Per month</p>

                          </div>

                        </li>

                      </ul>

                    </div>

                  </div>

                </div>



                <div class="col-md-6 col-lg-8">

                  <div class="card">

                    <div class="card-body">

                      <div class="card-title mb-30 d-flex align-items-center justify-content-between">

                        <h6 class="mb-0">Order Status</h6>

                        <div class="dashboard-dropdown">

                          <div class="dropdown">

                            <button class="btn dropdown-toggle" type="button" id="dashboardDropdown55"
                              data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                              <i class="ti-more"></i>

                            </button>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dashboardDropdown55">

                              <a class="dropdown-item" href="#"><i class="ti-pencil-alt"></i> Edit</a>

                              <a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>

                              <a class="dropdown-item" href="#"><i class="ti-eraser"></i> Remove</a>

                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>

                            </div>

                          </div>

                        </div>

                      </div>

                      <div class="recent-order-card">

                        <ul class="nav nav-pills mb-3" id="pills-tab1" role="tablist">

                          <li class="nav-item" role="presentation">

                            <button class="nav-link active" id="pills-home-tab1" data-bs-toggle="pill"
                              data-bs-target="#pills-home1" type="button" role="tab" aria-controls="pills-home1"
                              aria-selected="true">Chair</button>

                          </li>

                          <li class="nav-item" role="presentation">

                            <button class="nav-link" id="pills-profile-tab1" data-bs-toggle="pill"
                              data-bs-target="#pills-profile1" type="button" role="tab" aria-controls="pills-profile1"
                              aria-selected="false">Watch</button>

                          </li>

                          <li class="nav-item" role="presentation">

                            <button class="nav-link" id="pills-contact-tab1" data-bs-toggle="pill"
                              data-bs-target="#pills-contact1" type="button" role="tab" aria-controls="pills-contact1"
                              aria-selected="false">T-shirt</button>

                          </li>

                          <li class="nav-item" role="presentation">

                            <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill"
                              data-bs-target="#pills-contact2" type="button" role="tab" aria-controls="pills-contact2"
                              aria-selected="false">Shoes</button>

                          </li>



                        </ul>

                        <div class="tab-content" id="pills-tabContent1">

                          <div class="tab-pane fade show active" id="pills-home1" role="tabpanel"
                            aria-labelledby="pills-home-tab1" tabindex="0">

                            <div class="order-product-card">

                              <div class="recent-order-pro-card-list table-responsive text-nowrap">

                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">

                                  <!-- Table -->

                                  <thead>

                                    <tr>

                                      <th>ITEM</th>

                                      <th>QTY</th>

                                      <th>PRICE</th>

                                      <th>Paid</th>

                                      <th>TOTAL PRICE</th>

                                    </tr>

                                  </thead>

                                  <tbody>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/7.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Adirondack Chair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>15</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$10.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/6.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Bean Bag Chair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #ZDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>7</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/8.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Plastic Armchair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-234</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>19</span>

                                      </td>

                                      <td>

                                        <span class="">$122.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$116.00</span>

                                      </td>

                                    </tr>

                                  </tbody>

                                </table>

                              </div>

                            </div>



                          </div>

                          <div class="tab-pane fade" id="pills-profile1" role="tabpanel"
                            aria-labelledby="pills-profile-tab1" tabindex="0">

                            <div class="order-product-card">

                              <div class="recent-order-pro-card-list table-responsive text-nowrap">

                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">

                                  <!-- Table -->

                                  <thead>

                                    <tr>

                                      <th>ITEM</th>

                                      <th>QTY</th>

                                      <th>PRICE</th>

                                      <th>Paid</th>

                                      <th>TOTAL PRICE</th>

                                    </tr>

                                  </thead>

                                  <tbody>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/1.png" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">New Clock

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>15</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$10.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/2.png" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Bean Clock

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #ZDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>7</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/3.png" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Plastic Clock

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-234</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>19</span>

                                      </td>

                                      <td>

                                        <span class="">$122.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$116.00</span>

                                      </td>

                                    </tr>

                                  </tbody>

                                </table>

                              </div>

                            </div>

                          </div>

                          <div class="tab-pane fade" id="pills-contact1" role="tabpanel"
                            aria-labelledby="pills-contact-tab1" tabindex="0">

                            <div class="order-product-card">

                              <div class="recent-order-pro-card-list table-responsive text-nowrap">

                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">

                                  <!-- Table -->

                                  <thead>

                                    <tr>

                                      <th>ITEM</th>

                                      <th>QTY</th>

                                      <th>PRICE</th>

                                      <th>Paid</th>

                                      <th>TOTAL PRICE</th>

                                    </tr>

                                  </thead>

                                  <tbody>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/7.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Adirondack Chair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>15</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$10.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/6.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Bean Bag Chair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #ZDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>7</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/8.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Plastic Armchair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-234</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>19</span>

                                      </td>

                                      <td>

                                        <span class="">$122.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$116.00</span>

                                      </td>

                                    </tr>

                                  </tbody>

                                </table>

                              </div>

                            </div>

                          </div>

                          <div class="tab-pane fade" id="pills-contact2" role="tabpanel"
                            aria-labelledby="pills-contact-tab2" tabindex="0">

                            <div class="order-product-card">

                              <div class="recent-order-pro-card-list table-responsive text-nowrap">

                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">

                                  <!-- Table -->

                                  <thead>

                                    <tr>

                                      <th>ITEM</th>

                                      <th>QTY</th>

                                      <th>PRICE</th>

                                      <th>Paid</th>

                                      <th>TOTAL PRICE</th>

                                    </tr>

                                  </thead>

                                  <tbody>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/7.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Adirondack Chair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>15</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$10.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/6.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Bean Bag Chair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #ZDG-2321</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>7</span>

                                      </td>

                                      <td>

                                        <span class="">$72.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$126.00</span>

                                      </td>

                                    </tr>

                                    <tr>

                                      <td class="d-flex align-items-center">

                                        <img src="img/shop-img/8.jpg" class="shop-img-pro" alt="">

                                        <div>

                                          <a href="#" class=" font-15">Plastic Armchair

                                          </a>

                                          <span class="d-block font-12">Item:

                                            #XDG-234</span>

                                        </div>

                                      </td>



                                      <td>

                                        <span>19</span>

                                      </td>

                                      <td>

                                        <span class="">$122.00</span>

                                      </td>

                                      <td class="text-success">$12.00</td>

                                      <td>

                                        <span class="">$116.00</span>

                                      </td>

                                    </tr>

                                  </tbody>

                                </table>

                              </div>

                            </div>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- Sales Chart -->

                <div class="col-md-6 col-lg-4">

                  <div class="card">

                    <div class="card-body">

                      <div class="card-title">

                        <h4>Sales Overview</h4>

                        <p class="mb-0">Overview of Profit</p>

                      </div>

                      <div id="revenue-updates"></div>

                    </div>

                  </div>

                </div>



                <!-- Shop Table -->

                <div class="col-12">

                  <div class="card">

                    <div class="card-body">

                      <div
                        class="card-title border-bootom-none mb-30 d-sm-flex align-items-center justify-content-between">

                        <h6 class="mb-0">Best Selling Products</h6>

                        <div class="shop-tab-area">

                          <ul class="nav nav-pills" id="pills-tab" role="tablist">

                            <li class="nav-item" role="presentation">

                              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Daily</button>

                            </li>

                            <li class="nav-item" role="presentation">

                              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Weekly</button>

                            </li>

                            <li class="nav-item" role="presentation">

                              <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">Monthly</button>

                            </li>

                          </ul>

                        </div>

                      </div>



                      <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                          aria-labelledby="pills-home-tab" tabindex="0">

                          <div class="table-responsive text-nowrap">

                            <table class="table table-centered table-nowrap table-hover mb-0">

                              <thead>

                                <tr>

                                  <th>Product Name</th>

                                  <th>Sold</th>

                                  <th>Total Sale</th>

                                  <th>Stutas</th>

                                  <th>Action</th>

                                </tr>

                              </thead>

                              <tbody>

                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/2.png" alt="" />

                                    <span>Sound Box</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/1.png" alt="" />

                                    <span>Head Phone</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/4.png" alt="" />

                                    <span>New Sound</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="badges text-danger">Stock out</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/1.png" alt="" />

                                    <span>Sound Box</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>

                              </tbody>

                            </table>

                          </div>

                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                          aria-labelledby="pills-profile-tab" tabindex="0">

                          <div class="table-responsive text-nowrap">

                            <table class="table table-centered table-nowrap table-hover mb-0">

                              <thead>

                                <tr>

                                  <th>Product Name</th>

                                  <th>Sold</th>

                                  <th>Total Sale</th>

                                  <th>Stutas</th>

                                  <th>Action</th>

                                </tr>

                              </thead>

                              <tbody>

                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/1.png" alt="" />

                                    <span>Sound Box</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/3.png" alt="" />

                                    <span>Head Phone</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/4.png" alt="" />

                                    <span>New Sound</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="badges text-danger">Stock out</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/1.png" alt="" />

                                    <span>Sound Box</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>

                              </tbody>

                            </table>

                          </div>

                        </div>

                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                          aria-labelledby="pills-contact-tab" tabindex="0">

                          <div class="table-responsive text-nowrap">

                            <table class="table table-centered table-nowrap table-hover mb-0">

                              <thead>

                                <tr>

                                  <th>Product Name</th>

                                  <th>Sold</th>

                                  <th>Total Sale</th>

                                  <th>Stutas</th>

                                  <th>Action</th>

                                </tr>

                              </thead>

                              <tbody>

                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/1.png" alt="" />

                                    <span>Sound Box</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/3.png" alt="" />

                                    <span>Head Phone</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/4.png" alt="" />

                                    <span>New Sound</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="badges text-danger">Stock out</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>



                                <tr>

                                  <td class="d-flex align-items-center">

                                    <img class="shop-img" src="img/shop-img/1.png" alt="" />

                                    <span>Sound Box</span>

                                  </td>

                                  <td>$88.49</td>

                                  <td>$125</td>

                                  <td class="text-success">In stock</td>

                                  <td><a class="table-btn" href="#">View</a></td>

                                </tr>

                              </tbody>

                            </table>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>





              </div>

            </div>

          </div>

        </div>



        <!-- Footer Area -->

        <div class="footer-content-area">

          <div class="container-fluid">

            <div class="row">

              <div class="col-12">

                <!-- Footer Area -->

                <footer class="footer-area d-sm-flex justify-content-center align-items-center justify-content-between">

                  <!-- Copywrite Text -->

                  <div class="copywrite-text">

                    <p class="font-13">

                      Developed by &copy; <a href="#">Flohan</a>

                    </p>

                  </div>

                  <div class="fotter-icon text-center">

                    <p class="mb-0 font-13">2025 &copy; FlohanAdmin</p>

                  </div>

                </footer>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>



  <!-- ======================================

    ********* Page Wrapper Area End ***********

    ======================================= -->

  <script src="{{ asset('js/jquery.min.js') }}"> </script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>
  <script src="{{ asset('js/apexcharts.min.js') }}"> </script>
  <script src="{{ asset('js/dashboard-custom.js') }}"> </script>
  <script src="{{ asset('js/default-assets/setting.js') }}"> </script>
  <script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>
  <script src="{{ asset('js/todo-list.js') }}"> </script>
  <script src="{{ asset('js/default-assets/active.js') }}"> </script>









</body>





<!-- Mirrored from demo.riktheme.com/flohan/side-menu/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Mar 2025 19:16:08 GMT -->



</html>
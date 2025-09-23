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

<div class="flapt-page-wrapper">
    <!-- Sidemenu Area -->
    <div class="flapt-sidemenu-wrapper">
     @include('layouts.sidebar')
    </div>

    <div class="flapt-page-content">
        @include('layouts.topbar')

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="content-wraper-area">
                <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row g-4">
                        <!-- <div class="col-12">
                            <div class="card">
                                <div class="card-body card-breadcrumb">
                                    <div class="page-title-box d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">Dashboard User Info</h4>
                                       
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="pt-4 pb-4 border-bottom">
                                        <div class="personal-info-area">
                                            <!-- <p class="mb-2 text-dark">Dashboard User Info:</p> -->
                                            <ul class="list-group personal-data">
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2"> Name : </div>
                                                        <span class="text-muted">{{ $employee->name }}</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2"> Email : </div>
                                                        <span class="text-muted">{{ $employee->email }}</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2"> Phone : </div>
                                                        <span class="text-muted">{{ $employee->phone ?? 'N/A' }}</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2"> Designation : </div>
                                                        <span
                                                            class="text-muted">{{ $employee->user_type ?? 'N/A' }}</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2"> Status : </div>
                                                        <span class="text-muted">{{ $employee->user_status }}</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2"> Address : </div>
                                                        <span class="text-muted">{{ $employee->address }}</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2">Pan Card :</div>
                                                        @if($employee->pan_image)
                                                            <img src="{{ asset('storage/' . $employee->pan_image) }}"
                                                                alt="Pan Card" style="max-width: 100px; height: auto;">
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </div>
                                                </li>

                                                <li class="list-group-item">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="me-2">Aadhar Card :</div>
                                                        @if($employee->adhar_image)
                     <img src="{{ asset('storage/' . $employee->adhar_image) }}" alt="Aadhar Card" style="max-width: 100px; height: auto;">

                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </div>
                                                </li>

                                                {{-- Optional Fields --}}
                                                @if(!empty($employee->age))
                                                    <li class="list-group-item">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="me-2"> Age : </div>
                                                            <span class="text-muted">{{ $employee->age }}</span>
                                                        </div>
                                                    </li>
                                                @endif

                                                @if(!empty($employee->experience))
                                                    <li class="list-group-item">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="me-2"> Experience : </div>
                                                            <span class="text-muted">{{ $employee->experience }}</span>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card-body -->
                            </div> <!-- card -->
                        </div> <!-- col -->
                    </div> <!-- row -->
                </div> <!-- container-fluid -->
            </div> <!-- content-wraper-area -->
        </div> <!-- main-content -->
    </div> <!-- flapt-page-content -->
</div> <!-- flapt-page-wrapper -->


<script src="{{ asset('js/jquery.min.js') }}"> </script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>
<script src="{{ asset('js/default-assets/setting.js') }}"> </script>
<script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>
<script src="{{ asset('js/todo-list.js') }}"> </script>
<script src="{{ asset('js/default-assets/active.js') }}"> </script>
<script src="{{ asset('js/apexcharts.min.js') }}"> </script>
<script src="{{ asset('js/dashboard-custom-sass.js') }}"> </script>

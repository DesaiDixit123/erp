<link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<link rel="stylesheet" href="{{ asset('style.css') }}">









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

                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>

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

                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>

                    </div>

                </div>

            </div>

        @endif



        <!-- Main Content Area -->

        <div class="main-content">

            <div class="content-wraper-area">

                <div class="container-fluid">

                    <div class="row g-4">
                        <!-- 
                        <div class="col-12">

                            <div class="card shadow-sm border-0">

                                <div class="card-body py-4 px-4">

                                    <div
                                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">

                                        <div class="mb-2 mb-md-0">

                                            <h4 class="mb-1 text-primary fw-semibold">Add Tool</h4>

                                            <p class="text-muted mb-0">Fill out the form below to add a new tool</p>

                                        </div>



                                    </div>

                                </div>

                            </div>

                        </div> -->



                        <div class="d-flex justify-content-center min-vh-100 bg-light">

                            <div class="col-md-12">

                                <div class="card shadow">
                                    <div class="card-body">
                                     <form action="{{ route('dispatches.update', $dispatch->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="row">
        <!-- Component Dropdown -->
        <div class="mb-3 col-md-3">
            <label for="component_id" class="form-label">Component*</label>
            <select class="form-control" name="component_id" id="component_id" required>
                <option value="">Select Component</option>
                @foreach($tools as $tool)
                    <option value="{{ $tool->id }}" 
                        {{ old('component_id', $dispatch->component_id) == $tool->id ? 'selected' : '' }}>
                        {{ $tool->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tool Type Dropdown -->
        <div class="mb-3 col-md-3">
            <label for="component_type" class="form-label">Tool Type</label>
            <select class="form-control" name="component_type" id="component_type">
                <option value="">Select Tool Type</option>
                <option value="Casting Tool" {{ old('component_type', $dispatch->component_type) === 'Casting Tool' ? 'selected' : '' }}>Casting Tool</option>
                <option value="Trimming Tool" {{ old('component_type', $dispatch->component_type) === 'Trimming Tool' ? 'selected' : '' }}>Trimming Tool</option>
            </select>
        </div>

        <!-- Tool Number -->
        <div class="mb-3 col-md-3">
            <label for="tool_number" class="form-label">Tool Number</label>
            <input type="text" class="form-control" id="tool_number" name="tool_number" 
                   value="{{ old('tool_number', $dispatch->tool_number) }}" placeholder="Enter Tool Number">
        </div>

      <!-- Manufacturing Date -->
<div class="mb-3 col-md-3">
    <label for="manufacturing_date" class="form-label">Manufacturing Date</label>
    <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date"
        value="{{ old('manufacturing_date', optional($dispatch->manufacturing_date)->format('Y-m-d')) }}">
</div>


      
    </div>

    <!-- Submit Button -->
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Update Tool</button>
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
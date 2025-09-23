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
                                        <form action="{{ route('dispatches.store') }}" method="POST">
                                            @csrf

                                            <div class="row">

                                                <!-- Component Dropdown -->
                                                <div class="mb-3 col-md-3">
                                                    <label for="component_id" class="form-label">Component*</label>
                                                    <select class="form-control" name="component_id" id="component_id"
                                                        required>
                                                        <option value="">Select Component</option>
                                                        @foreach($tools as $tool)
                                                            <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Tool Type Dropdown -->
                                                <div class="mb-3 col-md-3">
                                                    <label for="component_type" class="form-label">Tool Type</label>
                                                    <select class="form-control" name="component_type"
                                                        id="component_type">
                                                        <option value="">Select Tool Type</option>
                                                        <option value="Casting Tool">Casting Tool</option>
                                                        <option value="Trimming Tool">Trimming Tool</option>
                                                    </select>
                                                </div>

                                                <!-- Tool Number -->
                                                <div class="mb-3 col-md-3">
                                                    <label for="tool_number" class="form-label">Tool Number</label>
                                                    <input type="text" class="form-control" id="tool_number"
                                                        name="tool_number" placeholder="Enter Tool Number">
                                                </div>

                                                <!-- Manufacturing Date -->
                                                <div class="mb-3 col-md-3">
                                                    <label for="manufacturing_date" class="form-label">Manufacturing
                                                        Date</label>
                                                    <input type="date" class="form-control" id="manufacturing_date"
                                                        name="manufacturing_date">
                                                </div>

                                             
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Add Tool</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                           <div class="card shadow mt-4">
    <div class="card-body">
        <h5 class="mb-3 text-primary">All Tools</h5>

        <!-- 🔎 Filters Form -->
        <form method="GET" action="{{ route('toools.index') }}" class="row g-3 mb-3">
            <!-- Component Search -->
            <div class="col-md-4">
                <input type="text" name="component" value="{{ request('component') }}" 
                       class="form-control" placeholder="Search by Component Name">
            </div>

            <!-- Tool Type Dropdown -->
            <div class="col-md-4">
                <select name="tool_type" class="form-control">
                    <option value="">-- Select Tool Type --</option>
                    <option value="Casting Tool" {{ request('tool_type') == 'Casting Tool' ? 'selected' : '' }}>
                        Casting Tool
                    </option>
                    <option value="Trimming Tool" {{ request('tool_type') == 'Trimming Tool' ? 'selected' : '' }}>
                        Trimming Tool
                    </option>
                </select>
            </div>

            <!-- Manufacturing Date -->
            <div class="col-md-3">
                <input type="date" name="manufacturing_date" value="{{ request('manufacturing_date') }}"
                       class="form-control">
            </div>

            <!-- Submit Button -->
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Sr No.</th>
                        <th>Component</th>
                        <th>Tool Type</th>
                        <th>Tool Number</th>
                        <th>Manufacturing Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispatches as $index => $dispatch)
                        <tr>
                            <td>{{ $dispatches->firstItem() + $loop->index }}</td>
                            <td>{{ $dispatch->component->name ?? '-' }}</td>
                            <td>{{ $dispatch->component_type ?? '-' }}</td>
                            <td>{{ $dispatch->tool_number ?? '-' }}</td>
                            <td>
                                {{ $dispatch->manufacturing_date
                                    ? \Carbon\Carbon::parse($dispatch->manufacturing_date)->timezone('Asia/Kolkata')->format('d M Y')
                                    : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('dispatches.edit', $dispatch->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('dispatches.destroy', $dispatch->id) }}" method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this dispatch?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No dispatch records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {!! $dispatches->links('pagination::bootstrap-5') !!}
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
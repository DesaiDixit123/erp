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



                        <div class="d-flex justify-content-center  min-vh-100 bg-light">

                            <div class="col-md-12">

                                <div class="card shadow">

                                    <div class="card-body">

                                        <form action="{{ route('components.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <div class="row">

                                                <!-- Tool Name -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="name" class="form-label">Component Name*</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        required>
                                                </div>

                                                <!-- Company Dropdown -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="company_id" class="form-label">Customer Name*</label>
                                                    <select class="form-control" name="company_id" id="company_id" required>
                                                        <option value="">Select Customer Name</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->id }}" 
                                                                {{ old('company_id', $employee->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                                {{ $company->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Tool Image -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="image" class="form-label">Component Image</label>
                                                    <input type="file" class="form-control" id="image" name="image"
                                                        accept="image/*">
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Add Component</button>
                                            </div>
                                        </form>


                                    </div>

                                </div>



                                <!-- All Services Table -->

                            <div class="card shadow mt-4">
    <div class="card-body">
        <h5 class="mb-3 text-primary">All Components</h5>

        <!-- 🔎 Search Box -->
        <form method="GET" action="{{ route('components.index') }}" class="mb-3 d-flex">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control me-2" placeholder="Search by Component or Customer Name">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Sr No.</th>
                        <th>Component Name</th>
                        <th>Customer Name</th>
                        <th>Component Image</th>
                        <th>Updated Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tools as $index => $tool)
                        <tr>
                            <td>{{ $tools->firstItem() + $loop->index }}</td>
                            <td>{{ $tool->name }}</td>
                            <td>{{ $tool->company->name ?? 'N/A' }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $tool->image) }}" alt="Component Image" width="60">
                            </td>
                            <td>{{ $tool->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('components.edit', $tool->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('components.destroy', $tool->id) }}"
                                      method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this component?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No tools found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {!! $tools->links('pagination::bootstrap-5') !!}
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
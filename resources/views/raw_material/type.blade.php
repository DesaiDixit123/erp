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

                        <!-- <div class="col-12">

                            <div class="card shadow-sm border-0">

                                <div class="card-body py-4 px-4">

                                    <div

                                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">

                                        <div class="mb-2 mb-md-0">

                                            <h4 class="mb-1 text-primary fw-semibold">Add Raw Material Type</h4>

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

                                        <form action="{{ route('raw_material.store') }}" method="POST"
                                            enctype="multipart/form-data">

                                            @csrf



                                            <div class="row">



                                                <div class="mb-3 col-md-6">

                                                    <label for="name" class="form-label">Raw Material Name*</label>

                                                    <input type="text" class="form-control" id="name" name="name"
                                                        required>

                                                </div>
                                                <div class="mb-3 col-md-6">

                                                    <label for="measuring_unit" class="form-label">Measuring
                                                        Unit*</label>

                                                    <input type="text" class="form-control" id="measuring_unit"
                                                        name="measuring_unit" required>

                                                </div>
                                                <div class="mb-3 col-md-6">

                                                    <label for="opening_stock" class="form-label">Opening Stock*</label>

                                                    <input type="number" class="form-control" id="opening_stock"
                                                        name="opening_stock" required>

                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="type" class="form-label">Type*</label>
                                                    <select class="form-control" name="type" id="type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="Raw Material">Raw Material</option>
                                                        <option value="Consumable">Consumable</option>
                                                    </select>
                                                </div>








                                            </div>



                                            <!-- Submit Button -->

                                            <div class="d-grid">

                                                <button type="submit" class="btn btn-primary">Add Raw Material</button>

                                            </div>

                                        </form>

                                    </div>

                                </div>



                                <!-- All Services Table -->

                                <div class="card shadow mt-4">

                                    <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">

                                            <h5 class="mb-3 text-primary">Raw Materials</h5>
                                        </div>
                                        <div class="col-md-8">
      <form method="GET" action="{{ route('raw_material.index') }}"
                                            class="row g-3 mb-3">
                                         

                                            <div class="col-md-4">
                                                <select name="type" class="form-select">
                                                    <option value="">Search By Material</option>
                                                    <option value="Raw Material" {{ request('type') == 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                                                    <option value="Consumable" {{ request('type') == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                              
                                            </div>
                                        </form>


                                        </div>
                                    </div>
                                  
                                        <div class="table-responsive">

                                            <table class="table table-bordered table-striped align-middle">

                                                <thead class="table-primary">

                                                    <tr>

                                                        <th>Sr No.</th>

                                                        <th>Raw Material Name</th>
                                                        <th>Measuring Unit</th>
                                                        <th>Opening Stock</th>
                                                        <th>type</th>



                                                        <th>Created At</th>

                                                        <th>Actions</th>



                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @forelse($types as $index => $type)

                                                        <tr>

                                                            <td>{{ $types->firstItem() + $loop->index }}</td>

                                                            <td>{{ $type->name }}</td>
                                                            <td>{{ $type->measuring_unit }}</td>
                                                            <td>{{ $type->opening_stock }}</td>
                                                            <td>{{ $type->type }}</td>



                                                            <td>{{ $type->created_at->format('d M Y') }}</td>

                                                            <td>

                                                                <a href="{{ route('raw_material.edit', $type->id) }}"
                                                                    class="btn btn-sm btn-warning">Edit</a>



                                                                <form
                                                                    action="{{ route('raw_material.destroy', $type->id) }}"
                                                                    method="POST" class="d-inline-block"
                                                                    onsubmit="return confirm('Are you sure you want to delete this raw material?');">

                                                                    @csrf

                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">Delete</button>

                                                                </form>

                                                            </td>



                                                        </tr>

                                                    @empty

                                                        <tr>

                                                            <td colspan="5" class="text-center">No Raw Material Type found.
                                                            </td>

                                                        </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                            <!-- Pagination -->

                                            <div class="d-flex justify-content-end mt-3">

                                                {!! $types->links('pagination::bootstrap-5') !!}

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
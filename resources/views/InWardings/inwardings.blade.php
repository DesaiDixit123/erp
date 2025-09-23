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

                                            <h4 class="mb-1 text-primary fw-semibold">Add Available Raw Material</h4>

                                            <p class="text-muted mb-0">Fill out the form below to add available raw

                                                material</p>

                                        </div>

                                    </div>



                                </div>

                            </div>

                        </div> -->



                        <div class="d-flex justify-content-center  min-vh-100 bg-light">

                            <div class="col-md-12">

                                <div class="card shadow">

                                    <div class="card-body">
                                        <form action="{{ route('inwardings.store') }}" method="POST">
                                            @csrf

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="raw_material_type_id" class="form-label">Raw Material
                                                        Type</label>
                                                    <select name="raw_material_type_id" class="form-select" required>
                                                        <option value="">Select Material</option>
                                                        @foreach ($rawMaterials as $material)
                                                            <option value="{{ $material->id }}">{{ $material->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                                    <input type="date" name="purchase_date" class="form-control"
                                                        required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="vendor_id" class="form-label">Vendor</label>
                                                    <select name="vendor_id" class="form-select" required>
                                                        <option value="">Select Vendor</option>
                                                        @foreach ($vendors as $vendor)
                                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="number_of_pcs" class="form-label">Number of Pcs
                                                        </label>
                                                    <input type="number" name="number_of_pcs" class="form-control"
                                                        min="0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="quantity" class="form-label">Quantity
                                                        </label>
                                                    <input type="number" name="quantity" class="form-control"
                                                        min="0">
                                                </div>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Add Inwarding</button>
                                            </div>
                                        </form>

                                    </div>

                                </div>



                                <!-- Inwarding Table -->
                                <div class="card shadow mt-4">
                                    <div class="card-body">
                                        <h5 class="mb-3 text-primary">Inwarding Entries</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Raw Material</th>
                                                        <th>Purchase Date</th>
                                                        <th>Vendor</th>
                                                        <th>Number of Pcs</th>
                                                        <th>Quantity</th>
                                                        <th>Created At</th>
                                                        <th>Action</th> <!-- New Action Column -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($inwardings as $index => $entry)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $entry->rawMaterialType->name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($entry->purchase_date)->format('d M Y') }}
                                                            </td>
                                                            <td>{{ $entry->vendor->name }}</td>
                                                            <td>{{ $entry->number_of_pcs ?? '-' }}</td>
                                                            <td>{{ $entry->quantity ?? '-' }}</td>
                                                            <td>{{ $entry->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('inwardings.edit', $entry->id) }}"
                                                                    class="btn btn-sm btn-warning">Edit</a>

                                                                <form action="{{ route('inwardings.destroy', $entry->id) }}"
                                                                    method="POST" class="d-inline-block"
                                                                    onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No inwarding
                                                                entries found</td>
                                                        </tr>
                                                    @endforelse
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
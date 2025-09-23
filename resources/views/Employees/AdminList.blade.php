<link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<link rel="stylesheet" href="{{ asset('style.css') }}">







<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .order-btn a {

        margin-right: 10px;

        font-size: 20px;

        transition: color 0.3s ease;

        text-decoration: none;

    }



    .order-btn a.view {

        color: #3498db;

    }



    .order-btn a.edit {

        color: #f1c40f;

    }



    .order-btn a.delete {

        color: #e74c3c;

    }



    .order-btn a:hover {

        opacity: 0.8;

    }



    .table-fixed {

        table-layout: fixed;

        width: 100%;

    }



    .table-fixed th,

    .table-fixed td {

        word-wrap: break-word;

    }
</style>



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



        <div class="main-content">

            <div class="content-wraper-area">

                <div class="container-fluid">

                    <div class="row g-4">

                        <!-- <div class="col-12">

                            <div class="card ">

                                <div class="card-body card-breadcrumb">

                                    <div class="page-title-box d-flex align-items-center justify-content-between">

                                        <h4 class="mb-0">Admin List</h4>

                                    </div>

                                </div>

                            </div>

                        </div> -->



                        <!-- Shop Table -->

                        <div class="col-12">

                            <div class="card">

                                <div class="card-body" style="max-height: 500px; overflow-y: auto;">

                                    <div class="card-title">

                                        <h4>All Employees List</h4>

                                    </div>
                                    <form method="GET" action="{{ route('admin.list') }}" class="row g-3 mb-3">
                                        <!-- Single Search Field -->
                                        <div class="col-md-3">
                                            <label for="search" class="form-label">Search</label>
                                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                                class="form-control" placeholder="Name, Phone, User Type, Status">
                                        </div>

                                        <!-- From Date -->
                                        <div class="col-md-2">
                                            <label for="from_date" class="form-label">From Date</label>
                                            <input type="date" id="from_date" name="from_date"
                                                value="{{ request('from_date') }}" class="form-control">
                                        </div>

                                        <!-- To Date -->
                                        <div class="col-md-2">
                                            <label for="to_date" class="form-label">To Date</label>
                                            <input type="date" id="to_date" name="to_date"
                                                value="{{ request('to_date') }}" class="form-control">
                                        </div>

                                        <!-- Filter Button -->
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </form>



                                    <table class="table table-bordered nowrap data-table-area"
                                        style="min-width: 1000px;">

                                        <thead>

                                            <tr>

                                                <th style="white-space: nowrap;">SR No.</th>

                                                <th>Name</th>

                                                <th>Email</th>

                                                <th>Phone</th>

                                                <!-- <th>Address</th>

                                                <th style="width:150px; white-space: nowrap;">Aadhar card</th>

                                                <th style="width:150px; white-space: nowrap;">Pan Card</th> -->


                                                <th>Role</th>

                                                <th>Date</th>

                                                <th>Status</th>

                                                <th style="width: 150px;">Action</th>

                                            </tr>

                                        </thead>



                                        <tbody>

                                            @forelse ($admins as $admin)

                                                <tr>

                                                    <td>{{ $admins->firstItem() + $loop->index }}</td>

                                                    <td>{{ $admin->name }}</td>

                                                    <td>{{ $admin->email }}</td>

                                                    <td>{{ $admin->phone }}</td>

                                                    <!-- <td>{{ $admin->address }}</td>

                                                                    <td><img class="shop-img"

                                                                            src="{{ asset('storage/' . $admin->adhar_image) }}" alt="" />

                                                                    </td>

                                                                    <td><img class="shop-img"

                                                                            src="{{ asset('storage/' . $admin->pan_image) }}" alt="" />

                                                                    </td> -->
                                                    <td>{{ $admin->user_type }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($admin->created_at)->timezone('Asia/Kolkata')->format('d M Y,h:i A')}}
                                                    </td>



                                                    <td>

                                                        <span
                                                            class="badge {{ $admin->user_status === 'Active' ? 'bg-success' : 'bg-secondary' }}">

                                                            {{ ucfirst($admin->user_status) }}

                                                        </span>

                                                    </td>

                                                    <td class="order-btn d-flex">





                                                        @if ($admin->user_status == 'Active')

                                                            <form action="{{ route('employee.deactivate', $admin->id) }}"
                                                                method="POST" class="me-2">

                                                                @csrf

                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger">Inactive</button>

                                                            </form>

                                                        @else

                                                            <form action="{{ route('employee.activate', $admin->id) }}"
                                                                method="POST" class="me-2">

                                                                @csrf

                                                                <button type="submit"
                                                                    class="btn btn-sm btn-primary">Activate</button>

                                                            </form>

                                                        @endif



                                                        <a href="{{ route('admin.show', $admin->id) }}"
                                                            class="text-info mx-2" title="View">

                                                            <i class="fas fa-eye"></i>

                                                        </a>

                                                        <a href="{{ route('admin.edit', $admin->id) }}"
                                                            class="edit text-primary mx-2" title="Edit">

                                                            <i class="fas fa-edit"></i>

                                                        </a>



                                                        <form action="{{ route('add-admin.destroy', $admin->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this admin?')">

                                                            @csrf

                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="text-red-500 hover:text-red-700 bg-transparent border-0"
                                                                title="Delete">

                                                                <i class="fas fa-trash-alt"></i>

                                                            </button>

                                                        </form>

                                                    </td>





                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No admins found.
                                                    </td>
                                                </tr>
                                            @endforelse

                                        </tbody>

                                    </table>



                                    <!-- Pagination -->

                                    <div class="d-flex justify-content-end mt-3">

                                        {!! $admins->links('pagination::bootstrap-5') !!}

                                    </div>



                                </div>

                            </div>

                        </div>



                    </div>

                </div>

            </div>

        </div>

    </div> <!-- End Page Content -->

</div> <!-- End Page Wrapper -->



<script src="{{ asset('js/jquery.min.js') }}"> </script>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>

<script src="{{ asset('js/default-assets/setting.js') }}"> </script>

<script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>

<script src="{{ asset('js/todo-list.js') }}"> </script>

<script src="{{ asset('js/jquery.dataTables.min.js') }}"> </script>

<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"> </script>

<script src="{{ asset('js/dataTables.responsive.min.js') }}"> </script>

<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"> </script>

<script src="{{ asset('js/dataTables.buttons.min.js') }}"> </script>

<script src="{{ asset('js/buttons.print.min.js') }}"> </script>

<script src="{{ asset('js/pdfmake.min.js') }}"> </script>

<script src="{{ asset('js/vfs_fonts.js') }}"> </script>

<script src="{{ asset('js/buttons.html5.min.js') }}"> </script>

<script src="{{ asset('js/jszip.min.js') }}"> </script>

<script src="{{ asset('js/dataTables-custom.js') }}"> </script>





<script>

    // Auto hide toast after 4 seconds

    setTimeout(() => {

        document.querySelectorAll('.toast').forEach(toast => {

            toast.classList.remove('show');

        });

    }, 4000);

</script>



<!-- Active JS -->

<script src="js/default-assets/active.js"></script>
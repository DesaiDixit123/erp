<link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('style.css') }}">

<div class="flapt-page-wrapper">
    <!-- Sidebar -->
    <div class="flapt-sidemenu-wrapper">
        @include('layouts.sidebar')
    </div>

    <!-- Page Content -->
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

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="content-wraper-area">
                <div class="container-fluid">
                    <div class="row g-4">
                        <div class="d-flex justify-content-center min-vh-100 bg-light">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <form action="{{ route('components.update', $tools->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')

                                            <div class="row">
                                                <!-- Component Name -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="name" class="form-label">Component Name*</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name', $tools->name) }}" required>
                                                </div>

                                            
<div class="mb-3 col-md-4">
    <label for="company_id" class="form-label">Customer Name*</label>
    <select class="form-control" name="company_id" id="company_id" required>
        <option value="">Select Customer Name</option>
        @foreach($companies as $company)
            <option value="{{ $company->id }}"
                {{ (int) old('company_id', $tools->company_id) === (int) $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach
    </select>
</div>


                                                <!-- Component Image -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="image" class="form-label">Component Image</label>
                                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                    @if($tools->image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $tools->image) }}" alt="Component Image" width="100">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Update Component</button>
                                            </div>
                                        </form>
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

<!-- Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/default-assets/setting.js') }}"></script>
<script src="{{ asset('js/default-assets/scrool-bar.js') }}"></script>
<script src="{{ asset('js/todo-list.js') }}"></script>
<script src="{{ asset('js/default-assets/active.js') }}"></script>
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script src="{{ asset('js/dashboard-custom-sass.js') }}"></script>

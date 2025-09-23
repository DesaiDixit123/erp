
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
                        <!-- <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body py-4 px-4">
                                    <div
                                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                        <div class="mb-2 mb-md-0">
                                            <h4 class="mb-1 text-primary fw-semibold">Edit Raw Material Type</h4>
                                            <p class="text-muted mb-0">Update the name below</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="d-flex justify-content-center min-vh-100 bg-light">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <form action="{{ route('raw_material.update', $type->id) }}" method="POST">
                                            @csrf
                                            @method('POST') {{-- Using POST for update as discussed earlier --}}

                                            <div class="row">
                                                <!-- Raw Material Type Name -->
                                                <div class="mb-3 col-md-6">
                                                    <label for="name" class="form-label">Raw Material Type Name*</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name', $type->name) }}" required>
                                                </div>
                                        
                                                <div class="mb-3 col-md-6">
                                                    <label for="measuring_unit" class="form-label">Measuring Unit*</label>
                                                    <input type="text" class="form-control" id="measuring_unit" name="measuring_unit"
                                                        value="{{ old('measuring_unit', $type->measuring_unit) }}" required>
                                                </div>
                                        
                                                <!-- Raw Material Type Name -->
                                                <div class="mb-3 col-md-6">
                                                    <label for="opening_stock" class="form-label">Measuring Unit*</label>
                                                    <input type="text" class="form-control" id="opening_stock" name="opening_stock"
                                                        value="{{ old('opening_stock', $type->opening_stock) }}" required>
                                                </div>
                                        

                                                 <div class="mb-3 col-md-6">
                                                    <label for="type" class="form-label">Component
                                                        Type*</label>
                                                    <select class="form-control" name="type"
                                                        id="type" required>
                                                        <option value="">Select Component Type</option>
                                                        <option value="Raw Material" {{ $type->type === 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                                                        <option value="Consumable" {{ $type->type === 'Consumable' ? 'selected' : '' }}>Consumable</option>

                                                    </select>
                                                </div>
                                                    </div>

                                            <!-- Submit Button -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Update Raw Material</button>
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

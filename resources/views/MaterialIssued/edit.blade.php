
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
    <form action="{{ route('material-issued.update', $materialIssued->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Update માટે PUT method ઉપયોગ કરો --}}

        <div class="row">
            <!-- Raw Material Dropdown -->
            <div class="mb-3 col-md-6">
                <label for="raw_material_type_id" class="form-label">Raw Material Type*</label>
                <select name="raw_material_type_id" id="raw_material_type_id" class="form-control" required>
                    <option value="">Select Material</option>
                    @foreach ($rawMaterials as $material)
                        <option value="{{ $material->id }}" {{ $materialIssued->raw_material_type_id == $material->id ? 'selected' : '' }}>
                            {{ $material->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Issue Date -->
            <div class="mb-3 col-md-6">
                <label for="issue_date" class="form-label">Issue Date*</label>
                <input type="date" class="form-control" id="issue_date" name="issue_date"
                    value="{{ old('issue_date', $materialIssued->issue_date) }}" required>
            </div>

            <!-- Quantity -->
            <div class="mb-3 col-md-6">
                <label for="quantity" class="form-label">Quantity*</label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                    value="{{ old('quantity', $materialIssued->quantity) }}" required>
            </div>

            <!-- Shift -->
            <div class="mb-3 col-md-6">
                <label for="shift" class="form-label">Shift*</label>
                <select name="shift" id="shift" class="form-control" required>
                    <option value="">Select Shift</option>
                    <option value="Day" {{ $materialIssued->shift == 'Day' ? 'selected' : '' }}>Day</option>
                    <option value="Night" {{ $materialIssued->shift == 'Night' ? 'selected' : '' }}>Night</option>
                </select>
            </div>
        </div>

        <!-- Submit -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update Issued Material</button>
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

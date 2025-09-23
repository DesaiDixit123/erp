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
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body py-4 px-4">
                                    <div
                                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                        <div class="mb-2 mb-md-0">
                                            <h4 class="mb-1 text-primary fw-semibold">Add Consumable Raw Material</h4>
                                            <p class="text-muted mb-0">Fill out the form below to add Consumable Raw
                                                Material</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center  min-vh-100 bg-light">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <form action="{{ route('consumable.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="raw_material_type_id" class="form-label">Raw Material
                                                        Type</label>
                                                    <select name="raw_material_type_id" id="raw_material_type_id"
                                                        class="form-select" required>
                                                        <option value="">Select Type</option>
                                                        @foreach($types as $type)
                                                            @php
                                                                $availableQty = $type->availableRawMaterial ? $type->availableRawMaterial->quantity : 0;
                                                            @endphp
                                                            <option value="{{ $type->id }}"
                                                                data-available="{{ $availableQty }}">
                                                                {{ $type->name }} (Available: {{ $availableQty }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="quantity" class="form-label">Quantity</label>
                                                    <input type="number" name="quantity" id="quantity"
                                                        class="form-control" min="1" required>
                                                    <small id="available-info" class="text-muted"></small>
                                                </div>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Update Consumable Raw
                                                    Material</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <!-- Left: Consumable Raw Material -->


                                      <!-- Right: Available Raw Material -->
                                    <div class="col-md-6">
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <h5 class="mb-3 text-success">All Available Raw Material</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped align-middle">
                                                        <thead class="table-success">
                                                            <tr>
                                                                <th style="white-space:nowrap;" >Sr No</th>
                                                                <th style="white-space:nowrap;">Material Type</th>
                                                                <th>Quantity</th>
                                                                <th style="white-space:nowrap;">Updated</th>
                                                                <th style="white-space:nowrap;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($availables as $index => $available)
                                                                <tr>
                                                                    <td>{{ $availables->firstItem() + $index }}</td>
                                                                    <td>{{ $available->rawMaterialType->name }}</td>
                                                                    <td>{{ $available->quantity }}</td>
                                                                    <td style="white-space:nowrap">{{ $available->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                                                                    <td style="white-space:nowrap;" >
                                                                        <a href="{{ route('available.edit', $available->id) }}"
                                                                            class="btn btn-sm btn-warning">Edit</a>
                                                                        <form
                                                                            action="{{ route('available.destroy', $available->id) }}"
                                                                            method="POST" class="d-inline-block"
                                                                            onsubmit="return confirm('Are you sure?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button
                                                                                class="btn btn-sm btn-danger">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No records found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        {!! $availables->links('pagination::bootstrap-5') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <h5 class="mb-3 text-primary">All Consumable Raw Material</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped align-middle">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th style="white-space:nowrap;">Sr No</th>
                                                                <th style="white-space:nowrap;">Material Type</th>
                                                                <th>Quantity</th>
                                                                <th style="white-space:nowrap;">Updated</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($consumables as $index => $material)
                                                                <tr>
                                                                    <td>{{ $consumables->firstItem() + $index }}</td>
                                                                    <td>{{ $material->rawMaterialType->name }}</td>
                                                                    <td>{{ $material->quantity }}</td>
                                                                    <td style="white-space:nowrap">{{ $material->updated_at-> timezone('Asia/Kolkata')-> format('d M Y, h:i A') }}</td>
                                                                    <td style="white-space:nowrap;">
                                                                        <a href="{{ route('consumable.edit', $material->id) }}"
                                                                            class="btn btn-sm btn-warning">Edit</a>
                                                                        <form
                                                                            action="{{ route('consumable.destroy', $material->id) }}"
                                                                            method="POST" class="d-inline-block"
                                                                            onsubmit="return confirm('Are you sure?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button
                                                                                class="btn btn-sm btn-danger">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No records found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        {!! $consumables->links('pagination::bootstrap-5') !!}
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

<script>
    const materialSelect = document.getElementById('raw_material_type_id');
    const quantityInput = document.getElementById('quantity');
    const infoText = document.getElementById('available-info');
    let available = 0;

    // Update available quantity info + max attribute
    function updateAvailableInfo() {
        const selectedOption = materialSelect.options[materialSelect.selectedIndex];
        available = parseInt(selectedOption.getAttribute('data-available')) || 0;

        quantityInput.max = available;
        infoText.innerText = `Available quantity: ${available}`;
        quantityInput.value = ''; // clear previous invalid input
    }

    // Prevent user from typing more than available
    quantityInput.addEventListener('input', function () {
        let enteredQty = parseInt(quantityInput.value);
        if (enteredQty > available) {
            quantityInput.value = available;
        }
    });

    // On form submit check again
    document.querySelector('form').addEventListener('submit', function (e) {
        const enteredQty = parseInt(quantityInput.value);
        if (enteredQty > available) {
            e.preventDefault();
            alert(`You cannot consume more than available quantity (${available})`);
        }
    });

    materialSelect.addEventListener('change', updateAvailableInfo);
    window.addEventListener('load', updateAvailableInfo);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
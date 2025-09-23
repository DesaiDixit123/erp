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

                <div class="toast align-items-center text-bg-success border-0" role="alert">

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

                                <div class="card-body">
                                    <form action="{{ route('material-issued.store') }}" method="POST">
                                        @csrf
                                        <div class="row mb-3">
                                            <!-- Raw Material Dropdown -->
                                            <div class="col-md-6">
                                                <label for="raw_material_type_id" class="form-label">Raw
                                                    Material</label>
                                                <select name="raw_material_type_id" class="form-select"
                                                    id="raw_material_type_id" required>
                                                    <option value="">Select Material</option>
                                                    @foreach ($rawMaterials as $material)
                                                        <option value="{{ $material->id }}"
                                                            data-available="{{ $material->available_quantity ?? 0 }}">
                                                            {{ $material->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p id="available-info" class="text-muted small mt-1"></p>
                                            </div>

                                            <!-- Issue Date -->
                                            <div class="col-md-6">
                                                <label for="issue_date" class="form-label">Issue Date</label>
                                                <input type="date" name="issue_date" class="form-control" required>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="col-md-6">
                                                <label for="quantity" class="form-label">Quantity</label>
                                                <input type="number" name="quantity" id="quantity" class="form-control"
                                                    min="1" required>
                                            </div>

                                            <!-- Shift -->
                                            <div class="col-md-6">
                                                <label for="shift" class="form-label">Shift</label>
                                                <select name="shift" class="form-select" required>
                                                    <option value="">Select Shift</option>
                                                    <option value="Day">Day</option>
                                                    <option value="Night">Night</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Issue Material</button>
                                        </div>
                                    </form>

                                </div>




                                <div class="card shadow mt-4">
                                    <div class="card-body">
                                        <h5 class="mb-3 text-primary">Issued Materials</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Raw Material</th>
                                                        <th>Issue Date</th>
                                                        <th>Quantity</th>
                                                        <th>Shift</th>
                                                        <th>Created At</th>
                                                        <th>Action</th> <!-- New Action Column -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($issuedMaterials as $index => $item)
                                                        <tr>
                                                            <td>{{ $issuedMaterials->firstItem() + $index }}</td>
                                                            <td>{{ $item->rawMaterialType->name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->issue_date)->format('d M Y') }}
                                                            </td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>{{ $item->shift }}</td>
                                                            <td>{{ $item->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                                            </td>
                                                            <td>
                                                                <!-- Edit Button -->
                                                                <a href="{{ route('material-issued.edit', $item->id) }}"
                                                                    class="btn btn-sm btn-warning">Edit</a>

                                                                <!-- Delete Button -->
                                                                <form
                                                                    action="{{ route('material-issued.destroy', $item->id) }}"
                                                                    method="POST" style="display:inline-block;"
                                                                    onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No issued materials found
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-end mt-3">
                                            {!! $issuedMaterials->links('pagination::bootstrap-5') !!}
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




<script>
    // Auto show all .toast elements (Bootstrap 5)
    document.addEventListener("DOMContentLoaded", function () {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'))
        toastElList.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl)
            toast.show()
        });
    });
</script>

<script>
    const materialSelect = document.getElementById('raw_material_type_id');
    const quantityInput = document.getElementById('quantity');
    const infoText = document.getElementById('available-info');
    let available = 0;

    function updateAvailableInfo() {
        const selectedOption = materialSelect.options[materialSelect.selectedIndex];
        available = parseInt(selectedOption.getAttribute('data-available')) || 0;

        quantityInput.max = available;
        infoText.innerText = `Available quantity: ${available}`;
        quantityInput.value = '';
    }

    quantityInput.addEventListener('input', function () {
        const enteredQty = parseInt(quantityInput.value);
        if (enteredQty > available) {
            quantityInput.value = available;
        }
    });

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

<script src="{{ asset('js/jquery.min.js') }}"> </script>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>

<script src="{{ asset('js/default-assets/setting.js') }}"> </script>

<script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>

<script src="{{ asset('js/todo-list.js') }}"> </script>

<script src="{{ asset('js/default-assets/active.js') }}"> </script>

<script src="{{ asset('js/apexcharts.min.js') }}"> </script>

<script src="{{ asset('js/dashboard-custom-sass.js') }}"> </script>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
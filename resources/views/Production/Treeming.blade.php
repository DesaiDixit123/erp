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
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body py-4 px-4">
                                    <div
                                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                        <div class="mb-2 mb-md-0">
                                            <h4 class="mb-1 text-primary fw-semibold">Triming</h4>
                                            <p class="text-muted mb-0">Fill out the form below to add Triming</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center min-vh-100 bg-light">
                            <div class="col-md-12">

                                <div class="card shadow">
                                    <div class="card-body">
                                        <form action="{{ route('trimming.store') }}" method="POST">
                                            @csrf

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Date</label>
                                                    <input type="date" name="date" class="form-control" required>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Shift</label>
                                                    <select name="shift" class="form-select" required>
                                                        <option value="">Select Shift</option>
                                                        <option value="Day">Day</option>
                                                        <option value="Night">Night</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Machine No</label>
                                                    <select name="machine_no" class="form-select" required>
                                                        <option value="">Select Machine</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">


                                                <div class="col-md-4">
                                                    <label class="form-label">Component Name</label>
                                                    <select name="tool_type_id" class="form-select" required>
                                                        <option value="">Select Component Name</option>
                                                        @foreach($tools as $tool)
                                                            <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Machine Operator 1</label>
                                                    <input type="text" name="operator_name1" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Machine Operator 2</label>
                                                    <input type="text" name="operator_name2" class="form-control"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <label class="form-label">Quantity (KG)</label>
                                                    <input type="number" name="quantity_kg" step="0.01" min="0.01"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Quantity (PCS)</label>
                                                    <input type="number" name="quantity_pcs" min="1"
                                                        class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Submit Triming</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <div class="row g-4 mt-4">
                                    <!-- 🔹 Left Side (Casting Table) -->
                                    <div class="col-md-6">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <h5 class="mb-3 text-primary">Casting Records</h5>
                                                                                        <!-- ✅ Trimming Form -->
 <!-- <div class="card shadow mt-4">
                                            <div class="card-body">
                                                <form method="GET" action="{{ route('casting.index') }}">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <input type="text" name="search"
                                                                value="{{ request('search') }}" class="form-control"
                                                                placeholder="Search anything...">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="date" name="from_date"
                                                                value="{{ request('from_date') }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="date" name="to_date"
                                                                value="{{ request('to_date') }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2 d-grid">
                                                            <button type="submit"
                                                                class="btn btn-primary">Filter</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> -->
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped align-middle">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Date</th>
                                                                <th>Component</th>
                                                                <th>Quantity (KG)</th>
                                                                <th>Quantity (PCS)</th>
                                                    
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($castings as $index => $casting)
                                                                <tr>
                                                                    <td>{{ $castings->firstItem() + $index }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($casting->date)->format('d M Y') }}
                                                                    </td>
                                                                    <td>{{ $casting->tool->name ?? 'N/A' }}</td>
                                                                    <td>{{ $casting->quantity_kg }}</td>
                                                                    <td>{{ $casting->quantity_pcs }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No casting records
                                                                        found</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-end mt-3">
                                                    {!! $castings->links('pagination::bootstrap-5') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 🔹 Right Side (Trimming Form + Trimming Table) -->
                                    <div class="col-md-6">
                                        <!-- ✅ Trimming Form -->

                                        <!-- ✅ Trimming Table -->
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <h5 class="mb-3 text-primary">Trimming Records</h5>

                                                 <!-- <div class="card shadow mt-4">
                                            <div class="card-body">
                                                <form method="GET" action="{{ route('casting.index') }}">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <input type="text" name="search"
                                                                value="{{ request('search') }}" class="form-control"
                                                                placeholder="Search anything...">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="date" name="from_date"
                                                                value="{{ request('from_date') }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="date" name="to_date"
                                                                value="{{ request('to_date') }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2 d-grid">
                                                            <button type="submit"
                                                                class="btn btn-primary">Filter</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> -->

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped align-middle">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Date</th>
                                                                <th>Component</th>
                                                                <th>Quantity (KG)</th>
                                                                <th>Quantity (PCS)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($records as $index => $record)
                                                                <tr>
                                                                    <td>{{ $records->firstItem() + $index }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}
                                                                    </td>
                                                                    <td>{{ $record->tool->name ?? 'N/A' }}</td>
                                                                    <td>{{ $record->quantity_kg }}</td>
                                                                    <td>{{ $record->quantity_pcs }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No trimming records
                                                                        found</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-end mt-3">
                                                    {!! $records->links('pagination::bootstrap-5') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- All Process Records Table --}}
                                <div class="card shadow mt-4">
                                    <div class="card-body">
                                        <h5 class="mb-3 text-success">All Triming Process Records</h5>
                                         <div class="card shadow mt-4">
                                            <div class="card-body">
                                                <form method="GET" action="{{ route('casting.index') }}">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <input type="text" name="search"
                                                                value="{{ request('search') }}" class="form-control"
                                                                placeholder="Search anything...">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="date" name="from_date"
                                                                value="{{ request('from_date') }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="date" name="to_date"
                                                                value="{{ request('to_date') }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2 d-grid">
                                                            <button type="submit"
                                                                class="btn btn-primary">Filter</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th style="white-space: nowrap;">Sr No</th>
                                                        <th>Date</th>
                                                        <th>Shift</th>
                                                        <th style="white-space: nowrap;">Machine Number</th>
                                                        <th style="white-space: nowrap;">Component Name</th>
                                                        <th style="white-space: nowrap;">Machine Operator 1</th>
                                                        <th style="white-space: nowrap;">Machine Operator 2</th>
                                                        <th style="white-space: nowrap;">Quantity (KG)</th>
                                                        <th style="white-space: nowrap;">Quantity (PCS)</th>
                                                        <th style="white-space: nowrap;">Updated Date</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($allRecords as $index => $record)
                                                        <tr>
                                                            <td>{{ $allRecords->firstItem() + $index }}</td>
                                                            <td style="white-space: nowrap;">
                                                                {{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}
                                                            </td>
                                                            <td>{{ $record->shift }}</td>
                                                            <td>{{ $record->machine_no }}</td>
                                                            <td>{{ $record->tool->name ?? 'N/A' }}</td>
                                                            <td>{{ $record->machine_operator_name1 }}</td>
                                                            <td>{{ $record->machine_operator_name2 }}</td>
                                                            <td>{{ $record->quantity_kg }}</td>
                                                            <td>{{ $record->quantity_pcs }}</td>
                                                            <td style="white-space: nowrap;">
                                                                {{ $record->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                                            </td>
                                                            <td style="white-space: nowrap;">
                                                                <form
                                                                    action="{{ route('casting.process.delete', $record->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">Delete</button>
                                                                </form>
                                                            </td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center">No process records found
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-end mt-3">
                                                {!! $allRecords->links('pagination::bootstrap-5') !!}
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
</div>

<script src="{{ asset('js/jquery.min.js') }}"> </script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>
<script src="{{ asset('js/default-assets/setting.js') }}"> </script>
<script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>
<script src="{{ asset('js/todo-list.js') }}"> </script>
<script src="{{ asset('js/default-assets/active.js') }}"> </script>
<script src="{{ asset('js/apexcharts.min.js') }}"> </script>
<script src="{{ asset('js/dashboard-custom-sass.js') }}"> </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"> </script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // 🔹 Jyaare component select thay tyaare casting available qty fetch karo
        $('select[name="tool_type_id"]').change(function () {
            let toolId = $(this).val();

            if (toolId) {
                $.ajax({
                    url: '/get-casting-quantity/' + toolId, // ✅ route banavani
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            // KG input update karo
                            let kgInput = $('input[name="quantity_kg"]');
                            kgInput.attr('max', res.data.quantity_kg);
                            kgInput.val(''); // reset

                            // PCS input update karo
                            let pcsInput = $('input[name="quantity_pcs"]');
                            pcsInput.attr('max', res.data.quantity_pcs);
                            pcsInput.val(''); // reset
                        }
                    },
                    error: function () {
                        alert('Failed to fetch casting quantity!');
                    }
                });
            }
        });

        // 🔹 Auto restrict KG input
        $('input[name="quantity_kg"]').on('input', function () {
            let entered = parseFloat($(this).val()) || 0;
            let max = parseFloat($(this).attr('max')) || 0;

            if (entered > max) {
                $(this).val(max);
            }
        });

        // 🔹 Auto restrict PCS input
        $('input[name="quantity_pcs"]').on('input', function () {
            let entered = parseInt($(this).val()) || 0;
            let max = parseInt($(this).attr('max')) || 0;

            if (entered > max) {
                $(this).val(max);
            }
        });

        // 🔹 MODAL ma pan restrict logic
        $('#treemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recordId = button.data('id');
            var availableQtyKg = button.data('qtykg');
            var availableQtyPcs = button.data('qtypcs');

            $('#modal_record_id').val(recordId);

            // set max in modal inputs
            $('#treemForm input[name="quantity_kg"]').attr('max', availableQtyKg).val('');
            $('#treemForm input[name="quantity_pcs"]').attr('max', availableQtyPcs).val('');
        });

        // 🔹 Modal inputs restrict
        $('#treemForm input[name="quantity_kg"]').on('input', function () {
            let entered = parseFloat($(this).val()) || 0;
            let max = parseFloat($(this).attr('max')) || 0;
            if (entered > max) {
                $(this).val(max);
            }
        });

        $('#treemForm input[name="quantity_pcs"]').on('input', function () {
            let entered = parseInt($(this).val()) || 0;
            let max = parseInt($(this).attr('max')) || 0;
            if (entered > max) {
                $(this).val(max);
            }
        });
    });
</script>
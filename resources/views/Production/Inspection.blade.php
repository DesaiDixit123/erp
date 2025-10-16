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
                                            <h4 class="mb-1 text-primary fw-semibold">Inspection</h4>
                                            <p class="text-muted mb-0">Fill out the form below to add Inspection</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center min-vh-100 bg-light">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <form action="{{ route('casting.updateInspection') }}" method="POST"
                                            id="inspectionForm">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Date:</label>
                                                    <input type="date" name="date" class="form-control" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Component Name</label>
                                                    <select name="tool_type_id" class="form-select" required>
                                                        <option value="">Select Component Name</option>
                                                        @foreach($tools as $tool)
                                                            <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- <h5 class="text-primary">Rejection Details</h5> -->
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label>Non-Filling</label>
                                                    <input type="number" name="non_filling"
                                                        class="form-control reason-field" min="0" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Trimming</label>
                                                    <input type="number" name="trimming"
                                                        class="form-control reason-field" min="0" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Casting</label>
                                                    <input type="number" name="casting"
                                                        class="form-control reason-field" min="0" value="0">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label>Blow Hole</label>
                                                    <input type="number" name="blow_hole"
                                                        class="form-control reason-field" min="0" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Sound Test</label>
                                                    <input type="number" name="sound_test"
                                                        class="form-control reason-field" min="0" value="0">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label>Total Rejected</label>
                                                    <input type="number" id="total_rejected" name="total_rejected"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Ok Quantity</label>
                                                    <input type="number" id="ok_quantity" name="ok_quantity"
                                                        class="form-control" readonly>
                                                </div>
                                                <!-- <div class="col-md-4">
            <label>Total Inspected</label>
            <input type="number" id="quantity_inspected" name="quantity_inspected" class="form-control" readonly>
        </div> -->
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Submit Inspection</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>




                                <div class="row g-4 mt-4">
                                    <!-- 🔹 Left Side (Casting Table) -->
                                    <div class="col-md-6">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <h5 class="mb-3 text-primary">Trimming Records</h5>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped align-middle">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Date</th>
                                                                <th>Component</th>
                                                                <th>Quantity</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($trimmings as $index => $casting)
                                                                <tr>
                                                                    <td>{{ $trimmings->firstItem() + $index }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($casting->date)->format('d M Y') }}
                                                                    </td>
                                                                    <td>{{ $casting->tool->name ?? 'N/A' }}</td>

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
                                                    {!! $trimmings->links('pagination::bootstrap-5') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 🔹 Right Side (Trimming Form + Trimming Table) -->
                                    <div class="col-md-6">

                                        <div class="card shadow">
                                            <div class="card-body">
                                                <h5 class="mb-3 text-primary">Inspection Records</h5>



                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped align-middle">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th style="white-space: nowrap;">Sr No</th>

                                                                <th style="white-space: nowrap;">Date</th>

                                                                <th style="white-space: nowrap;">Component Name</th>
                            
                                                                <th style="white-space: nowrap;">Ok Quantity</th>

                                                                <th style="white-space: nowrap;">Rejected Quantity</th>
                              

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($records as $index => $record)
                                                                <tr>
                                                                    <td>{{ $records->firstItem() + $index }}</td>


                                                                    <td>{{ $record->date }}</td>
                                                                    <td>{{ $casting->tool->name ?? 'N/A' }}</td>
                
                                                                    <td>{{ $record->ok_quantity }}</td>
                                                                    <td>{{ $record->total_rejected }}</td>
                   




                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No records found
                                                                    </td>
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

                            </div>


                        </div>






                        {{-- All Process Records Table --}}
                        <div class="card shadow mt-4">
                            <br class="card-body">
                            <h5 class="mb-3 text-success">All Inspection Process Records</h5>


                            <div class="card shadow mt-3">
                                <div class="card-body">
                                    <form method="GET" action="{{ route('inspection.index') }}"
                                        class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label">Search</label>
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                class="form-control" placeholder="Search by name/reason">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">From Date</label>
                                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">To Date</label>
                                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 d-flex">
                                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                                            <a href="{{ route('inspection.index') }}"
                                                class="btn btn-secondary">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            </br>
                            </br>
                            </br>
                            </br>


                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead class="table-success">
                                        <tr>
                                            <th style="white-space: nowrap;">Sr No</th>
                                            <th>Date</th>


                                            <th style="white-space: nowrap;">Component Name</th>
                                            <th style="white-space: nowrap;">Quantity Inspected</th>
                                            <th style="white-space: nowrap;">Ok</th>
                                            <th style="white-space: nowrap;">Rejected</th>
                                            <th style="white-space: nowrap;">Reason of Rejection</th>
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


                                                <td>{{ $record->tool->name ?? 'N/A' }}</td>

                                                <td>{{ $record->quantity_inspected }}</td>
                                                <td>{{ $record->ok_number }}</td>
                                                <td>{{ $record->rejected_number }}</td>
                                                <td>{{ $record->reason_of_rejected }}</td>
                                                <td style="white-space: nowrap;">
                                                    {{ $record->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                                </td>
                                                <td style="white-space: nowrap;">
                                                    <form action="{{ route('casting.process.delete', $record->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

<script src="{{ asset('js/jquery.min.js') }}"> </script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>
<script src="{{ asset('js/default-assets/setting.js') }}"> </script>
<script src="{{ asset('js/default-assets/scrool-bar.js') }}"> </script>
<script src="{{ asset('js/todo-list.js') }}"> </script>
<script src="{{ asset('js/default-assets/active.js') }}"> </script>
<script src="{{ asset('js/apexcharts.min.js') }}"> </script>
<script src="{{ asset('js/dashboard-custom-sass.js') }}"> </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function calculateTotals() {
            let totalRejected = 0;
            $(".reason-field").each(function () {
                totalRejected += parseInt($(this).val()) || 0;
            });

            $("#total_rejected").val(totalRejected);

            // Available trimming qty fetch karyo api mathi
            let available = parseInt($("#quantity_inspected").attr("max")) || 0;

            // total inspected = available
            $("#quantity_inspected").val(available);

            let okQty = available - totalRejected;
            if (okQty < 0) okQty = 0;

            $("#ok_quantity").val(okQty);
        }

        $(".reason-field").on("input", calculateTotals);

        $('select[name="tool_type_id"]').change(function () {
            var toolId = $(this).val();
            if (toolId) {
                $.get('/api/get-trimming-qty/' + toolId, function (data) {
                    let available = data.quantity_pcs || 0;
                    $('#quantity_inspected').attr('max', available).val(available);
                    calculateTotals();
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Open Modal and set data
        $('.open-treem-modal').click(function () {
            // ✅ Reset the form before setting values
            $('#treemForm')[0].reset();

            var recordId = $(this).data('id');
            var quantity = $(this).data('quantity');

            $('#modal_record_id').val(recordId);
            $('#available_quantity_hidden').val(quantity);
            $('#available_quantity_display').text(quantity);
            $('#modal_treem').attr('max', quantity);
            $('#modal_treem').val('');

            var modal = new bootstrap.Modal(document.getElementById('treemModal'));
            modal.show();
        });

        // Block above max entry
        $('#modal_treem').on('input', function () {
            let entered = parseInt($(this).val());
            let max = parseInt($('#available_quantity_hidden').val());

            if (entered > max) {
                $(this).val(max);
            }
        });

        // Submit form via Ajax
        $('#treemForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        $('#treemModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Update failed');
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    if (xhr.status === 422 && xhr.responseJSON?.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Error occurred while updating.');
                    }
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Auto limit Inspection quantity to available trimming
        $('select[name="tool_type_id"]').change(function () {
            var toolId = $(this).val();

            if (toolId) {
                // Ajax call thi trimming qty fetch karo
                $.get('/api/get-trimming-qty/' + toolId, function (data) {
                    let available = data.quantity_pcs || 0;
                    $('#quantity_inspected').attr('max', available); // set max
                });
            }
        });

        // Restrict typing beyond max
        $('#quantity_inspected').on('input', function () {
            let max = parseInt($(this).attr('max')) || 0;
            let val = parseInt($(this).val()) || 0;
            if (val > max) {
                $(this).val(max);
            }
        });
    });
</script>
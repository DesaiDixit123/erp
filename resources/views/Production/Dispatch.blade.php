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
                                            <h4 class="mb-1 text-primary fw-semibold">Dispatch</h4>
                                            <p class="text-muted mb-0">Fill out the form below to add Dispatch</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center min-vh-100 bg-light">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <form action="{{ route('casting.updateDispatch') }}" method="POST">
                                            @csrf

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="treem" class="form-label">Date:</label>
                                                    <input type="date" name="date" class="form-control" required>
                                                </div>

                                          

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
    <label class="form-label">Quantity Dispatched</label>
    <input type="number" 
           name="quantity_dispatch" 
           id="quantity_dispatch"
           step="1"
           min="1"
           class="form-control" 
           required>
    <small class="text-muted">Available: <span id="available_quantity">{{ $inspectionAvailable ?? 0 }}</span></small>
</div>


                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Submit Dispatch</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                          <div class="row g-4 mt-4">
    <!-- 🟢 LEFT SIDE - Inspection Table -->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-3 text-success">Inspection Records</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>Component Name</th>
                                <th>Quantity Inspected</th>
                                <!-- <th>Updated Date</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inspectionRecords as $index => $record)
                                <tr>
                                    <td>{{ $inspectionRecords->firstItem() + $index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                                    <td>{{ $record->tool->name ?? 'N/A' }}</td>
                                    <td>{{ $record->quantity_inspected }}</td>
                                    <!-- <td>{{ $record->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td> -->
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No inspection records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        {!! $inspectionRecords->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔵 RIGHT SIDE - Dispatch Table -->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-3 text-primary">Dispatch Records</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>Component Name</th>
                                <th>Quantity Dispatched</th>
                                <!-- <th>Updated Date</th> -->
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $index => $record)
                                <tr>
                                    <td>{{ $records->firstItem() + $index }}</td>
                                    <td>{{ $record->date }}</td>
                                    <td>{{ $record->tool->name }}</td>
                                    <td>{{ $record->quantity_dispatch }}</td>
                                    <!-- <td>{{ $record->updated_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td> -->
                                    <!-- <td>
                                        <form action="{{ route('dispatch.destroy', $record->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td> -->
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No dispatch records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        {!! $records->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                            </div>
                            
                        </div>
  {{-- All Process Records Table --}}
                                <!-- <div class="card shadow mt-4">
                                    <div class="card-body">
                                        <h5 class="mb-3 text-success">All Dispatch Process Records</h5>

                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th style="white-space: nowrap;">Sr No</th>
                                                        <th>Date</th>
                                                        <th style="white-space: nowrap;">Component Name</th>
                                                        <th style="white-space: nowrap;">Quantity Diapatched</th>
                                                        <th style="white-space: nowrap;">Updated At</th>
                                                   
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
                                                        
                                                            <td>{{ $record->quantity_dispatch }}</td>
                                                      
                                                      
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
                                </div> -->
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
        // Quantity input validate
        $('#quantity_dispatch').on('input', function () {
            let available = parseInt($('#available_quantity').text()) || 0;
            let entered = parseInt($(this).val()) || 0;

            if (entered > available) {
                $(this).val(available); // auto set to max
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Open Modal and set data
        $('.open-treem-modal').click(function () {
            var recordId = $(this).data('id');
            var treem = $(this).data('treem');

            $('#modal_record_id').val(recordId);
            $('#available_quantity_hidden').val(treem);
            $('#available_quantity_display').text(treem);
            $('#modal_treem').attr('max', treem);
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
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
                                        <form action="{{ route('casting.updateInspection') }}" method="POST">
                                            @csrf

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="treem" class="form-label">Date:</label>
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

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Quantity Inspected</label>
                                                  <input type="number" 
       name="quantity_inspected" 
       id="quantity_inspected" 
       step="1" 
       min="1" 
       class="form-control" 
       required>

                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Ok</label>
                                                    <input type="number" name="ok_quantity" step="0.01" min="0.01"
                                                        class="form-control" required>
                                                </div>


                                            </div>

                                            <div class="row mb-4">

                                                <div class="col-md-6">
                                                    <label class="form-label">Rejected</label>
                                                    <input type="number" name="rejected_quantity" min="1"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Reason of Rejection</label>
                                                    <select name="rejection_reason" class="form-select" required>
                                                        <option value="">Select Reason of Rejection</option>
                                                        <option value="Non-Filling">Non-Filling</option>
                                                        <option value="Trimming">Trimming</option>
                                                        <option value="Casting">Casting</option>
                                                        <option value="Blow hole">Blow hole</option>
                                                        <option value="Sound test">Sound test</option>
                                                    </select>
                                                </div>
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
                                                <h5 class="mb-3 text-primary">Trimming  Records</h5>
 
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
                                                        <th style="white-space: nowrap;">Quantity Inspected</th>
                                                        <th style="white-space: nowrap;">Ok Quantity</th>

                                                        <th style="white-space: nowrap;">Rejected Quantity</th>
                                                        <th style="white-space: nowrap;">Rejection Reason</th>
                                                     
                                                    </tr>
                                                        </thead>
                                                        <tbody>
                                                             @forelse($records as $index => $record)
                                                        <tr>
                                                            <td>{{ $records->firstItem() + $index }}</td>


                                                            <td>{{ $record->date }}</td>
                                                            <td>{{ $record->tool->name }}</td>
                                                            <td>{{ $record->quantity_inspected }}</td>
                                                            <td>{{ $record->ok_quantity }}</td>
                                                            <td>{{ $record->rejected_quantity }}</td>
                                                            <td>{{ $record->rejection_reason }}</td>


                                                           

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center">No records found</td>
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
        <form method="GET" action="{{ route('inspection.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name/reason">
            </div>

            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
            </div>

            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('inspection.index') }}" class="btn btn-secondary">Reset</a>
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
$(document).ready(function() {
    // Auto limit Inspection quantity to available trimming
    $('select[name="tool_type_id"]').change(function() {
        var toolId = $(this).val();
        
        if(toolId) {
            // Ajax call thi trimming qty fetch karo
            $.get('/api/get-trimming-qty/' + toolId, function(data) {
                let available = data.quantity_pcs || 0;
                $('#quantity_inspected').attr('max', available); // set max
            });
        }
    });

    // Restrict typing beyond max
    $('#quantity_inspected').on('input', function() {
        let max = parseInt($(this).attr('max')) || 0;
        let val = parseInt($(this).val()) || 0;
        if (val > max) {
            $(this).val(max);
        }
    });
});
</script>

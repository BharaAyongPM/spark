@extends('vendor.layout.master')

@push('plugin-styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Field Type Management</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fieldTypeModalCreate">
                    <i class="mdi mdi-plus"></i> Add Field Type
                </button>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="fieldTypesTable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th>Type Name</th>
                                    <th>Description</th>
                                    <th style="width:15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($fieldTypes as $fieldType)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $fieldType->type_name }}</td>
                                        <td>{{ $fieldType->description }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-success mb-1" data-bs-toggle="modal"
                                                data-bs-target="#fieldTypeModalEdit{{ $fieldType->id }}">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </button>
                                            <form action="{{ route('field_types.destroy', $fieldType) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mb-1">
                                                    <i class="mdi mdi-delete"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Field Type Modal -->
    <div class="modal fade" id="fieldTypeModalCreate" tabindex="-1" aria-labelledby="fieldTypeModalCreateLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('field_types.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="fieldTypeModalCreateLabel">Add New Field Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="type_name" class="form-label">Type Name</label>
                            <input type="text" class="form-control" id="type_name" name="type_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Field Type Modals -->
    @foreach ($fieldTypes as $fieldType)
        <div class="modal fade" id="fieldTypeModalEdit{{ $fieldType->id }}" tabindex="-1"
            aria-labelledby="fieldTypeModalEditLabel{{ $fieldType->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('field_types.update', $fieldType) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="fieldTypeModalEditLabel{{ $fieldType->id }}">Edit Field Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Type Name</label>
                                <input type="text" class="form-control" name="type_name"
                                    value="{{ $fieldType->type_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" required>{{ $fieldType->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('plugin-scripts')
    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#fieldTypesTable').DataTable({
                responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush

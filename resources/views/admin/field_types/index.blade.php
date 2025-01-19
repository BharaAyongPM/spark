{{-- resources/views/admin/field_types/index.blade.php --}}
<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="field_types"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Field Type Management"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fieldTypeModalCreate">
                Add Field Type
            </button>

            <!-- Field Types Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fieldTypes as $fieldType)
                                <tr>
                                    <td>{{ $fieldType->type_name }}</td>
                                    <td>{{ $fieldType->description }}</td>
                                    <td>
                                        <button class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#fieldTypeModalEdit{{ $fieldType->id }}">Edit</button>
                                        <form action="{{ route('field_types.destroy', $fieldType) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Create Field Type Modal -->
    <div class="modal fade" id="fieldTypeModalCreate" tabindex="-1" role="dialog"
        aria-labelledby="fieldTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fieldTypeModalLabel">Add New Field Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('field_types.store') }}" method="POST">
                    @csrf
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
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Field Type Modals for each field type -->
    @foreach ($fieldTypes as $fieldType)
        <div class="modal fade" id="fieldTypeModalEdit{{ $fieldType->id }}" tabindex="-1" role="dialog"
            aria-labelledby="fieldTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fieldTypeModalLabel">Edit Field Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('field_types.update', $fieldType) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="type_name" class="form-label">Type Name</label>
                                <input type="text" class="form-control" id="type_name" name="type_name"
                                    value="{{ $fieldType->type_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" required>{{ $fieldType->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>

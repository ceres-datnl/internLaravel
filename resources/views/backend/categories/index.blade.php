@extends('backend.layouts.app')

@section('title', __('Category'))

@push('after-styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endpush

@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{session()->get('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <x-backend.card>
        <x-slot name="header">
            @lang('Categories Management')
        </x-slot>
        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.categories.add')"
                    :text="__('Add Category')"
                />
            </x-slot>
        @endif
        <x-slot name="body">
            <table id="data-table-categories" class="table table-striped m-0" data-ordering="false">
                <thead>
                <tr class="bg-secondary">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Create_date</th>
                    <th>Actions</th>
                </tr>
                <tr class="searchByColumn">
                    <td><input type="text" class="form-control" placeholder="#ID" name="id"></td>
                    <td><input type="text" class="form-control" placeholder="#Name" name="name"></td>
                    <td>
                        <select class="form-control" name="status">
                            <option value="">-- Select --</option>
                            <option value="0">Deactive</option>
                            <option value="1">Active</option>
                        </select>
                    </td>
                    <td><input type="date" class="form-control" name="created_at"></td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </x-slot>
    </x-backend.card>
@endsection
@push('after-scripts')
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {

            let columns = [
                {
                    data: 'id', name: 'id'
                },
                {
                    data: 'name', name: 'name'
                },
                {
                    data: 'status', name: 'status'
                },
                {
                    data: 'created_at', name: 'created_at'
                },
                {
                    data: 'actions'
                },

            ];
            dataTables = $('#data-table-categories').DataTable({
                pagingType: 'full_numbers',
                "dom": 'rtip',
                paging: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{route('admin.categories.ajax')}}",
                    data: function (d) {
                        d.id = $("#data-table-categories input[name='id']").val();
                        d.name = $("#data-table-categories input[name='name']").val();
                        d.status = $("#data-table-categories select[name='status']").val();
                        d.created_at = $("#data-table-categories input[name='created_at']").val();
                    },
                },
                columns: columns,
                retrieve: true,
            });
            $(document).on("change", ".searchByColumn td input, .searchByColumn td select", function () {
                $('#data-table-categories').DataTable().ajax.reload();
            });
        });
        // Button Delete
        $(document).on("click", '.buttonDelete', function (e) {
            let id = $(this).data('id');
            let row = $(this).parent().parent();
            Swal.fire({
                title: 'Are you sure?',
                text: "This item will be moved to the trash",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('admin.categories.delete')}}",
                        type: "get",
                        data: {
                            id: id
                        },
                        success: function (result) {
                            row.html("");
                            result = JSON.parse(result);
                            console.log(result);
                            if (result.status === "OK") {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush




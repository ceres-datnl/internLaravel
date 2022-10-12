@extends('backend.layouts.app')

@section('title', __('Category'))
@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection
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
            <table id="data-table-categories" class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Create_date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </x-slot>
    </x-backend.card>
@endsection
@section('js')
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#data-table-categories').DataTable({
                processing : true,
                serverSide : true,
                ajax: "{{route('admin.categories.ajax')}}",
                columns : [
                    {data : 'id'},
                    {data : 'name'},
                    {data : 'status'},
                    {data : 'created_at'},
                    {data : 'actions'},
                ]
            });
        });
        $(document).on("click", '.buttonDelete', function (e){
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
                            if(result.status === "OK"){
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
@endsection

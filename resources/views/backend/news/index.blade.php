@extends('backend.layouts.app')
@section('title', __('News'))
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"/>
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
            @lang('News Management')
        </x-slot>
        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.news.add')"
                    :text="__('Add News')"
                />
            </x-slot>
        @endif
        <x-slot name="body">
            <table class="table table-striped" id="data-table-news">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created_date</th>
                    <th>Actions</th>
                </tr>
                <tr class="searchByColumn m-0 p-0">
                    <td><input type="text" class="form-control" placeholder="#ID" name="id"></td>
                    <td>
                        <select class="form-control" name="category_id">
                            <option value="">-- Select --</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" placeholder="#Title" name="title"></td>
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
            </table>
        </x-slot>
    </x-backend.card>
@endsection
@push('after-scripts')
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        //Datatable
        $(document).ready(function () {
            $("select[name='category_id']").select2({
                ajax: {
                    url: '{{route("admin.news.loadCategory")}}',
                    type: "POST",
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            "search": params.term,
                            "_token": "{{ csrf_token() }}",
                            "page": params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        return {
                            results: response,
                            pagination: {
                                more: true
                            }
                        };
                    },
                    cache: true
                },
            });
            //datatables
            let columns = [
                {
                    data: 'id', name: 'id'
                },
                {
                    data: 'nameCategory', name: 'nameCategory'
                },
                {
                    data: 'title', name: 'title'
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
            dataTables = $('#data-table-news').DataTable({
                pagingType: 'full_numbers',
                "dom": 'rtip',
                paging: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{route('admin.news.ajaxLoadListNews')}}",
                    data: function (d) {
                        d.id = $("#data-table-news input[name='id']").val();
                        d.category_id = $("#data-table-news select[name='category_id']").val();
                        d.title = $("#data-table-news input[name='title']").val();
                        d.status = $("#data-table-news select[name='status']").val();
                        d.created_at = $("#data-table-news input[name='created_at']").val();
                    },
                },
                columns: columns,
                retrieve: true,
            });
            $(document).on("change", ".searchByColumn td input, .searchByColumn td select", function () {
                $('#data-table-news').DataTable().ajax.reload();
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
                        url: "{{route('admin.news.delete')}}",
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

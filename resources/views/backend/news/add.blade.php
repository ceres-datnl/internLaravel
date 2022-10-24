@extends('backend.layouts.app')

@section('title', __('News'))
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="alert alert-danger alert-dismissible fade show notification">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <div class="message"></div>
    </div>
    <x-forms.post :action="route('admin.news.store')" enctype="multipart/form-data" id="formAddNews">
        <x-backend.card>
            <x-slot name="header">
                @lang('Add News')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.news.index')" :text="__('Back')"/>
            </x-slot>
            <x-slot name="body">
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Category:</label>
                    <div class="col-10">
                        <select class="form-control" name="category_id">
                            <option value="">--Select Category--</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Title:</label>
                    <div class="col-10">
                        <input type="text" class="form-control" placeholder="Enter title news" name="title">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Content:</label>
                    <div class="col-10">
                            <textarea class="form-control" rows="5" id="comment" placeholder="Enter content news" name="m_content"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label" >Status:</label>
                    <div class="col-10">
                        <select class="form-control custom-select" name="status" >
                            <option selected>Choose Status...</option>
                            <option value="0">Deactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Image:</label>
                    <div class="col-10">
                        <input type="file" class="form-control" name="imageNews">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function (){
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
        });
        $("select[name='category_id']").val('2');
        $("select[name='category_id']").trigger('change');
        $(".notification").hide();
        $(document).on("submit", "#formAddNews", function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $("#formAddNews").attr('action'),
                data: $("#formAddNews").serialize(),
                success: function (result) {
                    if (result.errors !== undefined) {
                        console.log(result.errors)
                        let message = "";
                        $("#formAddNews button[type='submit']").prop('disabled', false);
                        $(".notification").show();
                        for (const [key, value] of Object.entries(result.errors)) {
                            value.forEach(function (item){
                                message += item+"<br>";
                            });
                        }
                        $(".notification .message").html(message);
                    } else {
                        $('#formAddNews')[0].submit();
                    }
                },
                error: function (result) {
                    console.log(result);
                }
            });
        });
    </script>
@endpush

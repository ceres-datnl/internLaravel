@extends('backend.layouts.app')

@section('title', __('Category'))
@section('content')
    <div class="alert alert-danger alert-dismissible fade show notification">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <div class="message"></div>
    </div>
    <x-forms.post :action="route('admin.categories.store')" id="formAddCategory">
        <x-backend.card>
            <x-slot name="header">
                @lang('Add Category')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.categories.index')" :text="__('Back')"/>
            </x-slot>
            <x-slot name="body">
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Name Category:</label>
                    <div class="col-10">
                        <input type="text" class="form-control" placeholder="Enter name category" name="name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Status:</label>
                    <div class="col-10">
                        <select class="form-control custom-select" name="status">
                            <option selected>Choose Status...</option>
                            <option value="0">Deactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
@push('after-scripts')
    <script>
        $(".notification").hide();

        $(document).on("submit", "#formAddCategory", function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $("#formAddCategory").attr('action'),
                data: $("#formAddCategory").serialize(),
                success: function (result) {
                    if (result.errors !== undefined) {
                        console.log(result.errors);
                        let message = "";
                        $("#formAddCategory button[type='submit']").prop('disabled', false);
                        $(".notification").show();
                        for (const [key, value] of Object.entries(result.errors)) {
                            value.forEach(function (item){
                                message += item+"<br>";
                            });
                        }
                        $(".notification .message").html(message);
                    } else {
                        $('#formAddCategory')[0].submit();
                    }
                },
                error: function (result) {
                    console.log(result);
                }
            });
        });
    </script>
@endpush


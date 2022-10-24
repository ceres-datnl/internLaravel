@extends('backend.layouts.app')

@section('title', __('Category'))
@section('content')
    <div class="alert alert-danger alert-dismissible fade show notification">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <div class="message"></div>
    </div>
    <x-forms.post :action="route('admin.categories.update', $dataCategory->id)" id="formEditCategory">
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Category')
            </x-slot>
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.categories.index')" :text="__('Back')"/>
            </x-slot>
            <x-slot name="body">
                @method('put')
                <input type="hidden" value="{{$dataCategory->id}}" name="id">
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Name Category:</label>
                    <div class="col-10">
                        <input type="text" class="form-control" placeholder="Enter name category" name="name"
                               value="{{$dataCategory->name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Status:</label>
                    <div class="col-10">
                        <select class="form-control custom-select" name="status">
                            <option selected>Choose Status...</option>
                            <option value="0" {{$dataCategory->status == 0 ? "Selected" : ""}}>Deactive</option>
                            <option value="1" {{$dataCategory->status == 1 ? "Selected" : ""}}>Active</option>
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

        $(document).on("submit", "#formEditCategory", function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $("#formEditCategory").attr('action'),
                data: $("#formEditCategory").serialize(),
                success: function (result) {
                    if (result.errors !== undefined) {
                        console.log(result.errors);
                        let message = "";
                        $("#formEditCategory button[type='submit']").prop('disabled', false);
                        $(".notification").show();
                        for (const [key, value] of Object.entries(result.errors)) {
                            value.forEach(function (item) {
                                message += item + "<br>";
                            });
                        }
                        $(".notification .message").html(message);
                    } else {
                        $('#formEditCategory')[0].submit();
                    }
                },
                error: function (result) {
                    console.log(result);
                }
            });
        });
    </script>
@endpush

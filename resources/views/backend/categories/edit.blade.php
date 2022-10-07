@extends('backend.layouts.app')

@section('title', __('Category'))
@section('content')
    <x-forms.post :action="route('admin.categories.update')">
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
                        <input type="text" class="form-control" placeholder="Enter name category" name="name" value="{{$dataCategory->name}}">
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

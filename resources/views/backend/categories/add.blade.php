@extends('backend.layouts.app')

@section('title', __('Category'))
@section('content')
    <x-forms.post :action="route('admin.categories.store')">
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

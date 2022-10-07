@extends('backend.layouts.app')

@section('title', __('Category'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Detail Category')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.categories.index')" :text="__('Back')"/>
        </x-slot>
        <x-slot name="body">
            <table class="table">
                <tbody>
                <tr>
                    <td>
                        <b>ID</b>
                    </td>
                    <td>{{$dataCategory->id}}</td>
                </tr>
                <tr>
                    <td>
                        <b>Name</b>
                    </td>
                    <td>{{$dataCategory->name}}</td>
                </tr>
                <tr>
                    <td>
                        <b>Status</b>
                    </td>
                    <td>
                        @if($dataCategory->status == 0)
                            <span class="badge badge-danger">Deactive</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Created Date</b>
                    </td>
                    <td>{{date_format($dataCategory->created_at,'H:i:s d-m-Y')}}</td>
                </tr>
                <tr>
                    <td>
                        <b>Updated Date</b>
                    </td>
                    <td>{{date_format($dataCategory->updated_at,'H:i:s d-m-Y')}}</td>
                </tr>
                </tbody>
            </table>
        </x-slot>
    </x-backend.card>
@endsection

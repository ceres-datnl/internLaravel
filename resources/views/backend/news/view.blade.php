@extends('backend.layouts.app')

@section('title', __('News'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Detail News')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.news.index')" :text="__('Back')"/>
        </x-slot>
        <x-slot name="body">
            <table class="table">
                <tbody>
                <tr>
                    <div class="row">
                        <td class="col-6">
                            <b>ID</b>
                        </td>
                        <td class="col-6">{{$dataNews->id}}</td>
                    </div>
                </tr>
                <tr>
                    <td>
                        <b>Category</b>
                    </td>
                    <td>{{$dataNews->category->name}}</td>
                </tr>
                <tr>
                    <td>
                        <b>Title</b>
                    </td>
                    <td>{{$dataNews->title}}</td>
                </tr>
                <tr>
                    <td>
                        <b>Content</b>
                    </td>
                    <td class="text-break">
                        {!!$dataNews->content!!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Status</b>
                    </td>
                    <td>
                        @if($dataNews->status == 0)
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
                    <td>{{date_format($dataNews->created_at,'H:i:s d-m-Y')}}</td>
                </tr>
                <tr>
                    <td>
                        <b>Updated Date</b>
                    </td>
                    <td>{{date_format($dataNews->updated_at,'H:i:s d-m-Y')}}</td>
                </tr>
                @if($dataNews->file)
                    <tr>
                        <td>
                            <b>Image</b>
                        </td>
                        <td><img src="{{\App\Helpers\ImageUtils::getUrlImage($dataNews->file->path,$dataNews->file->name,\App\Helpers\ImageUtils::IMG_SIZE_MEDIUM)}}" width="550px" height="auto" class="rounded"
                                 alt="Cinque Terre">
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </x-slot>
    </x-backend.card>
@endsection


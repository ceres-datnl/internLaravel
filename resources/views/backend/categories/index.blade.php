@extends('backend.layouts.app')

@section('title', __('Category'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Categories Management')
        </x-slot>
        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.categories.create')"
                    :text="__('Add Category')"
                />
            </x-slot>
        @endif
        <x-slot name="body">
            <table class="table">
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
                @foreach($listCategory as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{App\Services\CategoryService::getStatusLabel($item->status)}}</td>
                        <td>{{date_format($item->created_at,'H:i:s Y-m-d')}}</td>
                        <td>
                            <a class="btn btn-primary" href="formEdit/{{$item->id}}" role="button"><i class="fa-solid fa-pen"></i> &nbsp;Edit</a>
                            <a class="btn btn-danger" href="#" role="button"><i class="fa-solid fa-trash"></i> &nbsp;Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $listCategory->links() }}
        </x-slot>
    </x-backend.card>
@endsection

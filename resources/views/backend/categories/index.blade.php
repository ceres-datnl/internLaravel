@extends('backend.layouts.app')

@section('title', __('Category'))

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
                        <td>{{date_format($item->created_at,'H:i:s d-m-Y')}}</td>
                        <td>
                            <x-utils.link
                                icon="fa-solid fa-eye"
                                class="btn btn-info"
                                role="button"
                                :href="route('admin.categories.view',['id' => $item->id ])"
                                :text="__('View')"
                            />
                            <x-utils.link
                                icon="fa-solid fa-pen"
                                class="btn btn-primary"
                                role="button"
                                :href="route('admin.categories.edit',['id' => $item->id ])"
                                :text="__('Edit')"
                            />
                            <a class="btn btn-danger" href="#" role="button"><i class="fa-solid fa-trash"></i>
                                &nbsp;Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $listCategory->links() }}
        </x-slot>
    </x-backend.card>
@endsection

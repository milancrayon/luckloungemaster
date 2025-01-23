@extends('master.layouts.app')
@section('panel')

<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-4 mb-30">

        <div class="card b-radius--5 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex p-3 bg--primary align-items-center">
                    <div class="ps-3">
                        <h4 class="text--white">{{__($master->mastername)}}</h4>
                    </div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('First Name')
                        <span class="fw-bold">{{__($master->firsname)}}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Last Name')
                        <span class="fw-bold">{{__($master->lastname)}}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Email')
                        <span class="fw-bold">{{$master->email}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Mobile Number')
                        <span class="fw-bold">{{$master->email}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Address')
                        <span class="fw-bold">{{$master->email}}</span>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-8 mb-30">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 border-bottom pb-2">@lang('Profile Information')</h5>

                <form action="{{ route('master.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-xxl-8 col-lg-6">
                            <div class="form-group ">
                                <label>@lang('Name')</label>
                                <input class="form-control" type="text" name="name" value="{{ $master->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Email')</label>
                                <input class="form-control" type="email" name="email" value="{{ $master->email }}" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{route('master.password')}}" class="btn btn-sm btn-outline--primary"><i class="las la-key"></i>@lang('Password Setting')</a>
@endpush
@push('style')
<style>
    .list-group-item:first-child {
        border-top-left-radius: unset;
        border-top-right-radius: unset;
    }
</style>
@endpush
@extends('master.layouts.app')
@section('panel')

<div class="row mb-none-30">
    <div class="col-lg-3 col-md-3 mb-30">

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
                        <span class="fw-bold">{{ $master->firstname }}</span>
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
                        <span class="fw-bold">+{{ $master->dial_code }} {{ $master->mobile }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Address')
                        <span class="fw-bold">
                            {{ @$master->address }}, <br />
                            {{ @$master->city }} {{ @$master->state }} {{ @$master->zip }}, <br />
                            @foreach ($countries as $key => $country)
                            @if ($master->country_code == $key)
                            {{ __($country->country) }}
                            @endif
                            @endforeach
                        </span>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-9 mb-30">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 border-bottom pb-2">@lang('Change Password')</h5>

                <form action="{{ route('master.password.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>@lang('Password')</label>
                        <input class="form-control" type="password" name="old_password" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('New Password')</label>
                        <input class="form-control" type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('Confirm Password')</label>
                        <input class="form-control" type="password" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('breadcrumb-plugins')
<a href="{{route('master.profile')}}" class="btn btn-sm btn-outline--primary"><i class="las la-user"></i>@lang('Profile Setting')</a>
@endpush
@push('style')
<style>
    .list-group-item:first-child {
        border-top-left-radius: unset;
        border-top-right-radius: unset;
    }
</style>
@endpush
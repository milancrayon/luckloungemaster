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
                            {{ @$master->address }}, 
                            {{ @$master->city }} {{ @$master->state }} {{ @$master->zip }},
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

    <div class="col-xl-9 col-lg-8 mb-30">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 border-bottom pb-2">@lang('Profile Information')</h5>

                <form action="{{ route('master.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('First Name')</label>
                                <input class="form-control" type="text" name="firstname" required value="{{ $master->firstname }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">@lang('Last Name')</label>
                                <input class="form-control" type="text" name="lastname" required value="{{ $master->lastname }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Email') </label>
                                <input class="form-control" type="email" name="email" value="{{ $master->email }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number') </label>
                                <div class="input-group ">
                                    <span class="input-group-text mobile-code">+{{ $master->dial_code }}</span>
                                    <input type="number" name="mobile" value="{{ $master->mobile }}" id="mobile" class="form-control checkMaster" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>@lang('Address')</label>
                                <input class="form-control" type="text" name="address" value="{{ @$master->address }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group">
                                <label>@lang('City')</label>
                                <input class="form-control" type="text" name="city" value="{{ @$master->city }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('State')</label>
                                <input class="form-control" type="text" name="state" value="{{ @$master->state }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Zip/Postal')</label>
                                <input class="form-control" type="text" name="zip" value="{{ @$master->zip }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Country')<span class="text--danger">*</span></label>
                                <select name="country" class="form-control select2">
                                    @foreach ($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" @selected($master->country_code == $key)>{{ __($country->country) }}</option>
                                    @endforeach
                                </select>
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
@push('script')
<script>
    (function($) {
        "use strict"

        let mobileElement = $('.mobile-code');
        $('select[name=country]').on('change', function() {
            mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

    })(jQuery);
</script>
@endpush
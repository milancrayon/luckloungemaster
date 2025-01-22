@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-12">
        <div class="row gy-4">

            <div class="col-xxl-3 col-sm-6">
                <x-widget
                    style="7"
                    title="Balance"
                    icon="las la-money-bill-wave-alt"
                    value="{{ showAmount(0) }}"
                    bg="indigo"
                    type="2" />
            </div>


            <div class="col-xxl-3 col-sm-6">
                <x-widget
                    style="7"
                    title="Deposits"
                    icon="las la-wallet"
                    value="{{ showAmount($totalDeposit) }}"
                    bg="8"
                    type="2" />
            </div>

            <div class="col-xxl-3 col-sm-6">
                <x-widget
                    style="7"
                    title="Withdrawals"
                    icon="la la-bank"
                    value="{{ showAmount($totalWithdrawals) }}"
                    bg="6"
                    type="2" />
            </div>

            <div class="col-xxl-3 col-sm-6">
                <x-widget
                    style="7"
                    title="Transactions"
                    icon="las la-exchange-alt"
                    value="{{ $totalTransaction }}"
                    bg="17"
                    type="2" />
            </div>

        </div>


        <div class="card mt-30">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Information of')</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.masters.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('First Name')</label>
                                <input class="form-control" type="text" name="firstname" required value="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">@lang('Last Name')</label>
                                <input class="form-control" type="text" name="lastname" required value="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Email') </label>
                                <input class="form-control" type="email" name="email" value="" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number') </label>
                                <div class="input-group ">
                                    <span class="input-group-text mobile-code">+</span>
                                    <input type="number" name="mobile" value="" id="mobile" class="form-control checkUser" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>@lang('Address')</label>
                                <input class="form-control" type="text" name="address" value="">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group">
                                <label>@lang('City')</label>
                                <input class="form-control" type="text" name="city" value="">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('State')</label>
                                <input class="form-control" type="text" name="state" value="">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Zip/Postal')</label>
                                <input class="form-control" type="text" name="zip" value="">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Country')<span class="text--danger">*</span></label>
                                <select name="country" class="form-control select2">
                                    @foreach ($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="form-group">
                                <label>@lang('Email Verification')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                    >
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="form-group">
                                <label>@lang('Mobile Verification')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                    >
                            </div>
                        </div>
                        <div class="col-xl-3 col-md- col-12">
                            <div class="form-group">
                                <label>@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ts">
                            </div>
                        </div>
                        <div class="col-xl-3 col-md- col-12">
                            <div class="form-group">
                                <label>@lang('KYC') </label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="kv">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <div id="passwordreset" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span>@lang('Password Reset')</span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.masters.passwordset', $master->id) }}" class="balanceAddSub disableSubmission" method="POST">
                @csrf
                <input type="hidden" name="act">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Password')</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" placeholder="@lang('New Password')" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Password Confirm')</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('Confirmed Password')" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

@endsection

@push('breadcrumb-plugins')

@endpush

@push('script')
<script>
    (function($) {
        "use strict"


        $('.bal-btn').on('click', function() {

            $('.balanceAddSub')[0].reset();

            var act = $(this).data('act');
            $('#addSubModal').find('input[name=act]').val(act);
            if (act == 'add') {
                $('.type').text('Add');
            } else {
                $('.type').text('Subtract');
            }
        });

        let mobileElement = $('.mobile-code');
        $('select[name=country]').on('change', function() {
            mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

    })(jQuery);
</script>
@endpush
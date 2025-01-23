@extends('master.layouts.app')

@section('panel')
<div class="row">
    <div class="col-12">
        <div class="row gy-4">

            <div class="col-xxl-3 col-sm-6">
                <x-widget
                    style="7"

                    title="Balance"
                    icon="las la-money-bill-wave-alt"
                    value="{{ showAmount($customer->balance) }}"
                    bg="indigo"
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

        <div class="d-flex flex-wrap gap-3 mt-4">
            <div class="flex-fill">
                <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-act="add">
                    <i class="las la-plus-circle"></i> @lang('Balance')
                </button>
            </div>

            <div class="flex-fill">
                <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="sub">
                    <i class="las la-minus-circle"></i> @lang('Balance')
                </button>
            </div>

            <div class="flex-fill">
                <a class="btn btn--primary btn--shadow w-100 btn-lg">
                    <i class="las la-list-alt"></i>@lang('Logins')
                </a>
            </div>

            <div class="flex-fill">
                <button data-bs-toggle="modal" data-bs-target="#passwordreset" class="btn btn--success btn--shadow w-100 btn-lg bal-btn">
                    <i class="las la-plus-circle"></i> @lang('Password Reset')
                </button>
            </div>

            @if ($customer->kyc_data)
            <div class="flex-fill">
                <a href="{{ route('master.customers.kyc.details', $customer->id) }}" target="_blank" class="btn btn--dark btn--shadow w-100 btn-lg">
                    <i class="las la-user-check"></i>@lang('KYC Data')
                </a>
            </div>
            @endif

            <div class="flex-fill">
                @if ($customer->status == Status::USER_ACTIVE)
                <button type="button" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                    <i class="las la-ban"></i>@lang('Ban User')
                </button>
                @else
                <button type="button" class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                    <i class="las la-undo"></i>@lang('Unban User')
                </button>
                @endif
            </div>
        </div>


        <div class="card mt-30">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Information of') {{ $customer->fullname }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('master.customers.update', [$customer->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('First Name')</label>
                                <input class="form-control" type="text" name="firstname" required value="{{ $customer->firstname }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">@lang('Last Name')</label>
                                <input class="form-control" type="text" name="lastname" required value="{{ $customer->lastname }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Email') </label>
                                <input class="form-control" type="email" name="email" value="{{ $customer->email }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number') </label>
                                <div class="input-group ">
                                    <span class="input-group-text mobile-code">+{{ $customer->dial_code }}</span>
                                    <input type="number" name="mobile" value="{{ $customer->mobile }}" id="mobile" class="form-control checkUser" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>@lang('Address')</label>
                                <input class="form-control" type="text" name="address" value="{{ @$customer->address }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group">
                                <label>@lang('City')</label>
                                <input class="form-control" type="text" name="city" value="{{ @$customer->city }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('State')</label>
                                <input class="form-control" type="text" name="state" value="{{ @$customer->state }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Zip/Postal')</label>
                                <input class="form-control" type="text" name="zip" value="{{ @$customer->zip }}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Country')<span class="text--danger">*</span></label>
                                <select name="country" class="form-control select2">
                                    @foreach ($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" @selected($customer->country_code == $key)>{{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="form-group">
                                <label>@lang('Email Verification')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                    @if ($customer->ev) checked @endif>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="form-group">
                                <label>@lang('Mobile Verification')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                    @if ($customer->sv) checked @endif>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md- col-12">
                            <div class="form-group">
                                <label>@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ts" @if ($customer->ts) checked @endif>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md- col-12">
                            <div class="form-group">
                                <label>@lang('KYC') </label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="kv" @if ($customer->kv == Status::KYC_VERIFIED) checked @endif>
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



{{-- Add Sub Balance MODAL --}}
<div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('master.customers.add.sub.balance', $customer->id) }}" class="balanceAddSub disableSubmission" method="POST">
                @csrf
                <input type="hidden" name="act">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')" required>
                            <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Remark')</label>
                        <textarea class="form-control" placeholder="@lang('Remark')" name="remark" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="passwordreset" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span>@lang('Password Reset')</span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('master.customers.passwordset', $customer->id) }}" class="balanceAddSub disableSubmission" method="POST">
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
</div>


<div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if ($customer->status == Status::USER_ACTIVE)
                    @lang('Ban User')
                    @else
                    @lang('Unban User')
                    @endif
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('master.customers.status', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($customer->status == Status::USER_ACTIVE)
                    <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                    <div class="form-group">
                        <label>@lang('Reason')</label>
                        <textarea class="form-control" name="reason" rows="4" required></textarea>
                    </div>
                    @else
                    <p><span>@lang('Ban reason was'):</span></p>
                    <p>{{ $customer->ban_reason }}</p>
                    <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                    @endif
                </div>
                <div class="modal-footer">
                    @if ($customer->status == Status::USER_ACTIVE)
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    @else
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('master.customers.login', $customer->id) }}" target="_blank" class="btn btn-sm btn-outline--primary"><i class="las la-sign-in-alt"></i>@lang('Login as User')</a>
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
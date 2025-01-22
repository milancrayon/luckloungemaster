@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.deposit.store') }}" >
                    @csrf
                    <input type="hidden" name="currency">
                    <input type="hidden" name="user_id" value="{{$userId}}">
                    <div class="row"> 
                        <div class="col-sm-12 col-md-6">
                            <div class="col-12">
                                <div class="form-group">
                                <label> @lang('Gateway')</label>
                                    @foreach ($gateways as $data) 
                                    <div class="form-check"> 
                                        <input class="form-check-input gateway-input" id="{{ titleToKey($data->name) }}" 
                                                data-gateway='@json($data)' type="radio" name="gateway" value="{{ $data->method_code }}"
                                                @checked($loop->first)
                                                data-min-amount="{{ showAmount($data->min_amount) }}"
                                                data-max-amount="{{ showAmount($data->max_amount) }}"  
                                                >
                                        <label for="{{ titleToKey($data->name) }}" class="form-check-label"> {{ __($data->name) }} </label>
                                        </div>
                                    @endforeach  
                                    </div>
                            </div> 
                            <div class="col-12">
                                <div class="form-group">
                                    <label> @lang('Identifier')</label>
                                    <select class="select2 form-control" name="option_identifier" required>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group ">
                                    <label>@lang('Amount')</label>
                                    <input class="form-control" type="text" class="form-control form--control amount"
                                        name="amount" placeholder="@lang('00.00')" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group ">
                                    <label>@lang('Transaction Id')</label>
                                    <input class="form-control" type="text" class="form-control form--control amount"
                                        name="transaction_id" value="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                        <div class="deposit-info row">
                            <div class="col">
                                <p class="text has-icon"> @lang('Limit')
                                    <span></span>
                                </p>
                            </div>
                            <div class="col text-end">
                                <p class="text"><span class="gateway-limit">@lang('0.00')</span>
                                </p>
                            </div>
                        </div>
                        <div class="deposit-info row">
                            <div class="col">
                                <p class="text has-icon">@lang('Processing Charge')
                                    <span data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')" class="proccessing-fee-info"><i
                                            class="las la-info-circle"></i> </span>
                                </p>
                            </div>
                            <div class="col text-end">
                                <p class="text"><span class="processing-fee">@lang('0.00')</span>
                                    {{ __(gs('cur_text')) }}
                                </p>
                            </div>
                        </div>

                        <div class="deposit-info row total-amount pt-3">
                            <div class="col">
                                <p class="text">@lang('Total')</p>
                            </div>
                            <div class="col text-end">
                                <p class="text"><span class="final-amount">@lang('0.00')</span>
                                    {{ __(gs('cur_text')) }}</p>
                            </div>
                        </div>

                        <div class="deposit-info row gateway-conversion d-none total-amount pt-2">
                            <div class="col">
                                <p class="text">@lang('Conversion')
                                </p>
                            </div>
                            <div class="col text-end">
                                <p class="text"></p>
                            </div>
                        </div>
                        <div class="deposit-info row conversion-currency d-none total-amount pt-2">
                            <div class="col">
                                <p class="text">
                                    @lang('In') <span class="gateway-currency"></span>
                                </p>
                            </div>
                            <div class="col text-end">
                                <p class="text">
                                    <span class="in-currency"></span>
                                </p>

                            </div>
                        </div>
                        <div class="d-none crypto-message">
                            @lang('Conversion with') <span class="gateway-currency"></span> @lang('and final value will Show on next step')
                        </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        "use strict";
        (function ($) {

            var amount = parseFloat($('#amount').val() || 0);

            var gateway, minAmount, maxAmount;

            $('#amount').on('input', function(e) {
                amount = parseFloat($(this).val());
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            function handleUpiChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();
                
                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');

                let processingFeeInfo =
                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`
                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);


                let _extra =JSON.parse(gateway.method.extra);
                let identifiers = _extra.identifier; 
                $('#option_identifier').val(null).trigger('change');
                let _data = [];
                identifiers.map((e)=>{  
                    _data.push({
                        id:e,text:e,
                    });
                }); 
                $('#option_identifier').val(null).empty().select2('destroy')
                $('#option_identifier').select2({data:_data}).trigger('change'); 
                $("input[name=currency]").val(gateway.currency);
                calculation();

            }
            handleUpiChange();

            $('.gateway-input').on('change', function(e) {
                handleUpiChange();
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".deposit-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".deposit-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                    $('.deposit-form').addClass('adjust-height')

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ? 8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

        })(jQuery);
    </script>
@endpush
@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="pt-120 pb-120 deposit_view">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-0">
                        @php 
                        @endphp
                        <div class="row text-center">
                            <i class="fas fa-check"></i>
                            <strong>{{ showAmount($data->final_amount, currencyFormat: false) }}
                                {{ __($data->method_currency) }}</strong>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <p>@lang('Gateway')</p>
                            </div>
                            <div class="col text-end">
                                <p>{{ __(@$data->gateway->name) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <p>@lang('Transaction')</p>
                            </div>
                            <div class="col text-end">
                                <p>{{ $data->trx }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <p>@lang('Initiated')</p>
                            </div>
                            <div class="col text-end">
                                <p> {{ showDateTime($data->created_at) }}<br>{{ diffForHumans($data->created_at) }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <p>@lang('Amount')</p>
                            </div>
                            <div class="col text-end">
                                <p> {{ showAmount($data->amount) }} + <span class="text--danger"
                                        data-bs-toggle="tooltip"
                                        title="@lang('Processing Charge')">{{ showAmount($data->charge) }} </span>
                                    <br>
                                    <strong data-bs-toggle="tooltip" title="@lang('Amount with charge')">
                                        {{ showAmount($data->amount + $data->charge) }}
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <p>@lang('Conversion')</p>
                            </div>
                            <div class="col text-end">
                                <p> {{ showAmount(1) }} = {{ showAmount($data->rate, currencyFormat: false) }}
                                    {{ __($data->method_currency) }}
                                    <br>
                                    <strong>{{ showAmount($data->final_amount, currencyFormat: false) }}
                                        {{ __($data->method_currency) }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <p>@lang('Status')</p>
                            </div>
                            <div class="col text-end">
                                <p> @php echo $data->statusBadge @endphp </strong>
                                </p>
                            </div>
                        </div>
                        @php
                            $details = [];
                            if ($data->method_code >= 1000 && $data->method_code <= 5000) {
                                foreach (@$data->detail ?? [] as $key => $info) {
                                    $details[] = $info;
                                    if ($info->type == 'file') {
                                        $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                    }
                                }
                            } 
                        @endphp
                        @foreach ($details as $_dt)
                            <div class="row">
                                <div class="col-5">
                                    <p>{{ $_dt->name}}</p>
                                </div>
                                <div class="col text-end">
                                    @if ($_dt->type == "file")
                                    <a href="{{ $_dt->value}}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a>
                                    @else
                                    <p>{{ $_dt->value}}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
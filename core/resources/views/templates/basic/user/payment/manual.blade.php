@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="pt-120 pb-120 deposit_manual">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body  ">
                            <form action="{{ route('user.deposit.manual.update') }}" method="POST" class="disableSubmission" enctype="multipart/form-data">
                                @if (isset($data['detail']->option_identifier))
                                    <input type="hidden" name="option_identifier" value="{{$data['detail']->option_identifier}}" />
                                @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- <div class="alert alert-primary"> 
                                            <p class="text-center mt-2"><i class="las la-info-circle"></i> @lang('Provide Transaction Details.')
                                            </p>
                                        </div> -->

                                        <div class="my-4 text-center">@php echo  $data->gateway->description @endphp</div>

                                    </div>

                                    <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />
                                </div>
                                <button type="submit" class="cmn-btn w-100">@lang('Confirm')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

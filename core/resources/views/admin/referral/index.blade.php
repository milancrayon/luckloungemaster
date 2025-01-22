@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-md-4">
        <div class="card border--primary parent">
            <div class="card-header bg--primary">
                <h5 class="text-white float-start">@lang('Deposit Referral Commission')</h5> 

                @if (gs('dc') == 0)
                    <a href="{{ route('admin.referral.status.update', 'dc') }}"
                        class="btn btn--success btn-sm float-end"><i class="las la-toggle-on"></i> @lang('Enable Now')</a>
                @else
                    <a href="{{ route('admin.referral.status.update', 'dc') }}"
                        class="btn btn--danger btn-sm float-end"><i class="las la-toggle-off"></i> @lang('Disable Now')</a>
                @endif

            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach ($referrals->where('commission_type', 'deposit') as $referral)
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span class="fw-bold">@lang('Level') {{ $referral->level }}</span>
                            <span class="fw-bold">{{ $referral->percent }}%</span>
                        </li>
                    @endforeach
                </ul>

                <div class="border-line-area">
                    <h6 class="border-line-title">@lang('Update Setting')</h6>
                </div>

                <div class="form-group mb-0">
                    <label>@lang('Number of Level')</label>
                    <div class="input-group">
                        <input type="number" name="level" min="1" placeholder="@lang('Type a number & hit ENTER ↵')"
                            class="form-control">
                        <button type="button" class="btn btn--primary generate">@lang('Generate')</button>
                    </div>
                    <span class="text--danger required-message d-none">@lang('Please enter a number')</span>
                </div>

                <form action="{{ route('admin.referral.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="commission_type" value="deposit">
                    <div class="d-none levelForm">
                        <div class="form-group">
                            <label class="text--success"> @lang('Level & Commission :')
                                <small>@lang('(Old Levels will Remove After Generate)')</small>
                            </label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="description referral-desc">
                                        <div class="row">
                                            <div class="col-md-12 planDescriptionContainer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border--primary parent">
            <h5 class="card-header bg--primary d-flex gap-2 justify-content-between align-items-center">
                <span class="text-white float-start">
                    @lang('Bet Place Commission')
                    <!-- @if (gs('bet_commission') == 0)
                        <span class="badge badge--danger">@lang('Disabled')</span>
                    @else
                        <span class="badge badge--success">@lang('Enabled')</span>
                    @endif -->
                </span>

                @if (gs('bet_commission') == 0)
                    <a href="{{ route('admin.referral.status.update', 'bet_commission') }}"
                        class="btn btn--success btn-sm float-end"><i class="las la-toggle-on"></i> @lang('Enable Now')</a>
                @else
                    <a href="{{ route('admin.referral.status.update', 'bet_commission') }}"
                        class="btn btn--danger btn-sm float-end"><i class="las la-toggle-off"></i> @lang('Disable Now')</a>
                @endif


            </h5>

            <div class="card-body parent">
                <ul class="list-group list-group-flush">
                    @foreach ($referrals->where('commission_type', 'bet') as $referral)
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span class="fw-bold">@lang('Level') {{ $referral->level }}</span>
                            <span class="fw-bold">{{ $referral->percent }}%</span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-line-area">
                    <h6 class="border-line-title">@lang('Update Setting')</h6>
                </div>

                <div class="d-flex flex-wrap gap-2  ">
                    <label>@lang('Number of Level')</label>
                    <div class="input-group">
                        <input type="number" name="level" min="1" placeholder="@lang('Type a number & hit ENTER ↵')"
                            class="form-control">
                        <button type="button" class="btn btn--primary generate">@lang('Generate')</button>
                    </div>
                    <span class="text--danger required-message d-none">@lang('Please enter a number')</span>
                </div>

                <form action="{{ route('admin.referral.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="commission_type" value="bet">
                    <div class="d-none levelForm">
                        <div class="form-group">
                            <label class="text--success"> @lang('Level & Commission :')
                                <small>@lang('(Old Levels will Remove After Generate)')</small>
                            </label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="description referral-desc">
                                        <div class="row">
                                            <div class="col-md-12 planDescriptionContainer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border--primary parent">
            <h5 class="card-header bg--primary d-flex gap-2 justify-content-between align-items-center">
                <span class="text-white float-start">
                    @lang('Bet Win Commission')
                </span>

                @if (gs('win_commission') == 0)
                    <a href="{{ route('admin.referral.status.update', 'win_commission') }}"
                        class="btn btn--success btn-sm float-end"><i class="las la-toggle-on"></i> @lang('Enable Now')</a>
                @else
                    <a href="{{ route('admin.referral.status.update', 'win_commission') }}"
                        class="btn btn--danger btn-sm float-end"><i class="las la-toggle-off"></i> @lang('Disable Now')</a>
                @endif
            </h5>

            <div class="card-body parent">
                <ul class="list-group list-group-flush">
                    @foreach ($referrals->where('commission_type', 'win') as $referral)
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span class="fw-bold">@lang('Level') {{ $referral->level }}</span>
                            <span class="fw-bold">{{ $referral->percent }}%</span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-line-area">
                    <h6 class="border-line-title">@lang('Update Setting')</h6>
                </div>
                <div class="d-flex flex-wrap gap-2 ">
                    <label>@lang('Number of Level')</label>
                    <div class="input-group">
                        <input type="number" name="level" min="1" placeholder="@lang('Type a number & hit ENTER ↵')"
                            class="form-control">
                        <button type="button" class="btn btn--primary generate">@lang('Generate')</button>
                    </div>
                    <span class="text--danger required-message d-none">@lang('Please enter a number')</span>
                </div>


                <form action="{{ route('admin.referral.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="commission_type" value="win">
                    <div class="d-none levelForm">
                        <div class="form-group">
                            <label class="text--success"> @lang('Level & Commission :')
                                <small>@lang('(Old Levels will Remove After Generate)')</small>
                            </label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="description referral-desc">
                                        <div class="row">
                                            <div class="col-md-12 planDescriptionContainer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
@push('style')
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }

        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            "use strict";

            var max = 1;
            $(document).ready(function () {
                $(".generate").on('click', function () {

                    var levelGenerate = $(this).parents('.card-body').find('[name="level"]').val();
                    var a = 0;
                    var val = 1;
                    var viewHtml = '';
                    if (levelGenerate !== '' && levelGenerate > 0) {
                        $(this).parents('.parent').find('.levelForm').removeClass('d-none');
                        $(this).parents('.parent').find('.levelForm').addClass('d-block');

                        for (a; a < parseInt(levelGenerate); a++) {
                            viewHtml += `<div class="input-group mt-4">
                                                            <span class="input-group-text form-control">@lang('Level')</span>
                                                            <input name="level[]" class="form-control" type="number" readonly value="${val++}" required placeholder="Level">
                                                            <input name="percent[]" class="form-control" type="number" step=".01" required placeholder="@lang('Percentage %')">
                                                            <button class="input-group-text bg--danger text-white border-0 delete_desc"><i class='fa fa-times'></i></button>
                                                        </div>`;
                        }
                        $(this).parents('.parent').find('.planDescriptionContainer').html(viewHtml);

                    } else {
                        alert('Level Field Is Required');
                    }
                });

                $(document).on('click', '.delete_desc', function () {
                    $(this).closest('.input-group').remove();
                });
            });
        });
    </script>
@endpush
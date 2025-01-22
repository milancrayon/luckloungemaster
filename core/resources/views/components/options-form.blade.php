@props(['form' => null])

<div class="form-field__wrapper">
    <div class="optionField simple_with_drop">
        @if ($form)
            @foreach ($form->option_data as $formData)
                <div class="form-field-wrapper" id="{{ $loop->index }}">
                    <input type="hidden" name="option_generator[instruction][]" class="form-control"
                        value="{{ @$formData->instruction }}">
                    <input type="hidden" name="option_generator[identifier][]" class="form-control"
                        value="{{ @$formData->identifier }}">
                    <input type="hidden" name="option_generator[image][]" class="form-control" value="{{ @$formData->image }}">
                    @php
                        $jsonData = json_encode([
                            'identifier' => @$formData->identifier,
                            'instruction' => @$formData->instruction,
                            'image' => @$formData->image,
                            'old_id' => '',
                        ]);

                    @endphp

                    <div class="form-field">
                        <div class="form-field__item d-flex align-items-center gap-2">
                            <div class="me-1">
                                <i class="las la-braille"></i>
                            </div>
                            <div>
                                <p class="title">@lang('identifier')</p>
                                <p class="value">{{ __(@$formData->identifier) }}</p>
                            </div>
                        </div> 
                        <div class="form-field__item">
                            <p class="title">@lang('instruction')</p>
                            <p class="value">{{ __(@$formData->instruction) }}</p>
                        </div>
                        <div class="form-field__item">
                            <div class="ipreview" style="background:url('{{ __(@$formData->image) }}') no-repeat;"> </div>
                        </div>
                        <div class="form-field__item">
                            <button type="button" class="btn btn--primary btn-sm editOptionData"
                                data-form_item="{{ $jsonData }}" data-update_id="{{ $loop->index }}"><i
                                    class="las la-pen me-0"></i></button>
                            <button type="button" class="btn btn--danger btn-sm removeOptionData"><i
                                    class="las la-times me-0"></i></button>
                        </div>
                    </div>

                </div>
            @endforeach
        @endif
    </div>
</div>

@push('style')
    <style>
        .form-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #cdcdcd;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            cursor: grab;
            background: #fff;
        }

        .form-field .title {
            font-size: 15px;
            font-weight: 600;
        }

        .form-field .form-field__item {
            min-width: 170px;
            text-align: left;
        }

        .addedField.simple_with_drop.ui-sortable {
            min-width: 900px;
        }

        .form-field .form-field__item:last-child {
            text-align: right;
        }

        .submitRequired {
            cursor: unset;
        }

        .form-field__wrapper {
            overflow-x: auto;
            margin-bottom: 10px;
        }
    </style>
@endpush
@push('script')
    <script>
        "use strict"
        var optionGenerator = new OptionGenerator();
        @if ($form)
            optionGenerator.totalField = {{ $form ? count((array) $form->option_data) : 0 }}
        @endif
        $(".simple_with_drop").sortable({
            stop: function (event, ui) {
                var start = ui.item.data('start');
                var end = ui.item.index();
                if (start !== end) {
                    $('.submitRequired').removeClass('d-none');
                }
            },
            start: function (event, ui) {
                ui.item.data('start', ui.item.index());
            }
        });
    </script>

    <script src="{{ asset('assets/global/js/option_actions.js') }}"></script>
@endpush
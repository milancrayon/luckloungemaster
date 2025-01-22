(function ($) {
    "use strict"

    $(document).on('click', '.addOption', function () {
        var html = optionGenerator.addOptions();
        $('.options').append(html);
    });

    $(document).on('click', '.removeOption', function () {
        $(this).closest('.form-group').remove();
    });

    $(document).on('click', '.editOptionData', function () {
        optionGenerator.formEdit($(this));
    });

    $(document).on('click', '.removeOptionData', function () {
        $(this).closest('.form-field-wrapper').remove();
        $('.submitRequired').removeClass('d-none');
    });

    $('.option-generate-btn').on('click', function () {
        $('.option-form').find('[name=image]').val("");
        $('.option-form').find('.ipreview').css("background", "none");
        if ($('.option-form').find('[name=identifier]').parent('.form-group').find('.identimessage').length > 0) {
            $('.option-form').find('[name=identifier]').parent('.form-group').find('.identimessage').remove();
        }
        optionGenerator.showModal();
    });


    $(document).on('change', '.op_image', async function (e) {
        if (e.target.files.length) {
            $('.optionSubmit').prop('disabled', true);
            var formData = new FormData();
            formData.append("token", $('.option-form input[name="_token"]').val());
            formData.append("image", e.target.files[0], e.target.files[0].name);
            await fetch("/upload", {
                headers: {
                    'X-CSRF-TOKEN': $('.option-form input[name="_token"]').val()
                },
                method: "POST",
                body: formData,
            }).then(response => response.json()).then(data => {
                if (data.data) {
                    $(this).parents('.option-form').find('input[name="image"]').val(data.data);
                    $(this).parents('.option-form').find('.ipreview').css("background", "url('" + data.data + "') no-repeat");
                    $('.optionSubmit').prop('disabled', false);
                }
            });
        }
    });

    var updateId = optionGenerator.totalField;
    $(optionGenerator.formClassName).on('submit', async function (e) {
        updateId += 1;
        e.preventDefault();
        var form = $(this); 
        var valididentifier = false;
        await $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('.option-form input[name="_token"]').val(),
            },
            url: '/admin/deposit/validateidentifier',
            data: { identifier: form.find('[name=identifier]').val(), code: $(document).find('[name=code]').val() },
            method: "POST",
            success: function (resData) {
                if (resData.valid) {
                    let _valls = $('.optionField').find('input[name="option_generator[identifier][]"]');
                    if ($(_valls).length > 0) {
                        let _isvalid = 1;
                        $(_valls).map((e, data) => {
                            if ($(data).val() == form.find('[name=identifier]').val()) {
                                if ($(data).parent(".form-field-wrapper").attr("id") == form.find('[name=update_id]').val()) { } else {
                                    _isvalid = 0;
                                    if ($(document).find('[name=identifier]').parent('.form-group').find('.identimessage').length > 0) {
                                    } else {
                                        $(document).find('[name=identifier]').parent('.form-group').append('<p class="identimessage text-danger" >Enter unique identifier!!</p>');
                                    }
                                }
                            }
                        });
                        if (_isvalid) {
                            valididentifier = 1;
                            if ($(form).find('[name=identifier]').parent('.form-group').find('.identimessage').length > 0) {
                                $(form).find('[name=identifier]').parent('.form-group').find('.identimessage').remove();
                            }
                        }
                    }else{
                        valididentifier = 1;
                    }
                } else {
                    if ($(document).find('[name=identifier]').parent('.form-group').find('.identimessage').length > 0) {
                    } else {
                        $(document).find('[name=identifier]').parent('.form-group').append('<p class="identimessage text-danger" >Enter unique identifier!!</p>');
                    }
                }
            },
        });
        if (valididentifier) {
            if (form.find('[name=image]').val()) {
                if ($('.op_image').parent('.form-group').find('.imagemessage').length > 0) {
                    $('.op_image').parent('.form-group').find('.imagemessage').remove();
                }
                if ($(document).find('[name=identifier]').parent('.form-group').find('.identimessage').length > 0) {
                    $(document).find('[name=identifier]').parent('.form-group').find('.identimessage').remove();
                }
                var formItem = optionGenerator.formsToJson(form);
                optionGenerator.makeFormHtml(formItem, updateId);
                optionGenerator.closeModal();
                $('.submitRequired').removeClass('d-none');
            } else {
                if ($('.op_image').parent('.form-group').find('.imagemessage').length > 0) {
                } else {
                    $('.op_image').parent('.form-group').append('<p class="imagemessage text-danger" >Image is Required!!</p>');
                }
            }
        }
    });

})(jQuery)


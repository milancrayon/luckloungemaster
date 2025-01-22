class OptionGenerator {
    constructor(formClassName = null) {
        this.fieldType = null;
        this.totalField = 0;
        if (this.formClassName) {
            this.formClassName = '.' + formClassName;
        } else {
            this.formClassName = '.option-form';
        }
    }

    formsToJson(form) {
        var formItem = {
            identifier: form.find('[name=identifier]').val(),
            instruction: form.find('[name=instruction]').val(),
            image: form.find('[name=image]').val(),
            old_id: form.find('[name=update_id]').val()
        };
        return formItem;
    }

    makeFormHtml(formItem, updateId) {
        if (formItem.old_id) {
            updateId = formItem.old_id;
        }
        var hiddenFields = ` 
            <input type="hidden" name="option_generator[identifier][]" value="${formItem.identifier}">
            <input type="hidden" name="option_generator[instruction][]" value="${formItem.instruction}">
            <input type="hidden" name="option_generator[image][]" value="${formItem.image}">
        `;
        var formsHtml = `
            ${hiddenFields}
            <div class="form-field">
                <div class="form-field__item d-flex align-items-center gap-2">
                    <div class="me-1">
                        <i class="las la-braille"></i>
                    </div>
                    <div>
                        <p class="title">Identifier</p>
                        <p class="value">${formItem.identifier}</p>
                    </div>
                </div>
                <div class="form-field__item">
                    <p class="title">Instruction</p>
                    <p class="value">${formItem.instruction}</p>
                </div>
                <div class="form-field__item">
                    <div class="ipreview" style="background:url('${formItem.image}') no-repeat;"> </div> 
                </div>
               
                <div class="form-field__item">
                    <button type="button" class="btn btn--primary btn-sm editOptionData" data-form_item='${JSON.stringify(formItem)}' data-update_id="${updateId}"><i class="las la-pen me-0"></i></button>
                    <button type="button" class="btn btn--danger btn-sm removeOptionData"><i class="las la-times me-0"></i></button>
                </div>
            </div>
        `;
        var html = `
            <div class="form-field-wrapper" id="${updateId}">
                ${formsHtml}
            </div>
        `;

        if (formItem.old_id) {
            html = formsHtml;
            $(`#${formItem.old_id}`).html(html);
        } else {
            $('.optionField').append(html);
        }
    }

    formEdit(element) {
        this.showModal()
        var formItem = element.data('form_item');
        var form = $(this.formClassName);
        form.find('[name=instruction]').val(formItem.instruction);
        form.find('[name=identifier]').val(formItem.identifier);
        form.find('[name=image]').val(formItem.image);
        $('.option-form').find('.ipreview').css("background", "url('" + formItem.image + "') no-repeat");
        form.find('[name=update_id]').val(element.data('update_id'));
        $('.optionSubmit').text('Update');

    }
    resetAll() {
        $(optionGenerator.formClassName).trigger("reset");
        $('.optionSubmit').text('Add');
        $('[name=update_id]').val('');

    }
    closeModal() {
        var modal = $('#optionGenerateModal');
        modal.modal('hide');
    }

    showModal() {
        this.resetAll();
        var modal = $('#optionGenerateModal');
        modal.modal('show');
    }

}
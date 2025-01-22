<div class="modal fade" id="optionGenerateModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">@lang('Add Option')</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i class="las la-times"></i>
        </button>
      </div>
      <form class="{{ $formClassName ?? 'option-form' }}">
        @csrf
        <div class="modal-body">
          <input type="hidden" name="update_id" value="">
          <input type="hidden" name="image" value="">
          <div class="form-group">
            <label>@lang('Identifier')</label>
            <input type="text" name="identifier" class="form-control" required>
          </div>
          <div class="form-group">
            <label>@lang('Instruction') <small>@lang('(if any)')</small></label>
            <input type="text" name="instruction" class="form-control">
          </div>
          <div class="row">
            <div class="col"> 
              <div class="form-group">
                <label>@lang('Image')</label>
                <input type="file" name="op_image" class="op_image form-control">
              </div>
            </div>
            <div class="col">
              <div class="ipreview">
                
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn--primary w-100 h-45 optionSubmit">@lang('Add')</button>
        </div>
      </form>
    </div>
  </div>
</div>


@push('script-lib')
  <script src="{{ asset('assets/global/js/option_generator.js') }}"></script>
@endpush
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="update-parc" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">{{ trans('loan.l_update_step') }}</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="parc_step">{{ trans('loan.l_step',['num'=>'']) }} <span style="color:red">*</span></label>
                        <select name="parc_step" id="parc_step" class="form-control">
                            <option value="1">{{ trans('loan.l_step', ['num'=>1]) }}</option>
                            <option value="2.1">{{ trans('loan.l_step', ['num'=>2.1]) }}</option>
                            <option value="2.2">{{ trans('loan.l_step', ['num'=>2.2]) }}</option>
                            <option value="2.3">{{ trans('loan.l_step', ['num'=>2.3]) }}</option>
                            <option value="3">{{ trans('loan.l_step', ['num'=>3]) }}</option>
                            <option value="4">{{ trans('loan.l_step', ['num'=>4]) }}</option></option>
                            <option value="5">{{ trans('loan.l_step', ['num'=>5]) }}</option></option>
                        </select>
                    </div>
                   <button type="button" class="btn btn-primary" id="save-parc-level"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                    <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
            </div>
        </div>
    </div>
</div>

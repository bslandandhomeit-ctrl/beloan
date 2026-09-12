<div aria-hidden="true" aria-labelledby="prod_prod_type" role="dialog" tabindex="-1" id="prod_prod_type" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">{{ trans('product.p_productsTypes') }}</h4>
            </div>
            <div class="modal-body">

                <form role="form" method="post"  id="form_prod_prod_type" class="cmxform">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="ipCate">{{ trans('product.p_productsTypes') }} <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="type" name="type" />
                    </div>
                    <div class="form-group">
                        <label for="ipDesc">{{ trans('multiple.m_description') }}</label>
                         <textarea class="form-control" id="Desc" name="Desc" rows="5" ></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                    <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
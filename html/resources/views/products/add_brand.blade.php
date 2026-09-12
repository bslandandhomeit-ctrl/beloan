<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mBrand" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">{{ trans('product.p_product_add_brand') }}</h4>
            </div>
            <div class="modal-body">

                <form role="form" method="post" id="frmBrand" class="cmxform">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="ipBrand">{{ trans('product.p_product_brand') }} <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="ipBrand" name="ipBrand" />
                    </div>
                    <div class="form-group">
                        <label for="ipDesc">{{ trans('multiple.m_description') }}</label>
                         <textarea class="form-control" id="ipDesc" name="ipDesc" rows="5" ></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                    <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
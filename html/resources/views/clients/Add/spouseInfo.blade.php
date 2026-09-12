
<?PHP foreach(isset($clientData->Spouse)?$clientData->Spouse:[0]as $spouse): //dd($spouse);?>

<div class="tab-pane" id="spouse">
    <div class="col-lg-6">
        <fieldset>
            <br/>
            <div class="form-group">
                <label class="control-label col-sm-5">{{trans('multiple.en')}} {{trans('customer.familyName')}}</label>
                <div class="col-md-6">
                    <input type="text" name="sp_family_name_en" value="{{$spouse->family_name_eng}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5">{{trans('multiple.en')}} {{trans('customer.firstName')}}</label>
                <div class="col-md-6">
                    <input type="text" name="sp_first_name_eng" value="{{$spouse->first_name_eng}}" class="form-control" placeholder=" ">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-5">{{ trans('customer.cus_birth_date') }}</label>
                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd"  class="input-append date dpYears col-md-6">
                    <input type="text" name="sp_birth_date"  value="{{UnEmptyDate($spouse->birthday)}}"  size="16" class="form-control"/>
                        <span class="add-on ">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-5"> {{ trans('customer.cus_nationality') }}</label>
                <div class="col-lg-6">
                    <select id="sp_nationality" name="sp_nationality" style="width: 100%">
                        <option value="">-</option>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5"> {{ trans('customer.sp_occupation') }}</label>
                <div class="col-lg-6">
                    <input type="text" name="sp_occupation" value="{{$spouse->occupation}}"  id="sp_occupation" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-5">{{ trans('customer.identification')}} {{trans('customer.type')}}</label>
                <div class="col-md-6">
                    <select class="" name="id_type" style="width: 100%">
                        <option value="">-</option>
                        @foreach($identification as $vals)
                            <option value="{{$vals->code}}"
                            <?PHP if(strtoupper($vals->code) ==  strtoupper($spouse->id_type)){
                                echo  'selected';
                            } ?>
                            >{{$vals->description}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-5">Id Number</label>
                <div class="col-md-6">
                    <input type="text" name="sp_id_number" value="{{$spouse->id_number}}"  class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5">{{ trans('customer.cus_issued_date') }}</label>
                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd"  class="input-append date dpYears col-md-6">
                    <input type="text" name="id_issued_date" value="{{UnEmptyDate($spouse->id_issued_date)}}"  size="16" class="form-control"/>
                        <span class="add-on ">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5">{{ trans('customer.cus_expired_date') }}</label>
                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd"  class="input-append date dpYears col-md-6">
                    <input type="text" name="exp_date" value="{{UnEmptyDate($spouse->exp_date)}}"  size="16" class="form-control"/>
                        <span class="add-on ">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                </div>
            </div>


        </fieldset>
    </div>

    <div class="col-lg-6">
        <fieldset>
            <br/>
            <div class="form-group">
                <label class="control-label col-sm-5">{{trans('multiple.kh')}} {{trans('customer.familyName')}}</label>
                <div class="col-md-6">
                    <input type="text" name="sp_family_name_kh" value="{{$spouse->family_name_kh}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5">{{trans('multiple.kh')}} {{trans('customer.firstName')}} </label>
                <div class="col-md-6">
                    <input type="text" name="sp_first_name_kh" value="{{$spouse->first_name_kh}}" class="form-control" placeholder="">
                </div>
            </div>

        </fieldset>
    </div>
</div>
<?PHP endforeach; ?>
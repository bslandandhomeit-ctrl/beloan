<div class="tab-pane" id="employer">
    <div id="employerList"></div>
     <?PHP
        $i = 0;
        $class = 'glyphicon-chevron-down';
        $delBtn = '';
         ?>
    @if(count($clientData->Employer)>0)
            <?PHP $Employer =  $clientData->Employer; ?>
        @else
            <?PHP $Employer =  [0]; ?>
    @endif
         @foreach($Employer as $employer)
    <?PHP if($i>0) {
        $delBtn = '<a class="btn btn-danger btn-xs glyphicon glyphicon-remove" style="margin-left: 5px !important;"> </a>';
    }
    $i++;
    //dd($employer);
    ?>

        <div class="panel-heading cloneEm">
            <div class="btnEm text-right">
                <a class="collap btn btn-info btn-xs glyphicon {{$class}}" style="margin-left: 5px !important;"> </a>
                <a class="btn btn-info btn-xs glyphicon glyphicon-plus addEm" style="margin-left: 5px !important;"> </a>
                <a class="secondary1 btn btn-secondary btn-xs"> 1 </a>
                <?PHP echo $delBtn;?>
            </div>
            <div class="panel-body {{$employer->id}}"  data-draft-id="">
                <div class="collapse employerForm">

                    <div class="col-lg-5">
                        <?PHP
                        $employer_type = config('static_data.employer_type')
                        ?>
                        <fieldset>
                            <br/>
                            <div class="form-group">
                                <label class="control-label col-sm-5">{{trans('employer.employer')}} {{trans('employer.type')}}</label>
                                <div class="col-md-6">
                                    <select name="employer_type[]" style="width: 100%">
                                        <option value="">-</option>
                                        @foreach($employer_type as $keys=>$vals)
                                            <option value="{{$keys}}"
                                            <?PHP if(strtoupper($keys) == strtoupper($employer->employer_type)){
                                                echo 'selected';
                                            } ?>
                                            > {{$vals}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{trans('employer.selfEmployed')}}</label>
                                <div class="col-md-6">
                                    <input type="checkbox" name="self_employed[]" value="Y"  class="form-control" style="height:31px; width: 31px;"
                                    <?PHP
                                            if(strtoupper($employer->self_employed) == "Y"){
                                                echo 'checked';
                                            }
                                            ?>>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{trans('multiple.en')}} {{ trans('employer.name') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" name="employer_name_en[]" value="{{$employer->employer_name}}" id="employer_name" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{trans('multiple.kh')}} {{ trans('employer.name') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" name="employer_name_kh[]" value="{{$employer->employer_name_kh}}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{ trans('employer.bizType') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" name="business_type[]" value="{{$employer->business_type}}"  class="form-control">
                                </div>
                            </div>
                        <!--Begin Economic -->
                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{ trans('employer.economic_sector') }}</label>
                                <div class="col-lg-6">
                                    <select class="" name="economic_sector[]" style="width: 100%">
                                        <option value="">-</option>
                                        @foreach($economic_sector as $vals)
                                            <option value="{{$vals->id}}"
                                                <?PHP if(strtoupper(trim($vals->id)) == strtoupper(trim($employer->economic_id))) {
                                                echo 'selected';
                                            }?>
                                            >
                                                {{$vals->english}} ( {{$vals->code}} )
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        <!--End  -->

                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{ trans('employer.date_of_employment') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" class="input-append date dpYears col-md-6">
                                    <input type="text" name="date_of_employment[]"  value="{{UnEmptyDate($employer->date_of_employment)}}"  size="16" class="form-control"/>
                        <span class="add-on">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('employer.length_of_service')}} </label>
                                <div class="col-md-6">
                                    <input type="text" name="length_of_service[]"  value="{{$employer->length_of_service}}"  class="form-control" placeholder="number of months">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('employer.contract_exp_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" class="input-append date dpYears col-md-6">
                                    <input type="text" name="contract_exp_date[]"  value="{{UnEmptyDate($employer->contract_exp_date)}}" size="16" class="form-control"/>
                        <span class="add-on">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{trans('currency.c_currency')}}</label>
                                <div class="col-md-6">
                                    <select name="currency[]" style="width: 100%">
                                        <option value="">--</option>
                                        @foreach($currency as $vals)
                                            <option value="{{$vals->id}}"
                                                <?PHP
                                                    if((int)$vals->id == (int)$employer->currency_id){
                                                        echo 'selected';
                                                    }
                                                ?>
                                            > {{$vals->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('employer.monthly_basic_salary') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="monthly_basic_salary[]" value="{{$employer->monthly_basic_salary}}"  size="16" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('employer.total_monthly_salary') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="total_monthly_salary[]"  value="{{$employer->total_monthly_salary}}" size="16" class="form-control"/>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-lg-5">
                        <fieldset>
                            <br/>
                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('multiple.country') }}</label>
                                <div class="col-md-6">
                                    <select id="em_country" name="em_country[]" style="width: 100%"  class="em_country">
                                        <option value="">-</option>
                                        <?PHP if(!empty($employer->country)): ?>
                                            <option value="{{$employer->country->id}}" selected >{{$employer->country->description[1]['name']}}</option>
                                        <?PHP endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('multiple.province') }}<span class="red"> *</span></label>
                                <div class="col-md-6">
                                    <select id="em_province" name="em_province[]" style="width: 100%" class="em_province">
                                        <?PHP if(!empty($employer->province)): ?>
                                            <option value="{{$employer->province->prov_gis}}" selected >{{$employer->province->eng_name}}</option>
                                        <?PHP endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('multiple.district') }}<span class="red"> *</span></label>
                                <div class="col-md-6">
                                    <select id="em_district" name="em_district[]"  style="width: 70%" class="em_district">

                                        <?PHP if(!empty($employer->District)): ?>
                                            <option value="{{$employer->District->distr_gis}}" selected >{{$employer->District->eng_name}}</option>
                                        <?PHP endif; ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('multiple.commune') }}<span class="red"> *</span></label>
                                <div class="col-md-6">
                                    <select id="em_commune" name="em_commune[]" style="width: 100%" class="em_commune">

                                        <?PHP if(!empty($employer->commune)): ?>
                                            <option value="{{$employer->commune->comm_gis}}" selected >{{$employer->commune->en_name}}</option>
                                        <?PHP endif; ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('multiple.village') }}<span class="red"> *</span></label>
                                <div class="col-md-6">
                                    <select id="em_village" name="em_village[]" style="width: 100%" class="em_village">

                                        <?PHP if(!empty($employer->Village)): ?>
                                            <option value="{{$employer->Village->vill_gis}}" selected >{{$employer->Village->en_name}}</option>
                                        <?PHP endif; ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{trans('multiple.en')}} {{ trans('multiple.m_address') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="employer_address[]" value="{{$employer->employer_address}}"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5">{{trans('multiple.kh')}} {{ trans('multiple.m_address') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="employer_address_kh[]" value="{{$employer->employer_address_kh}}"  style="width: 100%" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">{{ trans('multiple.postalCode') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="em_postal_code[]" value="{{$employer->postal_code}}"  style="width: 100%" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5"> {{trans('multiple.en')}} {{ trans('multiple.occupation') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="occupation[]" value="{{$employer->occupation}}"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5">{{trans('multiple.kh')}} {{ trans('multiple.occupation') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="occupation_kh[]" value="{{$employer->occupation_kh}}"  />
                                </div>
                            </div>

                        </fieldset>
                    </div>

                </div>
            </div>
    </div>
    @endforeach
</div>

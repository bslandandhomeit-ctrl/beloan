<div class="tab-pane" id="gIn">
    <br/>
    <div class="col-lg-11 table-responsive">
        <table class="table indent">
            <thead>
            <tr>
                <th>{{ trans('customer.identification')}} {{trans('customer.type')}}</th>
                <th>{{ trans('customer.identification')}} Number</th>
                <th> Issued date </th>
                <th> Issued By </th>
                <th> Expired date </th>
                <th> Add </th>
            </tr>
            </thead>
            <?PHP $i = 0;$class = 'glyphicon-plus'; ?>
                <?PHP $Ident = isset($clientData->Identification)?$clientData->Identification:[0]; 
                    if(sizeof($Ident) <= 0|| $Ident == false){
                        $Ident = [0];
                    }
                ?>
            <?PHP foreach($Ident as $id):

            if($i>0) {
                $class = 'glyphicon-minus';
            }
            $i++;
            ?>
            <tbody class="copyId">
            <tr class="{{$id->id}}">
                <td style="width: 20%">
                    <select class="" name="id_type_id[]" id="id_type" style="width: 100%; padding-top: 8px;">
                        <option value=""> - </option>
                        @foreach($identification as $vals)
                            <option value="{{$vals->id}}"
                            <?PHP if((int)$vals->id == (int)$id->id_type_id){
                                echo 'selected';
                            } ?>
                                > {{$vals->description}}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="text" name="id_number[]" autocomplete="off" value="{{$id->id_number}}" class="form-control" id="id_number"/>
                </td>

                <td>
                    <div data-date-viewmode="years" data-initialize="datepicker" class="input-append date dpYears col-md-6" style="width: 100%" >
                        <input type="text" name="issued_date[]" value="{{UnEmptyDate($id->issued_date)}}" size="16" class="form-control"  />
                            <span class="add-on">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                    </div>
                </td>

                <td>
                    <input  type="text" name="issued_by[]" value="{{$id->issued_by}}" class="form-control" />
                </td>

                <td>
                    <div data-date-viewmode="years" data-initialize="datepicker" class="input-append date dpYears col-md-6" style="width: 100%" >
                        <input type="text" name="id_expiry_date[]" value="{{UnEmptyDate($id->id_expiry_date)}}" size="16" class="form-control" />
                            <span class="add-on">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                    </div>
                </td>

                <td>
                    <a href="#" class="btn btn-info glyphicon {{$class}} " onclick="return false"></a>
                </td>
            </tr>
            </tbody>
            <?PHP endforeach; ?>
        </table>
    </div>
</div>
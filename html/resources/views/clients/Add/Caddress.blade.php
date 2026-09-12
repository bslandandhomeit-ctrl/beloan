<div class="tab-pane active" id="1">
    <br/>
    <div class="table-responsive">

        <table class="table" id="addressList">
            <thead>
            <tr>
                <th>{{ trans('multiple.country') }}</th>
                <th>{{ trans('multiple.province') }}</th>
                <th>{{ trans('multiple.district') }}</th>
                <th>{{ trans('multiple.commune') }}</th>
                <th>{{ trans('multiple.village') }}</th>
                <th>{{ trans('multiple.action') }}</th>
            </tr>

            </thead>
             <?PHP $class = 'glyphicon-plus'; ?>
                <?PHP $addresses = isset($clientData->Address)?$clientData->Address:[0]; //dd($addresses);?>
                <?php 
                    if(sizeof($addresses) <= 0 || $addresses == false){
                        $addresses = [0];
                    }
                ?>
                <?PHP foreach($addresses as $address):
                //dd($address->Commune->comm_gis);
                        if($i>0) {
                            $class = 'glyphicon-minus';
                        }
                        $i++;
                        ?>
                    <tbody class="mbody">
                    <tr class="{{$address->id}}">
                        <td style="width: 10%">
                            <select class="country" id="countryn" name="country[]" style="width: 100%">
                                <option value="">{{trans('multiple.select')}} {{trans('multiple.country')}}</option>
                                <?PHP if(!empty($address->country)):?>
                                <option value="{{$address->country->id}}" selected data-code="{{$address->country->iso_code_3}}">
                                    {{$address->country->description[1]['name']}}
                                </option>
                                <?PHP endif;?>
                            </select>
                        </td>
                        <td style="width: 22%">
                            <select class="provinces" id="provinces" name="provinces[]" style="width: 100%">

                                <?PHP if(!empty($address->province)):?>
                                <option value="{{$address->province->prov_gis}}" selected>
                                    {{$address->province->eng_name}}
                                </option>
                                <?PHP endif;?>

                            </select>
                        </td>
                        <td style="width: 25%">
                            <select class="district" id="district" name="district[]" style="width: 100%">
                                <?PHP if(!empty($address->District)):?>
                                <option value="{{$address->District->distr_gis}}" selected>
                                    {{$address->District->eng_name}}
                                </option>
                                <?PHP endif;?>
                            </select>
                        </td>
                        <td style="width: 23%">
                            <select class="commune" id="commune" name="commune[]" style="width: 100%">

                                <?PHP if(!empty($address->Commune)):?>
                                <option value="{{$address->Commune->comm_gis}}" selected>
                                    {{$address->Commune->en_name}}
                                </option>
                                <?PHP endif;?>

                            </select>

                        </td>
                        <td style="width: 25%">
                            <select class="villages" id="vill" name="villages[]" style="width: 100%">

                                <?PHP if(!empty($address->Village)):?>
                                <option value="{{$address->Village->vill_gis}}" selected>
                                    {{$address->Village->en_name}}
                                </option>
                                <?PHP endif;?>

                            </select>
                        </td>
                        <td><a href="#" class="btn btn-info glyphicon {{$class}}" onclick="return false"></a></td>
                    </tr>

                    <tr>
                        <td>
                            <label>House No</label>
                            <textarea class="form-control address_kh" name="address_en1[]">{{$address->address_en1}}</textarea>
                        </td>
                        <td>
                            <label>Street No</label>
                            <textarea class="form-control address_kh" name="address_kh1[]">{{$address->address_kh1}}</textarea>
                        </td>
                        <td rowspan="2">
                            <label> Address Type</label>
                            <select class="addr_types" id="addr_types" name="addr_types[]" style="width:100%;">
                                <option value=""> {{trans('multiple.select')}} {{trans('multiple.address_type')}}</option>
                                @foreach($address_type as $keys=>$vals)
                                    <option value="{{$keys}}" data-addtypes="{{$keys}}"
                                    <?PHP if(strtoupper($address->address_type) == strtoupper($keys)){
                                            echo 'selected';
                                        }
                                        ?>
                                    >{{$vals}}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- <td>
                            <label>Address 1 English </label>
                            <input class="form-control address_en" name="address_en1[]" placeholder="" value="{{$address->address_en1}}" />
                            <label>Address 2 English </label>
                            <input class="form-control address_en" name="address_en2[]" placeholder="" value="{{$address->address_en2}}" />
                        </td> -->
                    </tr>
            </tbody>

                <?PHP endforeach; ?>
        </table>
    </div>
</div>
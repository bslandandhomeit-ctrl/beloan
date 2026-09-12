<?PHP $ContactNumberType = config('static_data.ContactNumberType'); ?>
<div class="tab-pane" id="contact">
    <div class="col-lg-12">
        <br/>
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table tb_contact">
                    <thead>
                    <tr>
                        <th> Phone of</th>
                        <th> Number</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <?PHP $disabled = "disabled";
                    $i = 0;$class = 'glyphicon-plus'; ?>
                <?PHP $Contact = isset($clientData->Contact)?$clientData->Contact:[0];?>
                <?PHP foreach($Contact as $contact):

                        if($i>0) {
                            $class = 'glyphicon-minus';
                        }
                    $i++;
                    ?>
                    <tbody class="copy">
                    <tr class="{{$contact->id}}">
                        <td style="width: 20%">
                            <select class="contact_number_type" name="contact_number_type[]" style="width: 100%;margin-top: 8px;">
                                <option value=""> -- </option>
                                @foreach($ContactNumberType as $code=>$cont)
                                    @if(strtoupper($code) == strtoupper($contact->contact_number_type))
                                        <option value="{{$code}}" selected> {{$cont}}</option>
                                        @else
                                        <option value="{{$code}}"> {{$cont}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="phone_number[]" value="{{$contact->contact_number_number}}" class="form-control phoneNumber" ></td>
                        <td><i class="btn btn-group-xs btn-info glyphicon btnCopy {{$class}}"></i></td>
                    </tr>
                    </tbody>
                    <?PHP endforeach; ?>
                </table>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-1">{{ trans('multiple.m_email') }}</label>
                <div class="col-lg-6">
                    <input type="text" name="email_address" value="{{$contact->email_address}}" class="form-control" style="width: 80%;">
                </div>
            </div>

        </div>
    </div>
</div>
@if(count($loan_guarantor) > 0)
    @foreach ($loan_guarantor as $val)
        <div class="row">
            <div class="col-md-2">
                <div class="fileupload-new thumbnail" style="width: 150px;">
                    <img src="{{ $val->Clients->general->first()->photo?asset('/data/clients/'.$val->Clients->general->first()->photo, isset($secure) ? false : false):asset('images/noimage.gif', isset($secure) ? false : false) }}"
                         alt="Profile Picture"/>
                </div>
                @if(!empty($val->Clients->location_latitude) && !empty($val->Clients->location_longitude))
                    <a href="javascript:;" class="client-view view-map"
                       data-lat="{{ $val->Clients->location_latitude }}"
                       data-long="{{ $val->Clients->location_longitude }}"
                    >{{ trans('multiple.m_view_map') }}</a> &nbsp;
                @endif
                @if(!empty($val->Clients->general->first()->signature))
                    <a href="javascript:;" class="client-view guarantor-signature"
                       data-mfp-src="{{ asset('data/signatures/'.$val->Clients->general->first()->signature, isset($secure) ? false : false)}}"
                       id="guarantor-signature">View Client Front</a>
                @endif
                <br/>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table-condensed">
                            <tr>
                                <th>{{ trans('customer.cus_customer_name') }} :</th>
                                <td>{{ $val->Clients->client_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('user.u_user_kh_name') }} :</th>
                                <td>{{ !empty($val->Clients->general->first()->family_name_kh)?$val->Clients->general->first()->family_name_kh .' '.$val->Clients->general->first()->first_name_kh:'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('multiple.m_gender') }} :</th>
                                <td>{{ !empty($val->Clients->general->first()->gender)?$val->Clients->general->first()->gender:'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('customer.cus_nationality') }} :</th>
                                @if(!empty($val->Clients->general->first()->country))
                                    <td>{{ !empty($val->Clients->general->first()->country->description->first()->name)?$val->Clients->general->first()->country->description->first()->name:'N/A' }}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table-condensed">
                            <tr>
                                <th style="width: 30%;">{{ trans('customer.cus_birth_date') }}
                                    :
                                </th>
                                <td>{{ !empty($val->Clients->general->first()->date_of_birth)? date("d-M-Y", strtotime($val->Clients->general->first()->date_of_birth)):'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('multiple.m_phone',['num'=>'']) }} :</th>
                                <td>{{ $val->Clients->phone1}}{{ !empty($val->Clients->phone2)? ' / '. $val->Clients->phone2:''}}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('multiple.m_address') }} :</th>
                                <td>{{ !empty($val->Clients->address)? $val->Clients->address:'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table-condensed">
                            <tr>
                                <th>{{ trans('customer.cus_card_number') }} :</th>
                                <td>{{ !empty($val->Clients->Identification->first()->id_number)?$val->Clients->Identification->first()->id_number:'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('customer.cus_issued_date') }} :</th>
                                <td>{{ !empty($val->Clients->Identification->first()->issued_date) ? Date("d-M-Y", strtotime($val->Clients->Identification->first()->issued_date)):'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('customer.cus_issued_by') }} :</th>
                                <td>{{ !empty($val->Clients->Identification->first()->issued_by) ? $val->Clients->Identification->first()->issued_by:'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('customer.cus_card_expired_date') }} :</th>
                                <td>{{ !empty($val->Clients->Identification->first()->id_expiry_date) ?  date("d-M-Y", strtotime($val->Clients->Identification->first()->id_expiry_date)):'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
@else
    No Data
@endif
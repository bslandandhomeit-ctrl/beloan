@if(count($co_borrower) > 0)
    @foreach($co_borrower as $val)
        <?php
            $country = [];
            $general = $val->Clients->general->first();
            if($general->country->description){
                $country = $general->country->description->first();
            }
        ?>
        <div class="row">
            <div class="col-sm-12" style="float: right;">
                <a href="{{ route('delete_co_borrower',[$val->loan_id,$val->id]) }}" style="float: right;" onclick="return confirm('Are you sure you want to delete?');" class="btn btn-danger btn-xs" title="Remove"><i class="fa fa-times-circle"></i> <span class="title"></span></a>
            </div>
            <div class="col-md-2">
                <div class="fileupload-new thumbnail" style="width: 150px;">
                    <img src="{{ $general->photo?asset('/data/clients/'.$general->photo, isset($secure) ? false : false):asset('images/noimage.gif', isset($secure) ? false : false) }}" alt="Profile Picture"/>
                </div>
                @if(!empty($val->Clients->location_latitude) && !empty($val->Clients->location_longitude))
                    <a href="javascript:;" class="client-view view-map" data-lat="{{ $val->Clients->location_latitude }}" data-long="{{ $val->Clients->location_longitude }}"
                    >{{ trans('multiple.m_view_map') }}</a> &nbsp;
                @endif
                @if(!empty($general->signature))
                    <a href="javascript:;" class="client-view guarantor-signature" data-mfp-src="{{ asset('data/signatures/'.$general->signature, isset($secure) ? false : false)}}" id="client-signature">View Client Front</a>
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
                                <td>{{ !empty($general->family_name_kh)?$general->family_name_kh .' '.$general->first_name_kh:'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('multiple.m_gender') }} :</th>
                                <td>{{ !empty($general->gender)?$general->gender:'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('customer.cus_nationality') }} :</th>
                                <td>{{ !empty($country->name)?$country->name:'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table-condensed">
                            <tr>
                                <th style="width: 30%;">{{ trans('customer.cus_birth_date') }}
                                    :
                                </th>
                                <td>{{ !empty($general->date_of_birth)? date("d-M-Y", strtotime($general->date_of_birth)):'N/A' }}</td>
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
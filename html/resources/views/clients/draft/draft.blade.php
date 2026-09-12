<section class = "panel">
    <header class = "panel-heading">
        <span>{{ trans('sidebar.sb_draft') }}</span>
    </header>

    <div class = "panel-body">

        <section id="unseen" class="ox-scroll">
            <div class="col-lg-12">
                <table class = "table table-bordered table-striped table-condensed table-hover clientTable">
                    <thead>
                    <th style = "text-align: center;">{{ trans('customer.cus_customer_id') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_photo') }}</th>
                    <th style = "text-align: center;">{{ trans('customer.cus_customer_name') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_gender') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.id_number') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_status') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.action') }}</th>
                    </thead>
                    <tbody>
                    @forelse($clientDraft as $client)
                        @foreach($client->general as $generals)
                            <tr>
                                <td align = "center">{{$client->id}}</td>
                                <td align = "center">
                                    <?PHP $file = asset('/data/clients/'.$generals->photo, isset($secure)?false:false) ?>
                                    <img class="img-responsive" style="max-width: 100px;" src="{{($generals->photo)?$file:asset('theme/images/404.png')}}" alt="" />

                                </td>
                                <td>{{$generals->family_name}} {{ $generals->first_name }}</td>
                                <?PHP $gender = config('static_data.gender')?>
                                <td>
                                    {{$gender[$generals->gender]}}
                                </td>
                                <td>
                                    <?PHP $text = ''; ?>
                                    @foreach($client->Identification as $key=>$iden)
                                            <?PHP if($key>0){echo $text= ' , ';} ?> {{$iden->id_number}}
                                        @endforeach
                                </td>
                                <td>
                                    <?PHP $text = ''; ?>
                                    @foreach($client->Contact as $key=>$contact)
                                        <?PHP if($key>0){echo $text= ' , ';} ?> {{$contact->contact_number_number}}
                                    @endforeach
                                </td>
                                <td align = "center">
                                    @if($client->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td align = "center" class = "define-width">
                                    <a href = "{{route('add_client',[$client->id])}}"  class="btn btn-xs btn-default" title = "Edit" style="margin-right: 10px;"><i class = "fa fa-pencil"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan = 9>{{ trans('multiple.m_no_result') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>

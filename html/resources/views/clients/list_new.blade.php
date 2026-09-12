@extends('layouts.app')

@section('css')
    <link href = "{{ asset('css/client.css', isset($secure)?false:false) }}" rel = "stylesheet">
    <link href = "{{ asset('css/loan-style.css', isset($secure)?false:false) }}" rel = "stylesheet">
    <style>
      @media print {
           .hide-this{
             display: none !important;
           }
      }
    </style>
@endsection
@section('content')
    <section class = "panel">
        <header class = "panel-heading">
            <span>{{trans('sidebar.sb_client_summary')}}</span>
            <span style="float: right"><a href = "{{route('add_client',[''])}}" class = "btn btn-success"><i class = "fa fa-plus"></i> {{ trans('sidebar.sb_add_client') }}
                </a></span>
        </header>

        <div class = "panel-body">
            <div class = "position-center" style = "width:90%;">
                <form role = "form" class = "cmxform form-horizontal" method = "get" action = "{{ route('list_client') }}" id = "search_frm">
                    <div class = "row">
                        <div class = "col-lg-6">
                            <div class = "form-group">
                                <label for = "Name" class = "col-lg-3 control-label">{{ trans('customer.cus_customer_name') }}</label>
                                <div class = "col-lg-7">
                                    <input type = "text" class = "form-control" id = "client_name" value = "{{$fields['name']}}" name = "name">
                                </div>
                            </div>
                            <div class = "form-group">
                                <!--<label for = "Phone" class = "col-lg-3 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                                <div class = "col-lg-7">
                                    <input type = "text" class = "form-control" value = "{{$fields['phone']}}" id = "phone1" name = "phone" data-mask = "999-999-999?9">
                                </div> -->
                                <label for = "Phone" class = "col-lg-3 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                                <div class = "col-lg-7">
                                    <input type = "text" class = "form-control" value = "{{$fields['phone']}}" id = "phone1" name = "phone">
                                </div>
                            </div>
                        </div>
                        <div class = "col-lg-6">
                            <div class = "form-group">
                                <label for = "inputCardnumber" class = "col-lg-3 control-label">{{ trans('customer.cus_card_number') }}</label>
                                <div class = "col-lg-7">
                                    <input type = "text" class = "form-control" value = "{{$fields['identify_id']}}" id = "card_number" name = "identify_id">
                                </div>
                            </div>
                            <div class = "form-group">
                                <label for = "inputCardnumber" class = "col-lg-3 control-label">{{ trans('customer.cus_customer_id') }}</label>
                                <div class = "col-lg-7">
                                    <input type = "text" class = "form-control" value = "{{$fields['customer_id']}}" id = "client_code" name = "customer_id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" value="{{$fields['status']}}" name = "status">
                    <div class = "row">
                        <div class = "col-lg-12 text-right">
                            <button type = "submit" class = "btn btn-info"><i class = "fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-lg-offset-10 col-lg-2">
                        <span class = "pagi_label">Number of Rows:</span>
                         <select name="sel_offset" id="sel_offset" class="form-control">
                             @if(!empty($fields['sel_offset']))
                                 <option value="{{$fields['sel_offset']}}" selected>{{$fields['sel_offset']}}</option>
                             @endif
                             <option value="">--</option>
                             <option value="2">2</option>
                             <option value="5">5</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="25">25</option>
                             <option value="30">30</option>
                             <option value="35">35</option>
                             <option value="50">50</option>
                             <option value="100">100</option>
                         </select>
                       </div>
                    </div>
                </form>
            </div>
            <section id = "unseen" class = "ox-scroll">
                <div id="printArea">
                @include('api.report_header')
                <table class="table table-bordered table-striped table-condensed table-hover clientTable" id="customers_list">
                    <thead>
                    <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                    <th style = "text-align: center;">{{ trans('customer.cus_customer_id') }}</th>
                    <th style = "text-align: center;" class="hide-this">{{ trans('multiple.m_photo') }}</th>
                    <th style = "text-align: center;">{{ trans('customer.cus_customer_name') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_gender') }}</th>
                    <th style = "text-align: center;">{{ trans('customer.identification') }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                    <th style = "text-align: center;">{{ trans('multiple.m_status') }}</th>
                    <th style = "text-align: center;" class="hide-this">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                    @forelse($client_list as $list)
                        @foreach(isset($list->general)?$list->general:[0] as $general)
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $list->id}}</td>
                                <td align="center">{{ $list->cus_acc }}</td>
                                <?php 
                                    $url = '';
                                    if($general->photo){
                                        if(file_exists('data/clients/'.$general->photo)){
                                            $url = asset('data/clients/'.$general->photo);
                                        }else{
                                            $url = asset('images/noimage.gif');
                                        }
                                    }else{
                                        $url = asset('images/noimage.gif');
                                    }

                                ?>
                                <td align = "center" class="hide-this">
                                    <img class = "listPhoto" src = "{{ $url }} ">
                                </td>

                                <td><a href="{{ route('client_detail', [$list->id]) }}">{{ $general->family_name}} {{ $general->first_name }}</a></td>

                                <td align = "center">{{ $general->gender ? $general->gender : '-'}}</td>
                                <td align = "center">
                                    @foreach(isset($list->Identification)?$list->Identification:[0] as $Iden)
                                        {{ $Iden->id_number }},
                                    @endforeach
                                </td>
                                <td align = "center">@foreach(isset($list->Contact)?$list->Contact:[0] as $Contact) {{ $Contact->contact_number_number}}, @endforeach </td>
                                <td align = "center">
                                    @if($list->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td align = "center" class = "define-width hide-this">
                                    <a href = "{{ route('client_detail', [$list->id]) }}" class = "btn btn-xs btn-default" title = "Detail"><i class = "fa fa-search-minus"></i></a>

                                    @if($list->status == 1)
                                        {{-- <a href="#" class="btn btn-xs btn-warning"  disabled title="Edit"><i class = "fa fa-pencil"></i></a> --}}
                                        <a href = "{{route('add_client', [$list->id])}}" class = "btn btn-xs btn-default" title = "Edit"><i class = "fa fa-pencil"></i></a>
                                        <a href = "{{ route('loan_add', [$list->id,0]) }}" class = "btn btn-xs btn-default" title = "Add Loan"><i class = "fa fa-plus"></i></a>
                                    @else
                                        <a href = "{{route('add_client', [$list->id])}}" class = "btn btn-xs btn-default" title = "Edit"><i class = "fa fa-pencil"></i></a>
                                        <a style = "background-color: #ddd" href = "{{ route('loan_add', [$list->id,0]) }}" class = "btn btn-xs btn-default" disabled><i class = "fa fa-plus"></i></a>
                                    @endif
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
                    {!! str_replace('?page', '&page', $client_list->appends(\Request::except('page'))->render()) !!}
                </div>
            </section>
        </div>
    </section>
@endsection

@section('js')
    <script type = "text/javascript" src = "{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure)?false:false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js', isset($secure)?false:false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#sel_offset').on('change', function () {

                $('input[name="sel_offset"]').val();
                $('#search_frm').submit();
            });

        });
        $("#export").click(function (event) {
            event.preventDefault();
          var con = confirm("Do you really want to export to CSV file?");
          if(con == true){
              new TableExport(document.getElementById('customers_list'), {
                  formats: ['csv'],
                  filename:'customers_list'
              });
              $('button.csv').hide().click();
              $('.tableexport-caption').remove();
          }
        });

        $("#xexport").click(function (event) {
            event.preventDefault();
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('customers_list'), {
                        formats: ['xlsx'],
                        filename: 'customers_list'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });

    </script>
@endsection

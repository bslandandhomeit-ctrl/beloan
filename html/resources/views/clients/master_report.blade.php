@extends('layouts.app')

@section('css')
    <link href = "{{ asset('css/client.css', isset($secure)?false:false) }}" rel = "stylesheet">
    <link href = "{{ asset('css/loan-style.css', isset($secure)?false:false) }}" rel = "stylesheet">
@endsection
@section('content')
    <section class = "panel">
        <header class = "panel-heading">
            <span>{{trans('sidebar.sb_master_report')}}</span>
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
                                <label for = "Phone" class = "col-lg-3 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                                <div class = "col-lg-7">
                                    <input type = "text" class = "form-control" value = "{{$fields['phone']}}" id = "phone1" name = "phone" data-mask = "999-999-999?9">
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
                </form>
            </div>
            <section id = "unseen" class = "ox-scroll">
                <div id="printArea">
                @include('api.report_header')
                <table class = "ddd table table-bordered table-striped table-condensed table-hover clientTable" id="customers_list">
                    <thead>
                        <th>Branch</th>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Name (ENG)</th>
                        <th>Name (KHM)</th>
                        <th>Gender</th>
                        <th>Account #</th>
                        <th>Currency</th>
                        <th>Disburse</th>
                        <th>Loan Balance</th>
                        <th>Out. Amount (OS)</th>
                        <th>Day Due</th>
                        <th>Outstanding Amt</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                   
                    </thead>
                    <tbody>
                        @forelse($clients as $c)
                            <tr>
                                <td>x</td>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->client_name }}</td>
                                <td>{{ $c->clientCBCEmployer->employer_name_kh }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                        @empty
                            <td colspan="20"></td>
                        @endforelse
                    </tbody>
                </table>
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
        });
    </script>
@endsection

@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',false) }}"/>
    <style>
        table tr td:not(:first-child) {
            text-align: right;
        }
        @media print{
          .hide-this{
            display: none;
          }
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_account_his') }}
        </header>
        <div class="panel-body">
            <div class="position-left">
                <div class="row">
                    <div class="col-lg-12">

                        <form role="form" class="cmxform form-inline" method="get" action="{{ route('account_his') }}" style="min-width: 500px;">

                            <!-- <div class="form-group" style="min-width: 500px;">
                                <label class="col-lg-4 control-label"> {{ trans('account.ref_id')}}<span class="red-color">*</span></label>
                                <select id="results" class="col-lg-5 form-control reference">
                                </select>
                            </div> -->
                        </form>

                        </div>
                        <div class="col-lg-6">

                            <div class="form-group">
                              <label class="col-lg-3 control-label" style="margin-top:15px;">{{ trans('multiple.branch')}}</label>
                              <div class="col-lg-7">
                                <select class="form-control " id="branch_id" name="branch_id">
                                    <option value="0">All</option>
                                    @foreach($branch as $b)
                                    <option value="{{ $b->branch_code }}"
                                            @if(isset($branch_code))
                                            @if($branch_code==$b->branch_code)
                                            selected
                                            @endif
                                            @endif>{{ $b->short_name}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>

                          <div class="form-group">
                              <label for="Name" class="col-lg-3 control-label" style="margin-top:15px;">{{ trans('multiple.m_start_date') }}</label>
                              <div class="col-lg-7">
                                  <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{$data['start']}}" class="input-append date dpStart">
                                      <input type="text" id ="dpStart"  name="dpStart" size="16" class="form-control" value = "{{ $data['start'] }}">
                                      <span class="add-on date">
                                          <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="inputCardnumber" class="col-lg-3 control-label" style="margin-top:15px;">{{ trans('multiple.m_end_date') }}</label>
                              <div class="col-lg-7">
                                  <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{$data['end']}}" class="input-append date dpEnd">
                                      <input type="text" id ="dpEnd" name="dpEnd" size="16" class="form-control" value="{{ $data['end'] }}">
                                      <span class="add-on date">
                                          <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-lg-3 control-label">{{ trans('account.account_or_code')}}<span class="red-color">*</span></label>
                              <div class="col-lg-7">
                                <select name="selType" id="selCoaCat" name="parent_id" class="select2_type account_select1" style="width: 550px;" >
                                    <option value="">-</option>
                                    @if(isset($search))
                                        @foreach($search as $s)
                                            <option value="{{ $s->id}}">  {{   $s->name }}
                                                ( {{$s->account_code}})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-12" id="printArea">
                            <div class="col-lg-12 text-right">
                                <div class="form-group">
                                    <a class="btn btn-info hide-this" id="search"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</a>
                                    <a class="btn btn-warning hide-this" id="printer"><i class="fa fa-print"></i> {{ trans('account.print') }}</a>
                                    <a id="export" class="btn btn-primary hide-this"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                    <a id="xexport" class="btn btn-primary hide-this"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-condensed income_statement_is acHis"
                                   style="width:100%;">
                                <thead>
                                <tr>
                                    <th>{{ trans('account.no') }}</th>
                                    <th> {{ trans('account.a_number') }}</th>
                                    <th> {{ trans('account.a_name') }}</th>
                                    <th> {{ trans('account.a_journal_id') }}</th>
                                    <th>{{ trans('account.a_entry_id') }}</th>
                                    <th>{{ trans('account.a_tra_id') }}</th>
                                    <th> {{ trans('product.p_loan_reference') }}</th>
                                    <th>{{ trans('multiple.date') }}</th>
                                    <th>{{ trans('account.a_debit') }}</th>
                                    <th>{{ trans('account.a_credit') }}</th>
                                    <th>{{ trans('account.a_bal') }}</th>
                                    <th>{{ trans('account.a_desc') }}</th>
                                    <th>{{ trans('multiple.audit_title') }}</th>
                                    <th class="hide-this">{{ trans('account.a_print') }}</th>
                                </tr>
                                </thead>
                                <tbody id="result"></tbody>
                            </table>

                            <div id="loading" style="display: none"></div>
                            <div class="prepare">
                                <span style="text-align: left">Prepared by : </span>
                                <span style="margin-left: 150px">Verified by : </span>
                                <span style="margin-left: 150px">Approved by : </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </section>
@endsection


@section('js')
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',false)}}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

    <script type="text/javascript">
    $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementsByTagName('table'), {
                formats: ['csv'],
                filename:"account_history"
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
        event.preventDefault();
    });

    $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementsByTagName('table'), {
                        formats: ['xlsx'],
                        filename: 'account_history'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
    
    function export_row(el){
      let body = document.createElement('tbody');

      let record = el.parentNode.parentElement.cloneNode(true);
      body.appendChild(record);

      let tb = document.getElementsByTagName('table')[0];
      let tb_clone = tb.cloneNode(true);

      tb_clone.removeChild(tb_clone.childNodes[3]);
      tb_clone.insertBefore(body, tb_clone.childNodes[2].nextSibling);

      var csv = [];
      var rows = tb_clone.querySelectorAll("tr");

      for (var i = 0; i < rows.length; i++) {
          var row = [], cols = rows[i].querySelectorAll("td, th");

          for (var j = 0; j < cols.length; j++)
              row.push(cols[j].innerText);
          csv.push(row.join(","));
      }

      // Download CSV file
      downloadCSV(csv.join("\n"), 'account_history_record.csv');

    }

    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {type: "text/csv"});

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }

    $('.dpStart').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $('.dpEnd').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $(document).ready(function () {

            // Function to shuffle the demo data
          /*  var shuffle = function (str) {
                return str.split('').sort(function () {
                    return 0.5 - Math.random();
                }).join('');
            };

            // For demonstration purposes we first make
            // a huge array of demo data (20 000 items)
            // HEADS UP; for the _.map function i use underscore (actually lo-dash) here
            var mockData = function () {
                var array = _.map(_.range(1, 20000), function (i) {
                    return {
                        id  : i,
                        text: shuffle('te ststr ing to shuffle') + ' ' + i
                    };
                });
                return array;
            };

            $(function () {
                // create demo data
                var dummyData = mockData();
                // set initial value(s)
                $('#e24').val([
                    dummyData[75].text, dummyData[1897].text
                ]);

                // init select 2
                $('#e24').select2({
                    data             : dummyData,
                    // init selected from elements value
                    initSelection    : function (element, callback) {
                        var initialData = [];
                        $(element.val().split(",")).each(function () {
                            initialData.push({
                                id  : this,
                                text: this
                            });
                        });
                        callback(initialData);
                    },
                    // configure as multiple select
                    multiple         : true,

                    // NOT NEEDED: text for loading more results
                    formatLoadMore   : 'Loading more...',

                    // query with pagination
                    query            : function (q) {
                        var pageSize,
                            results;
                        pageSize = 20; // or whatever pagesize
                        results  = [];
                        if (q.term && q.term !== "") {
                            // HEADS UP; for the _.filter function i use underscore (actually lo-dash) here
                            results = _.filter(this.data, function (e) {
                                return (e.text.toUpperCase().indexOf(q.term.toUpperCase()) >= 0);
                            });
                        } else if (q.term === "") {
                            results = this.data;
                        }
                        q.callback({
                            results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
                            more   : results.length >= q.page * pageSize
                        });
                    }
                });
            });*/

            //$(document.body).on("change", "#selCoaCat", function () {
              //  $("#result").html("");
              //  getAccount();
              //Result_list($(this).val());
            //});
            $("#search").on("click", function(e){
              Result_list();
              e.preventDefault();
            });
            /*$(document.body).on("change", "#results", function () {

                var id = $("#results option:selected").val();
                Result_list(id);
            });*/

            //$(".account_select1").select2();
            $("#selCoaCat").select2();
        });
      /*  function getAccount() {
            var data = <?PHP //echo $search ?>;
            var coaId = $("#selCoaCat option:selected").val();
            var branch_id = $("#branch_id").val();
            $.each(data, function (i, data) {

                if (data.id == coaId && data.id !== 'undefinded') {
                    getReference(coaId, branch_id);
                }
            });
        }
        function getReference(referId, b_id) {
            $("#reference").html("");
            if (!referId) {
                return false;
            } else {
                $.ajax({
                    url: "/accounting/getreference/" + referId + '/' + b_id,
                    method: 'get',
                    data: {},
                    beforeSend: function () {
                        imgLoading(true)
                    },
                    success: function (data, status) {
                        if (status == 'success') {
                            dropDown(data, ".reference");
                        } else {
                            imgLoading(true);
                        }
                    }
                }).done(function () {
                    imgLoading(false);
                });
            }
        }

        function dropDown(data, selectorId) {
            var referenceArray = [];
            $('.reference').children().remove();
            var selector ='';
            if ($.isEmptyObject(data)) {
                selector += '<option value="0">No reference</option>';
            }else{

                selector += '<option value="0">Choose a reference</option>';
                $.each(data, function (i, val) {
                    console.log(val.reference)

                    if (referenceArray.indexOf(val.reference) == -1 && !$.isEmptyObject(val.reference) && typeof val.reference != 0) {
                        selector += '<option value=' + val.coa_id + '>' + val.reference + '</option>';
                        referenceArray.push(val.reference);
                    }
                });
            }
            $(selector).appendTo(selectorId);
        }*/

        function Result_list() {
            var fk_coa_id = $("#selCoaCat").val();
            var start_date = $('#dpStart').val();
            var end_date = $('#dpEnd').val();
            var branch = $('#branch_id').val();
            var table = '';
            var no = 1;
            $("#result").html("");
            if (!fk_coa_id) {
                return false;
            } else {
                $.ajax({
                    url: "/accounting/getjd/" + fk_coa_id,
                    method: 'get',
                    data: {start_date : start_date, end_date : end_date, branch : branch},
                    beforeSend: function () {
                        imgLoading(true)
                    },
                    success: function (data, status) {
                        if (status == 'success') {
                          var sum_debit = 0;
                          var sum_credit = 0;
                          var factor = parseFloat(data['factor']);
                          var bal = factor * parseFloat(data['p_bal']);
                        //   console.log(factor);console.log(bal);
                        //   console.log(data['result']);
                            $.each(data['result'], function (i, val) {
                                if (val.tran_re_id == null) {
                                    var tranId = '<td></td>';
                                } else {
                                    tranId = '<td><a target="_blank" href=' + "../loans/journal/" + val.tran_re_id + '><span style="text-decoration: underline;">' + val.tran_re_id + '</span></a></td>';
                                }
                                bal += factor * (val.debit - val.credit);
                                var desc = val.descr;
                                if(desc == ''){
                                  if (val.description != null){
                                    desc = val.description;
                                  }
                                }
                                if(desc == '0') desc = '';
                                var ref = val.reference;
                                if (val.reference == null){
                                  ref = '';
                                }
                                // if(desc == ''){
                                //     $.each(data['journal_re'], function(j,v){
                                //         if(v.id == val.jd_jr_id && v.detail.id != val.jd_id){
                                //             console.log(v);
                                //         }
                                //     });
                                // }

                                table +=
                                        '<tr>' +
                                        '<td>' + no + '</td>' +
                                        '<td>' + val.account.account_code + '</td>' +
                                        '<td>' + val.account.name + '</td>' +
                                        '<td>' + val.jd_id + '</td>' +
                                        '<td>' + val.jd_jr_id + '</td>' + tranId +
                                        '<td>' + ref +
                                        '<td>' + val.entry_date + '</td>' +
                                        '<td>' + accounting.formatNumber(val.debit,4) + '</td>' +
                                        '<td>' + accounting.formatNumber(val.credit,4) + '</td>' +
                                        '<td>' + accounting.formatNumber(bal,4) + '</td>' +
                                        '<td>' + desc + '</td>' +
                                        '<td>' + val.is_audit + '</td>' +
                                        '<td class="hide-this"><span class="glyphicon glyphicon-print" onclick="export_row(this)"></span></td>' +
                                        '</tr>';
                                no++;
                                sum_debit += parseFloat(val.debit);
                                sum_credit += parseFloat(val.credit);
                            });
                            table +=
                            '<tr>' +
                            '<td colspan = 6>' + "Total" + '</td>' +
                            '<td style="text-align: right; font-weight: bold;">' + accounting.formatNumber(sum_debit,4) + '</td>' +
                            '<td style="text-align: right; font-weight: bold;">' + accounting.formatNumber(sum_credit,4) + '</td>' +
                            '<td style="text-align: right; font-weight: bold;">' + accounting.formatNumber(bal,4) + '</td>' +
                            '</tr>';
                            $(table).appendTo("#result");
                        } else {
                            imgLoading(true);
                        }
                    }
                }).done(function () {
                    imgLoading(false);
                });

            }
        }
        function imgLoading(load) {
            if (!load == true) {
                $("#loading").slideUp(1900, function () {
                    $("#loading").html("");
                    $("#loading").css({"display": "none"});
                });
            } else {
                $("#loading").slideUp(0, function () {
                    $("#loading").css({"display": "inline"});
                    $('<div><img src="/images/loading.gif" /> </div>').appendTo("#loading");
                });
            }
        }
    </script>
@endsection

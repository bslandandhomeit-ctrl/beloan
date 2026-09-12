@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')

    <section class="panel">
        <header class="panel-heading">
            <span>{{ trans('teller.t_withdraw') }}</span>
            <span style="float: right"><a href="#" id="printer" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('print') }}</a></span>
        </header>
        <?php

        $currency_char = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        ?>

        <style media="print">

        </style>
        <style>
          table.mtable {
              width: 681px;
              max-width: 681px;
              min-width: 681px;
          }
          table tbody tr td,p{
              font-size: 9px!important;
          }
          tr.tdheight{
              height: 25px !important;
          }
          @media screen
          {
              p.bodyText {font-family:verdana, arial, sans-serif;}
          }

          @media print
          {
              table,tr,td,*{
                  border:none;
              }
              /*#printArea,#panel-body{*/
                  /*padding-left: 0px !important;*/
                  /*margin-left: -15px;!important;*/

              /*}*/
              tr.tdheight{
                  height: 25px !important;
              }
              .check {
                  width: 70px;
                  border: none;
                  right: 0px;
                  float: right;
                  margin-top:-3px!important;
              }
              .check-top-15px{
                  margin-top:-18px !important;
                  top:-18px !important
              }

              #amount_char{
                  margin-top: 0px !important;
              }
          }
        </style>
        <div id="printWithdraw">
            <div class="panel-body" id="printArea" >
                <div class="docmargin">
                    <form id="my_form">
                        {{-- <table border="1" cellpadding="0" cellspacing="0" style="width:775px;max-width:775px;min-width:775px; font-size: 11px;" > --}}
                        <table border="1" cellpadding="0" cellspacing="0" class="mtable">
                            <tbody>
                            <tr>
                                <td colspan="15" rowspan="3" valign="top"  valign="top">
                                    <div class="hidden-print"><p>គ្រឹះស្ងានមីក្រូហិរញ្ញវត្ថ ធី&amp;ហ្គូ ហ្វាយនែន ភីអិលស៊ី</p>
                                        <p>T &amp; Go finanace Plc</p></div>
                                </td>
                                <td colspan="4"><p><span class="hidden-print">  កាលបរិច្ឆេទ/Date  </span></p></td>
                                <td colspan="3"><?PHP echo date("d-M-Y",strtotime($journalr->entry_date)); ?></td>
                            </tr>
                            <tr class="tdheight" >
                                <td colspan="4"><p><span class="hidden-print"> ម៉ោង/Time: </span></p></td>
                                <td colspan="3"><?PHP echo date("H:m:s", strtotime($journalr->entry_date)); ?></td>
                            </tr>
                            <tr>
                                <td height="28" colspan="4" >
                                    <p style="line-height:12px;" ><p><span class="hidden-print">  លេខប្រត្រិបត្តិការ  </span></p></p>
                                    <p style="line-height:1px;" ><span class="hidden-print"> Trqansaction ID  </span></p></td>
                                <td colspan="3"><div style="border:0.001em inset rgba(0, 0, 0, 0.42); padding:6px;"><?PHP echo str_pad($journalr->id, 8, '0' , STR_PAD_LEFT) ?> </div></td>
                            </tr>
                            <tr style="height:22px;">
                                <td colspan="4" ><p><span class="hidden-print">  ឈ្នោះគណនី/account Name  </span></p></td>
                                <td colspan="11"><?PHP echo $drawdown->account_no; ?></td>
                                <td colspan="4" style="vertical-align: bottom;">
                                    <input type="checkbox" name="vehicle" value="Bike" style="margin-left: 5px;"  <?PHP if($drawdown->currency==1){echo 'checked';} ?> />
                                    <span style="position: absolute; margin-top: 3px;"><span class="hidden-print">  ប្រាក់រៀល/KHR </span></span>
                                </td>
                                <td colspan="3">
                                    <input type="checkbox" name="vehicle" value="Bike" style="margin-left: 5px;"  <?PHP if($drawdown->currency==2){echo 'checked';} ?>  /><span style="position: absolute; margin-top: 3px;"> <span class="hidden-print">ប្រាក់ដុល្លា/USD </span></span></td>
                            </tr>
                            <tr style="height:22px;">
                                <td colspan="4"><p><span class="hidden-print"> ឈ្នោះគណនី/account Number </span></p></td>
                                <td colspan="11"><?PHP echo $drawdown->account_name ?></td>
                                <td colspan="7"> <input type="checkbox" name="vehicle" value="Bike"  style="margin-left: 5px;"  <?PHP if($slips->types==1){echo 'checked';} ?> >
                                    <span style="position: absolute; margin-top: 3px;"> <span class="hidden-print">ដកជាសាក់ប្រាក់/Cash withdrawal</span> </span></td>
                            </tr>
                            <tr style="height:19px;">
                                <td colspan="11" rowspan="3" valign="top"><p><span class="hidden-print">ចំនួនទឹកប្រាក់/Amount:</span></p>
                                    <textarea name="amount_char" id="amount_char" style=" margin-top:-3px; width:360px;min-width:360px; max-width:360px;   resize: none;border:none; overflow:hidden" ><?PHP echo $currency_char->format($slips->amount).' dollar '.'only'; ?> </textarea></td>
                                <td colspan="3" rowspan="3"><div style="border:0.001em inset rgba(0, 0, 0, 0.42); padding:6px;"> {{$currency->symbol.' '.number_format($slips->amount, 2)}} </div> </td>
                                <td rowspan="3">&nbsp;</td>
                                <td colspan="7">
                                    <input type="checkbox" name="vehicle" value="Bike"  style="margin-left: 5px;"   <?PHP if($slips->types==2){echo 'checked';} ?>>
                                    <span style="position: absolute; margin-top: 3px;"> <span class="hidden-print"> ដាក់ជាមូលប្បទានប័ន្រ/Check withdrawal </span> </span></td>
                            </tr>
                            <tr style="height:25px;">
                                <td colspan="7"><p> <span class="hidden-print"> លេខមួលប្បទានប័ត្រ/Check: </span>
                                    <input name="check" id="check" type="text" style="width:65px;border:none; margin-top: 2px;" placeholder="...." value=" <?PHP echo $slips->check_num?$slips->check_num:''; ?>" /></p></td>
                            </tr>
                            <tr style="height:25px;" >
                                <td colspan="7" style="vertical-align: top" > <p><span class="hidden-print">ឈ្នោះធនាគារ/Bank Name:</span>
                                    <input type="text" name="bank" id="bank" style="width:65px; border:none;" placeholder="......" value="<?PHP echo $slips->bank_name?$slips->bank_name:'' ?>" /></p></td>
                            </tr>
                            <tr>
                                <td colspan="15" valign="top"  style="width:375px;min-width:375px; max-width:375px"><p><span class="hidden-print"> អធិប្បាយ/Description </span></p>
                                    <textarea name="description" id="descr" style="margin-top:-9px;width:550px; min-width:550px; max-width:550px; min-height:50px; max-height:50px; height:50px; border:none; resize: none; overflow:hidden" placeholder="hello" >{{$journalr->description}}</textarea>

                                </td>
                                <td colspan="4" valign="top"><p> <span class="hidden-print">ធារីចំណូល  </span></p>
                                    <p><span class="hidden-print">Teller </span>                       </p>
                                    <p>{{$user->name}}</p></td>
                                <td colspan="3" rowspan="2" valign="top"><p><span class="hidden-print"> អនុម័ត្តដោយ </span></p>
                                    <p><span class="hidden-print">authorized by:</span></p><p>{{$user->prepared_by}}</p></td>
                            </tr>
                            <tr>
                                <td height="52" colspan="9" valign="top" ><p> <span class="hidden-print"> ហតថេលខមចស់គណនី<  </span></p>
                                    <p><span class="hidden-print">Applicant’s signature(s)</span></p></td>
                                <td colspan="6" valign="top" ><p><span class="hidden-print">  ហតថេលខអនកទទួល្របក់ ឬ មូលបបទនប័្រត   </span></p>
                                    <p><span class="hidden-print">Payee’s signature</span></p></td>
                                <td colspan="4" valign="top"><p><span class="hidden-print">  អ្នកពិនិត្យ  </span></p>
                                    <p><span class="hidden-print">Checked by</span></p></td>
                            </tr>
                            <tr class="no-border">
                                <td width="1" height="0" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="39" style="border:none;">&nbsp;</td>
                                <td width="27" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="32" style="border:none;">&nbsp;</td>
                                <td width="9" style="border:none;">&nbsp;</td>
                                <td width="54" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="67" style="border:none;">&nbsp;</td>
                                <td width="2" style="border:none;">&nbsp;</td>
                                <td width="33" style="border:none;">&nbsp;</td>
                                <td width="2" style="border:none;">&nbsp;</td>
                                <td width="21" style="border:none;">&nbsp;</td>
                                <td width="31" style="border:none;">&nbsp;</td>
                                <td width="2" style="border:none;">&nbsp;</td>
                                <td style="border:none;">&nbsp;</td>
                                <td width="61" style="border:none;">&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure)?false:false) }}"></script>
@endsection

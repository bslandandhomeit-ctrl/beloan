@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
<style>

    table tbody tr td,p{
        font-size: 9px;
    }
    table.mtable {
        width: 681px;
        max-width: 681px;
        min-width: 681px;
    }
    td.tdwidth {
        height: 80px;
    }
    td p {
        line-height: 4px;
        margin-top: 6px;
    }
    td.no-border{
        border: none;
    }td.date,.time{
         padding-top:3px;
     }
    td.account{
        padding-top:3px;
    }
   .date{
       height: 22px;
       vertical-align: bottom;
       margin-bottom: -9px;
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
      .check {
        width: 70px;
        border: none;
        right: 25px !important;
        float: right;
        margin-top:-3px!important;
      }
      .check-top-15px{
        margin-top:-18px !important;
        top:-18px !important
    }

    #amount_char{
      margin-top: 15px !important;
      }
    }
    @media screen, print
    {

    }

</style>
@section('content')

    <section class="panel">
        <header class="panel-heading">
            <span>{{ trans('teller.t_deposit') }}</span>
            <span style="float: right"><a href="#" id="printer" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('print') }}</a></span>
        </header>
        <?php
        //$currency_char = new NumberFormatter("en", NumberFormatter::SPELLOUT); 
        ?>
        <style media="print">
            .hidden_print {
                display: none;
            }
        </style>

        <div class="panel-body" id="printArea" style="margin: auto;">
            <div class="docmargin">
                <form id="my_form">
                    <table border="1" cellpadding="0" cellspacing="0" class="mtable">
                        <tbody>
                        <tr >
                            <td colspan="15" rowspan="3" valign="top" class="tdwidth" >
                                <div class="hidden-print"><p>គ្រឹះស្ងានមីក្រូហិរញ្ញវត្ថ ធី&amp;ហ្គូ ហ្វាយនែន ភីអិលស៊ី</p>
                                    <p>T &amp; Go finanace Plc</p>
                                </div>
                            </td>
                            <td class="date" colspan="4"><div class="date hidden-print">កាលបរិច្ឆេទ/Date</div></td>
                            <td  class="date" colspan="3"><div class="date"> <?PHP echo date("d-M-Y",strtotime($journalr->entry_date)); ?></div> </td>
                        </tr>
                        <tr >
                            <td class="date" colspan="4"><div class="date hidden-print">ម៉ោង/Time:</div></td>
                            <td colspan="3"  ><div class=""><?PHP echo date("H:m:s", strtotime($journalr->entry_date)); ?></div></td>
                        </tr>
                        <tr>
                            <td height="28" colspan="4" >
                                <p class="hidden-print" style="line-height:12px;" >លេខប្រត្រិបត្តិការ</p>
                                <p class="hidden-print" style="line-height:1px;" >Trqansaction ID</p></td>
                            <td colspan="3"><div style="padding:6px; "><?PHP echo str_pad($journalr->id, 8, '0' , STR_PAD_LEFT) ?></div></td>
                        </tr>
                        <tr style="height:22px;">
                            <td class="account" colspan="4" valign="top" style=""><div class="hidden-print"> ឈ្នោះគណនី/account Name</div></td>
                            <td colspan="11" valign="middle"><?PHP echo $drawdown->account_no; ?></td>
                            <td colspan="4">
                                <input class="hidden-print" type="checkbox" name="vehicle" value="Bike" style="margin-left: 2px;" <?PHP if($drawdown->currency==1){echo 'checked';} ?> />
                                <span class="hidden-print" style="position: absolute; margin-top: 3px;">ប្រាក់រៀល/KHR</span> </td>
                            <td colspan="3"> <input class="hidden-print" type="checkbox" <?PHP if($drawdown->currency==2){echo 'checked';} ?>  name="" value="1" style="margin-left: 5px;">
                                <span class="hidden-print" style="position: absolute; margin-top: 3px;">ប្រាក់ដុល្លា/USD</span></td>
                        </tr>
                        <tr style="height:22px;">
                            <td class="account" colspan="4" valign="top" style=""><div class="hidden-print">ឈ្នោះគណនី/account Number</div></td>
                            <td colspan="11"><?PHP echo $drawdown->account_name ?></td>
                            <td colspan="7"> <input class="hidden-print" type="checkbox" name="cash" value="cash" style="margin-left: 2px;" <?PHP if($slips->types==1){echo 'checked';} ?> >
                                <span class="hidden-print" style="position: absolute; margin-top: 3px;">ដាក់សាច់ប្រាក់/Cash Deposit</span></td>
                        </tr>
                        <tr>
                            <td colspan="11" rowspan="3" valign="top" style="height: 82px;">

                               <p><span class="hidden_print">ចំនួនទឹកប្រាក់/Amount:</span></p>
                                <p><textarea name="amount_char" id="amount_char" style="width:316px;min-width:316px; max-width:316px;   resize: none;border:none; overflow:hidden" > <?PHP echo $currency_char->format($slips->amount).' dollar '.'only'; ?></textarea></p>
                            </td>
                            <td colspan="3" rowspan="3" style="vertical-align:middle;"><div style="padding:6px; text-align: center"> {{ $currency->symbol.' '.number_format($slips->amount, 2)}} </div></td>
                            <td rowspan="3">&nbsp;</td>
                            <td colspan="7" >
                                <p>
                                    <div class="hidden_print"><input type="checkbox" name="check" value="check" style="margin-left: 2px;"  <?PHP if($slips->types==2){echo 'checked';} ?> /><span style="position: absolute; margin-top: 3px;">ដាក់ជាមូលប្បទានប័ន្រ/Check Deposit</span></div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <p>
                                    <span class="hidden_print">
                                    លេខមួលប្បទានប័ត្រ/Check:
                                    </span>

                                    <span><input class="check" name="check" id="check" type="text" value="<?PHP echo $slips->check_num?$slips->check_num:''; ?>" style="width:70px;border:none;" placeholder="...." /></span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <p>
                                    <span class="hidden_print">ឈ្នោះធនាគារ/Bank Name: </span>
                                    <span><input class="check check-top-15px" type="text" name="bank" id="bank" value="<?PHP echo $slips->bank_name?$slips->bank_name:'' ?>" style="width:70px; border:none;" placeholder="......"/></span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" rowspan="2" valign="top"><p><div class="hidden_print">អធិប្បាយ/Description</div></p>
                                <textarea name="description" id="descr" style=" border:none; resize: none; overflow:hidden" placeholder="Description">{{$journalr->description}}</textarea>
                            </td>
                            <td colspan="5" rowspan="2" valign="top">
                                <p> <div class="hidden_print">សត្ថលេខាអ្នកដាក់ប្រាក់</div> </p>
                                <p> <div class="hidden_print"> Pay's signature </div> </p>
                            </td>
                            <td height="" colspan="4" valign="top"><p><div class="hidden_print">ធារីចំណូល</div></p>
                                <p><div class="hidden_print">Teller</div></p>
                                <p style="position:absolute;margin-top: 15px;margin-left: 4px;">{{$user->name}}</p></td>
                            <td colspan="3" rowspan="2" valign="top">
                                <p>
                                    <div class="hidden_print">អនុម័ត្តដោយ</div>
                                </p>
                                <p><div class="hidden_print"> authorized by: </div> </p> </td>
                        </tr>
                        <tr>
                            <td height="50" colspan="4" valign="top">
                                <p>
                                    <div class="hidden_print">អ្នកពិនិត្យ</div>
                                </p>
                                <p><div class="hidden_print">Checked by</div></p>
                            </td>
                        </tr>
                        <tr class="no-border">
                            <td width="1" height="0" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="76" style="border:none;">&nbsp;</td>
                            <td width="42" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="32" style="border:none;">&nbsp;</td>
                            <td width="9" style="border:none;">&nbsp;</td>
                            <td width="54" style="border:none;">&nbsp;</td>
                            <td width="1" style="border:none;">&nbsp;</td>
                            <td width="55" style="border:none;">&nbsp;</td>
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

    </section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure)?false:false) }}"></script>
@endsection

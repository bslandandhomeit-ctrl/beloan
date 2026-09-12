@extends('layouts.app')
@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')

<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.slip_credit') }}</span>
        <span style="float: right"><a href="#" id="printer" class="btn btn-success"><i class="fa fa-plus"></i> {{trans('print') }}</a></span>
    </header>
    <?php
    $currency_char = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    ?>
    <style media="print">
        .hidden_print {
            display: none;
        }
    </style>
    <div class="row" id="printArea">
        <div class="col-lg-6">
            <div class="panel-body">
                <div class="docmargin" style="width:770px;max-width:770px;min-width:770px;">
                    <?PHP if($credit): ?>
                    <form id="my_form">
                        <table height="321" border="1" style="width:770px;max-width:770px;min-width:770px; font-size:11px;">
                            <tbody>
                            <?PHP $jds = []; ?>
                            <?PHP foreach($credit as $jr): ?>
                                        <?PHP
                                            $entry_date = $jr->entry_date;
                                            $jrId = str_pad($jr->id,8,0,STR_PAD_LEFT);
                                        ?>
                                        <?PHP foreach($jr->detail as $jd): ?>
                                            <?PHP
                                        if(floatval($jd->credit)!= 0){
                                            $jds[] = $jd;
                                        }
                                    ?>
                                        <?PHP endforeach; ?>
                                    <?PHP endforeach; ?>

                            <?PHP foreach($jds as $jd_prop): ?>

                            <tr>
                                <td height="42" colspan="8" style="border:none;">&nbsp;</td>
                                <td colspan="12" style="border:none;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="border:none;width:57px;max-width:57px;min-width:57px;height: 19px;">កាលបរិច្ចេទ</td>
                                <td colspan="5" rowspan="2" style="border:none;width:80px; max-width: 80px; min-width: 80px; ">
                                    <div style="border:1px solid red;height:30px;line-height: 28px;">{{$entry_date}}</div>
                                </td>
                                <td colspan="3" rowspan="2" valign="top" style="border:none; width:200px;">
                                    <input type="checkbox" style="margin-left:107px;" <?PHP if((int)$jd_prop->account->currency ===1){echo 'checked';} ?> >
                                    <span style="position:absolute; margin-top:1.5px;"> ប្រាក់រៀល/KHR</span>
                                    <input type="checkbox" style="margin-left:126px" <?PHP if((int)$jd_prop->account->currency ===2){echo 'checked';} ?>>
                                    <span style="position: absolute; margin-top: 1.5px;">ប្រាក់ដុល្លា/USD</span></td>
                                <td width="10" rowspan="4" style="border:none;">&nbsp;</td>
                                <td width="1" style="border:none;">&nbsp;</td>
                                <td width="28" style="border:none;">&nbsp;</td>
                                <td colspan="3" style="border:none; width:90px;">លេចខប្រតិប្តិការ</td>
                                <td width="172" colspan="5" rowspan="2" style="border:none;"><div style="height:30px;border:1px solid black; line-height: 28px;">{{$jrId}}</div></td>
                            </tr>
                            <tr>
                                <td style="border:none;">Date</td>
                                <td style="border:none;">&nbsp;</td>
                                <td style="border:none;">&nbsp;</td>
                                <td colspan="3" style="border:none;">Transaction ID</td>
                            </tr>
                            <tr>
                                <td height="46" colspan="9" valign="top" style="max-width:498px;width: 498px; min-width:498px;"><p>ឈ្មោះគណនីឥណទាន្ធ/Debit Account Name</p>
                                    <p>
                                        <input type="text" style="width:100%;border:none;" placeholder="......................." value="{{$jd_prop->account->name}}">
                                    </p></td>
                                <td colspan="10" valign="top"><p>លេខគណនីឥណទាន/Debit Account Number
                                    </p>
                                    <p>
                                        <input type="text" placeholder="......................." style="width:100%;border:none;"  value="{{$jd_prop->account->account_code}}">
                                    </p></td>
                            </tr>
                            <tr>
                                <td colspan="9" valign="top">អធិប្បាយ/Description
                                                        <textarea name="descr" id="descr" style="resize:none;height:65px; width:100%;border:none; overflow:hidden;">
                                                            {{$jd_prop->description}}
                                                        </textarea></td>
                                <td colspan="10" valign="top"><span style="position:absolute; margin-top:10px;">ចំនួន/Amount</span>
                                    <input type="text" style="width:73%;border:none;;float:right; height:30px;" placeholder="................." value="{{floatval($jd_prop->credit)}}">
                                    <textarea name="descr" id="descr" placeholder="................" style="resize:none;height:47px; width:100%;border:none;"></textarea></td>
                            </tr>

                            <?PHP endforeach; ?>
                            </tbody>

                        </table>
                    </form>
                    <?PHP endif; ?>
                </div>
            </div>
            <table style="margin-left: 17px;">
                <tr>
                    <td style="width: 250px; text-align: center">រៀបចំដោយ/Prepared by:</td>
                    <td style="width: 250px; text-align: center">ពិនិត្យដោយ/Verified by:</td>
                    <td style="width: 250px; text-align: center">អនុម័ត្តដោយ/Approved by:</td>
                </tr>
            </table>
        </div>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',false) }}"></script>
<script type="text/javascript">

</script>
@endsection
@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/data-tables/dataTablesStyle.css',false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/data-tables/TableTools.css',false) }}"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
            <section class="panel">
                <header class="panel-heading"> {{ trans('account.a_acc_customer') }} </header>
                <div class="panel-body">
                    <div class="col-lg-4">
                        <!--Something else-->
                        <?php $currency_symbol = config('static_data.currency_symbol'); ?>

                    </div>
                    <div class="col-lg-8 text-right">
                        <a href="#" class="btn btn-sm btn-info" id="add_vendor_act">{{ trans('account.add_acc_customer') }}</a>
                    </div>
                    <hr/>
                    <div class="table col-lg-12">
                        <table class="table table-responsive data_table table-bordered" >
                            <thead>
                                <tr>
                                    <th width="20px">No</th>
                                    <th>Company Name</th>
                                    <th>Balance Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?PHP if($acc_customer):;?>
                            <?PHP $i=1;?>
                                <?PHP foreach($acc_customer as $acc_cust): ?>
                                    <tr class="tr{{$acc_cust->id}}" style="<?PHP if($acc_cust->status==1){echo "background-color:#f2f2f2;cursor: no-drop ";} ?>">
                                        <?PHP
                                            $td  = '<td>'.$i++.'</td>';
                                            $td  .= '<td>'.$acc_cust->company_name.'</td>';
                                        if(count($acc_cust->JournalRequiryWhere)!=0) {
                                            foreach($acc_cust->JournalRequiryWhere as $detail){
                                                foreach($detail->detail as $detailPro){
                                                    if(floatval($detailPro->credit) != 0) {
                                                        $credit += floatval($detailPro->credit);
                                                    }
                                                }
                                            }
                                            $td  .= '<td>'.$currency_symbol[2].' '.number_format($credit, 2).'</td>';

                                        }else{
                                            $td  .= '<td>0</td>';
                                        }
                                            $td  .= '<td>';
                                            $td  .= '<i data-id="'.$acc_cust->id.'" class="btn btn-default btn-xs glyphicon glyphicon-eye-open" style="margin-right:4px;"></i>';
                                            $td  .= '<i data-id="'.$acc_cust->id.'" class="btn btn-default btn-xs glyphicon glyphicon-edit"  style="margin-right:4px;"></i>';
                                            $td  .= '<i data-id="'.$acc_cust->id.'" class="btn btn-danger  btn-xs glyphicon glyphicon-remove"  style="margin-right:4px;"></i>';
                                            $td  .= '</td>';
                                            echo $td;
                                        ?>
                                    </tr>
                                <?PHP  endforeach; ?>
                            <?PHP else: ?>
                            <tr></tr>
                            <?PHP endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

    </div>
<input type="hidden" id="customer_id" /> <!--Don't delete this.-->
@endsection

@section('js')
    <script src="{{ asset('theme/js/data-tables/jquery.dataTables.js',false) }}"></script>
    <script src="{{ asset('theme/js/data-tables/TableTools.min.js',false) }}"></script>
    <script>
        $(document).ready(function () {
            $('.data_table').DataTable({
                "sDom": 'T<"clear">lfrtip',
                "aLengthMenu": [[-1,10,15,25,50,100,200,250,300,-2], ["All",10,15,25,50,100,200,250,300,"Only Mark"]],
                "oTableTools": {
                    "sSwfPath": "{{asset('theme/js/data-tables/images/copy_csv_xls_pdf.swf',false) }}"
                }
            });
            var prev = $('.paginate_disabled_previous');
            prev.addClass('btn btn-default');
            prev.css({'margin-right':'7px'});
            $('.paginate_disabled_next').addClass('btn btn-default')
        });
        $(document).on('click', '#add_vendor_act, .glyphicon-edit, .glyphicon-remove, .glyphicon-eye-open ', function () {

            var id = $(this).attr('data-id')?$(this).attr('data-id'):0;
            $('#customer_id').val(id);
            if ($(this).is('#add_vendor_act') || $(this).is('.glyphicon-edit')) {

                call_and_delete_Loading('#loading','Loading....');
                $.ajax({
                    url: "get_add_customer_act/"+id,
                    method: 'get',
                    dataType: 'html',
                    timeout: 3000,
                    success: function (data, status) {
                        if (status === 'success') {
                            delete_allModel('.modal');

                            $(data).appendTo('body');
                            $('#add_vendor').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            $('#vedor_id').val(id);
                            $('#loading').remove();
                        }
                    }
                })
            }
            if($(this).is('.glyphicon-remove')) {

               if(confirm("Are you sure?")){
                   call_and_delete_Loading('#loading','Loading....')

                   $.ajax({
                       url: "delet_acc_customer/"+id,
                       method: 'post',
                       dataType: 'json',
                       timeout: 3000,
                       data:{id:id,_token:$('meta[name="_token"]').attr('content')},
                       headers: {
                           'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                       },
                       success: function (data, status) {
                           if(status === 'success') {

                               if (data.delete === false) {
                                   $('#loading').remove();
                                   $('<div id="loading"></div>').appendTo('body');
                                   return imgLoading(true,'We can\'t delete your data please try again ',5,'warning');
                               }else{

                                   $('#loading').remove();
                                   $('<div id="loading"></div>').appendTo('body');
                                   imgLoading(true,'Successfully!!!',5,status);
                                   if(data.status === 1){
                                       $('.tr'+id).css({'background-color':'#f2f2f2','cursor':'no-drop'});
                                   }else{
                                       $('.tr'+id).css({'background-color':'white','cursor':''});
                                   }
                               }
                           }
                       }
                   })
               }
            }
            if($(this).is('.glyphicon-eye-open')){

                call_and_delete_Loading('#loading', "Loading.....");

                $.ajax({
                    url: "get_acc_customer_info/"+id+'/'+'0'+'/'+'0',
                    method: 'get',
                    dataType: 'html',
                    timeout: 3000,
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    }, success: function (data, status) {
                        if(status === 'success') {
                            delete_allModel('.modal');

                            $(data).appendTo('body');
                            $('#customer_info').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            $('#customer_id').val(id);
                            call_and_delete_Loading('#loading')
                        }
                    }
                })
            }

        });

        function call_and_delete_Loading(loading,sms){
            $('body').find(loading).each(function(){
                $(this).remove();
            });
            if(sms != undefined) {
                $('<div id="loading"></div>').appendTo('body');
                imgLoading(true,sms, 9,'warning');

            }
        }
        function delete_allModel(models) {

            $('body').find(models).each(function (e) {
                $(this).remove();
            });
        }

    </script>

@endsection
<style>
    form#search {
        position: absolute;
        overflow: auto;
        z-index:1000000;
        top:-7px;
    }
</style>

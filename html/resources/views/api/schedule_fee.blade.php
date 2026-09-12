<form class="cmxform form-horizontal" method="post" action="">
<?php $fee_type = Config::get('static_data')['fee_charge'];?>
<h4 class="sch_title">{{ trans('loan.schedule_fee') }}</h4>
<table class="table table-bordered schedule_fee">
    <thead>
        <tr>
            <th style="text-align:center; vertical-align: middle;">{{ trans('multiple.type') }}</th>
            <th style="text-align:center; vertical-align: middle;">{{ trans('multiple.amount') }}</th>
            <th style="text-align:center; vertical-align: middle;">{{ trans('multiple.date') }}</th>
            <th style="text-align:center; vertical-align: middle;">{{ trans('multiple.note') }}</th>
            <th style="text-align:center; vertical-align: middle;">{{ trans('multiple.is_paid') }}</th>
            <th style="text-align:center; vertical-align: middle;">{{ trans('multiple.action') }}</th>
        </tr>
    </thead>
    
    <tbody>
        <?php $fee_type = Config::get('static_data')['fee_charge'];?>
        @foreach($res as $r)
            <?php 
                $date = date('Y-m-d');
                $date = date('Y-m-t', $date);
                $schedule_date = $r->schedule_date;
            ?>
            <tr @if($date >= $schedule_date && $r->status==0) class="bg-danger" @endif>
                <td>{{$fee_type[$r->fee_type]}}</td>
                <td class="text-right">{{number_format($r->amount)}}</td>
                <td>{{$r->schedule_date}}</td>
                <td>{{$r->note}}</td>
                <td>@if($r->status==1) {{ trans('multiple.yes') }} @else {{ trans('multiple.no') }} @endif</td>
                <td>
                    @if($r->status==0)
                    <a href="#" class="btn btn-default btn-xs edit_fee" title="{{ trans('multiple.edit') }}" data-toggle="modal" data-target="#feeModal-{{$r->id}}"><i class="fa fa-pencil"></i></a>
                    <a href="{{ route('loan_add_charge', [$r->loan_id]) }}?fee_id={{$r->id}}" class="btn btn-primary btn-xs" title="{{ trans('loan.add_charge') }}"><i class="glyphicon glyphicon-share-alt"></i></a>

                    <div class="modal fade" id="feeModal-{{$r->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>

                            <div class="modal-body">
                               <div style="padding:0 25px">
                                    <div class="form-group">
                                        <select class="form-control" name="fee_type[]">
                                            @foreach($fee_type as $key=>$val)
                                                <option value="{{$key}}" @if($key==$r->fee_type) selected @endif>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" value="{{$r->amount}}" class="form-control" name="amount" />
                                    </div>

                                    <div class="form-group">
                                        <input type="text" value="{{$r->note}}" class="form-control" name="note" />
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary fee_update" id="{{$r->id}}"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.save') }}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;{{ trans('multiple.cancel') }}</button>
                                    </div>
                               </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $("body").on("click", ".fee_update", function () {
            this_id = $(this).attr('id');
            this_parent = $(this).parents('td').find('#feeModal-'+this_id);
            $.ajax({
                url: "{{ route('schedule_fee_update') }}",
                data: "id="+this_id+"&amount=" + this_parent.find('input[name="amount"]').val() + "&note=" + this_parent.find('input[name="note"]').val(),
                method: 'get',
                success: function (res) {
                    window.location.reload();
                }
            });

            return false;
        });    
    });
</script>




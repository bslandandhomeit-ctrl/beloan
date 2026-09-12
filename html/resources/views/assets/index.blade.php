@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure)?false:false) }}" rel="stylesheet">
<link href="{{ asset('css/loan-style.css',isset($secure)?false:false) }}" rel="stylesheet">
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_asset_summary') }}</span>
        <span style="float: right"><a href="{{route ('add_asset') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_asset') }}</a></span>
    </header>

    <div class="panel-body">

        <section id="unseen" class="ox-scroll">
            <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                <thead>
                <th>#</th>
                <th >{{ trans('multiple.branch') }}</th>
                <th >{{ trans('multiple.category') }}</th>
                <th >{{ trans('multiple.location') }}</th>
                <th >{{ trans('asset.classification') }}</th>
                <th >{{ trans('asset.currency') }}</th>
                <th >{{ trans('asset.invoice_num') }}</th>
                <th >{{ trans('asset.tag_num') }}</th>
                <th >{{ trans('asset.main_gl_id') }}</th>
                <th >{{ trans('asset.depre_gl_id') }}</th>
                <th >{{ trans('asset.exp_gl_id') }}</th>
                <th >{{ trans('asset.depre_meth') }}</th>
                <th >{{ trans('asset.purchased_date') }}</th>
                <th >{{ trans('asset.original_cost') }}</th>
                <th >{{ trans('asset.depre_year') }}</th>
                <th >{{ trans('asset.rate') }}</th>
                <th >{{ trans('asset.remark') }}</th>
                <th >{{ trans('multiple.m_status') }}</th>
                <th >{{ trans('multiple.m_action') }}</th>
                <th >{{ trans('asset.button') }}</th>
                </thead>
                <tbody>
                    @foreach($res as $list)
                        <?php $i++;?>
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$branches[$list->branch]}}</td>
                            <td>{{$asset_categories[$list->category]}}</td>
                            <td>{{$asset_locations[$list->location]}}</td>
                            <td>{{$asset_classifications[$list->classification]}}</td>
                            <td>{{$currency[$list->currency]}}</td>
                            <td>{{$list->invoice_num}}</td>
                            <td>{{$list->tag_num}}</td>
                            <td>{{$list->main_gl_id}}</td>
                            <td>{{$list->depre_gl_id}}</td>
                            <td>{{$list->exp_gl_id}}</td>
                            <td>{{config('static_data.asset_depre_meth')[$list->depre_meth]}}</td>
                            <td>{{$list->purchased_date}}</td>
                            <td>{{$list->original_cost}}</td>
                            <td>{{$list->depre_year}}</td>
                            <td>{{$list->rate}}%</td>
                            <td>{{$list->remark}}</td>
                            <td>{{$list->status}}</td>
                            <td align="center" class="define-width">
                                <a href="{{route('edit_asset', [$list->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                <a href="{{route('delete_asset', [$list->id])}}" class="do-delete btn btn-xs btn-danger" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                            <td>
                                <a href="{{route('edit_asset', [$list->id])}}?adjust=1" class="btn btn-xs btn-warning"> {{ trans('asset.adjust') }}</a>
                                <a href="{{route('dispose_asset', [$list->id])}}" class="btn btn-xs btn-success">{{ trans('asset.dispose') }}</a>
                                <a href="{{route('write_off_asset', [$list->id])}}" class="btn btn-xs btn-warning">{{ trans('asset.write_off') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="page">
                <?php echo $res->appends([ ])->render(); ?>
            </div>

        </section>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure)?false:false) }}"></script>
<script type="text/javascript">
$(document).ready(function () {

});
</script>
@endsection

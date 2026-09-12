@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="{{ asset('theme/js/data-tables/DT_bootstrap.css',isset($secure) ? false : false) }}" />
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
                All Translations
                <a class="pull-right btn btn-primary" href="{{ route('add_locale') }}">Add Language</a>
                <a class="pull-right btn btn-success" href="{{ route('add_tran') }}">Add Translation</a>
             </header>
             <div class="panel-body">
                @if(!empty($locales) && count($locales) > 0)
                 <?php
                    $local_id = [];
                 ?>
                 <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                 <table class="table table-bordered" id="locale-table">
                    <thead>
                       <tr>
                            <th></th>
                            @foreach($locales as $l)
                                <th><a href="{{ route('generate_locale',[$l->id]) }}" class="btn btn-primary">Save {{ $l->locale }}</a> </th>
                            @endforeach
                            <th></th>
                       </tr>
                       <tr>
                         <th>No</th>
                         @foreach($locales as $l)
                            <?php
                                $local_id[$l->id] = $l->id;
                            ?>
                            <th>{{ $l->locale }}</th>
                         @endforeach
                         <th>Edit</th>
                       </tr>
                    </thead>
                    @if(!empty($all_locale) && count($all_locale) > 0)
                        <?php
                            $group_lang = $all_locale->groupBy('key');
                            $i = 0;
                             foreach($group_lang as $gl){
                               $i++;
                               $tr = '<tr>';
                               $tr .= '<td>'.$i.'</td>';
                                foreach($local_id as $key => $v){
                                    $item =  array_first($gl, function ($k, $value)  use($key){
                                        return $key == $value->locale_id;
                                    });
                                    if(!empty($item)){
                                        $tr .= '<td id="'.$item->id.'" class="'.$key.'">'.$item->title.'</td>';
                                    }else{
                                        $tr .= '<td id="" class="'.$key.'">-</td>';
                                    }
                                }
                                $tr .= '<td><a class="edit btn btn-xs btn-default" href="javascript:;" id="edit"><i class="fa fa-pencil"></i></a></td>';
                                $tr .= '</tr>';
                                echo $tr;
                             }
                        ?>
                    @endif
                 </table>
                 @endif
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('theme/js/data-tables/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/data-tables/DT_bootstrap.js',isset($secure) ? false : false)}}"></script>
<script src="{{ asset('js/locale-table.js',isset($secure) ? false : false) }}"></script>
<!-- END JAVASCRIPTS -->
<script>
    jQuery(document).ready(function() {
        LocaleTable.init();
    });
</script>
@endsection
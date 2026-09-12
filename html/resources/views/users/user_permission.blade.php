@extends('layouts.app')

@section('css')
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <form action="{{ route('make_permission',[$id]) }}" method="POST">
             <header class="panel-heading">
                 {{ trans('user.u_user_permission') }}
             </header>
             <div class="panel-body ox-scroll">
                   @if(Session::has('message'))
                       <p class="alert {{ Session::get('alert-class', 'alert-success') }}" >{{ Session::get('message') }}</p>
                   @endif
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">
                   <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('multiple.m_no') }}</th>
                                <th>{{ trans('user.u_user_code') }}</th>
                                <th>{{ trans('multiple.m_type') }}</th>
                                <th>{{ trans('multiple.m_note') }}</th>
                                <th>{{ trans('multiple.m_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                           @if(!empty($permissions) && count($permissions) > 0)
                                <?php $no = 0;
                                    $permission_group = $permissions->groupBy('group_code');
                                    foreach($permission_group as $gp){
                                        $first_group = $gp[0];
                                        if($first_group->group_code != 0){
                                            echo '<tr><th colspan="4" align="center">'.$first_group->group.'</th><td>';
                                            echo ' <div class="icheck group">
                                                   <div class="flat-green single-row">
                                                      <div class="radio " >
                                                          <input type="checkbox" id="'.$first_group->group_code.'"/>
                                                      </div>
                                                  </div>
                                              </div></td></tr>';
                                        }
                                        foreach($gp as $p){
                                            $no++;
                                            $table ='<tr>';
                                            $table .= '<td>'. $no .'</td>';
                                            $table .= '<td>'. $p->code .'</td>';
                                            $table .= '<td>'. $p->action_type .'</td>';
                                            $table .= '<td>'. $p->note .'</td>';
                                                $check = '';
                                                if(!empty($user_permissions) && count($user_permissions) > 0){
                                                    $per_id = $p->id;
                                                    if($user_permissions->contains(function($key,$up) use($per_id){
                                                         return $per_id == $up->permission_id;
                                                    })){
                                                        $check = 'checked';
                                                    };
                                                }
                                             $table .= '<td align="left"><input name="permission[]" type="checkbox"'. $check .' value="'. $p->id .'" class="'.$first_group->group_code.'"></td>';
                                          $table .= '</tr>';
                                          echo $table;
                                        }
                                    }
                                ?>
                           @endif
                        </tbody>
                   </table>
                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 20px;">{{ trans('multiple.m_save') }}</button>
                </div>
             </form>
        </section>
    </div>
</div>
@endsection

@section('js')
 <script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
 <script type="text/javascript">
    $(document).ready(function(){
        $('.group input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        }).on('ifToggled',function(e){
            e.preventDefault();
            var chck = $(this).prop('checked');
            var id = $(this).attr('id');
            if(chck){
               $('input:checkbox.'+id).each(function () {
                     this.checked = true;
               });
            }else{
                $('input:checkbox.'+id).each(function () {
                     this.checked = false;
               });
            }
        });
    });
 </script>
@endsection
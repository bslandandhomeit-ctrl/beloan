@extends('layouts.app')
@section('content')
    <section class="panel">
        <div class="panel-heading">
            {{ trans('company.com_ip_list')}}
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                    <th>{{ trans('multiple.m_no') }}</th>
                    <th>{{ trans('company.com_ip_start') }}</th>
                    {{--<th>{{ trans('company.com_ip_end') }}</th>--}}
                    <th>{{ trans('multiple.m_action') }}</th>
                </thead>
                <tbody>
                    @if(!empty($ip_list) && count($ip_list) > 0)
                        <?php $n = 0;?>
                        @foreach($ip_list as $ip)
                          <?php $n++;?>
                            <tr>
                                <td>{{ $n }}</td>
                                <td>{{ $ip->ip_start_range }}</td>
                                {{--<td>{{ $ip->ip_end_range }}</td>--}}
                                <td><a href="{{ route('ip_del',[$ip->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-times-circle"></i> Delete</a> </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">{{ trans('multiple.m_no_result') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Add IP</button>
        </div>
    </section>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add an IP address</h4>
              </div>

              <form class='form-group' role="form" action="{{ route('ip_range') }}" method="post" id="frmIpRange">
                  <div class="modal-body">
                      <label>Enter an IP address</label><input type="text" name="ip_addr" class="form-control"></input>
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  </div>

                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
              </form>
          </div>

        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
@endsection

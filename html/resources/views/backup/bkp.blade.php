@extends('layouts.app')
@section('content')
   <div class="row">
        <div class="panel">
            <div class="panel-heading">
                DB Backup
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Date</th>
                        <th>Download</th>
                    </thead>
                    <tbody>
                        @if(!empty($back_up) && count($back_up) > 0)
                            @var $i = 0;
                            @foreach($back_up as $bkp)
                                @var $i = $i +1
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ date('Y-m-d H:i:s', strtotime(substr($bkp[0], 0, -4))) }}</td>
                                    <td><a href="{{ route('db_backup_down').'?b='.$bkp[0] }}" class="btn btn-primary">Download</a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No Back up</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
   </div>
@endsection
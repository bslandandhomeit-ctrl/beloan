@extends('layouts.app')
@section('css')

@endsection

@section('content')

<section class="panel">
    <div class="panel-body">

        <div class="row">
            <div class="col-lg-12">
                <div class="header-days">
                    <h5>Traccar GPS system user summary</h5>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>phone</th>
                            <th>model</th>
                            <th>status</th>
                            <th>category</th>
                        </tr>
                        </thead>
                        <tbody id="userLists"></tbody>
                    </table>
                    <div class="col-lg-12">
                        <a class="btn btn-info btn-md btnReload"> Reload </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('js')

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
    <script src="{{ asset('theme/js/advanced-datatable/js/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>

    <script>
        $(document).ready(function(){
            return getData();
        });

        $('.btnReload').on('click', function(){
            var userLists =$('#userLists tr');

            userLists.tr.remove();
            return getData();
        });

        function getData(){

            $.ajax({
                type: 'get',
                url: 'http://traccar.figix.asia/api/reports/summary?deviceId=2&from=2016-11-19T20%3A50%3A35.000Z&to=2016-11-19T21%3A20%3A35.000Z',
                headers: {
                    "Authorization": "Basic " + btoa('admin' + ":" + 'admin123456')
                },
                success: function (data, status) {
                    var htmls = '';

                    console.log(data);

                    $.each(data, function(inx, vals){
                        htmls += '<tr>';
                        htmls += '<td>'+vals.name+'</td>';
                        htmls += '<td>'+vals.phone+'</td>';
                        htmls += '<td>'+vals.model+'</td>';
                        htmls += '<td>'+vals.status+'</td>';
                        htmls += '<td>'+vals.category+'</td>';
                        htmls += '</tr>';
                    });
                    $(htmls).appendTo('#userLists');
                }
            });
        }
    </script>

@endsection
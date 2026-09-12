@extends('layouts.app')
@section('css')

    <style>
        #map {
            height: 100%;
        }

    </style>
@endsection

@section('content')

    <section class = "panel">
        <div class = "panel-body">
            {{--        {{dd($permission)}}--}}
            <div class = "row">
                <div class = "col-lg-12">
                    <div class = "header-days">
                        <h5>Traccar GPS system user summary</h5>
                    </div>
                    <div class = "col-lg-12 text-right" style = "margin-bottom: 14px;">
                        <a class = "btn btn-info btn-md"> Devices</a> <a class = "btn btn-info btn-md"> Report</a>
                    </div>
                    <div class = "col-lg-12 table-responsive">
                        <table class = "table table-bordered ">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Admin</th>
                                <th>Coordinate Format</th>
                                <th>Device Limit</th>
                                <th>Disabled</th>
                                <th>Distance Unit</th>
                                <th>expirationTime</th>
                                <th>map type</th>
                                <th>speedUnit</th>

                            </tr>
                            </thead>
                            <tbody id = "userLists"></tbody>
                        </table>
                        <div class = "col-lg-12">
                            <a class = "btn btn-info btn-md btnReload">Reload</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>





@endsection

@section('js')

    <script>
        $(document).ready(function () {
            return getData();
        });

        $('.btnReload').on('click', function () {
            var userLists = $('#userLists'), btnReload = $('.btnReload').attr('disabled', true);
            userLists.children('tr').remove();
            getData();
            btnReload.attr('disabled', false);
        });

        function getData() {

            $.ajax({
                type: 'get',
                url: 'http://traccar.figix.asia/api/users',
                headers: {
                    "Authorization": "Basic " + btoa('admin' + ":" + 'admin123456')
                },
                success:function(data, status) {
                    var htmls = '';
                    $.each(data, function (inx, vals) {
                        htmls += '<tr>';
                        htmls += '<td>' + vals.name + '</td>';
                        htmls += '<td>' + vals.email + '</td>';
                        htmls += '<td>' + vals.admin + '</td>';
                        htmls += '<td>' + vals.coordinateFormat + '</td>';
                        htmls += '<td>' + vals.deviceLimit + '</td>';
                        htmls += '<td>' + vals.disabled + '</td>';
                        htmls += '<td>' + vals.distanceUnit + '</td>';
                        htmls += '<td>' + vals.expirationTime + '</td>';
                        htmls += '<td>' + vals.map + '</td>';
                        htmls += '<td>' + vals.speedUnit + '</td>';
                        htmls += '</tr>';
                    });
                    $(htmls).appendTo('#userLists');
                }
            });
        }

    </script>

@endsection
@extends('layouts.app')
@section('css')
    <link rel = "stylesheet" type = "text/css" href = "{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel = "stylesheet" type = "text/css" href = "{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection

@section('content')
    <div class = "modal fade" id = "call_map" tabindex = "-1" role = "dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class = "modal-dialog" style = "width: 100%; height:100%">
            <div class = "modal-content">
                <div class = "modal-header">
                    <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button>
                    <h4 class = "modal-title">{{ trans('multiple.m_map') }}</h4>
                </div>
                <div class = "modal-body">
                    <form id = "searchingForm" class = "form-inline text-left" onsubmit = "return false">

                        <div class = "form-group">
                            <label class = "col-sm-2 control-label">From </label>
                        </div>
                        <div class = "form-group">
                            <input type = "text" name = "from_date" class = "form-control dpYears"/>
                        </div>

                        <div class = "form-group">
                            <label class = "col-sm-2 control-label">To</label>
                        </div>
                        <div class = "form-group">
                            <div class = "col-sm-3">
                                <input type = "text" name="to_date" class = "form-control dpYears">
                            </div>
                        </div>

                        <div class = "form-group">
                            <label class = "col-sm-2 control-label">All</label>
                        </div>
                        {{--<div class = "form-group">--}}
                        {{--<div class = "col-sm-3">--}}
                        {{--<input type = "checkbox" name = "all" class = "form-control">--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        <div class = "form-group">
                            <a  href="#" id="btn_submit" value="Search" class = "btn btn-info btn-md"> Search </a>
                        </div>
                    </form>
                    <hr/>
                    <div id="result"></div>
                </div>

                <div class = "modal-footer">
                    <button data-dismiss = "modal" class = "btn btn-default" type = "button">{{ trans('multiple.m_close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <section class = "panel">
        <div class = "panel-body">

            <div class = "row">
                <div class = "col-lg-12">
                    <div class = "header-days">
                        <h5>Traccar GPS system devices summary</h5>
                    </div>
                    <div class = "col-lg-12 text-right" style = "margin-bottom: 14px;">
                        <a class = "btn btn-info btn-md"> Devices</a> <a class = "btn btn-info btn-md"> Report</a>
                    </div>
                    <hr/>
                    {{--<div class="col-lg-12">--}}
                    {{--<div class="text-right " style="margin-bottom: 15px;">--}}
                    {{--<button class="btn btn-info btn-md" id="btn_all" > All </button>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <div class = "col-lg-12">
                        <div class = "table-responsive">
                            <table class = "table table-bordered ">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>phone</th>
                                    <th>model</th>
                                    <th>status</th>
                                    <th>category</th>
                                    <th>Map</th>
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
        </div>
    </section>

<div id="maps" style="height:400px;"></div>

@endsection

@section('js')
    <script type = "text/javascript" src = "{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type = "text/javascript" src = "{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.js',isset($secure) ? false : false) }}"></script>

    <script>
        $(document).ready(function(){

            var id = '';
            $(document).on('click', '.callModal', function (e) {
                e.preventDefault();
                id = $(this).attr('data-id');
                $('#map').remove();
                $('<div id="map" style="height: 700px;"></div>').appendTo('#result');
            });

            $("#btn_submit").click(function (e) {
                e.preventDefault();

                var from_data = $('input[name=from_date]').val();
                var to_date = $('input[name=to_date]').val();
                var all = $('input[name=all]').val();

                if(typeof from_data != 'undefined' && isNaN(from_data) && typeof to_date != 'undefined' && isNaN(to_date)) {

                    $.ajax({
                        type: 'get',
                        url: 'http://traccar.figix.asia/api/reports/route?deviceId=' + id + '&from='+from_data+'&to='+to_date+' ',
                        headers: {
                            "Authorization": "Basic " + btoa('admin' + ":" + 'admin123456')
                        },
                        crossDomain: true,
                        dataType: "json",
                        beforeSend:function(){

                            call_and_delete_Loading('#loading','Loading....');
                            $('input[type=submit]').attr('disabled', true);
                            $('#map').remove();
                            $('<div id="map" style="height: 700px;"></div>').appendTo('#result');
                        },
                        success: function (data, status) {
                            if (status == 'success' && !$.isEmptyObject(data)) {
                            call_and_delete_Loading('#loading', 'Waiting map');

                                var path = [];
                                var i = 1;
                                $.each(data, function (inx, vals) {
                                    if(isNaN(vals.address)){

                                            path.push({
                                                deviceTime  :   vals.deviceTime,
                                                lat         :   vals.latitude,
                                                lng         :   vals.longitude,
                                                adds     :   vals.address,
                                                speeds     :   vals.speed
                                            });
                                    }
                                    i++
                                });

                                google.maps.event.addDomListener(window, "load", initMap(path));
                            }
                        },error: function(requestObject, error, errorThrown) {

                            switch (requestObject.status) {
                                case 401:
                                    if(confirm('User\'s session was expired, Please click OK to login again')){
                                        window.location.href = '/user/login';
                                    }
                                    break;
                                case 500:
                                    if(confirm('Internal Server Error, Please contact your technical ')){
                                        window.location.reload()
                                    }
                                    break;
                                default:
                                    console.log(requestObject);
                            }
                        }
                    }).done(function(data, status){
                        call_and_delete_Loading('#loading', 'Successfully!!!');
                        $('input[type=submit]').attr('disabled', false);
                    });
                }
            });

            $('#call_map').on('hide.bs.modal', function () {
                $('input[type=submit]').attr('disabled', false);
                //$('#searchingForm').find("input[type=text]").val("");

            });
            $('.dpYears').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
        })
    </script>

    <script src = "http://www.google.com/jsapi"></script>
    <script async defer src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDzHP89pTvW3oT77maAqNrcWL-C9AFMqgI&callback=initMap"></script>
    <script>

        function initMap(path) {

            var mapOptions = {
                center: new google.maps.LatLng(path[0].lat, path[0].lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map"), mapOptions);
            var infoWindow = new google.maps.InfoWindow();
            var lat_lng = new Array();
            var latlngbounds = new google.maps.LatLngBounds();
            for (i = 0; i < path.length; i++) {
                var data = path[i];
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                lat_lng.push(myLatlng);
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: data.deviceTime
                });
                latlngbounds.extend(marker.position);
                (function (marker, data) {
                    google.maps.event.addListener(marker, "click", function (e) {

                        var htmls = '<div><label style="font-size: 16px;"> Address  </label> : '+ data.adds + ' </div><br/>' +
                                    '<div><label style="font-size: 16px;"> Date    </label> : ' + new Date(data.deviceTime).toISOString().slice(0,10) + '<span> Time :  '+new Date(data.deviceTime).toISOString().slice(11,20)+' </span></div><br/>'+
                                    '<div><label style="font-size: 16px;"> Speed    </label> : ' + data.speeds + '</div>'
                                ;
                        infoWindow.setContent(htmls);
                        infoWindow.open(map, marker);
                    });
                })(marker, data);
            }
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);

            var path = new google.maps.MVCArray();
            var service = new google.maps.DirectionsService();
            var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });

            for (var i = 0; i < lat_lng.length; i++) {
                if ((i + 1) < lat_lng.length) {
                    var src = lat_lng[i];
                    var des = lat_lng[i + 1];
                    path.push(src);
                    poly.setPath(path);
                    service.route({
                        origin: src,
                        destination: des,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    }, function (result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                                path.push(result.routes[0].overview_path[i]);
                            }
                        }
                    });
                }
            }
        }


    </script>
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
                url: 'http://traccar.figix.asia/api/devices',
                headers: {
                    "Authorization": "Basic " + btoa('admin' + ":" + 'admin123456')
                },
                success: function (data, status) {
                    var htmls = '';
                    $.each(data, function (inx, vals) {
                        htmls += '<tr>';
                        htmls += '<td>' + vals.name + '</td>';
                        htmls += '<td>' + vals.phone + '</td>';
                        htmls += '<td>' + vals.model + '</td>';
                        htmls += '<td>' + vals.status + '</td>';
                        htmls += '<td>' + vals.category + '</td>';
                        htmls += '<td> <a class="callModal" data-id="' + vals.id + '" href="#call_map" style="text-decoration:underline;" data-toggle="modal">{{ trans('multiple.m_view_map') }}</a> </td>';
                        htmls += '</tr>';
                    });
                    $(htmls).appendTo('#userLists');
                }
            });
        }

        function call_and_delete_Loading(loading,sms, status){
            $('body').find(loading).each(function(){
                $(this).remove();
            });
            var stat =  (status)?status:'warning';
            if(sms != undefined) {
                $('<div id="loading"></div>').appendTo('body');
                imgLoading(true,sms, 9, stat);
            }
        }

    </script>

@endsection
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection

@section('content')
     <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_holiday_management') }}
        </header>
        <div class="panel-body">
            <div class="position-center">
                <a href="{{ route('upload_sheet') }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> {{ trans('loan.l_upload_holiday_sheet') }}</a>
                <br/><br/>
                <div id="calendar"></div>
            </div>
        </div>
    </section>
@endsection   

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.2/fullcalendar.min.js"></script>
        <script>
            var holiday = <?php echo json_encode($holiday); ?>;
            $(document).ready(function() {
                $('#calendar').fullCalendar({
                
                    header: true,
                    allDaySlot:false,
                     header: {
                         right: 'prev,next today',
                         center:'title',
                         left:''
                     },

                    dayNamesShort:['SUN','MON','TUE','WED','THU','FRI','SAT'],
                    dayRender: function(date,cell){
                        var is_exists = false;
                        var dn = parseInt(date.format('DD'));
                        var cur_cal_date = (date.format('YYYY-MM-DD')).toString();
                        for(var i=0;i<holiday.length;i++){
                            var holiday_date = (holiday[i].holiday_date).toString();
                            if(holiday_date.localeCompare(cur_cal_date) == 0){
                                cell.css('background-color','#EC6459');
                                cell.append('<div class="text-center"><span class="label label-mark">Day-off</span></div>');
                                is_exists = true;
                            }
                        }
                        if(!is_exists){
                            cell.append('<div class="text-center"><span class="label label-mark">Mark Now</span></div>');
                        }
                    },
                    dayClick: function(date, allDay, jsEvent) {
                        var $cell = $(this);
                        var con = confirm("Are you sure?");
                        if(con == true){
                            var path = '<?php echo route("add_holiday"); ?>';
                            $.ajax({
                                type: "GET",
                                url : path,
                                data: { click_date : date.format("YYYY-MM-DD") },
                                success: function(data) {
                                    if(data.status == true){
                                        holiday = data.holiday;
                                        var color='#EC6459',label='Day-off';
                                        if(data.is_deleted == true){
                                            if(($cell.attr('class')).indexOf('fc-sun')>=0){
                                                color = '#EBCCD1';
                                            }else{
                                                color = '#FFF';
                                            }
                                            label = 'Mark Now';
                                        }
                                        $cell.css('background-color',color);
                                        $cell.find('.label').html(label);
                                    }
                                }
                            })
                            .done(function(data) {
                                //alert('done');
                            })
                            .fail(function(jqXHR, ajaxOptions, thrownError) {
                                //console.log(jqXHR.responseText);
                            });
                        }
                    }
                });
            });
        </script>
@endsection

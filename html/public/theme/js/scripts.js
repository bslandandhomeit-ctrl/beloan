(function ($) {
    "use strict";
    $(document).ready(function () {
        /*==Left Navigation Accordion ==*/
        if ($.fn.dcAccordion) {
            $('#nav-accordion').dcAccordion({
                eventType: 'click',
                autoClose: true,
                saveState: true,
                disableLink: true,
                speed: 'slow',
                showCount: false,
                autoExpand: true,
                classExpand: 'dcjq-current-parent'
            });
        }
        /*==Slim Scroll ==*/
        if ($.fn.slimScroll) {
            $('.event-list').slimscroll({
                height: '305px',
                wheelStep: 20
            });
            $('.conversation-list').slimscroll({
                height: '360px',
                wheelStep: 35
            });
            $('.to-do-list').slimscroll({
                height: '300px',
                wheelStep: 35
            });
        }
        /*==Nice Scroll ==*/
        if ($.fn.niceScroll) {


            $(".leftside-navigation").niceScroll({
                cursorcolor: "#1FB5AD",
                cursorborder: "0px solid #fff",
                cursorborderradius: "0px",
                cursorwidth: "3px"
            });

            $(".leftside-navigation").getNiceScroll().resize();
            if ($('#sidebar').hasClass('hide-left-bar')) {
                $(".leftside-navigation").getNiceScroll().hide();
            }
            $(".leftside-navigation").getNiceScroll().show();

            $(".right-stat-bar").niceScroll({
                cursorcolor: "#1FB5AD",
                cursorborder: "0px solid #fff",
                cursorborderradius: "0px",
                cursorwidth: "3px"
            });

        }


        /*==Collapsible==*/
        $('.widget-head').click(function (e) {
            var widgetElem = $(this).children('.widget-collapse').children('i');

            $(this)
                .next('.widget-container')
                .slideToggle('slow');
            if ($(widgetElem).hasClass('ico-minus')) {
                $(widgetElem).removeClass('ico-minus');
                $(widgetElem).addClass('ico-plus');
            } else {
                $(widgetElem).removeClass('ico-plus');
                $(widgetElem).addClass('ico-minus');
            }
            e.preventDefault();
        });


        /*==Sidebar Toggle==*/

        $(".leftside-navigation .sub-menu > a").click(function () {
            var o = ($(this).offset());
            var diff = 80 - o.top;
            if (diff > 0)
                $(".leftside-navigation").scrollTo("-=" + Math.abs(diff), 500);
            else
                $(".leftside-navigation").scrollTo("+=" + Math.abs(diff), 500);
        });


        $('.sidebar-toggle-box .fa-bars').click(function (e) {

            $(".leftside-navigation").niceScroll({
                cursorcolor: "#1FB5AD",
                cursorborder: "0px solid #fff",
                cursorborderradius: "0px",
                cursorwidth: "3px"
            });

            $('#sidebar').toggleClass('hide-left-bar');
            if ($('#sidebar').hasClass('hide-left-bar')) {
                $(".leftside-navigation").getNiceScroll().hide();
            }
            $(".leftside-navigation").getNiceScroll().show();
            $('#main-content').toggleClass('merge-left');
            e.stopPropagation();
            if ($('#container').hasClass('open-right-panel')) {
                $('#container').removeClass('open-right-panel')
            }
            if ($('.right-sidebar').hasClass('open-right-bar')) {
                $('.right-sidebar').removeClass('open-right-bar')
            }

            if ($('.header').hasClass('merge-header')) {
                $('.header').removeClass('merge-header')
            }


        });
        $('.toggle-right-box .fa-bars').click(function (e) {
            $('#container').toggleClass('open-right-panel');
            $('.right-sidebar').toggleClass('open-right-bar');
            $('.header').toggleClass('merge-header');

            e.stopPropagation();
        });

        $('.header,#main-content,#sidebar').click(function () {
            if ($('#container').hasClass('open-right-panel')) {
                $('#container').removeClass('open-right-panel')
            }
            if ($('.right-sidebar').hasClass('open-right-bar')) {
                $('.right-sidebar').removeClass('open-right-bar')
            }

            if ($('.header').hasClass('merge-header')) {
                $('.header').removeClass('merge-header')
            }


        });


        $('.panel .tools .fa').click(function () {
            var el = $(this).parents(".panel").children(".panel-body");
            if ($(this).hasClass("fa-chevron-down")) {
                $(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
                el.slideUp(200);
            } else {
                $(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
                el.slideDown(200);
            }
        });


        $('.panel .tools .fa-times').click(function () {
            $(this).parents(".panel").parent().remove();
        });

        // tool tips

        $('.tooltips').tooltip();

        // popovers

        $('.popovers').popover();

        $('#more-notify').on('click', function (e) {
            var page = $(this).attr('data-page');
            load_notify(page);
        });
        function load_notify(page) {
            $.ajax({
                url: "/login/notify?page=" + page,
                success: function (data) {
                    $('ul.notify-login li:last-child').remove();
                    $('ul.notify-login').append(data);
                    $('#more-notify').on('click', function (e) {
                        load_notify();
                    });
                }
            });
        }

        Notification();
    });

})(jQuery);

function closeMe() {
    if (confirm("Are you sure will close this window?")) {
        close();
    }
}
/**
 * For loading images and I will create another option
 * @param load
 *
 * Status: Under implementation
 * sms to send messages
 * closeTime to delete alert
 * loadTime time for loading
 * localted at:/public/theme/js
 */
function imgLoading(load, sms, times, types) {

    var intimes = times*100;
    var uptimes = times*1000;
    var classs = '';

    if (load == true) {
        if(types == 'success'){

            uptimes = times*1000;
            intimes = times*500;
            classs = 'alert-success';
        }if(types === 'danger'){
            classs = 'alert-danger';
        }
        else{
            classs = 'alert-warning';
        }
        $("#loading").css({"display": "block", "text-align": "center", "position": "fixed", "right": "3%", "z-index": "10000", "top": "10%","width":"auto","min-width":"10%" ,"box-shadow":"rgba(105, 102, 102, 0.27) 1px 1px 2px 3px","border-radius":'5px'});
        $('<div class="alert '+classs+' alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + sms + '</div>').appendTo("#loading");
        $("#loading").fadeTo(uptimes, intimes).slideUp(intimes, function () {
           // $("#loading").empty();
        });
    }else{
        //$("#loading").empty();
        //return false;
    }
}

function Notification() {

    $.ajax({
        url: '/js/load/notification/notification.js',
        method: 'get',
        dataType: 'script',
        success: function (data, status) {
            if (status == 'success') {
            }else{
                imgLoading(true,"Fail!!!! refresh your page. Errors Type:" + status, 5, status);
            }
        },
        error: function (jgxht, status, errorthrogh) {
            imgLoading(true, "Fail!!!! refresh your page. Errors Type:" + status, 5, status);
        }
    });
}
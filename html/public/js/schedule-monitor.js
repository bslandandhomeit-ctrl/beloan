var Schedule = function()
{
    this.schedule = true;
    this.r_search = false;
    this.d_search = false;
    this.first_detail = false;
    this.$loading = $("#loading");
    this.init();
};
$.extend(Schedule.prototype,{
    init:function(){
        var self = this;
        self.bindEvent();
        self.$loading.hide();
    },
    bindEvent: function(){
        var self = this;
        $("#schedule-loan").on('click',function(e){
            var url = $("#url").val();
            if(self.schedule == true){
                self.r_search = true;
                self.$loading.show();
                var data = {
                    contract_id:  $("#contract_id").val() ,
                    client_name: $("#client_name").val() ,
                    phone: $("#phone").val(),
                    city : $("#address").val(),
                    date : $("#date").val(),
                    is_schedule: 0,
                    offset : $("input[name='offset']").val(),
                };
                $.ajax({
                    url: url,
                    type: 'get',
                    data: data,
                    success:function(data){
                        $("#summary").html(data);
                        $(".header-title").html('SCHEDULE FOR'+ '&nbsp;' + $("#date").val());
                        self.bindEventAnchor();
                        self.$loading.hide();
                    },
                    error:function(xhr, textStatus, errorThrown){
                        $("#summary").html();
                        self.$loading.hide();
                    }
                });
            }else if(self.schedule == false){
                self.d_search = true;
                self.$loading.show();
                var data = {
                    contract_id:  $("#contract_id").val(),
                    client_name: $("#client_name").val(),
                    phone: $("#phone").val(),
                    city : $("#address").val(),
                    date : $("#date").val(),
                    is_schedule: 1,
                    offset : $("input[name='offset']").val(),
                };
                $.ajax({
                    url: url,
                    type: 'get',
                    data: data,
                    success:function(data){
                        $("#detail_summary").html(data);
                        $(".header-title").html('SCHEDULE FOR'+ '&nbsp;' + $("#date").val());
                        self.bindEventAnchor();
                        self.$loading.hide();
                    },
                    error:function(xhr, textStatus, errorThrown){
                        $("#detail_summary").html();
                        self.$loading.hide();
                    }
                })
            }
        });
        $('a[data-toggle="tab"]').on('shown.bs.tab',function(e) {
            var target = $(e.target).attr("href");
            if(target == "#summary"){
                self.schedule = true;
            }else if("#detail_summary"){
                var url = $("#url").val();
                self.schedule = false;
                if(self.first_detail == false){
                    var data = {
                        is_schedule: 1
                    };
                    $.ajax({
                        url: url,
                        type:'get',
                        data: data,
                        success:function(data){
                            self.first_detail = true;
                            $("#detail_summary").html(data);
                            $(".header-title").html('SCHEDULE FOR'+ '&nbsp;' + $("#date").val());
                            self.bindEventAnchor();
                        }
                    });
                }
            }
        });
        $("ul.pagination li a").on('click',function(e){
            e.preventDefault();
            self.pagination($(this));
        });
    },
    pagination:function(target){
        var self = this;
        var url = target.attr('href');
        if(self.schedule == true){
            var data = {
                is_schedule: 0
            };
            if(self.r_search == true){
                $.extend(data,{
                    contract_id: $("#contract_id").val(),
                    client_name: $("#client_name").val(),
                    phone: $("#phone").val(),
                    city : $("#address").val(),
                    date : $("#date").val(),
                    offset : $("input[name='offset']").val(),
                });
            }
            $.ajax({
                url: url,
                data: data,
                success: function(data) {
                    $('#summary').html(data);
                    $(".header-title").html('SCHEDULE FOR'+ '&nbsp;' + $("#date").val());
                    self.bindEventAnchor();
                }
            });
        }else{
            var data = {
                is_schedule: 1
            };
            if(self.d_search == true){
                $.extend(data,{
                    contract_id: $("#contract_id").val(),
                    client_name: $("#client_name").val(),
                    phone: $("#phone").val(),
                    city : $("#address").val(),
                    date : $("#date").val(),
                    offset : $("input[name='offset']").val(),
                });
            }
            $.ajax({
                url: url,
                data: data,
                success: function(data) {
                    $('#detail_summary').html(data);
                    $(".header-title").html('SCHEDULE FOR'+ '&nbsp;' + $("#date").val());
                    self.bindEventAnchor();
                }
            });
        }
    },
    bindEventAnchor: function()
    {
        var self = this;
        $("ul.pagination li a").on('click',function(e){
            e.preventDefault();
            self.pagination($(this));
        });
    }
});
$(document).ready(function(){
    new Schedule();
});
var Irregular = function()
{
    this.repayment = true;
    this.first_detail = false;
    this.r_search = false;
    this.d_search = false;
    this.$loading = $("#loading");
    this.init();
};
$.extend(Irregular.prototype,{
    init:function(){
        var self = this;
        self.bindEvent();
        self.$loading.hide();
    },
    bindEvent: function () {
        var self = this;
        $("#search-loan").on('click',function(e){
            var url = $("#url-name").val();
            if(self.repayment == true){
                self.r_search = true;
                self.$loading.show();
                var data = {
                    contract_id: ($("#contract_id").val() != '') ? $("#contract_id").val() : '',
                    client_name: $("#client_name").val() != '' ? $("#client_name").val() : '',
                    phone: $("#phone").val() != '' ?  $("#phone").val() : '',
                    branch : $("#selBrand").val() != '' ? $("#selBrand").val() : '',
                    is_detail: 0,
                    co: $('#co').val()
                };
                $.ajax({
                    url: url,
                    type:'get',
                    data: data,
                    success:function(data){
                        $("#irregular").html(data);
                        self.bindEventAnchor();
                        self.$loading.hide();
						dosorting();
                    },
                    error:function(xhr, textStatus, errorThrown){
                        $("#irregular").html();
                        self.$loading.hide();
                    }
                });
            }else if(self.repayment == false){
                self.d_search = true;
                self.$loading.show();
                var data = {
                    contract_id: ($("#contract_id").val() != '') ? $("#contract_id").val() : '',
                    client_name: $("#client_name").val() != '' ? $("#client_name").val() : '',
                    phone: $("#phone").val() != '' ?  $("#phone").val() : '',
                    branch : $("#selBrand").val() != '' ? $("#selBrand").val() : '',
                    is_detail: 1,
                    co: $('#co').val()
                };
                $.ajax({
                    url: url,
                    type:'get',
                    data: data,
                    success:function(data){
                        $("#detail_irregular").html(data);
                        self.bindEventAnchor();
                        self.$loading.hide();
						do_sorting();
                    },
                    error:function(xhr, textStatus, errorThrown){
                        $("#detail_irregular").html();
                        self.$loading.hide();
                    }
                });
            }
        });
        $('#myModal').on('show.bs.modal', function (e) {
            var zindex = parseInt($('#myModal').css("z-index")) + 10;
            $(document.body).find('div.datepicker').first().css("z-index", zindex);
        });
        $("body").on('click','.btn-action',function(e){
            e.preventDefault();
            self.showModal($(this));
        });
        $('#save').on('click',function(event){
            event.preventDefault();
            var url_id = $('#getID').val();
            var url = $("#url-name").val();
            $.ajax({
                url: url+"/"+url_id,
                type: 'post',
                data:{
                    _token: $('#_token').val(),
                    reason: $('#reason').val(),
                    action_taken: $('#action_taken').val(),
                    todo_payment: $('#todo_payment').val()
                },
                success: function(data){
                    if(data.status == true){
                        $("#myModal").modal("toggle");
                        $("td#reason-"+url_id).html(data.result.reason);
                        $("td#action_taken-"+url_id).html(data.result.action_taken);
                        $("td#todo_payment-"+url_id).html(data.result.todo_payment);
                        $("td#rea-"+url_id).html(data.result.reason);
                        $("td#action-"+url_id).html(data.result.action_taken);
                        $("td#todo-"+url_id).html(data.result.todo_payment);
                    }
                },
                error: function(err1, err2, err3){

                }
            });
        });
        $('a[data-toggle="tab"]').on('shown.bs.tab',function(e) {
            var target = $(e.target).attr("href");
            if(target == "#irregular"){
                self.repayment = true;
            }else if("#detail_irregular"){
                var url = $("#url-name").val();
                self.repayment = false;
                if(self.first_detail == false){
                    var data = {
                        is_detail: 1
                    };
                    $.ajax({
                        url: url,
                        type:'get',
                        data: data,
                        success:function(data){
                            self.first_detail = true;
                            $("#detail_irregular").html(data);
                            self.bindEventAnchor();
							do_sorting();
                        }
                    });
                }
            }
        });
        $("body").on('click','ul.pagination li a',function(e){
            e.preventDefault();
            self.pagination($(this));
        });
    },
    pagination:function(target){
        var self = this;
        var url = target.attr('href');
        var page = url.split('page=')[1];
        var offset = url.split('offset=')[1];
        if(self.repayment == true){
            var data = {
                is_detail: 0
            };
            if(self.r_search == true){
                $.extend(data,{
                    contract_id: $("#contract_id").val(),
                    client_name: $("#client_name").val(),
                    phone: $("#phone").val(),
                    branch : $("#selBrand").val()
                });
            }
            $.ajax({
                url: '?page='+page+'&offset='+offset,
                type: 'get',
                data: data,
                success: function(data) {
                    $('#irregular').html(data);
                    self.bindEventAnchor();
                }
            });
        }else{
            var data = {
                is_detail: 1
            };
            if(self.d_search == true){
                $.extend(data,{
                    contract_id: $("#contract_id").val(),
                    client_name: $("#client_name").val(),
                    phone: $("#phone").val(),
                    branch : $("#selBrand").val()
                });
            }
            $.ajax({
                url: '?page='+page+'&offset='+offset,
                data: data,
                success: function(data) {
                    $('#detail_irregular').html(data);
                    self.bindEventAnchor();
					do_sorting();
                }
            });
        }
    },
    showModal: function(target){
        var id = target.attr('data-id');
        var parentElement = target.parent("td").parent("tr");
        var reason = parentElement.children("td#reason-"+id).text();
        var action_taken = parentElement.children("td#action_taken-"+id).text();
        var todo_payment = parentElement.children("td#todo_payment-"+id).text();
        $('#reason').val(reason);
        $('#action_taken').val(action_taken);
        $('#todo_payment').val(todo_payment);
        $('.todo').datepicker("update",todo_payment);
        $('#getID').val(id);
        $("#myModal").modal('show');
    },
    bindEventAnchor: function()
    {
       /* var self = this;
        $("ul.pagination li a").on('click',function(e){
            e.preventDefault();
            self.pagination($(this));
        });
        $(".btn-action").on('click',function(e){
            e.preventDefault();
            self.showModal($(this));
        });*/
    }
});
$(document).ready(function(){
    new Irregular();

    $('.todo').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
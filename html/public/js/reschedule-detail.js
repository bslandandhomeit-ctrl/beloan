var Loan = function(){
    this.activeTabdoc = false;
    this.repaySchedule = false;
    this.actualRepay = false;
    this.trans = false;
    this.guarantor =  false;
    this.co_borrower = false;
    this.charge = false;
    this.loanpay = false;
    this.schedule_fee = false;
    this.$tab =  $('a[data-toggle="tab"]');
    this.$loading = $("#loading");
    this.map = null;
    this.marker = null;
    this.$modal = $("#map");
    this.init();
};
$.extend(Loan.prototype,{

    init: function(){
        var self = this;
        self.bindEvent();
        self.createMap();
        self.$loading.hide();
    },
    bindEvent: function () {
        var self =  this;
        self.$tab.on('shown.bs.tab',function(e) {
            var target = $(e.target).attr("href");
            var token = $("#token").val();
            var id = $("#loan-id").val();
            if (target == "#schedule") {
                if(self.repaySchedule == false){
                    var repay = $("#repayment-data").val();
                    if(repay != '') {
                        var data = $.parseJSON(repay);
                        $.extend(data, {_token: token});
                        self.$loading.show();
                        var url = '/api/reschedule/reschedule/' + id;
                        //console.log(data);
                        self.repaymentSchedule(url, data, $("#schedule"));
                    }
                }
            }else if(target == "#actual"){
                if(self.actualRepay ==  false){
                    self.$loading.show();
                    var url = '/api/loan/reactual/'+id;
                    var data = {_token: token};
                    self.actualRepayment(url,data,$("#actual"));
                }
            }else if(target == "#loandoc"){
                if(self.activeTabdoc == false){
                    self.$loading.show();
                    var url = '/api/loan/document/'+id;
                    var data = {_token: token};
                    self.loanDocument(url,data,$("#loandoc"));
                }
            }else if(target == "#charge"){
                if(self.charge == false){
                    self.$loading.show();
                    var url = '/api/loan/charge/'+id;
                    var data = {_token: token};
                    self.loanCharge(url,data,$("#charge"));
                }
            }else if(target == "#guarantor"){
                if(self.guarantor == false){
                    self.$loading.show();
                    var url = '/api/loan/guarantor/'+id;
                    var data = {_token: token};
                    self.loanGuarantor(url,data,$("#guarantor"));
                }
            }else if(target == "#co_borrower"){
                if(self.co_borrower == false){
                    self.$loading.show();
                    var url = '/api/loan/co-borrower/'+id;
                    var data = {_token: token};
                    self.coBorrower(url,data,$("#co_borrower"));
                }
            }else if(target == "#loanpay"){
                if(self.loanpay == false){
                    self.$loading.show();
                    var url = '/api/loan/todaypay/'+id;
                    var data = {_token: token};
                    self.loanPayToday(url,data,$("#loanpay"));
                }
            }else if(target == "#transaction"){
                if(self.trans == false){
                    self.$loading.show();
                    var url = '/api/loan/transaction/'+id;
                    var data = {_token: token};
                    console.log(data);
                    self.transaction(url,data,$("#transaction"));
                }
            }else if(target == "#schedule_fee"){
                if(self.schedule_fee == false){
                    self.$loading.show();
                    var url = '/api/loan/schedule_fee/'+id;
                    var data = {_token: token};
                    self.scheduleFee(url,data,$("#schedule_fee"));
                }
            }
        });
        self.$modal.on('shown.bs.modal', function () {
            google.maps.event.trigger(self.map, 'resize');
            self.map.panTo(self.marker.getPosition());
        });
    },
    repaymentSchedule: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.repaySchedule = true;
                self.$loading.hide();
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    actualRepayment: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                //console.log(resData);
                content.html(resData);
                self.actualRepay = true;
                self.$loading.hide();
                $(".repayment-receipt").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    transaction: function(url,data,content){
        var self = this;
        console.log("URL:"+url);
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.trans = true;
                self.$loading.hide();
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    loanDocument: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.activeTabdoc = true;
                self.$loading.hide();
                $(".photo-popup").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    loanGuarantor: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.guarantor = true;
                self.$loading.hide();
                $(".guarantor-signature").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
                $(".guarantor-collateral").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
                $('.view-map').on('click',function(e){
                    e.preventDefault();
                    var lat = $(this).attr('data-lat');
                    var long = $(this).attr('data-long');
                    self.loadMap(lat,long);
                });
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    coBorrower: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.co_borrower = true;
                self.$loading.hide();
                $(".guarantor-signature").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
                $(".guarantor-collateral").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
                $('.view-map').on('click',function(e){
                    e.preventDefault();
                    var lat = $(this).attr('data-lat');
                    var long = $(this).attr('data-long');
                    self.loadMap(lat,long);
                });
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    loanCharge: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.charge = true;
                self.$loading.hide();
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    loanPayToday: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.loanpay = true;
                self.$loading.hide();
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
    createMap: function(){
        var self =  this;
        var myCenter = new google.maps.LatLng(parseFloat(11.5793643), parseFloat(104.8901867));
        var mapProp = {
            center:myCenter,
            zoom:15,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        self.map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        self.marker = new google.maps.Marker({
            position:myCenter
        });
        self.marker.setMap(self.map);
    },
    loadMap: function(lat,long){
        var self =  this;
        var myCenter = new google.maps.LatLng(parseFloat(lat), parseFloat(long));
        self.map.setCenter(myCenter);
        self.marker.setPosition(myCenter);
        self.$modal.modal('show');
    },
    scheduleFee: function(url,data,content){
        var self = this;
        $.ajax({
            url: url,
            type:'POST',
            data: data,
            success:function(resData){
                content.html(resData);
                self.schedule_fee = true;
                self.$loading.hide();
            },
            error:function(xhr, textStatus, errorThrown){
                content.html('');
                self.$loading.hide();
            }
        });
    },
});
$(document).ready(function(){
    var loan = new Loan();
    $("#client-signature").magnificPopup({type: 'image',image:{cursor:'mfp-auto-cursor'}});
    $('.view-map').on('click',function(e){
        e.preventDefault();
        var lat = $(this).attr('data-lat');
        var long = $(this).attr('data-long');
        loan.loadMap(lat,long);
    });
});

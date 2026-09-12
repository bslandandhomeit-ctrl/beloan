$(document).ready(function(){
//console.log("----",Math.round((4.74 + 99807.85)*100)/100 );
    function ajaxSet(url, data, id, mid){
        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            data: data,
            cache:false,
            success: function(data)
            {
                if(data.status == true){
                   $(mid).modal("toggle");
                   $(id).append($("<option></option>").attr("value", data.insertId).text(data.insertLabel));
                    $(id + " option[value="+ data.insertId + "]").prop('selected', true);

                }else{
                    alert("Error in saving.");
                }
            },
            error: function(xhr, textStatus, errorThrown)
            {
                //console.log(xhr.responseText);
            }
        });
    }
	 $("#frm-product").validate({
        rules:{
            ipPrice:{
            	required: true,
            	number: true
            },
            selCate:{
                required: true
            }
        },
        messages:{
            ipPrice:{
            	required: "Price is required",
            	number: "Please enter only number."
            },
            selCate:{
                required: "Select a Product Category."
            }
        }
    });

    $("#frmCategory").validate({
        rules:{
            ipCate:{
                required: true,
                minlength: 3
            }
        },
        messages:{
            ipCate:{
                required: "Category is required."
            }
        },
        submitHandler: function(form){
            var data = {
                        ipCate: $('#ipCate').val(),
                        ipDesc: $('#ipDesc').val(),
                        _token: $('#frmCategory #_token').val()
            };
            ajaxSet('/category/add', data, "#selCate", "#mCate");
            return false;
        }
    });

    $("#form_prod_prod_type").validate({
        rules:{
            type:{
                required: true,
                minlength: 3
            }
        },
        messages:{
            type:{
                required: "Product type is required."
            }
        },
        submitHandler: function(form){
            var data = {
                        type: $('#type').val(),
                        desc: $('#Desc').val(),
                        _token: $('#form_prod_prod_type #_token').val()
            };
            ajaxSet('/product/add_product_type', data, "#seltype", "#prod_prod_type");
            return false;
        }
    });

      $("#frmBrand").validate({
        rules:{
            ipBrand:{
                required: true,
                minlength: 3
            }
        },
        messages:{
            ipBrand:{
                required: "Brand is required."
            }
        },
        submitHandler: function(){
            var data = {
                        ipBrand: $('#ipBrand').val(),
                        ipDesc: $('#ipDesc').val(),
                        _token: $('#frmBrand #_token').val()
            };

            ajaxSet('/brand/add', data, "#selBrand", "#mBrand");
            return false;
        }
    });

    $("#frmDealer").validate({
        rules:{
            dealer:{
                required: true,
                minlength: 3
            },
            phone:{
                required: true
            },
            photo:{
                filesize:1048576
            }
        },
        messages:{
            dealer:{
                required: 'Dealer Name is required.'
            },
            phone:{
                required: 'Phone is required.'
            }
        }
    });

    $("#staffForm").validate({
        rules:{
            name:{
                required: true,
                minlength: 3
            },
            phone:{
                required: true
            },
            photo:{
                filesize:1048576
            },
            br:{
                required: true,
            },
            ro:{
                required: true,
            },
            salary:{
                required: true,
            },
            start_date:{
                required: true,
            }
        },
        messages:{
            name:{
                required: 'Staff Name is required.'
            },
            phone:{
                required: 'Phone is required.'
            },
            br:{
                required: 'Branch is required'
            },
            ro:{
                required: 'Role is required',
            },
            salary:{
                required: 'Salary is required',
            },
            start_date:{
                required: 'Start Date is required',
            }
        }
    });

    //$("#s2id_e9").hide();
    $('#inputgroup').hide();
    $("#btnAdd").on("click", function(){
        $(this).toggleClass("fa-minus-square-o");
        //$("#s2id_e9").toggle();
        $('#inputgroup').toggle();
        $('#e9').select2('data', null);
    });

    $("#fileupload").validate({
        rules:{
            ipDealer:{
                required: true
            },
            selBank:{
                required: true
            },
            selSender:{
                required: true
            },
            dpDisbursement:{
                required: true
            },
            dpTransfer:{
                required: true
            },
            ipDisburseAmount:{
                required: true
            },
            ipTransferAmount:{
                required: true
            }
        },
        messages:{
            ipDealer:{
                required: "Dealer Name is required."
            },
            selBank:{
                required: "Bank Account is required."
            },
            selSender:{
                required: "Sender is required."
            },
            dpDisbursement:{
                required: "Disbursement Date is required."
            },
            dpTransfer:{
                required: "Transfer Date is required."
            },
            ipDisburseAmount:{
                required: "Disbursement Amount is required."
            },
            ipTransferAmount:{
                required: "Transfer Amount is required."
            }
        }
    });
    $('#frmJournal').validate({
        rules:{
          'branch':{
            required:true
          },
            'selTran[]':{
                required: true
            },
            'selType[]':{
                required: true
            },
            'ipDebit[]':{
                required: true,
                number: true
            },
            'ipCredit[]':{
                required: true,
                number: true
            },
            'txtDesc[]':{
                required: true
            }

        },
        messages:{
          'branch':{
            required:""
          },
            'selTran[]':{
                required: ""
            },
            'selType[]':{
                required: ""
            },
            'ipDebit[]':{
                required: "",
                number : ""
            },
            'ipCredit[]':{
                required: "",
                number : ""
            },
            'txtDesc[]':{
                required: ""
            }
        },
        submitHandler: function(frm, e){
            e.preventDefault();
            var $debits = $('input[name="ipDebit[]"]'),
                $credits = $('input[name="ipCredit[]"]'),
                $account_code = $('select[name="selType[]"]'),
                t_debit = 0.0,
                t_credit = 0.0,
                b1 = false, b2 = false,temp='',item=[];
//console.log($debits); console.log($credits);
            $account_code.each(function(){
                item.push($(this).val());
            });
/*            for(var i=0;i<item.length;i++){
                for(var j=i+1;j<item.length;j++){
                    if(item[i]==item[j]){
                        b1 = true;
                        break;
                    }
                }
            }
*/
            $.each($debits,function(k,v){
//              console.log("sss = " ,v.value);
                t_debit = Math.round(t_debit * 10000)/ 10000 + Math.round(parseFloat(v.value)*10000)/10000;//Math.round($(this).val()*10000)/10000;
                if(t_debit - Math.round(t_debit*10000)/10000  != 0){
                  t_debit = Math.round(t_debit * 10000)/ 10000;
                }
//                console.log('debit:'+t_debit);
            });
            $.each($credits,function(k,v){
//              console.log("sss1 = " ,v.value);
                t_credit = Math.round(t_credit * 10000)/ 10000 + Math.round(parseFloat(v.value)*10000)/10000;//Math.round($(this).val()*10000)/10000;
                if(t_credit - Math.round(t_credit*10000)/10000  != 0){
                  t_credit = Math.round(t_credit * 10000)/ 10000;
                }
//                console.log('credit:'+t_credit);
            });
            if(b1 == true){
                alert("Account number must not be the same.");
            }
            if((t_debit - t_credit)!= 0){
                b2 = true;
                alert("Total debit substract total credit must be equal zero(0)");
            }

            if(b1 == false && b2 == false){
                frm.submit();
            }

            return false;
        }
    });
    $("#frmBankAcc").validate({
        rules:{
            bank_name:{
                required: true
            },
            account_name:{
                required: true
            },
            account_number:{
                required: true
            }
        },
        messages:{
            bank_name:{
                required: "Bank Name is required."
            },
            account_name:{
                required: "Account Name is required."
            },
            account_number:{
                required:"Account Number is required."
            }
        },
        submitHandler:function(){
            var data = { _token: $('#frmBankAcc #_token').val(),
                        bank_name: $('#bank_name').val(),
                        account_name: $('#account_name').val(),
                        account_number: $('#account_number').val()
                        };
            $.ajax({
                url: '/bank/ajax_add',
                type: 'POST',
                dataType: "json",
                data: data,
                cache:false,
                success: function(data)
                {
                    if(data.status == true){
                        var lbText = data.bank.account_name + ' (' + data.bank.account_number + ')';
                        $('#addBank').modal("toggle");
                        $('#opt').append($("<option></option>").attr("value", data.id).text(lbText));
                        var arr = [];
                        var arr_obj = $('#e9').select2('data');
                        for(var i=0;i<arr_obj.length;i++){
                            arr.push({
                                id:arr_obj[i].id,
                                text:arr_obj[i].text
                            });
                        }
                        arr.push({id:data.id, text:lbText});
                        $('#e9').select2("data",arr);

                    }else{
                        alert("Error in saving.");
                    }
                },
                error: function(xhr, textStatus, errorThrown)
                {
                    console.log(xhr.responseText);
                }
            });
            return false;
        }
    });
    $("#frmAccount").validate({
        rules:{
            'account_code[]':{
                required: true,
                isValidDigit:true,
                remote:{
                    url: '/accounting/check/account/0',
                    type: "GET"
                }
            },
            'category[]':{
                required: true
            },
            'sub_category[]':{
                required: true
            },
            'type[]':{
                required: true
            },
            'account_name[]':{
                required: true
            }
        },
        messages:{
            'account_code[]':{
                required: "Account code is required.",
                remote: "This account code is already taken.",
                isValidDigit:"Account code should be in 5 digits."
            },
            'category[]':{
                required: "Category is required."
            },
            'sub_category[]':{
                required:"Sub-category is required."
            },
            'type[]':{
                required:"Type is required."
            },
            'account_name[]':{
                required:"Account name in required."
            }
        },
        submitHandler: function(frm,e){
            e.preventDefault();
            var $codes = $('input[name="account_code[]"]');
            var arr = Array(), b = false;
            $codes.each(function(){
                arr.push($(this).val());
            });

            for(var i=0;i<arr.length;i++){
                for(var j=i+1;j<arr.length;j++){
                    if(arr[i]==arr[j]){
                        b= true;
                        break;
                    }
                }
            }
            if(b == true){
                alert("Account number cannot be the same.");
            }else{
                frm.submit();
            }
            return false;
        }
    });

    $("#frmEditAccount").validate({
        rules:{
            account_code:{
                required: true,
                isValidDigit:true,
                remote:{
                    url: '/accounting/check/account/1',
                    type: "GET",
                    data:{
                        account_code: $("#account_code").val(),
                        code: $("#code").val()
                    }
                }
            },
            category:{
                required: true
            },
            sub_category:{
                required: true
            },
            type:{
                required: true
            },
            account_name:{
                required: true
            }
        },
        messages:{
            account_code:{
                required: "Account code is required.",
                remote: "This account code is already taken.",
                isValidDigit:"Account code should be in 5 digits."
            },
            category:{
                required: "Category is required."
            },
            sub_category:{
                required:"Sub-category is required."
            },
            type:{
                required:"Type is required."
            },
            account_name:{
                required:"Account name in required."
            }
        }
    });

    $("#frmCurrency").validate({
        rules:{
            from_currency:{
                required: true
            },
            to_currency:{
                required: true
            },
            unit:{
                required: true
            },
            ask_rate:{
                required: true
            },
            bid_rate:{
                required: true
            }
        },
        messages:{
            from_currency:{
                required: "Primary Currency is required."
            },
            to_currency:{
                required: "Secondary Currency is required."
            },
            unit:{
                required: "Currency Unit is required."
            },
            ask_rate:{
                required: "Ask Rate is required."
            },
            bid_rate:{
                required: "Bid Rate is required."
            }
        }
    });
    // $("#btnBank").on("click", function(){
    //     if($("#e9").val()==null){
    //         if(!$("#e9").next("label").length){
    //             $("#e9").after("<label class='error' for='e9'>This field is required.</label>");
    //         }
    //     }else{
    //         $("#e9").next("label").remove();
    //         $.ajax({
    //             url: '/dealer/bank',
    //             type: 'GET',
    //             dataType: "json",
    //             data: { d_id : $("#d_id").val(), banks : $("#e9").val() },
    //             success: function(data)
    //             {
    //                 if(data.status == true){
    //                     $("#e9").after("<label class='text-success'>Successfully saved.</label>");
    //                         setTimeout(function() {
    //                             $('#e9').next("label").fadeOut().remove();
    //                         }, 2000);
    //                 }else{
    //                    setTimeout(function() {
    //                         $('#e9').next("label").fadeOut().remove();
    //                     }, 2000);
    //                 }
    //             },
    //             error: function(xhr, textStatus, errorThrown)
    //             {
    //                 console.log("Error: " + errorThrown);
    //             }
    //         });
    //     }
    // });

});

$.validator.prototype.checkForm = function(){
    this.prepareForm();
    for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
        if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
            for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                this.check( this.findByName( elements[i].name )[cnt] );
            }
        } else {
            this.check( elements[i] );
        }
    }
    return this.valid();
};
$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes)
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param)
},"File size must be not greater than 1MB");

$.validator.addMethod('isValidDigit',function(value){
    var contract_id_pattern = /^[1-9][0-9]{4}$/;
    if(value.match(contract_id_pattern)){
        var str = value.substr(1,(value.length - 1));
        if(Number(str) > 0)
            return true;
    }
    return false;
},'Please enter a valid digit');

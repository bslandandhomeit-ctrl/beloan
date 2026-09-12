
    $(document).ready(function(){
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
    });

    $("#addBtn").on('click', function(){
        i++;  
        if(i < 3){
            document.getElementById('removeElement_'+ i +'');
        }
        else{
            var disableElement = document.getElementById('addBtn');
                disableElement.disabled = "disabled";
        }
        function create() {


                var htmlStr = '<div id="removeElement_'+ i +'">' +
                                '<div class="col-sm-12">' +
                                    '<h5>ADD COLLATERAL</h5>' +
                                    '<hr/>' +
                                '</div>' +
                            '<div class="col-sm-6">' +
                                '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">Collateral Type <span class="red">*</span></label>' +
                                        '<div class="col-md-8">' + 
                                        '<select name="collateral_type[]" class="form-control" id="collateral_type'+ i +'" required>' +
                                            '<option value="">Select Collateral Type</option>';
                                            for(var key in getStatic.collateral_type){
                                                htmlStr += '<option value="'+getStatic.collateral_type[key]+'">'+getStatic.collateral_type[key]+'</option>';
                                            }
                                    htmlStr += '</select>' +
                                    '</div>' + 
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">Collateral Number <span class="red">*</span></label>' +
                                        '<div class="col-md-8">' + 
                                        '<input type="text" placeholder="Enter collateral number" class="form-control collateral_no" name="collateral_no[]" id="collateral_no'+ i +'" required/>' +
                                    '</div>' + 
                                '</div>' +                            
                                '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">Collateral Value <span class="red">*</span></label>' +
                                        '<div class="col-md-8">' + 
                                        '<input type="text" placeholder="Enter collateral value" class="form-control" name="collateral_value[]" id="collateral_value'+ i +'" required/>' +
                                    '</div>' + 
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">Registration Type <span class="red">*</span></label>' +
                                        '<div class="col-md-8">' + 
                                        '<select name="collateral_registration[]" id="collateral_registration'+ i +'" class="form-control" required>' +
                                            '<option value="">Select Collateral Registration Type</option>';
                                            for(var key in getStatic.collateral_regis_type){
                                                htmlStr += '<option value="'+getStatic.collateral_regis_type[key]+'">'+getStatic.collateral_regis_type[key]+'</option>';
                                            }
                                    htmlStr += '</select>' +
                                    '</div>' + 
                                '</div>' +
                            '</div>'+
                            '<div class="col-sm-6">' +
                                '<span class="tools pull-right">' +
                                    '<button type="button" onclick="removeDiv('+ i +')" class="fa fa-times btn btn-danger"></button>' +
                                '</span>' +
                                '<div class="form-group">' +
                                '<label class="col-sm-3 control-label">Collateral Photo</label>' +
                                    '<div class="col-sm-2">' +
                                        '<div class="fileupload fileupload-new" data-provides="fileupload">' +
                                            '<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">' +
                                                '<img src="/images/noimage.gif" alt="" />' +
                                            '</div>' +
                                            '<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>' +
                                            '<div>' +
                                               '<span class="btn btn-white btn-file">' +
                                               '<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>' +
                                               '<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>' +
                                               '<input type="file" name="collateral_photo[]" class="default"/>' +
                                               '</span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">Collateral Address <span class="red">*</span></label>' +
                                        '<div class="col-md-8">' + 
                                        '<textarea type="text" placeholder="Enter collateral address" class="form-control" name="collateral_address[]" id="collateral_address'+ i +'" required></textarea>' +
                                    '</div>' + 
                                '</div>' +
                                '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">Collateral Note</label>' +
                                        '<div class="col-md-8">' + 
                                            '<textarea type="text" placeholder="Enter collateral note" class="form-control" name="note[]" id="collateral_note" ></textarea>' +
                                        '</div>' + 
                                '</div>' +
                            '</div>' +
                        '</div>';

            var frag = document.createDocumentFragment(),
                temp = document.createElement('div');
            temp.innerHTML = htmlStr;
            while (temp.firstChild) {
                frag.appendChild(temp.firstChild);
            }
            return frag;
        }
        var fragment = create();
        $("#addGuarantorCollateral").append(fragment);
    });
    
    function removeDiv(rid){
        var removeElement = document.getElementById('removeElement_'+ rid +'');
            removeElement.parentNode.removeChild(removeElement);
            i = i - 1;
        var disableElement = document.getElementById('addBtn');
        disableElement.disabled = false;
    }
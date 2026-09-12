var LocaleTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }
                oTable.fnDraw(false);
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                for (var i = 1, iLen = jqTds.length; i < iLen - 1; i++) {
                    jqTds[i].innerHTML = '<input type="text" class="form-control small" value="' + aData[i] + '">';
                }
                jqTds[jqTds.length - 1].innerHTML = '<a class="edit btn btn-xs btn-default" href="javascript:; " id="save"><i class="fa fa-floppy-o"></i> </a> ' +
                                                    '<a class="cancel btn btn-xs btn-default" href="javascript:;"> <i class="fa fa-times-circle"></i></i></a>';
            }

            function saveRow(oTable, nRow,saveId) {
                var jqInputs = $('input', nRow);
                var jqTds = $('>td', nRow);
                for (var i = 1, iLen = jqTds.length; i < iLen -1; i++) {
                    if(jqTds[i].id == ""){
                        jqTds[i].id = saveId[i-1];
                    }
                    oTable.fnUpdate(jqInputs[i - 1].value, nRow, i, false);
                }
                oTable.fnUpdate('<a class="edit btn btn-xs btn-default" href="javascript:;" id="edit"><i class="fa fa-pencil"></i></a>', nRow, jqTds.length - 1, false);
                oTable.fnDraw(false);
            }

            function saveData(oTable,data)
            {
                $.ajax({
                    type: 'POST',
                    url : '/locale/update',
                    data: data,
                    success:function(data){
                        if(data.success == true){
                            saveRow(oTable, nEditing,data.saveId);
                            nEditing = null;
                        }else{
                            location.reload();
                        }
                    }
                });
            }

            var oTable = $('#locale-table').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 15,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
                ]
            });

            jQuery('#locale-table_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            jQuery('#locale-table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown

            var nEditing = null;

            $('body').on('click', 'a.cancel',function (e) {
                e.preventDefault();
                restoreRow(oTable, nEditing);
                nEditing = null;
            });

            $('body').on('click','a.edit', function (e) {
                e.preventDefault();
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];
                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.id == "save") {
                    var token = $("#token").val();
                    var jqTds = $('>td', nRow);
                    var jqInputs = $('input', nRow);
                    var localeData = new Array();
                    for (var i = 1, iLen = jqTds.length; i < iLen -1; i++) {
                        var d = {
                            id : jqTds[i].id,
                            locale_id : jqTds[i].className.trim(),
                            title: jqInputs[i - 1].value
                        };
                        localeData.push(d);
                    }
                    var data = {
                        locale : localeData,
                        _token: token
                    };
                    saveData(oTable,data)
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }
    };

}();
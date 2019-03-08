/*
 * Project Name: fais *
 * Copyright(C)2018 Department of Science & Technology -IX *
 * Developer: Larry Mark B. Somocor , Aris Moratalla  *
 * 04 20, 2018 , 10:43:00 AM *
 * Module: purchaserequest *
 */

jQuery(document).ready(function ($) {
    var po_no = $("#purchaserequest-purchase_request_number").val();
    if (po_no!='') {
        jQuery.ajax( {
            type: "POST",
            url: frontendURI + "procurement/purchaserequest/checkprdetails?pno=" + po_no ,
            dataType: "json",
            success: function ( result ) {
                $.each( result, function ( i, field ) {
                    var purchase_request_details_id = field.purchase_request_details_id;
                    var purchase_request_id = field.purchase_request_id;
                    var purchase_request_number = field.purchase_request_number;
                    var purchase_request_details_unit = field.purchase_request_details_unit;
                    var unit_id = field.unit_id;
                    var purchase_request_details_item_description = field.purchase_request_details_item_description;
                    var purchase_request_details_quantity = field.purchase_request_details_quantity;
                    var purchase_request_details_price = field.purchase_request_details_price;
                    var purchase_request_details_status = field.purchase_request_details_status;

                    /********** Temporary Data **************/
                    opentr  = "<tr class='table-data'>";
                    checkbox = "<td><div class=\"radio-container\"><div class=\"radio tbl-tmt\" data-id='"+purchase_request_details_id+"' data-radio=\"test\"><input type=\"radio\" name=\"test\" class=\"radio-ui\"></div></div></td>"
                    closetr = "</tr>";
                    var pd_id ="<td>" + purchase_request_details_id + "</td>";
                    unit = "<td>"+unit_id+"</td>";
                    itemdescription = "<td>"+purchase_request_details_item_description+"</td>";
                    qty = "<td>"+purchase_request_details_quantity+"</td>";
                    unitcost = "<td>"+ purchase_request_details_price +"</td>";
                    var tt = parseFloat(purchase_request_details_quantity) * parseFloat(purchase_request_details_price);
                    totalcost = "<td>" +  tt.toFixed(2) + "</td>";
                    $dataAppend =  opentr + checkbox  + pd_id + unit + itemdescription + qty + unitcost + totalcost + closetr;
                    $('table tbody.table-body').append($dataAppend);
                    var table = $('#pr-table').tableToJSON();
                    var jsonstring = JSON.stringify(table);
                    $('.radio.tbl-tmt').each(function(){
                        $('.radio.tbl-tmt').length > 0 ? $('.delete-row').prop('disabled',false) : $('.delete-row').prop('disabled',true);
                    });
                    $('#purchaserequest-lineitembudgetlist').val(jsonstring);

                } );
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        } );
    }

    $("body").keydown(function(key) {
        if (key.which == 115) {
            $('#add-container').each(function () {
                if ($(".mypopup").hasClass('selected')) { $(".mypopup").removeClass('selected'); }else{  $(".mypopup").addClass('selected');}
            });
        }
        if (key.which == 113) {
            $("#btnInsert").click();
        }
    });

    $("tr.table-data td").each(function() {

    });

    $("body").on('click','#btnAddLineItem',function () {

        $('#add-container').each(function () {
            if ($(".mypopup").hasClass('selected')) { $(".mypopup").removeClass('selected'); }else{  $(".mypopup").addClass('selected');}
        });


    });

    $("body").on('click','#btnAdd',function () {
        opentr  = "<tr class='table-data'>";
        checkbox = "<td><div class=\"radio-container\"><div class=\"radio tbl-tmt\" data-id=\"2\" data-radio=\"test\"><input type=\"radio\" name=\"test\" class=\"radio-ui\"></div></div></td>"
        closetr = "</tr>";
        p_id = "<td>" + -1 + "</td>";
        unit = "<td>" + $("#txtunits").val() + "</td>";
        itemdescription = "<td>"  + $("#txtitemdesc").val() + "</td>";
        qty ="<td>" + $("#txtqty").val() + "</td>";
        unitcost = "<td>" + $("#txtcost").val() + "</td>";
        $(".req").removeClass('visible');
        $("#txtunits").focus();
        if ($("#txtunits").val() == "") {
            $(".one").addClass('visible');
            $("#txtunits").focus();
        }
            else if ($("#txtitemdesc").val() == "") {
                $(".two").addClass('visible');
                $("#txtitemdesc").focus();
            }
            else if ($("#txtqty").val() == "") {
                $(".three").addClass('visible');
                $("#txtqty").focus();
            }
            else if ($("#txtcost").val() == "") {
                $(".four").addClass('visible');
                $("#txtcost").focus();
        }else{
            var tt = parseFloat($("#txtqty").val()) * parseFloat($("#txtcost").val());
            totalcost = "<td>" +  tt.toFixed(2) + "</td>";
            $dataAppend = opentr + checkbox + p_id + unit + itemdescription + qty + unitcost + totalcost + closetr;
            $('table tbody.table-body').append($dataAppend);
            var table = $('#pr-table').tableToJSON();
            var jsonstring = JSON.stringify(table);
            $('.radio.tbl-tmt').each(function(){
                $('.radio.tbl-tmt').length > 0 ? $('.delete-row').prop('disabled',false) : $('.delete-row').prop('disabled',true);
            });
            $('#purchaserequest-lineitembudgetlist').val(jsonstring);
            $("#txtunits").val('');
            $("#txtitemdesc").val('');
            $("#txtqty").val('');
            $("#txtcost").val('');
        }
    });
    
    $("body").on('click','#btnClose',function () {
            $('#add-container').each(function () {
                if ($(".mypopup").hasClass('selected')) { $(".mypopup").removeClass('selected'); }else{  $(".mypopup").addClass('selected');}
            });
    })

    $('body').on('click','#btnInsert' , function() {
        /********** Temporary Data **************/
        opentr  = "<tr class='table-data'>";
        checkbox = "<td><div class=\"radio-container\"><div class=\"radio tbl-tmt\" data-id=\"2\" data-radio=\"test\"><input type=\"radio\" name=\"test\" class=\"radio-ui\"></div></div></td>"
        closetr = "</tr>";
        p_id = "<td>"+ -1 + "</td>";
        unit = "<td>1</td>";
        itemdescription = "<td>Item description</td>";
        qty = "<td>5</td>";
        unitcost = "<td>60,000</td>";
        totalcost = "<td>60,000</td>";
        $dataAppend = opentr + checkbox + p_id  + unit + itemdescription + qty + unitcost + totalcost + closetr;
        $('table tbody.table-body').append($dataAppend);
        var table = $('#pr-table').tableToJSON();
        var jsonstring = JSON.stringify(table);
        $('.radio.tbl-tmt').each(function(){
        $('.radio.tbl-tmt').length > 0 ? $('.delete-row').prop('disabled',false) : $('.delete-row').prop('disabled',true);
        });
        $('#purchaserequest-lineitembudgetlist').val(jsonstring);
    });

    $('body').on('click','.delete-row' , function() {
        $("table tbody").find('.radio.tbl-tmt').each(function(){
            if($(this).hasClass('check')) {
                var id = $(this).attr('data-id');
                if (id==2) {
                    $(this).parents("tr").remove();
                    $('#tbl-item-selected').html($('.radio.tbl-tmt.check').length+ " selected").show('fast');
                    var table = $('#pr-table').tableToJSON();
                    var jsonstring = JSON.stringify(table);
                    $('.radio.tbl-tmt').length > 0 ? $('.delete-row').prop('disabled',false) : $('.delete-row').prop('disabled',true);
                    $('#purchaserequest-lineitembudgetlist').val(jsonstring);
                }else{
                    var s  = DelDetails(id);
                }
                if (s=='success') {
                    $(this).parents("tr").remove();
                    $('#tbl-item-selected').html($('.radio.tbl-tmt.check').length+ " selected").show('fast');
                    var table = $('#pr-table').tableToJSON();
                    var jsonstring = JSON.stringify(table);
                    $('.radio.tbl-tmt').length > 0 ? $('.delete-row').prop('disabled',false) : $('.delete-row').prop('disabled',true);
                    $('#purchaserequest-lineitembudgetlist').val(jsonstring);
                }
            }
        });
    });

    function DelDetails(id) {
        $.get(frontendURI + "procurement/purchaserequest/deletedetails?idno=" + id , function(data, status){
        });
        return 'success';
    }


});

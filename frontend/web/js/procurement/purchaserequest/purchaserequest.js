/*
 * Project Name: fais *
 * Copyright(C)2018 Department of Science & Technology -IX *
 * Developer: Larry Mark B. Somocor , Aris Moratalla  *
 * 04 20, 2018 , 10:43:00 AM *
 * Module: purchaserequest *
 */

jQuery(document).ready(function ($) {

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

    $('body').on('click','#btnInsert' , function() {
        /********** Temporary Data **************/
        opentr  = "<tr class='table-data'>";
        checkbox = "<td><div class=\"radio-container\"><div class=\"radio tbl-tmt\" data-id=\"2\" data-radio=\"test\"><input type=\"radio\" name=\"test\" class=\"radio-ui\"></div></div></td>"
        closetr = "</tr>";
        unit = "<td>1</td>";
        itemdescription = "<td>Item description</td>";
        qty = "<td>5</td>";
        unitcost = "<td>60,000</td>";
        totalcost = "<td>60,000</td>";

        $dataAppend = opentr + checkbox  + unit + itemdescription + qty + unitcost + totalcost + closetr;
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
                $(this).parents("tr").remove();
                $('#tbl-item-selected').html($('.radio.tbl-tmt.check').length+ " selected").show('fast');
                var table = $('#pr-table').tableToJSON();
                var jsonstring = JSON.stringify(table);
                $('.radio.tbl-tmt').length > 0 ? $('.delete-row').prop('disabled',false) : $('.delete-row').prop('disabled',true);
                $('#purchaserequest-lineitembudgetlist').val(jsonstring);
            }
        });
    });


});

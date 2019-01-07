/*
 * Project Name: fais *
 * Copyright(C)2018 Department of Science & Technology -IX *
 * Developer: Larry Mark B. Somocor , Aris Moratalla  *
 * 04 20, 2018 , 10:43:00 AM *
 * Module: purchaserequest *
 */

jQuery(document).ready(function ($) {

    $(document).on('click','.purchaseorder' , function() {
        $(".loadpartial").fadeIn(300);
        $(".loadpartial").show();
        var x = $(this).data('id');
        alert(x);
        jQuery.ajax( {
            type: "POST",
            url: frontendURI + "procurement/purchaseorder/view?id=" + x + "&view=purchaseorder",
            dataType: "text",
            success: function ( response ) {
                //console.log(response);
                $("#purchaseorderview").html(response);
                $(".loadpartial").hide();
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        } );
    });


});

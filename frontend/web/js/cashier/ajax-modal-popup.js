//$.pjax.defaults.timeout = 25000;

$("body").on("click","#buttonCreateLddapada",function () {
    
    $('#modalLddapada').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    $('#modalHeader').html($(this).attr('title'));
    setTimeout(function () {
        $("#btnrefresh").click();
    },1500);
});

$("body").on("click","#buttonAddCreditor",function () {
    
    $('#modalCreditor').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    $('#modalHeader').html($(this).attr('title'));
    setTimeout(function () {
        $("#btnrefresh").click();
    },1500);
});

$("#modalCreditor").on("hidden.bs.modal", function () {
    // put your default event here
    $.pjax.reload({container:'#lddap-ada-items'});
});


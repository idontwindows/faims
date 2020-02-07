function loadModal(url){
    $('#modalContainer').modal('show')
        .find('#modalContent')
        .load(url);
        //.load($(this).attr('value'));
    $('#modalHeader').html($(this).attr('title'));
    setTimeout(function () {
        $("#btnrefresh").click();
    },1500);
}

$("body").on("click","#buttonCreateRequest",function () {
    $('#modalRequest').modal('show')
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

$("body").on("click","#buttonViewRequesttype",function () {
    $('#modalViewRequesttype').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    $('#modalHeader').html($(this).attr('title'));
    setTimeout(function () {
        $("#btnrefresh").click();
    },1500);
});


$("body").on("click","#buttonViewAttachments",function () {
    loadModal($(this).attr('value'));
});

$("body").on("click","#buttonUploadAttachments",function () {
    loadModal($(this).attr('value'));
});

$("body").on("click","#buttonSubmit",function () {
    loadModal($(this).attr('value'));
});
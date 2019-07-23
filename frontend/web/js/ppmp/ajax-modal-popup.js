//$.pjax.defaults.timeout = 25000;

$("body").on("click","#buttonAddPpmp",function () {
    
    $('#modalPpmp').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    $('#modalHeader').html($(this).attr('title'));
    setTimeout(function () {
        $("#btnrefresh").click();
    },1500);

});

$('body').keydown(
function(e){
    if(e.keyCode === 27){
        $('#modalPpmp').modal('hide');
    }
});

//$("#buttonAddPpmp").click(function(){
//   alert('hahahha'); 
//});
    

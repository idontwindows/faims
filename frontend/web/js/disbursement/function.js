$('#taxable').show(500);
$('#specify').hide(500);
$('#pono').hide(500);

$("body").on("change","input[name='Disbursement[taxable]']",function () {
    var s= $(this).val()
    var amt = $("#disbursement-dv_amount").val();
    var disb =  $("#disbursement-particulars").val();
    if(amt=='') {
    }else{
        if (s==1) {
             var gross = amt;
             var lesstax = gross / 1.12;
             var tax = lesstax * 0.05;
             var ewt = lesstax * 0.01;
             var net = gross - tax - ewt;
             if (gross > 10000) {
                 $("#disbursement-particulars").html(disb
                     +'&#13;&#13;' + 'GROSS : ' + parseFloat( gross ).toFixed(2)
                     +'&#13;' + 'Less TAX : ' + parseFloat( tax ).toFixed(2)
                     +'&#13;' + 'Less EWT : ' + parseFloat( ewt ).toFixed(2)
                     +'&#13;' + 'NET AMOUNT : ' + parseFloat( net ).toFixed(2));
             }else{
                 $("#disbursement-particulars").html(disb
                     +'&#13;&#13;' + 'GROSS : ' + parseFloat( gross ).toFixed(2)
                     +'&#13;' + 'Less TAX : ' + parseFloat( tax ).toFixed(2)
                     +'&#13;' + 'NET AMOUNT : ' + parseFloat( net ).toFixed(2));
             }
        }else{
            //$("#myappend").html('');
        }
    }
})

$("body").on("change","input[type=radio][name='rdList']",function () {

    var x = $(this).val();
    var s = $('#disbursement-dv_type').val();
    if (s=='TF') {
        if (x==2) {
        $("input[name=rdList][value='1']").prop('checked', true);
        }
    }
    if (x==0) {
        $(".cboPono").select2({
            disabled : false,
            dropdownParent: $('#modalDisbursement'),
            width: "element",
            theme : "krajee"
        });
    }else{
        $(".cboPono").select2({
            disabled : true,
            dropdownParent: $('#modalDisbursement'),
            width: "element",
            theme : "krajee"
        });
    }
});


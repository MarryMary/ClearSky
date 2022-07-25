$("#dl_key_setting").change(function() {
    const str1 = $('input:radio[name="CodeType"]:checked').val();
    if(str1 == "keymust"){
        $("#dlkey").prop("disabled", false);
    }else{
        $("#dlkey").prop("disabled", true);
    }
});

$(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.parent().parent().next(':text').val(label);
});
$("#radio_codetype").change(function() {
    const str1 = $('input:radio[name="CodeType"]:checked').val();
    if(str1 == "OnlyMusic"){
        $("#Music").prop("disabled", false);
        $("#money_setting").prop("disabled", true);
    }else{
        $("#Music").prop("disabled", true);
        $("#money_setting").prop("disabled", false);
        if(str1 == "Master") {
            $("#money_setting").prop("disabled", true);
        }
    }
});

$("#radio_codegenset").change(function() {
    const str1 = $('input:radio[name="IsChangeCode"]:checked').val();
    if(str1 == "Change"){
        $("#generate").prop("disabled", false);
    }else{
        $("#generate").prop("disabled", true);
    }
});
$(function(){
    // 変数に要素を入れる
    var ytopen = $('#modal-open'),
        ytclose = $('#modal-close'),
        ytcontainer = $('#modal-container'),
        linkopen = $('#linkmd-open'),
        linkclose = $('#linkmd-close'),
        linkcontainer = $('#linkct');

    //開くボタンをクリックしたらモーダルを表示する
    ytopen.on('click',function(){
        ytcontainer.addClass('active');
        return false;
    });

    linkopen.on('click',function(){
        linkcontainer.addClass('active');
        return false;
    });

    //閉じるボタンをクリックしたらモーダルを閉じる
    ytclose.on('click',function(){
        ytcontainer.removeClass('active');
        return false;
    });

    linkclose.on('click',function(){
        linkcontainer.removeClass('active');
        return false;
    });

    //モーダルの外側をクリックしたらモーダルを閉じる
    $(document).on('click',function(e) {
        if(!$(e.target).closest('.modal-body').length) {
            ytcontainer.removeClass('active');
            linkcontainer.removeClass('active');
        }
    });


});

$("#radio_url").change(function() {
    const str1 = $('input:radio[name="islink_only"]:checked').val();
    if(str1 == "custom_message"){
        $("#message_custom").prop("disabled", false);
    }else{
        $("#message_custom").prop("disabled", true);
    }
});

$(function() {
    $('#addpict').change(function() {
        var fd = new FormData();
        var fd = new FormData($('#info').get(0));
        $.ajax({
            url: 'Register/Picture',
            type: 'POST',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType : "json",
            scriptCharset: 'utf-8'
        }).done(function(data){
            var data_stringify = JSON.stringify(data);
            var data_json = JSON.parse(data_stringify);
            var data_status = data_json["STATUS"];
            if(data_status == "FAILED"){
                alert(data_json["message"]);
            }else {
                var textarea = $('#MainText');
                var returned_tags = data_json["htmltags"] + "\n";
                textarea.val(textarea.val() + returned_tags);
            }
        });
    });
});

function youtube(){
    var container = $('#modal-container');
    var textarea = $('#MainText');
    var ytlinks = $('#ytlinks').val();
    var returned_tags = "<div class='youtube'>"+String(ytlinks)+"</div>"
    textarea.val(textarea.val() + returned_tags);
    $('#ytlinks').val('');
    container.removeClass('active');
}

function link(){
    const str1 = $('input:radio[name="islink_only"]:checked').val();
    if(str1 == "custom_message"){
        var container = $('#linkct');
        var textarea = $('#MainText');
        var urls = $('#urls').val();
        var custom_message = $('#message_custom').val();
        if(urls == ""){
            alert("URL入力欄を空にすることはできません。")
            return false;
        }
        if(custom_message == ""){
            alert("URLカスタムメッセージを使用する場合は、URLカスタムメッセージ入力欄を空にすることはできません。")
            return false;
        }else {
            var returned_tags = "<a href='" + urls + "'>" + custom_message + "</a>" + "\n";
            textarea.val(textarea.val() + returned_tags);
            container.removeClass('active');
            $('#urls').val('');
            $('#message_custom').val('');
            return false;
        }
    }else{
        var container = $('#linkct');
        var textarea = $('#MainText');
        var urls = $('#urls').val();
        if(urls == ""){
            alert("URL入力欄を空にすることはできません。")
            return false;
        }
        if(urls.length > 100) {
            var custom_message = urls;
        }else{
            var custom_message = urls.substring(0, 100)+"...";
        }
        var returned_tags = "<a href='" + urls + "'>" + custom_message + "</a>" + "\n"
        textarea.val(textarea.val() + returned_tags);
        container.removeClass('active');
        $('#urls').val('');
        return false;
    }
}

$(function(){
    $("input"). keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        } else {
            return true;
        }
    });
});
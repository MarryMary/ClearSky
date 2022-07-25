var buyurl = "";

$(function(){
    // 変数に要素を入れる
    var ytopen = $('.modal-open'),
        ytclose = $('#modal-close'),
        ytcontainer = $('#modal-container');

    ytopen.on('click', function(){
        ytcontainer.addClass('active');
        var link = $(this).data('id');
        buyurl = link;
        return false;
    });

    //閉じるボタンをクリックしたらモーダルを閉じる
    ytclose.on('click',function(){
        ytcontainer.removeClass('active');
        buyurl = "";
        return false;
    });

    //モーダルの外側をクリックしたらモーダルを閉じる
    $(document).on('click',function(e) {
        if(!$(e.target).closest('.modal-body').length) {
            ytcontainer.removeClass('active');
            buyurl = "";
        }
    });
});

function buy(){
    var link = buyurl;
    location.href=link;
}
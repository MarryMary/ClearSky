var delurl = "";
$(function(){
    // 変数に要素を入れる
    var ytopen = $('.modal-open'),
        ytclose = $('#modal-close'),
        ytcontainer = $('#modal-container');

    ytopen.on('click', function(){
        ytcontainer.addClass('active');
        var link = $(this).data('id');
        delurl = link;
        return false;
    });

    //閉じるボタンをクリックしたらモーダルを閉じる
    ytclose.on('click',function(){
        ytcontainer.removeClass('active');
        delurl = "";
        return false;
    });

    //モーダルの外側をクリックしたらモーダルを閉じる
    $(document).on('click',function(e) {
        if(!$(e.target).closest('.modal-body').length) {
            ytcontainer.removeClass('active');
            delurl = "";
        }
    });
});

/*
document.getElementById("search_del").addEventListener('keyup',function(){
    var text =$('#search_del').val();
    $.ajax('Search/JOINInfo',
        {
            type: 'POST',
            data: {query: text},
            dataType: 'json'
        }).done(function(data) {
        var data_stringify = JSON.stringify(data);
        var data_json = JSON.parse(data_stringify);
        $('#table').find('tbody tr').remove()
        data.Result.forEach(function (element) {
            $('#table').append('<tr><th scope="row">'+element[0]+'</th><td>'+element[1]+'</td><td>'+ element[2] +'</td><td>'+element[4]+'</td><td><button type="button" class="btn btn-success">更新</button></td> <td><input class="form-check-input" type="checkbox" value="'+ element[0] +'" id="flexCheckDefault"></td></tr>');
        });
    });
});
 */

function del(){
    var link = delurl;
    location.href=link;
}
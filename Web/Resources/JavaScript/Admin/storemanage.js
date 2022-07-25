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
    $.ajax('Search/Music',
        {
            type: 'POST',
            data: {query: text},
            dataType: 'json'
        }).done(function(data) {
        var data_stringify = JSON.stringify(data);
        var data_json = JSON.parse(data_stringify);
        $('#table').find('tbody tr').remove()
        data.Result.forEach(function (element) {
            var table = `
<th scope="row">${element[0]}</th>
<td>${element[1]}</td>
<td>${element[5]}</td>
<td>¥${element[4]}</td>
<td><button type="button" class="btn btn-danger modal-open" id="modal-open" data-id="MusicDelete/${element[0]}">削除</button></td>
`
            $('#table').append(table);
        });
    });
});
*/

function del(){
    var link = delurl;
    location.href=link;
}
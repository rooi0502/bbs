$(function()
{
    $("#store").on('click', function()
    {
        var fd = new FormData($('#form')[0]);
        fd.append('id', $('#id').html());
        fd.append('attr', $('#attr').html());
        ($('#detail')[0]) ? fd.append('detail', $('#detail').attr('id')) : "";
        ($('#checkbox').prop('checked')) ? fd.append('delete', 'delete') : "";
        $.ajax({
            type: "POST",
            url: "edit.php",
            data: fd,
            contentType : false,
            processData : false,
            dataType : "json",
        }).done(function(data)
        {
            if (data['detail']) {
                location.reload();
            } else {
                // ビュー部分
                $('#view_id'+"."+data['id']).text(data['id']);
                $('#view_name'+"."+data['id']).text(data['name']);
                $('#view_text'+"."+data['id']).text(data['text']);
                $('#view_text'+"."+data['id']).css('color', data['color']);
    
                // モーダルウィンドウ部分
                $('#name').text(data['name']);
                $('#text').val(data['text']);
                $('#color').val(data['color']);
                if (data['fname']) {
                    var src = "../uploads/" + data['fname'];
                    $('#fname').attr("src", src);
                    $('#image').show();
                } else {
                    $('#fname').attr("src", "");
                    $('#image').hide();
                }
            }
        }).fail(function(XMLHttpRequest, textStatus, errorThrown)
        {
            var error_msg = JSON.parse(XMLHttpRequest.responseText);
            for (var i=0;i<error_msg.length;i++) {
                alert(error_msg[i]);
            }
        });
    });
});
$(function()
{
    $('[id$="edit"]').on('click', function()
    {
        $("#overlay, #modal_window").fadeIn();
        $("#overlay, #modal_close").on('click', function() 
        {
            $("#overlay, #modal_window").fadeOut();
        });
        var id = $(this).attr('class');
        var attr = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "get.php",
            data: {
                'id': id,
                "attr": attr,
            },
            dataType : "json",
        }).done(function(data)
        {
            // モーダルウィンドウ内の表示処理
            $('#attr').html(data['attr']);
            $('#id').html(data['id']);
            $('#name').val(data['name']);
            $('#text').val(data['text']);
            $('#time').html(data['time']);
            $('#color').val(data['color']);
            if (data['user_id']) {
                $('#user_id').html("ユーザーID:" + data['user_id']);
            } else {
                $('#user_id').html("ユーザーID:なし");
            }
            if (data['fname']) {
                var src = "../uploads/" + data['fname'];
                $('#fname').attr("src",src);
                $('#image').show();
                $('#checkbox').show();
                $('#checkbox').prop('checked', false);
                $('#checkbox_text').show();
            } else {
                $('#fname').attr("src","");
                $('#image').hide();
                $('#checkbox').hide();
                $('#checkbox_text').hide();
            }
        }).fail(function(XMLHttpRequest, textStatus, errorThrown)
        {
            console.log(arguments);
            alert(errorThrown);
        });
    });

    locateCenter();
    $(window).resize(locateCenter);

    function locateCenter()
    {
        var w = $(window).width();
        var h = $(window).height();
        var cw = $("#modal_window").outerWidth();
        var ch = $( "#modal_window" ).outerHeight();
        $("#modal_window").css({"left":((w - cw)/2) + "px", "top":((h - ch)/2) + "px"});
    }
});
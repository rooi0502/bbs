$(function() 
{
    $("[id$=delete]").on('click', function() 
    {
        var id = $(this).attr('class');
        var attr = $(this).attr('id');
        console.log(attr);
        if (confirm("削除しますか？")) {
            $.ajax({
                type: "POST",
                url: "delete.php",
                data: {
                    id: id,
                    attr: attr,
                },
                dataType: "json",
            }).done(function(data)
            {
                window.location.href = data;
            }).fail(function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert(errorThrown);
            });
        }
    });
});
//ajax请求分类
function getCate()
{
    //获取select框选中的值
    var id = $("[name='pid']").find('option:selected').val();
    $.ajax({
        type: 'get',
        url: '/index.php/Admin/Cate/ajaxGetCate',
        data: {'pid':id},
        cache: false,
        async : false,
        dataType: "json",
        success: function (data)
        {            var html = '<option value="0" selected="selected">无</option>';
            for(var i=0;i<data.length;i++)
            {
                html += '<option value="'+data[i].id+'">'+data[i].title+'</option>';
            }
            $('#ppidOption').html(html);
        },
        error:function () {
            alert("请求失败！");
        }
     });
}
function ajaxRequest()
{
    $.ajax({
        type: "post",
        url: url,
        data: {"para":1},
        cache: false,
        async : false,
        dataType: "json",
        success: function (data)
        {

        },
        error:function () {
            alert("请求失败！");
        }
     });
}
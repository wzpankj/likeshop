<?php /*a:2:{s:76:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\decoration\applet\bottom\lists.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo url(); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css?v=<?php echo htmlentities($front_version); ?>">
    <link rel="stylesheet" href="/static/admin/css/app.css?v=<?php echo htmlentities($front_version); ?>">
    <link rel="stylesheet" href="/static/admin/css/like.css?v=<?php echo htmlentities($front_version); ?>">
    <script src="/static/lib/layui/layui.js?v=<?php echo htmlentities($front_version); ?>"></script>
    <script src="/static/admin/js/app.js"></script>
</head>
<body>
<?php echo $js_code; ?>
<script src="/static/admin/js/jquery.min.js"></script>
<script src="/static/admin/js/function.js"></script>


<style>
    .layui-table-cell {
        height: auto;
    }
</style>
<div class="wrapper">
    <div class="layui-card">
        <!-- 主体区域 -->
        <div class="layui-card-body">
            <div class="layui-tab-content">
                <table id="like-table-lists" lay-filter="like-table-lists"></table>
                <script type="text/html" id="table-operation">
                    <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">编辑</a>
                </script>
                <script type="text/html" id="icon">
                    <img src="{{d.icon}}" style="height:80px;width: 80px" class="image-show">
                </script>
                <script type="text/html" id="select_icon">
                    <img src="{{d.select_icon}}" style="height:80px;width: 80px" class="image-show">
                </script>
            </div>

        </div>

    </div>
</div>

<script>
    layui.use(["table", "element", "laydate"], function(){
        var table   = layui.table;

        like.tableLists('#like-table-lists','<?php echo url(); ?>',[
            {field: 'id', width: 60, title: 'ID'}
            ,{field: 'name', width: 260, align: 'center', title: '导航名称'}
            ,{field: 'image', width: 120,title: '导航图标', templet: '#icon'}
            ,{field: 'image', width: 120,title: '选中图标', templet: '#select_icon'}
            ,{title: '操作', width: 180, align: 'center', fixed: 'right', toolbar: '#table-operation'}
        ],[],false);

        var active = {
            edit:function(obj){
                var id = obj.data.id;
                layer.open({
                    type: 2
                    ,title: '编辑导航'
                    ,content: '<?php echo url("decoration.applet.bottom/edit"); ?>?id='+id
                    ,area: ['90%', '90%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'editSubmit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("decoration.applet.bottom/edit"); ?>',
                                data:field,
                                type:"post",
                                success:function(res)
                                {
                                    if(res.code == 1) {
                                        layui.layer.msg(res.msg);
                                        layer.close(index);
                                        table.reload('like-table-lists');
                                    }
                                }
                            });
                        });
                        submit.trigger('click');
                    }
                });
            },

        };
        like.eventClick(active);


    })
</script>
</body>
</html>
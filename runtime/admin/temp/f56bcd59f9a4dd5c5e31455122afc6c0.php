<?php /*a:2:{s:74:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\decoration\applet\home\lists.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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

        <div class="layui-tab layui-tab-card" lay-filter="test">
            <div class="layui-tab-content " style="padding: 0 15px;">
                <div class="layui-tab-item layui-show">

                    <table id="like-table-lists" lay-filter="like-table-lists"></table>

                    <script type="text/html" id="table-operation">
                        <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">编辑</a>
                    </script>

                    <script type="text/html" id="image">
                        <img src="{{d.image}}" style="height:80px;width: 80px" class="image-show">
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(["table", "element"], function(){
        var table   = layui.table;

        like.tableLists('#like-table-lists','<?php echo url(); ?>',[
            {field: 'id', width: 60, title: 'ID'}
            ,{field: 'name', width: 200, align: 'center', title: '菜单名称'}
            ,{field: 'image', width: 120,title: '菜单图片', templet: '#image'}
            ,{field: 'describe',title: '菜单描述'}
            ,{title: '操作', width: 180, align: 'center', fixed: 'right', toolbar: '#table-operation'}
        ],'',true);


        var active = {
            edit:function(obj){
                var id = obj.data.id;
                layer.open({
                    type: 2
                    ,title: '编辑导航'
                    ,content: '<?php echo url("decoration.applet.home/edit"); ?>?id='+id
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
                                url:'<?php echo url("decoration.applet.home/edit"); ?>',
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
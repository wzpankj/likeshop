<?php /*a:2:{s:74:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\decoration\applet\mine\lists.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
            <ul class="layui-tab-title">
                <li lay-id="1" class="layui-this">导航设置</li>
                <li lay-id="2">其他设置</li>
            </ul>
            <div class="layui-tab-content " style="padding: 0 15px;">
                <div class="layui-tab-item layui-show">
                    <div style="margin-top: 10px">
                        <button class="layui-btn layui-btn-sm layEvent <?php echo htmlentities($view_theme_color); ?>" lay-event="add">新增菜单</button>
                    </div>
                    <table id="like-table-lists" lay-filter="like-table-lists"></table>
                    <script type="text/html" id="table-operation">
                        <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">编辑</a>
                        {{# if(0 == d.is_show) { }}
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="status">显示</a>
                        {{# }else{ }}
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="status">隐藏</a>
                        {{# } }}
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    </script>
                    <script type="text/html" id="image">
                        <img src="{{d.image}}" style="height:80px;width: 80px" class="image-show">
                    </script>
                </div>
                <div class="layui-tab-item">
                    <div class="layui-form" lay-filter="">

                            <div class="layui-form-item">
                                <label class="layui-form-label">顶部背景图：</label>
                                <div class="layui-input-block">
                                    <?php if($other_set['background_image']): ?>
                                    <div class="upload-image-div">
                                        <img src="<?php echo htmlentities($other_set['background_image']); ?>" alt="img">
                                        <input type="hidden" name="background_image" value="<?php echo htmlentities($other_set['background_image']); ?>">
                                        <div class="del-upload-btn">x</div>
                                    </div>
                                    <div class="upload-image-elem" style="display:none;"><a class="add-upload-image"> + 添加图片</a></div>
                                    <?php else: ?>
                                    <div class="upload-image-elem"><a class="add-upload-image"> + 添加图片</a></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="layui-form-item"><label class="layui-form-label"></label>
                                <span style="color: #a3a3a3;font-size: 9px">顶部背景图，建议尺寸：宽400px*高400px。jpg，jpeg，png格式</span>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="submit">确定</button>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    layui.use(["table", "element", "laydate"], function(){
        var table   = layui.table;
        var element = layui.element;
        var form    = layui.form;

        like.tableLists('#like-table-lists','<?php echo url(); ?>',[
            {field: 'id', width: 60, title: 'ID', align: 'center'}
            ,{field: 'name', width: 200, align: 'center', title: '菜单名称'}
            ,{field: 'image', width: 120,title: '菜单图标', templet: '#image'}
            ,{field: 'link_type_desc', width: 120,title: '菜单类型', align: 'center'}
            ,{field: 'link_address_desc', width: 300,title: '链接地址'}
            ,{field: 'is_show_desc', width: 120,title: '菜单状态', align: 'center'}
            ,{field: 'sort', width: 120,title: '排序', align: 'center'}
            ,{title: '操作', width: 250, align: 'center', fixed: 'right', toolbar: '#table-operation'}
        ],'',true);

        var active = {
            add:function(){
                layer.open({
                    type: 2
                    ,title: '新增导航'
                    ,content: '<?php echo url("decoration.applet.mine/add"); ?>'
                    ,area: ['90%', '90%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'addSubmit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("decoration.applet.mine/add"); ?>',
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
            edit:function(obj){
                var id = obj.data.id;
                layer.open({
                    type: 2
                    ,title: '编辑导航'
                    ,content: '<?php echo url("decoration.applet.mine/edit"); ?>?id='+id
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
                                url:'<?php echo url("decoration.applet.mine/edit"); ?>',
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
            status: function(obj) {
                var is_show =   1 == obj.data.is_show ? 0 : 1;
                if(is_show){
                    var tips = "确定显示菜单:<span style='color: red'>"+ obj.data.name +"</span>";
                }else{
                    var tips = "确定隐藏菜单:<span style='color: red'>"+ obj.data.name +"</span>";
                }
                layer.confirm(tips, function(index) {
                    like.ajax({
                        url: "<?php echo url('decoration.applet.mine/status'); ?>",
                        data: {id: obj.data.id,is_show:is_show},
                        type: "POST",
                        success: function (res) {
                            if (res.code === 1) {
                                layui.layer.msg(res.msg);
                                layer.close(index);
                                table.reload('like-table-lists');
                            }
                        }
                    });
                    layer.close(index);
                })
            },
            del: function(obj) {
                layer.confirm("确定删除菜单:<span style='color: red'>"+ obj.data.name +"</span>", function(index) {
                    like.ajax({
                        url: "<?php echo url('decoration.applet.mine/del'); ?>",
                        data: {id: obj.data.id},
                        type: "POST",
                        success: function (res) {
                            if (res.code === 1) {
                                layui.layer.msg(res.msg);
                                layer.close(index);
                                obj.del();
                            }
                        }
                    });
                    layer.close(index);
                })
            },

        };
        like.eventClick(active);


        like.delUpload();
        $(document).on("click", ".add-upload-image", function () {
            like.imageUpload({
                limit: 1,
                field: "background_image",
                that: $(this)
            });
        })
        // 提交表单
        form.on('submit(submit)', function (data) {
            var data = data.field;
            console.log(data);
            like.ajax({
                url: '<?php echo url("decoration.applet.mine/otherSet"); ?>'
                , data: data
                , type: 'post'
                , success: function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        });
                    }

                }
            });
        });

    })
</script>
</body>
</html>
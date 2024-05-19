<?php /*a:2:{s:56:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\auth\lists.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
        <div class="layui-card-body">

            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-btn-container" style="display: inline-block;">
                        <div class="layui-btn-group">
                            <button class="layui-btn  <?php echo htmlentities($view_theme_color); ?>" id="add">
                                添加
                            </button>
                            <button class="layui-btn  <?php echo htmlentities($view_theme_color); ?>" id="expand-all">全部展开
                            </button>
                            <button class="layui-btn   <?php echo htmlentities($view_theme_color); ?>" id="fold-all">全部折叠
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <table id="menu-lists" class="layui-table" lay-filter="menu-lists"></table>
            <script type="text/html" id="icon">
                <i class="layui-icon {{d.icon}}"></i>
            </script>
            <script type="text/html" id="operation">
                {{#  if(d.system == 0){ }}
                <a class="edit layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i
                        class="layui-icon layui-icon-edit"></i>修改
                </a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i
                        class="layui-icon layui-icon-delete"></i>删除
                </a>
                {{#  } }}
                {{#  if(d.system == 1){ }}
                <a class="edit layui-btn layui-btn-disabled layui-btn-xs" lay-event=""><i
                        class="layui-icon layui-icon-edit"></i>修改
                </a>
                <a class="layui-btn layui-btn-disabled layui-btn-xs" lay-event=""><i
                        class="layui-icon layui-icon-delete"></i>删除
                </a>
                {{#  } }}
            </script>

            <!-- 表格状态列 -->
            <script type="text/html" id="disable">
                {{#  if(d.system == 0 && d.type == 1){ }}
                <input type="checkbox" lay-filter="switch-disable" data-id={{d.id}} lay-skin="switch"
                       lay-text="菜单显示|菜单隐藏" {{# if(d.disable==0){ }} checked {{# } }}/>
                {{#  } }}
                {{#  if(d.system == 1 && d.type == 1){ }}
                <input type="checkbox" lay-filter="switch-disable" data-id={{d.id}} lay-skin="switch"
                       lay-text="权限显示|权限隐藏" disabled checked />
                {{#  } }}
                {{#  if(d.system == 0 && d.type == 2){ }}
                <input type="checkbox" lay-filter="switch-disable" data-id={{d.id}} lay-skin="switch"
                       lay-text="权限启用|权限禁用" {{# if(d.disable==0){ }} checked {{# } }}/>
                {{#  } }}
                {{#  if(d.system == 1 && d.type == 2){ }}
                <input type="checkbox" lay-filter="switch-disable" data-id={{d.id}} lay-skin="switch"
                       lay-text="权限启用|权限禁用" disabled checked />
                {{#  } }}
            </script>

            <div class="page-loading">
                <div class="ball-loader sm">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>
    </div>
</div>




<script>

    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/'
    }).extend({
        treeTable: 'treetable/treeTable'
    }).use(['form', 'table', 'treeTable'], function () {
        var form = layui.form
        ,treeTable = layui.treeTable;

        // 渲染表格
        var insTb = treeTable.render({
            elem: '#menu-lists',
            tree: {
                iconIndex:0,
                childName:'sub',
                treeColIndex:3,
                getIcon: function (d) {
                    return '<i class="ew-tree-icon layui-icon layui-icon-spread-left "></i>';
                }
            },
            cols: [
                {field: 'name', title: '名称',width: 280},
                {field: 'type_str', title: '类型',width: 70},
                {field: '#icon', title: '图标', width: 80,toolbar: '#icon'},
                {field: 'uri', title: '规则',width: 200},
                {field: 'sort', title: '排序', width: 80,event: 'tips',edit:'number'},
                {templet: '#disable', title: '控制', width: 120},
                {align: 'center', toolbar: '#operation', title: '操作', width: 200},
                {field: '', title: '',width: ''},
            ],
            reqData: function(data, callback) {
                // 在这里写ajax请求，通过callback方法回调数据
                like.ajax({
                    url:'<?php echo url("auth/lists"); ?>',
                    type:'get',
                    success:function (res) {
                        jsonObj = JSON.parse(res.data);
                        if(res.code==0) {
                            callback(jsonObj);
                        } else {
                            callback(res.msg);
                        }
                    }
                })
            },
            style: 'margin-top:0;'
        });


        treeTable.on('tool(menu-lists)', function(obj) {
            var menu_id = obj.data.id;

            if(obj.event === 'del'){
                layer.confirm('确定删除此菜单及其子菜单？', function(index){
                    like.ajax({
                        url:'<?php echo url("auth/del"); ?>',
                        data: {ids:[menu_id]},
                        type:"post",
                        success:function(res)
                        {
                            if(res.code == 1) {
                                layer.close(index);
                                layui.layer.msg(res.msg, {
                                    offset: '15px'
                                    , icon: 1
                                    , time: 1000
                                },function(){
                                    location.reload();
                                });
                            }
                        }
                    });
                });
            }

            if(obj.event === 'edit'){
                layer.open({
                    type: 2
                    ,title: '编辑菜单'
                    ,content: '<?php echo url("auth/edit"); ?>?id='+menu_id
                    ,area: ['90%', '90%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submit = layero.find('iframe').contents().find('#menu-submit');
                        iframeWindow.layui.form.on('submit(menu-submit)', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("auth/edit"); ?>',
                                data:field,
                                type:"post",
                                success:function(res)
                                {
                                    if(res.code == 1) {
                                        layer.close(index);
                                        layui.layer.msg(res.msg, {
                                            offset: '15px'
                                            , icon: 1
                                            , time: 1000
                                        },function(){
                                            location.reload();
                                        });
                                    }
                                }
                            });
                        });
                        submit.trigger('click');
                    }
                });
            }
        });

        form.on('switch(switch-disable)',function (obj) {
            var menu_id = obj.elem.attributes['data-id'].nodeValue;
            var disable = 1;
            if(obj.elem.checked){
                disable = 0;
            }
            var data ={disable:disable,id:menu_id};
            like.ajax({
                url:'<?php echo url("auth/status"); ?>',
                data:data,
                type:"post",
                success:function (res) {
                    if(res.code == 1) {
                        layui.layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        },function(){
                            location.reload();
                        });
                    }
                }
            });
        });

        $('#add').click(function(){
            layer.open({
                type: 2
                ,title: '添加菜单'
                ,content: '<?php echo url("auth/add"); ?>'
                ,area: ['90%', '90%']
                ,btn: ['确定', '取消']
                ,yes: function(index, layero) {
                    var iframeWindow = window['layui-layer-iframe'+ index]
                        ,submit = layero.find('iframe').contents().find('#menu-submit');
                    iframeWindow.layui.form.on('submit(menu-submit)', function(data) {
                        var field = data.field;
                        like.ajax({
                            url:'<?php echo url("auth/add"); ?>',
                            data:field,
                            type:"post",
                            success:function(res)
                            {
                                if(res.code == 1) {
                                    layer.close(index);
                                    layui.layer.msg(res.msg, {
                                        offset: '15px'
                                        , icon: 1
                                        , time: 1000
                                    },function(){
                                        location.reload();
                                    });
                                }
                            }
                        });
                    });
                    submit.trigger('click');
                }
            });
        });

        // 全部展开
        $('#expand-all').click(function () {
            insTb.expandAll();
        });

        // 全部折叠
        $('#fold-all').click(function () {
            insTb.foldAll();
        });
    });
</script>
</body>
</html>
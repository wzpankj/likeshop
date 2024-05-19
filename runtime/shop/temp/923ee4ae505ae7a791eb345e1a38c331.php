<?php /*a:2:{s:56:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\admin\lists.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo url(); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css?v=<?php echo htmlentities($front_version); ?>">
    <link rel="stylesheet" href="/static/admin/css/app.css">
    <link rel="stylesheet" href="/static/admin/css/like.css">
    <script src="/static/lib/layui/layui.js?v=<?php echo htmlentities($front_version); ?>"></script>
    <script src="/static/admin/js/app.js"></script>
</head>
<body>
<?php echo $js_code; ?>
<script src="/static/admin/js/jquery.min.js"></script>
<script src="/static/admin/js/function.js"></script>

<div class="wrapper">
    <div class="layui-card">
        <div class="layui-card-body">
            <!--搜索条件-->
            <div class="layui-form layui-card-header layuiadmin-card-header-auto" style="height: auto;">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">账号</label>
                        <div class="layui-input-block">
                            <input type="text" name="account" id="account" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" id="name" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">角色</label>
                        <div class="layui-input-block">
                            <select name="role_id" id="role_id">
                                <option value="">所有</option>
                                <?php if(is_array($role_lists) || $role_lists instanceof \think\Collection || $role_lists instanceof \think\Paginator): $i = 0; $__LIST__ = $role_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layuiadmin-btn-admin <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="LAY-user-back-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                        <button class="layui-btn layui-btn-sm layui-btn-primary " lay-submit lay-filter="table-clear-search">
                            清空查询
                        </button>
                    </div>
                </div>
            </div>
            <!--添加按钮-->
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layui-btn-sm layuiadmin-btn-admin <?php echo htmlentities($view_theme_color); ?> like-auth" auth-uri="admin/add" data-type="add">添加</button>
            </div>

            <!--表格-->
            <table id="admin-lists" lay-filter="admin-lists"></table>

            <!--js模板-->
            <script type="text/html" id="login-state">
                {{#  if(d.login_state == 1){ }}
                <button class="layui-btn layui-btn-xs <?php echo htmlentities($view_theme_button); ?>">在线</button>
                {{#  } else { }}
                <button class="layui-btn layui-btn-xs layui-btn-primary">下线</button>
                {{#  } }}
            </script>
            <script type="text/html" id="buttonTpl">
                {{#  if(d.disable == 1){ }}
                <button class="layui-btn layui-btn-danger layui-btn-xs layui-bg-red">禁用</button>
                {{#  } else { }}
                <button class="layui-btn layui-btn-xs layui-btn-primary">启用</button>
                {{#  } }}
            </script>
            <script type="text/html" id="admin-operation">
                {{#  if(d.root == 1){ }}
                <a  class="layui-btn layui-btn-disabled layui-btn-xs"><i class="layui-icon layui-icon-edit like-auth" auth-uri="admin/edit"></i>编辑</a>
                <a class="layui-btn layui-btn-disabled layui-btn-xs"><i class="layui-icon layui-icon-delete"></i>删除</a>
                {{#  } else { }}
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                {{#  } }}
            </script>
        </div>
    </div>
</div>

<script>

    layui.use(['table'], function(){
        var form = layui.form
            ,table = layui.table;

        //监听搜索
        form.on('submit(LAY-user-back-search)', function(data){
            var field = data.field;

            //执行重载
            table.reload('admin-lists', {
                where: field,
                page: {curr: 1},
            });
        });

        //清空查询
        form.on('submit(table-clear-search)', function () {
            $('#account').val('');
            $('#name').val('');
            $('#role_id').val('');
            form.render('select');
            //刷新列表
            table.reload('admin-lists', {
                where: [],
                page: {curr: 1},
            });
        });

        //事件
        var active = {
            add: function(){
                layer.open({
                    type: 2
                    ,title: '添加管理员'
                    ,content: '<?php echo url("admin/add"); ?>'
                    ,area: ['90%', '90%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'admin-submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("admin/add"); ?>',
                               data:field,
                               type:"post",
                               success:function(res)
                               {
                                   if(res.code == 1) {
                                       layui.layer.msg(res.msg, {
                                           offset: '15px'
                                           , icon: 1
                                           , time: 1000
                                       });
                                       layer.close(index); //关闭弹层
                                       table.reload('admin-lists'); //数据刷新
                                   }
                               }
                            });
                        });

                        submit.trigger('click');
                    }
                });
            }
        };
        $('.layui-btn.layuiadmin-btn-admin').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });


        like.tableLists('#admin-lists', '<?php echo url("admin/lists"); ?>', [
            {field: 'id', width: 60, title: 'ID', sort: true}
            ,{field: 'account',width:100, title: '账号'}
            ,{field: 'name',width:100, title: '名称'}
            ,{field: 'role', width:120,title: '角色'}
            ,{field: 'create_time',width:170, title: '创建时间', sort: true}
            ,{field: 'login_time', width:170,title: '最后登录时间', sort: true}
            ,{field: 'login_ip', width:140,title: '最后登录ip',}
            // ,{field: 'login_state',width:90, title:'登录状态', templet: '#login-state', minWidth: 40, align: 'center'}
            ,{field: 'disable', width:70,title:'状态', templet: '#buttonTpl', minWidth: 40, align: 'center'}
            ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#admin-operation'}
        ]);


        //监听工具条
        table.on('tool(admin-lists)', function(obj){
            if(obj.event === 'del'){
                var id = obj.data.id;
                layer.prompt({
                    formType: 1
                    ,title: '删除操作，请输ok继续操作'
                }, function(value, index){
                    if(value!='ok'){
                        layer.close(index);
                        layer.msg('口令输入错误');
                        return;
                    }
                    layer.close(index);
                    layer.confirm('确定删除此管理员？', function(index){
                        like.ajax({
                            url:'<?php echo url("admin/del"); ?>',
                            data:{'admin_id':id},
                            type:"post",
                            success:function(res)
                            {
                                if(res.code == 1) {
                                    obj.del();
                                    layui.layer.msg(res.msg, {
                                        offset: '15px'
                                        , icon: 1
                                        , time: 1000
                                    });
                                    layer.close(index);
                                }
                            }
                        });
                    });
                });
            }else if(obj.event === 'edit'){
                var id = obj.data.id;
                layer.open({
                    type: 2
                    ,title: '编辑管理员'
                    ,content: '<?php echo url("admin/edit"); ?>?admin_id='+id
                    ,area: ['90%', '90%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'admin-submit-edit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);

                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("admin/edit"); ?>',
                                data:field,
                                type:"post",
                                success:function(res)
                                {
                                    if(res.code == 1) {
                                        layui.layer.msg(res.msg, {
                                            offset: '15px'
                                            , icon: 1
                                            , time: 1000
                                        });
                                        layer.close(index); //关闭弹层
                                        table.reload('admin-lists'); //数据刷新
                                    }
                                }
                            });
                        });

                        submit.trigger('click');
                    }
                })
            }
        });
    });
</script>
</body>
</html>
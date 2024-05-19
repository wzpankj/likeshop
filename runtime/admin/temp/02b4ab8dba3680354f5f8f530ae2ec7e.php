<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\system\crontab\lists.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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


<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-collapse like-layui-collapse" lay-accordion="" style="border:1px dashed #c4c4c4">
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title like-layui-colla-title" style="background-color: #fff">操作提示</h2>
                    <div class="layui-colla-content layui-show">
                        <p>1.请在linux环境使用crontab命令添加定时任务：*/1 * * * * php think crontab 。</p>
                        <p>2.规则如下：</p>
                        <p>*(0~59分)&nbsp;&nbsp;*(0～23小时)&nbsp;&nbsp;*(1～31号)&nbsp;&nbsp;*(1-12月)&nbsp;&nbsp;*(0-7星期，0和7都代表星期日)</p>
                        <p>*(星号)：代表任何时刻都接受的意思。例：0 10 * * * command 日、月、周都是*，就代表着不论何月、何日的星期几的10：00都执行后续命令的意思。</p>
                        <p>,(逗号)：代表分隔时段的意思。例：要执行的工作是2：00与3：00时，就会是：0 2,3 * * * command时间还是有五列，不过第二列是 2,3 ，代表2与3都适用。</p>
                        <p>-(减号)：	代表一段时间范围内，例：8点到11点之间的每小时的18分都进行一项工作：18 8-11 * * * command仔细看到第二列变成8-11.代表 8,9,10,11,12 都适用的。</p>
                        <p>/n(斜线)：那个n代表数字，即是每隔n单位间隔的意思，例：每五分钟进行一次，则：*/5 * * * *  。</p>
                    </div>
                </div>
            </div>
            <br>
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layui-btn-sm layuiadmin-btn-crontab <?php echo htmlentities($view_theme_color); ?>" data-type="add">添加</button>
            </div>
            <table id="crontab-lists" lay-filter="crontab-lists"></table>
            <script type="text/html" id="status">
                {{#  if(d.status == 1){ }}
                <button class="layui-btn layui-btn-sm <?php echo htmlentities($view_theme_button); ?>">运行</button>
                {{#  } }}

                {{#  if(d.status == 2){ }}
                <button class="layui-btn layui-btn-sm layui-btn-danger">停止</button>
                {{#  } }}

                {{#  if(d.status == 3){ }}
                <button class="layui-btn layui-btn-sm layui-btn-danger">错误</button>
                {{#  } }}
            </script>
            <script type="text/html" id="operation">
                <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">编辑</a>
                {{#  if(d.system == 0){ }}
                <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete">删除</a>
                {{#  } }}
                {{#  if(d.system == 1){ }}
                <a class="layui-btn layui-btn-sm layui-btn-danger layui-btn-disabled">删除</a>
                {{#  } }}
            </script>

        </div>
    </div>
</div>
<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['table'], function () {
        var $ = layui.$
            , form = layui.form
            , table = layui.table;


        table.render({
            elem: '#crontab-lists'
            , url: '<?php echo url("system.Crontab/lists"); ?>' //模拟接口
            , cols: [[
                {field: 'name', width: 150, title: '名称'}
                , {field: 'type_desc', width: 90, title: '类型'}
                , {field: 'command', width: 150, title: '命令'}
                , {field: 'parameter', width: 100, title: '参数'}
                , {field: 'expression', width: 100, title: '规则'}
                , {width:80,title: '状态', templet: '#status', align: 'center',sort: true}
                , {field: 'error', width: 120, title: '错误原因'}
                , {field: 'last_time_str', width: 160, title: '最后执行时间'}
                , {field: 'time', width: 90, title: '时长'}
                , {field: 'max_time', width: 90, title: '最大时长'}
                , {fixed: 'right', title: '操作',width: 300,align: 'center', toolbar: '#operation'}
            ]]
            , text: {none: '暂无数据！'}
            , parseData: function (res) { //将原始数据解析成 table 组件所规定的数据
                return {
                    "code": res.code,
                    "msg": res.msg,
                    "count": res.data.count, //解析数据长度
                    "data": res.data.lists, //解析数据列表
                };
            },
            response: {
                statusCode: 1
            }
            ,done: function(res, curr, count){
                // 解决操作栏因为内容过多换行问题
                $(".layui-table-main tr").each(function (index, val) {
                    $($(".layui-table-fixed-l .layui-table-body tbody tr")[index]).height($(val).height());
                    $($(".layui-table-fixed-r .layui-table-body tbody tr")[index]).height($(val).height());
                });
            }
        });

        //监听工具条
        table.on('tool(crontab-lists)', function (obj) {
            var operation = obj.event;
            var id = obj.data['id'];
            switch (operation) {
                case 'start':
                case 'stop':
                case 'restart':
                    var data={'operation':operation,'id':id}
                    like.ajax({
                        url: '<?php echo url("system.Crontab/operation"); ?>',
                        data: data,
                        type: "post",
                        success: function (res) {
                            if (res.code == 1) {
                                layui.layer.msg(res.msg, {
                                    offset: '15px'
                                    , icon: 1
                                    , time: 500
                                },function(){
                                    table.reload('crontab-lists'); //数据刷新
                                });
                            }
                        }
                    });
                    break;
                case 'delete':
                    layer.confirm('确定定时任务？', function(index){
                        like.ajax({
                            url:'<?php echo url("system.Crontab/del"); ?>',
                            data:{'id':id},
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
                    break;
                case 'edit':
                    layer.open({
                        type: 2
                        ,title: '编辑系统任务'
                        ,content: '<?php echo url("system.Crontab/edit"); ?>?id='+id
                        ,area: ['90%', '90%']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'crontab-submit-edit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);
                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field;
                                like.ajax({
                                    url:'<?php echo url("system.Crontab/edit"); ?>',
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
                                            table.reload('crontab-lists'); //数据刷新
                                        }
                                    }
                                });
                            });
                            submit.trigger('click');
                        }
                    })
            }
        });

        var active = {
            add: function(){
                layer.open({
                    type: 2
                    ,title: '添加系统任务'
                    ,content: '<?php echo url("system.Crontab/add"); ?>'
                    ,area: ['90%', '90%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'crontab-submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("system.Crontab/add"); ?>',
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
                                        table.reload('crontab-lists'); //数据刷新
                                    }
                                }
                            });
                        });

                        submit.trigger('click');
                    }
                });
            },
        }
        $('.layui-btn.layuiadmin-btn-crontab').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>
</body>
</html>
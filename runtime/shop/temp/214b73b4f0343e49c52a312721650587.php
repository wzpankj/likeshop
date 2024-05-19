<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\goods\shop_goods\lists.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
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

<style>
    .layui-table-cell {
        height: auto;
    }
</style>
<div class="wrapper">
    <div class="layui-card">
        <!--搜索条件-->
        <div class="layui-card-body layui-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">商品名称：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="goods_name" id="goods_name" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">商品分类:</label>
                    <div class="layui-input-block">
                        <select name="goods_category" id="goods_category"  placeholder="请选择商品分类" >
                            <option value="0">全部</option>
                            <?php foreach($goods_category as $val): ?>
                            <option value="<?php echo htmlentities($val['id']); ?>"><?php echo htmlentities($val['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="search">查询</button>
                    <button class="layui-btn layui-btn-sm layui-btn-primary" lay-submit lay-filter="clear-search">重置</button>
                    <button class="layui-btn layui-btn-sm layuiadmin-btn-goods layui-btn-primary " lay-submit lay-filter="export-file">导出</button>
                </div>
            </div>
        </div>

        <!-选项卡-->
        <div class="layui-tab layui-tab-card" lay-filter="like-tabs">
            <ul class="layui-tab-title">
                <li data-type='1' class="layui-this">全部(<?php echo htmlentities($statistics['all']); ?>)</li>
                <li data-type='2' >门店在售(<?php echo htmlentities($statistics['on_sale']); ?>)</li>
                <li data-type='3' >门店售罄(<?php echo htmlentities($statistics['sell_out']); ?>)</li>
                <li data-type='4' >门店停售(<?php echo htmlentities($statistics['stop_selling']); ?>)</li>
            </ul>

            <div class="layui-tab-content" style="padding: 0 15px;">
                <div style="padding-bottom: 10px;padding-top: 20px;" class="add">
                    <button id="upper" class="layui-btn layui-btn-sm layuiadmin-btn-goods layui-btn-danger" data-type="upper">上架销售</button>
                    <button id="lower" class="layui-btn layui-btn-sm layuiadmin-btn-goods layui-btn-danger" data-type="lower">停止销售</button>
                </div>

                <table id="like-table-lists" lay-filter="like-table-lists"></table>

                <script type="text/html" id="goods-info">
                    <img src="{{d.image}}" style="height:60px;width: 60px;margin-right: 5px;" class="image-show"> {{d.name}}
                </script>

                <script type="text/html" id="price-info">
                   ￥{{d.min_price}} ~ ￥{{d.max_price}}
                </script>

                <script type="text/html" id="table-operation">
                    <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">编辑</a>
                    {{# if(d.shop_status == 1){ }}
                    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="status_close">停止销售</a>
                    {{# }else{ }}
                    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="status_open">上架销售</a>
                    {{# } }}
                </script>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['table', 'form', 'element'], function(){
        var $ = layui.$
            ,form = layui.form
            ,table = layui.table
            ,element = layui.element;

        //监听搜索
        form.on('submit(search)', function(data){
            var field = data.field;
            //执行重载
            table.reload('like-table-lists', {
                where: field,
                page: {curr: 1}
            });
        });

        //清空查询
        form.on('submit(clear-search)', function(){
            $('#goods_name').val('');
            $('#goods_category').val('');
            form.render('select');
            //刷新列表
            table.reload('like-table-lists', {
                where: [], page: {curr: 1}
            });
        });


        $('.layui-btn.layuiadmin-btn-goods').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //切换列表
        element.on('tab(like-tabs)', function (data) {
            var type = $(this).attr('data-type');
            // 重置搜索模块
            $('#goods_name').val('');
            $('#goods_category').val('');
            form.render('select');
            // 重新获取商品列表
            getList(type);
            if (listType === '2') {
                $("#lower").show();
                $("#upper").hide();
            } else if (listType === '3')  {
                $("#lower").show();
                $("#upper").hide();
            } else if (listType === '4')  {
                $("#lower").hide();
                $("#upper").show();
            } else{
                $("#lower").hide();
                $("#upper").hide();
            }
        });

        //图片放大
        $(document).on('click', '.image-show', function () {
            var src = $(this).attr('src');
            like.showImg(src,600);
        });

        //获取列表
        getList(1); // 初始加载获取销售中的商品
        function getList(type) {
            like.tableLists('#like-table-lists', '<?php echo url("goods.shop_goods/lists"); ?>?type='+type, [
                {type: 'checkbox'}
                ,{title: '商品信息',width:280, templet: '#goods-info'}
                ,{title: '价格', width: 180, templet: '#price-info', align: 'center'}
                ,{field: 'total_stock',width: 100,title: '门店库存', align: 'center'}
                ,{field: 'sales_sum',width: 100,title: '门店销量', align: 'center'}
                ,{field: 'shop_status_desc',width: 150,title: '门店商品状态', align: 'center'}
                ,{field: 'status_desc',width: 150,title: '总部商品状态', align: 'center'}
                ,{field: 'sort',width: 80, title:'排序', align: 'center'}
                ,{fixed: 'right', title: '操作', width: 280, align: 'center', toolbar: '#table-operation'}
            ]);
        }

        //更新选项卡 统计数据
        function updateTabNumber() {
            like.ajax({
                url: '<?php echo url("goods.shop_goods/totalCount"); ?>',
                data: {},
                type: "GET",
                success: function (res) {
                    if (res.code === 1) {
                        $(".layui-tab-title li[data-type=1]").html("全部(" + res.data.all + ")");
                        $(".layui-tab-title li[data-type=2]").html("门店在售(" + res.data.on_sale + ")");
                        $(".layui-tab-title li[data-type=3]").html("门店售罄(" + res.data.sell_out + ")");
                        $(".layui-tab-title li[data-type=4]").html("门店停售(" + res.data.stop_selling + ")");
                    }
                }
            });
        }

        // 导出
        form.on('submit(export-file)', function(data){
            var type = $(this).attr('data-type');
            var field = data.field;
            $.ajax({
                url: '<?php echo url("goods.shop_goods/exportFile"); ?>?type='+ type,
                type: 'get',
                data: field,
                dataType: 'json',
                error: function() {
                    layer.msg('导出超时，请稍后再试!');
                },
                success: function(res) {
                    console.log(res.data)
                    table.exportFile(res.data.exportTitle,res.data.exportData, res.data.exportExt, res.data.exportName);
                },
                timeout: 15000
            });
            layer.msg('导出中请耐心等待~');
        });


        //事件
        var active = {
            //批量下架
            lower: function(){
                var checkStatus = table.checkStatus('like-table-lists');
                var checkData = checkStatus.data;
                var ids = [];
                // 取出选中的行ID
                checkData.forEach(function (item) {
                    ids.push(parseInt(item['id']));
                });
                if (ids.length <= 0) {
                    layui.layer.msg('未选择', {time: 1000});
                    return false;
                }
                // 提交数据
                like.ajax({
                    url:'<?php echo url("goods.shop_goods/lower"); ?>',
                    data:{"goods_ids":ids},
                    type:"post",
                    success:function(res) {
                        if(res.code === 1) {
                            layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                            updateTabNumber();
                            table.reload('like-table-lists', { where: [] });
                        } else {
                            layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                        }
                    }
                });

            },
            //批量上架
            upper: function(){
                var checkStatus = table.checkStatus('like-table-lists');
                var checkData = checkStatus.data;
                var ids = [];
                // 取出选中的行ID
                checkData.forEach(function (item) {
                    ids.push(parseInt(item['id']));
                });
                if (ids.length <= 0) {
                    layui.layer.msg('未选择', {time: 1000});
                    return false;
                }
                // 提交数据
                like.ajax({
                    url:'<?php echo url("goods.shop_goods/upper"); ?>',
                    data:{"goods_ids":ids},
                    type:"post",
                    success:function(res) {
                        if(res.code === 1) {
                            layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                            updateTabNumber();
                            table.reload('like-table-lists', { where: [] });
                        } else {
                            layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                        }
                    }
                });

            }
        };




        //监听工具条
        table.on('tool(like-table-lists)', function(obj){
            var id = obj.data.id;
            var name = obj.data.name;
            var ids = [];
            ids.push(parseInt(id));

            //停止销售
            if(obj.event === 'status_close') {
                layer.confirm('确定停止销售商品:'+'<span style="color: red">'+name+'</span>', function(index){
                    like.ajax({
                        url:'<?php echo url("goods.shop_goods/lower"); ?>',
                        data:{"goods_ids":ids},
                        type:"post",
                        success:function(res)
                        {
                            if(res.code === 1) {
                                layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                                updateTabNumber();
                                table.reload('like-table-lists', { where: [] });
                            } else {
                                layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                            }
                        }
                    });
                })
            }
            //上架销售
            if(obj.event === 'status_open') {
                layer.confirm('确定上架销售商品:'+'<span style="color: red">'+name+'</span>', function(index){
                    like.ajax({
                        url:'<?php echo url("goods.shop_goods/upper"); ?>',
                        data:{"goods_ids":ids},
                        type:"post",
                        success:function(res)
                        {
                            if(res.code === 1) {
                                layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                                updateTabNumber();
                                table.reload('like-table-lists', { where: [] });
                            } else {
                                layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                            }
                        }
                    });
                })
            }
            //编辑商品
            if(obj.event === 'edit') {
                layer.open({
                    type: 2
                    ,title: '编辑商品'
                    ,content: '<?php echo url("goods.shop_goods/edit"); ?>?goods_id='+id
                    ,area: ['90%', '90%']
                    ,btn: ['保存', '取消']
                    ,maxmin: true
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'edit-submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            like.ajax({
                                url:'<?php echo url("goods.shop_goods/edit"); ?>',
                                data:field,
                                type:"post",
                                success:function(res)
                                {
                                    if(res.code == 1)
                                    {
                                        layui.layer.msg(res.msg, {
                                            offset: '15px'
                                            , icon: 1
                                            , time: 1000
                                        },function () {
                                            window.location.href = window.location.href;
                                        });

                                    }
                                }
                            });
                        });
                        submit.trigger('click');
                    }
                    ,cancel: function(index, layero){
                        var window_index = index;
                        layer.confirm('商品未保存，确定关闭吗？', {
                            time: 0, //不自动关闭
                            btn: ['确定', '取消'],
                            yes: function(index){
                                layer.close(index);
                                layer.close(window_index);
                            }
                        });
                        return false;
                    }
                });
            }
        });

    });
</script>
</body>
</html>
<?php /*a:2:{s:62:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\goods\goods\lists.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
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
                            <option value="">全部</option>
                            <?php foreach($goods_category as $val): ?>
                            <option value="<?php echo htmlentities($val['id']); ?>"><?php echo htmlentities($val['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">门店商品:</label>
                    <div class="layui-input-block">
                        <select name="is_shop_goods" id="is_shop_goods" >
                            <option value="">全部</option>
                            <option value="1">是</option>
                            <option value="2">否</option>
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

            <div class="layui-tab-content" style="padding: 0 15px;">
                <div style="padding-bottom: 10px;padding-top: 20px;" class="add">
                    <button id="upper" class="layui-btn layui-btn-sm layuiadmin-btn-goods layui-btn-danger" data-type="upper">加入门店商品</button>
                    <button id="lower" class="layui-btn layui-btn-sm layuiadmin-btn-goods layui-btn-danger" data-type="lower">移出门店商品</button>
                </div>

                <table id="goods-lists" lay-filter="goods-lists"></table>

                <script type="text/html" id="goods-info">
                    <img src="{{d.image}}" style="height:60px;width: 60px;margin-right: 5px;" class="image-show"> {{d.name}}
                </script>

                <script type="text/html" id="price-info">
                   ￥{{d.min_price}} ~ ￥{{d.max_price}}
                </script>

                <script type="text/html" id="goods-operation">
                    {{# if(d.is_shop_goods == 1){ }}
                    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="status_close">移出门店商品</a>
                    {{# }else{ }}
                    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="status_open">加入门店商品</a>
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
            table.reload('goods-lists', {
                where: field,
                page: {curr: 1}
            });
        });

        //清空查询
        form.on('submit(clear-search)', function(){
            $('#goods_name').val('');
            $('#goods_category').val('');
            $('#is_shop_goods').val('');
            form.render('select');
            //刷新列表
            table.reload('goods-lists', {
                where: [], page: {curr: 1}
            });
        });

        //图片放大
        $(document).on('click', '.image-show', function () {
            var src = $(this).attr('src');
            like.showImg(src,600);
        });

        //获取列表
        getList(); // 初始加载获取销售中的商品
        function getList() {
            like.tableLists('#goods-lists', '<?php echo url("goods.goods/lists"); ?>', [
                {type: 'checkbox'}
                ,{title: '商品信息',width:280, templet: '#goods-info'}
                ,{field: 'goods_category_name', title: '商品分类',width:150}
                ,{title: '价格', width: 180, templet: '#price-info', align: 'center'}
                ,{field: 'is_shop_goods_desc',width: 150,title: '门店商品', align: 'center'}
                ,{field: 'status_desc',width: 150,title: '总部商品状态', align: 'center'}
                ,{field: 'sort',width: 80, title:'排序', align: 'center'}
                ,{fixed: 'right', title: '操作', width: 280, align: 'center', toolbar: '#goods-operation'}
            ]);
        }

        $('.layui-btn.layuiadmin-btn-goods').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //事件
        var active = {
            //批量移出门店商品
            lower: function(){
                var checkStatus = table.checkStatus('goods-lists');
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
                    url:'<?php echo url("goods.goods/removeShopGoods"); ?>',
                    data:{"goods_ids":ids},
                    type:"post",
                    success:function(res) {
                        if(res.code === 1) {
                            layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                            table.reload('goods-lists', { where: [] });
                        } else {
                            layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                        }
                    }
                });

            },
            //批量加入门店商品
            upper: function(){
                var checkStatus = table.checkStatus('goods-lists');
                var checkData = checkStatus.data;
                var ids = [];
                // 取出选中的行ID
                checkData.forEach(function (item) {
                    ids.push(parseInt(item['id']));
                });
                console.log(ids)
                if (ids.length <= 0) {
                    layui.layer.msg('未选择', {time: 1000});
                    return false;
                }
                // 提交数据
                like.ajax({
                    url:'<?php echo url("goods.goods/joinShopGoods"); ?>',
                    data:{"goods_ids":ids},
                    type:"post",
                    success:function(res) {
                        if(res.code === 1) {
                            layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                            table.reload('goods-lists', { where: [] });
                        } else {
                            layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                        }
                    }
                });

            }
        };

        //监听工具条
        table.on('tool(goods-lists)', function(obj){
            var id = obj.data.id;
            var name = obj.data.name;
            var ids = [];
            ids.push(parseInt(id));

            //移出门店商品
            if(obj.event === 'status_close') {
                layer.confirm('确定把商品:'+'<span style="color: red">'+name+'</span>移出门店', function(index){
                    like.ajax({
                        url:'<?php echo url("goods.goods/removeShopGoods"); ?>',
                        data:{"goods_ids":ids},
                        type:"post",
                        success:function(res)
                        {
                            if(res.code === 1) {
                                layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                                table.reload('goods-lists', { where: [] });
                            } else {
                                layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                            }
                        }
                    });
                })
            }
            //加入门店商品
            if(obj.event === 'status_open') {
                layer.confirm('确定把商品:'+'<span style="color: red">'+name+'</span>加入门店', function(index){
                    like.ajax({
                        url:'<?php echo url("goods.goods/joinShopGoods"); ?>',
                        data:{"goods_ids":ids},
                        type:"post",
                        success:function(res)
                        {
                            if(res.code === 1) {
                                layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                                table.reload('goods-lists', { where: [] });
                            } else {
                                layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
                            }
                        }
                    });
                })
            }
        });

        // 导出
        form.on('submit(export-file)', function(data){
            var field = data.field;
            $.ajax({
                url: '<?php echo url("goods.goods/exportFile"); ?>',
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



    });
</script>
</body>
</html>
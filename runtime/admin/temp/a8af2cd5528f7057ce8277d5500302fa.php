<?php /*a:2:{s:68:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\marketing\coupon\lists.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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


<div class="wrapper">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-collapse like-layui-collapse" lay-accordion="" style="border:1px dashed #c4c4c4">
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title like-layui-colla-title" style="background-color: #fff">操作提示</h2>
                    <div class="layui-colla-content layui-show">
                        <p>*优惠券在发放时间在只要为关闭未删除符合条件就能领取。</p>
                        <p>*优惠券已关闭不能继续领取，已发放的优惠券在用券时间内能继续使用</p>
                        <p>*优惠券已删除不能继续领取，已发放的优惠券不能继续使用</p>
                    </div>
                </div>
            </div>
            <!--搜索模块-->
            <div class="layui-form" style="margin-top: 30px;">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">优惠券名称：</label>
                        <div class="layui-input-inline" style="width: 200px;">
                            <input type="text" id="name" name="name" placeholder="请输入优惠券名称" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">门店信息:</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" id="shop" name="shop"  autocomplete="off"  placeholder="请输入门店名称/编号查询">
                        </div>
                    </div>
<!--                    <div class="layui-inline">-->
<!--                        <label class="layui-form-label">领取方式：</label>-->
<!--                        <div class="layui-input-inline" style="width: 200px;">-->
<!--                            <select name="get_type" id="get_type">-->
<!--                                <option value="">全部</option>-->
<!--                                <option value="1">直接领取</option>-->
<!--                                <option value="2">商家赠送</option>-->
<!--                            </select>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="layui-inline">
                        <label class="layui-form-label">创建时间:</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input time" id="start_time" name="start_time"  autocomplete="off">
                        </div>
                        <div class="layui-input-inline" style="margin-right: 5px;width: 10px;">
                            <label class="layui-form-mid">-</label>
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input time" id="end_time" name="end_time"  autocomplete="off">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layuiadmin-btn-coupon <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="search">查询</button>
                        <button class="layui-btn layui-btn-sm layuiadmin-btn-coupon layui-btn-primary }" lay-submit lay-filter="clear-search">重置</button>
                    </div>
                </div>
            </div>
        </div>
        <!--选项卡-->
        <div class="layui-tab layui-tab-card" lay-filter="tab-all">

            <ul class="layui-tab-title">
                <li data-type='0' class="layui-this">全部<span class="all"></span></li>
                <li data-type='1' >未开始<span class="not_start"></span></li>
                <li data-type='2' >进行中<span class="ing"></span></li>
                <li data-type='3' >已结束<span class="end"></span></li>
            </ul>

            <div class="layui-tab-item layui-show">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <!--门店信息-->
                        <script type="text/html" id="shop_info">
                            <div>
                                <p>{{d.shop_sn}} — {{d.shop_name}}</p>
                            </div>
                        </script>
                        <!--                        动态表格-->
                        <table id="like-table-lists" lay-filter="like-table-lists"></table>
                        <!--                        行工具模板 功能按钮-->
                        <script type="text/html" id="table-operation">
                            <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="detail">详情</a>
                            <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="log">发放记录</a>
                        </script>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    .layui-table-cell {
        height: auto;
    }
    .layui-form-label{
        width: 86px;
    }
</style>
<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['table','laydate','form'], function(){
        var $ = layui.$
            ,form = layui.form
            ,table = layui.table
            ,laydate = layui.laydate
            ,element = layui.element;

        // 监听行工具栏按钮
        $('.layui-btn.layuiadmin-btn-coupon').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //日期时间范围
        laydate.render({
            elem: '#start_time'
            , type: 'datetime'
            ,theme: '#1E9FFF'
        });

        laydate.render({
            elem: '#end_time'
            , type: 'datetime'
            ,theme: '#1E9FFF'
        });

        //事件
        var active = {

        }


        //监听搜索
        form.on('submit(search)', function(data){
            var field = data.field;
            //执行重载
            table.reload('like-table-lists', {
                where: field,
                page: {
                    curr: 1 //重新从第 1 页开始
                }
            });
        });
        //清空查询
        form.on('submit(clear-search)', function(){
            $('#name').val('');  //清空输入框
            $('#get_type').val('');
            $('#shop').val('');
            $('#start_time').val('');
            $('#end_time').val('');
            form.render('select');
            //刷新列表
            table.reload('like-table-lists', {
                where: [],
                page: {
                    curr: 1 //重新从第 1 页开始
                }
            });
        });
        //获取列表
        getList('0')
        //切换列表
        element.on('tab(tab-all)', function (data) {
            var type = $(this).attr('data-type');
            getList(type)
        });

        //监听工具条
        table.on('tool(like-table-lists)', function(obj){
            var id = obj.data.id;
            if(obj.event === 'detail'){
                layer.open({
                    type: 2
                    ,title: '优惠券详情'
                    ,content: '<?php echo url("marketing.coupon/detail"); ?>?id='+id
                    ,area: ['90%', '90%']
                })
            }
            //发放记录
            if(obj.event === 'log'){
                layer.open({
                    type: 2
                    ,title: '发放记录'
                    ,content: '<?php echo url("marketing.coupon/couponLog"); ?>?id='+id
                    ,area: ['90%', '90%']
                })

            }
            if(obj.event === 'status'){
                var name = obj.data.name;

                if(obj.data.status == 1) {
                    var status = 2;
                    var tips = '开始领取：'+'<span style="color: red">'+name+'</span> ';
                }else {
                    var status = 3;
                    var tips = '确定结束领取：'+'<span style="color: red">'+name+'</span>'  + '结束领取的优惠券不能重新开始领取，请谨慎操作';
                }

                layer.confirm(tips, function(index){
                    like.ajax({
                        url:'<?php echo url("marketing.coupon/changeStatus"); ?>',
                        data:{id:id,status:status},
                        type:"post",
                        success:function(res)
                        {
                            if(res.code == 1)
                            {
                                layui.layer.msg(res.msg, {
                                    offset: '15px'
                                    , icon: 1
                                    , time: 1000
                                });
                                layer.close(index); //关闭弹层
                                table.reload('like-table-lists'); //数据刷新
                            }
                        }
                    });
                    layer.close(index);


                })

            }
        });

        function getList(type) {
            layui.define(['table', 'form'], function(exports){
                var $ = layui.$
                    ,table = layui.table
                    ,form = layui.form;

                var cols  = [
                    {field: 'shop', title: '门店信息', align: 'center',templet:'#shop_info',width:230},
                    {field: 'name',align: 'center',width:120,title: '优惠券名称'}
                    ,{field: 'discount_money_desc',align: 'center',width:200, title: '优惠券金额'}
                    ,{field: 'use_time_desc',align: 'center',width:300, title:'用券时间'}
                    ,{field: 'get_tips_desc',align: 'center',width:160, title:'已领取/剩余'}
                    ,{field: 'use_goods_type_desc',align: 'center',width:160, title: '使用场景'}
                    ,{field: 'status_desc',align: 'center',width:120,title: '状态'}
                    ,{field: 'create_time',align: 'center',width:160, title:'创建时间'}
                    ,{fixed: 'right', title: '操作',width:380, align: 'center', toolbar: '#table-operation'}
                ];

                table.render({
                    id:'like-table-lists'
                    ,elem: '#like-table-lists'
                    ,url: '<?php echo url(""); ?>?type='+type  //模拟接口
                    ,cols: [cols]
                    ,page:true
                    ,text: {none: '暂无数据！'}
                    ,parseData: function(res){ //将原始数据解析成 table 组件所规定的数据
                        $(".all").html("("+res.data.statistics.coupon_all+")");
                        $(".not_start").html("("+res.data.statistics.coupon_not_start+")");
                        $(".ing").html("("+res.data.statistics.coupon_ing+")");
                        $(".end").html("("+res.data.statistics.coupon_end+")");
                        return {
                            "code":res.code,
                            "msg":res.msg,
                            "count": res.data.count, //解析数据长度
                            "data": res.data.list, //解析数据列表
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

            });
        }

    });
</script>
</body>
</html>
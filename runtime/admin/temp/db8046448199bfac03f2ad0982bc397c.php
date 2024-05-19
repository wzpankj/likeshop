<?php /*a:2:{s:68:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\order\shop_order\lists.html";i:1681780582;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
        height:auto;
    }
    .goods-content>div:not(:last-of-type) {
        border-bottom:1px solid #DCDCDC;
    }
    .goods-data::after{
        display: block;
        content: '';
        clear: both;
    }
    .goods_name_hide{
        overflow:hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
    }
    .operation-btn {
        margin: 5px;
    }
    .table-operate{
        text-align: left;
        font-size:14px;
        padding:0 5px;
        height:auto;
        overflow:visible;
        text-overflow:inherit;
        white-space:normal;
        word-break: break-all;
    }
</style>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
        <div class="layui-collapse like-layui-collapse" lay-accordion="" style="border:1px dashed #c4c4c4">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title like-layui-colla-title" style="background-color: #fff">操作提示</h2>
                <div class="layui-colla-content layui-show">
                    <p>*到店订单列表。</p>
                    <p>*订单状态有待付款，准备中，待取餐，已完成，已关闭。</p>
                    <p>*待付款订单取消后则为已关闭。待付款订单支付后则为准备中。准备中订单完成后则为待取餐。待取餐订单收货后则为已完成。</p>
                </div>
            </div>
        </div>
        </div>
        <div class="layui-tab layui-tab-card" lay-filter="tab-all">

            <ul class="layui-tab-title">
                <li data-type='0' class="layui-this">全部（<?php echo htmlentities($statistics['all']); ?>）</li>
                <li data-type="1">待付款（<?php echo htmlentities($statistics['wait_pay']); ?>）</li>
                <li data-type="2">准备中（<?php echo htmlentities($statistics['preparing']); ?>）</li>
                <li data-type="3">待取餐（<?php echo htmlentities($statistics['wait_take']); ?>）</li>
                <li data-type="4">已完成（<?php echo htmlentities($statistics['finish']); ?>）</li>
                <li data-type="5">已关闭（<?php echo htmlentities($statistics['close']); ?>）</li>
                <li data-type="6">退款失败（<?php echo htmlentities($statistics['refund_fail']); ?>）</li>
            </ul>
            <div class="layui-card-body layui-form">
                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-inline">
                            <label class="layui-form-label">门店信息:</label>
                            <div class="layui-input-block">
                                <input type="text" name="shop_info" id="shop_info" placeholder="请输入门店名称/编号查询"
                                       autocomplete="off" class="layui-input" style="width: 188px;">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">订单信息:</label>
                            <div class="layui-input-block">
                                <input type="text" name="order_sn" id="order_sn" placeholder="请输入订单编号查询"
                                       autocomplete="off" class="layui-input" style="width: 188px;">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">用户信息:</label>
                            <div class="layui-input-block">
                                <input type="text" name="user_info" id="user_info" placeholder="请输入用户昵称/用户编号"
                                       autocomplete="off" class="layui-input" style="width: 188px;">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">商品名称:</label>
                            <div class="layui-input-block">
                                <input type="text" name="goods_name" id="goods_name" placeholder="请输入商品名称"
                                       autocomplete="off" class="layui-input" style="width: 188px;">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">就餐方式:</label>
                            <div class="layui-input-block">
                                <select name="dining_type" id="dining_type">
                                    <option value="">全部</option>
                                    <?php foreach($dining_type as $item => $val): ?>
                                    <option value="<?php echo htmlentities($item); ?>"><?php echo htmlentities($val); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="layui-row">

                        <div class="layui-inline">
                            <label class="layui-form-label">付款方式:</label>
                            <div class="layui-input-block">
                                <select name="pay_way" id="pay_way">
                                    <option value="">全部</option>
                                    <?php foreach($pay_way as $item => $val): ?>
                                    <option value="<?php echo htmlentities($item); ?>"><?php echo htmlentities($val); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">下单时间:</label>
                            <div class="layui-input-inline">
                                <div class="layui-input-inline">
                                    <input type="text" name="start_time" class="layui-input" id="start_time"
                                           placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="layui-input-inline" style="margin-right: 5px;width: 20px;">
                                <label class="layui-form-mid">至</label>
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="end_time" class="layui-input" id="end_time"
                                       placeholder="" autocomplete="off">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-sm layuiadmin-btn-ad <?php echo htmlentities($view_theme_color); ?>" lay-submit
                                    lay-filter="search">查询
                            </button>
                            <button class="layui-btn layui-btn-sm layuiadmin-btn-ad layui-btn-primary" lay-submit
                                    lay-filter="export">导出
                            </button>
                            <button class="layui-btn layui-btn-sm layuiadmin-btn-ad layui-btn-primary " lay-submit
                                    lay-filter="clear-search">重置
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="layui-tab-item layui-show">
                <div class="layui-card">
                    <div class="layui-card-body">

                        <table id="like-table-lists" lay-filter="like-table-lists"></table>

                        <script type="text/html" id="table-operation" >
                            <div class="table-operate">
                                <a class="layui-btn layui-btn-primary layui-btn-sm operation-btn" lay-event="detail">订单详情</a>
                            </div>
                        </script>

                        <script type="text/html" id="image">
                            <img src="{{d.image}}" style="height:80px;width: 80px" class="image-show">
                        </script>

                        <!--门店信息-->
                        <script type="text/html" id="shop">
                            <div>
                                <p>{{d.shop_sn}} — {{d.shop_name}}</p>
                            </div>
                        </script>

                        <!--订单信息-->
                        <script type="text/html" id="order">
                            <div style="text-align: left">
                                <p>订单编号:{{d.order_sn}}</p>
                                <p>订单状态:{{d.order_status_text}}</p>
                                <p>下单时间:{{d.create_time}}</p>
                            </div>
                        </script>

                        <!--会员信息-->
                        <script type="text/html" id="user">
                            <img src="{{d.user.avatar}}" style="height:80px;width: 80px" class="image-show">
                            <div class="layui-input-inline"  style="text-align: left;">
                                <p>会员编号:{{d.user.sn}}</p>
                                <p style="width: 180px;text-overflow:ellipsis;overflow: hidden">会员昵称:{{d.user.nickname}}</p>
                            </div>
                        </script>

                        <!--商品信息-->
                        <script type="text/html" id="goods">
                            <div class="goods-content">
                                {{#  layui.each(d.order_goods, function(index, item){ }}
                                <div style="text-align: left;" class="goods-data">
                                    <div>
<!--                                        <img src="{{ item.image }}" style="height:80px;width: 80px;margin-right: 10px;" class="image-show layui-col-md4">-->
                                        <div class="layui-input-inline layui-col-md8" style="width: 100%">
                                            <span class="layui-col-md7 goods_name_hide" style="width: 100%">{{ item.goods_name }}</span>
                                            <span class="layui-col-md7 goods_name_hide" style="width: 100%">
                                                <span style="width: 245px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;float: left;display: block;">{{ item.spec_value }}</span>
                                                <span style="float: right">x{{ item.goods_num }}</span>
                                            </span>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>

<!--                                    <div style="display: block">-->
<!--                                        <span>单价:{{ item.goods_price }}</span>-->
<!--                                        <span>实付:{{ item.goods_price }}</span>-->
<!--                                        <span>数量:{{ item.goods_num }}</span>-->
<!--                                        <span>总计实付:{{ item.total_price }}</span>-->
<!--                                    </div>-->
                                </div>
                                {{#  }); }}
                            </div>
                        </script>

                        <!--商品数量-->
                        <script type="text/html" id="goods_num">
                            {{ d.total_num }}件
                        </script>

                        <!--收货信息-->
                        <script type="text/html" id="delivery">
                            <div style="text-align: left">
                                <p>就餐方式:{{d.dining_type_text}}</p>
                                <p>取餐时间:{{d.appoint_time}}</p>
                                <p>取餐码:{{d.take_code}}</p>
                                <p>联系人:{{d.consignee}}</p>
                                <p>联系方式:{{d.mobile}}</p>
                            </div>
                        </script>

                        <!--金额信息-->
                        <script type="text/html" id="amount">
                            <div style="text-align: left">
                                <p>配送费用:{{d.delivery_amount}}</p>
                                <p>商品金额:{{d.goods_amount}}</p>
                                <p>应付金额:{{d.order_amount}}</p>
                            </div>
                        </script>

                        <!--支付信息-->
                        <script type="text/html" id="pay_info">
                            <div style="text-align: left">
                                <p>支付方式:{{d.pay_way_text}}</p>
                                <p>支付状态:{{d.pay_status_text}}</p>
                                <p>支付时间:{{d.pay_time}}</p>
                                <p>支付流水号:{{#  if(d.transaction_id != null){ }}{{d.transaction_id}}{{#  }else{ }}-{{#  } }}</p>
                            </div>
                        </script>

                        <!--退款信息信息-->
                        <script type="text/html" id="refund_info">
                            <div style="text-align: left">
                                <p>已退款金额:{{#  if(d.refund_amount != null){ }}{{d.refund_amount}}{{#  }else{ }}-{{#  } }}</p>
                                <p>剩余退款金额:{{#  if(d.surplus_refund_amount != null){ }}{{d.surplus_refund_amount}}{{#  }else{ }}-{{#  } }}</p>
                                <p>最后退款状态:{{d.refund_status_desc}}</p>
                                <p>最后退款时间:{{#  if(d.refund_time != null){ }}{{d.refund_time}}{{#  }else{ }}-{{#  } }}</p>
                            </div>
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
      layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function(){
        var $ = layui.$
            , form = layui.form
            , table = layui.table
            , element = layui.element
            , laydate = layui.laydate;

        //图片放大
        $(document).on('click', '.image-show', function () {
            var src = $(this).attr('src');
            like.showImg(src,600);
        });

        //监听搜索
        form.on('submit(search)', function (data) {
            var field = data.field;
            //执行重载
            table.reload('like-table-lists', {
                where: field,
                page: {
                    curr: 1
                }
            });
            updateTabNumber(field);
        });
        //清空查询
        form.on('submit(clear-search)', function () {
            $('#shop_info').val('');
            $('#order_sn').val('');
            $('#user_info').val('');
            $('#goods_name').val('');
            $('#dining_type').val('');
            $('#pay_way').val('');
            $('#start_time').val('');
            $('#end_time').val('');
            form.render('select');
            //刷新列表
            table.reload('like-table-lists', {
                where: [],
                page: {
                    curr: 1
                }
            });
        });

        //日期时间范围
        laydate.render({
            elem: '#start_time'
            ,type: 'datetime'
            ,trigger: 'click'
            ,done: function (value, date, endDate) {
                var startDate = new Date(value).getTime();
                var endTime = new Date($('#end_time').val()).getTime();
                if (endTime < startDate) {
                    layer.msg('结束时间不能小于开始时间');
                    $('#start_time').val($('#end_time').val());
                }
            }
        });

        laydate.render({
            elem: '#end_time'
            ,type: 'datetime'
            ,trigger: 'click'
            ,done: function (value, date, endDate) {
                var startDate = new Date($('#start_time').val()).getTime();
                var endTime = new Date(value).getTime();
                if (endTime < startDate) {
                    layer.msg('结束时间不能小于开始时间');
                    $('#end_time').val($('#start_time').val());
                }
            }
        });

        //获取列表
        getList('');
        //切换列表
        element.on('tab(tab-all)', function (data) {
            $('#shop_info').val('');
            $('#order_sn').val('');
            $('#user_info').val('');
            $('#goods_name').val('');
            $('#dining_type').val('');
            $('#pay_way').val('');
            $('#start_time').val('');
            $('#end_time').val('');
            form.render('select');
            var type = $(this).attr('data-type');
            getList(type);
            if (type !== ''){
                $('.order_status').hide();
            }else {
                $('.order_status').show();
            }
        });

        function getList(type) {
            table.render({
                elem: '#like-table-lists'
                , url: '<?php echo url("order.shop_order/lists"); ?>?type=' + type
                , cols: [[
                    {type: 'checkbox'}
                    ,{field:'id',title: 'id',width:60,align: 'center'}
                    , {field: 'shop', title: '门店信息', align: 'center',templet:'#shop',width:230}
                    , {field: 'order', title: '订单信息', align: 'center',templet:'#order',width:230}
                    , {field: 'user', title: '用户信息', templet:'#user',width:300}
                    , {field: 'order_goods', title: '商品信息', align: 'center',templet:'#goods',width:300}
                    , {field: 'total_num', title: '商品数量', align: 'center',templet:'#goods_num',width:100}
                    , {field: 'delivery', title: '收货信息', align: 'center',templet:'#delivery',width:200}
                    , {field: 'total_amount', title: '订单金额', align: 'center',templet:'#amount',width:150}
                    , {field: 'pay_info', title: '支付信息',templet:'#pay_info', align: 'center',width:200}
                    , {field: 'refund_info', title: '退款信息',templet:'#refund_info', align: 'center',width:200}
                    , {fixed: 'right', title: '操作', width: 120, align: 'center', toolbar: '#table-operation'}
                ]]
                , page: true
                , text: {none: '暂无数据！'}
                ,response: {
                    statusCode: 1 
                  } 
                , parseData: function (res) {
                    return {
                        "code": res.code,
                        "msg": res.msg,
                        "count": res.data.count,
                        "data": res.data.lists,
                    };
                }
                ,done: function(res, curr, count){
                    // 解决操作栏因为内容过多换行问题
                    $(".layui-table-main tr").each(function (index, val) {
                        $($(".layui-table-fixed-l .layui-table-body tbody tr")[index]).height($(val).height());
                        $($(".layui-table-fixed-r .layui-table-body tbody tr")[index]).height($(val).height());
                    });
                }
            });
        }

        //导出
        form.on('submit(export)', function(data){
          var type = $(this).attr('data-type');
          var field = data.field;
          $.ajax({
              url: '<?php echo url("order.shop_order/exportFile"); ?>?type='+ type,
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

        $('.layui-btn.layuiadmin-btn-goods').on('click', function(){
          var type = $(this).data('type');
          active[type] ? active[type].call(this) : '';
        });

        //监听工具条
        table.on('tool(like-table-lists)', function (obj) {
            var id = obj.data.id;
            //详情
            if(obj.event === 'detail'){
                layer.open({
                    type: 2
                    ,title: '订单详情'
                    ,content: '<?php echo url("order.shop_order/detail"); ?>?id='+id
                    ,area: ['90%', '90%']
                    ,yes: function(index, layero){
                        table.reload('like-table-lists');
                    }
                })
            }
        });

          //更新选项卡 统计数据
          function updateTabNumber(field) {
              like.ajax({
                  url: '<?php echo url("order.shop_order/totalCount"); ?>',
                  data: field,
                  type: "GET",
                  success: function (res) {
                      if (res.code === 1) {
                          $(".layui-tab-title li[data-type=0]").html("全部(" + res.data.all + ")");
                          $(".layui-tab-title li[data-type=1]").html("待付款(" + res.data.wait_pay + ")");
                          $(".layui-tab-title li[data-type=2]").html("准备中(" + res.data.preparing + ")");
                          $(".layui-tab-title li[data-type=3]").html("待取餐(" + res.data.wait_take + ")");
                          $(".layui-tab-title li[data-type=4]").html("已完成(" + res.data.finish + ")");
                          $(".layui-tab-title li[data-type=5]").html("已关闭(" + res.data.close + ")");
                          $(".layui-tab-title li[data-type=6]").html("退款失败(" + res.data.refund_fail + ")");
                      }
                  }
              });
          }
    });
</script>
</body>
</html>
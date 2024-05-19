<?php /*a:2:{s:68:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\order\shop_order\detail.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout2.html";i:1676699056;}*/ ?>
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
    .div-flex {
        display: flex;
        align-items: center;
        justify-content: left;
    }

    .width-160 {
        width: 200px;
    }
    .layui-table th {
        text-align: center;
    }
    .table-margin{
        margin-left: 50px;
        margin-right: 50px;
        text-align: center;
    }
    .image{
        height:80px;
        width: 80px;
    }

    .mt50{
        margin-left: 50px;
    }

</style>

<div class="layui-card-body" >
    <!--基本信息-->
    <div class="layui-form" lay-filter="layuiadmin-form-order" id="layuiadmin-form-order" >
    <input type="hidden" class="order_id" name="order_id" value="<?php echo htmlentities($detail['id']); ?>">

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>订单信息</legend>
        </fieldset>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label ">订单编号:</label>
        <div class="width-160"><?php echo htmlentities($detail['order_sn']); ?></div>
        <label class="layui-form-label ">订单状态:</label>
        <div class="width-160"><?php echo htmlentities($detail['order_status_text']); ?></div>
        <label class="layui-form-label ">下单时间:</label>
        <div class="width-160"><?php echo htmlentities($detail['create_time']); ?></div>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label ">订单类型:</label>
        <div class="width-160"><?php echo htmlentities($detail['order_type_text']); ?></div>
        <label class="layui-form-label ">订单来源:</label>
        <div class="width-160">微信小程序</div>
    </div>

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>用户信息</legend>
        </fieldset>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label ">用户编号:</label>
        <div class="width-160"><?php echo htmlentities($detail['user']['sn']); ?></div>
        <label class="layui-form-label ">用户昵称:</label>
        <div class="width-160"><?php echo htmlentities($detail['user']['nickname']); ?></div>
    </div>

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>商品信息</legend>
        </fieldset>
    </div>

    <div class="layui-form-item table-margin">
        <table class="layui-table">
            <colgroup>
                <col width="250">
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="100">
            </colgroup>
            <thead>
            <tr>
                <th>商品信息</th>
                <th>单价</th>
                <th>实付单价</th>
                <th>数量</th>
                <th>总计实付</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($detail['order_goods'] as $k => $goods): ?>
            <tr>
                <td>
                    <div style="text-align: left">
                        <div class="layui-col-md3">
                            <img src="<?php echo htmlentities($goods['goods_image']); ?>" class="image-show image" >
                        </div>
                        <div class="layui-col-md9">
                            <p style="margin-top: 10px"><?php echo htmlentities($goods['goods_name']); ?></p>
                            <br>
                            <p><?php echo htmlentities($goods['spec_value']); ?></p>
                        </div>
                    </div>
                </td>
                <td>￥<?php echo htmlentities($goods['goods_price']); ?></td>
                <td>￥<?php echo htmlentities($goods['pay_price']); ?></td>
                <td><?php echo htmlentities($goods['goods_num']); ?></td>
                <td>￥<?php echo htmlentities($goods['total_price']); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">合计</td>
                <td style="text-align: center">
                    <p><?php echo htmlentities($detail['total_num']); ?></p>
                </td>
                <td style="text-align: left;">
                    <p>总计金额：￥<?php echo htmlentities($detail['goods_price']); ?></p>
                    <p>优惠金额：￥<?php echo htmlentities($detail['discount_amount']); ?></p>
                    <p>实付金额：￥<?php echo htmlentities($detail['goods_pay_amount']); ?></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>收货信息</legend>
        </fieldset>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label ">就餐方式:</label>
        <div class="width-160"><?php echo htmlentities($detail['dining_type_text']); ?></div>
        <label class="layui-form-label ">取餐时间:</label>
        <div class="width-160"><?php echo htmlentities($detail['appoint_time']); ?></div>
        <label class="layui-form-label ">取餐码:</label>
        <div class="width-160"><?php echo htmlentities($detail['take_code']); ?></div>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label ">联系人:</label>
        <div class="width-160"><?php echo htmlentities($detail['consignee']); ?></div>
        <label class="layui-form-label ">手机号码:</label>
        <div class="width-160"><?php echo htmlentities($detail['mobile']); ?></div>
    </div>


    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>买家留言</legend>
        </fieldset>
    </div>

    <div class="layui-form-item table-margin">
        <textarea class="layui-textarea" disabled><?php echo htmlentities($detail['user_remark']); ?></textarea>
    </div>

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>订单金额</legend>
        </fieldset>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label ">订单总额:</label>
        <div class="width-160">￥<?php echo htmlentities($detail['total_amount']); ?></div>
        <label class="layui-form-label ">配送费用:</label>
        <div class="width-160">￥<?php echo htmlentities($detail['delivery_amount']); ?></div>
        <label class="layui-form-label ">优惠金额:</label>
        <div class="width-160">￥<?php echo htmlentities($detail['discount_amount']); ?></div>
        <label class="layui-form-label ">应付金额:</label>
        <div class="width-160">￥<?php echo htmlentities($detail['order_amount']); ?></div>
    </div>

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>退款信息</legend>
        </fieldset>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label " style="width: 100px;">已退款金额:</label>
        <div class="width-160"><?php if($detail['refund_amount'] != null): ?>￥<?php echo htmlentities($detail['refund_amount']); else: ?>-<?php endif; ?></div>
        <label class="layui-form-label " style="width: 100px;">剩余退款金额:</label>
        <div class="width-160"><?php if($detail['surplus_refund_amount'] != null): ?>￥<?php echo htmlentities($detail['surplus_refund_amount']); else: ?>-<?php endif; ?></div>
        <label class="layui-form-label " style="width: 100px;">最后退款状态:</label>
        <div class="width-160"><?php echo htmlentities($detail['refund_status_desc']); ?></div>
    </div>

    <div class="layui-form-item div-flex">
        <label class="layui-form-label " style="width: 100px;">最后退款时间:</label>
        <div class="width-160"><?php echo htmlentities($detail['refund_time']); ?></div>
    </div>

    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>订单操作</legend>
        </fieldset>
    </div>

    <div class="layui-form-item div-flex ">
        <div class="layui-input-block ">
            <?php if($detail['order_status'] <= 1): ?>
            <button type="submit" class="layui-btn layui-btn-sm layui-btn-danger width_160 " id="cancel">取消订单</button>
            <?php endif; if($detail['order_status'] == 1): ?>
            <button type="submit" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="notice">通知取餐</button>
            <?php endif; if($detail['order_status'] == 2): ?>
            <button type="submit" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="confirm">确认取餐</button>
            <?php endif; ?>

            <button type="submit" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="remarks">备注</button>
            <button type="button" class="layui-btn layui-btn-sm layui-btn-primary width_160 " id="back">返回</button>
        </div>
    </div>


    <div class="layui-form-item">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>订单日志</legend>
        </fieldset>
    </div>

    <div class="layui-form-item table-margin">
        <table class="layui-table">
            <colgroup>
                <col width="100">
                <col width="100">
                <col width="250">
            </colgroup>
            <thead>
            <tr>
                <th>日期</th>
                <th>动作类型</th>
                <th>日志内容</th>
            </tr>
            </thead>
            <tbody>
                <?php if(empty($detail['order_log']) || (($detail['order_log'] instanceof \think\Collection || $detail['order_log'] instanceof \think\Paginator ) && $detail['order_log']->isEmpty())): ?>
                    <tr>
                        <td colspan="3">暂无记录</td>
                    </tr>
                <?php else: foreach($detail['order_log'] as $k => $log): ?>
                    <tr>
                        <td><?php echo htmlentities($log['create_time']); ?></td>
                        <td><?php echo htmlentities($log['channel_text']); ?></td>
                        <td><?php echo htmlentities($log['content']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


</div>
</div>

<script type="text/javascript">
    //注意：选项卡 依赖 element 模块，否则无法进行功能性操作

    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function(){
        var $ = layui.$;

        //主图放大
        $(document).on('click', '.image-show', function () {
            var src = $(this).attr('src');
            like.showImg(src,600);
        });


        $('#back').click(function () {
            var index=parent.layer.getFrameIndex(window.name); //获取当前窗口的name
            parent.layer.close(index);
            parent.layui.table.reload('like-table-lists');
            return true;
        });

        //取消订单
        $('#cancel').click(function () {
            var id = $('.order_id').val();
            var ids = [];
            ids.push(id);
            layer.confirm('确认取消订单吗?', {
                btn: ['确认','取消'] //按钮
            }, function(){
                like.ajax({
                    url: '<?php echo url("order.shop_order/cancel"); ?>'
                    , data: {'order_ids': ids}
                    , type: 'post'
                    , success: function (res) {
                        if (res.code == 1) {
                            layui.layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            },function () {
                                location.reload();
                            });
                        }
                    }
                });
            });
        });
        //通知取餐
        $('#notice').click(function () {
            var id = $('.order_id').val();
            var ids = [];
            ids.push(id);
            layer.confirm('确认通知取餐吗?', {
                btn: ['确认','取消'] //按钮
            }, function(){
                like.ajax({
                    url: '<?php echo url("order.shop_order/notice"); ?>'
                    , data: {'order_ids': ids}
                    , type: 'post'
                    , success: function (res) {
                        if (res.code == 1) {
                            layui.layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            },function () {
                                location.reload();
                            });
                        }
                    }
                });
            });
        });
        //确认取餐
        $('#confirm').click(function () {
            var id = $('.order_id').val();
            var ids = [];
            ids.push(id);
            layer.confirm('确认取餐吗?', {
                btn: ['确认','取消'] //按钮
            }, function(){
                like.ajax({
                    url: '<?php echo url("order.shop_order/confirm"); ?>'
                    , data: {'order_ids': ids}
                    , type: 'post'
                    , success: function (res) {
                        if (res.code == 1) {
                            layui.layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            },function () {
                                location.reload();
                            });
                        }
                    }
                });
            });
        });
        //备注
        $('#remarks').click(function () {
            var id = $('.order_id').val();
            like.ajax({
                url: '<?php echo url("order.shop_order/remarks"); ?>'
                , data: {'id': id}
                , type: 'get'
                , success: function (res) {
                    if (res.code === 1) {
                        layer.prompt({title: '备注信息', formType: 2, value: res.data[0].order_remarks}, function(value, index){
                            layer.close(index);
                            like.ajax({
                                url: '<?php echo url("order.shop_order/remarks"); ?>'
                                , data: {'id': id, "order_remarks": value }
                                , type: 'post'
                                , success: function (res) {
                                    if (res.code === 1) {
                                        layui.layer.msg(res.msg, {
                                            offset: '15px'
                                            , icon: 1
                                            , time: 1000
                                        });
                                    }
                                }
                            });

                        });
                    }
                }
            });
        });


    });
</script>
</body>
</html>
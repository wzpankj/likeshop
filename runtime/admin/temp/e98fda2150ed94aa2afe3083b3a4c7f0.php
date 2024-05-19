<?php /*a:2:{s:71:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\setting\transaction\index.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
    .layui-form-label {
        width: 220px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
<!--表单区域-->
            <div class="layui-form" style="margin-top: 15px;">
                <div class="layui-form-item">
                    <lable class="layui-form-label">商品库存显示:</lable>
                    <div class="layui-input-block">
                        <input type="radio" name="is_show_stock" value="1" title="显示" <?php if($config['is_show_stock'] ==1): ?>checked<?php endif; ?> />
                        <input type="radio" name="is_show_stock" value="0" title="不显示" <?php if($config['is_show_stock'] == 0): ?>checked<?php endif; ?> />
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">商品详情显示库存数量</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label">库存扣减时机:</lable>
                    <div class="layui-input-block">
                        <input type="radio" name="dec_stock" value="1" title="提交订单扣库存" <?php if($config['dec_stock'] ==1): ?>checked<?php endif; ?> />
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">商品库存扣减的时机，默认选择订单支付扣库存</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label">取消订单退回库存:</lable>
                    <div class="layui-input-block">
                        <input type="radio" name="return_stock" value="0" title="无需退回库存" <?php if($config['return_stock'] ==0): ?>checked<?php endif; ?> />
                        <input type="radio" name="return_stock" value="1" title="需要退回库存" <?php if($config['return_stock'] ==1): ?>checked<?php endif; ?> />
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">订单取消时是否需要退回库存，默认无需退回库存</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label">未付款订单自动取消时长:</lable>
                    <div class="layui-inline">
                        <input type="number" min="0" name="unpaid_order_cancel_time" value="<?php echo htmlentities($config['unpaid_order_cancel_time']); ?>" class="layui-input" step="0.01"  min="0" onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''">
                    </div>
                    <div class="layui-inline">
                        分钟
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">未付款订单多久时间后自动关闭，不填或填0表示订单不自动取消</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label">已支付订单允许取消时长：</lable>
                    <div class="layui-inline">
                        <input type="number" min="0" name="paid_order_cancel_time" value="<?php echo htmlentities($config['paid_order_cancel_time']); ?>" class="layui-input" step="0.01"  min="0" onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''">
                    </div>
                    <div class="layui-inline">
                        分钟
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">已支付未发货的订单多久时间内允许客户自行取消，不填或填0表示订单不允许客户取消</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label">已发货订单自动完成时长：</lable>
                    <div class="layui-inline">
                        <input type="number" min="0" name="order_auto_receipt_days" value="<?php echo htmlentities($config['order_auto_receipt_days']); ?>" class="layui-input" step="0.01"  min="0" onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''">
                    </div>
                    <div class="layui-inline">
                        天
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">已发货订单多久时间后自动收货完成订单，不填或填0表示订单不自动收货标记已完成</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="set">确定</button>
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
    }).use(['form','element'], function(){
        var $ = layui.$,form = layui.form,element = layui.element;

        form.on('submit(set)', function(data) {
            like.ajax({
                url:'<?php echo url("setting.transaction/set"); ?>',
                data: data.field,
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
                    }
                }
            });
        });

    });
</script>
</body>
</html>
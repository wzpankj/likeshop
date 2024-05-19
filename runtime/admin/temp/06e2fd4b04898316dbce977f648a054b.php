<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\integral\rule\config.html";i:1682430021;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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


<link rel="stylesheet" href="/static/admin/css/goods.css" media="all">
<style>
    .layui-panel {
        border-radius: 10px;
        padding-left: 30px;
    }
    .goods-spec {
        padding: 20px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-bg-gray" style="padding: 30px;">
            <div class="layui-row layui-col-space15 layui-form">
                <!-- 充值设置 -->
                <div class="layui-col-md12">
                    <div class="layui-panel">
                        <div class="layui-card-body" pad15>
                            <div lay-filter="">
                                <div class="layui-form-item div-flex">
                                    <h3>积分设置</h3>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">订单金额：</label>
                                    <div class="layui-inline">
                                        <input type="number" name="min_order_money"
                                               value="<?php echo htmlentities($config['min_order_money']); ?>" lay-verify="integer" lay-verType="tips"
                                               switch-tab="1" autocomplete="off" class="layui-input">
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label"></label>
                                        <span style="color: #a3a3a3;font-size: 9px">最低订单金额要求，不填或填0表示不限制最低订单金额</span>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">最低积分：</label>
                                    <div class="layui-inline">
                                        <input type="number" name="min_integral"
                                               value="<?php echo htmlentities($config['min_integral']); ?>" lay-verify="integer" lay-verType="tips"
                                               switch-tab="1" autocomplete="off" class="layui-input">
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label"></label>
                                        <span style="color: #a3a3a3;font-size: 9px">最低积分要求，不填或填0表示不限制最低最低积分，填写后达到该值才能使用</span>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">每：</label>
                                    <div class="layui-inline">
                                        <input type="number" name="one_yuan"
                                               value="<?php echo htmlentities($config['one_yuan']); ?>" lay-verify="integer" lay-verType="tips"
                                               switch-tab="1" autocomplete="off" class="layui-input">
                                               积分抵扣一元
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label"></label>
                                        <span style="color: #a3a3a3;font-size: 9px">每多少积分抵扣一元，如100</span>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">每消费一元赠：</label>
                                    <div class="layui-inline">
                                        <input type="number" name="money_to_num"
                                               value="<?php echo htmlentities($config['money_to_num']); ?>" lay-verify="integer" lay-verType="tips"
                                               switch-tab="1" autocomplete="off" class="layui-input">
                                               积分
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label"></label>
                                        <span style="color: #a3a3a3;font-size: 9px">每消费一元赠送多少积分，如1</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

               
                <div class="layui-col-md12">
                    <div class="layui-panel" style="padding:10px">
                        <div class="layui-form-item" style="margin: 9px 0 0 95px; ">
                            <div class="layui-input-block" style="margin-left: unset;text-align: center">
                                <button class="layui-btn layui-btn-primary" onclick="window.location.reload()">
                                    取消
                                </button>
                                <button class="layui-btn layui-btn-md <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="set">
                                    确定
                                </button>
                            </div>
                        </div>
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
</style>
<script>
    layui.config({
        version: "<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function () {
        var $ = layui.$
            ,form = layui.form
            ,rule_num = <?php echo htmlentities(count($rule)); ?>;
        if(rule_num > 0){
            $('#goods-spec-project').parent().show(); 
        }

        form.verify({
            Ndouble: function (value) {
                if (value < 0) {
                    return '只能输入正数哦';
                }
                if (value < 0.01) {
                    return '必须大于0.01';
                }
                var patter_money = /^[1-9]\d{0,7}(\.\d{1,2})?$|^0(\.\d{1,2})?$/;
                if (value && !patter_money.test(value)) {
                    return '数值长度不能超过八位数,且小数不能超过两位';
                }
            },
            integer: function (value) {
                if (value && value < 0) {
                    return '只能输入正数哦';
                }
                if (value && 0 != value && value < 0.01) {
                    return '必须大于0.01';
                }
                var patter_money = /^[1-9]\d{0,7}(\.\d{1,2})?$|^0(\.\d{1,2})?$/;
                if (value && !patter_money.test(value)) {
                    return '数值长度不能超过八位数，且小数不能超过两位';
                }
            }
        });

       
        


        form.verify({
            more_spec_required: function (value, item) {
                if (!value) {
                    return $(item).attr('verify-msg');
                }
            }
        });

        form.on('submit(set)', function (data) {
            like.ajax({
                url: '<?php echo url("integral.Rule/config"); ?>' //实际使用请改成服务端真实接口
                , data: data.field
                , type: 'post'
                , success: function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        });
                        window.location.reload();
                    }

                }
            });
        });
    });
</script>
</body>
</html>
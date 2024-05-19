<?php /*a:2:{s:75:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\recharge_courtesy\rule\config.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
                                    <h3>充值设置</h3>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">应用状态：</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="app_status" value="0" title="关闭" <?php if($config['app_status']== 0): ?>checked<?php endif; ?> />
                                        <input type="radio" name="app_status" value="1" title="开启" <?php if($config['app_status']== 1): ?>checked<?php endif; ?> />
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label"></label>
                                        <span style="color: #a3a3a3;font-size: 9px">关闭或开启充值奖励应用，关闭后不显示商城充值奖励入口</span>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">最低充值金额：</label>
                                    <div class="layui-inline">
                                        <input type="number" name="min_recharge_amount"
                                               value="<?php echo htmlentities($config['min_recharge_amount']); ?>" lay-verify="integer" lay-verType="tips"
                                               switch-tab="1" autocomplete="off" class="layui-input">
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label"></label>
                                        <span style="color: #a3a3a3;font-size: 9px">最低充值金额要求，不填或填0表示不限制最低充值金额</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 充值规则 -->
                <div class="layui-col-md12">
                    <div class="layui-panel">
                        <div class="layui-card-body" pad15>
                            <div class="layui-form-item div-flex">
                                <h3>充值规则</h3>
                            </div>

                            <!-- 规则 -->
                            <div class="layui-form-item" style="display: none;">
                                <div class="layui-input-block goods-spec-div" id="goods-spec-project">

                                    <?php foreach($rule as $key => $item): ?>
                                    <div class="goods-spec-div goods-spec layui-col-md10" lay-verify="add_more_spec|repetition_spec_name" lay-verType="tips"
                                         autocomplete="off"
                                         switch-tab="1" >
                                        <a class="goods-spec-del-x" data-rule_num=<?php echo ++$key; ?>  style="display: none;">x</a>
                                        <label class="layui-form-label" style="margin-left: -140px;">规则<?php echo htmlentities($key); ?>：</label>
                                        <div>
                                            <label class="layui-form-label"><span class="form-label-asterisk">*</span>单笔充值满：</label>
                                            <div class="layui-input-block" style="width: 500px">
                                                <div class="layui-input-inline">
                                                    <input type="number" name="recharge_amount[]" value="<?php echo htmlentities($item['recharge_amount']); ?>" lay-verify="more_spec_required|Ndouble" lay-verType="tips"
                                                           switch-tab="1"
                                                           verify-msg="不能为空" autocomplete="off" class="layui-input spec_name">
                                                </div>
                                                <div class="layui-input-inline" style="margin-top:6px">元</div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div>
                                            <label class="layui-form-label"><span class="form-label-asterisk">*</span>赠送余额：</label>
                                            <div class="layui-input-block" style="width: 500px">
                                                <div class="layui-input-inline">
                                                    <input type="number" name="give_balance[]" value="<?php echo htmlentities($item['give_balance']); ?>" lay-verify="more_spec_required|Ndouble" lay-verType="tips"
                                                           switch-tab="1"
                                                           verify-msg="不能为空" autocomplete="off" class="layui-input spec_name">
                                                </div>
                                                <div class="layui-input-inline" style="margin-top:6px">元</div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>


                            <!-- 添加 按钮-->
                            <div class="layui-form-item" >
                                <label class="layui-form-label"></label>
                                <button class="layui-btn layui-btn-primary layui-btn-md layui-col-md9" id="add-spec" >
                                    + 添加 <span><?php echo htmlentities(count($rule)); ?></span>/10
                                </button>
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

<!--规格项模板-->
<script type="text/html" id="template-spec">
    <div class="goods-spec-div goods-spec layui-col-md10" lay-verify="add_more_spec|repetition_spec_name" lay-verType="tips"
         autocomplete="off"
         switch-tab="1" >
        <a class="goods-spec-del-x" style="display: none;" data-rule_num={rule_num} >x</a>
        <label class="layui-form-label" style="margin-left: -140px;">规则{value}：</label>
        <div>
            <label class="layui-form-label"><span class="form-label-asterisk">*</span>单笔充值满：</label>
            <div class="layui-input-block" style="width: 500px">
                <div class="layui-input-inline">
                    <input type="number" name="recharge_amount[]" lay-verify="more_spec_required|Ndouble" lay-verType="tips"
                           switch-tab="1"
                           verify-msg="不能为空" autocomplete="off" class="layui-input spec_name">
                </div>
                <div class="layui-input-inline" style="margin-top:6px">元</div>
            </div>
        </div>
        <br/>
        <div>
            <label class="layui-form-label"><span class="form-label-asterisk">*</span>赠送余额：</label>
            <div class="layui-input-block" style="width: 500px">
                <div class="layui-input-inline">
                    <input type="number" name="give_balance[]" lay-verify="more_spec_required|Ndouble" lay-verType="tips"
                           switch-tab="1"
                           verify-msg="不能为空" autocomplete="off" class="layui-input spec_name">
                </div>
                <div class="layui-input-inline" style="margin-top:6px">元</div>
            </div>
        </div>
    </div>
</script>

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

        //监听添加规格项按钮
        $(document).on('click', '#add-spec', function () {
            addSpec();
        });

        //添加规格项
        function addSpec() {
            var element_spec = $('#goods-spec-project'); // 规则区域
            var count = $('.goods-spec').length; // 规则数量
            if (count > 9) {
                layer.msg('最多添加10个规格项目');
                return;
            }
            rule_num++;
            value = rule_num;
            $("#add-spec span").html(rule_num);
            var template_spec = $('#template-spec').html(); // 获取规则模板
            // 使用value值替换规格项目模板中{value}占位符，并追加至规则区域中
            element_spec.append(template_spec.replace('{value}', value).replace('{rule_num}', value));
            $('#goods-spec-project').parent().show();
        }

        // 鼠标移入显示删除规格项按钮
        $(document).on('mouseenter', '.goods-spec', function () {
            $(this).find('.goods-spec-del-x').show();
        });

        // 鼠标移出隐藏删除规格项按钮
        $(document).on('mouseleave', '.goods-spec', function () {
            $(this).find('.goods-spec-del-x').hide();
        });

        // 监听删除规格项目按钮
        $(document).on('click', '.goods-spec-del-x', function () {
            console.log($(this).attr('data-value'))
            // 不是删除最后一个
            var del_rule_num = $(this).attr('data-rule_num')
            if(rule_num != del_rule_num){
                layer.msg('请先删除规则'+rule_num);
                return;
            }
            rule_num--;
            $("#add-spec span").html(rule_num);
            $(this).parent().remove(); // 移除当前规格项目
            var goods_spec_project = $('#goods-spec-project');
            if (goods_spec_project.children().length == 0) { // 规格项区域中若没有子元素则隐藏
                goods_spec_project.parent().hide();
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
                url: '<?php echo url("recharge_courtesy.Rule/config"); ?>' //实际使用请改成服务端真实接口
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
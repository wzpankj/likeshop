<?php /*a:2:{s:70:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\user\user\adjust_account.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
    .layui-form-label{
        width: 90px;
    }
    .reqRed::before {
        content: '*';
        color: red;
        margin-right: 5px;
    }
</style>
<div class="layui-form" lay-filter="adjust" id="layuiadmin-form-user" style="padding: 20px 30px 0 0;">
    <input type="hidden" value="<?php echo htmlentities($info['id']); ?>" name="id">
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-tab layui-tab-card"  lay-filter="adjust">
                <div class="layui-tab-content" >
                    <div class="layui-tab-item  layui-show " >
                        <div class="layui-form-item">
                            <label class="layui-form-label">当前余额：</label>
                            <div class="layui-input-inline">
                                <label class="layui-form-mid"><?php echo htmlentities($info['user_money']); ?></label>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label reqRed">余额增减：</label>
                            <div class="layui-input-block">
                                <input type="radio" name="money_handle" value="1" title="增加余额" checked>
                                <input type="radio" name="money_handle" value="0" title="扣减余额">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label reqRed">调整余额：</label>
                            <div class="layui-input-inline">
                                <input type="number" min="0" name="money" value="" lay-verify="" lay-vertype="tips" placeholder="" autocomplete="off" class="layui-input">
                                <label class="layui-form-mid layui-word-aux">请输入调整的余额金额</label>
                            </div>
                            <label class="layui-form-mid">元</label>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label reqRed">备注：</label>
                            <div class="layui-input-block">
                                <textarea type="text" name="money_remark" autocomplete="off" class="layui-textarea" style="width: 30%;"></textarea>
                                <label class="layui-form-mid layui-word-aux" style="margin-left: 10px;">不超过100字</label>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="edit-submit" id="edit-submit" value="确认">
    </div>
</div>
<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form', 'element'], function(){
        var $ = layui.$,form = layui.form ;
        var element = layui.element;
    })
</script>

</body>
</html>
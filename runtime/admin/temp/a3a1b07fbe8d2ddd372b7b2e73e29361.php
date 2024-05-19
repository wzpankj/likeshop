<?php /*a:2:{s:65:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\printer\config\edit.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
    .tips{
        color: red;
    }
    .layui-form-label {
        width: 100px;
    }
</style>

<div class="layui-form" lay-filter="">

    <div class="layui-form-item" style="margin-top: 20px;">
        <label class="layui-form-label"><span class="tips">*</span>设备类型：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text" name="name" lay-verify="required" lay-verType="tips" autocomplete="off" class="layui-input" value="易联云" disabled>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>应用id：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text"  name="client_id" value="<?php if(isset($detail['client_id'])): ?><?php echo htmlentities($detail['client_id']); ?><?php endif; ?>" lay-verify="required" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>apiKey：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text"  name="client_secret" value="<?php if(isset($detail['client_secret'])): ?><?php echo htmlentities($detail['client_secret']); ?><?php endif; ?>" lay-verify="required" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="edit-submit" id="edit-submit" value="确认">
    </div>
</div>
<script>
    layui.use(['table', 'form', 'element'], function(){

    });
</script>
</body>
</html>
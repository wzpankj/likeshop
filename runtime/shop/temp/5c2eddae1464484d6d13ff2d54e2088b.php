<?php /*a:2:{s:64:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\zhuohao\zhuohao\add.html";i:1677135519;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout2.html";i:1676699056;}*/ ?>
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
.tips{
    color: red;
}
.layui-form-label {
    width: 100px;
}
</style>

<div class="layui-form" lay-filter="">
    <div class="layui-form-item" style="margin-top: 20px;">
        <label class="layui-form-label"><span class="tips">*</span>名称：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text" name="name" lay-verify="required" lay-verType="tips" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>编号：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text"  name="number"  placeholder="请输入编号" class="layui-input">
            </div>
        </div>
    </div>
   
   
   
    
   
   
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="add-submit" id="add-submit" value="确认">
    </div>
</div>
<script>
    layui.use(['table', 'form', 'element'], function(){

    });
</script>
</body>
</html>
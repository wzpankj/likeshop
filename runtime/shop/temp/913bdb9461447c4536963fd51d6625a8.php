<?php /*a:2:{s:64:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\printer\printer\add.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout2.html";i:1676699056;}*/ ?>
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
        <label class="layui-form-label"><span class="tips">*</span>打印机名称：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text" name="name" lay-verify="required" lay-verType="tips" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>设备类型：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <select name="printer_config_id"  placeholder="请选择" >
                    <?php foreach($type_list as $type): ?>
                    <option value="<?php echo htmlentities($type['id']); ?>"><?php echo htmlentities($type['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>终端号：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text"  name="machine_code"  placeholder="请输入终端号" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>打印机秘钥：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="text"  name="private_key"  placeholder="请输入秘钥" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>小票模板：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <select name="template_id"  placeholder="请选择" >
                    <?php foreach($template_list as $template): ?>
                    <option value="<?php echo htmlentities($template['id']); ?>"><?php echo htmlentities($template['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>打印联数：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="radio" name="print_number" value="1" title="1联" checked>
                <input type="radio" name="print_number" value="2" title="2联">
                <input type="radio" name="print_number" value="3" title="3联">
                <input type="radio" name="print_number" value="4" title="4联">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>自动打印：</label>
        <div class="layui-input-block">
            <div class="layui-col-md4">
                <input type="radio" name="auto_print" value="0" title="关闭" checked>
                <input type="radio" name="auto_print" value="1" title="开启" >
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <span style="color: #a3a3a3;font-size: 9px">订单付款后自动打印订单，默认关闭</span>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>打印状态</label>
        <div class="layui-input-inline">
            <input type="radio" name="status" value="1" title="启用" checked>
            <input type="radio" name="status" value="0" title="停用">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <span style="color: #a3a3a3;font-size: 9px">开启和关闭打印机的使用状态</span>
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
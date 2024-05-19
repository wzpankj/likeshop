<?php /*a:2:{s:73:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\notice_setting\notice_config.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
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
    .layui-form-label {
        width: 120px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <!--            表单区域-->
            <div class="layui-form" style="margin-top: 15px;">
                <div class="layui-form-item">
                    <lable class="layui-form-label">外卖订单通知:</lable>
                    <div class="layui-input-block">
                        <input type="radio" name="take_out_notice" value="0" title="关闭" <?php if($config['take_out_notice'] ==0): ?>checked<?php endif; ?> />
                        <input type="radio" name="take_out_notice" value="1" title="开启" <?php if($config['take_out_notice'] == 1): ?>checked<?php endif; ?> />
                    </div>
                </div>
                <div class="layui-form-item">
                    <lable class="layui-form-label"></lable>
                    <div class="layui-input-block">
                        <span class="layui-word-aux">设置是否接收外卖订单通知</span>
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
                url:'<?php echo url("NoticeSetting/noticeConfig"); ?>',
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
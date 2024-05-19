<?php /*a:2:{s:75:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\decoration\applet\style\style.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
        height: auto;
    }
    .layui-form-radio * {
        font-size: 12px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form" style="margin-top: 15px;">
                <div class="layui-form-item" style="font-size: 5px !important;">
                    <label class="layui-form-label" style="width: 100px">风格配色:</label>
                    <?php foreach($style as $key=>$value): ?>
                    <div class="layui-inline" style="margin-right: 15px;">
                        <input lay-filter="style" type="radio" name="style" value="<?php echo htmlentities($key); ?>" class="layui-input" title="<?php echo htmlentities($value['title']); ?>" <?php if($key==$select_style): ?>checked<?php endif; ?>/>
                        <img src="<?php echo htmlentities($value['image']); ?>" style="width: 50px;height: 50px;">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="layui-form-item" style="font-size: 5px !important;">
                    <label class="layui-form-label" style="width: 100px">配色效果:</label>
                    <div>
                        <?php foreach($effect as $key=>$value): ?>
                        <div class="layui-inline effect<?php echo htmlentities($key); ?> effect" <?php if($key==$select_style): ?>style="display:block;width:80%"<?php endif; if($key!=$select_style): ?>style="display:none;width:80%"<?php endif; ?>>
                            <img src="<?php echo htmlentities($value); ?>" style="max-width:75%;border: 5px solid #ccc" />
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div>
                        <button class="layui-btn layui-btn-normal layui-btn-sm" lay-submit lay-filter="submit">确定</button>
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
            , form = layui.form;

        //监听单选框
        form.on('radio(style)', function(data){
            var style = data.value;//被点击的radio的value值
            $(".effect"+style).show().siblings().hide();
        });

        // 监听提交
        form.on('submit(submit)', function(obj) {
            like.ajax({
                url:'<?php echo url("decoration.applet.style/style"); ?>',
                data: obj.field,
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
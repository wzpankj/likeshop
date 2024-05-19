<?php /*a:2:{s:62:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\wechat\op\config.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
        width: 150px;
    }
    .tips{
        color: red;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-collapse like-layui-collapse" lay-accordion="" style="border:1px dashed #c4c4c4">
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title like-layui-colla-title" style="background-color: #fff">操作提示</h2>
                    <div class="layui-colla-content layui-show">
                        *填写微信开放平台开发配置，请前往微信开放平台创建应用并完成认证。
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card-body" pad15>
            <div class="layui-form" lay-filter="">
                <div class="layui-form-item div-flex">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>开发者信息</legend>
                    </fieldset>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="tips">*</span>AppID：</label>
                    <div class="layui-input-block">
                        <div class="layui-col-md3">
                            <input type="text"  name="app_id" value="<?php echo htmlentities($config['app_id']); ?>"  lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="tips">*</span>AppSecret：</label>
                    <div class="layui-input-block">
                        <div class="layui-col-md3">
                            <input type="text"  name="secret" value="<?php echo htmlentities($config['secret']); ?>"  lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <span style="color: #a3a3a3;font-size: 9px">登录微信开放平台，查看并设置</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-sm <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="set">确定</button>
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
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function(){
        var $ = layui.$,form = layui.form;

        form.on('submit(set)', function (data) {
            like.ajax({
                url: '<?php echo url("wechat.Op/config"); ?>' //实际使用请改成服务端真实接口
                , data: data.field
                , type: 'post'
                , success: function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg, {
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
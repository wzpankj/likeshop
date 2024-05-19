<?php /*a:1:{s:57:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\login\login.html";i:1676699056;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlentities($view_env_name); ?><?php echo htmlentities($config['shop_name']); ?></title>
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/static/common/css/login.css"/>
</head>
<style>
    #logo{
        width: 176px;height: 42px
    }
</style>
<body>
<div class="login">
    <div class="login-form-box">
        <div class="login-left">
            <div class="login-left-img">
                <img  src="<?php echo htmlentities($storageUrl); ?><?php echo htmlentities($config['login_image']); ?>"/>
            </div>
        </div>
        <div class="login-right">
            <div class="login-form layui-form">
                <div class="login-title">
                    <?php echo htmlentities($config['login_title']); ?>
                </div>
                <div class="form-box-item">
                    <div class="icon">
                        <img src="/static/common/image/login/login_number.png"/>
                    </div>
                    <div>
                        <input type="text" name="account" lay-verify="required" lay-vertype="tips"
                               class="layui-input" style="border:none;width: 150px;padding-left: 20px;"
                               placeholder="请输入账号" value=""/>
                    </div>
                </div>
                <div class="form-box-item">
                    <div class="icon">
                        <img src="/static/common/image/login/login_password.png"/>
                    </div>
                    <div>
                        <input type="password" name="password" lay-verify="required" lay-vertype="tips"
                               class="layui-input" style="border:none;width: 150px;padding-left: 20px;"
                               placeholder="请输入密码"/>
                    </div>
                </div>
<!--                <div class="form-box-item">-->
<!--                    <div class="icon">-->
<!--                        <img src="/static/common/image/login/login_code.png"/>-->
<!--                    </div>-->
<!--                    <div style="display: flex; align-items: center; justify-content: space-between;">-->
<!--                        <input  type="text" name="code" lay-verify="required" lay-vertype="tips"-->
<!--                                style="border:none;width: 150px;padding-left: 20px;" placeholder="请输入验证码"/>-->
<!--                        <img style="height: 36px;width: 126px;" src="<?php echo captcha_src(); ?>" class="layadmin-user-login-codeimg" id="captcha">-->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-box-checked">
                    <div>
                        <input type="checkbox" lay-skin="primary" name="remember_account" title="记住账号" <?php if(!(empty($account) || (($account instanceof \think\Collection || $account instanceof \think\Paginator ) && $account->isEmpty()))): ?>checked=""<?php endif; ?>>
                    </div>
                </div>
                <button id="login" lay-filter="login" class="submit-btn" lay-submit style="background-color: #2C85EA">登录</button>
            </div>
        </div>
    </div>
    <footer>
        <?php echo htmlentities($config['company_name']); ?>&nbsp;&nbsp;<a target="_blank" href="<?php echo htmlentities($config['link']); ?>"><?php echo htmlentities($config['number']); ?></a>&nbsp;
    </footer>
</div>

</body>

<script src="/static/lib/layui/layui.js"></script>
<script src="/static/admin/js/jquery.min.js"></script>
<script src="/static/admin/js/function.js"></script>

<script>
    if (self != top) {
        parent.window.location.replace(window.location.href);
    }
    //更换图形验证码
    $('body').on('click', '#captcha', function () {
        var othis = $(this);
        this.src = '<?php echo captcha_src(); ?>?t=' + new Date().getTime();
    });

    layui.use('form', function(){
        var form = layui.form;
        form.on('submit(login)', function (obj) {
            login(obj);
        });
    });

    function login(obj) {
        like.ajax({
            url: '<?php echo url("login/login"); ?>'
            , data: obj.field
            , type: 'post'
            , success: function (res) {
                if (res.code == 1) {
                    layer.msg(res.msg, {
                        offset: '15px'
                        , icon: 1
                        , time: 1000
                    }, function () {
                        location.href = '../';
                    });
                }
                $('#captcha').attr('src', '<?php echo captcha_src(); ?>?t=' + new Date().getTime());
            },
        });
    }


    like.keyUpClick(('[name="account"]'), '#login');
    like.keyUpClick(('[name="password"]'), '#login');
    like.keyUpClick(('[name="code"]'), '#login');

</script>
</html>
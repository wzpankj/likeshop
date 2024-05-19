<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\order\profile\profile.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
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

<div>
    <div class="layui-panel">

        <fieldset class="layui-elem-field">
            <legend>订单汇总</legend>
            <div class="layui-field-box">
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">成交订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['order_sum']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">到店订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['shop_order_sum']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">外卖订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['takeout_order_sum']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="layui-elem-field">
            <legend>到店订单</legend>
            <div class="layui-field-box">
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">今日到店订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['day_shop_order']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">准备中订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['afoot_shop_order']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">待取餐订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['wait_shop_order']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="layui-elem-field">
            <legend>外卖订单</legend>
            <div class="layui-field-box">
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">今日外卖订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['day_takeout_order']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">准备中订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['afoot_takeout_order']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">配送中订单/笔</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['delivery_takeout_order']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

    </div>
</div>
<style>
    .layui-panel {
        margin: 15px;
        padding: 20px 0;
    }
    .layui-elem-field {
        width: 95%;
        border-radius: 10px;
        margin: auto auto 30px;
    }
    .profile {
        width: 200px;
        margin-right: 30px;
    }
    .profile-title {
        text-align: center;
        font-size: 20px;
        margin-top: 10px;
    }
    .profile-body {
        text-align: center;
        font-size: 20px;
        margin: 20px 0;
    }
</style>
<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function(){

    });
</script>
</body>
</html>
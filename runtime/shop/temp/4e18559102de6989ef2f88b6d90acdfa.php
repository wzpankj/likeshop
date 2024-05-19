<?php /*a:1:{s:56:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\index\index.html";i:1676699056;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlentities($view_env_name); ?><?php echo htmlentities($config['shop_name']); ?></title>
    <link rel="shortcut icon" href="<?php echo htmlentities($storageUrl); ?><?php echo htmlentities($config['web_favicon']); ?>"/>
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/app.css">
    <script src="/static/lib/layui/layui.js"></script>
    <script src="/static/admin/js/app.js"></script>
</head>
<body>
    <!-- 头部区域 -->
    <div class="layui-header">
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item">
                <a class="refresh" href="javascript:" title="刷新">
                   <i class="layui-icon layui-icon-refresh-3"></i>
                </a>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item layui-hide-xs">
                <form class="layui-form roll-nav notice-form">
                    <input type="checkbox" name="switch" lay-skin="switch" lay-filter="switchTest" lay-text="开启订单通知|关闭订单通知"  id="checkbox">
                </form>
            </li>
            <li class="layui-nav-item layui-hide-xs">
                <a class="lock" href="javascript:">
                    <i class="layui-icon layui-icon-notice" title="通知"></i>
                </a>
            </li>
            <li class="layui-nav-item layui-hide-xs">
                <a class="fullscreen" href="javascript:">
                    <i class="layui-icon layui-icon-screen-full"></i>
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:">
                    <cite><?php echo htmlentities($admin_name); ?>（<?php echo htmlentities($shop_name); ?>）</cite>
                <span class="layui-nav-more"></span></a>
                <dl class="layui-nav-child layui-anim layui-anim-upbit userinfo">
<!--                    <dd><a lay-id="u-1" href="javascript:" data-url="pages/member/user.html">个人中心<span class="layui-badge-dot"></span></a></dd>-->
<!--                    <dd><hr></dd>-->
                    <dd><a href="<?php echo url('login/logout'); ?>" id="logout">退出登录</a></dd>
                </dl>
            </li>
        </ul>
    </div>

    <!-- 菜单区域 -->
    <div class="layui-sidebar ">
        <div class="layui-logo">
            <img style="height:20px;width: 77px" src="<?php echo htmlentities($storageUrl); ?><?php echo htmlentities($config['shop_logo']); ?>">
        </div>
        <ul class="layui-side-menu">
            <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li>
                    <?php if(empty($vo['sub']) || (($vo['sub'] instanceof \think\Collection || $vo['sub'] instanceof \think\Paginator ) && $vo['sub']->isEmpty())): ?>
                        <a lay-id="<?php echo htmlentities($vo['id']); ?>" lay-href="<?php echo url($vo['uri']); ?>" class="active">
                            <i class="layui-icon <?php echo htmlentities($vo['icon']); ?>"></i>
                            <cite><?php echo htmlentities($vo['name']); ?></cite>
                        </a>
                    <?php else: ?>
                        <a href="javascript:">
                            <i class="layui-icon <?php echo htmlentities($vo['icon']); ?>"></i>
                            <cite><?php echo htmlentities($vo['name']); ?></cite>
                        </a>

                        <dl class="child-menu">
                            <dt><strong><?php echo htmlentities($vo['name']); ?></strong></dt>
                            <?php if(is_array($vo['sub']) || $vo['sub'] instanceof \think\Collection || $vo['sub'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$second): $mod = ($i % 2 );++$i;?>
                                <dd>
                                    <?php if(empty($second['sub']) || (($second['sub'] instanceof \think\Collection || $second['sub'] instanceof \think\Paginator ) && $second['sub']->isEmpty())): ?>
                                        <a lay-id="<?php echo htmlentities($second['id']); ?>" lay-href="<?php echo url($second['uri']); ?>"><?php echo htmlentities($second['name']); ?></a>
                                    <?php else: ?>
                                        <a class="child-menu-title">
                                            <i class="layui-icon layui-icon-triangle-d"></i>
                                            <cite><?php echo htmlentities($second['name']); ?></cite>
                                        </a>
                                        <dl>
                                            <?php if(is_array($second['sub']) || $second['sub'] instanceof \think\Collection || $second['sub'] instanceof \think\Paginator): $i = 0; $__LIST__ = $second['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$third): $mod = ($i % 2 );++$i;?>
                                            <dd><a lay-id="<?php echo htmlentities($third['id']); ?>" lay-href="<?php echo url($third['uri']); ?>"><?php echo htmlentities($third['name']); ?></a></dd>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </dl>
                                    <?php endif; ?>
                                </dd>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </dl>
                    <?php endif; ?>
                </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <!-- 标签区域 -->
    <div class="lay-pagetabs" id="LAY_app_tabs">
        <div class="layui-icon lay-tabs-control layui-icon-prev" lay-event="leftPage"></div>
        <div class="layui-icon lay-tabs-control layui-icon-next" lay-event="rightPage"></div>
        <div class="layui-icon lay-tabs-control layui-icon-down">
            <ul class="layui-nav lay-tabs-select" lay-filter="lay-pagetabs-nav">
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:"><span class="layui-nav-more"></span></a>
                    <dl class="layui-nav-child layui-anim-fadein11 ">
                        <dd lay-event="closeThisTabs"><a href="javascript:">关闭当前标签页</a></dd>
                        <dd lay-event="closeOtherTabs"><a href="javascript:">关闭其它标签页</a></dd>
                        <dd lay-event="closeAllTabs"><a href="javascript:">关闭全部标签页</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
        <div class="layui-tab" lay-unauto lay-allowclose="true" lay-filter="lay-layout-tabs">
            <ul class="layui-tab-title" id="LAY_app_tabsheader">
                <li lay-id="0" lay-attr="<?php echo url('index/stat'); ?>" class="layui-this">工作台<i class="layui-icon layui-tab-close">ဆ</i></li>
            </ul>
        </div>
    </div>

    <!-- 主体区域 -->
    <div class="layui-body" id="LAY_app_body">
        <div lay-id="0" class="lay-tabsbody-item layui-show">
            <iframe src="<?php echo url('index/stat'); ?>" class="lay-iframe"></iframe>
        </div>
    </div>

    <audio id='audioPlay' src='/mp3/notice.wav' hidden='true'></audio>

</body>
</html>

<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function(){
        var $ = layui.$
            , form = layui.form

        var timer_id;

        form.on('switch(switchTest)', function(data){
            if(this.checked){
                layer.msg('实时提醒：'+ (this.checked ? '已开启' : 'false'), {
                    offset: '6px'
                });
                timer_id = setInterval(updateTabNumber, 5000);
            }else{
                layer.msg('实时提醒：已关闭', {
                    offset: '6px'
                });
                clearInterval(timer_id);
            }
        });

        function updateTabNumber() {
            $.ajax({
                url: '<?php echo url("index/getDayOrder"); ?>',
                data: {},
                type: "GET",
                success: function (res) {
                    if(res > 0){
                        layer.msg('您有新订单啦', {
                            offset: 't',
                            anim: 6,
                            time: 5000,
                        });
                        document.getElementById('audioPlay').play();
                    }
                }
            });
        }
    });
</script>
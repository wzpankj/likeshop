<?php /*a:2:{s:55:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\auth\edit.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
    .layui-form-item .layui-input-inline {
        width: 270px;
    }
</style>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 30px 0 0;">
    <input type="hidden" value="<?php echo htmlentities($detail['id']); ?>" name="id">
    <div class="layui-form-item">
        <label class="layui-form-label">父类菜单</label>
        <div class="layui-input-inline">
            <select name="pid" class="layui-select" lay-search>
                <?php if($detail['type'] == '1'): ?><option value="0">顶级</option><?php endif; if(is_array($menu_lists) || $menu_lists instanceof \think\Collection || $menu_lists instanceof \think\Paginator): $i = 0; $__LIST__ = $menu_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>" <?php if($detail['pid'] == $vo['id']): ?> selected <?php endif; ?> ><?php echo htmlentities($vo['name']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" name="name" placeholder="请输入菜单名称" autocomplete="off" class="layui-input" value="<?php echo htmlentities($detail['name']); ?>" lay-verify="required" placeholder="请输入菜单名称" lay-vertype="tips">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-inline">
            <input type="radio" lay-filter="type" name="type" value="1" title="菜单" <?php if($detail['type'] == '1'): ?>checked<?php endif; ?>>
            <input type="radio" lay-filter="type" name="type" value="2" title="权限" <?php if($detail['type'] == '2'): ?>checked<?php endif; ?>>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">规则</label>
        <div class="layui-input-inline">
            <input type="text" name="uri"  placeholder="请输入控制器方法规则：例：admin/lists" autocomplete="off" class="layui-input"  lay-vertype="tips" value="<?php echo htmlentities($detail['uri']); ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-inline">
            <input type="number" name="sort" autocomplete="off" class="layui-input"  lay-verify="required" placeholder="请输入排序，数字越大越靠前" lay-vertype="tips" value="<?php echo htmlentities($detail['sort']); ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">请选择图标</label>
        <div class="layui-input-inline">
            <input type="text" id="iconPicker" lay-filter="iconPicker" style="display:none;">
            <input type="hidden" name="icon" value="<?php echo htmlentities($detail['icon']); ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
            <input type="checkbox" lay-filter="disable" name="disable" lay-skin="switch" lay-text="启用|禁用" <?php if($detail['disable'] == 0): ?>checked<?php endif; ?>>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="menu-submit" id="menu-submit" value="确认">
    </div>
</div>
<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/'
    }).extend({
        iconPicker: 'iconpicker/module/iconPicker/iconPicker'
    }).use(['form','iconPicker'], function(){
        var $ = layui.$
            ,form = layui.form ;
        var iconPicker = layui.iconPicker;

        iconPicker.render({
            // 选择器，推荐使用input
            elem: '#iconPicker',
            // 数据类型：fontClass/unicode，推荐使用fontClass
            type: 'fontClass',
            // 是否开启搜索：true/false，默认true
            search: true,
            // 是否开启分页：true/false，默认true
            page: true,
            // 每页显示数量，默认12
            limit: 50,
            // 每个图标格子的宽度：'43px'或'20%'
            cellWidth: '43px',
            // 点击回调
            click: function (data) {
                $('input[name="icon"]').val(data.icon);
            }
        });
        iconPicker.checkIcon('iconPicker', '<?php echo htmlentities($detail['icon']); ?>');


        form.on('radio(type)', function (data) {
            if (data.value == 1) {
                $("#pid").prepend("<option value='0'>顶级</option>");
            } else {
                $("#pid option[value='0']").remove();
            }
            form.render('select');
        });
    })
</script>
</body>
</html>
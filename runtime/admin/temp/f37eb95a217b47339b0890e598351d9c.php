<?php /*a:2:{s:65:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\system\crontab\edit.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
        width: 240px;
    }
</style>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 30px 0 0;">
    <input type="hidden" value="0" name="id">
    <div class="layui-form-item">
        <input type="hidden" name="id" value="<?php echo htmlentities($info['id']); ?>">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required" lay-vertype="tips" placeholder="请输入名称"
                   autocomplete="off" class="layui-input" value="<?php echo htmlentities($info['name']); ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-inline">
            <input type="radio" lay-filter="type" name="type" value="1" title="定时任务" <?php if($info['type'] == '1'): ?>checked<?php endif; ?>>
            <!--<input type="radio" lay-filter="type" name="type" value="2" title="守护进程" <?php if($info['type'] == '2'): ?>checked<?php endif; ?>>-->
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">命令</label>
        <div class="layui-input-inline">
            <input type="text" name="command" lay-verify="required" lay-vertype="tips"
                   placeholder="请输入thinkphp命令，例：version" autocomplete="off" class="layui-input" value="<?php echo htmlentities($info['command']); ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">参数</label>
        <div class="layui-input-inline">
            <input type="text" name="parameter" placeholder="请输入参数，例:--id 8 --name 测试" autocomplete="off"
                   class="layui-input" value="<?php echo htmlentities($info['parameter']); ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
            <input type="checkbox" lay-filter="disable" name="status" lay-skin="switch" lay-text="立即启动|停止"  <?php if($info['status'] == '1'): ?>checked<?php endif; ?>>
        </div>
    </div>
    <div class="layui-form-item" id="expression">
        <label class="layui-form-label">规则</label>
        <div class="layui-input-inline">
            <input type="text" name="expression" lay-verify="expression_required" lay-vertype="tips"
                   placeholder="请输入crontab规则，例：59 * * * *" autocomplete="off" class="layui-input" value="<?php echo htmlentities($info['expression']); ?>">
        </div>
    </div>
    <div class="layui-form-item" id="preview" style="display: none">
        <label class="layui-form-label">执行预览</label>
        <div class="layui-input-inline">
            <table class="layui-table">
                <colgroup>
                    <col width="60">
                    <col width="160">
                </colgroup>
                <thead>
                <tr>
                    <th>次数</th>
                    <th>执行时间</th>
                </tr>
                </thead>
                <tbody id="tbody_lists">
                </tbody>
            </table>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-inline">
            <textarea name="remark" placeholder="请输入内容" class="layui-textarea"><?php echo htmlentities($info['remark']); ?></textarea>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="crontab-submit-edit" id="crontab-submit-edit" value="确认">
    </div>
</div>
<script src="/static/common/js/array.js"></script>
<script>
    var code;
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function () {
        var $ = layui.$
            , form = layui.form;

        function preview() {
            expression = $("[name=expression]").val();
            if (isEmptyString(expression)) {
                $('#tbody_lists').html('');
                $('#preview').hide();
                return;
            }
            var data = {'expression': expression};
            like.ajax({
                url: '<?php echo url("system.Crontab/expression"); ?>',
                data: data,
                type: "get",
                success: function (res) {
                    if (res.code == 1) {
                        var html = '';
                        var data = res.data;
                        for (var i in data) {
                            html += '<tr>';
                            html += '<td>' + data[i]['time'] + '</td>';
                            html += '<td>' + data[i]['date'] + '</td>';
                            html += '</tr>';
                        }
                        $('#preview').show();
                        $('#tbody_lists').html(html);
                    }
                }
            });
        }

        $("[name=expression]").bind('input propertychange', function () {
            clearTimeout(code);
            code = setTimeout(function () {
                preview();
            }, 1000);
        });

        form.on('radio(type)', function (data) {
            if (data.value == 1) {
                $('#expression').show();
                preview();
            } else {
                $('#expression').hide();
                $('#preview').hide();
            }
        });

        form.verify({
            expression_required: function (value, item) {
                if ($('input[name="type"]:checked').val() == 2) {
                    return;
                }
                if(isEmptyString(value)){
                    return '定时任务的规则不能为空';
                }
            }
        });

        if ('<?php echo htmlentities($info['type']); ?>' == '1') {
            $('#expression').show();
            preview();
        } else {
            $('#expression').hide();
            $('#preview').hide();
        }
        preview();
    })
</script>
</body>
</html>
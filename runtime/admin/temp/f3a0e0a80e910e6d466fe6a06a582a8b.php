<?php /*a:2:{s:63:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\announcement\edit.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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

<div class="wrapper">
    <div class="layui-card">
        <div class="layui-card-header">门店公告</div>
        <div class="layui-card-body" pad15>
            <div class="layui-form" lay-filter="">
                <div class="layui-form-item">
                    <label class="layui-form-label">公告内容:</label>
                    <div class="layui-input-block" style="width:80%">
                        <textarea placeholder="请输入内容" id="content" lay-verify="content" name="content" class="layui-textarea" style="width: 300px;"><?php echo htmlentities($detail['content']); ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="submit">确定</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>

    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/'
    }).extend({
        likeedit: 'likeedit/likeedit'
    }).use(['table', 'form', 'element', 'likeedit'], function() {
        var form = layui.form
            , likeedit = layui.likeedit;

        likeedit.set({
            uploadImage: {
                url: '<?php echo url("file/lists"); ?>?type=10',
                type: 'post'
            }
        });
        var content = likeedit.build('content'); //建立编辑器
        form.verify({

            content: function() {
                likeedit.sync(content)
            }
        })
        // var likeedit_index = likeedit.build('content');
        // form.verify({
        //     content: function () {
        //         likeedit.sync(likeedit_index)
        //     }
        // });


        form.on('submit(submit)', function (data) {
            like.ajax({
                url: '<?php echo url(); ?>'
                , data: data.field
                , type: 'post'
                , success: function (res) {
                    if(res.code == 1) {
                        layui.layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        }, function () {
                            location.href = location.href;
                        });
                    }
                }
            });
        });
    });

</script>
</body>
</html>
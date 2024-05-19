<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\decoration\ad\ad\edit.html";i:1676699055;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
    .tips{
        color: red;
    }
    .link{
        display: none;
    }
    .size-tips-div{
        display: none;
    }
    .category{
        display: none;
    }
    .layui-form-label{
        width: 90px;
    }
</style>
<div class="layui-form" lay-filter="layuiadmin-form" id="layuiadmin-form" style="padding: 20px 30px 0 0;">
    <input type="hidden" name="id" value="<?php echo htmlentities($detail['id']); ?>">
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="tips">*</span>广告位标题：</label>
        <div class="layui-input-inline">
            <input type="text" name="title" value="<?php echo htmlentities($detail['title']); ?>" lay-vertype="tips" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="white-space:nowrap;"><span class="tips">*</span>广告位：</label>
        <div class="layui-input-inline">
            <select name="ad_position_id" lay-filter="selectPid">
                <option value="" ></option>
                <?php foreach($position_list as $position): ?>
                <option value="<?php echo htmlentities($position['id']); ?>" data-width="<?php echo htmlentities($position['width']); ?>" data-height="<?php echo htmlentities($position['height']); ?>" <?php if($detail['ad_position_id'] == $position['id']): ?> selected <?php endif; ?> ><?php echo htmlentities($position['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="white-space:nowrap;"><span class="tips">*</span>广告图片：</label>
        <div class="layui-input-block">
            <div class="like-upload-image">
                <?php if($detail['image']): ?>
                <div class="upload-image-div">
                    <img src="<?php echo htmlentities($detail['image']); ?>" alt="img">
                    <input type="hidden" name="image" value="<?php echo htmlentities($detail['image']); ?>">
                    <div class="del-upload-btn">x</div>
                </div>
                <div class="upload-image-elem" style="display:none;"><a class="add-upload-image"> + 添加图片</a></div>
                <?php else: ?>
                <div class="upload-image-elem"><a class="add-upload-image"> + 添加图片</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="layui-form-item size-tips-div">
        <label class="layui-form-label"></label>
        <span class="size-tips"></span>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="white-space:nowrap;">广告链接：</label>
        <div class="layui-input-inline">
            <input type="text" name="link" autocomplete="off" class="layui-input" value="<?php echo htmlentities($detail['link']); ?>">
            <span style="margin-top: 10px;">请填写完整的自定义链接，http或者https开头的完整链接。</span>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="white-space:nowrap;"><span class="tips">*</span>状态：</label>
        <div class="layui-input-inline">
            <input type="radio" name="status" value=0 title="停用" <?php if($detail['status'] == 0): ?>checked<?php endif; ?>>
            <input type="radio" name="status" value=1 title="启用" <?php if($detail['status'] == 1): ?>checked<?php endif; ?>>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="editSubmit" id="editSubmit" value="确认">
    </div>
</div>
<script>
    layui.use(["table", "laydate","form", "jquery"], function(){
        var $ = layui.jquery;
        var form = layui.form;

        form.render();
        // 监听 广告位置选择
        form.on('select(selectPid)', function(data){
            var id = data.value;
            var elem = $(data.elem).find("option:selected");
            if(id){
                renderSize(elem);
            }else{
                $('.size-tips-div').hide();
            }
        });

        function renderSize(elem) {
            var width = elem.attr('data-width') ? elem.attr('data-width'): 0;
            var height = elem.attr('data-height') ? elem.attr('data-height') : 0;
            if(width || height){
                $('.size-tips-div').show();
                var html  = '建议上传广告图片宽*高, '+width+'px*'+height+'px';
                $('.size-tips').text(html);
            }
        }


        like.delUpload();
        $(document).on("click", ".add-upload-image", function () {
            like.imageUpload({
                limit: 1,
                field: "image",
                that: $(this)
            });
        })

    })
</script>
</body>
</html>
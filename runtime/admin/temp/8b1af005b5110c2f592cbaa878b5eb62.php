<?php /*a:2:{s:56:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\file\lists.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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

<div class="file-manager">
    <div id="cate-tree-more" class="cate-tree-more" style="width: 200px"></div>
    <div class="gallery">
        <div class="layui-card" style="box-shadow:none;">
            <div class="layui-card-header">
                <button type="button" class="layui-btn layui-btn layui-btn-sm layui-btn-normal layEvent" lay-event="addCate"><i class="layui-icon layui-icon-add-circle"></i> 添加分类</button>
                <button type="button" class="layui-btn layui-btn layui-btn-sm layui-btn-normal" id="upload">
                    <i class="layui-icon layui-icon-upload"></i> <?php if($type == 10): ?>上传图片<?php elseif($type == 20): ?>上传视频<?php else: ?>上传文件<?php endif; ?>
                </button>
                <button type="button" class="layui-btn layui-btn layui-btn-sm layEvent layui-btn layui-btn-warm" lay-event="move" style="margin-left:5px;"><i class="layui-icon layui-icon-water"></i>  移动分类</button>
                <button type="button" class="layui-btn layui-btn layui-btn-sm layEvent layui-btn layui-btn-danger" lay-event="del"><i class="layui-icon layui-icon-delete"></i>  删除文件</button>
            </div>
            <div class="layui-card-body">
                <script id="manager" type="text/html">
                    <ul class="warehouse">
                        {{#  layui.each(d, function(index, item){ }}
                            <li lay-id="{{item.id}}" lay-href="{{item.uri}}">
                                <div class="file-icon"><img class="file-image" src="{{item.uri}}"></div>
                                <div class="file-name"><a href="javascript:">{{item.name}}</a></div>
                            </li>
                        {{#  }); }}
                    </ul>
                    {{#  if(d.length === 0){ }}
                        <div class="empty">
                            <i class="layui-icon layui-icon-release" style=""></i>
                            <p>木有数据，请去上传</p>
                        </div>
                    {{#  } }}
                </script>
                <div id="view"></div>
            </div>
        </div>
        <div style="height:55px;"></div>
        <div class="footer">
            <button type="button" id="okFile" class="layui-btn layui-btn layui-btn-sm layui-btn-normal">使用选中文件</button>
            <div id="page"></div>
        </div>
    </div>
</div>

<script>
    layui.use(["form", "element", "tree", "laytpl", "laypage", "upload"], function() {
        var $ = layui.jquery;
        var tree     = layui.tree;
        var laytpl   = layui.laytpl;
        var laypage  = layui.laypage;
        var upload   = layui.upload;
        var category = JSON.parse('<?php echo $category; ?>');
        var curCid   = 0;

        var uploadIns = upload.render({
            elem: "#upload"
            ,url: "<?php echo url('Upload/image'); ?>"
            ,accept: "images"
            ,exts: "jpg|png|gif|bmp|jpeg|ico"
            ,size: 4096
            ,data : {
                "cid": curCid, 
                "type": "<?php echo htmlentities($type); ?>"
            }
            ,number: 20
            ,multiple: true
            ,done: function (res) {
                if (res.code === 1) {
                    var li  = "<li lay-id='"+res.data.id+"' lay-href='"+res.data.uri+"'>\n";
                        li += "<div class='file-icon'><img class='file-image' src='"+res.data.uri+"'></div>\n"
                        li += "<div class='file-name'><a href='javascript:'>"+res.data.name+"</a></div>\n";
                        li += "</li>"
                    $(".warehouse").prepend(li)
                }

                layer.msg(res.msg);
            }
        });

        var active = {
            getFile: function (cid, page) {
                cid = cid === undefined ? 0 : cid;
                page = page === undefined ? 1 : page;
                var data = {cid:cid, page:page, type: "<?php echo htmlentities($type); ?>"};
                var index = layer.load(1, {shade: false});
                like.ajax({
                    url: "<?php echo url('File/lists'); ?>",
                    type: "GET",
                    data: data,
                    success:function(res) {
                        layer.close(index);
                        if(res.code === 1) {
                            active.renderList(res.data.data);
                            laypage.render({
                                elem: "page"
                                ,count: res.data.total
                                ,curr: res.data.current_page
                                ,limit: res.data.per_page
                                ,last: res.data.last_page
                                ,jump: function (obj, first) {
                                    if (!first) {
                                        active.getFile(cid, obj.curr)
                                    }
                                }
                            });
                        }
                    }
                });
            },
            renderList: function(data) {
                var getTpl = document.getElementById("manager").innerHTML;
                var view   = document.getElementById('view');
                laytpl(getTpl).render(data, function(html){
                    view.innerHTML = html;
                });
            },
            getChoice: function() {
                var ids  = [];
                var href = [];
                $(".warehouse li.on").each(function () {
                    ids.push($(this).attr("lay-id"));
                    href.push($(this).attr("lay-href"));
                });

                return {ids:ids, href:href}
            },
            move: function () {
                var fileData = active.getChoice();
                if (fileData.ids.length === 0) {
                    layer.msg("尚未选择任何文件");
                    return false;
                }
                layer.open({
                    type: 2
                    ,title: "移动文件"
                    ,content: "<?php echo url('File/move'); ?>?type=<?php echo htmlentities($type); ?>"
                    ,area: ["400px", "440px"]
                    ,btn: ["确定", "取消"]
                    ,yes: function(index, layero){
                        var iframeWindow = window["layui-layer-iframe" + index];
                        var submit = layero.find("iframe").contents().find("#addSubmit");
                        iframeWindow.layui.form.on("submit(addSubmit)", function(data){
                            data.field["file_ids"] = fileData.ids;
                            data.field["type"] = "<?php echo htmlentities($type); ?>";
                            like.ajax({
                                url: "<?php echo url('File/move'); ?>",
                                data: data.field,
                                type: "POST",
                                success:function(res) {
                                    if(res.code === 1) {
                                        // if (data.field['cid'] !== '0') {
                                        //     $(".warehouse li.on").remove();
                                        // }
                                        active.getFile(curCid, 1);
                                        layui.layer.msg(res.msg);
                                        layer.close(index);
                                    }
                                }
                            });
                        });
                        submit.trigger("click");
                        return false;
                    }
                });
            },
            del: function () {
                var fileData = active.getChoice();
                if (fileData.ids.length === 0) {
                    layer.msg("尚未选择任何文件");
                    return false;
                }
                layer.confirm('确定删除所选文件？', function(index){
                    like.ajax({
                        url: "<?php echo url('File/del'); ?>",
                        data: {file_ids: fileData.ids, type: '<?php echo htmlentities($type); ?>'},
                        type: "POST",
                        success:function(res) {
                            console.log(res)
                            if(res.code === 1) {
                                active.getFile(curCid, 1);
                                layui.layer.msg(res.msg);
                            }
                        }
                    });
                    layer.close(index);
                });

            },
            addCate: function () {
                layer.open({
                    type: 2
                    ,title: "新增分类"
                    ,content: "<?php echo url('File/addCate'); ?>"
                    ,area: ["400px", "400px"]
                    ,btn: ["确定", "取消"]
                    ,yes: function(index, layero){
                        var iframeWindow = window["layui-layer-iframe" + index];
                        var submit = layero.find("iframe").contents().find("#addSubmit");
                        iframeWindow.layui.form.on("submit(addSubmit)", function(data){
                            data.field["type"] = "<?php echo htmlentities($type); ?>";
                            like.ajax({
                                url: "<?php echo url('File/addCate'); ?>",
                                data: data.field,
                                type: "POST",
                                success:function(res) {
                                    if(res.code === 1) {
                                        category = res.data;
                                        active.renderTree();
                                        layui.layer.msg(res.msg);
                                        layer.close(index);
                                    }
                                }
                            });
                        });
                        submit.trigger("click");
                    }
                });
            },
            editCate: function (obj) {
                if (obj.data.id === 0) {
                    layer.msg("此分类不允许编辑", {icon: 2});
                    return false;
                }
                layer.open({
                    type: 2
                    ,title: "编辑分类"
                    ,content: "<?php echo url('File/editCate'); ?>?id="+ obj.data.id + '&&type=<?php echo htmlentities($type); ?>&&shop_id=0'
                    ,area: ["400px", "400px"]
                    ,btn: ["确定", "取消"]
                    ,yes: function(index, layero){
                        var iframeWindow = window["layui-layer-iframe" + index];
                        var submit = layero.find("iframe").contents().find("#addSubmit");
                        iframeWindow.layui.form.on("submit(addSubmit)", function(data){
                            data.field['id'] = obj.data.id;
                            data.field["type"] = "<?php echo htmlentities($type); ?>";
                            like.ajax({
                                url: "<?php echo url('File/editCate'); ?>",
                                data: data.field,
                                type: "POST",
                                success:function(res) {
                                    if(res.code === 1) {
                                        category = res.data;
                                        active.renderTree();
                                        layui.layer.msg(res.msg);
                                        layer.close(index);
                                    }
                                }
                            });
                        });
                        submit.trigger("click");
                        return false;
                    }
                });
            },
            delCate: function(obj) {
                if (obj.data.id === 0) {
                    layer.msg("此分类不允许删除", {icon: 2});
                    return false;
                }
                like.ajax({
                    url: "<?php echo url('File/delCate'); ?>",
                    data: {id: obj.data.id, type: "<?php echo htmlentities($type); ?>"},
                    type: "POST",
                    success:function(res) {
                        if(res.code === 1) {
                            category = res.data;
                            active.renderTree();
                            layui.layer.msg(res.msg);
                        }else{
                            category = res.data;
                            active.renderTree();
                        }
                    }
                });
            },
            renderTree: function () {
                tree.render({
                    elem: '#cate-tree-more'
                    ,edit: ['update', 'del']
                    ,customOperate: false
                    ,onlyIconControl:true
                    ,data: category
                    ,click: function (obj) {
                        // 节点被点击
                        var cid = obj.data.id;
                        var page = 1;
                        curCid = cid;
                        active.getFile(cid, page);
                        uploadIns.reload({
                            elem: "#upload"
                            ,url: "<?php echo url('Upload/image'); ?>"
                            ,data : {
                                "cid": curCid, 
                                "type": "<?php echo htmlentities($type); ?>"
                            }
                        });
                    }
                    ,operate: function(obj){
                        switch (obj.type) {
                            case "update":
                                active.editCate(obj);
                                break;
                            case "del":
                                active.delCate(obj);
                                break;
                        }
                    }
                });
            }
        };

        like.eventClick(active);
        active.renderTree();
        active.getFile();


        $(document).on("click", ".warehouse li", function () {
            if ($(this).hasClass("on")) {
                $(this).removeClass("on");
            } else {
                $(this).addClass("on");
            }
        });


        // 选择图片(用于富文本的图片选择)
        // Author: 张无忌
        $("#okFile").click(function () {
            var urls = [];
            $(".warehouse li.on").each(function () {
                urls.push($(this).attr("lay-href"));
            });

            if (urls.length <= 0) {
                parent.layer.msg("请至少选择一张图片");
                return false;
            }

            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
            parent.window.callback && parent.window.callback(urls.reverse());
        });

    })
</script>
</body>
</html>
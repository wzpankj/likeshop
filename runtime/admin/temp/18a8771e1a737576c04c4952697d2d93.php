<?php /*a:5:{s:61:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\goods\goods\add.html";i:1681910188;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;s:68:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\goods\goods\goods_base.html";i:1676699056;s:68:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\goods\goods\goods_spec.html";i:1681910214;s:76:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\goods\goods\goods_sale_setting.html";i:1676699056;}*/ ?>
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


<link rel="stylesheet" href="/static/admin/css/goods.css" media="all">

<div class="layui-tab layui-tab-card">
    <!--顶部切换页-->
    <ul class="layui-tab-title">
        <li class="goods-tab layui-this" style="color: #6a6f6c">基础设置</li>
        <li class="goods-tab" style="color: #6a6f6c">规格型号</li>
        <li class="goods-tab" style="color: #6a6f6c">销售设置</li>
    </ul>

    <!--切换内容-->
    <div class="layui-tab-content layui-form">
        <!--基础信息-->
        <div class="layui-tab-item goods-content layui-show">
    <div class="layui-card-body" pad15>
        <div>
            <!--商品编码-->
            <div class="layui-form-item">
                <label class="layui-form-label">商品编码：</label>
                <div class="layui-input-block">
                    <input type="text" name="code" lay-verType="tips" autocomplete="off" switch-tab="0" class="layui-input">
                </div>
            </div>
            <!--商品名称-->
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品名称：</label>
                <div class="layui-input-block">
                    <input name="goods_id" type="hidden">
                    <input type="text" name="name" lay-verify="custom_required" lay-verType="tips"
                           autocomplete="off" maxlength="64"
                           switch-tab="0" verify-msg="请输入商品名称，最多64个字符" placeholder="请输入商品名称，最少3个字符，最多64个字符"
                           class="layui-input">
                </div>
            </div>
            <!--商品简介-->
            <div class="layui-form-item">
                <label class="layui-form-label">商品简介：</label>
                <div class="layui-input-block">
                    <input type="text" maxlength="60" name="remark"  autocomplete="off" class="layui-input">
                </div>
            </div>
            <!--商品分类-->
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品分类：</label>
                <div class="layui-input-inline">
                    <select name="goods_category_id" lay-filter="goods_category_id" lay-verify="custom_required"
                            lay-verType="tips" switch-tab="0" verify-msg="请选择分类">
                    </select>
                </div>
            </div>
            <!--商品封面图-->
            <div class="layui-form-item" style="margin-bottom: 0px">
                <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品封面图：</label>
                <div class="layui-input-block" id="imageContainer">
                    <div class="like-upload-image">
                        <div class="upload-image-elem"><a class="add-upload-image" id="image"> + 添加图片</a></div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <span style="color: #a3a3a3;font-size: 9px">建议尺寸：800*800像素</span>
            </div>
        </div>
    </div>
</div>
        <!--规格型号-->
        <div class="layui-tab-item goods-content">
    <!--规格型号-->
    <div class="layui-card-body" pad15>
        <div lay-filter="">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品规格：</label>
                <div class="layui-input-block">
                    <input type="radio" name="spec_type" lay-filter="spec-type" value="1" title="统一规格" checked <?php if(isset($status) && $status == 1): ?>disabled<?php endif; ?>>
                    <input type="radio" name="spec_type" lay-filter="spec-type" value="2" title="多规格" <?php if(isset($status) && $status == 1): ?>disabled<?php endif; ?>>
                </div>
            </div>

            <div <?php if(isset($status) && $status == 1): ?> style="display: none"<?php endif; ?>>
                <!-- 规格项 -->
                <div class="layui-form-item" style="display: none">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block goods-spec-div" id="goods-spec-project">
                    </div>
                </div>

                <!-- 添加规格项目按钮 -->
                <div class="layui-form-item" style="display: none">
                    <label class="layui-form-label"></label>
                    <button class="layui-btn layui-btn-normal layui-btn-sm" id="add-spec" lay-verify="add_more_spec"
                            lay-verType="tips" autocomplete="off" switch-tab="1" verify-msg="至少添加一个规格">添加规格项目
                    </button>
                    <br>
                    <span style="color: #a3a3a3;font-size: 9px;margin-left:130px;">最多支持3个规格项</span>
                </div>
            </div>

            <!-- 统一规格 -->
            <div class="layui-form-item" id="one-spec-lists">
                <label class="layui-form-label">规格明细：</label>
                <div class="layui-input-block goods-spec-div">
                    <table id="one-spec-lists-table" class="layui-table spec-lists-table" lay-size="sm">
                        <thead>
                        <tr style="background-color: #f3f5f9">
                            <th><span class="form-label-asterisk">*</span>市场价(元)</th>
                            <th><span class="form-label-asterisk">*</span>价格(元)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input type="number" class="layui-input"
                                       lay-verify="one_spec_required|one_market_price"
                                       lay-verType="tips"
                                       autocomplete="off" switch-tab="1" verify-msg="请输入市场价"
                                       name="one_market_price" <?php if(isset($status) && $status == 1): ?>disabled<?php endif; ?>></td>
                            <td><input type="number" class="layui-input"
                                       lay-verify="one_spec_required|one_price"
                                       lay-verType="tips"
                                       autocomplete="off" switch-tab="1" verify-msg="请输入价格"
                                       name="one_price" <?php if(isset($status) && $status == 1): ?>disabled<?php endif; ?>></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 多规格 -->
            <div class="layui-form-item" id="more-spec-lists" style="display: none">
                <label class="layui-form-label">规格明细：</label>
                <div class="layui-input-block goods-spec-div">
                    <div class="batch-div add-more-spec" <?php if(isset($status) && $status == 1): ?> style="display: none"<?php endif; ?>><span class="batch-spec-title">批量设置：</span>
                        <div>
                            <span class="batch-spec-content click-a" input-name="market_price">市场价</span>
                            <span class="batch-spec-content click-a" input-name="price">价格</span>
                        </div>
                    </div>
                    <table id="more-spec-lists-table" class="layui-table spec-lists-table" lay-size="sm">
                    </table>
                </div>
            </div>
        </div>

        <div style="margin-top: 100px;">
            <div class="layui-form-item material-title">
                <label class="layui-form-label">商品物料：</label>
            </div>
            <!-- 物料项 -->
            <div class="layui-form-item" style="display: none">
                <label class="layui-form-label">商品物料：</label>
                <div class="layui-input-block goods-material-div" id="goods-material-project">
                </div>
            </div>

            <!-- 添加物料项按钮 -->
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <button class="layui-btn layui-btn-normal layui-btn-sm" id="add-material" lay-verify="add-material"
                        lay-verType="tips" autocomplete="off" switch-tab="1">添加物料项
                </button>
                <br>
                <span style="color: #a3a3a3;font-size: 9px;margin-left:130px;">最多支持4个物料项</span>
            </div>
        </div>

    </div>
</div>

<!--规格项模板-->
<script type="text/html" id="template-spec">
    <div class="goods-spec-div goods-spec" lay-verify="add_more_spec|repetition_spec_name" lay-verType="tips"
         autocomplete="off"
         switch-tab="1" verify-msg="至少添加一个规格，且规格需要规格值">
        <a class="goods-spec-del-x" style="display: none;">x</a>
        <div class="layui-form-item"><label class="layui-form-label">规格项：</label>
            <div class="layui-input-block" style="width: 500px">
                <div class="layui-input-inline">
                    <input type="hidden" name="spec_id[]" value="0">
                    <input type="text" name="spec_name[]" lay-verify="more_spec_required" lay-verType="tips"
                           switch-tab="1"
                           verify-msg="规格项不能为空"
                           placeholder="请填写规格名" autocomplete="off" class="layui-input spec_name" value="{value}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block goods-spec-value-dev" lay-verify="repetition_spec_value" lay-verType="tips"
                 switch-tab="1">
                <div class="layui-input-inline">
                    <input type="hidden" class="spec_values" name="spec_values[]" value="">
                    <input type="hidden" class="spec_value_ids" name="spec_value_ids[]" value="">
                    <a href="#" class="add-spec-value">+ 添加规格值</a>
                </div>
            </div>
        </div>
    </div>
</script>

<!--规格值模板-->
<script type="text/html" id="template-spec-value">
    <div class="layui-input-inline goods-spec-value" style="width: 90px">
        <a class="goods-spec-value-del-x" style="display: none;">x</a>
        <input value="{spec_value}" spec-value-temp-id="{spec_value_temp_id}" class="layui-input goods-spec-value-input"
               placeholder="规格值"
               lay-verify="more_spec_required" lay-verType="tips" switch-tab="1" verify-msg="规格值不能为空">
        <input type="hidden" class="goods-sepc-value-id-input" value="{spec_value_id}">
    </div>
</script>
<!-- 多规格表头模板 -->
<script type="text/html" id="template-spec-table-th">
    <colgroup>
        <col width="60px">
    </colgroup>
    <thead>
    <tr style="background-color: #f3f5f9">
        {spec_th}
        <th><span class="form-label-asterisk">*</span>市场价(元)</th>
        <th><span class="form-label-asterisk">*</span>价格(元)</th>
    </tr>
    </thead>
</script>
<!--多规格行模板-->
<script type="text/html" id="template-spec-table-tr">
    {spec_td}
    <td><input type="number" class="layui-input" lay-verify="more_spec_required|more_market_price" lay-verType="tips"
               autocomplete="off" switch-tab="1" verify-msg="请输入市场价" name="market_price[]" <?php if(isset($status) && $status == 1): ?>disabled<?php endif; ?>></td>
    <td><input type="number" class="layui-input" lay-verify="more_spec_required|more_price" lay-verType="tips"
               autocomplete="off" switch-tab="1" verify-msg="请输入价格" name="price[]" <?php if(isset($status) && $status == 1): ?>disabled<?php endif; ?>></td>
    </tr>
</script>



<!--物料项模板-->
<script type="text/html" id="template-material">
    <div class="goods-spec-div goods-material" autocomplete="off" style="background-color: white;padding: 20px;margin-bottom: 20px;">
        <a class="goods-spec-del-x" style="display: none;">x</a>
        <div class="layui-form-item" style="clear: unset">
            <div class="layui-input-inline select-material">
                <select name="" lay-filter="material_category" lay-verify="custom_required"
                        lay-verType="tips" switch-tab="0" verify-msg="请选择物料分类" class="material-category" style="width: 190px;height: 38px;">
                </select>
            </div>
            <div class="layui-input-inline more-material">
                <input type="checkbox" name="" lay-skin="primary" title="支持多选" class="choice">
            </div>
            <div class="layui-input-inline must-material">
                <input type="checkbox" name="" lay-skin="primary" title="必选" class="must_select">
            </div>
        </div>
        <div class="checkbox">

        </div>
    </div>
</script>
        <!--销售设置-->
        <div class="layui-tab-item">
    <!--商品排序-->
    <div class="layui-form-item">
        <label class="layui-form-label">商品排序：</label>
        <div class="layui-input-inline">
            <input type="number" name="sort" autocomplete="off" class="layui-input"  value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <span style="color: #a3a3a3;font-size: 9px">排序权重，值越小越靠前</span>
    </div>
    <!--销售状态-->
    <div class="layui-form-item">
        <label class="layui-form-label"><span class="form-label-asterisk">*</span>销售状态：</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="上架销售" verify-msg="选择销售状态" lay-verify="status" >
            <input type="radio" name="status" value="0" title="放入仓库" verify-msg="选择销售状态" lay-verify="status" checked>
        </div>
    </div>
</div>
        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="add-submit" id="add-submit" value="确认">
        </div>
    </div>
</div>

<script src="/static/common/js/array.js"></script>

<script>
    var dragstart = 0;
    var swop_element_ed = -1;
    var create_table_by_spec = null;
    var spec_table_data = [];
    var spec_value_temp_id_number = 0;
    var num = 0;

    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/'
    }).extend({
        likeedit: 'likeedit/likeedit'
    }).use(['table', 'form', 'element', 'likeedit'], function() {
        var form = layui.form
            ,$ = layui.$
            , element = layui.element
            , likeedit = layui.likeedit;

        // -------------------------------------- 下拉菜单渲染 begin -------------------------------
        var goods_category_lists = <?php echo $goods_category_lists; ?>; // 商品分类

        //渲染商家分类
        like.setSelect('', goods_category_lists, "goods_category_id", '分类');
        // -------------------------------------- 下拉菜单渲染 end ---------------------------------

        // ----------------------------------------图片/视频上传 begin -----------------------------
        // 监听图片删除
        like.delUpload();
        // 商品封面图
        $(document).on("click", "#image", function () {
            like.imageUpload({
                limit: 1,
                field: "image",
                that: $(this),
                content: '<?php echo url("file/lists"); ?>?type=10'
            });
        })
        // 放大图片
        $(document).on('click', 'img',function(){
            like.showImg($(this).attr('src'),600);
        });
        // ----------------------------------------图片/视频上传 end -----------------------------

        //------------------------------------------数据验证 begin -------------------------------
        function switchTab(number) {
            $('.goods-tab').removeClass('layui-this');
            $('.goods-content').removeClass('layui-show');
            $('.goods-tab').eq(number).addClass('layui-this');
            $('.goods-content').eq(number).addClass('layui-show');
        }

        form.verify({
            custom_required: function (value, item) {
                if (!$.trim(value)) {
                    switchTab($(item).attr('switch-tab'));
                    return $(item).attr('verify-msg');
                }
            },
            status:function(value,item){
                if(!$('input[name="status"]:checked').val()){
                    return $(item).attr('verify-msg');
                }
            },
            one_spec_required: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 1) {
                    if (!value) {
                        switchTab($(item).attr('switch-tab'));
                        return $(item).attr('verify-msg');
                    }
                }
            },
            add_more_spec: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    if ($('#more-spec-lists-table tbody tr').length == 0) {
                        switchTab($(item).attr('switch-tab'));
                        return $(item).attr('verify-msg');
                    }
                }
            },
            more_spec_required: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    if (!value) {
                        switchTab($(item).attr('switch-tab'));
                        return $(item).attr('verify-msg');
                    }
                }
            },
            one_market_price: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 1) {
                    if (value && value <= 0) {
                        switchTab($(item).attr('switch-tab'));
                        return '市场价必须大于0';
                    }
                }
            },
            one_price: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    if (value && value <= 0) {
                        switchTab($(item).attr('switch-tab'));
                        return '价格必须大于0';
                    }
                }
            },
            more_market_price:function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    if (value && value <= 0) {
                        switchTab($(item).attr('switch-tab'));
                        return '市场价必须大于0';
                    }
                }
            },
            more_price:function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    if (value && value < 0.01) {
                        switchTab($(item).attr('switch-tab'));
                        return '价格必须大于或等于0.01';
                    }
                }
            },
            repetition_spec_name: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    var spec_names = [];
                    $('.spec_name').each(function () {
                        spec_names.push($(this).val());
                    });
                    if ((new Set(spec_names)).size != spec_names.length) {
                        switchTab($(item).attr('switch-tab'));
                        return '规格名称重复';
                    }
                }
            },
            repetition_spec_value: function (value, item) {
                if ($('input[name="spec_type"]:checked').val() == 2) {
                    var spec_values = [];
                    $(item).find('.goods-spec-value-input').each(function () {
                        spec_values.push($(this).val());
                    });
                    if ((new Set(spec_values)).size != spec_values.length) {
                        switchTab($(item).attr('switch-tab'));
                        return '同一规格中，规格值不能重复';
                    }
                }
            }
        });
        //------------------------------------------数据验证 end ----------------------------------
        //------------------------------------------规格型号 begin --------------------------------
        //监听多规格/单规格按钮
        form.on('radio(spec-type)', function (data) {
            switchSpecType(data.value);
        });

        // 统一规格与多规格切换事件
        function switchSpecType(value) {
            var goods_spec_project = $('#goods-spec-project'); // 规格项区域

            if (value == 2) { // 多规格
                $('#add-spec').parent().show(); // 显示添加规格项目按钮

                if (goods_spec_project.children().length > 0) { // 判断规格项区域是否有子元素
                    goods_spec_project.parent().show(); // 显示规格项区域
                    $('#more-spec-lists').show(); // 显示多规格明细
                }
                $('#one-spec-lists').hide(); // 隐藏统一规格规格明细
            } else {
                $('#add-spec').parent().hide(); // 隐藏 添加规格项目 按钮
                goods_spec_project.parent().hide(); // 隐藏规格项区域
                $('#more-spec-lists').hide(); // 隐藏多规格明细
                $('#one-spec-lists').show(); // 显示单规格明细
            }
        }

        //监听添加规格项按钮
        $(document).on('click', '#add-spec', function () {
            addSpec();
        });

        //添加规格项
        function addSpec(value) {
            value = value === undefined ? ' ' : value;
            var element_spec = $('#goods-spec-project'); // 规格项区域
            var count = $('.goods-spec').length; // 规格项数量
            if (count > 2) {
                layer.msg('最多添加3个规格项目');
                return;
            }
            var template_spec = $('#template-spec').html(); // 获取规格项目模板
            // 使用value值替换规格项目模板中{value}占位符，并追加至规格项区域中
            element_spec.append(template_spec.replace('{value}', value));
            $('#goods-spec-project').parent().show();
            form.render('checkbox');
        }

        // 鼠标移入显示删除规格项按钮
        $(document).on('mouseenter', '.goods-spec', function () {
            $(this).find('.goods-spec-del-x').show();
        });

        // 鼠标移出隐藏删除规格项按钮
        $(document).on('mouseleave', '.goods-spec', function () {
            $(this).find('.goods-spec-del-x').hide();
        });

        // 监听删除规格项目按钮
        $(document).on('click', '.goods-spec-del-x', function () {
            $(this).parent().remove(); // 移除当前规格项目
            var goods_spec_project = $('#goods-spec-project');
            if (goods_spec_project.children().length == 0) { // 规格项区域中若没有子元素则隐藏
                goods_spec_project.parent().hide();
            }
            // 触发生成表格函数
            triggerCreateTableBySepc();
        });

        // 监听规格项输入
        $(document).on('input', '.goods-spec input', function () {
            triggerCreateTableBySepc();
            specValueLater();
        });

        // 触发生成规格明细表格
        function triggerCreateTableBySepc() {
            clearTimeout(create_table_by_spec);
            create_table_by_spec = setTimeout(createTableBySepc, 1000);
        }

        // 生成规格明细表格
        function createTableBySepc() {
            if ($('.goods-spec').length <= 0) { // 没有规格项目，隐藏多规格明细
                $('#more-spec-lists').hide();
                return;
            }
            $('#more-spec-lists').show(); // 显示多规格明细
            var table_title = []; // 用于保存 规格项的值
            var table_data = [];  // 规格项数据
            var spec_value_temp_arr = []; // 规格值临时数组
            var i = 0;
            var table_html = '';
            var th_html = $('#template-spec-table-th').html(); // 多规格表头模板
            var tr_html = $('#template-spec-table-tr').html(); // 多规格行模板

            //遍历规格项目
            $('.goods-spec').each(function () {
                var spec_name = $(this).find('.spec_name').first().val(); // 规格项的值 例：颜色
                if (isEmptyString(spec_name)) {
                    return true;
                }
                table_title[i] = spec_name; // 保存 规格项的值  例：['颜色']
                table_data[i] = []; // 例: [[]]
                spec_value_temp_arr[i] = []; // 例：[[]]
                var j = 0;
                // 遍历 当前规格项目 下的所有 规格值
                $(this).find('.goods-spec-value .goods-spec-value-input').each(function () {
                    var spec_value = $(this).val(); // 规格值 例：
                    var spec_value_temp_id = $(this).attr('spec-value-temp-id'); // 规格值临时id
                    if (isEmptyString(spec_value)) {
                        return true;
                    }
                    table_data[i][j] = spec_value; // 将 规格值 保存至 规格项 中
                    spec_value_temp_arr[i][j] = spec_value_temp_id; // 将 规格值临时id 保存至 规格值临时数组 中
                    j++;
                });
                i++;
            });

            //表格头部组装
            spec_th_html = '';
            for (var i in table_title) {
                spec_th_html += '<th>' + table_title[i] + '</th>';
            }
            table_html = th_html.replace('{spec_th}', spec_th_html);
            // 笛卡尔积, 组装SKU 例：[['颜色', 'S码'], ['颜色', 'M码']]
            spec_value_temp_arr = cartesianProduct(spec_value_temp_arr);
            table_data = cartesianProduct(table_data);
            for (var i in table_data) {
                var spec_tr_html = '';
                var tr_name_arr = [];
                var specs = '';
                if (Array.isArray(table_data[i])) {
                    //根据规格创建tr
                    var spec_value_temp_ids = '';
                    for (var j in spec_value_temp_arr[i]) {
                        spec_value_temp_ids += spec_value_temp_arr[i][j] + ',';
                    }
                    spec_value_temp_ids = spec_value_temp_ids.substring(0, spec_value_temp_ids.lastIndexOf(','));
                    spec_tr_html += '<tr spec-value-temp-ids="' + spec_value_temp_ids + '">';

                    for (var j in table_data[i]) {
                        spec_tr_html += '<td>' + table_data[i][j] + '</td>';
                        tr_name_arr[j] = table_data[i][j];
                        specs += table_data[i][j].replace(',', '') + ',';
                    }
                } else {
                    var spec_value_temp_ids = spec_value_temp_arr[i];
                    spec_tr_html = '<tr spec-value-temp-ids="' + spec_value_temp_ids + '">';
                    spec_tr_html += '<td>' + table_data[i] + '</td>';
                    specs += table_data[i].replace(',', '') + ',';
                }
                specs = specs.substring(0, specs.lastIndexOf(','));
                spec_table_data["spec_value_str[]" + spec_value_temp_ids] = specs;
                spec_tr_html += '<td style="display: none"><input type="hidden" name="spec_value_str[]" value="' + specs + '"><input type="hidden" name="item_id[]" value=""></td>';
                table_html += tr_html.replace('{spec_td}', spec_tr_html);
            }

            $('#more-spec-lists-table').html(table_html);
            setTableValue();
        };

        //动态渲染已保存的值
        function setTableValue() {
            $('#more-spec-lists-table').find('input').each(function () {
                var key = $(this).attr('name') + $(this).parent().parent().attr('spec-value-temp-ids');
                if (spec_table_data[key] !== undefined) {
                    $(this).val(spec_table_data[key]);
                }
            });
            $('.goods-spec-img-div').each(function () {
                var key = $(this).parent().parent().attr('spec-value-temp-ids');
                if (spec_table_data["spec_image[]" + key]) {
                    $(this).html('<input name="spec_image[]" type="hidden" value="' + spec_table_data["spec_image[]" + key] + '"><a class="goods-spec-img-del-x">x</a><img class="goods-spec-img" src="' + spec_table_data["spec_image[]" + key] + '">');
                }
            });
        }

        // 监听添加规格值链接被点击: 弹出多行输出框，处理输入的规格值数据，遍历每个规格值并生成相应的html
        $(document).on('click', '.add-spec-value', function () {
            var add_spec_value = $(this);
            layer.prompt({title: '输入规格值，多个请换行', formType: 2}, function (text, index) {
                layer.close(index);
                var specs = text.split('\n');
                for (var i in specs) {
                    specs[i] = specs[i].trim();
                }
                specs = unique(specs);
                var added_specs = [];
                add_spec_value.parent().parent().find('.goods-spec-value-input').each(function () {
                    added_specs.push($(this).val().trim());
                });
                for (var i in specs) {
                    var spec = specs[i].trim();
                    if (spec == '' || in_array(spec, added_specs)) {
                        //已存或为空的不添加
                        continue;
                    }
                    addSpecvalue(add_spec_value, spec, 0);
                }
                specValueLater();
            });
        });

        // 添加规格值: 将【数据】填充至【规格值模板】，并将【规格值模板】追加至【添加规格值】链接前
        function addSpecvalue(add_spec_value, spec, spec_id) {
            var template_spec_value = $('#template-spec-value').html();
            var template_spec_value_html = template_spec_value.replace('{spec_value_temp_id}', spec_value_temp_id_number--);
            template_spec_value_html = template_spec_value_html.replace('{spec_value_id}', spec_id);
            template_spec_value_html = template_spec_value_html.replace('{spec_value}', spec)
            add_spec_value.parent().before(template_spec_value_html);
        }

        //处理每项规格值
        function specValueLater() {
            $('.add-spec-value').each(function () {
                add_spec_value = $(this);
                var spec_values = '';
                add_spec_value.parent().parent().find('.goods-spec-value-input').each(function () {
                    spec_values += $(this).val() + ',';
                });
                add_spec_value.parent().find('.spec_values').val(spec_values.substring(0, spec_values.lastIndexOf(',')));

                var spec_value_ids = '';
                add_spec_value.parent().parent().find('.goods-sepc-value-id-input').each(function () {
                    spec_value_ids += $(this).val() + ',';
                });
                add_spec_value.parent().find('.spec_value_ids').val(spec_value_ids.substring(0, spec_value_ids.lastIndexOf(',')));
                triggerCreateTableBySepc();
            });
        }

        // 显示或隐藏 规格值删除按钮
        $(document).on('mouseenter', '.goods-spec-value', function () {
            $(this).find('.goods-spec-value-del-x').show();
        });

        $(document).on('mouseleave', '.goods-spec-value', function () {
            $(this).find('.goods-spec-value-del-x').hide();
        });

        //删除规格值
        $(document).on('click', '.goods-spec-value-del-x', function () {
            var add_spec_value = $(this).parent().parent().find('.add-spec-value').first();
            $(this).parent().remove();
            specValueLater();
            triggerCreateTableBySepc();
        });

        // 监听规格明细输入，规格数据本地保存
        $(document).on('input', '#more-spec-lists-table input', function () {
            var key = $(this).attr('name') + $(this).parent().parent().attr('spec-value-temp-ids');
            spec_table_data[key] = $(this).val();
        });

        //批量填充
        $(document).on('click', '.batch-spec-content', function () {
            var title = $(this).text();
            var input_name = $(this).attr('input-name');
            layer.prompt({
                formType: 3
                , title: '批量填写' + title
            }, function (value, index, elem) {
                $('input[name="' + input_name + '[]"]').val(value);
                //保存值到本地
                $('#more-spec-lists-table input').each(function () {
                    var key = $(this).attr('name') + $(this).parent().parent().attr('spec-value-temp-ids');
                    spec_table_data[key] = $(this).val();
                });
                layer.close(index);
            });
        });
        //------------------------------------------规格型号 end ------------------------------------

        //------------------------------------------富文本编辑器 begin --------------------------------
        likeedit.set({
            uploadImage: {
                url: '<?php echo url("file/lists"); ?>?type=10',
                type: 'post'
            }
        });
        var likeedit_index = likeedit.build('content');
        form.verify({
            content: function () {
                likeedit.sync(likeedit_index)
            }
        });
        //------------------------------------------富文本编辑器 end --------------------------------
        //------------------------------------ -----编辑页面 begin --------------------------------
        <?php if(!(empty($info) || (($info instanceof \think\Collection || $info instanceof \think\Paginator ) && $info->isEmpty()))): ?>
        var goods_info= <?php echo (isset($info) && ($info !== '')?$info:''); ?>;
        $('input[name="goods_id"]').val(goods_info['base']['id']);
        $('input[name="name"]').val(goods_info['base']['name']);
        $('input[name="code"]').val(goods_info['base']['code']);
        like.setSelect(goods_info['base']['goods_category_id'], goods_category_lists, "goods_category_id", '分类');
        $('input[name="remark"]').val(goods_info['base']['remark']);

        //渲染商品主图
        if(goods_info['base']['image']){
            var html = '' +
                '<div class="upload-image-div">' +
                '<img src="' + goods_info['base']['image'] + '" alt="img" />' +
                '<input type="hidden" name="image" value="' + goods_info['base']['image'] + '">' +
                '<div class="del-upload-btn">x</div>' +
                '</div>' +
                '<div class="upload-image-elem" style="display:none;"><a class="add-upload-image" id="image"> + 添加图片</a></div>';
            $('#imageContainer').html(html);
        }

        // 规格类型
        $("input[name=spec_type][value="+goods_info['base']['spec_type']+"]").prop('checked',"true");

        $('input[name="sort"]').val(goods_info['base']['sort']);  //商品排序
        $("input[name=status][value="+goods_info['base']['status']+"]").prop("checked",true);   //销售状态

        form.render();

        switchSpecType(goods_info['base']['spec_type']);

        if(goods_info['base']['spec_type'] == 1){ // 单规格
            var html = '' +
                '<div class="upload-image-div">' +
                '<img src="' + goods_info['item'][0]['image'] + '" alt="img" />' +
                '<input type="hidden" name="one_spec_image" value="' + goods_info['item'][0]['image'] + '">' +
                '<div class="del-upload-btn">x</div>' +
                '</div>' +
                '<div class="upload-image-elem" style="display:none;"><a class="add-upload-image" id="one_spec_image"> + 添加图片</a></div>';
            if(goods_info['item'][0]['image']){
                $('#one_spec_image').parent().parent().html(html);
            }
            $('input[name="one_market_price"]').val(goods_info['item'][0]['market_price']);
            $('input[name="one_price"]').val(goods_info['item'][0]['price']);
        }

        if(goods_info['base']['spec_type'] == 2) { // 多规格
            for(var i in goods_info['spec']){
                addSpec(goods_info['spec'][i]['name']);
                var spes_values = goods_info['spec'][i]['values'];
                for(var j in  spes_values){
                    addSpecvalue($('.add-spec-value').eq(i),spes_values[j]['value'],spes_values[j]['id']);
                }

            }
            for(var i in goods_info['spec']){
                $('input[name="spec_id[]"]').eq(i).val(goods_info['spec'][i]['id']);
            }
            specValueLater();
            createTableBySepc();
            for(var i in goods_info['item']){
                $('#more-spec-lists-table tbody tr').each(function() {
                    var spec_value_str = $(this).find('input[name="spec_value_str[]"]').first().val();
                    if(spec_value_str == goods_info['item'][i]['spec_value_str']){
                        spec_value_temp_ids = $(this).attr('spec-value-temp-ids');
                        spec_table_data["spec_image[]"+spec_value_temp_ids] = goods_info['item'][i]['abs_image'];
                        spec_table_data["price[]"+spec_value_temp_ids] = goods_info['item'][i]['price'];
                        spec_table_data["market_price[]"+spec_value_temp_ids] = goods_info['item'][i]['market_price'];
                        spec_table_data["item_id[]"+spec_value_temp_ids] = goods_info['item'][i]['id'];
                        spec_table_data["spec_value_str[]"+spec_value_temp_ids] = goods_info['item'][i]['spec_value_str'];
                        return false;
                    }
                });
            }
            setTableValue();
        }

        //物料项
        var material_category = <?php echo $material_category; ?>;
        $('.material-title').hide();
        $('#goods-material-project').parent().show();
        if (<?php echo htmlentities($status); ?> == 1) {
            $('#add-material').parent().hide();
        }
        var goods_material= <?php echo $goods_material; ?>;
        let goods_material_id_arr = goods_material['goods_material'].map(obj => {return obj.material_id})

        for (var i in goods_material['goods_material_category']) {
            addMaterial('',goods_material['goods_material_category'][i]['category_id']);
            if (<?php echo htmlentities($status); ?> == 1) {
                $('.material-category').attr('disabled','disabled');
                $('.choice').attr('disabled','disabled');
                $('.must_select').attr('disabled','disabled');
            }
            if (goods_material['goods_material_category'][i]['all_choice'] == 1) {
                $('.choice').eq(i).attr('checked','checked');
            }
            if (goods_material['goods_material_category'][i]['must_select'] == 1) {
                $('.must_select').eq(i).attr('checked','checked');
            }
            var checkbox_html = '';
            for (var j in material_category) {
                if (material_category[j]['id'] == goods_material['goods_material_category'][i]['category_id']) {
                    for (var k in material_category[j]['material']) {
                        if (<?php echo htmlentities($status); ?> == 1) {
                            if (goods_material_id_arr.indexOf(material_category[j]["material"][k]["id"]) > -1) {
                                checkbox_html += '<input type="checkbox" name="material_category['+ i +'][material][]" lay-skin="primary" value="' + material_category[j]["material"][k]["id"] + '" title="' + material_category[j]['material'][k]['name'] + '" checked disabled>';
                            } else {
                                checkbox_html += '<input type="checkbox" name="material_category['+ i +'][material][]" lay-skin="primary" value="' + material_category[j]["material"][k]["id"] + '" title="' + material_category[j]['material'][k]['name'] + '" disabled>';
                            }
                        }else {
                            if (goods_material_id_arr.indexOf(material_category[j]["material"][k]["id"]) > -1) {
                                checkbox_html += '<input type="checkbox" name="material_category['+ i +'][material][]" lay-skin="primary" value="' + material_category[j]["material"][k]["id"] + '" title="' + material_category[j]['material'][k]['name'] + '" checked>';
                            } else {
                                checkbox_html += '<input type="checkbox" name="material_category['+ i +'][material][]" lay-skin="primary" value="' + material_category[j]["material"][k]["id"] + '" title="' + material_category[j]['material'][k]['name'] + '">';
                            }
                        }

                    }
                }
            }

            $('.checkbox').eq(i).html(checkbox_html);
            form.render('checkbox');
        }

        likeedit.setContent(likeedit_index,goods_info['base']['content']);
        form.render();


        <?php endif; ?>
        //-----------------------------------------编辑页面 end --------------------------------
        //------------------------------------ -----物料项 begin --------------------------------
        //监听添加物料项按钮
        $(document).on('click', '#add-material', function () {
            addMaterial();
            $('.material-title').hide();
        });

        //添加物料项
        function addMaterial(value,category_value) {
            value = value === undefined ? ' ' : value;
            category_value = category_value === undefined ? ' ' : category_value;
            var element_spec = $('#goods-material-project'); // 物料项区域
            var count = $('.goods-material').length; // 物料项数量
            if (count > 3) {
                layer.msg('最多添加4个物料项');
                return;
            }
            var template_spec = $('#template-material').html(); // 获取物料项模板
            // 使用value值替换物料项模板中{value}占位符，并追加至物料项区域中
            element_spec.append(template_spec.replace('{value}', value));
            $('#goods-material-project').parent().show();
            form.render('checkbox');

            //渲染物料分类下拉框
            setTimeout(() => {
                materialCategory(category_value);
            }, 0.1 * 1000)

        }

        // 鼠标移入显示删除物料项按钮
        $(document).on('mouseenter', '.goods-material', function () {
            $(this).find('.goods-spec-del-x').show();
        });

        // 鼠标移出隐藏删除物料项按钮
        $(document).on('mouseleave', '.goods-material', function () {
            $(this).find('.goods-spec-del-x').hide();
        });

        // 监听删除物料项按钮
        $(document).on('click', '.goods-spec-del-x', function () {
            $(this).parent().remove(); // 移除当前物料项
            var goods_spec_project = $('#goods-material-project');
            if (goods_spec_project.children().length == 0) { // 物料项区域中若没有子元素则隐藏
                goods_spec_project.parent().hide();
                $('.material-title').show();
            }
        });

        //物料联动
        var material_category = <?php echo $material_category; ?>;

        function materialCategory(value) {
            value = value === undefined ? ' ' : value;
            layui.use(['form'], function () {
                var form = layui.form;
                var selectHtml = '<option value="">请选择物料分类</option>';
                for (var i in material_category) {
                    if (value == material_category[i]['id']) {
                        selectHtml += '<option value="' + material_category[i]['id'] + '" selected>' + material_category[i]['name'] + '</option>';
                    }else {
                        selectHtml += '<option value="' + material_category[i]['id'] + '">' + material_category[i]['name'] + '</option>';
                    }
                }
                $('.material-category').eq(num).html(selectHtml);
                $('.material-category').eq(num).attr('name','material_category['+ num +'][material_category_id]');
                $('.material-category').eq(num).parent('.select-material').siblings('.more-material').find('.choice').attr('name','material_category['+ num +'][choice]');
                $('.material-category').eq(num).parent('.select-material').siblings('.must-material').find('.must_select').attr('name','material_category['+ num +'][must_select]');
                form.render('select');
                num = num + 1;
            });
        }
        form.on('select(material_category)', function (data) {
            var checkbox_html = '';

            for (var i in material_category) {
                if (material_category[i]['id'] == data.value) {
                    for (var j in material_category[i]['material']) {
                        checkbox_html += '<input type="checkbox" name="material_category['+ (num - 1) +'][material][]" lay-skin="primary" value="' + material_category[i]["material"][j]["id"] + '" title="' + material_category[i]['material'][j]['name'] + '">';
                    }
                }
            }
            $(this).parents('.goods-material').find('.checkbox').html(checkbox_html);
            form.render('checkbox');
        });
                //------------------------------------ -----物料项 end--------------------------------
    });
</script>
</body>
</html>
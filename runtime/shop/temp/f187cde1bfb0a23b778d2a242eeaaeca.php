<?php /*a:5:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\goods\shop_goods\edit.html";i:1682257423;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout2.html";i:1676699056;s:72:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\goods\shop_goods\goods_base.html";i:1676699056;s:72:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\goods\shop_goods\goods_spec.html";i:1682257711;s:80:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\goods\shop_goods\goods_sale_setting.html";i:1676699056;}*/ ?>
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
                    <input type="text" name="code" lay-verType="tips" autocomplete="off" switch-tab="0" class="layui-input" value="<?php echo htmlentities($info['code']); ?>" disabled>
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
                           class="layui-input" value="<?php echo htmlentities($info['name']); ?>" disabled>
                </div>
            </div>
            <!--商品简介-->
            <div class="layui-form-item">
                <label class="layui-form-label">商品简介：</label>
                <div class="layui-input-block">
                    <input type="text" maxlength="60" name="remark"  autocomplete="off" class="layui-input" value="<?php echo htmlentities($info['remark']); ?>" disabled>
                </div>
            </div>
            <!--商品分类-->
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品分类：</label>
                <div class="layui-input-inline">
                    <select name="goods_category_id" lay-filter="goods_category_id" lay-verify="custom_required"
                            lay-verType="tips" switch-tab="0" verify-msg="请选择分类" disabled>
                        <option><?php echo htmlentities($info['goods_category_name']); ?></option>
                    </select>
                </div>
            </div>
            <!--商品封面图-->
            <div class="layui-form-item" style="margin-bottom: 0px">
                <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品封面图：</label>
                <div class="layui-input-block" id="imageContainer">
                    <div class="like-upload-image">
                        <div class="upload-image-elem"><img src="<?php echo htmlentities($info['image']); ?>" style="width: 80px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!--规格型号-->
        <div class="layui-tab-item goods-content">
    <!--规格型号-->
    <div class="layui-card-body" pad15>
        <div lay-filter="">

            <!-- 统一规格 -->
            <div class="layui-form-item" id="one-spec-lists">

                <!-- 批量 -->
                <div class="layui-form-item" id="more-spec-lists">
                    <div class="layui-input-block goods-spec-div">
                        <div class="batch-div add-more-spec" <?php if(isset($status) && $status == 1): ?> style="display: none"<?php endif; ?>><span class="batch-spec-title">批量设置：</span>
                            <div>
                                <!-- <span class="batch-spec-content click-a" input-name="market_price">市场价</span> -->
                                <span class="batch-spec-content click-a" input-name="kucun">门店库存</span>
                            </div>
                        </div>
                        <table id="more-spec-lists-table" class="layui-table spec-lists-table" lay-size="sm">
                        </table>
                    </div>
                </div>
                <label class="layui-form-label">规格明细：</label>
                <div class="layui-input-block goods-spec-div">
                    <table id="one-spec-lists-table" class="layui-table spec-lists-table" lay-size="sm">
                        <thead>
                        <tr style="background-color: #f3f5f9">
                            <th style="width: 100px"><span class="form-label-asterisk">*</span>商品规格</th>
                            <th><span class="form-label-asterisk">*</span>市场价(元)</th>
                            <th><span class="form-label-asterisk">*</span>价格(元)</th>
                            <th><span class="form-label-asterisk">*</span>门店库存</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($info['goods_item'] as $key=>$val): ?>
                        <tr>
                            <input type="hidden" name="goods_item[<?php echo htmlentities($key); ?>][id]" value="<?php echo htmlentities($val['id']); ?>">
                            <td><input type="text" class="layui-input"
                                       name="spec_value_str" value="<?php echo htmlentities($val['spec_value_str']); ?>" disabled></td>
                            <td><input type="number" class="layui-input"
                                       name="market_price" value="<?php echo htmlentities($val['market_price']); ?>" disabled></td>
                            <td><input type="number" class="layui-input"
                                       lay-verify="price_required|price"
                                       autocomplete="off" switch-tab="1" verify-msg="请输入价格"
                                       name="goods_item[<?php echo htmlentities($key); ?>][price]" value="<?php echo htmlentities($val['price']); ?>" <?php if($info['pricing_policy'] != 2): ?> disabled<?php endif; ?>></td>
                            <td><input type="number" class="layui-input kucun"
                                       lay-verify="stock_required|stock"
                                       autocomplete="off" switch-tab="1" verify-msg="请输入库存"
                                       name="goods_item[<?php echo htmlentities($key); ?>][stock]" value="<?php echo htmlentities($val['stock']); ?>"></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="margin-top: 100px;">
            <!-- 物料项 -->
            <div class="layui-form-item">
                <label class="layui-form-label">商品物料：</label>
                <div class="layui-input-block goods-material-div" id="goods-material-project">
                    <?php foreach($info['goods_material_category'] as $value): ?>
                    <div class="goods-spec-div goods-material" autocomplete="off" style="background-color: white;padding: 20px;margin-bottom: 20px;">
                        <div class="layui-form-item" style="clear: unset">
                            <div class="layui-input-inline select-material">
                                <select name="material_category_id[]" lay-filter="material_category" lay-verify="custom_required"
                                        lay-verType="tips" switch-tab="0" verify-msg="请选择物料分类" class="material-category" style="width: 190px;height: 38px;" disabled>
                                    <option value="<?php echo htmlentities($value['category_id']); ?>"><?php echo htmlentities($value['name']); ?></option>
                                </select>
                            </div>
                            <div class="layui-input-inline more-material">
                                <input type="checkbox" name="choice[]" lay-skin="primary" title="支持多选" class="choice" <?php if($value['all_choice'] == 1): ?>checked<?php endif; ?> disabled>
                            </div>
                        </div>
                        <div class="checkbox">
                            <?php foreach($value['goods_material'] as $key=>$val): ?>
                            <input type="checkbox" name="material[]" lay-skin="primary" value="<?php echo htmlentities($val['id']); ?>" title="<?php echo htmlentities($val['name']); ?>" checked disabled>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

        <!--销售设置-->
        <div class="layui-tab-item">
    <!--商品排序-->
    <div class="layui-form-item">
        <label class="layui-form-label">商品排序：</label>
        <div class="layui-input-inline">
            <input type="number" name="sort" autocomplete="off" class="layui-input"  value="<?php echo htmlentities($info['sort']); ?>" disabled>
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
            <input type="radio" name="status" value="1" title="上架销售" verify-msg="选择销售状态" lay-verify="status" <?php if($info['status'] == 1): ?>checked<?php endif; ?> disabled>
            <input type="radio" name="status" value="0" title="放入仓库" verify-msg="选择销售状态" lay-verify="status" <?php if($info['status'] == 0): ?>checked<?php endif; ?> disabled>
        </div>
    </div>
</div>
        <div class="layui-form-item layui-hide">
            <input type="hidden" value="<?php echo htmlentities($info['shop_goods_id']); ?>" name="goods_id">
            <input type="button" lay-submit lay-filter="edit-submit" id="edit-submit" value="确认">
        </div>
    </div>
</div>

<script src="/static/common/js/array.js"></script>

<script>
    var dragstart = 0;
    var spec_table_data = [];

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


        //------------------------------------------数据验证 begin -------------------------------
        function switchTab(number) {
            $('.goods-tab').removeClass('layui-this');
            $('.goods-content').removeClass('layui-show');
            $('.goods-tab').eq(number).addClass('layui-this');
            $('.goods-content').eq(number).addClass('layui-show');
        }

        form.verify({
            price: function (value, item) {
                if (value && value < 0) {
                    switchTab($(item).attr('switch-tab'));
                    return '价格必须大于等于0';
                }
            },
            stock: function (value, item) {
                if (value && value <= 0) {
                    switchTab($(item).attr('switch-tab'));
                    return '库存大于0';
                }
            },
        });
        //------------------------------------------数据验证 end ----------------------------------
        //批量填充
        $(document).on('click', '.batch-spec-content', function () {
            var title = $(this).text();
            var input_name = $(this).attr('input-name');
            layer.prompt({
                formType: 3
                , title: '批量填写' + title
            }, function (value, index, elem) {
                $('.'+input_name).val(value);
                layer.close(index);
            });
        });
    });
</script>
</body>
</html>
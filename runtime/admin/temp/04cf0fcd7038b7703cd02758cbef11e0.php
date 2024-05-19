<?php /*a:2:{s:60:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\shop\shop\edit.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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
    .layui-form-item .layui-form-label { width: 95px; }
    .layui-form-item .layui-input-inline { width: 240px; }
    #map-container{
        width: 1000px;
        height: 600px;
        margin-left:18px;
        margin-top:20px;
        z-index: 0;
    }
</style>

<div class="layui-card layui-form" style="box-shadow:none;">
    <div class="layui-tab layui-tab-card" lay-filter="like-tabs">
        <ul class="layui-tab-title">
            <li lay-id="1" class="layui-this">基础设置</li>
            <li lay-id="2">经营设置</li>
        </ul>
        <div class="layui-tab-content">
            <!-- 1、基础设置 -->
            <input type="hidden" value="<?php echo htmlentities($detail['id']); ?>" name="id">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>门店编号：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="shop_sn" value="<?php echo htmlentities($detail['shop_sn']); ?>" id="shop_sn" lay-verType="tips" lay-verify="shop_sn"
                               switch-tab="0" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>门店名称：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="<?php echo htmlentities($detail['name']); ?>" id="name" lay-verType="tips" lay-verify="name"
                               switch-tab="0" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>联系人：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="contact" value="<?php echo htmlentities($detail['contact']); ?>" id="contact" lay-verType="tips" lay-verify="contact"
                               switch-tab="0" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>联系手机：</label>
                    <div class="layui-input-inline">
                        <input type="number" name="phone"  value="<?php echo htmlentities($detail['phone']); ?>" id="phone" lay-verType="tips" lay-verify="phone"
                               switch-tab="0" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>门店地址：</label>
                    <div class="layui-input-inline">
                        <select name="province_id" id="province" lay-filter="province" lay-verify="required"
                                lay-verType="tips" switch-tab="0">
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="city_id" id="city" lay-filter="city" lay-verify="required">
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="district_id" id="district" lay-filter="district" lay-verify="required">
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>详细地址：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="address"  value="<?php echo htmlentities($detail['address']); ?>"  lay-verify="required" lay-verType="tips"  placeholder="" autocomplete="off" class="layui-input">
                        <input type="hidden" name="longitude" value="<?php echo htmlentities($detail['longitude']); ?>"class="layui-input" lay-verify="location">
                        <input type="hidden" name="latitude" value="<?php echo htmlentities($detail['latitude']); ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <button class="layui-btn layui-btn-normal" id="search_map">搜索地图</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span style="color: red">*</span>
                        地图定位：
                    </label>
                    <div class="layui-input-block">
                        <div id="map-container" ></div>
                    </div>
                </div>
            </div>
            <!-- 2、经营设置 -->
            <div class="layui-tab-item">
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>价格方式：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="pricing_policy" value="1" title="总部定价" <?php if($detail['pricing_policy']==1): ?>checked<?php endif; ?>>
                        <input type="radio" name="pricing_policy" value="2" title="门店定价" <?php if($detail['pricing_policy']==2): ?>checked<?php endif; ?>>
                    </div>
                    <div class="layui-form-mid layui-word-aux">选择门店定价后，总部调整价格将不影响门店价格</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>营业周天：</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="weekdays[]" value="1" lay-skin="primary" title="周一" <?php if(in_array(1,$detail['weekdays'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="weekdays[]" value="2" lay-skin="primary" title="周二" <?php if(in_array(2,$detail['weekdays'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="weekdays[]" value="3" lay-skin="primary" title="周三" <?php if(in_array(3,$detail['weekdays'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="weekdays[]" value="4" lay-skin="primary" title="周四" <?php if(in_array(4,$detail['weekdays'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="weekdays[]" value="5" lay-skin="primary" title="周五" <?php if(in_array(5,$detail['weekdays'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="weekdays[]" value="6" lay-skin="primary" title="周六" <?php if(in_array(6,$detail['weekdays'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="weekdays[]" value="7" lay-skin="primary" title="周天" <?php if(in_array(7,$detail['weekdays'])): ?>checked<?php endif; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>营业时段：</label>
                    <div class="layui-input-inline">
                        <div class="layui-input-inline">
                            <input type="text" value="<?php echo htmlentities($detail['business_start_time']); ?>"  class="layui-input" id="business_start_time" name="business_start_time" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-input-inline" style="margin-right: 5px;width: 20px;">
                        <label class="layui-form-mid">—</label>
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" value="<?php echo htmlentities($detail['business_end_time']); ?>" id="business_end_time" name="business_end_time" lay-verify="required">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>外卖配送距离：</label>
                    <div class="layui-input-inline">
                        <input type="number" name="delivery_distance" value="<?php echo htmlentities($detail['delivery_distance']); ?>" id="delivery_distance" lay-verType="tips" lay-verify="delivery_distance"
                               switch-tab="1" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">公里</div>
                </div>
                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>配送方式：</label>
                    <div class="layui-input-inline">
                        <input type="checkbox" name="delivery_type[]" value="1" lay-skin="primary" title="自提" <?php if(in_array(1,$detail['delivery_type'])): ?>checked<?php endif; ?>>
                        <input type="checkbox" name="delivery_type[]" value="2" lay-skin="primary" title="外卖" <?php if(in_array(2,$detail['delivery_type'])): ?>checked<?php endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>外卖起送价格：</label>
                    <div class="layui-input-inline">
                        <input type="number" name="delivery_buy_limit"  value="<?php echo htmlentities($detail['delivery_buy_limit']); ?>"  id="delivery_buy_limit" lay-verType="tips" lay-verify="delivery_buy_limit"
                               switch-tab="1" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">元</div>
                </div>
                <div class="layui-form-item">
                    <label  class="layui-form-label"><span style="color:red;">*</span>外卖配送运费：</label>
                    <div class="layui-input-inline">
                        <input type="number" name="delivery_freight" value="<?php echo htmlentities($detail['delivery_freight']); ?>"  id="delivery_freight" lay-verType="tips" lay-verify="delivery_freight"
                               switch-tab="1" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">元</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red;">*</span>门店状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="0" title="停业"  <?php if($detail['status']==0): ?>checked<?php endif; ?>>
                        <input type="radio" name="status" value="1" title="营业"  <?php if($detail['status']==1): ?>checked<?php endif; ?>>
                    </div>
                    <div class="layui-form-mid layui-word-aux">门店停业后，不能对外提供服务</div>
                </div>

            </div>
        </div>
    </div>

    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="edit-submit" id="edit-submit" value="确认">
    </div>
</div>
<script src="/static/common/js/area.js"></script>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=<?php echo htmlentities($qq_map_key); ?>"></script>
<script>
    layui.config({
        version: "<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/'
    }).extend({
        likeArea: 'likeArea/likeArea',
        txMap:'likeMap/txMap',
    }).use(['likeArea', 'form','txMap','laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var like_area = layui.likeArea;
        var txMap = layui.txMap;
        var laydate = layui.laydate;
        laydate.render({
            elem: '#business_start_time'
            ,type: 'time'
            ,format: 'HH:mm'
        });

        laydate.render({
            elem: '#business_end_time'
            ,type: 'time'
            ,format: 'HH:mm'
        });
        // 三级联动地址
        like_area.init('province','city','district','province_id','city_id','district_id',<?php echo htmlentities($detail['province_id']); ?>,<?php echo htmlentities($detail['city_id']); ?>,<?php echo htmlentities($detail['district_id']); ?>);
        //加载腾讯地图
        txMap.init(<?php echo htmlentities($detail['latitude']); ?>,<?php echo htmlentities($detail['longitude']); ?>);



    })
</script>
</body>
</html>
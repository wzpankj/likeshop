<?php /*a:2:{s:64:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\wechat\mnp\set_mnp.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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
    .layui-form-label {
        width: 156px;
    }

    .tips {
        color: red;
    }

    .copy {
        margin-left: 10px;
    }

    .layui-form-item .layui-input-inline {
        width: 410px;
    }

    .layui-input {
        display: inline-block;
        width: 80%;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-collapse like-layui-collapse" lay-accordion="" style="border:1px dashed #c4c4c4">
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title like-layui-colla-title" style="background-color: #fff">操作提示</h2>
                    <div class="layui-colla-content layui-show">
                        *填写微信小程序开发配置，请前往微信公众平台申请小程序并完成认证。
                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="layui-card-header" style="margin-top: 20px">小程序配置</div>-->
        <div class="layui-card-body" pad15>
            <div class="layui-form" lay-filter="">
                <!--                微信小程序-->
                <div class="layui-form-item div-flex">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>微信小程序</legend>
                    </fieldset>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">小程序名称：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="<?php echo htmlentities($mnp['name']); ?>" class="layui-input" autocomnplete="off">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">原始ID：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="original_id" value="<?php echo htmlentities($mnp['original_id']); ?>" class="layui-input"
                               autocomnplete="off">
                    </div>

                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">小程序码：</label>
                    <div class="layui-input-block">
                        <div class="like-upload-image">
                            <?php if($mnp['qr_code']): ?>
                            <div class="upload-image-div">
                                <img src="<?php echo htmlentities($mnp['qr_code']); ?>" alt="img">
                                <input type="hidden" name="bg_image" value="<?php echo htmlentities($mnp['qr_code']); ?>">
                                <div class="del-upload-btn">x</div>
                            </div>
                            <div class="upload-image-elem" style="display:none;"><a class="add-upload-image"
                                                                                    id="qrcode"> + 添加图片</a></div>
                            <?php else: ?>
                            <div class="upload-image-elem"><a class="add-upload-image" id="qrcode"> + 添加图片</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">建议尺寸：宽400px*高400px。jpg，jpeg，png格式</span>
                </div>
                <!--                开发者ID-->
                <div class="layui-form-item div-flex">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>开发者ID</legend>
                    </fieldset>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="tips">*</span>AppID：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="app_id" value="<?php echo htmlentities($mnp['app_id']); ?>" autocomplete="off" lay-verify="required"
                               lay-verType="tips"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="tips">*</span>AppSecret：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="app_secret" value="<?php echo htmlentities($mnp['app_secret']); ?>" lay-verify="required"
                               lay-verType="tips" autocomnplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->开发者ID，设置AppID和AppSecret</span>
                </div>
                <!--                服务器域名-->
                <div class="layui-form-item div-flex">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>服务器域名</legend>
                    </fieldset>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">request合法域名：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="request_domain" readonly="readonly" value="<?php echo htmlentities($mnp['request_domain']); ?>"
                               class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->服务器域名，填写https协议域名</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">socket合法域名：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="socket_domain" readonly="readonly" value="<?php echo htmlentities($mnp['socket_domain']); ?>"
                               class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->服务器域名，填写wss协议域名</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">uploadFile合法域名：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="uploadfile_domain" readonly="readonly" value="<?php echo htmlentities($mnp['uploadfile_domain']); ?>"
                               class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->服务器域名，填写https协议域名</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">downloadFile合法域名：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="downloadfile_domain" readonly="readonly"
                               value="<?php echo htmlentities($mnp['downloadfile_domain']); ?>"
                               class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->服务器域名，填写https协议域名</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">udp合法域名：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="udp_domain" readonly="readonly" value="<?php echo htmlentities($mnp['udp_domain']); ?>"
                               class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->服务器域名，填写udp协议域名</span>
                </div>
                <!--                业务域名-->
                <div class="layui-form-item div-flex">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>业务域名</legend>
                    </fieldset>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">业务域名：</label>
                    <div class="layui-input-inline">
                        <input type="text" readonly="readonly" value="<?php echo htmlentities($mnp['business_domain']); ?>" class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置->业务域名，填写业务域名</span>
                </div>
                <!--                消息推送-->
                <div class="layui-form-item div-flex">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>消息推送</legend>
                    </fieldset>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">URL：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="url" readonly="readonly" value="<?php echo htmlentities($mnp['url']); ?>" class="layui-input"
                               autocomplete="off">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置>消息推送配置，填写服务器地址（URL）</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">Token：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="token" value="<?php echo htmlentities($mnp['token']); ?>" autocomnplete="off" class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">小程序账号登录微信公众平台，点击开发>开发设置>消息推送配置，设置令牌Token。不填默认为“LikeMall”</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">EncodingAESKey：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="encoding_ses_key" value="<?php echo htmlentities($mnp['encoding_ses_key']); ?>" autocomnplete="off"
                               class="layui-input">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm copy">复制</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">消息加密密钥由43位字符组成，字符范围为A-Z,a-z,0-9</span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">消息加密方式：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="encryption_type" <?php if($mnp['encryption_type']== 1): ?> checked <?php endif; ?> value="1"
                        title="明文模式(不使用消息体加解密功能，安全系数较低)" />
                    </div>
                    <div class="layui-input-block">
                        <input type="radio" name="encryption_type" <?php if($mnp['encryption_type']== 2): ?> checked <?php endif; ?> value="2"
                        title="兼容模式(明文、密文将共存，方便开发者调试和维护)" >
                    </div>
                    <div class="layui-input-block" style="margin-left: 186px">
                        <input type="radio" name="encryption_type" <?php if($mnp['encryption_type']== 3): ?> checked <?php endif; ?> value="3"
                        title="安全模式（推荐）(消息包为纯密文，需要开发者加密和解密，安全系数高)" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">数据格式：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="data_type" value="1" title="JSON" <?php if($mnp['data_type']== 1): ?> checked <?php endif; ?>
                        >
                        <input type="radio" name="data_type" value="2" title="XML" <?php if($mnp['data_type']== 2): ?> checked <?php endif; ?>
                        >

                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="setmnp">确定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
    layui.config({
        version: "<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/' //静态资源所在路径
    }).use(['form'], function () {
        var $ = layui.$, form = layui.form;

        // 小程序码图片
        $(document).on("click", "#qrcode", function () {
            like.imageUpload({
                limit: 1,
                field: "qr_code",
                that: $(this)
            });
        })
        // 删除图片
        like.delUpload();

        //显示图片
        $(document).on('click', 'img', function () {
            var image = $(this).attr('src');
            like.showImg(image, 600);
        });

        //复制
        $(document).on('click', '.copy', function () {
            var copyText = $(this).prev()
            copyText.select()
            document.execCommand("Copy");
        })

        form.on('submit(setmnp)', function (data) {
            like.ajax({
                url: '<?php echo url("wechat.mnp/setMnp"); ?>' //实际使用请改成服务端真实接口
                , data: data.field
                , type: 'post'
                , success: function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        });
                    }

                }
            });
        });
    });

</script>
</body>
</html>
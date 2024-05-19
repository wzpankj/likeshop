<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\finance\center\center.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
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


<div class="wrapper">
    <div class="layui-card">
        <!-- 财务汇总 -->
        <h2 style="margin: 20px;">财务汇总</h2>
        <div style="margin: 0 20px">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md3" >
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">全部订单</div>
                        <div class="layui-card-body"><p><?php echo htmlentities($statistics['all_order']); ?></p></div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">到店订单</div>
                        <div class="layui-card-body"><p><?php echo htmlentities($statistics['shop_order']); ?></p></div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">外卖订单</div>
                        <div class="layui-card-body"><p><?php echo htmlentities($statistics['takeout_order']); ?></p></div>
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md3" >
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">营业额</div>
                        <div class="layui-card-body"><p>￥<?php echo htmlentities($statistics['sale_amount']); ?></p></div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">到店营业额</div>
                        <div class="layui-card-body"><p>￥<?php echo htmlentities($statistics['shop_sale_amount']); ?></p></div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">外卖营业额</div>
                        <div class="layui-card-body"><p>￥<?php echo htmlentities($statistics['takeout_sale_amount']); ?></p></div>
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md3" >
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">已退款金额</div>
                        <div class="layui-card-body"><p>￥<?php echo htmlentities($statistics['refund_amount']); ?></p></div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card" style="box-shadow:none;">
                        <div class="layui-card-header" style="border-bottom:0;">退款中金额</div>
                        <div class="layui-card-body"><p>￥<?php echo htmlentities($statistics['wait_refund_amount']); ?></p></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 财务查询 -->
        <h2 style="padding:20px;">财务查询</h2>
        <div class="layui-card-body layui-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">查询日期：</label>
                    <div class="layui-inline" style="margin-right:0;">
                        <div class="layui-input-inline" >
                            <input type="text" id="start_time" name="start_time" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">至</div>
                    <div class="layui-inline" style="margin-right:0;">
                        <div class="layui-input-inline" style="margin-right:0;">
                            <input type="text" id="end_time" name="end_time" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>

                <div class="layui-inline">
                    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="search">搜索</a>
                    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="export">导出</a>
                    <a class="layui-btn layui-btn-sm layui-btn-primary" lay-submit lay-filter="clear-search">重置</a>
                </div>
            </div>
        </div>

        <!-- 主体区域 -->
        <div class="layui-card-body">
            <table id="like-table-lists" lay-filter="like-table-lists"></table>
            <script type="text/html" id="sale_amount">
                ￥{{ d.sale_amount }}
            </script>
            <script type="text/html" id="shop_sale_amount">
                ￥{{ d.shop_sale_amount }}
            </script>
            <script type="text/html" id="takeout_sale_amount">
                ￥{{ d.takeout_sale_amount }}
            </script>
            <script type="text/html" id="refund_amount">
                ￥{{ d.refund_amount }}
            </script>
        </div>
    </div>
</div>

<script>
    layui.use(["table", "form", "laydate"], function(){
        var table   = layui.table;
        var form    = layui.form;
        var laydate = layui.laydate;

        laydate.render({type:"datetime", elem:"#start_time", trigger:"click", value:"<?php echo htmlentities($week[0]); ?>"});
        laydate.render({type:"datetime", elem:"#end_time", trigger:"click", value:"<?php echo htmlentities($week[1]); ?>"});

        //立即搜索
        form.on("submit(search)", function(data){
            table.reload("like-table-lists", {
                where: data.field,
                page: {
                    curr: 1
                }
            });
        });

        //重置搜索
        form.on("submit(clear-search)", function(){
            $("#start_time").val("<?php echo htmlentities($week[0]); ?>");
            $("#end_time").val("<?php echo htmlentities($week[1]); ?>");
            table.reload("like-table-lists", {
                where: {},
                page: {
                    curr: 1
                }
            });
        });


        getList('');
        function getList() {
            table.render({
                elem: '#like-table-lists'
                , url: '<?php echo url("finance.center/center"); ?>'
                , totalRow: true
                , cols: [[
                    {field:"time", align:"center", title:"日期", totalRowText: '汇总'}
                    ,{field:"sale_amount", align:"center",title:"营业额", totalRow: true, templet: '#sale_amount'}
                    ,{field:"shop_sale_amount", align:"center", title:"到店营业额", totalRow: true, templet: '#shop_sale_amount'}
                    ,{field:"takeout_sale_amount", align:"center", title:"外卖营业额", totalRow: true, templet: '#takeout_sale_amount'}
                    ,{field:"refund_amount", align:"center", title:"退款金额", totalRow: true, templet: '#refund_amount'}
                    ,{field:"all_order", align:"center", title:"全部订单", totalRow: '{{ parseInt(d.TOTAL_NUMS) }}'}
                    ,{field:"shop_order", align:"center", title:"到店订单", totalRow: '{{ parseInt(d.TOTAL_NUMS) }}'}
                    ,{field:"takeout_order", align:"center", title:"外卖订单", totalRow: '{{ parseInt(d.TOTAL_NUMS) }}'}
                ]]
                , page: true
                , text: {none: '暂无数据！'}
                ,response: {
                    statusCode: 1
                }
                , parseData: function (res) {
                    return {
                        "code": res.code,
                        "msg": res.msg,
                        "count": res.data.count,
                        "data": res.data.lists,
                    };
                }
                ,done: function(res, curr, count){
                    // 解决操作栏因为内容过多换行问题
                    $(".layui-table-main tr").each(function (index, val) {
                        $($(".layui-table-fixed-l .layui-table-body tbody tr")[index]).height($(val).height());
                        $($(".layui-table-fixed-r .layui-table-body tbody tr")[index]).height($(val).height());
                    });
                }
            });
        }


        //导出
        form.on('submit(export)', function(data){
            var field = data.field;
            $.ajax({
                url: '<?php echo url("finance.center/exportFile"); ?>',
                type: 'get',
                data: field,
                dataType: 'json',
                error: function() {
                    layer.msg('导出超时，请稍后再试!');
                },
                success: function(res) {
                    console.log(res.data)
                    table.exportFile(res.data.exportTitle,res.data.exportData, res.data.exportExt, res.data.exportName);
                },
                timeout: 15000
            });
            layer.msg('导出中请耐心等待~');
        });

    })
</script>
</body>
</html>
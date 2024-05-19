<?php /*a:2:{s:56:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\index\stat.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout1.html";i:1676699056;}*/ ?>
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


<link rel="stylesheet" href="/static/plug/layui-admin/dist/layuiadmin/layui/css/layui.css?v=2.4.0.20210429" media="all">
<link rel="stylesheet" href="/static/plug/layui-admin/dist/layuiadmin/style/admin.css?v=2.4.0.20210429" media="all">
<link rel="stylesheet" href="/static/plug/layui-admin/dist/layuiadmin/style/like.css?v=2.4.0.20210429" media="all">
<script src="/static/lib/layui/layui.js"></script>
<script src="/static/admin/js/app.js"></script>

<div class="wrapper" style="margin-bottom: 100px">
    <div class="layui-card" >
        <div class="layui-card-header">
            <span class="header-font" style="font-size: 20px;">运营数据</span>
            <span class="header-time">更新时间：<?php echo htmlentities($info['time']); ?></span>
        </div>
    </div>
    <div class="layui-row layui-col-space15">
        <!--今日数据-->
        <div class="layui-elem-field">
            <div class="layui-card-header" style="font-size: 20px;">
                今日数据
            </div>
            <div class="layui-field-box">
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">营业额</div>
                        <div class="profile-body">
                            ￥<?php echo htmlentities($info['day_order_amount']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">全部订单</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['day_all_order']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">到店订单</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['day_shop_order']); ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="profile">
                        <div class="profile-title">外卖订单</div>
                        <div class="profile-body">
                            <?php echo htmlentities($info['day_takeout_order']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both"></div>
        </div>

        <!--营业额-->
        <div class="layui-col-sm12" style="width: 50%">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-row">
                        营业额
                        <div class="layadmin-dataview">
                            <div id="sale-charts" style="width: 100%;height: 100%">
                                <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--订单-->
        <div class="layui-col-sm12" style="width: 50%">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-row">
                        订单
                        <div class="layadmin-dataview">
                            <div id="order-charts" style="width: 100%;height: 100%;">
                                <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 门店排名 -->
        <div class="layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    门店排名（前10名）
                </div>
                <div class="layui-card-body">
                    <table id="shop-lists" lay-filter="shop-lists" style="width: 100%"></table>
                </div>
            </div>
        </div>

        <!-- 商品排名 -->
        <div class="layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    商品排名（前10名）
                </div>
                <div class="layui-card-body">
                    <table id="goods-lists" lay-filter="goods-lists"></table>
                </div>
            </div>
        </div>

    </div>
</div>
<footer class="info_footer">
    <?php echo htmlentities($company_name); ?>&nbsp;&nbsp;|&nbsp;&nbsp;版本号：<?php echo htmlentities($version); ?>
    <br><br>
</footer>
<style>
    .layui-elem-field {
        margin: 30px 7.5px;
        border-radius: 2px;
        background-color: #fff;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 5%);
    }
    .profile {
        width: 300px;
        margin-right: 30px;
    }
    .profile-title {
        text-align: center;
        font-size: 20px;
        margin-top: 10px;
    }
    .profile-body {
        text-align: center;
        font-size: 20px;
        margin: 20px 0;
    }
    .layui-table-view .layui-table {
        width: 100%;
    }
</style>
<script>
    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/lib/'
    }).extend({
        echarts: 'echarts/echarts',
        echartsTheme: 'echarts/echartsTheme',
    }).use(['echarts','form','element', 'echartsTheme'], function () {
        var $ = layui.$
            ,echarts = layui.echarts;

        let bgColor = "#fff";
        let color = [
            "#0090FF",
            "#36CE9E",
            "#FFC005",
            "#FF515A",
            "#8B5CFF",
            "#00CA69"
        ];
        const hexToRgba = (hex, opacity) => {
            let rgbaColor = "";
            let reg = /^#[\da-f]{6}$/i;
            if (reg.test(hex)) {
                rgbaColor = `rgba(${parseInt("0x" + hex.slice(1, 3))},${parseInt(
                    "0x" + hex.slice(3, 5)
                )},${parseInt("0x" + hex.slice(5, 7))},${opacity})`;
            }
            return rgbaColor;
        }


        like.ajax({
            url: '<?php echo url("index/stat"); ?>',
            type: "get",
            success: function (res) {
                var dates = res.data.dates,
                    echarts_order_amount  = res.data.echarts_order_amount,
                    echarts_order = res.data.echarts_order;

                var sale_option = {
                    backgroundColor: bgColor,
                    color: color,
                    legend: {
                        right: 10,
                        top: 10
                    },
                    tooltip: {
                        trigger: "axis",
                        formatter: function(params) {
                            let html = '';
                            params.forEach(v => {
                                html += `<div style="color: #666;font-size: 14px;line-height: 24px">
                <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:${color[v.componentIndex]};"></span>
                ${v.name}
                <span style="color:${color[v.componentIndex]};font-weight:700;font-size: 18px">${v.value}</span>
                元`;
                            })
                            return html
                        },
                        extraCssText: 'background: #fff; border-radius: 0;box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);color: #333;',
                        axisPointer: {
                            type: 'shadow',
                            shadowStyle: {
                                color: '#ffffff',
                                shadowColor: 'rgba(225,225,225,1)',
                                shadowBlur: 5
                            }
                        }
                    },
                    grid: {
                        top: 100,
                        containLabel: true
                    },
                    xAxis: [{
                        type: "category",
                        boundaryGap: false,
                        axisLabel: {
                            formatter: '{value}',
                            textStyle: {
                                color: "#333"
                            }
                        },
                        axisLine: {
                            lineStyle: {
                                color: "#D9D9D9"
                            }
                        },
                        data: dates
                    }],
                    yAxis: [{
                        type: "value",
                        name: '营业额',
                        axisLabel: {
                            textStyle: {
                                color: "#666"
                            }
                        },
                        nameTextStyle: {
                            color: "#666",
                            fontSize: 12,
                            lineHeight: 40
                        },
                        splitLine: {
                            lineStyle: {
                                type: "dashed",
                                color: "#E9E9E9"
                            }
                        },
                        axisLine: {
                            show: false
                        },
                        axisTick: {
                            show: false
                        }
                    }],
                    series: [{
                        type: "line",
                        smooth: true,
                        symbolSize: 8,
                        zlevel: 3,
                        lineStyle: {
                            normal: {
                                color: color[0],
                                shadowBlur: 3,
                                shadowColor: hexToRgba(color[0], 0.5),
                                shadowOffsetY: 8
                            }
                        },
                        areaStyle: {
                            normal: {
                                color: new echarts.graphic.LinearGradient(
                                    0,
                                    0,
                                    0,
                                    1,
                                    [{
                                        offset: 0,
                                        color: hexToRgba(color[0], 0.3)
                                    },
                                        {
                                            offset: 1,
                                            color: hexToRgba(color[0], 0.1)
                                        }
                                    ],
                                    false
                                ),
                                shadowColor: hexToRgba(color[0], 0.1),
                                shadowBlur: 10
                            }
                        },
                        data: echarts_order_amount
                    }]
                };

                var order_option = {
                    backgroundColor: bgColor,
                    color: color,
                    legend: {
                        right: 10,
                        top: 10
                    },
                    tooltip: {
                        trigger: "axis",
                        formatter: function(params) {
                            let html = '';
                            params.forEach(v => {
                                html += `<div style="color: #666;font-size: 14px;line-height: 24px">
                <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:${color[v.componentIndex]};"></span>
                ${v.name}
                <span style="color:${color[v.componentIndex]};font-weight:700;font-size: 18px">${v.value}</span>
                人`;
                            })
                            return html
                        },
                        extraCssText: 'background: #fff; border-radius: 0;box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);color: #333;',
                        axisPointer: {
                            type: 'shadow',
                            shadowStyle: {
                                color: '#ffffff',
                                shadowColor: 'rgba(225,225,225,1)',
                                shadowBlur: 5
                            }
                        }
                    },
                    grid: {
                        top: 100,
                        containLabel: true
                    },
                    xAxis: [{
                        type: "category",
                        boundaryGap: false,
                        axisLabel: {
                            formatter: '{value}',
                            textStyle: {
                                color: "#333"
                            }
                        },
                        axisLine: {
                            lineStyle: {
                                color: "#D9D9D9"
                            }
                        },
                        data: dates
                    }],
                    yAxis: [{
                        type: "value",
                        name: '进店人数',
                        axisLabel: {
                            textStyle: {
                                color: "#666"
                            }
                        },
                        nameTextStyle: {
                            color: "#666",
                            fontSize: 12,
                            lineHeight: 40
                        },
                        splitLine: {
                            lineStyle: {
                                type: "dashed",
                                color: "#E9E9E9"
                            }
                        },
                        axisLine: {
                            show: false
                        },
                        axisTick: {
                            show: false
                        }
                    }],
                    series: [{
                        type: "line",
                        smooth: true,
                        symbolSize: 8,
                        zlevel: 3,
                        lineStyle: {
                            normal: {
                                color: color[0],
                                shadowBlur: 3,
                                shadowColor: hexToRgba(color[0], 0.5),
                                shadowOffsetY: 8
                            }
                        },
                        areaStyle: {
                            normal: {
                                color: new echarts.graphic.LinearGradient(
                                    0,
                                    0,
                                    0,
                                    1,
                                    [{
                                        offset: 0,
                                        color: hexToRgba(color[0], 0.3)
                                    },
                                        {
                                            offset: 1,
                                            color: hexToRgba(color[0], 0.1)
                                        }
                                    ],
                                    false
                                ),
                                shadowColor: hexToRgba(color[0], 0.1),
                                shadowBlur: 10
                            }
                        },
                        data: echarts_order
                    }]
                };


                var sale_charts= echarts.init(document.getElementById('sale-charts'));
                sale_charts.setOption(sale_option, true);

                var order_charts = echarts.init(document.getElementById('order-charts'));
                order_charts.setOption(order_option, true);

            }
        });




        getList(); // 初始加载

        function getList() {
            like.tableLists('#shop-lists', '<?php echo url("index/shop"); ?>?type=1', [
                {type: 'numbers',title: '排名',width:'10%'}
                ,{field: 'name', title: '门店信息',width:'40%'}
                ,{field: 'order_sum', title: '订单量',width:'25%'}
                ,{field: 'sale_amount', title: '销售额',width:'25%'}
            ]);

            like.tableLists('#goods-lists', '<?php echo url("index/shop"); ?>?type=2', [
                {type: 'numbers',title: '排名',width:'10%'}
                ,{field: 'name', title: '商品信息',width:'40%'}
                ,{field: 'order_sum', title: '订单量',width:'25%'}
                ,{field: 'sale_amount', title: '销售额',width:'25%'}
            ]);
        }
        
    });
</script>

</body>
</html>
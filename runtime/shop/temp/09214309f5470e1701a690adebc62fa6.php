<?php /*a:2:{s:61:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\statistics\visit.html";i:1676699056;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout2.html";i:1676699056;}*/ ?>
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


<div class="layui-col-md12">
    <div class="layui-fluid">

        <div class="layui-form" lay-filter="">
            <div class="layui-card" >
                <div class="layui-card-body">
                   
            <div class="layui-inline">
                <label class="layui-form-label">汇总时间:</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="start_time" class="layui-input" id="start_time"
                               placeholder="" autocomplete="off">
                    </div>
                </div>
                <div class="layui-input-inline" style="margin-right: 5px;width: 20px;">
                    <label class="layui-form-mid">至</label>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="end_time" class="layui-input" id="end_time"
                           placeholder="" autocomplete="off">
                </div>
            </div>
    
            <div class="layui-inline">
                <button class="layui-btn layui-btn-sm layuiadmin-btn-ad <?php echo htmlentities($view_theme_color); ?>" lay-submit
                        lay-filter="menber-search">查询
                </button>
                <button class="layui-btn layui-btn-sm layuiadmin-btn-ad layui-btn-primary " lay-submit
                        lay-filter="menber-clear-search">重置
                </button>
            </div>
            
                </div>
            </div>

            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md3" >
                    <div class="layui-card" >
                        <div class="layui-card-header" >
                            进店人数（人）
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" >
                            <p class="layuiadmin-big-font" id="user_count">0</p>
                        </div>
                    </div>
                </div>

            </div>

            <!--进店人数分析图-->
            <div class="layui-card" style="margin-top:20px">
                <div class="layui-card-body">
                    <div class="layui-form-item">
                        <div class="layui-input-inline layui-card-header" style="">进店人数（人）</div>
                        
                    </div>

                    <div class="layui-form-item">
                        <div id="visitCharts" style="width: 100%;height: 80vh;"></div>
                    </div>
                </div>
            </div>



        </div>

    </div>
</div>


<script>

    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/plug/layui-admin/dist/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index','table','like','echarts','form'], function(){
        var $ = layui.$
            ,form = layui.form
            ,like = layui.like
            ,echarts = layui.echarts
            ,laydate = layui.laydate;

        //日期时间范围
        laydate.render({
            elem: '#start_time'
            , type: 'datetime'
        });

        laydate.render({
            elem: '#end_time'
            , type: 'datetime'
        });

        //监听搜索
        form.on('submit(menber-search)', function (data) {
            graphData();
        });

        //清空查询
        form.on('submit(menber-clear-search)', function () {
            $('#start_time').val('');
            $('#end_time').val('');
            graphData();
        });


        const colorList = ["#9E87FF", '#73DDFF', '#fe9a8b', '#F56948', '#9E87FF'];

        graphData();

        function graphData(){
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            like.ajax({
                url:'<?php echo url("statistics/visit"); ?>',
                data: {'start_time':start_time,'end_time':end_time},
                type: "post",
                success: function (res) {
                    $('#user_count').text(res.data.user_count);
                    // 默认选中7天内时间
                    $('#start_time').val(res.data.start_time);
                    $('#end_time').val(res.data.end_time);
                    start_time = res.data.start_time;
                    end_time = res.data.end_time;


                    var visitNum =  res.data.echarts_count;
                    var newVisit =  res.data.echarts_add;
                    var xData     = res.data.days;
                    var option    = setOption(visitNum, newVisit, xData);
                    let chart     = document.getElementById('visitCharts');
                    let visitChart = echarts.init(chart);
                    visitChart.setOption(option, true);
                    window.addEventListener('resize', function () {
                        visitChart.resize()
                    });
                }
            });
        }
        


        //新增会员数量-图标设置
        function setOption(visitNum, newVisit, xData) {
            option = {
                backgroundColor: '#fff',
                legend: {
                    icon: 'circle',
                    top: '5%',
                    right: '5%',
                    itemWidth: 6,
                    itemGap: 20,
                    textStyle: {
                        color: '#556677'
                    }
                },
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: '#fff',
                    textStyle: {
                        color: '#5c6c7c'
                    },
                    padding: [10, 10],
                    extraCssText: 'box-shadow: 1px 0 2px 0 rgba(163,163,163,0.5)'
                },
                grid: {
                    top: '15%'
                },
                xAxis: [{
                    type: 'category',
                    name: '(日期)',
                    data: xData,
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(107,107,107,0.37)', //x轴颜色
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        interval: 0,
                        textStyle: {
                            color: '#999' //坐标轴字颜色
                        },
                        margin: 15
                    },
                    axisPointer: {
                        label: {
                            padding: [11, 5, 7],
                            backgroundColor: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0,
                                    color: '#fff' // 0% 处的颜色
                                }, {
                                    offset: 0.9,
                                    color: '#fff' // 0% 处的颜色
                                }, {
                                    offset: 0.9,
                                    color: '#33c0cd' // 0% 处的颜色
                                }, {
                                    offset: 1,
                                    color: '#33c0cd' // 100% 处的颜色
                                }],
                                global: false // 缺省为 false
                            }
                        }
                    },
                    boundaryGap: false
                }],
                yAxis: [{
                    type: 'value',
                    name: '(人)',
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: 'rgba(107,107,107,0.37)', //y轴颜色
                        }
                    },
                    axisLabel: {
                        textStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        show: false
                    }
                }],
                series: [{
                    name: '进店人数',
                    type: 'line',
                    data: newVisit,
                    symbolSize: 1,
                    symbol: 'circle',
                    smooth: true,
                    yAxisIndex: 0,
                    showSymbol: false,
                    lineStyle: {
                        width: 5,
                        color: new echarts.graphic.LinearGradient(1, 1, 0, 0, [{
                            offset: 0,
                            color: '#73DD39'
                        },
                            {
                                offset: 1,
                                color: '#73DDFF'
                            }
                        ]),
                        shadowColor: 'rgba(115,221,255, 0.3)',
                        shadowBlur: 10,
                        shadowOffsetY: 20
                    },
                    itemStyle: {
                        normal: {
                            color: colorList[1],
                            borderColor: colorList[1]
                        }
                    }
                }]
            };
            return option;
        }
    
        //会员访问量-图标设置
        function setOptionView(visitNum, newVisit, xData) {
            option = {
                backgroundColor: '#fff',
                legend: {
                    icon: 'circle',
                    top: '5%',
                    right: '5%',
                    itemWidth: 6,
                    itemGap: 20,
                    textStyle: {
                        color: '#556677'
                    }
                },
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: '#fff',
                    textStyle: {
                        color: '#5c6c7c'
                    },
                    padding: [10, 10],
                    extraCssText: 'box-shadow: 1px 0 2px 0 rgba(163,163,163,0.5)'
                },
                grid: {
                    top: '15%'
                },
                xAxis: [{
                    type: 'category',
                    name: '(日期)',
                    data: xData,
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(107,107,107,0.37)', //x轴颜色
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        interval: 0,
                        textStyle: {
                            color: '#999' //坐标轴字颜色
                        },
                        margin: 15
                    },
                    axisPointer: {
                        label: {
                            padding: [11, 5, 7],
                            backgroundColor: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0,
                                    color: '#fff' // 0% 处的颜色
                                }, {
                                    offset: 0.9,
                                    color: '#fff' // 0% 处的颜色
                                }, {
                                    offset: 0.9,
                                    color: '#33c0cd' // 0% 处的颜色
                                }, {
                                    offset: 1,
                                    color: '#33c0cd' // 100% 处的颜色
                                }],
                                global: false // 缺省为 false
                            }
                        }
                    },
                    boundaryGap: false
                }],
                yAxis: [{
                    type: 'value',
                    name: '(人)',
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: 'rgba(107,107,107,0.37)', //y轴颜色
                        }
                    },
                    axisLabel: {
                        textStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        show: false
                    }
                }],
                series: [{
                    name: '会员访问量',
                    type: 'line',
                    data: newVisit,
                    symbolSize: 1,
                    symbol: 'circle',
                    smooth: true,
                    yAxisIndex: 0,
                    showSymbol: false,
                    lineStyle: {
                        width: 5,
                        color: new echarts.graphic.LinearGradient(1, 1, 0, 0, [{
                            offset: 0,
                            color: '#73DD39'
                        },
                            {
                                offset: 1,
                                color: '#73DDFF'
                            }
                        ]),
                        shadowColor: 'rgba(115,221,255, 0.3)',
                        shadowBlur: 10,
                        shadowOffsetY: 20
                    },
                    itemStyle: {
                        normal: {
                            color: colorList[1],
                            borderColor: colorList[1]
                        }
                    }
                }]
            };
            return option;
        }
   
    });

</script>


<script type="text/javascript">
    (function(){
        var tab_tit  = document.getElementById('think_page_trace_tab_tit').getElementsByTagName('span');
        var tab_cont = document.getElementById('think_page_trace_tab_cont').getElementsByTagName('div');
        var open     = document.getElementById('think_page_trace_open');
        var close    = document.getElementById('think_page_trace_close').children[0];
        var trace    = document.getElementById('think_page_trace_tab');
        var cookie   = document.cookie.match(/thinkphp_show_page_trace=(\d\|\d)/);
        var history  = (cookie && typeof cookie[1] != 'undefined' && cookie[1].split('|')) || [0,0];
        open.onclick = function(){
            trace.style.display = 'block';
            this.style.display = 'none';
            close.parentNode.style.display = 'block';
            history[0] = 1;
            document.cookie = 'thinkphp_show_page_trace='+history.join('|')
        }
        close.onclick = function(){
            trace.style.display = 'none';
            this.parentNode.style.display = 'none';
            open.style.display = 'block';
            history[0] = 0;
            document.cookie = 'thinkphp_show_page_trace='+history.join('|')
        }
        for(var i = 0; i < tab_tit.length; i++){
            tab_tit[i].onclick = (function(i){
                return function(){
                    for(var j = 0; j < tab_cont.length; j++){
                        tab_cont[j].style.display = 'none';
                        tab_tit[j].style.color = '#999';
                    }
                    tab_cont[i].style.display = 'block';
                    tab_tit[i].style.color = '#000';
                    history[1] = i;
                    document.cookie = 'thinkphp_show_page_trace='+history.join('|')
                }
            })(i)
        }
        parseInt(history[0]) && open.click();
        tab_tit[history[1]].click();
    })();
</script>
</body>
</html>
</body>
</html>
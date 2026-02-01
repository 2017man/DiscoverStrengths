$(function () {
    map();

    function map() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('map_2'));

        var chinaGeoCoordMap = {
            黑龙江: [127.9688, 45.368],
            内蒙古: [110.3467, 41.4899],
            吉林: [125.8154, 44.2584],
            北京市: [116.4551, 40.2539],
            辽宁: [123.1238, 42.1216],
            河北: [114.4995, 38.1006],
            天津: [117.4219, 39.4189],
            山西: [112.3352, 37.9413],
            陕西: [109.1162, 34.2004],
            甘肃: [103.5901, 36.3043],
            宁夏: [106.3586, 38.1775],
            青海: [101.4038, 36.8207],
            新疆: [87.9236, 43.5883],
            西藏: [91.11, 29.97],
            四川: [103.9526, 30.7617],
            重庆: [108.384366, 30.439702],
            山东: [117.1582, 36.8701],
            河南: [113.4668, 34.6234],
            江苏: [118.8062, 31.9208],
            安徽: [117.29, 32.0581],
            湖北: [114.3896, 30.6628],
            浙江: [119.5313, 29.8773],
            福建: [119.4543, 25.9222],
            江西: [116.0046, 28.6633],
            湖南: [113.0823, 28.2568],
            贵州: [106.6992, 26.7682],
            云南: [102.9199, 25.4663],
            广东: [113.12244, 23.009505],
            广西: [108.479, 23.1152],
            海南: [110.3893, 19.8516],
            上海: [121.4648, 31.2891],
            '平凉市': [106.684223, 35.54173],
        };

        var chinaDatas = [
            {
                "value": "50000",
                "name": "安徽",
            },
            {
                "value": "49500",
                "name": "宁夏",
            },
            {
                "value": "49000",
                "name": "陕西",
            },
            {
                "value": "48500",
                "name": "浙江",
            },
            {
                "value": "48000",
                "name": "福建",
            },
            {
                "value": "47500",
                "name": "河北",
            },
            {
                "value": "47000",
                "name": "湖北",
            },
            {
                "value": "46500",
                "name": "山西",
            },
            {
                "value": "46000",
                "name": "四川",
            },
            {
                "value": "45500",
                "name": "山东",
            },
            {
                "value": "45000",
                "name": "湖南",
            },
            {
                "value": "44500",
                "name": "江苏",
            },
            {
                "value": "44000",
                "name": "江西",
            },
            {
                "value": "43500",
                "name": "河南",
            }
        ];

        var convertData = function (data) {
            var res = [];
            for (var i = 0; i < data.length; i++) {
                var dataItem = data[i];
                var fromCoord = chinaGeoCoordMap[dataItem.name];
                var toCoord = [106.684223, 35.54173];
                if (fromCoord && toCoord) {
                    res.push([{
                        coord: fromCoord,
                        value: dataItem.value,
                    },
                        {
                            coord: toCoord,
                        },
                    ]);
                }
            }
            return res;
        };

        item = ['平凉市', chinaDatas];
        var series = [
            {
                type: 'lines',
                zlevel: 1,
                effect: {
                    show: true,
                    period: 4, //箭头指向速度，值越小速度越快
                    trailLength: 0.02, //特效尾迹长度[0,1]值越大，尾迹越长重
                    symbol: 'arrow', //箭头图标
                    symbolSize: 5, //图标大小
                },
                lineStyle: {
                    normal: {
                        width: 1, //尾迹线条宽度
                        opacity: 1, //尾迹线条透明度
                        curveness: 0.3, //尾迹线条曲直度
                    },
                },
                data: convertData(item[1]),
            },
            {
                type: 'effectScatter',
                coordinateSystem: 'geo',
                zlevel: 2,
                rippleEffect: {
                    //涟漪特效
                    period: 4, //动画时间，值越小速度越快
                    brushType: 'stroke', //波纹绘制方式 stroke, fill
                    scale: 4, //波纹圆环最大限制，值越大波纹越大
                },
                label: {
                    normal: {
                        show: true,
                        position: 'right', //显示位置
                        offset: [5, 0], //偏移设置
                        formatter: function (params) {
                            //圆环显示文字
                            return params.data.name;
                        },
                        fontSize: 10,
                    },
                    emphasis: {
                        show: true,
                    },
                },
                symbol: 'circle',
                symbolSize: function (val) {
                    return val[2] / 4000; //圆环大小
                },
                itemStyle: {
                    normal: {
                        show: false,
                        color: '#FFA54F',
                    },
                },
                data: item[1].map(function (dataItem) {
                    return {
                        name: dataItem.name,
                        value: chinaGeoCoordMap[dataItem.name].concat([dataItem.value]),
                    };
                }),
            },
            //被攻击点
            {
                type: 'effectScatter',
                coordinateSystem: 'geo',
                zlevel: 2,
                rippleEffect: {
                    period: 4,
                    brushType: 'stroke',
                    scale: 15,
                },
                label: {
                    normal: {
                        show: true,
                        position: 'left',
                        color: '#0f0',
                        formatter: '{b}',
                        textStyle: {
                            color: '#ffde00',
                            fontSize: 20,
                        },
                    },
                    emphasis: {
                        show: true,
                        color: '#FFA54F',
                    },
                },
                symbol: 'circle',
                symbolSize: 25,
                data: [{
                    name: item[0],
                    value: chinaGeoCoordMap[item[0]].concat([1]),
                },],
            }
        ];

        option = {
            title: [
                {
                    text: '投资地区排名（国内)',
                    x: '50%',
                    y: '10%',
                    textAlign: 'center',
                    textStyle: {
                        fontSize: 18,
                        color: '#fff'
                    }
                },
            ],
            tooltip: {
                trigger: 'item',
                backgroundColor: 'rgba(166, 200, 76, 0.82)',
                borderColor: '#FFFFCC',
                showDelay: 0,
                hideDelay: 0,
                enterable: true,
                transitionDuration: 0,
                extraCssText: 'z-index:100',
                formatter: function (params, ticket, callback) {
                    //根据业务自己拓展要显示的内容
                    var res = '';
                    var name = params.name;
                    var value = params.value[params.seriesIndex + 1];
                    res = "<span style='color:#fff;'>" + name + '</span><br/>数据：' + value;
                    return res;
                },
            },
            // 	backgroundColor: '#013954',
            visualMap: {
                //图例值控制
                min: 43000,
                max: 50000,
                calculable: true,
                show: false,
                color: ['#f44336', '#fc9700', '#ffde00', '#ffde00', '#00eaff'],
                textStyle: {
                    color: '#fff',
                },
            },
            geo: {
                map: 'china',
                zoom: 1.5,
                label: {
                    emphasis: {
                        show: false,
                    },
                },
                roam: true, //是否允许缩放
                itemStyle: {
                    normal: {
                        borderColor: '#516a89', //省市边界线00fcff 516a89
                        borderWidth: 1, //区域边框宽度
                        areaColor: '#092766', //区域颜色#4c60ff,#031525,#092766
                    },
                    emphasis: {
                        color: 'rgba(37, 43, 61, .5)', //悬浮背景
                    },
                },
            },
            series: series,
        };

        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
    }


    [
        {
            value: '79',
            name: '安徽省'
        },
        {
            value: '71',
            name: '宁夏'
        },
        {
            value: '70',
            name: '陕西省'
        },
        {
            value: '69',
            name: '浙江省'
        },
        {
            value: '66',
            name: '福建省'
        },
        {
            value: '60',
            name: '河北省'
        },
        {
            value: '55',
            name: '湖北省'
        },
        {
            value: '52',
            name: '山西省'
        },
        {
            value: '49',
            name: '四川省'
        },
        {
            value: '47',
            name: '山东省'
        },
        {
            value: '43',
            name: '湖南省'
        },
        {
            value: '39',
            name: '江苏省'
        },
        {
            value: '35',
            name: '江西省'
        },
        {
            value: '33',
            name: '河南省'
        },
        {
            value: '30',
            name: '韩国'
        },
        {
            value: '28',
            name: '开曼群岛'
        },
        {
            value: '25',
            name: '马来西亚'
        },
        {
            value: '21',
            name: '英属维尔京群岛'
        },
        {
            value: '19',
            name: '美国'
        },
        {
            value: '18',
            name: '英国'
        },
        {
            value: '17',
            name: '泰国'
        },
        {
            value: '16',
            name: '德国'
        },
        {
            value: '14',
            name: '新加坡'
        },
        {
            value: '12',
            name: '日本'
        },
        {
            value: '11',
            name: '法国'
        }
    ];

    function scater() {
        var chartData = [
            {
                value: '79',
                name: '安徽省'
            },
            {
                value: '71',
                name: '宁夏'
            },
            {
                value: '70',
                name: '陕西省'
            },
            {
                value: '69',
                name: '浙江省'
            },
            {
                value: '66',
                name: '福建省'
            },
            {
                value: '60',
                name: '河北省'
            },
            {
                value: '55',
                name: '湖北省'
            },
            {
                value: '52',
                name: '山西省'
            },
            {
                value: '49',
                name: '四川省'
            },
            {
                value: '47',
                name: '山东省'
            },
            {
                value: '43',
                name: '湖南省'
            },
            {
                value: '39',
                name: '江苏省'
            },
            {
                value: '35',
                name: '江西省'
            },
            {
                value: '33',
                name: '河南省'
            },
            {
                value: '30',
                name: '韩国'
            },
            {
                value: '28',
                name: '开曼群岛'
            },
            {
                value: '25',
                name: '马来西亚'
            },
            {
                value: '21',
                name: '英属维尔京群岛'
            },
            {
                value: '19',
                name: '美国'
            },
            {
                value: '18',
                name: '英国'
            },
            {
                value: '17',
                name: '泰国'
            },
            {
                value: '16',
                name: '德国'
            },
            {
                value: '14',
                name: '新加坡'
            },
            {
                value: '12',
                name: '日本'
            },
            {
                value: '11',
                name: '法国'
            }
        ];

        option = {
            backgroundColor: '#000f4e', //背景色
            tooltip: {
                show: true,
                trigger: 'axis', //axis , item
                backgroundColor: 'rgba(0,15,78,0.6)',
                borderColor: '#00afff',
                borderWidth: 1,
                borderRadius: 0,
                textStyle: {
                    color: '#fff',
                    fontSize: 13,
                    align: 'left'
                },
                axisPointer: {
                    type: 'line', //'line' | 'cross' | 'shadow' | 'none
                    lineStyle: {
                        width: 1,
                        type: 'dotted',
                        color: 'rgba(46,149,230,.9)'
                    }
                }
            },
            legend: {
                show: false,
                orient: 'horizontal', //'vertical'
                data: [],
                icon: 'circle',
                selectedMode: true,
                itemWidth: 10,
                itemHeight: 10,
                itemGap: 20,
                textStyle: {
                    fontSize: 13,
                    color: '#9bc8ff'
                },
                x: 'center',
                y: '25'
            },
            xAxis: {
                type: 'category',
                axisLabel: {
                    show: true,
                    interval: 0, //类目间隔 设置为 1，表示『隔一个标签显示一个标签』
                    rotate: 60,
                    textStyle: {
                        color: '#fff',
                        fontSize: 10
                    },
                    formatter: '{value}'
                },
                axisLine: {
                    lineStyle: {
                        color: 'rgba(255,255,255,.6)'
                    }
                },
                axisTick: {
                    show: false //坐标轴小标记
                },
                data: (function (data) {
                    var arr = [];
                    data.forEach(function (items) {
                        arr.push(items.name); //name
                    });
                    return arr;
                })(chartData) //载入横坐标数据
            },
            yAxis: {
                type: 'value',
                name: '（亿元）',
                nameTextStyle: {
                    color: '#93d3fc',
                    fontSize: 12,
                    align: 'right'
                },
                axisLabel: {
                    show: true,
                    textStyle: {
                        color: '#9bc8ff',
                        fontSize: 13
                    },
                    interval: 0, //类目间隔 设置为 1，表示『隔一个标签显示一个标签』
                    margin: 10
                    //formatter: '{value}'
                },
                // splitNumber: 5, //y轴刻度设置(值越大刻度越小)
                axisLine: {
                    //y轴线
                    show: true,
                    lineStyle: {
                        color: 'rgba(255,255,255,.6)'
                    }
                },
                splitLine: {
                    show: true,
                    lineStyle: {
                        color: 'rgba(15,45,134,.6)', //横向网格线颜色
                        width: 1
                    }
                },
                axisTick: {
                    show: false //坐标轴小标记
                }
            },
            series: [
                {
                    name: '投资金额',
                    type: 'scatter',
                    stack: '总量',
                    label: {
                        normal: {
                            show: false,
                            position: 'top',
                            textStyle: {
                                color: '#9bc8ff',
                                fontSize: 12
                            },
                            formatter: '{c}亿元' //图形上显示数字
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: '#00FFD2' //颜色
                        }
                    },

                    markLine: {
                        silent: false, // 是否不响应鼠标事件
                        animation: false, // 关闭动画显示
                        symbol: ['none', 'arrow'], // 如果两个子项都不要起始箭头可综合配置
                        //label: { show: true, position: "insideEndTop", formatter: "市均", color: "#00FFE4" },
                        data: [
                            {
                                xAxis: 6,
                                label: {
                                    formatter: '国内中线'
                                },
                                symbol: 'none'
                            },
                            {
                                xAxis: 19,
                                label: {
                                    formatter: '境外中线'
                                },
                                symbol: 'none'
                            },
                            {
                                type: 'average',
                                name: 'b',
                                label: {
                                    formatter: '平均'
                                },
                                lineStyle: {
                                    type: 'dashed',
                                    color: '#f00'
                                },
                                symbol: 'none'
                            }
                        ]
                    },
                    symbol: 'circle', //circle, rect, roundRect, triangle,  pin, diamond, arrow
                    symbolPosition: 'end',
                    // symbolSize: 30,
                    // symbolOffset: [0, '-120%'],
                    data: (function (data) {
                        var arr = [];
                        data.forEach(function (items) {
                            var itemName = items.name,
                                itemValue = items.value,
                                itemStyle = itemValue; //console.log(itemStyle)
                            arr.push({
                                name: itemName,
                                value: itemValue,
                                symbolSize: itemStyle
                            });
                        });
                        return arr;
                    })(chartData) //载入数据并设置图形尺寸
                }
            ]
        };

        var app = {
            curIndex: -1
        };
        setInterval(() => {
            var dataLen = chartData.length;

            // 取消之前高亮的图形
            myChart.dispatchAction({
                type: 'downplay',
                seriesIndex: 0,
                dataIndex: app.curIndex
            });

            app.curIndex = (app.curIndex + 1) % dataLen;

            // 高亮当前图形
            myChart.dispatchAction({
                type: 'highlight',
                seriesIndex: 0,
                dataIndex: app.curIndex
            });

            // 显示 tooltip
            myChart.dispatchAction({
                type: 'showTip',
                seriesIndex: 0,
                dataIndex: app.curIndex
            });
        }, 3000);

    }


    /**
     * 定时轮播
     * @type {number}
     */
    var count = 0;
    var timer = null;
    var chart = echarts.init(document.getElementById('map_1'));

    var dataLength = option.series[0].data.length;
    timer && clearInterval(timer);
    timer = setInterval(() => {
        chart.dispatchAction({
            type: 'downplay',
            seriesIndex: 0,
        });
        chart.dispatchAction({
            type: 'highlight',
            seriesIndex: 0,
            dataIndex: count % dataLength,
        });
        chart.dispatchAction({
            type: 'showTip',
            seriesIndex: 0,
            dataIndex: count % dataLength,
        });
        count++;
    }, 3000);
    chart.on('mouseover', function (params) {
        clearInterval(timer);
        chart.dispatchAction({
            type: 'downplay',
            seriesIndex: 0,
        });
        chart.dispatchAction({
            type: 'highlight',
            seriesIndex: 0,
            dataIndex: params.dataIndex,
        });
        chart.dispatchAction({
            type: 'showTip',
            seriesIndex: 0,
            dataIndex: params.dataIndex,
        });
    });
    chart.on('mouseout', function (params) {
        timer && clearInterval(timer);
        timer = setInterval(function () {
            chart.dispatchAction({
                type: 'downplay',
                seriesIndex: 0,
            });
            chart.dispatchAction({
                type: 'highlight',
                seriesIndex: 0,
                dataIndex: count % dataLength,
            });
            chart.dispatchAction({
                type: 'showTip',
                seriesIndex: 0,
                dataIndex: count % dataLength,
            });
            count++;
        }, 3000);
    });

})


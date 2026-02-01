<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datav/echarts.min.js') }}"></script>

<div id="main" style="height: 600px;width:100%"></div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据
    var option = {
        backgroundColor: '#03111D',
        tooltip: {
            trigger: 'axis',
        },
        grid: [
            {
                show: false,
                left: '5%',
                top: '10%',
                bottom: '55%',
                width: '90%',
            },
            {
                show: false,
                left: '5%',
                top: '50%',
                bottom: '55%',
                width: '90%',
            },
            {
                show: false,
                left: '5%',
                top: '55%',
                bottom: '10%',
                width: '90%',
            },
            {
                show: false,
                right: '5%',
                top: '50%',
                bottom: '90%',
                width: '90%',
            },
        ],
        legend: {
            data: ['集团单位', '已签章', '监督员', '信息数'],
            position: 'center',
            textStyle: {
                color: "#666666"
            },
            itemWidth: 15,
            itemHeight: 10,
            itemGap: 25
        },
        xAxis: [
            {
                gridIndex: 0,
                show: false,
            },
            {
                type: 'category',
                gridIndex: 1,

                // data: ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2020'],
                axisLabel: {
                    color: '#eff3f9',
                    align: 'center',
                    verticalAlign: 'middle',
                    interval: 0,
                },
                axisLine: {
                    show: false,
                    lineStyle: {
                        color: 'rgba(65, 97, 128, 0.5)',
                    },
                },
                axisTick: {
                    alignWithLabel: true
                }
            },
            {
                gridIndex: 2,
                show: false,
            },
            {
                gridIndex: 3,
                show: false,
            },
        ],
        yAxis: [
            {
                type: 'value',
                name: "集团单位/个",
                nameTextStyle: {
                    color: "#cdd5e2"
                },
                axisTick: {
                    show: false,
                },
                splitLine: {
                    show: false,
                },
                axisLabel: {
                    color: '#eff3f9',
                },
            },
            {
                gridIndex: 1,
                show: false,
            },
            {
                gridIndex: 2,
                type: 'value',
                name: "信息数/条",
                nameTextStyle: {
                    color: "#cdd5e2"
                },
                inverse: true,
                axisTick: {
                    show: false,
                },

                splitLine: {
                    show: false,
                },
                axisLabel: {
                    show: true,
                    // formatter: "{value} 个", //右侧Y轴文字显示
                    textStyle: {
                        color: "#cdd5e2"
                    }
                }
            },
            {
                gridIndex: 3,
                type: "value",
                name: "监督员/位",
                nameTextStyle: {
                    color: "#cdd5e2"
                },
                position: "right",
                axisLine: {
                    lineStyle: {
                        color: 'rgba(65, 97, 128, 0.5)'
                    }
                },
                splitLine: {
                    show: false,
                },
                axisLabel: {
                    show: true,
                    // formatter: "{value}位", //右侧Y轴文字显示
                    textStyle: {
                        color: "#cdd5e2"
                    }
                }
            }
        ],
        series: [
            {
                name: '集团单位',
                type: 'bar',
                barWidth: '12px',
                xAxisIndex: 0,
                yAxisIndex: 0,
                itemStyle: {
                    normal: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: '#12BDDF'
                        }, {
                            offset: 1,
                            color: 'rgba(24, 253, 255, 0)'
                        }]),
                    },
                },
                label: {
                    show: true,
                    position: "top",
                    textStyle: {
                        //数值样式
                        color: "#12BDDF",
                        fontSize: 16,
                    },

                    formatter: function (p) {
                        return p.value > 0 ? (p.value) + '个' : '0个';
                    }
                },
                data: [400, 400, 300, 300, 300, 400, 400, 400, 300]
            },
            // {
            //     name: '已签章',
            //     type: 'bar',
            //     barWidth: '12px',
            //     xAxisIndex: 0,
            //     yAxisIndex: 0,
            //     itemStyle: {
            //         normal: {
            //             color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
            //                 offset: 0,
            //                 color: '#DE9600'
            //             }, {
            //                 offset: 1,
            //                 color: 'rgba(255, 183, 24,0)'
            //             }]),
            //         }
            //     },
            //     data: [380, 300, 260, 240, 268, 275, 168, 333, 22]
            // },
            {
                name: '信息数',
                type: 'bar',
                barWidth: '12px',
                xAxisIndex: 2,
                yAxisIndex: 2,
                itemStyle: {
                    normal: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 1,
                            color: '#DE9600'
                        }, {
                            offset: 0,
                            color: 'rgba(255, 183, 24,0)'
                        }]),
                    }
                },
                label: {
                    show: true,
                    position: "bottom",
                    textStyle: {
                        //数值样式
                        color: "#12BDDF",
                        fontSize: 16,
                    },
                    formatter: function (p) {
                        return p.value > 0 ? (p.value) + '条' : '0条';
                    }
                },
                data: [400, 400, 300, 300, 300, 400, 400, 400, 300]
            },
            {
                name: "监督员",
                type: "line",
                xAxisIndex: 3,
                yAxisIndex: 3, //使用的 y 轴的 index，在单个图表实例中存在多个 y轴的时候有用
                smooth: true, //平滑曲线显示

                symbol: "circle", //标记的图形为实心圆
                symbolSize: 0, //标记的大小
                itemStyle: {
                    normal: {
                        color: '#0AF37A',
                        borderColor: '#0AF37A',  //圆点透明 边框
                        borderWidth: 0,
                        lineStyle: {
                            color: "#0AF37A"
                        },
                        areaStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: '#46C118'
                            }, {
                                offset: 1,
                                color: 'rgba(12, 162, 13, 0)'
                            }])
                        },
                    },
                },
                label: {
                    show: true,
                    position: "top",
                    textStyle: {
                        //数值样式
                        color: "#0AF37A",
                        fontSize: 16,
                    },
                    formatter: function (p) {
                        return p.value > 0 ? (p.value) + '位' : '0位';
                    }
                },
                // data: [50, 80, 60, 39.6, 82.9, 48.8, 53, 50]
            }
        ]
    };

    url = '/api/v1/datav/count';
    $.ajax({
        url: url, //请求路径
        type: 'POST',
        success: function (data) {
            option.xAxis[0].data = option.xAxis[1].data = option.xAxis[2].data = option.xAxis[3].data = data.xData;

            option.series[0].data = data.depData;
            option.series[1].data = data.infoData;
            option.series[2].data = data.relationData;
            // option.series[3].data = data.signRatio;
            console.log(data);
            myChart.setOption(option);
        }, //请求成功时候的回调函数
        error: function () {
            // alert("fail")
            return;
        } //请求失败时候的回调函数
    });
    // 使用刚指定的配置项和数据显示图表。
    window.addEventListener('resize', function () {
        myChart.resize();
    });
</script>


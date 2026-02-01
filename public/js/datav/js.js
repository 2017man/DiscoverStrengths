﻿$(function () {
    echartsA1_2();
    echarts_4();
    echarts_5();
    echarts_A1_3();
    echartsB1_1();

    function echartsA1_2() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartA1_2'));

        option = {
            grid: {
                left: '0%',
                top: '30px',
                right: '10%',
                bottom: '4%',
                containLabel: true
            },
            xAxis: {
                name: '年',
                type: 'category',
                boundaryGap: false,
                data: ['2017', '2018', '2019', '2020', '2021'],
                axisLine: {
                    lineStyle: {
                        color: "rgba(255,255,255,.6)",
                    },
                },
                axisTick: {
                    show: true,
                },
                axisLabel: {
                    interval: 0,
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: "rgba(255,255,255,.6)",
                        fontSize: '12',
                    },
                },
            },
            yAxis: {
                type: 'value',
                name: '亿元',
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: "rgba(255,255,255,.6)",
                    },
                },
                axisTick: {
                    show: false,
                },
                axisLabel: {
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: "rgba(255,255,255,.6)",
                        fontSize: '12',
                    },
                },
            },
            series: [
                {
                    type: 'line',
                    label: {show: true},
                    areaStyle: {},
                    data: [820, 932, 901, 934, 1290],
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: {
                        normal: {
                            color: '#0092f6',
                            lineStyle: {
                                color: '#0092f6',
                                width: 1
                            },
                            areaStyle: {
                                //color: '#94C9EC'
                                color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                    {
                                        offset: 0,
                                        color: 'rgba(7,44,90,0.3)'
                                    },
                                    {
                                        offset: 1,
                                        color: 'rgba(0,146,246,0.9)'
                                    }
                                ])
                            }
                        }
                    }
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
    }

    function echarts_4() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echart4'));

        option = {
            grid: {
                left: '0%',
                top: '30px',
                right: '10%',
                bottom: '4%',
                containLabel: true
            },
            xAxis: {
                name: '年',
                type: 'category',
                boundaryGap: false,
                data: ['2017', '2018', '2019', '2020', '2021'],
                axisLine: {
                    lineStyle: {
                        color: "rgba(255,255,255,.6)",
                    },
                },
                axisTick: {
                    show: false,
                },
                axisLabel: {
                    interval: 0,
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: "rgba(255,255,255,.6)",
                        fontSize: '12',
                    },
                },
            },
            yAxis: {
                type: 'value',
                name: '亿元',
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: "rgba(255,255,255,.6)",
                    },
                },
                axisTick: {
                    show: false,
                },
                axisLabel: {
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: "rgba(255,255,255,.6)",
                        fontSize: '12',
                    },
                },
            },
            series: [
                {
                    type: 'line',
                    label: {show: true},
                    areaStyle: {},
                    data: [820, 932, 901, 934, 1290],
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: {
                        normal: {
                            color: '#00d4c7',
                            lineStyle: {
                                color: '#00d4c7',
                                width: 1
                            },
                            areaStyle: {
                                //color: '#94C9EC'
                                color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                    {
                                        offset: 0,
                                        color: 'rgba(7,44,90,0.3)'
                                    },
                                    {
                                        offset: 1,
                                        color: 'rgba(0,212,199,0.9)'
                                    }
                                ])
                            }
                        }
                    }
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
    }

    function echarts_5() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echart5'));

        var yData7 = [
            '崆峒区',
            '华亭市',
            '泾川县',
            '灵台县',
            '崇信县',
            '庄浪县',
            '静宁县'
        ];
        var data7 = ['2000', '1600', '1000', '600', '400', '200', '200'];
        data7.sort((a, b) => b - a);
        var max7 = Math.max.apply(null, data7);
        var getMax7 = [];
        for (let i = 0; i < yData7.length; i++) {
            getMax7.push(max7);
        }
        option = {
            // backgroundColor: '#0b1751',
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'none'
                },
                formatter: function (params) {
                    return params[0].name + ' : ' + params[0].value + ' 亿元';
                }
            },
            xAxis: {
                show: false,
                type: 'value'
            },
            grid: {
                left: '0%',
                top: '10px',
                right: '0%',
                bottom: '1px',
                containLabel: true
            },
            yAxis: [
                {
                    type: 'category',
                    inverse: true,
                    offset: 80,
                    axisLabel: {
                        show: true,
                        align: 'left',
                        textStyle: {
                            color: 'rgba(255,255,255,.6)',
                            fontSize: '12'
                        },
                        formatter: function (value, index) {
                            var num = '';
                            var str = '';
                            num = index + 1;
                            if (index === 0) {
                                str = '{no1|' + '} {num1|' + num + '} {title1| ' + value + '}';
                            } else if (index === 1) {
                                str = '{no2|' + '} {num2|' + num + '} {title2| ' + value + '}';
                            } else if (index === 2) {
                                str = '{no3|' + '} {num3|' + num + '} {title3| ' + value + '}';
                            } else {
                                str = ' {num|' + num + '} {title| ' + value + '}';
                            }
                            return str;
                        },
                        rich: {
                            num: {
                                color: '#387ec1',
                                backgroundColor: '#112b67',
                                width: 10,
                                height: 10,
                                fontSize: 12,
                                padding: [6, 5, 3, 5],
                                align: 'center',
                                shadowColor: '#3374ba',
                                borderColor: '#3374ba',
                                borderWidth: 1
                            },
                            num1: {
                                color: '#51aff8',
                                backgroundColor: '#112b67',
                                width: 10,
                                height: 10,
                                fontSize: 14,
                                padding: [7, 5, 3, 5],
                                align: 'center',
                                shadowColor: '#4db2ff',
                                borderColor: '#4db2ff',
                                borderWidth: 1
                            },
                            num2: {
                                color: '#51aff8',
                                backgroundColor: '#112b67',
                                width: 10,
                                height: 10,
                                fontSize: 14,
                                padding: [7, 5, 3, 5],
                                align: 'center',
                                shadowColor: '#4db2ff',
                                borderColor: '#4db2ff',
                                borderWidth: 1
                            },
                            num3: {
                                color: '#51aff8',
                                backgroundColor: '#112b67',
                                width: 10,
                                height: 10,
                                fontSize: 14,
                                padding: [7, 5, 3, 5],
                                align: 'center',
                                shadowColor: '#4db2ff',
                                borderColor: '#4db2ff',
                                borderWidth: 1
                            }
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        show: false
                    },
                    data: yData7
                },
                {
                    type: 'category',
                    inverse: true,
                    offset: 0,
                    axisTick: 'none',
                    axisLine: 'none',
                    show: true,
                    axisLabel: {
                        textStyle: {
                            color: 'rgba(255,255,255,.6)',
                            fontSize: '12'
                        },

                        formatter: '{value} 亿元'
                    },
                    data: data7
                }
            ],
            series: [
                {
                    name: '值',
                    type: 'bar',
                    zlevel: 1,
                    itemStyle: {
                        normal: {
                            // barBorderRadius: 30,
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 1,
                                y2: 0,
                                colorStops: [
                                    {
                                        offset: 0,
                                        color: 'transparent' //  0%  处的颜色
                                    },
                                    {
                                        offset: 1,
                                        color: '#00d0ff' //  100%  处的颜色
                                    }
                                ],
                                global: false //  缺省为  false
                            }
                        }
                    },
                    barWidth: 20,
                    data: data7
                },
                {
                    name: '背景',
                    type: 'bar',
                    barWidth: 20,
                    barGap: '-100%',
                    data: getMax7,
                    itemStyle: {
                        color: '#152448'
                        // barBorderRadius: 30,
                    }
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
    }


    function echarts_A1_3() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartA1_3'));

        option = {
            grid: {
                left: '1%',
                top: '30px',
                right: '1%',
                bottom: '10%',
                containLabel: true
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    crossStyle: {
                        color: '#999'
                    }
                }
            },
            legend: {
                data: ['GDP', 'GDP增速', '人均GDP'],
                textStyle: {color: 'rgba(255,255,255,.6)'},
            },
            xAxis: {
                type: 'category',
                data: ['2017', '2018', '2019', '2020', '2021'],

                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: 'rgba(255,255,255,.6)'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    // interval: 0,
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: 'rgba(255,255,255,.6)',
                        fontSize: '12'
                    }
                }
            },
            yAxis: [
                {
                    type: 'value',
                    name: '金额（亿元）',
                    axisLabel: {
                        padding: [3, 0, 0, 0],
                        formatter: '{value}',
                        color: 'rgba(95, 187, 235, 1)',
                        textStyle: {
                            color: 'rgba(255,255,255,.6)',
                            fontSize: '12'
                        }
                    },
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: 'rgba(255,255,255,.6)'
                        }
                    },
                    axisTick: {
                        show: false
                    }
                },
                {
                    type: 'value',
                    name: '人均GDP（元）',
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(255,255,255,.6)'
                        }
                    },
                    splitLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        formatter: '{value}',
                        interval: 0,
                        // rotate:50,
                        show: true,
                        textStyle: {
                            color: 'rgba(255,255,255,.6)',
                            fontSize: '12'
                        }
                    }
                }
            ],
            series: [
                {
                    name: 'GDP',
                    type: 'bar',
                    barWidth: '10', //柱条宽度
                    data: [220, 182, 191, 234, 290],
                    label: {
                        show: true
                    },

                    itemStyle: {
                        //图形的样式
                        color: {
                            //渐变色配置
                            type: 'linear',
                            x: 0,
                            y: 0,
                            x2: 0,
                            y2: 1,
                            colorStops: [
                                {
                                    offset: 0,
                                    color: 'rgba(120, 235, 187, 1)' // 0% 处的颜色
                                },
                                {
                                    offset: 1,
                                    color: 'rgba(172, 244, 220, 0)' // 100% 处的颜色
                                }
                            ],
                            global: false // 缺省为 false
                        }
                    }
                },
                {
                    name: 'GDP增速',
                    type: 'bar',
                    barWidth: '10', //柱条宽度
                    data: [320, 282, 91, 134, 190],
                    label: {
                        show: true
                    },

                    itemStyle: {
                        //图形的样式
                        color: {
                            //渐变色配置
                            type: 'linear',
                            x: 0,
                            y: 0,
                            x2: 0,
                            y2: 1,
                            colorStops: [
                                {
                                    offset: 0,
                                    color: 'rgba(0,146,246,0.9)' // 0% 处的颜色
                                },
                                {
                                    offset: 1,
                                    color: 'rgba(7,44,90,0.3)' // 100% 处的颜色
                                }
                            ],
                            global: false // 缺省为 false
                        }
                    }

                },
                {
                    name: '人均GDP',
                    type: 'line',
                    yAxisIndex: 1,
                    smooth: true,
                    symbol: 'circle', //标记的图形为实心圆
                    symbolSize: 8, //标记的大小
                    label: {
                        show: true
                    },
                    data: [1.0, 1.2, 2.3, 3.5, 4.3],
                    itemStyle: {
                        //折线拐点标志的样式
                        color: '#ffde32'
                    }
                }
            ]
        };


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
    }


    function echartsB1_1() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartB1_1'));

        option = {
            title: [
                {
                    text: '产业结构',
                    x: '30%',
                    y: '30%',
                    textAlign: 'center',
                    textStyle: {
                        fontSize: 12,
                        color: '#fff'
                    }
                },
                {
                    text: '产业变化趋势',
                    x: '90%',
                    y: '10%',
                    textAlign: 'center',
                    textStyle: {
                        fontSize: 12,
                        color: '#fff'
                    }
                }
            ],
            grid: {
                left: '0%',
                top: '10px',
                right: '5%',
                bottom: '1px',
                containLabel: true
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['第一产业', '第二产业', '第三产业'],
                textStyle: {color: 'rgba(255,255,255,.6)'},
            },

            xAxis: {
                type: 'category',
                name: '月份',
                boundaryGap: false,
                data: ['2017', '2018', '2019', '2020', '2022'],
                axisLine: {
                    lineStyle: {
                        color: 'rgba(255,255,255,.6)'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    interval: 0,
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: 'rgba(255,255,255,.6)',
                        fontSize: '12'
                    }
                }
            },
            yAxis: {
                type: 'value',
                name: '万元',
                position: 'left',
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: 'rgba(255,255,255,.6)'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    interval: 0,
                    // rotate:50,
                    show: true,
                    textStyle: {
                        color: 'rgba(255,255,255,.6)',
                        fontSize: '12'
                    }
                }
            },
            series: [
                {
                    name: '第一产业',
                    label: {show: true},
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    data: [12, 13, 10, 13, 90],
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: {
                        normal: {
                            color: '#0092f6',
                            lineStyle: {
                                color: '#0092f6',
                                width: 1
                            },
                            areaStyle: {
                                //color: '#94C9EC'
                                color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                    {
                                        offset: 0,
                                        color: 'rgba(7,44,90,0.3)'
                                    },
                                    {
                                        offset: 1,
                                        color: 'rgba(0,146,246,0.9)'
                                    }
                                ])
                            }
                        }
                    }
                },
                {
                    name: '第二产业',
                    label: {show: true},
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    data: [22, 18, 19, 24, 29],
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: {
                        normal: {
                            color: '#00d4c7',
                            lineStyle: {
                                color: '#00d4c7',
                                width: 1
                            },
                            areaStyle: {
                                //color: '#94C9EC'
                                color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                    {
                                        offset: 0,
                                        color: 'rgba(7,44,90,0.3)'
                                    },
                                    {
                                        offset: 1,
                                        color: 'rgba(0,212,199,0.9)'
                                    }
                                ])
                            }
                        }
                    }
                },
                {
                    name: '第三产业',
                    label: {show: true},
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    data: [22, 18, 19, 24, 29],
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: {
                        normal: {
                            color: '#ffde32',
                            lineStyle: {
                                color: '#ffde32',
                                width: 1
                            },
                            areaStyle: {
                                //color: '#94C9EC'
                                color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                    {
                                        offset: 0,
                                        color: 'rgba(255,222,50,0.3)'
                                    },
                                    {
                                        offset: 1,
                                        color: 'rgba(255,222,50,0.3)'
                                    }
                                ])
                            }
                        }
                    }
                },

                {
                    type: 'pie',
                    radius: ['0%', '30%'],
                    center: ['50%', '30%'],
                    color: ['#0092f6', '#00d4c7', '#ffde32'],
                    // 修饰饼形图文字相关的样式 label对象
                    label: {
                        fontSize: 12,
                        formatter: '{b}增速:{c}\n占比{d}%'
                    },
                    data: [
                        {
                            value: 1096,
                            name: '第一产业'
                        },
                        {
                            value: 1240,
                            name: '第二产业'
                        },
                        {
                            value: 1032,
                            name: '第三产业'
                        }
                    ]
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
    }
})
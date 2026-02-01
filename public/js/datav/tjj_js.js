﻿$(function () {


    echartsC1_12();
    echartsC1_3();
    echarts_2();
    echarts_A1_3();
    echartsB1_1();
    echarts_C2_61();
    echarts_C2_62();
    echartsC2_2();

    function echartsC1_12() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC1_12'));

        var scale = 1;
        var echartDataPeo = [
            {
                value: 125,
                name: '市内'
            },
            {
                value: 53,
                name: '市外'
            }
        ];

        var echartDataRmb = [
            {
                value: 90.54,
                name: '市内'
            },
            {
                value: 50.73,
                name: '市外'
            }
        ];
        var rich = {
            yellow: {
                color: '#3bc7ff',
                fontSize: 12 * scale,
                padding: [8, 0],
                align: 'center'
            },
            total: {
                color: '#A2C7F3',
                fontSize: 25 * scale,
                align: 'center'
            },
            white: {
                color: '#a2c7f3',
                align: 'center',
                fontSize: 14 * scale,
                padding: [8, 0]
            },
            blue: {
                color: '#3bc7ff',
                fontSize: 12 * scale,
                align: 'center'
            },
            hr: {
                borderColor: '#a2c7f3',
                width: '100%',
                borderWidth: 1,
                height: 0
            }
        };
        option = {
            // backgroundColor: '#031f2d',
            title: [
                {
                    text: '投资人数统计',
                    left: '15%',
                    top: '2%',
                    textStyle: {
                        color: '#fff',
                        fontSize: 12
                    }
                },
                {
                    text: '投资金额统计',
                    right: '12%',
                    top: '2%',
                    textStyle: {
                        color: '#fff',
                        fontSize: 12
                    }
                },
            ],

            series: [
                {
                    name: '投资人数',
                    type: 'pie',
                    center: ['25%', '50%'],
                    radius: ['50%', '30%'],
                    hoverAnimation: false,
                    color: [
                        '#fc962c',
                        '#d83472',
                        '#0F9AF8',
                        '#2B63D5',
                        '#2039C3',
                        '#2ECACE',
                        '#6F81DA'
                    ],
                    label: {
                        normal: {
                            formatter: function (params, ticket, callback) {
                                var total = 0; //考生总数量
                                var percent = 0; //考生占比
                                echartDataPeo.forEach(function (value, index, array) {
                                    total += value.value;
                                });
                                percent = ((params.value / total) * 100).toFixed(1);
                                return (
                                    '{white|' +
                                    params.name +
                                    '}\n{hr|}\n{yellow|' +
                                    params.value +
                                    '户/}{blue|' +
                                    percent +
                                    '%}'
                                );
                            },
                            rich: rich
                        }
                    },
                    labelLine: {
                        normal: {
                            length: 10 * scale,
                            length2: 0,
                            lineStyle: {
                                color: '#a2c7f3'
                            }
                        }
                    },
                    data: echartDataPeo
                },
                {
                    name: '投资金额',
                    type: 'pie',
                    center: ['77%', '50%'],
                    radius: ['50%', '30%'],
                    hoverAnimation: false,
                    color: [
                        '#fc962c',
                        '#d83472',
                        '#0F9AF8',
                        '#2B63D5',
                        '#2039C3',
                        '#2ECACE',
                        '#6F81DA'
                    ],
                    label: {
                        normal: {
                            formatter: function (params, ticket, callback) {
                                var total = 0; //考生总数量
                                var percent = 0; //考生占比
                                echartDataRmb.forEach(function (value, index, array) {
                                    total += value.value;
                                });
                                percent = ((params.value / total) * 100).toFixed(1);
                                return (
                                    '{white|' +
                                    params.name +
                                    '}\n{hr|}\n{yellow|' +
                                    params.value +
                                    '亿元/}{blue|' +
                                    percent +
                                    '%}'
                                );
                            },
                            rich: rich
                        }
                    },
                    labelLine: {
                        normal: {
                            length: 10 * scale,
                            length2: 0,
                            lineStyle: {
                                color: '#a2c7f3'
                            }
                        }
                    },
                    data: echartDataRmb
                }
            ]
        };


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });

    }

    function echarts_2() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echart2'));

        var yData4 = [
            '崆峒区',
            '华亭市',
            '泾川县',
            '灵台县',
            '崇信县',
            '庄浪县',
            '静宁县'
        ];
        var data4 = ['2000', '1600', '1000', '600', '400', '200', '200'];
        data4.sort((a, b) => b - a);
        var max4 = Math.max.apply(null, data4);
        var getMax4 = [];
        for (let i = 0; i < yData4.length; i++) {
            getMax4.push(max4);
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
                    data: yData4
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
                    data: data4
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
                    data: data4
                },
                {
                    name: '背景',
                    type: 'bar',
                    barWidth: 20,
                    barGap: '-100%',
                    data: getMax4,
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
                left: '2%',
                top: '30px',
                right: '5%',
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
                    text: '产业结构增加值同比',
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
                left: '1%',
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
                min: 150,
                max: 2000,
                // position: 'left',
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
                    data: [312, 313, 310, 313, 390],
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
                    data: [422, 418, 419, 424, 429],
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
                    data: [522, 518, 519, 524, 529],
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
                        formatter: '{b}增加值同比:{c}%'
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

    function echarts_C2_61() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC2_61'));

        option = {
            grid: {
                left: '0%',
                top: '30px',
                right: '0%',
                bottom: '4%',
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
                data: ['价格指数', '同比'],
                show: false
            },
            xAxis: [
                {
                    type: 'category',
                    data: [
                        '崆峒区',
                        '华亭市',
                        '泾川县',
                        '灵台县',
                        '崇信县',
                        '庄浪县',
                        '静宁县'
                    ],

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
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name: '价格指数',
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
                    name: '同比(%)',
                    axisLine: {
                        lineStyle: {
                            color: "rgba(255,255,255,.6)",
                        },
                    },
                    axisTick: {
                        show: false,
                    },
                    axisLabel: {
                        formatter: '{value} %',
                        interval: 0,
                        // rotate:50,
                        show: true,
                        textStyle: {
                            color: "rgba(255,255,255,.6)",
                            fontSize: '12',
                        },
                    },
                    splitLine: {
                        show: false
                    },
                }
            ],
            series: [
                {
                    name: '价格指数',
                    type: 'bar',
                    tooltip: {
                        valueFormatter: function (value) {
                            return value;
                        }
                    },
                    data: [2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6],
                    barWidth: '20',
                    itemStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(
                                0,
                                0,
                                0,
                                1,
                                [
                                    {
                                        offset: 0,
                                        color: 'rgba(5, 213, 255, 1)' // 0% 处的颜色
                                    },
                                    {
                                        offset: 0.98,
                                        color: 'rgba(5, 213, 255, 0)' // 100% 处的颜色
                                    }
                                ],
                                false
                            ),
                            shadowColor: 'rgba(5, 213, 255, 1)',
                            shadowBlur: 4
                        }
                    },
                    label: {
                        normal: {
                            show: true,
                            formatter: '{c}',
                            textStyle: {
                                color: '#fff',
                                fontSize: 10
                            }
                        }
                    }
                },
                {
                    name: '同比',
                    type: 'line',
                    yAxisIndex: 1,
                    tooltip: {
                        valueFormatter: function (value) {
                            return value + ' %';
                        }
                    },
                    label: {show: true},
                    data: [2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3],
                    symbol: 'circle', //标记的图形为实心圆
                    symbolSize: 10, //标记的大小
                    itemStyle: {
                        //折线拐点标志的样式
                        color: '#058cff'
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

    function echarts_C2_62() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC2_62'));

        option = {
            grid: {
                left: '0%',
                top: '30px',
                right: '0%',
                bottom: '4%',
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
                data: ['价格指数', '同比'],
                show: false
            },
            xAxis: [
                {
                    type: 'category',
                    data: [
                        '崆峒区',
                        '华亭市',
                        '泾川县',
                        '灵台县',
                        '崇信县',
                        '庄浪县',
                        '静宁县'
                    ],

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
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name: '价格指数',
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
                    name: '同比(%)',
                    axisLine: {
                        lineStyle: {
                            color: "rgba(255,255,255,.6)",
                        },
                    },
                    axisTick: {
                        show: false,
                    },
                    axisLabel: {
                        formatter: '{value} %',
                        interval: 0,
                        // rotate:50,
                        show: true,
                        textStyle: {
                            color: "rgba(255,255,255,.6)",
                            fontSize: '12',
                        },
                    },
                    splitLine: {
                        show: false
                    },
                }
            ],
            series: [
                {
                    name: '价格指数',
                    type: 'bar',
                    tooltip: {
                        valueFormatter: function (value) {
                            return value;
                        }
                    },
                    data: [2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6],
                    barWidth: '20',
                    itemStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(
                                0,
                                0,
                                0,
                                1,
                                [
                                    {
                                        offset: 0,
                                        color: 'rgba(5, 213, 255, 1)' // 0% 处的颜色
                                    },
                                    {
                                        offset: 0.98,
                                        color: 'rgba(5, 213, 255, 0)' // 100% 处的颜色
                                    }
                                ],
                                false
                            ),
                            shadowColor: 'rgba(5, 213, 255, 1)',
                            shadowBlur: 4
                        }
                    },
                    label: {
                        normal: {
                            show: true,
                            formatter: '{c}',
                            textStyle: {
                                color: '#fff',
                                fontSize: 10
                            }
                        }
                    }
                },
                {
                    name: '同比',
                    type: 'line',
                    yAxisIndex: 1,
                    tooltip: {
                        valueFormatter: function (value) {
                            return value + ' %';
                        }
                    },
                    label: {show: true},
                    data: [2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3],
                    symbol: 'circle', //标记的图形为实心圆
                    symbolSize: 10, //标记的大小
                    itemStyle: {
                        //折线拐点标志的样式
                        color: '#058cff'
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


    function echartsC2_2() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC2_2'));


        var scale = 1;
        var echartDataXf = [
            {
                value: 1500,
                name: '城镇'
            },
            {
                value: 500,
                name: '乡村'
            }
        ];

        var echartDataTb = [
            {
                value: 13.8,
                name: '城镇'
            },
            {
                value: 9.9,
                name: '乡村'
            }
        ];
        var rich = {
            yellow: {
                color: '#3bc7ff',
                fontSize: 10 * scale,
                padding: [8, 0],
                align: 'center'
            },
            total: {
                color: '#A2C7F3',
                fontSize: 25 * scale,
                align: 'center'
            },
            white: {
                color: '#a2c7f3',
                align: 'center',
                fontSize: 14 * scale,
                padding: [8, 0]
            },
            blue: {
                color: '#3bc7ff',
                fontSize: 12 * scale,
                align: 'center'
            },
            hr: {
                borderColor: '#a2c7f3',
                width: '100%',
                borderWidth: 1,
                height: 0
            }
        };
        option = {
            // backgroundColor: '#031f2d',
            title: [
                {
                    text: '消费品零售额',
                    left: '15%',
                    top: '2%',
                    textStyle: {
                        color: '#fff',
                        fontSize: 12
                    }
                },
                {
                    text: '消费品零售额同比',
                    right: '10%',
                    top: '2%',
                    textStyle: {
                        color: '#fff',
                        fontSize: 12
                    }
                }
            ],

            series: [
                {
                    name: '消费品零售额',
                    type: 'pie',
                    radius: ['50%', '30%'],
                    center: ['25%', '50%'],
                    hoverAnimation: false,
                    color: [
                        '#fc962c',
                        '#d83472',
                        '#0F9AF8',
                        '#2B63D5',
                        '#2039C3',
                        '#2ECACE',
                        '#6F81DA'
                    ],
                    label: {
                        normal: {
                            formatter: function (params, ticket, callback) {
                                var total = 0; //考生总数量
                                var percent = 0; //考生占比
                                echartDataXf.forEach(function (value, index, array) {
                                    total += value.value;
                                });
                                percent = ((params.value / total) * 100).toFixed(1);
                                return (
                                    '{white|' +
                                    params.name +
                                    '}\n{hr|}\n{yellow|' +
                                    params.value +
                                    '亿元/}{blue|' +
                                    percent +
                                    '%}'
                                );
                            },
                            rich: rich
                        }
                    },
                    labelLine: {
                        normal: {
                            length: 10 * scale,
                            length2: 0,
                            lineStyle: {
                                color: '#a2c7f3'
                            }
                        }
                    },
                    data: echartDataXf
                },
                {
                    name: '消费品零售额同比',
                    type: 'pie',
                    center: ['77%', '50%'],
                    radius: ['50%', '30%'],
                    hoverAnimation: false,
                    color: [
                        '#fc962c',
                        '#d83472',
                        '#0F9AF8',
                        '#2B63D5',
                        '#2039C3',
                        '#2ECACE',
                        '#6F81DA'
                    ],
                    label: {
                        normal: {
                            formatter: function (params, ticket, callback) {
                                var total = 0; //考生总数量
                                var percent = 0; //考生占比
                                echartDataTb.forEach(function (value, index, array) {
                                    total += value.value;
                                });
                                percent = ((params.value / total) * 100).toFixed(1);
                                return (
                                    '{white|' +
                                    params.name +
                                    '}\n{hr|}\n{yellow|' +
                                    params.value +
                                    '%/}{blue|' +
                                    percent +
                                    '%}'
                                );
                            },
                            rich: rich
                        }
                    },
                    labelLine: {
                        normal: {
                            length: 10 * scale,
                            length2: 0,
                            lineStyle: {
                                color: '#a2c7f3'
                            }
                        }
                    },
                    data: echartDataTb
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });


    }

    function echartsC1_3() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC1_3'));

        option = {
            title: [
                {
                    text: '投资地区排名',
                    left: 'center',
                    top: '10%',
                    textStyle: {
                        color: '#fff',
                        fontSize: 16,
                        fontWeight: 'normal',
                    }
                },
            ],
            /**区域位置*/
            grid: {
                left: '3%',
                right: '6%',
                bottom: '1%',
                containLabel: true
            },
            xAxis: {
                data: [
                    '安徽省',
                    '宁夏',
                    '陕西省',
                    '浙江省',
                    '福建省',
                    '河北省',
                    '湖北省',
                    '山西省',
                    '四川省',
                    '山东省',
                    '湖南省',
                    '江苏省',
                    '江西省',
                    '河南省',
                    '韩国',
                    '开曼群岛',
                    '马来西亚',
                    '英属维尔京群岛',
                    '美国',
                    '英国',
                    '泰国',
                    '德国',
                    '新加坡',
                    '日本',
                    '法国'
                ],
                axisTick: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: 'rgba(255, 129, 109,.1)',
                        width: 1 //这里是为了突出显示加上的
                    }
                },
                axisLabel: {
                    rotate: 60,
                    textStyle: {
                        color: '#999',
                        fontSize: 12
                    }
                }
            },
            yAxis: [
                {
                    type: 'value',
                    name: '亿元',
                    nameTextStyle: {
                        color: 'rgba(255,255,255,.6)',
                        fontSize: 12,
                        align: 'right'
                    },
                    axisLabel: {
                        show: true,
                        textStyle: {
                            color: 'rgba(255,255,255,.6)',
                            fontSize: 13
                        },
                        interval: 0, //类目间隔 设置为 1，表示『隔一个标签显示一个标签』
                        // margin: 10
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
                        show: false,
                        lineStyle: {
                            color: 'rgba(15,45,134,.6)', //横向网格线颜色
                            width: 1
                        }
                    },
                    axisTick: {
                        show: false //坐标轴小标记
                    }
                }
            ],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'none'
                },
                formatter: function (params) {
                    return params[0].name + ': ' + params[0].value;
                }
            },
            series: [
                {
                    type: 'pictorialBar',
                    barWidth: '400%',
                    symbolSize: ['50%', '100%'],
                    label: {
                        show: true,
                        position: 'top',
                        // offset: [0, 0],
                        // color: 'red',
                        color: 'rgba(255,255,255,.6)',
                        formatter: '{c}',
                        // fontSize: 18
                    },
                    symbol: 'path://M0,10 L10,10 C5.5,10 5.5,5 5,0 C4.5,5 4.5,10 0,10 z',
                    markLine: {
                        silent: false, // 是否不响应鼠标事件
                        animation: false, // 关闭动画显示
                        symbol: ['none', 'arrow'], // 如果两个子项都不要起始箭头可综合配置
                        //label: { show: true, position: "insideEndTop", formatter: "市均", color: "#00FFE4" },
                        data: [
                            // {
                            //     xAxis: 6,
                            //     label: {
                            //         formatter: '国内中线'
                            //     },
                            //     symbol: 'none'
                            // },
                            // {
                            //     xAxis: 19,
                            //     label: {
                            //         formatter: '境外中线',
                            //
                            //     },
                            //     symbol: 'none'
                            // },
                            {
                                type: 'average',
                                name: 'b',
                                label: {
                                    formatter: '平均{c}',
                                    position: 'end',
                                },
                                lineStyle: {
                                    type: 'dashed',
                                    color: '#00eaff'
                                },
                                symbol: 'none'
                            }
                        ]
                    },

                    markArea: {
                        data: [
                            [{
                                name: '国内',
                                itemStyle: {
                                    color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                        {
                                            offset: 0,
                                            color: 'rgba(7,44,90,0.3)'
                                        },
                                        {
                                            offset: 1,
                                            color: 'rgba(0,146,246,0.9)'
                                        }
                                    ]),
                                }
                            }, {
                                xAxis: '河南省',
                                // yAxis: 35,
                            }],
                            [{

                                name: '境外',
                                xAxis: '韩国',
                                itemStyle: {
                                    color: new echarts.graphic.LinearGradient(0, 1, 0, 0, [
                                        {
                                            offset: 0,
                                            color: 'rgba(7,44,90,0.3)'
                                        },
                                        {
                                            offset: 1,
                                            color: 'rgba(0,212,199,0.9)'
                                        }
                                    ]),
                                }

                            }, {
                                // xAxis: '法国',
                                // yAxis: 35,
                            }]
                        ],
                        itemStyle: {
                            // color: "rgba(119,212,245,0.3)",
                            borderColor: "rgba(119,212,245,1)",
                            borderType: "dashed"
                        },
                        label: {
                            normal: {
                                show: true,
                                fontSize: 15,
                                fontWeight: 'bold',
                                color: '#3EFFEC'

                            }
                        },
                    },

                    itemStyle: {
                        normal: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [
                                    {
                                        offset: 0,
                                        color: '#FCE03C'
                                    },
                                    {
                                        offset: 0.5,
                                        color: 'rgba(252, 224, 60, .8)'
                                    },
                                    {
                                        offset: 1,
                                        color: 'rgba(113, 157, 235, 0.64)'
                                    }
                                ],
                                global: false //  缺省为  false
                            }
                        },
                        emphasis: {
                            opacity: 1
                        }
                    },
                    data: [
                        '79',
                        '71',
                        '70',
                        '69',
                        '66',
                        '60',
                        '55',
                        '52',
                        '49',
                        '47',
                        '43',
                        '39',
                        '35',
                        '33',
                        '30',
                        '28',
                        '25',
                        '21',
                        '19',
                        '18',
                        '17',
                        '16',
                        '14',
                        '12',
                        '11'
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
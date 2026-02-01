﻿$(function () {

    echarts_2();
    echarts_C2_61();
    echarts_C2_62();
    echartsC1_12();
    echartsC2_2();


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

    function echartsC1_12() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC1_12'));

        var scale = 1;
        var echartDataPeo = [
            {
                value: 3250,
                name: '国内'
            },
            {
                value: 120,
                name: '国外'
            }
        ];

        var echartDataRmb = [
            {
                value: 3250,
                name: '国内'
            },
            {
                value: 120,
                name: '国外'
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
                    right: '15%',
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
                    radius: ['20%', '30%'],
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
                                echartDataPeo.forEach(function (value, index, array) {
                                    total += value.value;
                                });
                                percent = ((params.value / total) * 100).toFixed(1);
                                return (
                                    '{white|' +
                                    params.name +
                                    '}\n{hr|}\n{yellow|' +
                                    params.value +
                                    '人/}{blue|' +
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
                    radius: ['20%', '30%'],
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

    function echartsC2_2() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('echartC2_2'));


        var scale = 1;
        var echartDataXf = [
            {
                value: 3250,
                name: '城镇'
            },
            {
                value: 120,
                name: '乡村'
            }
        ];

        var echartDataTb = [
            {
                value: 3250,
                name: '城镇'
            },
            {
                value: 120,
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
                    right: '15%',
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
                    radius: ['20%', '30%'],
                    center: ['20%', '50%'],
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
                    center: ['80%', '50%'],
                    radius: ['20%', '30%'],
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
})
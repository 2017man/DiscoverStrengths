$(function () {
    map();

    function map() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('map_1'));
        var data = [
            {name: '崆峒区', value: 69},
            {name: '静宁县', value: 100},
            {name: '庄浪县', value: 120},
            {name: '华亭市', value: 120},
            {name: '崇信县', value: 120},
            {name: '灵台县', value: 120},
            {name: '泾川县', value: 120},
        ];
        var geoCoordMap = {
            '崆峒区': [106.684223, 35.54173],
            '静宁县': [105.733489, 35.525243],
            '庄浪县': [106.041979, 35.203428],
            '华亭市': [106.649308, 35.215341],
            '崇信县': [107.031253, 35.304533],
            '灵台县': [107.620587, 35.064009],
            '泾川县': [107.365218, 35.335283]
        };

        var convertData = function (data) {
            var res = [];
            for (var i = 0; i < data.length; i++) {
                var geoCoord = geoCoordMap[data[i].name];
                if (geoCoord) {
                    res.push({
                        name: data[i].name,
                        value: geoCoord.concat(data[i].value)
                    });
                }
            }
            return res;
        };

        option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow',
                },
            },
            // tooltip: {
            //     trigger: 'item',
            //     formatter: function (params) {
            //         if (typeof (params.value)[2] == "undefined") {
            //             return params.name + ' : ' + params.value;
            //         } else {
            //             return params.name + ' : ' + params.value[2];
            //         }
            //     }
            // },

            geo: {
                map: 'pl',
                layoutCenter: ['50%', '50%'],
                layoutSize: '130%',
                label: {
                    emphasis: {
                        show: false
                    }
                },
                zoom: 1.04,
                roam: false,//禁止其放大缩小
                itemStyle: {
                    normal: {
                        borderWidth: 1, //区域边框宽度
                        areaColor: '#092766', //区域颜色#4c60ff,#031525,#092766
                        borderColor: '#00FEFF',//区域边框颜色#002097,#0DC0FF
                        shadowColor: '#6FFDFF',
                        shadowOffsetY: 0,
                        shadowBlur: 10,
                    },
                },
            },
            series: [

                // {
                //     name: 'Top 5',
                //     type: 'effectScatter',
                //     coordinateSystem: 'geo',
                //     data: convertData(data),
                //     symbolSize: function (val) {
                //         return val[2] / 20;
                //     },
                //     showEffectOn: 'render',
                //     rippleEffect: {
                //         period: 15,
                //         scale: 4,
                //         brushType: 'stroke'
                //     },
                //     hoverAnimation: true,
                //     label: {
                //         normal: {
                //             formatter: '{b}',
                //             position: 'right',
                //             show: true
                //         }
                //     },
                //     itemStyle: {
                //         normal: {
                //             color: '#ffd800',
                //             shadowBlur: 10,
                //             shadowColor: 'rgba(0,0,0,.3)'
                //         }
                //     },
                //     zlevel: 6
                // },

                {
                    type: 'map',
                    layoutCenter: ['50%', '50%'],
                    layoutSize: '130%',
                    // geoIndex: 0,
                    map: 'pl',
                    //鼠标悬浮显示
                    tooltip: {
                        trigger: 'item',
                        backgroundColor: 'transparent',
                        borderColor: 'transparent',
                        extraCssText: 'z-index:100;color:#fff;',
                        confine: true, //是否将 tooltip 框限制在图表的区域内
                        formatter: function (params, ticket, callback) {
                            //根据业务自己拓展要显示的内容
                            var res = '';
                            var name = params.name;
                            var count = params.value ? params.value : 0;
                            res = `<div style="box-shadow: 0 0 10px #3BD9D9; padding: 10px; position: absolute; top: 0; left:0;  border-radius: 4px; border: 1px solid #04b9ff; background: linear-gradient(to bottom,  #51bfd4 0%,rgba(35,90,178,.8) 100%);">
                             <div style='color:#F4BD59; font-size: 14px;'>${name}</div>
                             <div style="display: flex; align-items: center;padding-top: 6px;">
                              <div style="height: 6px; width: 6px; border-radius: 50%; background:#F4BD59; margin-right: 10px;"></div> <span style='color:#fff;font-size: 12px;margin-right: 20px;'>值</span><span style="font-size: 12px;font-family: 'PangMenZhengDao'">${count}</span>
                             </div>
                          </div>`;
                            return res;
                        },
                    },
                    itemStyle: {
                        normal: {
                            label: {
                                show: true,
                                color: '#fff',
                            },
                            color: '#fff',
                            borderColor: '#4438e0',
                            borderWidth: 1.5,
                            areaColor: {
                                type: 'linear-gradient',
                                x: 0,
                                y: 1000,
                                x2: 0,
                                y2: 0,
                                colorStops: [
                                    {
                                        offset: 0.5,
                                        color: '#0D59C1', // 0% 处的颜色
                                    },
                                    {
                                        offset: 1,
                                        color: '#53C9C7', // 100% 处的颜色
                                    },
                                ],
                                global: true, // 缺省为 false
                            },

                        },
                        emphasis: {
                            label: {
                                show: true,
                                color: '#fff',
                            },
                            borderWidth: 3,
                            borderColor: 'rgba(255, 230, 175,0.8)',
                            shadowColor: 'rgba(255, 230, 175,0.5)',
                            shadowBlur: 30,
                            textStyle: {
                                color: '#fff',
                                fontSize: 12,
                                backgroundColor: 'transparent',
                            },
                            areaColor: new echarts.graphic.LinearGradient(
                                0,
                                0,
                                0,
                                1,
                                [
                                    {
                                        offset: 0,
                                        color: '#1cfbfe',
                                    },
                                    {
                                        offset: 1,
                                        color: '#3348e7',
                                    },
                                ],
                                false
                            ),
                        },
                    },
                    data: data,
                },

                /**
                 * 地图坐标点显示
                 */
                // {
                //     type: 'effectScatter',
                //     coordinateSystem: 'geo',
                //     rippleEffect: {
                //         brushType: 'fill',
                //     },
                //     label: {
                //         show: true,
                //         color: '#fff',
                //         formatter: function (obj) {
                //             return obj.name;
                //         },
                //     },
                //     symbolSize: function (val) {
                //         var value = val[2];
                //         if (value < 1000) {
                //             return 15;
                //         }
                //         return 20;
                //     },
                //     showEffectOn: 'render', //加载完毕显示特效
                //     itemStyle: {
                //         normal: {
                //             color: '#ffd800',
                //             shadowBlur: 10,
                //             shadowColor: 'rgba(0,0,0,.3)'
                //         },
                //     },
                //     zlevel: 6,
                //     data: convertData(data),
                // },
            ]
        };

        myChart.setOption(option);
        window.addEventListener("resize", function () {
            myChart.resize();
        });
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


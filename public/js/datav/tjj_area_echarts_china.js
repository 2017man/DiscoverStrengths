
chinaDatas = [
    {
        "value": "79",
        "name": "安徽",
    },
    {
        "value": "71",
        "name": "宁夏",
    },
    {
        "value": "70",
        "name": "陕西",
    },
    {
        "value": "69",
        "name": "浙江",
    },
    {
        "value": "66",
        "name": "福建",
    },
    {
        "value": "60",
        "name": "河北",
    },
    {
        "value": "55",
        "name": "湖北",
    },
    {
        "value": "52",
        "name": "山西",
    },
    {
        "value": "49",
        "name": "四川",
    },
    {
        "value": "47",
        "name": "山东",
    },
    {
        "value": "43",
        "name": "湖南",
    },
    {
        "value": "39",
        "name": "江苏",
    },
    {
        "value": "35",
        "name": "江西",
    },
    {
        "value": "33",
        "name": "河南",
    }
];

item = ['平凉市', chinaDatas];

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
function convertData (data) {
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
$(function () {
    // map();



    // 数据转换
    // function map() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('map_2'));



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
                data: convertData(chinaDatas),
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
                    return val[2] / 5; //圆环大小
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
                    text: '投资地区分布（国内)',
                    x: '50%',
                    y: '10%',
                    textAlign: 'center',
                    textStyle: {
                        fontSize: 18,
                        color: '#fff',
                        fontWeight: 'normal',
                    }
                },
            ],
            tooltip: {
                trigger: 'item',
                backgroundColor: 'rgba(62,200,197,0.82)',
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
                min: 33,
                max: 80,
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
                roam: false, //是否允许缩放
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
    // }

})


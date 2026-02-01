var url = "http://pldatav.com/thirdapi/pldatav/data_tjj";
//3s请求刷新一次数据
window.setInterval(function () {
    //声明一个方法使用jQuery的ajax方法发送异步请求
    $.ajax({
        url: url, //请求路径
        success: function (data) {
            console.log(data);
            refreshData(data);
        }, //请求成功时候的回调函数
        error: function () {
            // alert("fail")
            return;
        } //请求失败时候的回调函数
    });

}, 3000);

function refreshData(data) {
    //更新数据

    //C1_12
    var myChartC1_12 = echarts.init(document.getElementById('echartC1_12'));
    var optionC1_12 = myChartC1_12.getOption();
    myChartC1_12.clear();
    // 数据刷新一次
    optionC1_12.series[0].data = data.C1_12.peo.data;
    optionC1_12.series[1].data = data.C1_12.money.data;
    myChartC1_12.setOption(optionC1_12);

    //C2_2
    var myChartC2_2 = echarts.init(document.getElementById('echartC2_2'));
    var optionC2_2 = myChartC2_2.getOption();
    myChartC2_2.clear();
    // 数据刷新一次
    optionC2_2.series[0].data = data.C2_2.xfp.data;
    optionC2_2.series[1].data = data.C2_2.tb.data;
    myChartC2_2.setOption(optionC2_2);

    //A1_3
    var myChartA1_3 = echarts.init(document.getElementById('echartA1_3'));
    var optionA1_3 = myChartA1_3.getOption();
    myChartA1_3.clear();
    // 数据刷新一次
    optionA1_3.series[0].data = data.A1_3.GDP.data;
    optionA1_3.series[1].data = data.A1_3.GDPZS.data;
    optionA1_3.series[2].data = data.A1_3.RJGDP.data;
    myChartA1_3.setOption(optionA1_3);

    //B1_1
    var myChartB1_1 = echarts.init(document.getElementById('echartB1_1'));
    var optionB1_1 = myChartB1_1.getOption();
    myChartB1_1.clear();
    // 数据刷新一次
    optionB1_1.series[0].data = data.B1_1.first.data;
    optionB1_1.series[1].data = data.B1_1.second.data;
    optionB1_1.series[2].data = data.B1_1.three.data;
    optionB1_1.series[3].data = data.B1_1.zs.data;
    myChartB1_1.setOption(optionB1_1);

    //B1_1
    var myChartC1_3 = echarts.init(document.getElementById('echartC1_3'));
    var optionC1_3 = myChartC1_3.getOption();
    myChartC1_3.clear();
    // 数据刷新一次
    optionC1_3.xAxis.data = data.C1_3.xData;
    optionC1_3.series[0].data = data.C1_3.inAndOut.data;
    myChartC1_3.setOption(optionC1_3);


    var myChartmap_2 = echarts.init(document.getElementById('map_2'));
    var optionmap_2 = myChartmap_2.getOption();
    myChartmap_2.clear();
    // 数据刷新一次
    // optionmap_2.xAxis.data = data.C1_3.in.xData;
    optionmap_2.series[0].data = convertData(data.C1_3.in.data);
    myChartmap_2.setOption(optionmap_2);

}
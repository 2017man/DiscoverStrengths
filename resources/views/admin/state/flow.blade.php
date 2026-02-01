<div>
    {{--    <h1>状态流转</h1>--}}
</div>
<div class="timeline">

    @foreach($flows as $flow)
        <div class="entry">
            <div class="title">
                <h3 style="">{{ $flow['created_at'] }}</h3>
            </div>
            <div class="body">
                <h3>{{ $flow['state'] }}</h3>
                <ul>
                    <li>{{ $flow['handler'] }}</li>
                </ul>
                <hr>
                <p>{{ $flow['remark'] }}</p>
            </div>
        </div>
    @endforeach


</div>

<style>
    /*@charset "UTF-8";*/
    /*body {*/
    /*    !*background: linear-gradient(55deg, #4E75B9 30%, #5CBF98 90%);*!*/
    /*    !*display: flex;*!*/
    /*    align-items: center;*/
    /*    justify-content: center;*/
    /*    min-height: 100vh;*/
    /*    !*width: 200vw;*!*/
    /*    margin: 0;*/
    /*    !*padding: 12vh 100px;*!*/
    /*    font-family: "Source Sans Pro", arial, sans-serif;*/
    /*    font-weight: 300;*/
    /*    !*color: #333;*!*/
    /*    box-sizing: border-box;*/
    /*}*/

    /*body * {*/
    /*    box-sizing: border-box;*/
    /*}*/

    .timeline {
        width: 100%;

        max-width: 800px;
        background: #fff;
        padding: 100px 50px;
        position: relative;
        box-shadow: 0.5rem 0.5rem 2rem 0 rgba(0, 0, 0, 0.2);
    }

    .timeline:before {
        content: "";
        position: absolute;
        top: 20px;
        bottom: 20px;
        left: calc(33% + 16px);
        width: 1px;
        background: #ddd;
    }

    .timeline:after {
        content: "";
        display: table;
        clear: both;
    }

    .entry {
        clear: both;
        text-align: left;
        position: relative;
    }

    .entry .title {
        margin-bottom: 0.5em;
        float: left;
        width: 33%;
        padding-right: 30px;
        text-align: right;
        position: relative;
    }

    .entry .title:before {
        content: "";
        position: absolute;
        width: 8px;
        height: 8px;
        border: 4px solid salmon;
        background-color: #fff;
        border-radius: 100%;
        top: 15%;
        right: -7px;
        z-index: 99;
    }

    .entry .title h3 {
        margin: 0;
        font-size: 100%;
    }

    .entry .title p {
        margin: 0;
        font-size: 100%;
    }

    /**
    右侧文本设置
     */
    .entry .body {
        margin: 0 0 3em;
        float: right;
        width: 66%;
        padding-left: 30px;
    }

    .entry .body p {
        line-height: 1.4em;
    }

    .entry .body p:first-child {
        margin-top: 0;
        font-weight: 400;
    }

    .entry .body ul {
        color: #aaa;
        padding-left: 0;
        list-style-type: none;
    }

    .entry .body ul li:before {
        content: "–";
        margin-right: 0.5em;
    }
</style>


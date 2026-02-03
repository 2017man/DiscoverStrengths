<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>MBTI 职业性格测试报告 - {{ $result_code ?? '' }}</title>
    <style>
        body { font-family: "SimSun", "宋体", serif; font-size: 12px; line-height: 1.6; color: #333; margin: 20px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        h2 { font-size: 14px; margin-top: 20px; margin-bottom: 10px; color: #333; }
        .summary { font-size: 14px; font-weight: bold; margin-bottom: 15px; color: #444; }
        .section { margin-bottom: 15px; }
        ul { margin: 5px 0; padding-left: 20px; }
        li { margin-bottom: 5px; }
        .traits { white-space: pre-wrap; }
        .footer { margin-top: 30px; font-size: 11px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <h1>MBTI 职业性格测试报告</h1>

    <div class="summary">
        {{ $result_code ?? '' }} - {{ $result_name ?? '' }}
    </div>

    @if(!empty($summary))
    <div class="section">
        <h2>总括</h2>
        <p>{{ $summary }}</p>
    </div>
    @endif

    @if(!empty($traits_summary))
    <div class="section">
        <h2>性格特点总括</h2>
        <p>{{ $traits_summary }}</p>
    </div>
    @endif

    @if(!empty($traits))
    <div class="section">
        <h2>性格特点</h2>
        <div class="traits">{{ $traits }}</div>
    </div>
    @endif

    @if(!empty($strengths) && is_array($strengths))
    <div class="section">
        <h2>优势</h2>
        <ul>
            @foreach($strengths as $item)
            <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($weaknesses) && is_array($weaknesses))
    <div class="section">
        <h2>劣势</h2>
        <ul>
            @foreach($weaknesses as $item)
            <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($careers))
    <div class="section">
        <h2>适合的职业</h2>
        <div class="traits">{{ $careers }}</div>
    </div>
    @endif

    @if(!empty($typical_figures))
    <div class="section">
        <h2>典型人物</h2>
        <p>{{ $typical_figures }}</p>
    </div>
    @endif

    @if(!empty($suggestion))
    <div class="section">
        <h2>沟通与成长建议</h2>
        <div class="traits">{{ $suggestion }}</div>
    </div>
    @endif

    <div class="footer">
        <p>—— 发现你的天赋优势 ——</p>
        <p>生成时间：{{ date('Y-m-d H:i') }}</p>
    </div>
</body>
</html>

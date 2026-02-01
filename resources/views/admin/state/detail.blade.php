<div class="list-group" style="padding-bottom: 30px">
    <a href="#" class="list-group-item active">基本信息</a>
    <a href="#" class="list-group-item">
        员工信息：{{ $info['user']['name'] }}
        - {{ $info['user']['department'] }}
        - {{ $info['user']['phone'] }}
        - {{ $info['user']['job_number'] }}
    </a>
    <a href="#" class="list-group-item">
        亲属集团：{{ $info['company_area'] }}
        - {{ $info['company_name'] }}
        - {{ $info['relations']['supervisor_name'] }}
        - {{ $info['relations']['supervisor_position'] }}
    </a>
    <a href="#" class="list-group-item">
        收集时间：{{ $info['created_at'] }}
    </a>
    <a href="#" class="list-group-item">收集内容： {{ $info['content'] }}</a>
</div>


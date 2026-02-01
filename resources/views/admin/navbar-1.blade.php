<ul class="nav navbar-nav">
    <li class="dropdown dropdown-notification nav-item">
        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown" aria-expanded="true"><i
                class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up">5</span></a>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right ">
            <li class="dropdown-menu-header">
                <div class="dropdown-header m-0 p-2">
                    <h3 class="white">5 New</h3><span class="grey darken-2">消息通知</span>
                </div>
            </li>
            <li class="scrollable-container media-list ps ps--active-y">

                @foreach ($notifications as $notification)
                    <a class="d-flex justify-content-between" href="javascript:void(0)">
                        <div class="media d-flex align-items-start">
                            <div class="media-left"><i class="feather icon-plus-square font-medium-5 primary"></i></div>
                            <div class="media-body">
                                <h6 class="primary media-heading">{{ $notification['title'] }}</h6>
                                <small class="notification-text">{{ $notification['text'] }}</small>
                            </div>
                            <small>
                                <time class="media-meta"
                                      datetime="2015-06-11T18:29:20+08:00">{{ $notification['created_at'] }}</time>
                            </small>
                        </div>
                    </a>
                @endforeach

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 0px; right: 0px; height: 254px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 184px;"></div>
                </div>
            </li>
            <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center" href="javascript:void(0)">Read
                    all notifications</a>
            </li>
        </ul>
    </li>
</ul>

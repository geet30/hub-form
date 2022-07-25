<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
        <i class="icon-bell"></i>
        <span class="badge badge-default notification-count"> {{ count($notifications) }} </span>
    </a>
    <ul class="dropdown-menu">
        <li>
            <ul class="dropdown-menu-list scroller dropdown-notification-scroll" data-handle-color="#637283" data-initialized="1">
                <?php
                // dd($notifications);die; 
                ?>
                @foreach ($notifications as $notificationrow)
                <li class="notification_{{$notificationrow->id}}">
                    @if(isset($notificationrow->notification_type) && $notificationrow->notification_type == 30 && isset($notificationrow->notificationable->status) && $notificationrow->notificationable->status == 1 || isset($notificationrow->notificationable->action) && $notificationrow->notificationable->action->status == 1)

                    <a class="mark-as-read" onclick="return false">
                        <span class="time">{{ $notificationrow->created_at->diffForHumans() }}</span>
                        <span class="details action_notify">
                            <span class="label label-sm label-icon label-info">
                                <i class="fa fa-bullhorn"></i>
                            </span> {{ $notificationrow->message }}
                        </span>
                        <span class="label label-default action-status reject-action" target="{{$notificationrow->id}}" data-id="{{(isset($notificationrow->notificationable->action))? $notificationrow->notificationable->action->id : $notificationrow->notificationable_id}}" onclick="open_model(this)">Reject</span>
                        <span class="label label-default action-status accept-action" target="{{$notificationrow->id}}" data-id="{{(isset($notificationrow->notificationable->action))? $notificationrow->notificationable->action->id : $notificationrow->notificationable_id}}" onclick="accept_reject_action(this, 1)">Accept</span>
                    </a>

                    @elseif(isset($notificationrow->notification_type) && $notificationrow->notification_type == 35 && isset($notificationrow->notificationable))

                    @if($notificationrow->notificationable->i_status == 0)
                    <a class="mark-as-read" onclick="return false">
                        <span class="time">{{ $notificationrow->created_at->diffForHumans() }}</span>
                        <span class="details action_notify">
                            <span class="label label-sm label-icon label-info">
                                <i class="fa fa-bullhorn"></i>
                            </span> {{ $notificationrow->message }}
                        </span>
                        <span class="label label-default supplier-status reject-action " data-id="{{$notificationrow->id}}" onclick="return false">Pending</span>
                        <span class="label label-default supplier-status accept-action" target="{{$notificationrow->id}}" data-id="{{(isset($notificationrow->notificationable))? $notificationrow->notificationable->id : $notificationrow->notificationable_id}}" onclick="approved_supplier(this, 1)">Approved</span>
                    </a>

                    @endif


                    @elseif(isset($notificationrow->notification_type) && $notificationrow->notification_type == 36 && isset($notificationrow->notificationable ) && isset($notificationrow->notificationable->status) && $notificationrow->notificationable->status == 1 )
                    <a class="mark-as-read" onclick="return false">
                        <span class="time">{{ $notificationrow->created_at->diffForHumans() }}</span>
                        <span class="details action_notify">
                            <span class="label label-sm label-icon label-info">
                                <i class="fa fa-bullhorn"></i>
                            </span> {{ $notificationrow->message }}
                        </span>

                    </a>
                    @elseif(isset($notificationrow->notification_type) && $notificationrow->notification_type == 37 && isset($notificationrow->notificationable ) )
                    <a class="mark-as-read chatnotification" onclick="return false" data-id="{{ $notificationrow->id}}" href="{{$notificationrow->url}}">
                        <span class="time"> {{$notificationrow->created_at->diffForHumans() }}</span>
                        <span class="details"><span class="label label-sm label-icon label-info">
                                <i class="fa fa-bullhorn"></i>
                            </span>{{$notificationrow->title}} <br> {{ $notificationrow->message }}
                        </span>
                    </a>
                    @else
                    <?php //echo date('H:i:s', \Carbon\Carbon::parse($notificationrow->created_at)->getTimestamp()); die;
                    ?>
                    <a class="mark-as-read" onclick="return false" data-id="{{ $notificationrow->id }}" href="{{ $notificationrow->url }}">
                        <span class="time">{{ $notificationrow->created_at->diffForHumans() }}</span>
                        <span class="details">
                            <span class="label label-sm label-icon label-info">
                                <i class="fa fa-bullhorn"></i>
                            </span> {{ $notificationrow->message }}
                        </span>
                    </a>
                    @endif
                </li>
                @endforeach
            </ul>
        </li>
        <li class="external">
            <h3><span class="bold">{{ auth()->user()->notifications_count->count() }} pending</span> notifications</h3>
            <a href="{{ route('notifications') }}">View All</a>
        </li>
    </ul>
</li>
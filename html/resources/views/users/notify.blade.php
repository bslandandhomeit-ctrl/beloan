@if(!empty($last_login) && count($last_login) > 0)
    @foreach($last_login as $ln)
        <li>
            <div class="prog-row">
                <a href="{{route('user_detail',[$ln->id])}}">
                    <div class="user-thumb">
                        <img src="{{ !empty($ln->photo) ? asset('data/users/'.$ln->photo, isset($secure)?false:false) : asset('images/no_profile.jpg', isset($secure)?false:false)}}" alt="">
                    </div>
                    <div class="user-details">
                        <h4>{{ $ln->name }}</h4>
                        <p>
                            Ip: {{ $ln->login_ip }}
                            <?php $ago = new Carbon\Carbon($ln->last_login); echo $ago->diffForHumans(); ?>
                        </p>
                    </div>
                </a>
            </div>
        </li>
    @endforeach
    @if($last_login->hasMorePages())
        <li class="text-center">
            <a href="javascript:;" class="view-btn text-info" id="more-notify">{{ trans('loan.l_more') }}</a>
        </li>
    @endif
@endif

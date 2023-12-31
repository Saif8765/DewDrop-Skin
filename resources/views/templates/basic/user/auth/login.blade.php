@extends($activeTemplate.'layouts.app')

@php
$loginSectionContent=getContent('login.content',true);
@endphp
@section('content')
<style>
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #999;
        cursor: pointer;
    }
</style>
<!-- Account Section Starts Here -->
<section class="account-section">
    <div class="row g-0 flex-wrap-reverse">
        <div class="col-md-6 col-xl-5 col-lg-6 ">
            <div class="account-form-wrapper">
                <div class="logo"><a href="{{ route('home') }}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/light_logo.png')}}" alt="logo"></a></div>
                 <form class="account-form" method="post" action="{{route('user.login')}}" onsubmit="return submitUserForm();">
    @csrf
    <div class="form--group">
        <label for="uname" class="form-label">@lang('Username')<span>*</span></label>
        <input id="uname" name="username" type="text" class="form-control form--control"  placeholder="@lang('Enter Username')" value="{{ old('username') }}">
    </div>
    <div class="form--group">

        <div>Hi</div>
        <label for="pass" class="form-label">@lang('Password')<span>*</span></label>
        <div class="password-wrapper">
            <input id="pass" name="password" type="password" class="form-control form--control" placeholder="@lang('Enter Password')">
            <i class="fa fa-eye password-toggle" aria-hidden="true"></i>
        </div>
    </div>
    <div class="form--group">
        @php echo loadReCaptcha() @endphp
    </div>
    @include($activeTemplate.'partials.custom_captcha')
    <div class="form--group custom--checkbox">
        <input id="remember-me" type="checkbox" name="remember" class="form-control form--control">
        <label for="remember-me" class="form-label">@lang('Remember Me')</label>
    </div>
    <div class="form--group button-wrapper">
        <button class="account--btn" type="submit">@lang('Sign In')</button>
        <a href="{{ route('user.register') }}" class="custom--btn"><span>@lang('Create Account')</span></a>
    </div>
</form>

                <p class="text--dark">@lang('Forgot Your Login Credentials') ? <a class="text--base ms-2" href="{{ route('user.password.request') }}">@lang('Reset Password')</a></p>
                <p class="text--dark">@lang('Forgot Your Email Address') ? <a class="text--base ms-2" href="{{ route('user.email.find') }}">@lang('Find Email')</a></p>
            </div>
        </div>
        <div class="col-md-6 col-xl-7 col-lg-6">
            <div class="account-thumb">
                <img src="{{getImage('assets/images/frontend/login/'.@$loginSectionContent->data_values->login_image,'1100x750')}}" alt="thumb">
                <div class="account-thumb-content">
                    <p class="welc">{{ __(@$loginSectionContent->data_values->title) }}</p>
                    <h3 class="title">{{ __(@$loginSectionContent->data_values->heading) }}</h3>
                    <p class="info">{{ __(@$loginSectionContent->data_values->sub_heading)}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="shape shape1"><img src="{{asset($activeTemplateTrue.'images/shape/08.png')}}" alt="shape"></div>
    <div class="shape shape2"><img src="{{asset($activeTemplateTrue.'images/shape/waves.png')}}" alt="shape"></div>
</section>



@endsection


@push('script')
<script>
    $(document).ready(function() {
        // Password toggle show/hide
        $('.password-toggle').click(function() {
            var passwordInput = $(this).parent().find('input');
            var passwordFieldType = passwordInput.attr('type');
            if (passwordFieldType == 'password') {
                passwordInput.attr('type', 'text');
                $(this).removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                $(this).removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>


@endpush
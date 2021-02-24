@extends('layouts.frontLayout.front_design')
@section('content')
    
<section id="form" style="margin-top: 20px;"><!--form-->
    <div class="container">
        <div class="row">
            @if (Session::has("flash_message_error"))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{!! session("flash_message_error") !!}</strong>
                </div>
                @endif 
                @if (Session::has("flash_message_success"))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{!! session("flash_message_success") !!}</strong>
                    </div>
                @endif
            <div class="col-sm-4 col-sm-offset-1">
                <div class="login-form"><!--login form-->
                    <h2>Forgot Password?</h2>
                    <form id="forgotPasswordForm" name="forgotPasswordForm" action="{{ url('/forgot-password') }}"
                     method="POST"> @csrf
                        <input name="email" type="email" placeholder="Email Address" required/>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div><!--/login form-->
            </div>
            <div class="col-sm-1">
                <h2 class="or">OR</h2>
            </div>
            <div class="col-sm-4">
                <div class="signup-form">
                    <!--sign up form-->
                    <h2>New User Signup!</h2>
                    <form name="registerForm" id="registerForm" action="{{ url('/user-register') }}" method="POST">
                        @csrf
                        <input name="name" id="name" type="text" placeholder="Name"/>
                        <input name="email" id="email" type="email" placeholder="Email Address"/>
                        <input name="password" id="myPassword" type="password" placeholder="Password"/>
                        <button type="submit" class="btn btn-default">Signup</button>
                    </form>
                </div>
                <!--/sign up form-->
            </div>
        </div>
    </div>
</section><!--/form-->

@endsection
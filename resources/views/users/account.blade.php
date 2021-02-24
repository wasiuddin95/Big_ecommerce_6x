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
                <div class="login-form"><!--Update Account-->
                    <h2>Update your account</h2>
                    <form name="accountForm" id="accountForm" action="{{ url('/account') }}" method="POST">
                        @csrf
                        <input value="{{ $userDetails->name }}" name="name" id="name" type="text" placeholder="Name"/>
                        <input value="{{ $userDetails->address }}" name="address" id="address" type="text" placeholder="Address"/>
                        <input value="{{ $userDetails->city }}" name="city" id="city" type="text" placeholder="City"/>
                        <input value="{{ $userDetails->state }}" name="state" id="state" type="text" placeholder="Division"/>
                        <select name="country" id="country">
                            <option value="">Select Country</option>
                            <option value="Bangladesh" selected>Bangladesh</option>
                            @foreach ($countries as $country)
                              <option value="{{ $country->country_name }}" 
                              @if($country->country_name == $userDetails->country) selected @endif>
                                {{ $country->country_name }}</option>
                            @endforeach
                        </select>
                        <input value="{{ $userDetails->pincode }}" name="pincode" id="pincode" type="text" placeholder="Pincode" style="margin-top: 10px;"/>
                        <input value="{{ $userDetails->mobile }}" name="mobile" id="mobile" type="text" placeholder="Mobile"/>
                        <button type="submit" class="btn btn-default">Update</button>
                    </form>
                </div><!--/Update Account-->
            </div>
            <div class="col-sm-1">
                <h2 class="or">OR</h2>
            </div>
            <div class="col-sm-4">
                <div class="signup-form">
                    <!--Password Change-->
                    <h2>Update Passwword</h2>
                    <form name="passwordForm" id="passwordForm" action="{{ url('/update-user-pwd') }}" method="POST">
                        @csrf
                        <input type="password" name="current_pwd" id="current_pwd" placeholder="Current Password">
                        <span id="chkPwd"></span>
                        <input type="password" name="new_pwd" id="new_pwd" placeholder="New Password">
                        <input type="password" name="confirm_pwd" id="confirm_pwd" placeholder="Confirm Password">
                        <button type="submit" class="btn btn-default">Update</button>
                    </form>
                </div>
                <!--/Password Change-->
            </div>
        </div>
    </div>
</section><!--/form-->

@endsection
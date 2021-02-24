<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Great E-commerce Admin</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="{{asset('css/backend_css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('css/backend_css/bootstrap-responsive.min.css')}}" />
        <link rel="stylesheet" href="{{asset('css/backend_css/matrix-login.css')}}" />
        <link href="{{asset('fonts/backend_fonts/css/font-awesome.css')}}" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

    </head>
    <body> 
        @if (Session::has("flash_message_error"))
            <div class="alert alert-error alert-block">
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
        <div class="row">
            <div class="container">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div >
                        <form  action="{{ url('/admin-forgot-password') }}" method="post" class="form-vertical">
                            @csrf
                            <div class="control-group normal_text"> <h3><img src="{{ asset('images/backend_images/logo2.png') }}" alt="Logo" /></h3></div>
                            <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
                            
                                <div class="control-group">
                                    <div class="controls">
                                        <div class="main_input_box">
                                            <span class="add-on bg_lg"><i class="icon-envelope"></i></span><input type="text" 
                                            name="name" placeholder="E-mail address" required/>
                                        </div>
                                    </div>
                                </div>
                           
                            <div class="form-actions">
                                <span class="pull-left"><a href="#" class="flip-link btn btn-success" >&laquo; Back to login</a></span>
                                <span class="pull-right"><button type="submit" class="btn btn-info"/>Recover</button></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{asset('js')}}/backend_js/jquery.min.js"></script>  
        <script src="{{asset('js')}}/backend_js/matrix.login.js"></script> 
        <script src="{{asset('js/backend_js')}}/bootstrap.min.js"></script> 
    </body>

</html>

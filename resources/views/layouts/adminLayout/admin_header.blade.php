<!--Header-part-->
<div id="header">
    <a href="{{url('/admin/dashboard')}}">
      <img src="{{ asset('images/backend_images/logo3.png') }}" class="logo_icon img-fluid">
    </a>
</div>
  <!--close-Header-part--> 
  
  
  <!--top-Header-menu-->
  <div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
      <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" 
        class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">
          <strong>Welcome {{-- {{ Auth::user()->name }} --}}
          ({{ Session::get('adminDetails')['username'] }}) </strong></span><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
          <li class="divider"></li>
          <li><a href="{{url('/admin/settings')}}"><i class="icon-check"></i> Change Password</a></li>
          <li class="divider"></li>
          <li><a href="{{ url('/logout') }}"><i class="icon-key"></i> Log Out</a></li>
        </ul>
      </li>
      <li class=""><a title="" href="javascript:void(0)"><i class="icon icon-user"></i>
        <span class="text">{{ Session::get('adminDetails')['type'] }}</span></a></li>
      <li class=""><a title="" href="{{url('/admin/settings')}}"><i class="icon icon-cog"></i>
         <span class="text">Settings</span></a></li>
      <li class=""><a title="" href="{{ url('/logout') }}"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
    </ul>
  </div>
  <!--close-top-Header-menu-->
  <!--start-top-serch-->
  <div id="search">
    <input type="text" placeholder="Search here..."/>
    <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
  </div>
  <!--close-top-serch-->
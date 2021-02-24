@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
         Home</a> <a href="#">Users</a> <a href="#" class="current">View User</a> </div>
    <h1>Users</h1>
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
  </div>
  {{-- View Export Link start --}}
  <div class="text-right" style="margin-right: 20px;" >
    <a href="{{ url('/admin/export-users') }}" class="btn btn-primary btn-mini"> + Export</a>
  </div>
  {{-- View Export Link End --}}
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Users</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>User ID</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>City</th>
                  <th>Division</th>
                  <th>Country</th>
                  <th>Pincode</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Registered On</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($users as $user)
              <tr class="gradeX">
                <td class="center">{{ $user->id }}</td>
                <td class="center">{{ $user->name }}</td>
                <td class="center">{{ $user->address }}</td>
                <td class="center">{{ $user->city }}</td>
                <td class="center">{{ $user->state }}</td>
                <td class="center">{{ $user->country }}</td>
                <td class="center">{{ $user->pincode }}</td>
                <td class="center">{{ $user->mobile }}</td>
                <td class="center">{{ $user->email }}</td>
                <td class="center">
                    @if ( $user->status==1 )
                        <span style="color: lightseagreen;">Active</span>
                    @else
                        <span style="color: red;">Inactive</span>
                    @endif  
                </td>
                <td class="center">{{ $user->created_at }}</td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    
@endsection
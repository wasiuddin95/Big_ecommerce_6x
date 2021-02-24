@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
         Home</a> <a href="#">Newsletter Subscribers</a> <a href="#" class="current">View Subscribers</a> </div>
    <h1>Subscribers</h1>
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
  <div class="text-right" style="margin-right: 20px;" >
      <a href="{{ url('/admin/export-newsletter-emails') }}" class="btn btn-primary btn-mini"> + Export</a>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Subscribers</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th style="text-align: left;">User ID</th>
                  <th style="text-align: left;">Email</th>
                  <th style="text-align: left;">Status</th>
                  <th style="text-align: left;">Created On</th>
                  <th style="text-align: left;">Delete</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($newsletters as $newsletter)
              <tr class="gradeX">
                <td class="center">{{ $newsletter->id }}</td>
                <td class="center">{{ $newsletter->email }}</td>
                <td class="center">
                    @if ( $newsletter->status==1 )
                      <a href="{{ url('/admin/update-newsletter-status/'.$newsletter->id.'/0') }}" title="Click to Inactive">
                        <span style="color: lightseagreen;">Active</span></a>
                    @else
                      <a href="{{ url('/admin/update-newsletter-status/'.$newsletter->id.'/1') }}" title="Click to Active">
                        <span style="color: red;">Inactive</span></a>
                    @endif  
                </td>
                <td class="center">{{ $newsletter->created_at }}</td>
                <td class="center">
                    <a href="javascript:" rel="{{ $newsletter->id }}" rel1="delete-newsletter-email"
                        title="Delete Subscriber" class="deleteRecord btn btn-danger btn-mini">Delete</a>
                </td>
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
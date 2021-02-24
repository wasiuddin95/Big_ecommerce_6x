@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
         Home</a> <a href="#">CMS Pages</a> <a href="#" class="current">View CMS Pages</a> </div>
    <h1>CMS Pages</h1>
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
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>CMS Pages</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Page ID</th>
                  <th>Title</th>
                  <th>URL</th>
                  <th>Status</th>
                  <th>Created On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($cmsPages as $page)
              <tr class="gradeX">
                <td>{{ $page->id }}</td>
                <td>{{ $page->title }}</td>
                <td>{{ $page->url }}</td>
                <td>{{ $page->meta_title }}</td>
                <td>{{ $page->meta_keywords }}</td>
                <td>@if($page->status==1) Active @else Inactive @endif</td>
                <td>{{ $page->created_at }}</td>
                <td class="center">
                  <a href="#myModal{{ $page->id }}" data-toggle="modal"
                  title="View CMS Page" class="btn btn-success btn-mini">View</a>
                  <a href="{{ url('/admin/edit-cms-page/'.$page->id) }}"
                  title="Edit CMS Page"   class="btn btn-primary btn-mini">Edit </a>
                  <a href="javascript:"  rel="{{ $page->id }}" rel1="delete-cms-page"
                    title="Delete Product" class="deleteRecord btn btn-danger btn-mini">Delete</a>
                </td>
              </tr>

                <div id="myModal{{ $page->id }}" class="modal hide">
                  <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h3>{{ $page->title }} Full Details</h3>
                  </div>
                  <div class="modal-body">
                    <p><strong>Title:</strong> {{ $page->title }}</p>
                    <p><strong>URL:</strong> {{ $page->url }}</p>
                    <p><strong>Status:</strong> @if($page->status==1) Active @else Inactive @endif</p>
                    <p><strong>Created On:</strong> {{ $page->created_at }}</p>
                    <p><strong>Description:</strong> {{ $page->description }}</p>
                    <p><strong>Meta Title:</strong> {{ $page->meta_title }}</p>
                    <p><strong>Meta Keywords:</strong> {{ $page->meta_keywords }}</p>
                    <p><strong>Meta Description:</strong> {{ $page->meta_description }}</p>
                  </div>
                </div>

              @endforeach

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
  $(function(){
      $(document).on('click','#delCat',function(e){
          e.preventDefault();
          var link = $(this).attr("href");
          Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
              if (result.value) {
                  window.location.href = link;
                  Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                  )
              }
              })
      });
  });
</script>
    
@endsection
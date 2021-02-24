@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Categories</a> <a href="#" class="current">View Category</a> </div>
    <h1>Categories</h1>
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
            <h5>View Categories</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th style="text-align: left;">ID</th>
                  <th style="text-align: left;">Category Name</th>
                  <th style="text-align: left;">Category Level</th>
                  <th style="text-align: left;">Category URL</th>
                  <th style="text-align: left;">Actions</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($categories as $category)
              <tr class="gradeX">
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent_id }}</td>
                <td>{{ $category->url }}</td>
                <td class="center">
                  @if(Session::get('adminDetails')['categories_edit_access']==1)
                  <a href="{{ url('/admin/edit-category/'.$category->id) }}" class="btn btn-primary btn-mini">Edit </a>
                  @endif
                  @if(Session::get('adminDetails')['categories_full_access']==1)
                  <a <?php /* href="{{ url('/admin/delete-category/'.$category->id) }}" id="delCat" */?> href="javascript:"
                  rel="{{ $category->id }}" rel1="delete-category"  class="deleteRecord btn btn-danger btn-mini">Delete</a>
                  @endif
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
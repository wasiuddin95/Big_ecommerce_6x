@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
         Home</a> <a href="#">Coupons</a> <a href="#" class="current">View Coupon</a> </div>
    <h1>Coupons</h1>
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
            <h5>View Coupons</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Coupon ID</th>
                  <th>Coupon Code</th>
                  <th>Amount</th>
                  <th>Amount Type</th>
                  <th>Expiry Date</th>
                  <th>Created Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($coupons as $coupon)
              <tr class="gradeX">
                <td>{{ $coupon->id }}</td>
                <td>{{ $coupon->coupon_code }}</td>
                <td>
                    {{ $coupon->amount }}
                    @if($coupon->amount_type=="Percentage") % @else /Tk @endif
                </td>
                <td>{{ $coupon->amount_type }}</td>
                <td>{{ $coupon->expiry_date }}</td>
                <td>{{ $coupon->created_at }}</td>
                <td>
                    @if($coupon->status=="1") Active @else Inactive @endif
                </td>
                <td class="center">
                  <a href="{{ url('/admin/edit-coupon/'.$coupon->id) }}"
                  title="Edit Coupon"   class="btn btn-primary btn-mini">Edit </a>
                  <a href="javascript:"  rel="{{ $coupon->id }}" rel1="delete-coupon"
                  title="Delete Coupon" class="deleteRecord btn btn-danger btn-mini">Delete</a>
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
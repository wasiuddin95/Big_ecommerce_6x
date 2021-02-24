@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Products</a> <a href="#" class="current">View Product</a> </div>
    <h1>Products</h1>
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
    <a href="{{ url('/admin/export-products') }}" class="btn btn-primary btn-mini"> + Export</a>
  </div>
  {{-- View Export Link End --}}
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Products</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Category ID</th>
                  <th>Category Name</th>
                  <th>Product Name</th>
                  <th>Product Code</th>
                  <th>Product Color</th>
                  <th>Price</th>
                  <th>Image</th>
                  <th>Feature <br> Items</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($products as $product)
              <tr class="gradeX">
                <td>{{ $product->id }}</td>
                <td>{{ $product->category_id }}</td>
                <td>{{ $product->category_name }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_color }}</td>
                <td>{{ $product->price }}/Tk</td>
                <td>
                  @if (!empty($product->image))
                  <img style="width: 100px;height :100px;"
                  src="{{ asset('/images/backend_images/products/small/'.$product->image) }}" alt="">
                  @endif
                </td>
                <td>@if($product->feature_item==1) Yes @else No @endif</td>
                <td class="center">
                  <a href="#myModal{{ $product->id }}" data-toggle="modal" style="margin-bottom: 3px;"
                  title="View Product" class="btn btn-success btn-mini">View</a> <br>
                  <a href="{{ url('/admin/edit-product/'.$product->id) }}" style="margin-bottom: 3px;"
                  title="Edit Product"   class="btn btn-primary btn-mini">Edit </a> <br>
                  <a href="{{ url('/admin/add-attributes/'.$product->id) }}" style="margin-bottom: 3px;"
                  title="Add Product Attributes"   class="btn btn-success btn-mini">Add Attribute</a> <br>
                  <a href="{{ url('/admin/add-images/'.$product->id) }}" style="margin-bottom: 3px;"
                  title="Add Extra Images"   class="btn btn-info btn-mini">Add Images</a>  <br>
                  <a <?php /* href="{{ url('/admin/delete-product/'.$product->id) }}" */?> 
                  href="javascript:"  rel="{{ $product->id }}" rel1="delete-product"
                  title="Delete Product" class="deleteRecord btn btn-danger btn-mini">Delete</a>
                </td>
              </tr>

                <div id="myModal{{ $product->id }}" class="modal hide">
                  <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h3>{{ $product->product_name }} Full Details</h3>
                  </div>
                  <div class="modal-body">
                    @if (!empty($product->image))
                    <img style="width: 100px;height :100px;"
                    src="{{ asset('/images/backend_images/products/small/'.$product->image) }}" alt="">
                    @endif
                    <p>Product ID: {{ $product->id }}</p>
                    <p>Category ID: {{ $product->category_id }}</p>
                    <p>Product Code: {{ $product->product_code }}</p>
                    <p>Product Color: {{ $product->product_color }}</p>
                    <p>Price: {{ $product->price }}/Tk</p>
                    <p>Fabric: </p>
                    <p>Material: </p>
                    <p>Description: {{ $product->description }}</p>
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
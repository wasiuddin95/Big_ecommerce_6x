@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
         Home</a> <a href="#">Shipping</a> <a href="#" class="current">Edit Shipping</a> </div>
      <h1>Shipping Charges</h1>
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
    <div class="container-fluid"><hr>
      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
              <h5>Edit Shipping Charges</h5>
            </div>
            <div class="widget-content nopadding">
              <form class="form-horizontal" method="post" action="{{url('/admin/edit-shipping/'.$shippingDetails->id)}}" 
              name="edit_shipping" id="edit_shipping">
                @csrf
                <input type="hidden" name="id" value="{{ $shippingDetails->id }}">
                <div class="control-group">
                  <label class="control-label">Country</label>
                  <div class="controls">
                    <input type="text" readonly="" value="{{ $shippingDetails->country }}">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Shipping Charges <br> (0-500g)</label>
                  <div class="controls">
                    <input type="text" name="shipping_charges0_500g" id="shipping_charges0_500g"
                     value="{{ $shippingDetails->shipping_charges0_500g }}">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Shipping Charges (501_1000g)</label>
                  <div class="controls">
                    <input type="text" name="shipping_charges501_1000g" id="shipping_charges501_1000g"
                     value="{{ $shippingDetails->shipping_charges501_1000g }}">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Shipping Charges (1001_2000g)</label>
                  <div class="controls">
                    <input type="text" name="shipping_charges1001_2000g" id="shipping_charges1001_2000g"
                     value="{{ $shippingDetails->shipping_charges1001_2000g }}">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Shipping Charges (2001_5000g)</label>
                  <div class="controls">
                    <input type="text" name="shipping_charges2001_5000g" id="shipping_charges2001_5000g"
                     value="{{ $shippingDetails->shipping_charges2001_5000g }}">
                  </div>
                </div>
                <div class="form-actions">
                  <input type="submit" value="Update Shipping" class="btn btn-success">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    
@endsection
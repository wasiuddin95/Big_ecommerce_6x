<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Auth;
use Session;
use Image;
use DB;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\Coupon;
use App\User;
use App\Country;
use App\DeliveryAddress;
use App\Order;
use App\OrdersProduct;
use App\Exports\productsExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Carbon\Carbon;
use \Milon\Barcode\DNS1D;


class ProductsController extends Controller
{
    public function addProduct(Request $request)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (empty($data['category_id'])) {
                return redirect()->back()->with('flash_message_error','Category is missing!!');
            }
            $product = new Product;
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if (!empty($data['description'])) {
                $product->description = $data['description'];
            }else {
                $product->description = '';
            }

            if (!empty($data['sleeve'])) {
                $product->sleeve = $data['sleeve'];
            }else {
                $product->sleeve = '';
            }

            if (!empty($data['pattern'])) {
                $product->pattern = $data['pattern'];
            }else {
                $product->pattern = '';
            }

            if (!empty($data['care'])) {
                $product->care = $data['care'];
            }else {
                $product->care = '';
            }
            $product->price = $data['price'];
            if (!empty($data['weight'])) {
                $product->weight = $data['weight'];
            }else {
                $product->weight = 0;
            }

            // Upload Image
            if ($request->hasFile('image')) {
                echo $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {

                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Store image name in products table
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    // Store image name in products table
                    $product->image = $filename;
                }
            }
            // Upload Image End

            // Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = $request->file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path,$video_name);
                $product->video = $video_name;
            }
            
            // Update Upload Video
            // if(Request::hasFile('video')){

            //     $video_tmp = Request::file('video');
            //     $video_name = $video_tmp->getClientOriginalName();
            //     $video_path = public_path().'videos/';
            //     $video_tmp->move($video_path,$video_name);
            //     $product->video = $video_name;
            // }


            if (!empty($data['video'])) {
                $product->video = $data['video'];
            }else {
                $product->video = '';
            }

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }
            if (empty($data['feature_item'])) {
                $feature_item = 0;
            }else{
                $feature_item = 1;
            }
            $product->status = $status;
            $product->feature_item = $feature_item;
            $product->save();
            // return redirect()->back()->with('flash_message_success','Product has been added successfully!!');
            return redirect('/admin/view-products')->with('flash_message_success','Product has been added successfully!!');
        }

        // Categories Dropdown start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select Category</option>";
        foreach ($categories as $cat) {
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value= '".$sub_cat->id."'>&nbsp;--&nbsp;"
                     .$sub_cat->name."</option>";
            }
        }
        // Categories Dropdown end

        $sleeveArray = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');
        $patternArray = array('Checked','Plain','Printed','Solid');

        return view('admin.products.add_product')->with(compact('categories_dropdown','sleeveArray','patternArray'));
    }

    public function editProduct(Request $request, $id = null)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Upload Image
            if ($request->hasFile('image')) {
                echo $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {

                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Store image name in products table
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }
                }else {
                    $filename = $data['current_image'];
            }

            // Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = $request->file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path,$video_name);
                $videoName = $video_name;
            }elseif (!empty($data['current_video'])) {
                $videoName = $data['current_video'];
            }else {
                $videoName = '';
            }

            if (empty($data['description'])) {
                $data['description'] = '';
            }

            if (empty($data['care'])) {
                $data['care'] = '';
            }

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }
            if (empty($data['feature_item'])) {
                $feature_item = 0;
            }else{
                $feature_item = 1;
            }
            if (!empty($data['sleeve'])) {
                $sleeve = $data['sleeve'];
            }else{
                $sleeve = '';
            }
            if (!empty($data['pattern'])) {
                $pattern = $data['pattern'];
            }else{
                $pattern = '';
            }

            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],'product_name'
            =>$data['product_name'],'product_code'=>$data['product_code'],'product_color'
            =>$data['product_color'],'description'=>$data['description'],'care'=>$data['care'],
            'price'=>$data['price'],'weight'=>$data['weight'],'image'=>$filename,'video'=>$videoName,'sleeve'=>$sleeve,
            'pattern'=>$pattern,'status'=>$status,'feature_item'=>$feature_item]);
            return redirect()->back()->with('flash_message_success','Product has been updated successfully!');
        }

        // Get Product Details
        $productDetails = Product::where(['id'=>$id])->first();
        // Categories Dropdown start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select Category</option>";
        foreach ($categories as $cat) {
            if ($cat->id==$productDetails->category_id) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                if ($sub_cat->id==$productDetails->category_id) {
                    $selected = "selected";
                }else {
                    $selected = "";
                }
                $categories_dropdown .= "<option value= '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;"
                     .$sub_cat->name."</option>";
            }
        }
        // Categories Dropdown end

        $sleeveArray = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');
        $patternArray = array('Checked','Plain','Printed','Solid');

        return view('admin.products.edit_product')->with(compact('productDetails',
        'categories_dropdown','sleeveArray','patternArray'));
    }

    public function viewProducts()
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $products = Product::orderBy('id','DESC')->get();
        $products = json_decode(json_encode($products));
        foreach ($products as $key => $val) {
            $category_name = Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        // echo "<pre>"; print_r($products); die;
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProductImage($id)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $productImage = Product::where(['id'=>$id])->first();

        // Get Product Image Path
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        // Delete Large Image if not exists in Folder
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium Image if not exists in Folder
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if not exists in Folder
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }

        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Product Image has been deleted successfully!!');
    }

    public function deleteProductVideo($id)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        // Get Video name
        $productVideo = Product::select('video')->where('id',$id)->first();

        // Get Video Path
        $video_path = 'videos/';

        // Delete Video If exists in videos folder
        if (file_exists($video_path.$productVideo->video)) {
            unlink($video_path.$productVideo->video);
        }

        // Delete Video from Products table
        Product::where('id',$id)->update(['video'=>'']);

        return redirect()->back()->with('flash_message_success','Product Video has been deleted successfully!!');
    }

    public function deleteAltImage($id = null)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $productImage = ProductsImage::where(['id'=>$id])->first();

        // Get Product Image Path
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        // Delete Large Image if not exists in Folder
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium Image if not exists in Folder
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if not exists in Folder
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }

        ProductsImage::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product Alternate Image(s) has been deleted successfully!!');
    }

    public function deleteProduct($id = null)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been deleted successfully!!');
    }

    public function addAttributes(Request $request, $id = null)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        // $productDetails =json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            foreach($data['sku'] as $key => $val){
                if (!empty($val)) {
                    // Prevent duplicate SKU Check
                    $attrCountSKU = ProductsAttribute::where('sku',$val)->count();
                    if ($attrCountSKU>0) {
                        return redirect('/admin/add-attributes/'.$id)->with('flash_message_error','SKU already exists!!
                        Please add another SKU.');   
                    }

                    // Prevent duplicate Size Check
                    $attrCountSizes = ProductsAttribute::where(['product_id'=>$id,'size'=>
                    $data['size'][$key]])->count();
                    if ($attrCountSizes>0) {
                        return redirect('/admin/add-attributes/'.$id)->with('flash_message_error','
                        "'.$data['size'][$key].'" Size already exists for this product!! Please add another Size.');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }

            return redirect('/admin/add-attributes/'.$id)->with('flash_message_success','Product Attributes
                has been added successfully!!');
        }
        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function editAttributes(Request $request, $id = null)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        if ($request->isMethod('POST')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['idAttr'] as $key => $attr) {
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],
                'stock'=>$data['stock'][$key]]);
            }
        return redirect()->back()->with('flash_message_success','Products Attributes has been updated successfully!!');
        }
    }

    public function addImages(Request $request, $id = null) 
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        // $productDetails =json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;

        if ($request->isMethod('post')) {
            // Add Images 
            $data = $request->all();
            if ($request->hasFile('image')) {
                $files = $request->file('image');
                foreach ($files as $file) {
                    // Upload Images after resize
                    $image = new ProductsImage;
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600,600)->save($medium_image_path);
                    Image::make($file)->resize(300,300)->save($small_image_path);
                    $image->image = $filename;
                    $image->product_id = $data['product_id'];
                    $image->save();
                }
            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success','Product Images has been
            added successfully!!');
        }

        $productsImages = ProductsImage::where(['product_id'=>$id])->get();

        return view('admin.products.add_images')->with(compact('productDetails','productsImages'));
    }

    public function deleteAttribute($id = null)
    {
        if (Session::get('adminDetails')['products_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Attribute has been deleted successfully!!');
    }

    // Eita only Main categoryr Products Show Kore
    public function mainProducts($url)
    {
        // Show 404 page if Category URL does not exist
        $countCategory = Category::where(['url'=>$url,'status' =>1])->count();
        if ($countCategory == 0) {
            abort(404);
            // return view('products.404');
        }

        $categories = Category::with('categories')->where(['parent_id'=>0])->paginate(6);
        $categoryDetails = Category::where(['url' => $url])->first();
        $productsAll = Product::where(['products.category_id' => $categoryDetails->id])->paginate(6);
        $productsAll = array_flatten(json_decode(json_encode($productsAll)));
        // echo "<pre>"; print_r($productsAll); die;
        $breadcrumb = "<a href='/'>Home</a> / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";
        // echo "<pre>"; print_r($breadcrumb); die;
        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;
        // echo $categoryDetails->id; die;
        return view('products.listing')->with(compact('categories','productsAll','categoryDetails',
            'meta_title','meta_description','meta_keywords','breadcrumb'));
    }

    public function products($url = null)
    {
        // if (Session::get('adminDetails')['products_access']==0){
        //     return redirect('/admin/dashboard')->with('flash_message_error','You have no access
        //     for this module');
        // }
        // Show 404 page if Category URL does not exist
        $countCategory = Category::where(['url'=>$url,'status' =>1])->count();
        if ($countCategory == 0) {
            abort(404);
            // return view('products.404');
        }

        // echo $url; die;
        // Get all categories and sub categories
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $categoryDetails = Category::where(['url'=>$url])->first();
        $cat_ids = array($categoryDetails->id);
        if ($categoryDetails->parent_id == 0) {
            // if url is main category url
            $subCategories = Category::where('parent_id',$categoryDetails->id)->get();
            $subCategories = array_flatten(json_decode(json_encode($subCategories)));
            // echo "<pre>"; print_r($subCategories); die;
            foreach ($subCategories as $subcat) {
                $cat_ids[] = $subcat->id;
            }
            // print_r($cat_ids); die;
            $productsAll = Product::whereIn('products.category_id', $cat_ids)->where('products.status','1')
            ->orderBy('products.id','Desc');
            // $productsAll = json_decode(json_encode($productsAll));
            // echo "<pre>"; print_r($productsAll); die;
            $breadcrumb = "<a href='/'>Home</a> / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";
        }else {
            // if url is sub category url
            $productsAll = Product::where(['products.category_id'=>$categoryDetails->id])
            ->where('products.status','1')->orderBy('products.id','Desc');
            $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();
            $breadcrumb = "<a href='/'>Home</a> / <a href='".$mainCategory->url."'>".$mainCategory->name."</a>
             / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";  
        }

        if (!empty($_GET['color'])) {
            $colorArray = explode('-',$_GET['color']);
            $productsAll = $productsAll->whereIn('products.product_color',$colorArray);
        }

        if (!empty($_GET['sleeve'])) {
            $sleeveArray = explode('-',$_GET['sleeve']);
            $productsAll = $productsAll->whereIn('products.sleeve',$sleeveArray);
        }

        if (!empty($_GET['pattern'])) {
            $patternArray = explode('-',$_GET['pattern']);
            $productsAll = $productsAll->whereIn('products.pattern',$patternArray);
        }

        if (!empty($_GET['size'])) {
            $sizeArray = explode('-',$_GET['size']);
            $productsAll = $productsAll->join('products_attributes','products_attributes.product_id'
            ,'=','products.id')->select('products.*','products_attributes.product_id','products_attributes.size')
            ->groupBy('products_attributes.product_id')
            ->whereIn('products_attributes.size',$sizeArray);
        }

        $productsAll = $productsAll->paginate(6);
        // $productsAll = json_decode(json_encode($productsAll));
        // echo "<pre>"; print_r($productsAll); die;
        
        // $colorArray = array('Black','Blue','Red','Green','Yellow','Pink','Purple','Gold','Silver','White','Brown',
        // 'Orange','Grey');

        $colorArray = Product::select('product_color')->groupBy('product_color')->get();
        $colorArray = array_flatten(json_decode(json_encode($colorArray),true));
        // echo "<pre>"; print_r($colorArray); die;

        $sleeveArray = Product::select('sleeve')->where('sleeve','!=','')->groupBy('sleeve')->get();
        $sleeveArray = array_flatten(json_decode(json_encode($sleeveArray),true));
        // echo "<pre>"; print_r($sleeveArray); die;

        $patternArray = Product::select('pattern')->where('pattern','!=','')->groupBy('pattern')->get();
        $patternArray = array_flatten(json_decode(json_encode($patternArray),true));
        // echo "<pre>"; print_r($patternArray); die;

        $sizesArray = ProductsAttribute::select('size')->groupBy('size')->get();
        $sizesArray = array_flatten(json_decode(json_encode($sizesArray),true));
        // echo "<pre>"; print_r($sizesArray); die;

        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;
        // echo $categoryDetails->id; die;
        return view('products.listing')->with(compact('categories','productsAll','categoryDetails',
            'meta_title','meta_description','meta_keywords','url','colorArray','sleeveArray','patternArray'
            ,'sizesArray','breadcrumb'));
    }

    public function product($id = null)
    {

        // Show 404 page if product is disabled
        $productsCount = Product::where(['id'=>$id,'status'=>1])->count();
        if ($productsCount == 0) {
            abort(404);
        }

        $productDetails = Product::with('attributes')->where('id',$id)->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;

        $relatedProducts = Product::where('id','!=',$id)->where(['category_id'=>$productDetails->
        category_id])->get();
        // $relatedProducts = json_decode(json_encode($relatedProducts));

        // foreach ($relatedProducts->chunk(3) as $chunk) {
        //     foreach ($chunk as $item) {
        //         echo $item; echo "<br>";
        //     }
        //     echo "<br><br><br>";
        // }
        // die;
        // echo "<pre>"; print_r($relatedProducts); die;

        // Get all categories and sub categories
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        // $categories = json_decode(json_encode($categories));
        // echo "<pre>"; print_r($categories); die; 

        // Get Product Alternate Images
        $productAltImages = ProductsImage::where('product_id',$id)->get();
        // $productAltImages = json_decode(json_encode($productAltImages));
        // echo "<pre>"; print_r($productAltImages); die; 

        // Get Product Alt Images
        $productAltImages = ProductsImage::where('product_id',$id)->get();

        $categoryDetails = Category::where('id',$productDetails->category_id)->first();
        
        if ($categoryDetails->parent_id == 0) {
            $breadcrumb = "<a style='color:#333;' href='/'>Home</a> / <a style='color:#333;' href='/products/".$categoryDetails->url."'>".$categoryDetails->name."</a> 
            / ".$productDetails->product_name;
        }else {
            $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();
            $breadcrumb = "<a style='color:#333;' href='/'>Home</a> / <a style='color:#333;'
             href='/products/".$mainCategory->url."'>".$mainCategory->name."</a>
             / <a style='color:#333;' href='/products/".$categoryDetails->url."'>"
             .$categoryDetails->name."</a> / ".$productDetails->product_name;  
        }

        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock'); 

        $meta_title = $productDetails->product_name;
        $meta_description = $productDetails->description;
        $meta_keywords = $productDetails->product_name;
        return view('products.detail')->with(compact('productDetails','categories','productAltImages','total_stock'
        ,'relatedProducts','meta_title','meta_description','meta_keywords','breadcrumb'));
    }

    public function getProductPrice(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $proArr = explode("-",$data['idSize']);
        // echo $proArr[0]; echo $proArr[1]; die;
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        $getCurrencyRates = Product::getCurrencyRates($proAttr->price);
        echo $proAttr->price."-".$getCurrencyRates['USD_Rate']."-".$getCurrencyRates['GBP_Rate']
        ."-".$getCurrencyRates['EUR_Rate'];
        echo "#";
        echo $proAttr->stock;
    }

    public function addtocart(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

        if (!empty($data['wishListButton']) && $data['wishListButton']=="Wish List" ) {
            // echo "Wish List is selected"; die;

            // Check User is logged in
            if (!Auth::check()) {
                return redirect()->back()->with('flash_message_error','Please login to to add product in your 
                Wish List!!');
            }

            // Check Size is Selected
            if (empty($data['size'])) {
                return redirect()->back()->with('flash_message_error','Please select size to
                 add product in your Wish List!!');
            }

            // Get Product Size
            $sizeIDArr = explode("-",$data['size']);
            $product_size = $sizeIDArr[1];

            // Get Product Price
            $proPrice = ProductsAttribute::where(['product_id'=>$data['product_id'],
            'size'=>$product_size])->first();
            $product_price = $proPrice->price;
            
            // Get User Email/Username
            $user_email = Auth::user()->email;

            // Set Quantity as 1;
            $quantity = 1;

            // Get Current Date
            $created_at = Carbon::now();

            $wishListCount = DB::table('wish_list')->where(['user_email'=>$user_email,'product_id'=>
                $data['product_id'],'product_color'=>$data['product_color']
                ,'size'=>$product_size])->count();

            if ($wishListCount>0) {
                return redirect()->back()->with('flash_message_error','This Product is already exists 
                in Wish List!!');
            }else {
                // Insert Product in Wish List
                DB::table('wish_list')->insert([
                    'product_id'=>$data['product_id'],
                    'product_name'=>$data['product_name'],
                    'product_code'=>$data['product_code'],
                    'product_color'=>$data['product_color'],
                    'price'=>$product_price,
                    'size'=>$product_size,
                    'quantity'=>$quantity,
                    'user_email'=>$user_email,
                    'created_at'=>$created_at
                ]);
                return redirect()->back()->with('flash_message_success','Product has been added
                    in Wish List');
            }

        }else {
            
            // If product added from wish list
            if (!empty($data['cartButton']) && $data['cartButton']=="Add to Cart" ) {
                $data['quantity'] = 1;
            }

            // Check Product Stock is available or not
            $product_size = explode("-",$data['size']);
            // echo $product_size[1]; die;
            $getProductStock = ProductsAttribute::where(['product_id'=>$data['product_id']
                ,'size'=>$product_size[1]])->first();
            // echo $getProductStock->stock; die;

            if ($getProductStock->stock<$data['quantity']) {
                return redirect()->back()->with('flash_message_error','Required Quantity is not available!');
            }

            if (empty(Auth::user()->email)) {
                $data['user_email'] = '';
            }else {
                $data['user_email'] = Auth::user()->email;
            }

            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = str_random(40); 
                Session::put('session_id',$session_id);
            }

            $sizeIDArr = explode("-",$data['size']);
            $product_size = $sizeIDArr[1];

            if (empty(Auth::check())) {
                $countProducts = DB::table('carts')->where(['product_id'=>$data['product_id'],'product_color'=>
            $data['product_color'],'size'=>$product_size,'session_id'=>$session_id])->count();
            
                if ($countProducts>0) {
                    return redirect()->back()->with('flash_message_error','Product already exists in Cart!!');
                }
            }else {
                $countProducts = DB::table('carts')->where(['product_id'=>$data['product_id'],'product_color'=>
                $data['product_color'],'size'=>$product_size,'user_email'=>$data['user_email']])->count();
            
                if ($countProducts>0) {
                    return redirect()->back()->with('flash_message_error','Product already exists in Cart!!');
                }
            }

                $getSKU = ProductsAttribute::select('sku')->where(['product_id'=>$data['product_id'],
                'size'=>$product_size])->first();
                // echo $getSKU['sku']; die;

                DB::table('carts')->insert(['product_id'=>$data['product_id'],'product_name'=>$data['product_name']
                ,'product_code'=>$getSKU['sku'],'product_color'=>$data['product_color'],'price'=>$data['price']
                ,'size'=>$product_size,'quantity'=>$data['quantity'],'user_email'=>$data['user_email']
                ,'session_id'=>$session_id]);

        return redirect('cart')->with('flash_message_success', 'Product has been added in Cart!!');
        }
    }

    public function cart()
    {
        
        if (Auth::check()) {
            $user_email = Auth::user()->email;
            $userCart = DB::table('carts')->where(['user_email'=>$user_email])->get();
        }else {
            $session_id = Session::get('session_id');
            $userCart = DB::table('carts')->where(['session_id'=>$session_id])->get();
        }
        
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        // echo "<pre>"; print_r($userCart); die;
        $meta_title = "Shopping Cart - Great E-commerce Site";
        $meta_description = "View Shopping Cart Of Great E-commerce Site";
        $meta_keywords = "shopping cart, e-commerce website";
        return view('products.cart')->with(compact('userCart','meta_title','meta_description','meta_keywords'));
    }

    public function wishList()
    {
        if (Auth::check()) {
            $user_email = Auth::user()->email;
            $userWishList = DB::table('wish_list')->where(['user_email'=>$user_email])->get(); 
            foreach ($userWishList as $key => $product) {
                $productDetails = Product::where('id',$product->product_id)->first();
                $userWishList[$key]->image = $productDetails->image;
            }
        }else {
            $userWishList = array();
        } 
        $meta_title = "Wish List - Great E-commerce Site";
        $meta_description = "View Wish List Of Great E-commerce Site";
        $meta_keywords = "Wish List, e-commerce website";
        return view('products.wish_list')->with(compact('userWishList','meta_title','meta_description','meta_keywords'));
    }

    public function deleteCartProduct($id = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        DB::table('carts')->where('id',$id)->delete();
        return redirect('cart')->with('flash_message_success','Product has been deleted from Cart!!');
    }

    public function updateCartQuantity($id = null, $quantity = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        $getCartDetails = DB::table('carts')->where('id',$id)->first();
        $getAttributeStock = ProductsAttribute::where('sku',$getCartDetails->product_code)->first();
        echo $getAttributeStock->stock; echo "--";
        $updated_quantity = $getCartDetails->quantity+$quantity;
        if ($getAttributeStock->stock >= $updated_quantity) {
            DB::table('carts')->where('id',$id)->increment('quantity',$quantity);
            return redirect('cart')->with('flash_message_success','Product Quantity has benn Updated Successfully!!');
        }else{
            return redirect('cart')->with('flash_message_error','Required Product Quantity is not available!!');
        }
    }

    public function applyCoupon(Request $request)
    {

        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $couponCount = Coupon::where('coupon_code',$data['coupon_code'])->count();
        if ($couponCount == 0) {
            return redirect()->back()->with('flash_message_error','This coupon does not exists!!');
        }else {
            // with perform other checks like Active/Inactive, Expiry date...

            // Get COupon Details
            $couponDetails = Coupon::where('coupon_code',$data['coupon_code'])->first();

            // If Coupon is Inactive
            if ($couponDetails->status==0) {
                return redirect()->back()->with('flash_message_error','This coupon is not active!!');
            }

            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if ($expiry_date < $current_date) {
                return redirect()->back()->with('flash_message_error','This coupon is Expired!!');
            }

            // Coupon is Valid for Discount

            // Get Cart Total Amount
            $session_id = Session::get('session_id');
            // $userCart = DB::table('carts')->where(['session_id'=>$session_id])->get();

            if (Auth::check()) {
                $user_email = Auth::user()->email;
                $userCart = DB::table('carts')->where(['user_email'=>$user_email])->get();
            }else {
                $session_id = Session::get('session_id');
                $userCart = DB::table('carts')->where(['session_id'=>$session_id])->get();
            }

            $total_amount = 0;
            foreach ($userCart as $item) {
                $total_amount = $total_amount + ($item->price * $item->quantity);
            }

            // Check if amount type is fixed or percentage
            if ($couponDetails->amount_type=="Fixed") {
                $couponAmount = $couponDetails->amount;
            }else {
                $couponAmount = $total_amount * ($couponDetails->amount/100);
            }

            // Add Coupon Code & Amount is Session
            Session::put('CouponAmount',$couponAmount);
            Session::put('CouponCode',$data['coupon_code']);

            return redirect()->back()->with('flash_message_success','Coupon code successfully applied. 
            You are availing discount!!');

        }
    }

    public function checkout(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        // Check if Shipping Address exists
        $shippingCount = DeliveryAddress::where('user_id',$user_id)->count();
        // $shippingCount = json_decode(json_encode($shippingCount));
        // echo "<pre>"; print_r($shippingCount); die;

        $shippingDetails = array();
        // $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        // $shippingDetails = json_decode(json_encode($shippingDetails));
        // echo "<pre>"; print_r($shippingDetails); die;
        if ($shippingCount>0) {
            $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        }

        // Update cart table with user email
        $session_id = Session::get('session_id');
        DB::table('carts')->where(['session_id'=>$session_id])->update(['user_email'=>$user_email]);

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // Return to Checkout page if any of the field is empty
            if (empty($data['billing_name']) || empty($data['billing_address']) || empty($data['billing_city'])
            || empty($data['billing_state']) || empty($data['billing_country']) || empty($data['billing_pincode'])
            || empty($data['billing_mobile']) || empty($data['shipping_name']) || empty($data['shipping_address'])
            || empty($data['shipping_city']) || empty($data['shipping_state']) || empty($data['shipping_country'])
            || empty($data['shipping_pincode']) || empty($data['shipping_mobile']) ){
                return redirect()->back()->with('flash_message_error','Please fill all fields to Checkout!!');
            }

            // Update User details
            User::where('id',$user_id)->update(['name'=>$data['billing_name'],'address'=>$data['billing_address']
            ,'city'=>$data['billing_city'],'state'=>$data['billing_state'],'country'=>$data['billing_country']
            ,'pincode'=>$data['billing_pincode'],'mobile'=>$data['billing_mobile']]);
           
            if ($shippingCount>0) {
                // Update Shippnig Address
                DeliveryAddress::where('user_id',$user_id)->update(['name'=>$data['shipping_name'],'address'=>$data['shipping_address']
                ,'city'=>$data['shipping_city'],'state'=>$data['shipping_state'],'country'=>$data['shipping_country']
                ,'pincode'=>$data['shipping_pincode'],'mobile'=>$data['shipping_mobile']]);
            }else {
                // Add new shipping address
                $shipping = new DeliveryAddress;
                $shipping->user_id = $user_id;
                $shipping->user_email = $user_email;
                $shipping->name = $data['shipping_name'];
                $shipping->address = $data['shipping_address'];
                $shipping->city = $data['shipping_city'];
                $shipping->state = $data['shipping_state'];
                $shipping->country = $data['shipping_country'];
                $shipping->pincode = $data['shipping_pincode'];
                $shipping->mobile = $data['shipping_mobile'];
                $shipping->save();
            }

            $pincodeCount = DB::table('pincodes')->where('pincode',$data['shipping_pincode'])->count();
            if ($pincodeCount == 0) {
                return redirect()->back()->with('flash_message_error','Your location is not available for delivery.
                 Please enter another location.');
            }

            return redirect()->action('ProductsController@orderReview');
        }
        $meta_title = "Checkout - Great E-commerce Site";
        return view('products.checkout')->with(compact('userDetails','countries','shippingDetails','meta_title'));
        // 
    }

    public function orderReview()
    {
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id',$user_id)->first();
        $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        $shippingDetails = json_decode(json_encode($shippingDetails));
        $userCart = DB::table('carts')->where(['user_email'=>$user_email])->get();
        $total_weight = 0;
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
            $total_weight = $total_weight + $productDetails->weight;
        }
        // echo "<pre>"; print_r($userCart); die;
        $codpincodeCount = DB::table('cod_pincodes')->where('pincode',$shippingDetails->pincode)->count();
        $prepaidpincodeCount = DB::table('prepaid_pincodes')->where('pincode',$shippingDetails->pincode)->count();

        // Fetch Shipping Charges
        $shippingCharges = Product::getShippingCharges($total_weight,$shippingDetails->country);
        Session::put('ShippingCharges',$shippingCharges);

        $meta_title = "Order Review - Great E-commerce Site";
        return view('products.order_review')->with(compact('userDetails','shippingDetails','userCart','meta_title',
        'codpincodeCount','prepaidpincodeCount','shippingCharges'));
    }

    public function placeOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;

            // Prevent Out of stock products from ordering
            $userCart = DB::table('carts')->where('user_email',$user_email)->get();
            foreach ($userCart as $cart) {

                $getAttributeCount = Product::getAttributeCount($cart->product_id,$cart->size);
                if ($getAttributeCount==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);
                    return redirect('/cart')->with('flash_message_error','One of the product is
                    not available. Try again!!');
                }

                $product_stock = Product::getProductStock($cart->product_id,$cart->size);
                if ($product_stock==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);
                    return redirect('/cart')->with('flash_message_error','This Product is Sold Out,
                     removed from cart. Please try placing order again.');
                }
                // echo "Original Stock: ".$product_stock;
                // echo "Demanded Stock: ".$cart->quantity; die;
                if ($cart->quantity>$product_stock) {
                    return redirect('/cart')->with('flash_message_error','Reduce Product Stock
                    and Please try again.');
                }

                $product_status = Product::getProductStatus($cart->product_id);
                if ($product_status==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);
                    return redirect('/cart')->with('flash_message_error','Disabled product
                     removed from cart. Please try placing order again.');
                }

                $getCategoryId = Product::select('category_id')->where('id',$cart->product_id)->first();
                $category_status = Product::getCategoryStatus($getCategoryId->category_id);
                if ($category_status==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);
                    return redirect('/cart')->with('flash_message_error','One of the 
                    product category is disabled. Please try placing order again!!');
                }

            }

            // Get Shipping Address of User
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();
            // echo "<pre>"; print_r($data); die;

            $pincodeCount = DB::table('pincodes')->where('pincode',$shippingDetails->pincode)->count();
            if ($pincodeCount == 0) {
                return redirect()->back()->with('flash_message_error','Your location is not available for delivery.
                 Please enter another location.');
            }

            if (empty(Session::get('CouponCode'))) {
                $coupon_code = '';
            }else {
                $coupon_code = Session::get('CouponCode');
            }

            if (empty(Session::get('CouponAmount'))) {
                $coupon_amount = '0';
            }else {
                $coupon_amount = Session::get('CouponAmount');
            }

            // Fetch Shipping Charges
            // $shippingCharges = Product::getShippingCharges($shippingDetails->country);

            $grand_total = Product::getGrandTotal();

            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->country = $shippingDetails->country;
            $order->pincode = $shippingDetails->pincode;
            $order->mobile = $shippingDetails->mobile;
            $order->coupon_code = $coupon_code;
            $order->coupon_amount = $coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->shipping_charges = Session::get('ShippingCharges');
            $order->grand_total = $grand_total;
            $order->save();

            $order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('carts')->where(['user_email'=>$user_email])->get();
            foreach ($cartProducts as $pro) {
                $cartPro = new OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_color = $pro->product_color;
                $cartPro->product_size = $pro->size;
                $product_price = Product::getProductPrice($pro->product_id,$pro->size);
                $cartPro->product_price = $product_price;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();

                // Reduce Stock Script Starts
                $getProductStock = ProductsAttribute::where('sku',$pro->product_code)->first();
                // echo "Original Stock: ".$getProductStock->stock;
                // echo "Stock to reduce: ".$pro->quantity; 
                $newStock = $getProductStock->stock - $pro->quantity;
                if ($newStock<0) {
                    $newStock = 0;
                }
                ProductsAttribute::where('sku',$pro->product_code)->update(['stock'=>$newStock]);
                // Reduce Stock Script Ends
            }

            Session::put('order_id',$order_id);
            Session::put('grand_total',$grand_total);

            if ($data['payment_method']=="COD") {

                $productDetails = Order::with('orders')->where('id',$order_id)->first();
                $productDetails = json_decode(json_encode($productDetails),true);
                // echo "<pre>"; print_r($productDetails); die;

                $userDetails = User::where('id',$user_id)->first();
                $userDetails = json_decode(json_encode($userDetails),true);
                // echo "<pre>"; print_r($userDetails); die;

                // Code for Order Email Start
                $email = $user_email;
                $messageData = [
                    'email' => $email,
                    'name' => $shippingDetails->name,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                ];
                Mail::send('emails.order',$messageData,function($message) use($email){
                    $message->to($email)->subject('Order Placed - Great E-commerce Site');
                });
                // Code for Order Email End

                // COD - Redirect user to thanks page after saving order
                return redirect('/thanks');
            }else {
                // COD - Redirect user to thanks page after saving order
                return redirect('/paypal');
            }
            

        }
    }

    public function thanks(Request $request)
    {
        $user_email = Auth::user()->email;
        DB::table('carts')->where('user_email',$user_email)->delete();
        return view('orders.thanks');
    }

    public function thanksPaypal()
    {
        return view('orders.thanks_paypal');
    }

    public function paypal(Request $request)
    {
        $user_email = Auth::user()->email;
        DB::table('carts')->where('user_email',$user_email)->delete();
        return view('orders.paypal');
    }

    public function cancelPaypal()
    {
        return view('orders.cancel_paypal');
    }

    public function userOrders()
    {
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id',$user_id)->orderBy('id','DESC')->get();
        // $orders = json_decode(json_encode($orders));
        // echo "<pre>"; print_r($orders); die;
        return view('orders.users_orders')->with(compact('orders'));
    }

    public function userOrderDetails($order_id)
    {
        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;

        return view('orders.user_order_details')->with(compact('orderDetails'));
    }

    public function viewOrders()
    {
        if (Session::get('adminDetails')['orders_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $orders = Order::with('orders')->orderBy('id','Desc')->get();
        $orders = json_decode(json_encode($orders));
        // echo "<pre>"; print_r($orders); die;
        return view('admin.orders.view_orders')->with(compact('orders'));
    }

    public function viewOrderDetails($order_id)
    {
        if (Session::get('adminDetails')['orders_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        // $userDetails = json_decode(json_encode($userDetails));
        // echo "<pre>"; print_r($userDetails); die;
        return view('admin.orders.order_details')->with(compact('orderDetails','userDetails'));
    }

    public function viewOrderInvoice($order_id)
    {
        if (Session::get('adminDetails')['orders_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        // $userDetails = json_decode(json_encode($userDetails));
        // echo "<pre>"; print_r($userDetails); die;
        return view('admin.orders.order_invoice')->with(compact('orderDetails','userDetails'));
    }

    public function viewPDFInvoice($order_id)
    {
        if (Session::get('adminDetails')['orders_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        // $userDetails = json_decode(json_encode($userDetails));
        // echo "<pre>"; print_r($userDetails); die;
        
        $output = '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <title>Example 1</title>
            <style>
            .clearfix:after {
                content: "";
                display: table;
                clear: both;
              }
              
              a {
                color: #5D6975;
                text-decoration: underline;
              }
              
              body {
                position: relative;
                width: 24cm;  
                height: 29.7cm; 
                margin: 0 auto; 
                color: #001028;
                background: #FFFFFF; 
                font-family: Arial, sans-serif; 
                font-size: 12px; 
                font-family: Arial;
              }
              
              header {
                padding: 10px 0;
                margin-bottom: 30px;
              }
              
              #logo {
                text-align: center;
                margin-bottom: 10px;
              }
              
              #logo img {
                width: 45px;
                background-image: url("/images/backend_images/logo3.png");
              }
              
              h1 {
                border-top: 1px solid  #5D6975;
                border-bottom: 1px solid  #5D6975;
                color: #5D6975;
                font-size: 2.4em;
                line-height: 1.4em;
                font-weight: normal;
                text-align: center;
                margin: 0 0 20px 0;
                background: url(dimension.png);
              }
              
              #project {
                float: left;
              }
              
              #project span {
                color: #5D6975;
                text-align: right;
                width: 52px;
                margin-right: 10px;
                display: inline-block;
                font-size: 0.8em;
              }
              
              #company {
                float: right;
                text-align: right;
              }
              
              #project div,
              #company div {
                white-space: nowrap;        
              }
              
              table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 10px;
              }
              
              table tr:nth-child(2n-1) td {
                background: #F5F5F5;
              }
              
              table th,
              table td {
                text-align: center;
              }
              
              table th {
                padding: 5px 10px;
                color: #5D6975;
                border-bottom: 1px solid #C1CED9;
                white-space: nowrap;        
                font-weight: normal;
              }
              
              table .service,
              table .desc {
                text-align: left;
              }
              
              table td {
                padding: 10px;
                text-align: right;
              }
              
              table td.service,
              table td.desc {
                vertical-align: top;
              }
              
              table td.unit,
              table td.qty,
              table td.total {
                font-size: 1.2em;
              }
              
              table td.grand {
                border-top: 1px solid #5D6975;;
              }
              
              footer {
                color: #5D6975;
                width: 100%;
                height: 30px;
                position: absolute;
                bottom: 0;
                border-top: 1px solid #C1CED9;
                padding: 8px 0;
                text-align: center;
              }
            </style>
          </head>
          <body>
            <header class="clearfix">
              <div id="logo">
                <img src="">
                <h3>Great E-Commerce</h3>
              </div>
              <h1>INVOICE #'.$orderDetails->id.'</h1>
              <div id="project" class="clearfix">
              <div><span>Order ID</span> '.$orderDetails->id.' </div>
              <div><span>Order Date</span> '.$orderDetails->created_at.' </div>
              <div><span>Order Amount</span> '.$orderDetails->grand_total.' </div>
              <div><span>Order Status</span> '.$orderDetails->order_status.' </div>
              <div><span>Payment Method</span> '.$orderDetails->payment_method.' </div>
              </div>
              <div id="project" style="float:right;">
                <div><strong>Shipping Address</strong></div>
                <div> '.$orderDetails->name.' </div>
                <div> '.$orderDetails->address.' </div>
                <div> '.$orderDetails->city.' </div>
                <div> '.$orderDetails->state.' </div>
                <div> '.$orderDetails->pincode.' </div>
                <div> '.$orderDetails->country.' </div>
                <div> '.$orderDetails->mobile.' </div>
              </div>
            </header>
            <main>
              <table>
                <thead>
                    <tr>
                        <td><strong>Product Name &<br> Product Code</strong></td>
                        <td class="text-center"><strong>Product Size &<br> Product Color</strong></td>
                        <td class="text-center"><strong>Product Price &<br> Product Qty</strong></td>
                        <td class="text-right"><strong>Totals</strong></td>
                    </tr>
                </thead>
                <tbody>';
                $subtotal = 0; 
                foreach ($orderDetails->orders as $pro){
                $output .= '<tr>
                                <td class="text-left">'.$pro->product_name.' <br>
                                    ('.$pro->product_code.')</td>
                                <td class="text-center">'.$pro->product_size.' <br>
                                ('.$pro->product_color.')</td>
                                <td class="text-center">'.$pro->product_price.'/Tk <br>
                                ('.$pro->product_qty.')</td>
                                <td class="text-right">'.$pro->product_price * $pro->product_qty.'/Tk</td>
                            </tr>';
                $subtotal = $subtotal + ($pro->product_price * $pro->product_qty); }
                $output .= '<tr>
                    <td colspan="3">SUBTOTAL</td>
                    <td class="total">'.$subtotal.'/Tk</td>
                  </tr>
                  <tr>
                    <td colspan="3">SHIPPING CHARGES (+)</td>
                    <td class="total">'.$orderDetails->shipping_charges.'/Tk</td>
                  </tr>
                  <tr>
                    <td colspan="3">COUPON DISCOUNT (-)</td>
                    <td class="total">'.$orderDetails->coupon_amount.'/Tk</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="grand total">GRAND TOTAL</td>
                    <td class="grand total">'.$orderDetails->grand_total.'/Tk</td>
                  </tr>
                </tbody>
              </table>
            </main>
            <footer>
              Invoice was created on a computer and is valid without the signature and seal.
            </footer>
          </body>
        </html>';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($output);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream();

    }

    public function updateOrderStatus(Request $request)
    {
        if (Session::get('adminDetails')['orders_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            Order::where('id',$data['order_id'])->update(['order_status'=>$data['order_status']]);
            return redirect()->back()->with('flash_message_success','Order Status has been updated successfully');
        }
    }

    public function filter(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

        $colorUrl = "";
        if (!empty($data['colorFilter'])) {
            foreach ($data['colorFilter'] as $color) {
                if (empty($colorUrl)) {
                    $colorUrl = "&color=".$color;
                }else {
                    $colorUrl .= "-".$color;
                }
            }
        }

        $sleeveUrl = "";
        if (!empty($data['sleeveFilter'])) {
            foreach ($data['sleeveFilter'] as $sleeve) {
                if (empty($sleeveUrl)) {
                    $sleeveUrl = "&sleeve=".$sleeve;
                }else {
                    $sleeveUrl .= "-".$sleeve;
                }
            }
        }

        $patternUrl = "";
        if (!empty($data['patternFilter'])) {
            foreach ($data['patternFilter'] as $pattern) {
                if (empty($patternUrl)) {
                    $patternUrl = "&pattern=".$pattern;
                }else {
                    $patternUrl .= "-".$pattern;
                }
            }
        }

        $sizeUrl = "";
        if (!empty($data['sizeFilter'])) {
            foreach ($data['sizeFilter'] as $size) {
                if (empty($sizeUrl)) {
                    $sizeUrl = "&size=".$size;
                }else {
                    $sizeUrl .= "-".$size;
                }
            }
        }

        $finalUrl = "products/".$data['url']."?".$colorUrl.$sleeveUrl.$patternUrl.$sizeUrl;
        return redirect::to($finalUrl);

    }

    public function searchProducts(Request $request)
    {
        if ($request->isMethod('GET')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $categories = Category::with('categories')->where(['parent_id' => 0])->get();

            $search_product = $data['product'];

            // $productsAll = Product::where('product_name','like','%'.$search_product.'%')->orwhere
            //     ('product_code',$search_product)->where('status',1)->paginate();

            $productsAll = Product::where(function($query) use($search_product){
                $query->where('product_name','like','%'.$search_product.'%')
                ->orWhere('product_code','like','%'.$search_product.'%')
                ->orWhere('description','like','%'.$search_product.'%')
                ->orWhere('product_color','like','%'.$search_product.'%');
            })->where('status',1)->get();

            $breadcrumb = "<a href='/'>Home</a> / ".$search_product;

            return view('products.listing')->with(compact('categories','productsAll','search_product','breadcrumb'));

        }
    }

    public function checkPincode(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            echo $pincodeCount = DB::table('pincodes')->where('pincode',$data['pincode'])->count();
        }
    }

    public function exportProducts()
    {
        return Excel::download(new productsExport,'products.xlsx');
    }

    public function deleteWishListProduct($id)
    {
        DB::table('wish_list')->where('id',$id)->delete();
        return redirect()->back()->with('flash_message_success','Product has been deleted 
            from Wish List');
    }

    public function viewOrdersCharts()
    {
        $current_month_orders = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)->count();
        $last_month_orders = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(1))->count();
        $last_to_last_month_orders = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(2))->count();
        return view('admin.products.view_orders_charts')->with(compact('current_month_orders','last_month_orders',
            'last_to_last_month_orders'));
        
    }








}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Banner;

class IndexController extends Controller
{
    public function index()
    {
        // In Ascending order (by default)
        // $productsAll = Product::get();

        // In Descending order
        // $productsAll = Product::orderBy('id','desc')->get();

        // in Random order()
        $productsAll = Product::inRandomOrder()->where('status',1)->where('feature_item',1)->paginate(6);
        // $productsAll = json_decode(json_encode($productsAll));
        // dump($productsAll);

        // Get all categories and sub categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        // $categories = json_decode(json_encode($categories));
        // echo "<pre>"; print_r($categories); die;
        // $categories_menu = "";
        // foreach($categories as $cat){
        //     $categories_menu .= "<div class='panel-heading'>
        //                             <h4 class='panel-title'>
        //                                 <a data-toggle='collapse' data-parent='#accordian' href='#".$cat->id."'>
        //                                     <span class='badge pull-right'><i class='fa fa-plus'></i></span>
        //                                     ".$cat->name."
        //                                 </a>
        //                             </h4>
        //                         </div>
        //     <div id='".$cat->id."' class='panel-collapse collapse'>
        //         <div class='panel-body'>
        //             <ul>";
        //             $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
        //             foreach ($sub_categories as $subcat) {
        //                 $categories_menu .= "<li><a href='".$subcat->url."'>".$subcat->name."</a></li>";
        //             }  
        //             $categories_menu .= "</ul>
        //         </div>
        //     </div>
        //     ";
        // }
        // ,'categories_menu'

        $banners = Banner::where('status','1')->get();

        // Meta Tags Start
        $meta_title = "Great E-commerce Site";
        $meta_description = "Online Shopping Site For Men and Women and Kids Clothes";
        $meta_keywords = "eshop website, online shopping, men clothes, women clothes, best products,";
        // Meta Tags End

        return view('index')->with(compact('productsAll','categories','banners','meta_title'
            ,'meta_description','meta_keywords'));
    }
}

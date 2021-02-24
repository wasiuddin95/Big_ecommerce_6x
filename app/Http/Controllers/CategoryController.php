<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if (empty($data['status'])) {
                $status = 0;
            }else {
                $status = 1;
            }
            if (empty($data['meta_title'])) {
                $data['meta_title'] = "";
            }
            if (empty($data['meta_description'])) {
                $data['meta_description'] = "";
            }
            if (empty($data['meta_keywords'])) {
                $data['meta_keywords'] = "";
            }

            $category = new Category;
            $category->name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'];
            $category->meta_description = $data['meta_description'];
            $category->meta_keywords = $data['meta_keywords'];
            $category->status = $status;
            $category->save();
            return redirect('/admin/view-category')->with('flash_message_success','Category Added Successfully!');
        }

        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request,$id = null)
    {
        if (Session::get('adminDetails')['categories_view_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if (empty($data['status'])) {
                $status = 0;
            }else {
                $status = 1;
            }
            if (empty($data['meta_title'])) {
                $data['meta_title'] = "";
            }
            if (empty($data['meta_description'])) {
                $data['meta_description'] = "";
            }
            if (empty($data['meta_keywords'])) {
                $data['meta_keywords'] = "";
            }

            Category::where(['id'=>$id])->update(['name'=>$data['category_name'],'description'
            =>$data['description'],'url'=>$data['url'],'meta_title'=>$data['meta_title'],'parent_id'=>$data['parent_id']
            ,'meta_description'=>$data['meta_description'],'meta_keywords'=>$data['meta_keywords'],'status'=>$status]);
            return redirect()->back()->with('flash_message_success','Category Updated Successfully!!');
        }
        $categoryDetails = Category::where(['id'=>$id])->first();
        // $categoryDetails = json_decode(json_encode($categoryDetails));
        // echo "<pre>"; print_r($categoryDetails); die;
        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.edit_category')->with(compact('categoryDetails','levels'));
    }

    public function deleteCategory($id = null)
    {
        if (Session::get('adminDetails')['categories_view_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        if (!empty($id)) {
            Category::where(['id'=>$id])->delete();
            return redirect()->back()->with('flash_message_success','Category Deleted Successfully!!');
        }

        // $category = Category::find($id);
        // $category->delete();
        // return redirect()->back()->with('flash_message_success','Data Deleted Successfully');

    }

    public function viewCategories()
    {
        if (Session::get('adminDetails')['categories_view_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access
            for this module');
        }
        $categories = Category::get();
        // $categories = json_decode(json_encode($categories));
        // echo "<pre>"; print_r($categories); die;
        return view('admin.categories.view_category')->with(compact('categories'));
    }











}

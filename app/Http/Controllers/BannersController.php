<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use DB;
use App\Banner;

class BannersController extends Controller
{
    public function addBanner(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $banner = new Banner;
            $banner->title = $data['title'];
            $banner->link = $data['link'];

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }
            $banner->status = $status;

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$filename;
                    // Store image name in banners table
                    Image::make($image_tmp)->resize(1140,441)->save($banner_path);

                    // Store image name in products table
                    $banner->image = $filename;
                }
            }

            // Laravel Validation
            $validatedData = $request->validate([
                'link' => 'required',
            ]);

            
            $banner->save();
            // return redirect()->back()->with('flash_message_success','Banner Image has been added successfully!!');
            return redirect('/admin/view-banners')->with('flash_message_success','Banner Image has been added successfully!!');

        }
        return view('admin.banners.add_banner');
    }

    public function editBanner(Request $request,$id = null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            if (empty($data['title'])) {
                $data['title'] = '';
            }

            if (empty($data['link'])) {
                $data['link'] = '';
            }

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$filename;
                    // Store image name in banners table
                    Image::make($image_tmp)->resize(1140,441)->save($banner_path);
                }
            }elseif (!empty($data['current_image'])) {
                $filename = $data['current_image'];
            }else {
                $filename = '';
            }
            
            Banner::where('id',$id)->update(['status'=>$status,'title'=>$data['title'],'link'=>$data['link'],
            'image'=>$filename]);
            return redirect()->back()->with('flash_message_success','Banner has been edited Successfully!!');

        }
        $bannerDetails = Banner::where('id',$id)->first();
        return view('admin.banners.edit_banner')->with(compact('bannerDetails'));
    }

    public function viewBanners()
    {
        $banners = Banner::get();
        return view('admin.banners.view_banners')->with(compact('banners'));
    }

    public function deleteBanner($id = null)
    {
        Banner::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Banner has been deleted Successfully!!');
    }














}

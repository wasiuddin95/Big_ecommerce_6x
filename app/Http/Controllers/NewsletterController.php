<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewsletterSubscriber;
use App\Exports\subscribersExport;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    public function checkSubscriber(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $subscriberCount = NewsletterSubscriber::where('email',$data['subscriber_email'])->count();
            if ($subscriberCount>0) {
                echo "exists";
            }

        }
    }

    public function addSubscriber(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $subscriberCount = NewsletterSubscriber::where('email',$data['subscriber_email'])->count();
            if ($subscriberCount>0) {
                echo "exists";
            }else {
                // ADD Newsletter Email in newsletter_subscriber table
                $newsletter = new NewsletterSubscriber;
                $newsletter->email = $data['subscriber_email'];
                $newsletter->status = 1;
                $newsletter->save();
                echo "Saved";
            }

        }
    }

    public function viewNewsletterSubcribers()
    {
        $newsletters = NewsletterSubscriber::get();
        return view('admin.newsletters.view_newsletters', compact('newsletters'));
    }

    public function updateNewsletterStatus($id,$status)
    {
        NewsletterSubscriber::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with('flash_message_success','Newsletter Status has been updated');
    }

    public function deleteNewsletterEmail($id)
    {
        NewsletterSubscriber::where('id',$id)->delete();
        return redirect()->back()->with('flash_message_success','Newsletter Email has been deleted successfully!!');
    }

    // public function exportNewsletterEmails()
    // {
    //     $subscriberData = NewsletterSubscriber::select('id','email','created_at')
    //     ->where('status',1)->orderBy('id','Desc')->get();
    //     $subscriberData = json_decode(json_encode($subscriberData),true);
    //     echo "<pre>"; print_r($subscriberData); die;
    //     return Excel::create('subscribers'.rand(),function($excel) use($subscriberData){
    //         $excel->sheet('mySheet',function($sheet) use($subscriberData){
    //             $sheet->fromArray($subscriberData);
    //         });
    //     })->download('xlsx');
    // }

    public function exportNewsletterEmails()
    {
        return Excel::download(new subscribersExport,'subscribers.xlsx');
    }



}

<?php

namespace App\Exports;

use App\NewsletterSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;

class subscribersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // For Exporting Complete table without any condition.
        // return NewsletterSubscriber::all();

        // For Exporting selected coloumns of table with condition.
        $subscriberData = NewsletterSubscriber::select('id','email','created_at')
        ->where('status',1)->orderBy('id','Desc')->get();
        return $subscriberData;
    }
}

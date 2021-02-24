<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class usersExport implements WithHeadings,FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return User::all();

        $userData = User::select('id','name','email','city','state','country','mobile','address','status','created_at')
        ->where('status',1)->orderBy('id','Desc')->get();
        return $userData;
    }

    public function headings(): array{
        return['ID','Name','Email','City','State','Country','Mobile No','Address','Status','Created At'];
    }







}

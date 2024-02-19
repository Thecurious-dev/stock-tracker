<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

   // protected $table = 'companies';

    protected static function boot(){
        parent::boot();
    
         //created
        static::created(function($company){
            $owner = User::find($company->owner_id);
            if($owner == null){
                throw new \Exception("Owner not found");
            }
    
            //dd($owner);
            $owner->company_id = $company->id;
            $owner->save();
        });
    
        //updated
    
        static::updated(function($company){
            $owner = User::find($company -> owner_id);
            if($owner == null){
                throw new \Exception("Owner not found");
            }

            $owner->company_id = $company->id;
            $owner->save();
           // dd($owner);
        });
    }
}

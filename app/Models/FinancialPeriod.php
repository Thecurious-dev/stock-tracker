<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPeriod extends Model
{
    use HasFactory;

    protected static function boot(){
        parent::boot();
    
        //created
        static::creating(function($model){

            //active financial period
            $active_financial_period =FinancialPeriod::where([
                'company_id'=>$model ->company_id,
                'status'=> 'Active',
            ])->first();  
            if($active_financial_period != null && $model-> status == 'Active'){
                throw new \Exception("There is an active financial period. Please close the first");
            }
          
        });

        static::updating(function($model){

            //updating financial period
            $active_financial_period =FinancialPeriod::where([
                'company_id'=>$model -> company_id,
                'status'=> 'Active',
            ])->first(); 
            if($model-> status == 'Active'){ 
                if($active_financial_period != null && $active_financial_period->id != $model->id){
                    throw new \Exception("You cannot have two financial period");
                }
            }
      
        });
    }
}

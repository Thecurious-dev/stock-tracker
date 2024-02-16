<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;


    protected static function boot(){
        parent::boot();
    
        //created
        static::creating(function($model){
            $model = self::prepare($model);
            $model->current_quantity = $model->original_quantity;
           
            return $model;
        });

        //created
        static::updating(function($model){
            $model = self::prepare($model);

            return $model;
            
        });

        
        static::created(function($model){
         
         $stock_category = StockCategory::find($model->stock_category_id);
         $stock_category->update_self();

         $stock_category = StockSubCategory::find($model->stock_sub_category_id);
         $stock_category->update_self();
        
        });

        static::updated(function($model){
         
            $stock_category = StockCategory::find($model->stock_category_id);
            $stock_category->update_self();

            $stock_category = StockSubCategory::find($model->stock_sub_category_id);
            $stock_category->update_self();
            
           
           });
        
           static::deleted(function($model){
         
            $stock_category = StockCategory::find($model->stock_category_id);
            $stock_category->update_self();

            $stock_category = StockSubCategory::find($model->stock_sub_category_id);
            $stock_category->update_self();
           
           });
    }

    static public function prepare($model){
       
            // checking for stock_sub_category_id 
        $sub_category = StockSubCategory::find($model->stock_sub_category_id);
        if($sub_category == null){
            throw new \Exception("Invalid Stock Sub Category");
        }
        //  assign stock_category_id to the SubCategory
        $model->stock_category_id = $sub_category->stock_category_id;

        $user = User::find($model->created_by_id); //User who created the model item
        if($user == null){
            throw new \Exception("Invalid user");
        }

        $financial_period = Utils::getActiveFinancialPeriod($user->company_id);

        if ($financial_period == null){
            throw new \Exception("Invalid Financial Period");
        }
        $model->financial_period_id = $financial_period->id;
        $model->company_id = $user->company_id;

        if($model->sku == null || strlen($model->sku) < 2){
    //dd(Utils::generateSKU($model->company_id));
            $model->sku = Utils::generateSKU($model->stock_sub_category_id); 
           // dd($model->sku);      
        }
        
        if($model->update_sku == "Yes" && $model->generate_sku == "Manual"){
            $model->sku = Utils::generateSKU($model->stock_sub_category_id);
            $model->generate_sku = "No";
        }

        return $model;
    }

    public function stockSubCategory(){
        return $this->belongsTo(StockSubCategory::class);
    }
    protected $appends = ['name_text'];

    //getter for name_text

    public function getNameTextAttribute(){

            $name_text = $this->name;

        if($this->stockSubCategory != null){
            $name_text = $name_text. "-" . $this->stockSubCategory->name;
        }
        $name_text = $name_text . " (" .number_format($this->current_quantity) . " ". $this->stockSubCategory->measurement_unit .")";
        return $name_text;
    }
}

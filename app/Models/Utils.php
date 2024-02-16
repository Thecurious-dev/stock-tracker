<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


    class Utils 
{
    static function getActiveFinancialPeriod ($company_id){
        return FinancialPeriod::where('company_id', $company_id)
            -> where ('status', 'Active')
            ->first();
    
    }

    public static function generateSKU($stock_sub_category_id){
        $year = date('Y');
        $sub_category = StockSubCategory::find($stock_sub_category_id);
        if(!$sub_category){
            return null;
        }
        $serial = StockItem::where('stock_sub_category_id', $stock_sub_category_id)->count() + 1;
        $sku = $year . "-" . $sub_category->id. "-" . $serial;
        // dd($sku);
        return $sku;
    }

}



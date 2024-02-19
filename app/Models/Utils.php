<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Utils extends Model
{
     //Api function for file uploads
     public static function file_upload($file){
        if($file == null){
            return "";
        }
        $file_ext = $file->getClientOriginalExtension();
        $file_name = time(). "-" . rand(1000, 100000). "." .$file_ext;
        $public_path = public_path()."/storage/images"; 
        $file->move($public_path, $file_name);
        $url = '/images'. $file_name;
        return $url;
    }

    public static function get_user(Request $r){
        $logged_in_user_id = $r->header("logged_in_user_id");
        return User::find($logged_in_user_id);
    }
    //API function for success response
    public static function success($data , $message ){
       //set header response to json
       header('Content-Type: application/json');
       http_response_code(200);
       echo json_encode([
           'code' => 1,
           'message' => $message,
           'data' => $data
       ]);
    }

    public static function error($message = null){
        //set header response to json
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'code' => 0,
            'message' => $message,
        ]);
       

    }
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



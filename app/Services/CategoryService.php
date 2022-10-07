<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryService {
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    public function getData(){
        $listCategory = $this->category->whereIn('status',[0,1])->paginate(10);
        return $listCategory;
    }
    public static function getStatusLabel($status){
        $labelStatus = "";
        switch ($status) {
            case 1 :
                $labelStatus = "Active";
                break;
            case 2 :
                $labelStatus = "Deleted";
                break;
            default :
                $labelStatus = "Deactive";
        }
        return $labelStatus;
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryService
{
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getData()
    {
        $listCategory = $this->category->whereIn('status', [0, 1])->paginate(10);

        return $listCategory;
    }

    public function insert($data)
    {
        $data = [
            'name'       => $data['name'],
            'status'     => $data['status'],
            'created_at' => Now(),
            'updated_at' => Now()
        ];

        $this->category->insert($data);
    }

    public static function getStatusLabel($status)
    {
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

    public function find($request)
    {
        $dataCategory = $this->category->find($request->id);
        return $dataCategory;
    }

    public function update($request){
        $data = [
            'name' => $request->name,
            'status' => $request->status,
            'updated_at' => Now()
        ];
        $this->category->where('id',$request->id)->update($data);
    }
}

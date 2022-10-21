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
        $listCategory = $this->category->get();

        return $listCategory;
    }

    public function ajax(Request $request)
    {
        $records = $this->category;
        $draw    = $request->draw;
        $start   = !empty($request->start) ? $request->start : 0;
        $length  = !empty($request->length) ? $request->length : 10;

        $columnIndexArray = $request->order;
        $orderArray       = $request->order;
        $columnNameArray  = $request->get("columns");
        $searchArray      = $request->search;
        $searchValue      = $searchArray['value'];


//        Total record
        $totalRecords = $this->category->select("count(*)")->count();
//        Total record with filter
        $records = $records->select("id", "name", "status", "created_at");
        if (isset($request->id) && !is_null($request->id)) {
            $records = $records->where("id", (int)$request->id);
        }
        if (isset($request->name) && !is_null($request->name)) {
            $records = $records->where("name", "like", "%" . $request->name . "%");
        }
        if (isset($request->status) && !is_null($request->status)) {
            $records = $records->where("status", $request->status);
        } else {
            $records = $records->whereIn("status", [0, 1]);
        }
        if (isset($request->created_at) && !is_null($request->created_at)) {
//            dd($request->created_at." 00:00:01");
            $records = $records->where("created_at", "like", $request->created_at . "%");
        }
        $totalRecordsFilter = $records->count();
        $records            = $records->skip($start)
            ->take($length);

        $dataCategory = $records->get();
        $listCategory = [];
        foreach ($dataCategory as $item) {
            $actions    = "";
            $id         = $item->id;
            $name       = $item->name;
            $status     = self::getStatusLabel($item->status);
            $created_at = date_create($item->created_at);
            $created_at = date_format($created_at, 'H:i:s d-m-Y');

            $actions        .= "<a class='btn btn-info mr-2' href=" . route('admin.categories.view', ['id' => $id]) . "' role='button'><i class='fa-solid fa-eye'></i></a>";
            $actions        .= "<a class='btn btn-primary mr-2' href=" . route('admin.categories.edit', ['id' => $id]) . "' role='button'><i class='fa-solid fa-pen'></i></a>";
            $actions        .= "<a class='btn btn-danger text-white buttonDelete' data-id='" . $id . "' role='button'><i class='fa-solid fa-trash'></i></a>";
            $listCategory[] = [
                'id'         => $id,
                'name'       => $name,
                'status'     => $status,
                'created_at' => $created_at,
                'actions'    => $actions
            ];
        }
//        dd($listCategory);
        $response = [
            "draw"            => $draw,
            "recordsTotal"    => $totalRecords,
            "recordsFiltered" => $totalRecordsFilter,
            "data"            => $listCategory
        ];
//        dd($response);
        echo json_encode($response);
        exit;
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

    public function update($request)
    {
        $data = [
            'name'       => $request->name,
            'status'     => $request->status,
            'updated_at' => Now()
        ];
        $this->category->where('id', $request->id)->update($data);
    }

    public function delete($request)
    {
        $this->category->where('id', $request->id)->update(['status' => 2]);
    }
}

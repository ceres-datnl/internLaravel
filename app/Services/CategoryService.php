<?php

namespace App\Services;

use Yajra\Datatables\Datatables;
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
        $draw   = $request->draw;
        $start  = !empty($request->start) ? $request->start : 0;
        $length = !empty($request->length) ? $request->length : 10;

        $columnIndexArray = $request->order;
        $orderArray       = $request->order;
        $columnNameArray  = $request->get("columns");
        $searchArray      = $request->search;
        $columnIndex      = $columnIndexArray[0]["column"];
        $columnName       = $columnNameArray[$columnIndex]['data'];
        $columnSortOrder  = $orderArray[0]['dir'];
        $searchValue      = $searchArray['value'];


//        Total record
        $totalRecords = $this->category->select("count(*)")->count();
//        Total record with filter
        $totalRecordsFilter = $this->category->select("count(*)")->where("name", "like", "%" . $searchValue . "%")->count();

        $records      = $this->category
            ->orderBy($columnName, $columnSortOrder)
            ->whereIn("status",[0,1])
            ->where("name", "like", "%" . $searchValue . "%")
            ->skip($start)
            ->take($length)
            ->get();
        $listCategory = [];
        foreach ($records as $record) {
            $actions = "";

            $id             = $record->id;
            $name           = $record->name;
            $status         = self::getStatusLabel($record->status);
            $created_at     = date("H:i:s d/m/Y", strtotime($record->created_at));
            $actions        .= "<a class='btn btn-info mr-2' href=" . route('admin.categories.view', ['id' => $id]) . "' role='button'><i class='fa-solid fa-eye'></i> &nbsp;View</a>";
            $actions        .= "<a class='btn btn-primary mr-2' href=" . route('admin.categories.edit', ['id' => $id]) . "' role='button'><i class='fa-solid fa-pen'></i> &nbsp;Edit</a>";
            $actions        .= "<a class='btn btn-danger text-white buttonDelete' data-id='" . $id . "' role='button'><i class='fa-solid fa-trash'></i> &nbsp;Delete</a>";
            $listCategory[] = [
                'id'         => $id,
                'name'       => $name,
                'status'     => $status,
                'created_at' => $created_at,
                'actions'    => $actions
            ];
        }
        $response = [
            "draw"            => intval($draw),
            "recordsTotal"    => $totalRecords,
            "recordsFiltered" => $totalRecordsFilter,
            "data"            => $listCategory
        ];
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

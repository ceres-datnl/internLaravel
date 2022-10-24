<?php

namespace App\Services;

use App\Helpers\ImageUtils;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\News;
use App\Services\CategoryService;
use App\Services\FileService;

class NewsService
{
    protected $news;
    protected $category;
    protected $file;

    public function __construct(News $news, CategoryService $category, FileService $file)
    {
        $this->news     = $news;
        $this->category = $category;
        $this->file     = $file;
    }


    public function ajaxLoadListNews($request)
    {
        $siteQueryBuilder  = $this->makingQueryAllSites($request);
        $datatable         = $this->queryDatatable($request, $siteQueryBuilder);
        $datatable['data'] = $this->parseDataColumnForManageAccount($request, $datatable['data']);

        return $datatable;
    }

    public function makingQueryAllSites($request)
    {
        $records = $this->news->select("news.*", "categories.name as nameCategory")->join("categories", "categories.id", "=", "news.category_id");
        if (isset($request->id) && !is_null($request->id)) {
            $records = $records->where("news.id", (int)$request->id);
        }
        if (isset($request->category_id) && !is_null($request->category_id)) {
            $records = $records->where("news.category_id", $request->category_id);
        }
        if (isset($request->title) && !is_null($request->title)) {
            $records = $records->where("news.title", "like", "%" . $request->title . "%");
        }
        if (isset($request->status) && !is_null($request->status)) {
            $records = $records->where("news.status", $request->status);
        } else {
            $records = $records->where("news.status", "!=", 2);
        }
        if (isset($request->created_at) && !is_null($request->created_at)) {
            $records = $records->where("news.created_at", "like", $request->created_at . "%");
        }

        return $records;
    }

    public function queryDatatable($request, $siteQueryBuilder)
    {
        $draw        = $request->draw;
        $start       = !empty($request->start) ? $request->start : 0;
        $length      = !empty($request->length) ? $request->length : 10;
        $searchArray = $request->search;
        $searchValue = $searchArray['value'];

        $totalRecords       = $this->news->whereIn("status", [0, 1])->count();
        $totalRecordsFilter = $siteQueryBuilder->count();
        $siteQueryBuilder   = $siteQueryBuilder->skip($start)
            ->take($length);

        $dataNews   = $siteQueryBuilder->get();
        $dataReturn = [
            "draw"            => $draw,
            "recordsTotal"    => $totalRecords,
            "recordsFiltered" => $totalRecordsFilter,
            "data"            => $dataNews
        ];

        return $dataReturn;
    }

    public function parseDataColumnForManageAccount($request, $dataRows)
    {
        $listCategory = [];
        foreach ($dataRows as $item) {
            $actions      = "";
            $id           = $item->id;
            $nameCategory = $item->nameCategory;
            $title        = $item->title;
            $status       = self::getStatusLabel($item->status);
            $created_at   = date_create($item->created_at);
            $created_at   = date_format($created_at, 'H:i:s d-m-Y');

            $actions        .= "<a class='btn btn-info mr-2' href=" . route('admin.news.view', $id) . " role='button'><i class='fa-solid fa-eye'></i></a>";
            $actions        .= "<a class='btn btn-primary mr-2' href=" . route('admin.news.edit', $id) . " role='button'><i class='fa-solid fa-pen'></i></a>";
            $actions        .= "<a class='btn btn-danger text-white buttonDelete' data-id='" . $id . "' role='button'><i class='fa-solid fa-trash'></i></a>";
            $listCategory[] = [
                'id'           => $id,
                'nameCategory' => $nameCategory,
                'title'        => $title,
                'status'       => $status,
                'created_at'   => $created_at,
                'actions'      => $actions
            ];
        }

        return $listCategory;
    }

    public function loadCategory($request)
    {
        $numberOfRecords = 25;

        $offset       = ($request->page - 1) * $numberOfRecords;
        $dataCategory = $this->category->category->select("id", "name as text");
        if (isset($request->search)) {
            $dataCategory = $dataCategory->where("name", "like", "%" . $request->search . "%");
        }
        $dataCategory = $dataCategory->skip($offset)->take($numberOfRecords)->paginate();

//        $reponse = [];
//        foreach ($dataCategory as $item) {
//            $reponse[] = [
//                "id"   => $item->id,
//                "text" => $item->name
//            ];
//        }

        return $dataCategory;
    }

    public function getCategoryData()
    {
        $dataCategory = $this->category->getData();

        return $dataCategory;
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

    public function insert(Request $request)
    {

        if (isset($request->imageNews) && !is_null($request->imageNews)) {
            $idFile = $this->file->uploadImage($request->imageNews);
        } else {
            $idFile = null;
        }

        $data = [
            "category_id" => $request->category_id,
            "title"       => $request->title,
            "content"     => $request->m_content,
            "file_id"     => $idFile,
            "status"      => $request->status,
            "created_at"  => Now(),
            "updated_at"  => Now()
        ];

        $this->news->insert($data);
    }

    public function findById($id)
    {
        $data = $this->news
            ->find($id);
        return $data;
    }

    public function getPathImage($path, $imageName, $size)
    {
        $pathImage = ImageUtils::getUrlImage($path, $imageName, $size);

        return $pathImage;
    }

    public function update($request, $id)
    {
        if (isset($request->imageNews) && !is_null($request->imageNews)) {
            $idFile = $this->file->uploadImage($request->imageNews);
        }
        $data = [
            "category_id" => $request->category_id,
            "title"       => $request->title,
            "content"     => $request->content,
            "status"      => $request->status,
            "updated_at"  => Now()
        ];
        if (isset($idFile)) {
            $data['file_id'] = $idFile;
        }
        try {
            $this->news->where("id", $id)->update($data);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function delete($request)
    {
        $this->news->where('id', $request->id)->update(['status' => 2]);
    }
}

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

//    public function getNewsData()
//    {
//        $dataNews = $this->news->select("news.*", "categories.name as nameCategory")
//            ->join("categories", "categories.id", "=", "news.category_id")
//            ->whereIn("news.status", [0, 1])
//            ->paginate(10);
//
//        return $dataNews;
//    }

    public function ajax($request)
    {
        $draw   = $request->draw;
        $start  = !empty($request->start) ? $request->start : 0;
        $length = !empty($request->length) ? $request->length : 10;

        $searchArray = $request->search;
        $searchValue = $searchArray['value'];

        $totalRecords = $this->news->whereIn("status", [0, 1])->count();

//        Total record with filter
        $records = $this->news->select("news.*","categories.name as nameCategory")->join("categories", "categories.id", "=", "news.category_id");
        if (isset($request->id) && !is_null($request->id)) {
            $records = $records->where("news.id", (int)$request->id);
        }
        if (isset($request->category_id) && !is_null($request->category_id)) {
            $records = $records->where("news.category_id", $request->category_id);
        }
        if (isset($request->title) && !is_null($request->title)) {
            $records = $records->where("news.title", $request->title);
        }
        if (isset($request->status) && !is_null($request->status)) {
            $records = $records->where("news.status", $request->status);
        } else {
            $records = $records->whereIn("news.status", [0, 1]);
        }
        if (isset($request->created_at) && !is_null($request->created_at)) {
//            dd($request->created_at." 00:00:01");
            $records = $records->where("news.created_at", "like", $request->created_at . "%");
        }
        $totalRecordsFilter = $records->count();
        $records            = $records->skip($start)
            ->take($length);

        $dataNews = $records->get();
        $listNews = [];

        foreach ($dataNews as $item) {
            $actions     = "";
            $id          = $item->id;
            $nameCategory = $item->nameCategory;
            $title       = $item->title;
            $status      = self::getStatusLabel($item->status);
            $created_at  = date_create($item->created_at);
            $created_at  = date_format($created_at, 'H:i:s d-m-Y');

            $actions        .= "<a class='btn btn-info mr-2' href=" . route('admin.news.view', ['id' => $id]) . "' role='button'><i class='fa-solid fa-eye'></i></a>";
            $actions        .= "<a class='btn btn-primary mr-2' href=" . route('admin.news.edit', ['id' => $id]) . "' role='button'><i class='fa-solid fa-pen'></i></a>";
            $actions        .= "<a class='btn btn-danger text-white buttonDelete' data-id='" . $id . "' role='button'><i class='fa-solid fa-trash'></i></a>";
            $listCategory[] = [
                'id'          => $id,
                'nameCategory' => $nameCategory,
                'title'       => $title,
                'status'      => $status,
                'created_at'  => $created_at,
                'actions'     => $actions
            ];
        }
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

    public function loadCategory($request)
    {
        $numberOfRecords = 25;

        $offset       = ($request->page - 1) * $numberOfRecords;
        $dataCategory = $this->category->category->select("id", "name");
        if (isset($request->search)) {
            $dataCategory = $dataCategory->where("name", "like", "%" . $request->search . "%");
        }
        $dataCategory = $dataCategory->skip($offset)->take($numberOfRecords)->get();

        $reponse = [];
        foreach ($dataCategory as $item) {
            $reponse[] = [
                "id"   => $item->id,
                "text" => $item->name
            ];
        }

        return $reponse;
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

    public function find($request)
    {
        try {
            $data = $this->news->select("news.*", "file.path as path", "file.name as imageName", "categories.name as categoryName")
                ->join("categories", "categories.id", "=", "news.category_id")
                ->leftJoin("file", "file.id", "=", "news.file_id")
                ->where("news.id", "=", $request->id)
                ->first();
        } catch (\Exception $exception) {
            return redirect('errors/404');
        }

        if ($data !== null) {
            if ($data['file_id'] !== null) {
                $pathImage = ImageUtils::getUrlImage($data['path'], $data['imageName'], ImageUtils::IMG_SIZE_MEDIUM);
            } else {
                $pathImage = null;
            }
        } else {
            return redirect('errors/404');
        }
        $data['linkImage'] = $pathImage;

        return $data;
    }

    public function update($request)
    {
        var_dump($request->all());
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
        $this->news->where("id", $request->id)->update($data);
    }

    public function delete($request)
    {
        $this->news->where('id', $request->id)->update(['status' => 2]);
    }
}

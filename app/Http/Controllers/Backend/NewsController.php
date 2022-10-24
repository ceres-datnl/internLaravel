<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Http\Requests\Backend\News\AddNewsRequest;
use App\Http\Requests\Backend\News\UpdateNewsRequest;

class NewsController extends Controller
{
    protected $news;
    protected $category;

    public function __construct(NewsService $news)
    {
        $this->news = $news;
    }

    public function index()
    {
        return view('backend.news.index');
    }

    public function ajaxLoadListNews(Request $request)
    {
        $response = $this->news->ajaxLoadListNews($request);
        echo json_encode($response);
        exit;
    }

    public function add()
    {
        $dataCategory = $this->news->getCategoryData();

        return view('backend.news.add')->with("dataCategory", $dataCategory);
    }

    public function store(AddNewsRequest $request)
    {
        if($request->ajax()){
            return response()->json(['status' => 'ok'], 200);
        }
        try {
            $this->news->insert($request);

        } catch (\Exception $exception) {
            return redirect('errors/404');
        }

        return redirect()->route('admin.news.index')->with(['success' => 'Add News Successful']);
    }

    public function view($id)
    {
        $dataNews = $this->news->findById($id);
        if (empty($dataNews)) {
            return redirect('errors/404');
        }

        return view('backend.news.view')->with("dataNews", $dataNews);
    }

    public function edit(Request $request, $id)
    {
        $dataNews = $this->news->findById($id);

        if (empty($dataNews)) {
            return redirect('errors/404');
        }

        return view("backend.news.edit")
            ->with('dataNews', $dataNews);
    }

    public function update(UpdateNewsRequest $request, $id)
    {
        if($request->ajax()){
            return response()->json(['status' => 'ok'], 200);
        }
        $findNewsById = $this->news->findById($id);
        if (empty($findNewsById)) {
            return redirect('errors/404');
        }
        $updateNews = $this->news->update($request, $id);
        if ($updateNews)
            return redirect()->route('admin.news.index')->with(['success' => 'Update News Successful']);
    }

    public function delete(Request $request)
    {
        $result       = [
            "status" => "OK",
            "errors" => ""
        ];
        $findNewsById = $this->news->findById($request->id);
        if (empty($findNewsById)) {
            $result = [
                "status" => "NO",
                "errors" => "Fail"
            ];
        }
        try {
            $this->news->delete($request);
        } catch (\Exception $exception) {
            $result = [
                "status" => "NO",
                "errors" => "Fail"
            ];
        }
        echo json_encode($result);
        exit;
    }

    public function loadCategory(Request $request)
    {
        try {
            $response = $this->news->loadCategory($request);
        } catch (\Exception $exception) {
            $response = [
                'errors' => true
            ];
        }
        echo json_encode($response);
        exit;
    }
}

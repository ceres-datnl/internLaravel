<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Requests\Backend\Category\AddCategoryRequest;
use App\Http\Requests\Backend\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(CategoryService $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        return view('backend.categories.index');
    }

    public function ajaxLoadListCategory(Request $request)
    {
        $response = $this->category->ajaxLoadListCategory($request);
        echo json_encode($response);
        exit;
    }

    public function add()
    {
        return view('backend.categories.add');
    }

    public function store(AddCategoryRequest $request)
    {
        try {
            $this->category->insert($request);
        } catch (\Exception $exception) {
            return redirect('errors/404');
        }

        return redirect()->route('admin.categories.index')->with(['success' => 'Add Category Successful']);
    }

    public function edit($id)
    {
        $dataCategory = $this->category->findById($id);
        if (empty($dataCategory)) {
            return redirect('errors/404');
        }

        return view("backend.categories.edit")->with('dataCategory', $dataCategory);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $dataCategory = $this->category->findById($id);
        if (empty($dataCategory)) {
            return redirect('errors/404');
        }
        $this->category->update($request, $id);

        return redirect()->route('admin.categories.index')->with(['success' => 'Update Category Successful']);
    }

    public function view($id)
    {
        $dataCategory = $this->category->findById($id);
        if (empty($dataCategory)) {
            return redirect('errors/404');
        }

        return view('backend.categories.view')->with("dataCategory", $dataCategory);
    }

    public function delete(Request $request)
    {
        $result = [
            "status" => "OK",
            "errors" => ""
        ];
        try {
            $this->category->delete($request);
        } catch (\Exception $exception) {
            $result = [
                "status" => "NO",
                "errors" => "Fail"
            ];
        }
        echo json_encode($result);
        exit;
    }
}

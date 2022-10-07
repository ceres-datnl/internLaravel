<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $listCategory = $this->categoryService->getData();

        return view('backend.categories.index')->with('listCategory', $listCategory);
    }

    public function add()
    {
        return view('backend.categories.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|max:255',
            'status' => 'required|numeric|min:0|max:2',
        ]);
        try {
            $this->categoryService->insert($request);
        } catch (\Exception $exception) {
            return redirect('errors/404');
        }

        return redirect()->route('admin.categories.index')->with(['success' => 'Add Product Successful']);
    }

    public function edit(Request $request)
    {
        try {
            $dataCategory = $this->categoryService->find($request);

            return view("backend.categories.edit")->with('dataCategory', $dataCategory);
        } catch (\Exception $exception) {
            return redirect('errors/404');
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|max:255',
            'status' => 'required|numeric|min:0|max:2',
        ]);
        try {
            $this->categoryService->update($request);

            return redirect()->route('admin.categories.index')->with(['success' => 'Update Product Successful']);
        } catch (\Exception $exception) {
            return redirect('errors/404');
        }
    }

    public function view(Request $request)
    {
        try {
            $dataCategory = $this->categoryService->find($request);

            return view("backend.categories.view")->with('dataCategory', $dataCategory);
        } catch (\Exception $exception) {
            return redirect('errors/404');
        }
    }
}

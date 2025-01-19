<?php

namespace App\Http\Controllers\Items;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\Items\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct(private Category $category) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortMethod = $request->sort ?? 'desc';
        $search = $request->search;
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $query = $this->category->orderBy("created_at", $sortMethod);
        if ($search && $search != 'null') {
            $query->where('service_name', 'LIKE', '%' . $search . '%');
        }
        $data = $query->paginate($perPage);

        $response = $this->apiResponse($data);
        $response['total_count'] = $this->category->count();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = CategoryRequest::validated($request->all());
        $response = $this->category->create($validated);

        return $this->apiResponse($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->category->where('id', $id)->first();
        $response = $this->apiResponse($category);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = CategoryRequest::validated($request->all());
        $response = $this->category->where("id", $id)->update($validated);

        if (! $response) {
            return $this->errorResponse("No record has been deleted!", 400);
        }

        return $this->apiResponse([
            "message" => "Record has been updated!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->category->where("id", $id)->delete();
        if (! $deleted) {
            return $this->errorResponse("No record has been deleted!", 400);
        }

        return $this->apiResponse([
            "message" => "Record has been deleted!"
        ]);
    }
}

<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\CouponRequest;
use App\Models\Coupon;
use Exception;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;

class CouponController extends Controller
{
    public function __construct(private Coupon $coupon) {}
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

        $query = $this->coupon->orderBy("created_at", $sortMethod);
        if ($search && $search != 'null') {
            $query->where('code', 'LIKE', '%' . $search . '%');
        }
        $data = $query->paginate($perPage);

        $response = $this->apiResponse($data);
        $response['total_count'] = $this->coupon->count();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = CouponRequest::validated($request->all());
        $validated['code'] = Str::uuid();

        try {
            $response = $this->coupon->create($validated);
            return $this->apiResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $response = $this->coupon->where("id", $id)->first();
        return $this->apiResponse($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = CouponRequest::validated($request->all());

        $coupon = $this->coupon->findOrFail($id);
        $coupon->update($validated);

        return $this->apiResponse([
            "message" => "Record has been updated!",
            "coupon" => $coupon
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $coupon = $this->coupon->findOrFail($id);
        $coupon->delete($id);

        return $this->apiResponse(["message" => "Coupon has been deleted!"]);
    }
}

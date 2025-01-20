<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\EventUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class EventUserController extends Controller
{
    public function __construct(private EventUser $event_user) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortMethod = $request->sort ?? 'desc';
        // $search = $request->search;
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $query = $this->event_user->orderBy("created_at", $sortMethod);
        $data = $query->paginate($perPage);

        $response = $this->apiResponse($data);

        $response['total_count'] = $this->event_user->count();
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

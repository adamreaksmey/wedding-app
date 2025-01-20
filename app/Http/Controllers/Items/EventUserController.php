<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\EventUserRequest;
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
    public function store(EventUserRequest $request)
    {
        $validated = $request->validated();
        $event_user = $this->event_user->create($validated);

        return $this->apiResponse($event_user);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $event_user = $this->event_user->findOrFail($id);
        return $this->apiResponse($event_user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validated();
        $event_user = $this->event_user->findOrFail($id);
        $event_user->update($validated);

        return $this->apiResponse([
            "message" => "Record has been updated!",
            "data" => $event_user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $event_user = $this->event_user->findOrFail($id);
        $event_user->delete($id);

        return $this->apiResponse([
            "message" => "Record has been deleted!"
        ]);
    }
}

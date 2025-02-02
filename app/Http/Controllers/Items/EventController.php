<?php

namespace App\Http\Controllers\Items;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Items\EventRequest;
use App\Models\Event;
use Illuminate\Pagination\Paginator;

class EventController extends Controller
{
    public function __construct(private Event $event) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 'true';
        $sortMethod = $request->sort ?? 'desc';
        $search = $request->search;
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;
        $categoryId = $request->categoryId;

        $query = $this->event->orderBy("created_at", $sortMethod);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($pagination == 'false') {
            $response = $this->apiResponse($query->get());
            $response['total_count'] = $this->event->count();

            return $response;
        }

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        if ($search && $search != 'null') {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        $data = $query->paginate($perPage);

        $response = $this->apiResponse($data);
        $response['total_count'] = $this->event->count();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $validated = $request->validated();

        if (! isset($validated['image']) || ! $validated['image']) {
            $validated['image'] = Helpers::image_placeholder();
        }

        $event = $this->event->create($validated);

        return $this->apiResponse($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $event = $this->event->findOrFail($id);
        return $this->apiResponse($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, int $id)
    {
        $validated = $request->validated();
        $event = $this->event->findOrFail($id);
        $event->update($validated);

        return $this->apiResponse([
            "message" => "Record has been updated!",
            "data" => $event
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $event = $this->event->findOrFail($id);
        $event->delete($id);

        return $this->apiResponse(["message" => "Record has been deleted!"]);
    }
}

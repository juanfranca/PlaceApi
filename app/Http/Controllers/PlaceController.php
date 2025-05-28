<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Services\PlaceService;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function __construct(protected PlaceService $placeService) {}

    /**
     * Display a listing of places.
     */
    public function index(Request $placeIndexRequest)
    {
        $places = $this->placeService->getPLaces($placeIndexRequest);

        return $this->successResponse('All places were successfully retrieved.', PlaceResource::collection($places));
    }

    /**
     * Display the specified place.
     */
    public function show(string $place)
    {
        $place = $this->placeService->getPlace($place);

        return $this->successResponse('Place details retrieved successfully.', new PlaceResource($place));
    }
    /**
     * Store a newly created place in storage.
     */
    public function store(PlaceRequest $request)
    {
        $newPlace = $this->placeService->handleStore($request->all());

        return $this->successResponse('Place created successfully.', new PlaceResource($newPlace));
    }

    /**
     * Update the specified place in storage.
     */
    public function update(PlaceRequest $request, string $place)
    {
        $place = $this->placeService->handleUpdate($request->all(), $place);

        return $this->successResponse('Place updated successfully.', new PlaceResource($place));
    }
}

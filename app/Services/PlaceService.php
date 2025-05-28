<?php

namespace App\Services;


use App\Models\Place;

class PlaceService
{
    public function getPlaces($request)
    {
        if ($request->has('name')) {
            return Place::where('name', 'ilike', "%" . $request->input('name') . "%")->get();
        }

        return Place::all();
    }

    public function getPlace($place)
    {
        $place = Place::where('slug', $place)->firstOrFail();

        return $place;
    }

    public function handleStore(array $newPlace)
    {
        $newPlace = Place::create($newPlace);

        return $newPlace;
    }

    public function handleUpdate(array $updatedPlace, $place)
    {
        $place = Place::where('slug', $place)->firstOrFail();

        $place->update($updatedPlace);

        return $place;
    }
}

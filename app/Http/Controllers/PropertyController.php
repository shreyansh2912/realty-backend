<?php

namespace App\Http\Controllers;

use App\Http\Requests\Property\storeRequest;
use App\Http\Requests\Property\updateReaquest;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $properties = Property::orderByDesc('id')->paginate($perPage);

            $properties->getCollection()->transform(function ($property) {
                $media = $property->getFirstMedia('media');
                $property->media_url = $media ? $media->getUrl() : null;
                return $property;
            });

            return response()->successJson($properties, [], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([$th->getMessage()], ['message' => "Something went wrong"], 500);
        }
    }


    public function store(storeRequest $request)
    {
        try {
            $property = Property::create([
                'name' => $request->name,
                'location' => $request->location,
                'property_type' => $request->property_type,
                'price' => $request->price,
            ]);

            if ($request->hasFile('image')) {
                $property->addMedia($request->file('image'))->toMediaCollection('media');
            }

            return response()->successJson([], ['message' => 'Property added successfully'], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([], ['message' => "Something went wrong"], 500);
        }
    }

    public function edit($id)
    {
        try {
            $property = Property::find($id);

            if (!$property) {
                return response()->errorJson(['message' => "Property not found"], [], 404);
            }

            $media = $property->getFirstMedia('media');
            if ($media) {
                $property['url'] = $media->getUrl();
                return response()->successJson($property, [], 200);
            }

            return response()->json([$property], 404);
        } catch (\Throwable $th) {
            return response()->errorJson([$th->getMessage()], ['message' => "Something went wrong"], 500);
        }
    }

    public function update(updateReaquest $request, $id)
    {
        try {
            $property = Property::find($id);

            if (!$property) {
                return response()->errorJson(['message' => "Property not found"], [], 404);
            }

            $property->update([
                'name' => $request->name ?? $property->name,
                'location' => $request->location ?? $property->location,
                'property_type' => $request->property_type ?? $property->property_type,
                'price' => $request->price ?? $property->price,
            ]);

            if ($request->hasFile('image')) {
                $property->clearMediaCollection('media');

                $property->addMedia($request->file('image'))->toMediaCollection('media');
            }

            return response()->successJson([], ['message' => 'Property updated successfully'], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([$th->getMessage()], ['message' => "Something went wrong"], 500);
        }
    }

    public function delete($id)
    {
        try {
            $property = Property::find($id);

            if (!$property) {
                return response()->errorJson(['message' => "Property not found"], [], 404);
            }

            $property->clearMediaCollection('media');
            $property->delete();

            return response()->successJson([], ['message' => 'Property deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([$th->getMessage()], ['message' => "Something went wrong"], 500);
        }
    }
}

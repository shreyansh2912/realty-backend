<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSession\storeRequest;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function store(storeRequest $request){
        try {
            return Property::create([
                'name' => $request->name,
                'location' => $request->location,
                'property_type' => $request->property_type,
                'price' => $request->price,
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return response()->errorJson([],['message'=>"Something went wrong"],500);
        }
    }
}

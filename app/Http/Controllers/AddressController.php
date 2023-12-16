<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //All countries
    public function getCountry()
    {
        $countries = Country::all();
        return response()->json(['countries' => $countries], 200);
    }

    //Provinces by Country
    public function getProvincesByCountry(Request $request)
    {

        $provinces = Province::where('country_id', $request->country)->get();

        return response()->json(['provinces' => $provinces, 'status' => 200], 200);
    }


    //Cities by province
    public function getCitiesByProvince(Request $request)
    {
        $cities = City::where('province_id', $request->province)->get();

        return response()->json(['cities' => $cities, 'status' => 200], 200);
    }
}

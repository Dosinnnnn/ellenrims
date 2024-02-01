<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RajaOngkirService;

class RajaOngkirController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getProvinces()
    {
        // Panggil method getProvinces dari service
        $provinces = $this->rajaOngkirService->getProvinces();

        // Return response sebagai JSON
        return response()->json($provinces);
    }

    // Di CartController.php

    public function getCities(Request $request, $province_id)
    {
        $cities = $this->rajaOngkirService->getCities($province_id);
        return response()->json($cities);
    }
}

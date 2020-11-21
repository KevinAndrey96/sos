<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

/**
 * Class WarehouseController
 * @package App\Http\Controllers\Api
 */
class WarehouseController extends Controller
{
    /**
     * @return array[]
     */
    public function __invoke(): array
    {
        $cabecera = count(Warehouse::where("name","Cabecera")->first()->products);
        $canaveral = count(Warehouse::where("name","Cañaveral")->first()->products);
        $centro = count(Warehouse::where("name","Centro")->first()->products);

        return ["data" => ["cabecera" => $cabecera, "canaveral" => $canaveral, "centro" => $centro]];
    }

    /**
     * @return array[]
     */
    public function index(): array
    {
        $warehouses = Warehouse::all();

        return ["data" => ["warehouses" => $warehouses]];
    }
}

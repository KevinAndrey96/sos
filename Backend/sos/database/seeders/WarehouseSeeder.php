<?php
namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class WarehouseSeeder
 * @package Database\Seeders
 */
class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::truncate();
        $warehouses = [
            ["name" => "Cabecera"],
            ["name" => "Cañaveral"],
            ["name" => "Centro"],
        ];
        DB::table('warehouses')->insert($warehouses);
    }
}

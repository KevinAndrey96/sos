<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductSeeder
 * @package Database\Seeders
 */
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        $products = Array();
        $statuses = ["active", "inactive", "pending"];

        for ($i=0; $i<100; $i++)
        {
            $faker = Faker\Factory::create();
            $product = [
                "name" => $faker->word(),
                "code" => $faker->numberBetween(9999, 99999),
                "stock" => $faker->numberBetween(0, 20),
                "warehouse_id" => $faker->numberBetween(1, 3),
                "description" => $faker->sentence(),
                "status" => $statuses[$faker->numberBetween(0, 2)],
            ];
            array_push($products, $product);
        }

        DB::table('products')->insert($products);
    }
}

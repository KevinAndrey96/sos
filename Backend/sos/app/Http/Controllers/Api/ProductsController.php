<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ProductsController
 * @package App\Http\Controllers\Api
 */
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $products = Product::orderBy('id', 'DESC')->get();
        foreach ($products as $product)
        {
            $product->warehouse;
        }

        return ["data" => ["products" => $products]];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        Product::create($request->toArray());
        return ["status" => 200, "message" => "Producto creado con éxito"];
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return array
     */
    public function show(Product $product): array
    {
        return ["data" => ["product" => $product]];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return array
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->toArray());
        return ["status" => 200, "message" => "Producto actualizado con éxito"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return array
     * @throws Exception
     */
    public function destroy(Product $product): array
    {
        $product->delete();
        return ["status" => 200, "message" => "Producto eliminado con éxito"];
    }

    /**
     * @return array[]
     */
    public function stats(): array
    {
        $total = count(Product::all());
        $active = count(Product::where("status", "active")->get());
        $pending = count(Product::where("status", "pending")->get());
        $inactive = count(Product::where("status", "inactive")->get());

        return ["data" => ["total" => $total, "active" => $active, "pending" => $pending, "inactive" => $inactive]];
    }
}

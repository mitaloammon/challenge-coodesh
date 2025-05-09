<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;



class ProductController extends Controller
{
    public function index()
    {
        return Product::paginate(20);
    }

    public function show($code)
    {
        return Product::where('code', $code)->firstOrFail();
    }

    public function update(Request $request, $code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update($request->only($product->getFillable()));
        return response()->json($product);
    }

    public function destroy($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update(['status' => 'trash']);
        return response()->json(['message' => 'Status atualizado para trash']);
    }

    public function importProducts()
    {
        $page = 1;
        $importedCount = 0;
    
        try {
            while ($page <= 5) {

                $response = Http::get("https://world.openfoodfacts.org/api/v2/search.json", [
                    'page' => $page,
                    'page_size' => 20, 
                    'fields' => 'code',
                ]);
                
                if (!$response->successful()) {
                    return response()->json(['error' => 'Erro ao obter lista de produtos'], 500);
                }

            $data = $response->json();
            $products = $data['products'] ?? [];

            if (empty($products)) {
                break; 
            }

            foreach ($products as $product) {
                $code = $product['code'] ?? null;

                if (!$code) continue;

                $productDetail = Http::get("https://world.openfoodfacts.org/api/v2/product/{$code}.json");
    
                if (!$productDetail->successful()) continue;

                $productData = $productDetail->json()['product'] ?? null;

                if (!$productData || !isset($productData['product_name'])) continue;


                            Product::updateOrCreate(
                                ['code' => $product['code'] ?? uniqid()],
                                [
                                    'url' => $product['url'] ?? null,
                                    'creator' => $product['creator'] ?? null,
                                    'created_t' => $product['created_t'] ?? null,
                                    'created_datetime' => $product['created_datetime'] ?? null,
                                    'last_modified_t' => $product['last_modified_t'] ?? null,
                                    'last_modified_datetime' => $product['last_modified_datetime'] ?? null,
                                    'product_name' => $product['product_name'] ?? null,
                                    'generic_name' => $product['generic_name'] ?? null,
                                    'quantity' => $product['quantity'] ?? null,
                                    'packaging' => $product['packaging'] ?? null,
                                    'brands' => $product['brands'] ?? null,
                                    'categories' => $product['categories'] ?? null,
                                    'origins' => $product['origins'] ?? null,
                                    'manufacturing_places' => $product['manufacturing_places'] ?? null,
                                    'labels' => $product['labels'] ?? null,
                                    'image_url' => $product['image_url'] ?? null,
                                    'image_small_url' => $product['image_small_url'] ?? null,
                                    'serving_size' => $product['serving_size'] ?? null,
                                    'no_nutriments' => $product['no_nutriments'] ?? false,
                                    'nutrition_grade_fr' => $product['nutrition_grade_fr'] ?? null,
                                    'main_category' => $product['main_category'] ?? null,
                                    'main_category_fr' => $product['main_category_fr'] ?? null,
                                    'energy_100g' => $product['energy_100g'] ?? null,
                                    'fat_100g' => $product['fat_100g'] ?? null,
                                    'saturated_fat_100g' => $product['saturated_fat_100g'] ?? null,
                                    'sodium_100g' => $product['sodium_100g'] ?? null,
                                ]
                            );

                            $importedCount++;
                        }
                        $page++;
    
                        return response()->json([
                            'message' => 'Importação concluída com sucesso.',
                            'products_imported' => $importedCount,
                        ]);
        }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro na importação: ' . $e->getMessage()], 500);

        }      
    }

    public function status()
    {
        $lastImport = Import::latest()->first();
        return response()->json([
            'status' => 'OK',
            'last_import' => $lastImport ? $lastImport->executed_at : null,
            'imported_count' => $lastImport ? $lastImport->imported_count : 0,
            'uptime' => now()->diffForHumans(),
        ]);
    }
}
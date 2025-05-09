<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Import;

use Illuminate\Support\Carbon;

class ImportProducts extends Command
{
    protected $signature = 'import:products';
    protected $description = 'Importa produtos do Open Food Facts';

    public function handle(): void
    {
    $indexUrl = 'https://challenges.coode.sh/food/data/json/index.txt';
    $files = explode("\n", trim(file_get_contents($indexUrl)));

    foreach ($files as $filename) {
        $url = "https://challenges.coode.sh/food/data/json/{$filename}";
        $this->info("Importando: $url");

        try {
            $response = Http::get($url);

            if ($response->successful()) {

                $data = (substr($filename, -3) === '.gz') 
                    ? gzdecode($response->body()) 
                    : $response->body();
            
                if ($data === false) {
                    $this->error("Falha ao descompactar o arquivo {$filename}.");
                    return;
                }
            
                $json = json_decode($data, true);
            
                if (is_array($json)) {
                    $count = 0;
            
                    foreach (array_slice($json, 0, 100) as $product) {
                        Product::updateOrCreate([
                            'code' => $product['code'] ?? uniqid(),
                        ], [
                            ...array_intersect_key($product, array_flip((new Product)->getFillable())),
                            'imported_t' => now(),
                            'status' => $product['status'] ?? 'draft',
                        ]);
                        $count++;
                    }
            
                    Import::create([
                        'filename' => $filename,
                        'imported_count' => $count,
                        'executed_at' => now(),
                        'success' => true,
                    ]);
                } else {
                    $this->error("O conteúdo do arquivo {$filename} não é um JSON válido.");
                }
            } else {
                $this->error("Erro ao acessar {$url}. Status da resposta: {$response->status()}");
            }
            
        } catch (\Exception $e) {
            $this->error("Erro ao importar {$filename}: " . $e->getMessage());
            Import::create([
                'filename' => $filename,
                'imported_count' => 0,
                'executed_at' => now(),
                'success' => false,
            ]);
        }
    }
}
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\ProductController;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-products-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(ProductController::class)->importProducts();
    }
}

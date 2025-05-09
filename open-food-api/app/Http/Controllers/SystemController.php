<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function status()
    {
        $dbOk = DB::connection()->getPdo() ? true : false;
        $lastImport = \App\Models\Import::latest('executed_at')->first();

        return response()->json([
            'database_connection' => $dbOk ? 'OK' : 'FAIL',
            'last_cron_execution' => optional($lastImport)->executed_at,
            'uptime' => now()->diffForHumans(File::lastModified(base_path('bootstrap/cache'))),
            'memory_usage' => memory_get_usage(true),
        ]);
    }
}

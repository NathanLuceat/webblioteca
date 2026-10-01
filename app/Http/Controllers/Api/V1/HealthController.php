<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index()
    {
        try {
            DB::connection()->getPdo();
            $database = 'ok';
        } catch (\Exception $e) {
            $database = 'erro';
        }

        return response()->json([
            'status' => 'ok',
            'database' => $database,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
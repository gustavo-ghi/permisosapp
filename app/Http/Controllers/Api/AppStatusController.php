<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppClient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // Importar Carbon para manejar fechas

class AppStatusController extends Controller
{
    public function checkStatus(Request $request)
    {
        $request->validate(['api_token' => 'required|string']);
        $token = $request->input('api_token');
        $appClient = AppClient::where('api_token', $token)->first();

        if (!$appClient) {
            return response()->json(['status' => 'error', 'message' => 'Token inválido.'], 404);
        }

        // Verificación 1: ¿La app está inactiva manualmente?
        if (!$appClient->is_active) {
            return response()->json(['app_name' => $appClient->name, 'status' => 'inactive', 'message' => 'La aplicación ha sido desactivada.']);
        }

        // Verificación 2: ¿El token ha expirado?
        if ($appClient->expires_at && Carbon::parse($appClient->expires_at)->isPast()) {
            return response()->json(['app_name' => $appClient->name, 'status' => 'expired', 'message' => 'El token de la aplicación ha expirado.']);
        }

        // Si todo está bien
        return response()->json(['app_name' => $appClient->name, 'status' => 'active']);
    }
}


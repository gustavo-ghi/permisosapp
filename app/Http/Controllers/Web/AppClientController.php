<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AppClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Necesario para generar el token

class AppClientController extends Controller
{
    // Muestra una lista de todas las aplicaciones
    public function index()
    {
        $apps = AppClient::latest()->paginate(10);
        return view('apps.index', compact('apps'));
    }

    // Muestra el formulario para crear una nueva aplicación
    public function create()
    {
        return view('apps.create');
    }

    // Guarda la nueva aplicación en la base de datos
 public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expires_at' => 'nullable|date',
        ]);

        AppClient::create([
            'name' => $request->name,
            'api_token' => Str::random(40),
            'is_active' => $request->has('is_active'),
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('apps.index')->with('success', 'Aplicación creada exitosamente.');
    }

    // Muestra el formulario para editar una aplicación existente
    public function edit(AppClient $app)
    {
        return view('apps.edit', compact('app'));
    }

    // Actualiza la aplicación en la base de datos
  public function update(Request $request, AppClient $app)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expires_at' => 'nullable|date',
        ]);

        $app->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('apps.index')->with('success', 'Aplicación actualizada exitosamente.');
    }

    // Elimina una aplicación
    public function destroy(AppClient $app)
    {
        $app->delete();
        return redirect()->route('apps.index')->with('success', 'Aplicación eliminada exitosamente.');
    }

    public function regenerateToken(AppClient $app)
    {
        $app->api_token = Str::random(40);
        $app->save();

        return response()->json([
            'success' => true,
            'new_token' => $app->api_token,
            'message' => '¡Token regenerado exitosamente!'
        ]);
    }
}

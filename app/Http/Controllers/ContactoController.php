<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;


class ContactoController extends Controller
{
    // Función para mostrar una lista de contactos
    public function list()
    {
        return response()->json(Contacto::all());
    }

    // Función para mostrar un contacto específico
    public function detail($id)
    {
        $contacto = Contacto::find($id);

        if (!$contacto) {
            return response()->json(['msg' => 'Contacto no encontrado'], 404);
        }

        return response()->json($contacto);
    }

    // Función para guardar un nuevo Contacto en la base de datos o actualizar uno existente
    public function saveOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $email = strtolower(trim($request->input('email')));
        $id = $request->input('id');

        // Validar que el email no esté en uso por otro contacto
        $existing = Contacto::where('email', $email)
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->first();
        if ($existing) {
            return response()->json(['msg' => 'El email ya está en uso.'], 400);
        }

        $contacto = $id ? Contacto::find($id) : new Contacto();

        if (!$contacto) {
            return response()->json(['msg' => 'Contacto no encontrado para actualizar.'], 404);
        }

        $contacto->fill([
            'name' => $request->input('name'),
            'email' => $email,
            'phone' => $request->input('phone'),
            'notes' => $request->input('notes'),
        ]);

        $contacto->save();

        return response()->json($contacto);
    }

    // Función para eliminar un contacto
    public function destroy($id)
    {
        try {
            $contacto = Contacto::find($id);

            if (!$contacto) {
                return response()->json(['msg' => 'Contacto no encontrado'], 404);
            }

            $contacto->delete();

            return response()->json(['msg' => 'Eliminado correctamente']);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error al eliminar: ' . $ex->getMessage()], 500);
        }
    }
}

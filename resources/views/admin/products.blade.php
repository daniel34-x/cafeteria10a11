@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Gestión de Productos</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="border rounded p-4">
        <h2 class="font-semibold mb-2">Nuevo producto</h2>
        <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-3">
            @csrf
            <input name="name" placeholder="Nombre" class="w-full border rounded p-2" required>
            <textarea name="description" placeholder="Descripción" class="w-full border rounded p-2"></textarea>
            <input name="price" type="number" step="0.01" placeholder="Precio" class="w-full border rounded p-2" required>
            <input name="image_url" placeholder="URL de imagen" class="w-full border rounded p-2">
            <input name="category" placeholder="Categoría" class="w-full border rounded p-2">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="active" value="1" checked> Activo
            </label>
            <button class="bg-[color:var(--cafe)] text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>

    <div class="border rounded p-4">
        <h2 class="font-semibold mb-2">Listado</h2>
        <table class="w-full">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Nombre</th>
                    <th class="py-2">Precio</th>
                    <th class="py-2">Activo</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $prod)
                <tr class="border-b">
                    <td class="py-2">{{ $prod->name }}</td>
                    <td class="py-2">$ {{ number_format($prod->price,2) }}</td>
                    <td class="py-2">{{ $prod->active ? 'Sí' : 'No' }}</td>
                    <td class="py-2">
                        <form method="POST" action="{{ route('admin.products.update', $prod) }}" class="inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="name" value="{{ $prod->name }}">
                            <input type="hidden" name="description" value="{{ $prod->description }}">
                            <input type="hidden" name="price" value="{{ $prod->price }}">
                            <input type="hidden" name="image_url" value="{{ $prod->image_url }}">
                            <input type="hidden" name="category" value="{{ $prod->category }}">
                            <input type="hidden" name="active" value="{{ $prod->active ? 1 : 0 }}">
                            <button class="text-blue-600">Guardar (sin cambios)</button>
                        </form>
                        <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" class="inline ml-2">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\FieldType;
use Illuminate\Http\Request;

class FieldTypeController extends Controller
{
    public function index()
    {
        $fieldTypes = FieldType::all();
        return view('admin.field_types.index', compact('fieldTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        FieldType::create($request->all());

        return back()->with('success', 'Field type added successfully.');
    }

    public function update(Request $request, FieldType $fieldType)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $fieldType->update($request->all());

        return back()->with('success', 'Field type updated successfully.');
    }

    public function destroy(FieldType $fieldType)
    {
        $fieldType->delete();

        return back()->with('success', 'Field type deleted successfully.');
    }
}

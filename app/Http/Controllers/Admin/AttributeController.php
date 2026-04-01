<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::latest()->paginate(20);
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
'name' => 'required|string|max:255|unique:attributes,name,NULL,id,deleted_at,NULL',
            'status' => 'required|in:active,inactive'
        ]);

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id . ',id,deleted_at,NULL',
            'status' => 'required|in:active,inactive'
        ]);

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute moved to Recycle Bin successfully.');
    }

    public function values(Attribute $attribute)
    {
        $values = $attribute->values()->latest()->paginate(20);
        return view('admin.attributes.values.index', compact('attribute', 'values'));
    }

    public function valuesCreate(Attribute $attribute)
    {
        return view('admin.attributes.values.create', compact('attribute'));
    }

    public function valuesStore(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255|unique:attribute_values,value,NULL,id,attribute_id,' . $attribute->id,
            'color_code' => ['nullable', 'string', 'max:20', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ]);

        $validated['attribute_id'] = $attribute->id;
        $attribute->values()->create($validated);

        return redirect()->route('admin.attributes.values', $attribute)->with('success', 'Attribute value created successfully.');
    }

    public function valuesEdit(AttributeValue $value)
    {
        if ($value->attribute->status !== 'active') {
            return redirect()->route('admin.attributes.index')->with('error', 'Attribute must be active.');
        }
        return view('admin.attributes.values.edit', compact('value'));
    }

    public function valuesUpdate(Request $request, AttributeValue $value)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255|unique:attribute_values,value,' . $value->id . ',id,attribute_id,' . $value->attribute_id,
            'color_code' => ['nullable', 'string', 'max:20', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ]);

        $value->update($validated);

        return redirect()->route('admin.attributes.values', $value->attribute)->with('success', 'Attribute value updated successfully.');
    }

    public function valuesDestroy(AttributeValue $value)
    {
        $value->delete();
        return redirect()->route('admin.attributes.values', $value->attribute)->with('success', 'Attribute value deleted successfully.');
    }
}


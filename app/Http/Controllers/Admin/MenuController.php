<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $menuGroups = Menu::when($type !== 'all', fn($q) => $q->type($type))
            ->active()
            ->orderBy('order')
            ->orderBy('id')
            ->with('children')
            ->get()
            ->groupBy('menu_type');

        $menuTypes = Menu::distinct('menu_type')->pluck('menu_type');

        return view('admin.menus.index', compact('menuGroups', 'menuTypes', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Menu::create($validated);

        return redirect()->back()->with('success', 'Menu item created successfully!');
    }

    public function edit(Menu $menu)
    {
        return response()->json([
            'menu' => $menu->only([
                'id',
                'menu_type',
                'title',
                'url',
                'parent_id',
                'order',
                'status',
            ]),
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'menu_type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $menu->update($validated);

        return redirect()->back()->with('success', 'Menu item updated successfully!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->back()->with('success', 'Menu item moved to Recycle Bin successfully.');
    }
}


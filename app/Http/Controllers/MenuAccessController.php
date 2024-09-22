<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPUnit\Framework\isNull;

class MenuAccessController extends Controller
{
    public function index() 
    {
        $menuAcceses = MenuAccess::with('children')
            ->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'menu_name' => $menu->menu_name,
                    'menu_key' => $menu->menu_key,
                    'children_count' => MenuAccess::where('parent_id', $menu->id)->count(),
                    'menu_url' => $menu->menu_url,
                    'parent_id' => $menu->parent_id,
                    'base_menu_access_for' => $menu->base_menu_access_for,
                    'children' => MenuAccess::where('parent_id', $menu->id)->get()
                ];
            });

        return Inertia::render('Admin/UserManagement/MenuAccess/MenuAccess', compact('menuAcceses'));
    }
}

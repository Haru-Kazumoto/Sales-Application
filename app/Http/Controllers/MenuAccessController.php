<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuAccessController extends Controller
{
    public function index() 
    {
        // Ambil semua data MenuAccess
        $menuAcceses = MenuAccess::all();

        // Susun menu dengan parent-child relationship
        $menus = $this->buildMenuTree($menuAcceses);


        return Inertia::render('Admin/UserManagement/MenuAccess/MenuAccess', compact('menus'));
    }

    // Fungsi rekursif untuk menyusun menu tree
    private function buildMenuTree($menuAcceses, $parentId = null)
    {
        $tree = [];

        foreach ($menuAcceses as $menu) {
            // Jika menu ini adalah anak dari parentId yang diberikan (null untuk root menu)
            if ($menu->parent_id == $parentId) {
                // Cari children dari menu ini
                $children = $this->buildMenuTree($menuAcceses, $menu->id);

                // Masukkan data menu ke dalam tree, tambahkan children jika ada
                $tree[] = [
                    'id' => $menu->id,
                    'menu_name' => $menu->menu_name,
                    'menu_key' => $menu->menu_key,
                    'menu_url' => $menu->menu_url,
                    'menu_icon' => $menu->menu_icon,
                    'children_count' => MenuAccess::where('parent_id', $menu->id)->count(),
                    'parent_id' => $menu->parent_id,
                    'base_menu_access_for' => $menu->base_menu_access_for,
                    'children' => $children
                ];
            }
        }

        return $tree;
    }
}
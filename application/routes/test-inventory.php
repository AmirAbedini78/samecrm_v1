<?php

use Illuminate\Support\Facades\Route;

// Test routes for inventory
Route::get('/test-inventory/{id}', function($id) {
    try {
        $inventory = \App\Models\Inventory::find($id);
        if (!$inventory) {
            return response()->json(['error' => 'Inventory not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'inventory' => $inventory,
            'id' => $id
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-inventory-edit/{id}', function($id) {
    try {
        $inventory = \App\Models\Inventory::find($id);
        if (!$inventory) {
            return response()->json(['error' => 'Inventory not found'], 404);
        }
        
        $page = [
            'page' => 'edit',
            'crumbs' => ['Accounting', 'Inventory'],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'Edit Inventory',
            'heading' => 'Edit Inventory',
            'mainmenu_accounting' => 'active',
        ];
        
        return response()->view('pages.inventory.edit', [
            'page' => $page,
            'inventory' => $inventory,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-inventory-show/{id}', function($id) {
    try {
        $inventory = \App\Models\Inventory::find($id);
        if (!$inventory) {
            return response()->json(['error' => 'Inventory not found'], 404);
        }
        
        $page = [
            'page' => 'inventory',
            'crumbs' => ['Accounting', 'Inventory'],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'Inventory Details',
            'heading' => 'Inventory Details',
            'mainmenu_accounting' => 'active',
        ];
        
        return response()->view('pages.inventory.show', [
            'page' => $page,
            'inventory' => $inventory,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

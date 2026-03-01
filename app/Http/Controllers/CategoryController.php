<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request, $id)
    {
        $colocation = Colocation::findOrFail($id);

        $member = $colocation->members->find(Auth::id());
        if (!$member || $member->pivot->role !== 'owner') {
            return redirect()->back()->with('error', 'Seul le propriétaire peut ajouter des catégories.');
        }

        Category::create([
            'colocation_id' => $colocation->id,
            'name'          => $request->name,
        ]);

        return redirect()->back()->with('success', 'Catégorie ajoutée.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('manage', $category);
        $member = $category->colocation->members->find(Auth::id());
        if (!$member || $member->pivot->role !== 'owner') {
            return redirect()->back()->with('error', 'Seul le propriétaire peut supprimer des catégories.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée.');
    }
}
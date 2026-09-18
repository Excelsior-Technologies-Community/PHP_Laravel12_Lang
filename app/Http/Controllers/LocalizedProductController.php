<?php

namespace App\Http\Controllers;

use App\Models\LocalizedProduct;
use Illuminate\Http\Request;

class LocalizedProductController extends Controller
{
    /**
     * Display multilingual product catalog.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $products = LocalizedProduct::query()
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {

                $locale = app()->getLocale();

                $query->where(function ($q) use ($search, $locale) {
                    $q->where("name_translations->{$locale}", 'like', "%{$search}%")
                        ->orWhere("description_translations->{$locale}", 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate(6)
            ->withQueryString();

        return view('products.index', compact(
            'products',
            'search'
        ));
    }

    /**
     * Display a single localized product.
     */
    public function show(string $slug)
    {
        $product = LocalizedProduct::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}
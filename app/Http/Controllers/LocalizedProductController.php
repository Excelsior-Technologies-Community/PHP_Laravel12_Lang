<?php

namespace App\Http\Controllers;

use App\Models\LocalizedProduct;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocalizedProductController extends Controller
{
    /**
     * Display multilingual product catalog.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $category = trim(
            $request->input('category', '')
        );

        $minPrice = $request->input('min_price');

        $maxPrice = $request->input('max_price');

        $sort = $request->input('sort', 'id');

        $locale = app()->getLocale();

        /*
        |--------------------------------------------------------------------------
        | Product Query
        |--------------------------------------------------------------------------
        */

        $productsQuery = LocalizedProduct::query()
            ->where('is_active', true);


        /*
        |--------------------------------------------------------------------------
        | 1. Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $productsQuery->where(function ($query) use (
                $search,
                $locale
            ) {

                $query
                    ->where(
                        "name_translations->{$locale}",
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        "description_translations->{$locale}",
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'category',
                        'like',
                        "%{$search}%"
                    );


                /*
                |--------------------------------------------------------------------------
                | English fallback
                |--------------------------------------------------------------------------
                */

                if ($locale !== 'en') {

                    $query
                        ->orWhere(
                            "name_translations->en",
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            "description_translations->en",
                            'like',
                            "%{$search}%"
                        );
                }

            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Category Filter
        |--------------------------------------------------------------------------
        */

        if ($category !== '') {

            $productsQuery->where(
                'category',
                $category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Minimum Price
        |--------------------------------------------------------------------------
        */

        if (
            $minPrice !== null
            && $minPrice !== ''
        ) {

            $productsQuery->where(
                'price',
                '>=',
                $minPrice
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Maximum Price
        |--------------------------------------------------------------------------
        */

        if (
            $maxPrice !== null
            && $maxPrice !== ''
        ) {

            $productsQuery->where(
                'price',
                '<=',
                $maxPrice
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Sorting
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'price_low':

                $productsQuery->orderBy(
                    'price',
                    'asc'
                );

                break;


            case 'price_high':

                $productsQuery->orderBy(
                    'price',
                    'desc'
                );

                break;


            case 'name_asc':

                $productsQuery->orderByRaw(
                    "JSON_UNQUOTE(
                        JSON_EXTRACT(
                            name_translations,
                            '$.\"{$locale}\"'
                        )
                    ) ASC"
                );

                break;


            case 'name_desc':

                $productsQuery->orderByRaw(
                    "JSON_UNQUOTE(
                        JSON_EXTRACT(
                            name_translations,
                            '$.\"{$locale}\"'
                        )
                    ) DESC"
                );

                break;


            case 'newest':

                $productsQuery->orderByDesc(
                    'created_at'
                );

                break;


            default:

                $productsQuery->orderBy(
                    'id'
                );

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $productsQuery
            ->paginate(5)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = LocalizedProduct::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');


        /*
        |--------------------------------------------------------------------------
        | Product Statistics
        |--------------------------------------------------------------------------
        */

        $statsQuery = LocalizedProduct::query()
            ->where('is_active', true);

        $totalProducts = (clone $statsQuery)->count();

        $averagePrice =
            (clone $statsQuery)->avg('price') ?? 0;

        $minimumPrice =
            (clone $statsQuery)->min('price') ?? 0;

        $maximumPrice =
            (clone $statsQuery)->max('price') ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Session Favorites
        |--------------------------------------------------------------------------
        */

        $favoriteIds = session(
            'favorite_products',
            []
        );

        $favoriteIds = array_map(
            'intval',
            $favoriteIds
        );


        /*
        |--------------------------------------------------------------------------
        | Session Comparison
        |--------------------------------------------------------------------------
        */

        $compareIds = session(
            'compare_products',
            []
        );

        $compareIds = array_map(
            'intval',
            $compareIds
        );


        /*
        |--------------------------------------------------------------------------
        | Recently Viewed
        |--------------------------------------------------------------------------
        */

        $recentIds = session(
            'recently_viewed_products',
            []
        );

        $recentIds = array_map(
            'intval',
            $recentIds
        );


        /*
        |--------------------------------------------------------------------------
        | Recently Viewed Products
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Do not use whereIn() when the array is empty.
        |
        */

        $recentProducts = collect();

        if (! empty($recentIds)) {

            $recentProducts = LocalizedProduct::whereIn(
                'id',
                $recentIds
            )
                ->where(
                    'is_active',
                    true
                )
                ->get()
                ->sortBy(function ($product) use (
                    $recentIds
                ) {

                    return array_search(
                        $product->id,
                        $recentIds
                    );

                })
                ->values();
        }


        return view(
            'products.index',
            compact(
                'products',
                'search',
                'category',
                'minPrice',
                'maxPrice',
                'sort',
                'categories',
                'totalProducts',
                'averagePrice',
                'minimumPrice',
                'maximumPrice',
                'favoriteIds',
                'compareIds',
                'recentProducts'
            )
        );
    }


    /**
     * Display a single localized product.
     */
    public function show(string $slug)
    {
        $product = LocalizedProduct::where(
            'slug',
            $slug
        )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Recently Viewed Products
        |--------------------------------------------------------------------------
        */

        $recentIds = session(
            'recently_viewed_products',
            []
        );

        $recentIds = array_map(
            'intval',
            $recentIds
        );


        $recentIds = array_values(
            array_diff(
                $recentIds,
                [$product->id]
            )
        );


        array_unshift(
            $recentIds,
            $product->id
        );


        $recentIds = array_slice(
            $recentIds,
            0,
            5
        );


        session([
            'recently_viewed_products' => $recentIds,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Related Products
        |--------------------------------------------------------------------------
        */

        $relatedProducts = LocalizedProduct::where(
            'is_active',
            true
        )
            ->where(
                'category',
                $product->category
            )
            ->where(
                'id',
                '!=',
                $product->id
            )
            ->latest()
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Favorites
        |--------------------------------------------------------------------------
        */

        $favoriteIds = session(
            'favorite_products',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Comparison
        |--------------------------------------------------------------------------
        */

        $compareIds = session(
            'compare_products',
            []
        );


        return view(
            'products.show',
            compact(
                'product',
                'relatedProducts',
                'favoriteIds',
                'compareIds'
            )
        );
    }


    /**
     * Toggle favorite product.
     */
    public function toggleFavorite(int $id)
    {
        $product = LocalizedProduct::where(
            'is_active',
            true
        )->findOrFail($id);


        $favoriteIds = session(
            'favorite_products',
            []
        );


        $favoriteIds = array_map(
            'intval',
            $favoriteIds
        );


        if (
            in_array(
                $id,
                $favoriteIds,
                true
            )
        ) {

            $favoriteIds = array_values(
                array_diff(
                    $favoriteIds,
                    [$id]
                )
            );


            $message =
                'Product removed from favorites successfully.';

        } else {

            $favoriteIds[] = $id;


            $message =
                'Product added to favorites successfully.';
        }


        session([
            'favorite_products' => $favoriteIds,
        ]);


        return back()->with(
            'success',
            $message
        );
    }


    /**
     * Toggle product comparison.
     */
    public function toggleCompare(int $id)
    {
        $product = LocalizedProduct::where(
            'is_active',
            true
        )->findOrFail($id);


        $compareIds = session(
            'compare_products',
            []
        );


        $compareIds = array_map(
            'intval',
            $compareIds
        );


        if (
            in_array(
                $id,
                $compareIds,
                true
            )
        ) {

            $compareIds = array_values(
                array_diff(
                    $compareIds,
                    [$id]
                )
            );


            $message =
                'Product removed from comparison successfully.';

        } else {


            /*
            |--------------------------------------------------------------------------
            | Maximum 3 Products
            |--------------------------------------------------------------------------
            */

            if (count($compareIds) >= 3) {

                return back()->with(
                    'error',
                    'You can compare a maximum of 3 products.'
                );
            }


            $compareIds[] = $id;


            $message =
                'Product added to comparison successfully.';
        }


        session([
            'compare_products' => $compareIds,
        ]);


        return back()->with(
            'success',
            $message
        );
    }


    /**
     * Display comparison page.
     */
    public function compare()
    {
        $compareIds = session(
            'compare_products',
            []
        );


        $compareIds = array_map(
            'intval',
            $compareIds
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Avoid whereIn([]).
        |--------------------------------------------------------------------------
        */

        $products = collect();


        if (! empty($compareIds)) {

            $products = LocalizedProduct::whereIn(
                'id',
                $compareIds
            )
                ->where(
                    'is_active',
                    true
                )
                ->get()
                ->sortBy(function ($product) use (
                    $compareIds
                ) {

                    return array_search(
                        $product->id,
                        $compareIds
                    );

                })
                ->values();
        }


        return view(
            'products.compare',
            compact('products')
        );
    }


    /**
     * Clear comparison.
     */
    public function clearCompare()
    {
        session()->forget(
            'compare_products'
        );


        return back()->with(
            'success',
            'Comparison list cleared successfully.'
        );
    }


    /**
     * Display favorite products.
     */
    public function favorites()
    {
        $favoriteIds = session(
            'favorite_products',
            []
        );


        $favoriteIds = array_map(
            'intval',
            $favoriteIds
        );


        $products = collect();


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Avoid whereIn([]).
        |--------------------------------------------------------------------------
        */

        if (! empty($favoriteIds)) {

            $products = LocalizedProduct::whereIn(
                'id',
                $favoriteIds
            )
                ->where(
                    'is_active',
                    true
                )
                ->get()
                ->sortBy(function ($product) use (
                    $favoriteIds
                ) {

                    return array_search(
                        $product->id,
                        $favoriteIds
                    );

                })
                ->values();
        }


        return view(
            'products.favorites',
            compact('products')
        );
    }


    /**
     * Export filtered products as CSV.
     */
    public function export(
        Request $request
    ): StreamedResponse {

        $search = trim(
            $request->input(
                'search',
                ''
            )
        );


        $category = trim(
            $request->input(
                'category',
                ''
            )
        );


        $minPrice =
            $request->input('min_price');


        $maxPrice =
            $request->input('max_price');


        $locale =
            app()->getLocale();


        $query = LocalizedProduct::query()
            ->where(
                'is_active',
                true
            );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(
                function ($q) use (
                    $search,
                    $locale
                ) {

                    $q->where(
                        "name_translations->{$locale}",
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            "description_translations->{$locale}",
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'category',
                            'like',
                            "%{$search}%"
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | English fallback
                    |--------------------------------------------------------------------------
                    */

                    if ($locale !== 'en') {

                        $q->orWhere(
                            "name_translations->en",
                            'like',
                            "%{$search}%"
                        )
                            ->orWhere(
                                "description_translations->en",
                                'like',
                                "%{$search}%"
                            );
                    }

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($category !== '') {

            $query->where(
                'category',
                $category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if (
            $minPrice !== null
            && $minPrice !== ''
        ) {

            $query->where(
                'price',
                '>=',
                $minPrice
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if (
            $maxPrice !== null
            && $maxPrice !== ''
        ) {

            $query->where(
                'price',
                '<=',
                $maxPrice
            );
        }


        $products = $query
            ->orderBy('id')
            ->get();


        $filename =
            'localized-products-'
            . now()->format(
                'Y-m-d-H-i-s'
            )
            . '.csv';


        return response()->streamDownload(

            function () use (
                $products
            ) {

                $handle = fopen(
                    'php://output',
                    'w'
                );


                fputcsv(
                    $handle,
                    [
                        'ID',
                        'Name',
                        'Description',
                        'Category',
                        'Price',
                        'Language',
                        'Active',
                    ]
                );


                foreach (
                    $products
                    as $product
                ) {

                    fputcsv(
                        $handle,
                        [
                            $product->id,
                            $product->localized_name,
                            $product->localized_description,
                            $product->category,
                            $product->price,
                            strtoupper(
                                app()->getLocale()
                            ),
                            $product->is_active
                                ? 'Yes'
                                : 'No',
                        ]
                    );
                }


                fclose($handle);
            },

            $filename
        );
    }
}
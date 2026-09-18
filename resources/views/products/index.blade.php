<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('Multilingual Products') }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f7fb;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .product-card {
            height: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .price {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .language-badge {
            font-size: 0.85rem;
        }

        .search-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            {{ __('Multilingual Products') }}
        </a>

        <div class="d-flex gap-2">

            <a href="{{ route('products.index') }}"
               class="btn btn-outline-light btn-sm">
                {{ __('Products') }}
            </a>

            <a href="{{ route('form') }}"
               class="btn btn-outline-light btn-sm">
                {{ __('Form') }}
            </a>

            <a href="{{ route('localization.dashboard') }}"
               class="btn btn-outline-light btn-sm">
                {{ __('Dashboard') }}
            </a>

        </div>
    </div>
</nav>


<div class="container py-5">

    {{-- Page Header --}}
    <div class="text-center mb-4">

        <h1 class="fw-bold">
            {{ __('Product Catalog') }}
        </h1>

        <p class="text-muted">
            {{ __('Browse products in your selected language.') }}
        </p>

        <span class="badge bg-primary language-badge">
            {{ __('Current Language') }}:
            {{ strtoupper(app()->getLocale()) }}
        </span>

    </div>


    {{-- Search --}}
    <div class="search-box mb-4">

        <form method="GET" action="{{ route('products.index') }}">

            <div class="row g-2">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control form-control-lg"
                        placeholder="{{ __('Search products') }}"
                    >

                </div>

                <div class="col-md-2">

                    <button type="submit"
                            class="btn btn-primary btn-lg w-100">
                        {{ __('Search') }}
                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- Products --}}
    @if($products->count())

        <div class="row g-4">

            @foreach($products as $product)

                <div class="col-md-6 col-lg-4">

                    <div class="card product-card">

                        <div class="card-body d-flex flex-column">

                            <div class="mb-2">

                                <span class="badge bg-secondary">
                                    {{ $product->category ?? 'General' }}
                                </span>

                            </div>

                            <h4 class="card-title fw-bold">
                                {{ $product->localized_name }}
                            </h4>

                            <p class="card-text text-muted">
                                {{ $product->localized_description }}
                            </p>

                            <div class="mt-auto">

                                <div class="price mb-3">
                                    ₹{{ number_format($product->price, 2) }}
                                </div>

                                <a
                                    href="{{ route('products.show', $product->slug) }}"
                                    class="btn btn-dark w-100"
                                >
                                    {{ __('View Details') }}
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- Pagination --}}
        <div class="mt-5">

            {{ $products->links() }}

        </div>

    @else

        <div class="alert alert-warning text-center">

            <h5 class="mb-2">
                {{ __('No products found') }}
            </h5>

            <p class="mb-0">
                {{ __('Try another search term.') }}
            </p>

        </div>

    @endif

</div>

</body>
</html>
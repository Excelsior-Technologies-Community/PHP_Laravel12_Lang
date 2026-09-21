<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Products</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .hero {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 25px;
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
            transition: .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .product-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
            transition: .2s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-icon {
            height: 170px;
            background: linear-gradient(135deg, #eef2ff, #f8f9ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }

        .price {
            font-size: 22px;
            font-weight: 700;
        }

        .filter-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
        }

        .translation-progress {
            height: 7px;
        }

        .recent-card {
            border: 0;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .06);
        }

        .pagination {
            margin-top: 30px;
        }

        .pagination .page-link {
            min-width: 42px;
            text-align: center;
            margin: 0 3px;
            border-radius: 8px !important;
        }

        .pagination .active .page-link {
            font-weight: 700;
        }

        .alert {
            border-radius: 12px;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">

        <div class="container">

            <a
                class="navbar-brand"
                href="{{ route('home') }}">
                🌐 Localization App
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                class="collapse navbar-collapse"
                id="mainNavbar">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route('home') }}">
                            Home
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link active"
                            href="{{ route('products.index') }}">
                            Products
                        </a>

                    </li>

                    @if (Route::has('products.favorites'))

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route('products.favorites') }}">
                            ❤️ Favorites
                        </a>

                    </li>

                    @endif

                    @if (Route::has('products.compare'))

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route('products.compare') }}">
                            ⚖️ Compare
                        </a>

                    </li>

                    @endif

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route('form') }}">
                            Form
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route('localization.dashboard') }}">
                            Dashboard
                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <div class="container py-4">





        {{-- HERO --}}

        <div class="hero">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h1 class="fw-bold mb-2">
                        Product Management
                    </h1>

                    <p class="mb-0">
                        Search, filter, sort and manage localized products.
                    </p>

                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <a
                        href="{{ route('localization.dashboard') }}"
                        class="btn btn-light">
                        📊 Localization Dashboard
                    </a>

                </div>

            </div>

        </div>

        {{-- SUCCESS MESSAGE --}}

        @if (session('success'))

        <div
            class="alert alert-success alert-dismissible fade show shadow-sm"
            role="alert">

            <strong>✅ Success!</strong>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>

        </div>

        @endif


        {{-- ERROR MESSAGE --}}

        @if (session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show shadow-sm"
            role="alert">

            <strong>⚠️ Error!</strong>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>

        </div>

        @endif


        {{-- VALIDATION ERRORS --}}

        @if ($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show shadow-sm"
            role="alert">

            <strong>⚠️ Please check the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>

        </div>

        @endif


        {{-- STATISTICS --}}

        <div class="row g-4 mb-4">

            <div class="col-md-3">

                <div class="card stat-card p-3">

                    <div class="text-muted">
                        Total Products
                    </div>

                    <h2 class="fw-bold mb-0">
                        {{ $totalProducts }}
                    </h2>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card p-3">

                    <div class="text-muted">
                        Categories
                    </div>

                    <h2 class="fw-bold mb-0">
                        {{ $categories->count() }}
                    </h2>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card p-3">

                    <div class="text-muted">
                        Average Price
                    </div>

                    <h2 class="fw-bold mb-0">
                        ₹{{ number_format((float) $averagePrice, 2) }}
                    </h2>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card p-3">

                    <div class="text-muted">
                        Price Range
                    </div>

                    <h5 class="fw-bold mb-0 mt-2">

                        ₹{{ number_format((float) $minimumPrice, 0) }}

                        -

                        ₹{{ number_format((float) $maximumPrice, 0) }}

                    </h5>

                </div>

            </div>

        </div>


        {{-- FILTERS --}}

        <div class="card filter-card mb-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h4 class="fw-bold mb-0">
                        🔎 Search & Advanced Filters
                    </h4>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-outline-secondary btn-sm">
                        Clear Filters
                    </a>

                </div>


                <form
                    method="GET"
                    action="{{ route('products.index') }}">

                    <div class="row g-3">


                        {{-- SEARCH --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Search product or category...">

                        </div>


                        {{-- CATEGORY --}}

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Category
                            </label>

                            <select
                                name="category"
                                class="form-select">

                                <option value="">
                                    All
                                </option>

                                @foreach ($categories as $cat)

                                <option
                                    value="{{ $cat }}"
                                    {{ ($category ?? '') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- MIN PRICE --}}

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Min Price
                            </label>

                            <input
                                type="number"
                                name="min_price"
                                value="{{ $minPrice ?? '' }}"
                                class="form-control"
                                min="0"
                                step="0.01"
                                placeholder="₹0">

                        </div>


                        {{-- MAX PRICE --}}

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Max Price
                            </label>

                            <input
                                type="number"
                                name="max_price"
                                value="{{ $maxPrice ?? '' }}"
                                class="form-control"
                                min="0"
                                step="0.01"
                                placeholder="₹9999">

                        </div>


                        {{-- SORT --}}

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Sort By
                            </label>

                            <select
                                name="sort"
                                class="form-select">

                                <option
                                    value="id"
                                    {{ ($sort ?? 'id') == 'id' ? 'selected' : '' }}>
                                    Default
                                </option>

                                <option
                                    value="newest"
                                    {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>
                                    Newest
                                </option>

                                <option
                                    value="price_low"
                                    {{ ($sort ?? '') == 'price_low' ? 'selected' : '' }}>
                                    Price Low → High
                                </option>

                                <option
                                    value="price_high"
                                    {{ ($sort ?? '') == 'price_high' ? 'selected' : '' }}>
                                    Price High → Low
                                </option>

                                <option
                                    value="name_asc"
                                    {{ ($sort ?? '') == 'name_asc' ? 'selected' : '' }}>
                                    Name A → Z
                                </option>

                                <option
                                    value="name_desc"
                                    {{ ($sort ?? '') == 'name_desc' ? 'selected' : '' }}>
                                    Name Z → A
                                </option>

                            </select>

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-12">

                            <button
                                type="submit"
                                class="btn btn-primary">
                                🔍 Apply Filters
                            </button>


                            @if (Route::has('products.export'))

                            <a
                                href="{{ route('products.export', request()->query()) }}"
                                class="btn btn-success">
                                📥 Export CSV
                            </a>

                            @endif

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- RESULT COUNT --}}

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="fw-bold mb-0">
                Products
            </h4>

            <span class="badge bg-primary">
                {{ $products->total() }} Results
            </span>

        </div>


        {{-- PRODUCTS --}}

        @if ($products->count())

        <div class="row g-4">

            @foreach ($products as $product)

            <div class="col-md-6 col-lg-4">

                <div class="card product-card">

                    <div class="product-icon">
                        🛍️
                    </div>


                    <div class="card-body d-flex flex-column">


                        {{-- CATEGORY --}}

                        <div class="mb-2">

                            <span class="badge bg-secondary">
                                {{ $product->category }}
                            </span>

                        </div>


                        {{-- TRANSLATION STATUS --}}

                        @php

                        $translationCount =
                        $product->translation_count ?? 0;

                        $translationPercentage =
                        $product->translation_percentage ?? 0;

                        @endphp


                        <div class="mb-3">

                            <div class="d-flex justify-content-between">

                                <small class="text-muted">
                                    Translation
                                </small>

                                <small class="fw-bold">

                                    {{ $translationCount }}/5

                                    ({{ $translationPercentage }}%)

                                </small>

                            </div>


                            <div class="progress translation-progress mt-1">

                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: {{ $translationPercentage }}%"
                                    aria-valuenow="{{ $translationPercentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"></div>

                            </div>

                        </div>


                        {{-- NAME --}}

                        <h5 class="fw-bold">
                            {{ $product->localized_name }}
                        </h5>


                        {{-- DESCRIPTION --}}

                        <p class="text-muted flex-grow-1">

                            {{ \Illuminate\Support\Str::limit(
                                    $product->localized_description,
                                    120
                                ) }}

                        </p>


                        {{-- PRICE --}}

                        <div class="price mb-3">

                            ₹{{ number_format((float) $product->price, 2) }}

                        </div>


                        {{-- ACTIONS --}}

                        <div class="d-flex gap-2 flex-wrap">


                            {{-- VIEW --}}

                            <a
                                href="{{ route('products.show', $product->slug) }}"
                                class="btn btn-primary btn-sm">
                                👁 View
                            </a>


                            {{-- FAVORITE --}}

                            @if (Route::has('products.favorite'))

                            <form
                                method="POST"
                                action="{{ route('products.favorite', $product->id) }}"
                                class="d-inline">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger btn-sm">

                                    @if (
                                    in_array(
                                    $product->id,
                                    $favoriteIds ?? []
                                    )
                                    )

                                    ❤️ Favorited

                                    @else

                                    ♡ Favorite

                                    @endif

                                </button>

                            </form>

                            @endif


                            {{-- COMPARE --}}

                            @if (Route::has('products.compare.toggle'))

                            <form
                                method="POST"
                                action="{{ route('products.compare.toggle', $product->id) }}"
                                class="d-inline">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-dark btn-sm">

                                    @if (
                                    in_array(
                                    $product->id,
                                    $compareIds ?? []
                                    )
                                    )

                                    ⚖️ Compared

                                    @else

                                    ⚖️ Compare

                                    @endif

                                </button>

                            </form>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>


        {{-- NUMBER ONLY PAGINATION --}}

        @if ($products->lastPage() > 1)

        <nav
            aria-label="Product pagination"
            class="mt-4">

            <ul class="pagination justify-content-center">

                @for (
                $page = 1;
                $page <= $products->lastPage();
                    $page++
                    )

                    <li
                        class="page-item
                            {{ $page == $products->currentPage() ? 'active' : '' }}">

                        @if ($page == $products->currentPage())

                        <span class="page-link">
                            {{ $page }}
                        </span>

                        @else

                        <a
                            class="page-link"
                            href="{{ $products->url($page) }}">
                            {{ $page }}
                        </a>

                        @endif

                    </li>

                    @endfor

            </ul>

        </nav>

        @endif


        @else


        {{-- NO PRODUCTS --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div class="display-4 mb-3">
                    📦
                </div>

                <h4 class="fw-bold">
                    No Products Found
                </h4>

                <p class="text-muted">
                    Try changing your search or filter criteria.
                </p>

                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-primary">
                    Clear Filters
                </a>

            </div>

        </div>

        @endif


        {{-- RECENTLY VIEWED --}}

        @if (
        isset($recentProducts)
        && $recentProducts->count()
        )

        <div class="mt-5">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="fw-bold mb-0">
                    🕒 Recently Viewed Products
                </h4>

            </div>


            <div class="row g-3">

                @foreach ($recentProducts as $recent)

                <div class="col-md-6 col-lg-3">

                    <div class="card recent-card h-100">

                        <div class="card-body">

                            <span class="badge bg-secondary mb-2">
                                {{ $recent->category }}
                            </span>

                            <h6 class="fw-bold">
                                {{ $recent->localized_name }}
                            </h6>

                            <div class="fw-bold mb-3">

                                ₹{{ number_format(
                                        (float) $recent->price,
                                        2
                                    ) }}

                            </div>

                            <a
                                href="{{ route('products.show', $recent->slug) }}"
                                class="btn btn-outline-primary btn-sm">
                                View Product
                            </a>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        @endif


    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    {{-- AUTO HIDE SUCCESS / ERROR MESSAGE --}}

    <script>
        setTimeout(function() {

            const alerts = document.querySelectorAll(
                '.alert-success, .alert-danger'
            );

            alerts.forEach(function(alertElement) {

                const alert =
                    bootstrap.Alert.getOrCreateInstance(alertElement);

                alert.close();

            });

        }, 4000);
    </script>


</body>

</html>
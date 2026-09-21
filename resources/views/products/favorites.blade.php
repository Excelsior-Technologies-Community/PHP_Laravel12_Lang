<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ __('Favorite Products') }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">

            <a
                href="{{ route('products.index') }}"
                class="navbar-brand">
                {{ __('Multilingual Products') }}
            </a>

        </div>

    </nav>


    <div class="container py-5">

        <h1 class="fw-bold mb-4">
            ❤️ {{ __('Favorite Products') }}
        </h1>


        @if($products->count())

        <div class="row g-4">

            @foreach($products as $product)

            <div class="col-md-4">

                <div class="card h-100 shadow-sm border-0">

                    <div class="card-body">

                        <span class="badge bg-secondary">
                            {{ $product->category }}
                        </span>

                        <h4 class="fw-bold mt-3">
                            {{ $product->localized_name }}
                        </h4>

                        <p class="text-muted">
                            {{ $product->localized_description }}
                        </p>

                        <h5>
                            ₹{{ number_format($product->price, 2) }}
                        </h5>

                        <a
                            href="{{ route('products.show', $product->slug) }}"
                            class="btn btn-dark mt-3">
                            {{ __('View Details') }}
                        </a>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <div class="alert alert-info">

            {{ __('You have no favorite products yet.') }}

        </div>

        @endif


        <a
            href="{{ route('products.index') }}"
            class="btn btn-primary mt-4">
            {{ __('Back to Products') }}
        </a>

    </div>

</body>

</html>
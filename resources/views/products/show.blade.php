<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $product->localized_name }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f7fb;
        }

        .product-detail {
            background: white;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('products.index') }}">
            {{ __('Multilingual Products') }}
        </a>

        <a
            href="{{ route('products.index') }}"
            class="btn btn-outline-light btn-sm"
        >
            {{ __('Back to Products') }}
        </a>

    </div>
</nav>


<div class="container py-5">

    <div class="product-detail">

        <span class="badge bg-secondary mb-3">
            {{ $product->category ?? 'General' }}
        </span>

        <h1 class="fw-bold mb-3">
            {{ $product->localized_name }}
        </h1>

        <p class="text-muted fs-5 mb-4">
            {{ $product->localized_description }}
        </p>

        <div class="price mb-4">
            ₹{{ number_format($product->price, 2) }}
        </div>

        <div class="mb-4">

            <strong>
                {{ __('Current Language') }}:
            </strong>

            {{ strtoupper(app()->getLocale()) }}

        </div>

        <a
            href="{{ route('products.index') }}"
            class="btn btn-dark"
        >
            {{ __('Back to Products') }}
        </a>

    </div>

</div>

</body>
</html>
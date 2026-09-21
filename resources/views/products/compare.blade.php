<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ __('Compare Products') }}</title>

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

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="fw-bold">
                ⚖ {{ __('Compare Products') }}
            </h1>


            @if($products->count())

            <form
                method="POST"
                action="{{ route('products.compare.clear') }}">

                @csrf

                <button class="btn btn-danger">

                    {{ __('Clear Comparison') }}

                </button>

            </form>

            @endif

        </div>


        @if($products->count())

        <div class="table-responsive">

            <table class="table table-bordered bg-white shadow-sm">

                <thead class="table-dark">

                    <tr>

                        <th>
                            {{ __('Feature') }}
                        </th>

                        @foreach($products as $product)

                        <th>
                            {{ $product->localized_name }}
                        </th>

                        @endforeach

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <th>
                            {{ __('Category') }}
                        </th>

                        @foreach($products as $product)

                        <td>
                            {{ $product->category }}
                        </td>

                        @endforeach

                    </tr>


                    <tr>

                        <th>
                            {{ __('Price') }}
                        </th>

                        @foreach($products as $product)

                        <td class="fw-bold">

                            ₹{{ number_format($product->price, 2) }}

                        </td>

                        @endforeach

                    </tr>


                    <tr>

                        <th>
                            {{ __('Translation') }}
                        </th>

                        @foreach($products as $product)

                        <td>

                            <span class="badge bg-success">

                                {{ $product->translation_percentage }}%

                            </span>

                        </td>

                        @endforeach

                    </tr>


                    <tr>

                        <th>
                            {{ __('Description') }}
                        </th>

                        @foreach($products as $product)

                        <td>

                            {{ $product->localized_description }}

                        </td>

                        @endforeach

                    </tr>


                    <tr>

                        <th>
                            {{ __('Action') }}
                        </th>

                        @foreach($products as $product)

                        <td>

                            <a
                                href="{{ route('products.show', $product->slug) }}"
                                class="btn btn-dark btn-sm">
                                {{ __('View') }}
                            </a>

                        </td>

                        @endforeach

                    </tr>

                </tbody>

            </table>

        </div>

        @else

        <div class="alert alert-info">

            {{ __('No products selected for comparison.') }}

        </div>

        @endif


        <a
            href="{{ route('products.index') }}"
            class="btn btn-primary">
            {{ __('Back to Products') }}
        </a>

    </div>

</body>

</html>
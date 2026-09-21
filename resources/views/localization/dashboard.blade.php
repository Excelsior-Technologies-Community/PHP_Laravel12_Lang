<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ __('Localization Dashboard') }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
            height: 100%;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
        }

        .section-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">

            <a
                class="navbar-brand fw-bold"
                href="{{ route('home') }}">
                {{ __('Localization Dashboard') }}
            </a>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-outline-light btn-sm">
                    {{ __('Products') }}
                </a>

                <a
                    href="{{ route('form') }}"
                    class="btn btn-outline-light btn-sm">
                    {{ __('Form') }}
                </a>

            </div>

        </div>

    </nav>


    <div class="container py-5">

        <div class="text-center mb-5">

            <h1 class="fw-bold">
                {{ __('Localization Dashboard') }}
            </h1>

            <p class="text-muted">
                {{ __('Monitor languages, translations and products.') }}
            </p>

            <span class="badge bg-primary">

                {{ __('Current Language') }}:

                {{ strtoupper(app()->getLocale()) }}

            </span>

        </div>


        {{-- Statistics --}}

        <div class="row g-4 mb-5">

            <div class="col-md-3">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Languages') }}
                        </h6>

                        <div class="stat-number text-primary">
                            {{ $totalLanguages }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Products') }}
                        </h6>

                        <div class="stat-number text-success">
                            {{ $totalProducts }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Active Products') }}
                        </h6>

                        <div class="stat-number text-info">
                            {{ $activeProducts }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Categories') }}
                        </h6>

                        <div class="stat-number text-warning">
                            {{ $totalCategories }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Price Statistics --}}

        <div class="row g-4 mb-5">

            <div class="col-md-4">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Average Price') }}
                        </h6>

                        <div class="stat-number">
                            ₹{{ number_format($averagePrice, 2) }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Minimum Price') }}
                        </h6>

                        <div class="stat-number">
                            ₹{{ number_format($minimumPrice, 2) }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card stat-card">

                    <div class="card-body text-center">

                        <h6>
                            {{ __('Maximum Price') }}
                        </h6>

                        <div class="stat-number">
                            ₹{{ number_format($maximumPrice, 2) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Translation Status --}}

        <div class="card section-card mb-5">

            <div class="card-body">

                <h4 class="fw-bold mb-4">
                    {{ __('Translation Status') }}
                </h4>


                <div class="row text-center">

                    <div class="col-md-4">

                        <div class="alert alert-success">

                            <h5>
                                {{ __('Complete') }}
                            </h5>

                            <h2>
                                {{ $translationComplete }}
                            </h2>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="alert alert-warning">

                            <h5>
                                {{ __('Partial') }}
                            </h5>

                            <h2>
                                {{ $translationPartial }}
                            </h2>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="alert alert-danger">

                            <h5>
                                {{ __('Incomplete') }}
                            </h5>

                            <h2>
                                {{ $translationIncomplete }}
                            </h2>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Category Statistics --}}

        <div class="card section-card mb-5">

            <div class="card-body">

                <h4 class="fw-bold mb-4">
                    {{ __('Products by Category') }}
                </h4>


                @if($categoryUsage->count())

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    {{ __('Category') }}
                                </th>

                                <th>
                                    {{ __('Products') }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($categoryUsage as $category)

                            <tr>

                                <td>
                                    {{ $category->category }}
                                </td>

                                <td>

                                    <span class="badge bg-primary">

                                        {{ $category->total }}

                                    </span>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                @else

                <div class="alert alert-info">
                    {{ __('No category data available.') }}
                </div>

                @endif

            </div>

        </div>


        {{-- Languages --}}

        <div class="card section-card mb-5">

            <div class="card-body">

                <h4 class="fw-bold mb-4">
                    {{ __('Supported Languages') }}
                </h4>


                <div class="row g-3">

                    @foreach($languages as $code => $language)

                    <div class="col-md-4">

                        <div class="border rounded p-3 d-flex justify-content-between">

                            <strong>
                                {{ $language }}
                            </strong>

                            <span class="badge bg-dark">
                                {{ strtoupper($code) }}
                            </span>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- Language Usage --}}

        <div class="card section-card mb-5">

            <div class="card-body">

                <h4 class="fw-bold mb-4">
                    {{ __('Language Usage Statistics') }}
                </h4>


                @if($languageUsage->count())

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    {{ __('Language') }}
                                </th>

                                <th>
                                    {{ __('Code') }}
                                </th>

                                <th>
                                    {{ __('Switch Count') }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($languageUsage as $usage)

                            <tr>

                                <td>
                                    {{ $usage->language_name }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ strtoupper($usage->locale) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $usage->total }}
                                    </span>
                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                @else

                <div class="alert alert-info">

                    {{ __('No language history available yet.') }}

                </div>

                @endif

            </div>

        </div>


        {{-- Recent Changes --}}

        <div class="card section-card">

            <div class="card-body">

                <h4 class="fw-bold mb-4">
                    {{ __('Recent Language Changes') }}
                </h4>


                @if($recentChanges->count())

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    {{ __('Language') }}
                                </th>

                                <th>
                                    {{ __('Code') }}
                                </th>

                                <th>
                                    {{ __('IP Address') }}
                                </th>

                                <th>
                                    {{ __('Date & Time') }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($recentChanges as $history)

                            <tr>

                                <td>
                                    {{ $history->language_name }}
                                </td>

                                <td>

                                    <span class="badge bg-secondary">

                                        {{ strtoupper($history->locale) }}

                                    </span>

                                </td>

                                <td>
                                    {{ $history->ip_address ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $history->created_at->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                @else

                <div class="alert alert-info">

                    {{ __('No language changes recorded.') }}

                </div>

                @endif

            </div>

        </div>

    </div>

</body>

</html>
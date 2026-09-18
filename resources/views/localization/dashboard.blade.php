<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ __('Localization Dashboard') }}</title>

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

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
        }

        .section-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .language-code {
            font-family: monospace;
            font-weight: 700;
        }

    </style>

</head>

<body>


{{-- Navbar --}}

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand"
            href="{{ route('home') }}"
        >
            {{ __('Localization Dashboard') }}
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route('home') }}"
                class="btn btn-outline-light btn-sm"
            >
                {{ __('Home') }}
            </a>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-outline-light btn-sm"
            >
                {{ __('Products') }}
            </a>

            <a
                href="{{ route('form') }}"
                class="btn btn-outline-light btn-sm"
            >
                {{ __('Form') }}
            </a>

        </div>

    </div>

</nav>



<div class="container py-5">


    {{-- Page Header --}}

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            {{ __('Localization Dashboard') }}
        </h1>

        <p class="text-muted">

            {{ __('Monitor languages, translations and language usage.') }}

        </p>

        <span class="badge bg-primary">

            {{ __('Current Language') }}:
            {{ strtoupper(app()->getLocale()) }}

        </span>

    </div>



    {{-- Statistics Cards --}}

    <div class="row g-4 mb-5">


        {{-- Available Languages --}}

        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        {{ __('Available Languages') }}
                    </h6>

                    <div class="stat-number text-primary">
                        {{ $totalLanguages }}
                    </div>

                </div>

            </div>

        </div>



        {{-- Total Products --}}

        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        {{ __('Total Products') }}
                    </h6>

                    <div class="stat-number text-success">
                        {{ $totalProducts }}
                    </div>

                </div>

            </div>

        </div>



        {{-- Active Products --}}

        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        {{ __('Active Products') }}
                    </h6>

                    <div class="stat-number text-info">
                        {{ $activeProducts }}
                    </div>

                </div>

            </div>

        </div>



        {{-- Total Translations --}}

        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        {{ __('Total Translations') }}
                    </h6>

                    <div class="stat-number text-warning">
                        {{ $totalTranslations }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Supported Languages --}}

    <div class="card section-card mb-5">

        <div class="card-body">

            <h4 class="fw-bold mb-4">
                {{ __('Supported Languages') }}
            </h4>


            <div class="row g-3">

                @foreach($languages as $code => $language)

                    <div class="col-md-6 col-lg-4">

                        <div class="border rounded p-3 d-flex justify-content-between align-items-center">

                            <div>

                                <strong>
                                    {{ $language }}
                                </strong>

                            </div>

                            <span class="badge bg-dark language-code">

                                {{ strtoupper($code) }}

                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



    {{-- Language Usage Statistics --}}

    <div class="card section-card mb-5">

        <div class="card-body">

            <h4 class="fw-bold mb-4">
                {{ __('Language Usage Statistics') }}
            </h4>


            @if($languageUsage->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

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

                                        <strong>
                                            {{ $usage->language_name }}
                                        </strong>

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

                <div class="alert alert-info mb-0">

                    {{ __('No language history available yet.') }}

                </div>

            @endif

        </div>

    </div>



    {{-- Recent Language Changes --}}

    <div class="card section-card">

        <div class="card-body">

            <h4 class="fw-bold mb-4">

                {{ __('Recent Language Changes') }}

            </h4>


            @if($recentChanges->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

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

                <div class="alert alert-info mb-0">

                    {{ __('No language changes recorded.') }}

                </div>

            @endif

        </div>

    </div>


</div>


</body>

</html>
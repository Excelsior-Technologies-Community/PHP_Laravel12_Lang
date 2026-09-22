<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ $product->localized_name }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .product-box {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .08);
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
        }

        .related-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .07);
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">

            <a
                class="navbar-brand"
                href="{{ route('products.index') }}">
                {{ __('Multilingual Products') }}
            </a>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-outline-light btn-sm">
                    {{ __('Products') }}
                </a>

                <a
                    href="{{ route('products.favorites') }}"
                    class="btn btn-outline-light btn-sm">
                    {{ __('Favorites') }}
                </a>

                <a
                    href="{{ route('products.compare') }}"
                    class="btn btn-outline-light btn-sm">
                    {{ __('Compare') }}
                </a>

            </div>

        </div>

    </nav>


    <div class="container py-5">

        <div class="product-box">

            <div class="mb-3">

                <span class="badge bg-secondary">
                    {{ $product->category ?? __('General') }}
                </span>

                <span class="badge bg-primary">
                    {{ $product->translation_percentage }}%
                    {{ __('Translated') }}
                </span>

            </div>


            <h1 class="fw-bold">
                {{ $product->localized_name }}
            </h1>


            <p class="text-muted fs-5" id="product-description-text">
                {{ $product->localized_description }}
            </p>

            {{-- MULTILINGUAL TEXT-TO-SPEECH (TTS) AUDIO READER --}}
            <div class="p-3 my-3 bg-light border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">🗣️</span>
                    <div>
                        <strong class="d-block text-dark">{{ __('Multilingual Audio Reader') }}</strong>
                        <small class="text-muted">{{ __('Listen to product description in') }} <span class="badge bg-dark">{{ strtoupper(app()->getLocale()) }}</span></small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2" id="tts-controls">
                    <button type="button" class="btn btn-sm btn-success fw-bold d-flex align-items-center gap-1" id="btn-speak">
                        <span>🔊 {{ __('Listen') }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary fw-bold d-flex align-items-center gap-1 d-none" id="btn-pause">
                        <span>⏸️ {{ __('Pause') }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger fw-bold d-flex align-items-center gap-1 d-none" id="btn-stop">
                        <span>⏹️ {{ __('Stop') }}</span>
                    </button>
                </div>
            </div>

            <div class="price my-4">

                ₹{{ number_format($product->price, 2) }}

            </div>


            <div class="d-flex gap-2 flex-wrap">

                <form
                    method="POST"
                    action="{{ route('products.favorite', $product->id) }}">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger">
                        {{ in_array($product->id, $favoriteIds)
                        ? __('Remove Favorite')
                        : __('Add to Favorites') }}
                    </button>

                </form>


                <form
                    method="POST"
                    action="{{ route('products.compare.toggle', $product->id) }}">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning">
                        {{ in_array($product->id, $compareIds)
                        ? __('Remove from Compare')
                        : __('Add to Compare') }}
                    </button>

                </form>


                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-outline-dark">
                    {{ __('Back to Products') }}
                </a>

            </div>

        </div>


        @if($relatedProducts->count())

        <div class="mt-5">

            <h2 class="fw-bold mb-4">
                {{ __('Related Products') }}
            </h2>


            <div class="row g-4">

                @foreach($relatedProducts as $related)

                <div class="col-md-4">

                    <div class="card related-card h-100">

                        <div class="card-body">

                            <h5 class="fw-bold">
                                {{ $related->localized_name }}
                            </h5>

                            <p class="text-muted">

                                {{ \Illuminate\Support\Str::limit(
                                        $related->localized_description,
                                        100
                                    ) }}

                            </p>

                            <h5>
                                ₹{{ number_format($related->price, 2) }}
                            </h5>

                            <a
                                href="{{ route('products.show', $related->slug) }}"
                                class="btn btn-dark mt-2">
                                {{ __('View Details') }}
                            </a>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSpeak = document.getElementById('btn-speak');
            const btnPause = document.getElementById('btn-pause');
            const btnStop = document.getElementById('btn-stop');

            if (!('speechSynthesis' in window)) {
                btnSpeak.disabled = true;
                btnSpeak.innerText = '⚠️ Speech Synthesis Unavailable';
                return;
            }

            const currentLocale = "{{ app()->getLocale() }}";
            const localeVoiceMap = {
                'gu': 'gu-IN',
                'hi': 'hi-IN',
                'es': 'es-ES',
                'fr': 'fr-FR',
                'en': 'en-US'
            };

            const textToSpeak = "{{ addslashes($product->localized_name) }}. {{ addslashes($product->localized_description) }}";
            let utterance = null;

            btnSpeak.addEventListener('click', function() {
                if (window.speechSynthesis.paused) {
                    window.speechSynthesis.resume();
                    btnPause.innerText = '⏸️ {{ __('Pause') }}';
                    return;
                }

                window.speechSynthesis.cancel();
                utterance = new SpeechSynthesisUtterance(textToSpeak);
                utterance.lang = localeVoiceMap[currentLocale] || 'en-US';
                utterance.rate = 0.95;

                utterance.onstart = function() {
                    btnPause.classList.remove('d-none');
                    btnStop.classList.remove('d-none');
                    btnSpeak.classList.add('d-none');
                };

                utterance.onend = function() {
                    resetButtons();
                };

                utterance.onerror = function() {
                    resetButtons();
                };

                window.speechSynthesis.speak(utterance);
            });

            btnPause.addEventListener('click', function() {
                if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
                    window.speechSynthesis.pause();
                    btnPause.innerText = '▶️ {{ __('Resume') }}';
                } else if (window.speechSynthesis.paused) {
                    window.speechSynthesis.resume();
                    btnPause.innerText = '⏸️ {{ __('Pause') }}';
                }
            });

            btnStop.addEventListener('click', function() {
                window.speechSynthesis.cancel();
                resetButtons();
            });

            function resetButtons() {
                btnPause.classList.add('d-none');
                btnStop.classList.add('d-none');
                btnSpeak.classList.remove('d-none');
                btnPause.innerText = '⏸️ {{ __('Pause') }}';
            }
        });
    </script>
</body>

</html>
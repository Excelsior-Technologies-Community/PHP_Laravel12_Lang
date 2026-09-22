<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Translation Key Editor & Scanner') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans" x-data="{
    search: '',
    filterMissing: false,
    showAddModal: false,
    newKey: '',
    newValues: { en: '', hi: '', gu: '', es: '', fr: '' }
}">

    <!-- Top Header -->
    <header class="bg-slate-900/80 backdrop-blur border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-xl shadow-lg shadow-cyan-500/20">
                    🔤
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-blue-300 bg-clip-text text-transparent">
                        {{ __('Dynamic Web Translation Key Editor & Scanner') }}
                    </h1>
                    <p class="text-xs text-slate-400">{{ __('Live Multi-Language JSON Dictionary Manager') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('localization.dashboard') }}" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 border border-slate-700 transition">
                    📊 {{ __('Localization Dashboard') }}
                </a>
                <a href="{{ route('products.index') }}" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 border border-slate-700 transition">
                    🛍️ {{ __('Products Catalog') }}
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">

        <!-- Flash Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold flex items-center gap-2">
                <span>✨ {{ session('success') }}</span>
            </div>
        @endif

        <!-- Top Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Total Registered Keys') }}</span>
                <div class="text-3xl font-extrabold mt-2 text-cyan-400">
                    {{ $data['total_keys'] }}
                </div>
                <span class="text-[10px] text-slate-500">{{ __('Unique translation entries') }}</span>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Missing Translations') }}</span>
                <div class="text-3xl font-extrabold mt-2 {{ $data['missing_count'] > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                    {{ $data['missing_count'] }}
                </div>
                <span class="text-[10px] text-slate-500">{{ __('Untranslated language cells') }}</span>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Supported Locales') }}</span>
                <div class="text-xl font-bold mt-2 text-indigo-400 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-xs font-mono">🇬🇧 EN</span>
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-xs font-mono">🇮🇳 HI</span>
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-xs font-mono">🇮🇳 GU</span>
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-xs font-mono">🇪🇸 ES</span>
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-xs font-mono">🇫🇷 FR</span>
                </div>
                <span class="text-[10px] text-slate-500">5 {{ __('Active Locales') }}</span>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5 shadow-xl mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <input type="text" x-model="search" placeholder="🔍 {{ __('Search translation key or text...') }}" class="w-full md:w-80 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                
                <button type="button" @click="filterMissing = !filterMissing" :class="filterMissing ? 'bg-amber-500/20 text-amber-300 border-amber-500/50' : 'bg-slate-800 text-slate-400 border-slate-700'" class="px-3.5 py-2 rounded-xl border text-xs font-semibold transition flex items-center gap-1.5">
                    <span>⚠️ {{ __('Show Missing Only') }}</span>
                </button>
            </div>

            <div class="flex items-center space-x-3">
                <button type="button" @click="showAddModal = true" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-400 border border-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                    <span>＋ {{ __('Add New Key') }}</span>
                </button>
            </div>
        </div>

        <!-- Main Form & Table -->
        <form method="POST" action="{{ route('localization.editor.save') }}">
            @csrf
            
            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-6 shadow-xl mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-100 flex items-center gap-2">
                        <span>📖 {{ __('Translation Matrix') }}</span>
                    </h2>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-xs font-bold text-white shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                        <span>💾 {{ __('Save All Changes') }}</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4 min-w-[200px]">{{ __('Translation Key') }}</th>
                                <th class="py-3 px-4 min-w-[160px]">🇬🇧 {{ __('English') }} (en)</th>
                                <th class="py-3 px-4 min-w-[160px]">🇮🇳 {{ __('Hindi') }} (hi)</th>
                                <th class="py-3 px-4 min-w-[160px]">🇮🇳 {{ __('Gujarati') }} (gu)</th>
                                <th class="py-3 px-4 min-w-[160px]">🇪🇸 {{ __('Spanish') }} (es)</th>
                                <th class="py-3 px-4 min-w-[160px]">🇫🇷 {{ __('French') }} (fr)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-mono">
                            @foreach($data['matrix'] as $row)
                                <tr x-show="(search === '' || '{{ strtolower(addslashes($row['key'])) }}'.includes(search.toLowerCase())) && (!filterMissing || {{ count($row['missing']) > 0 ? 'true' : 'false' }})" class="hover:bg-slate-850 transition">
                                    <td class="py-3 px-4 font-sans font-bold text-slate-200">
                                        <div class="break-words max-w-xs">{{ $row['key'] }}</div>
                                        @if(count($row['missing']) > 0)
                                            <span class="text-[10px] text-amber-400 font-normal block mt-0.5">⚠️ {{ __('Missing') }}: {{ implode(', ', array_map('strtoupper', $row['missing'])) }}</span>
                                        @endif
                                    </td>

                                    @foreach(['en', 'hi', 'gu', 'es', 'fr'] as $loc)
                                        <td class="py-2 px-3">
                                            <input type="text" name="translations[{{ $loc }}][{{ $row['key'] }}]" value="{{ $row['values'][$loc] }}" placeholder="{{ $row['key'] }}" class="w-full px-2.5 py-1.5 rounded-lg bg-slate-950 border text-xs text-slate-200 focus:outline-none focus:border-cyan-500 font-sans {{ empty($row['values'][$loc]) ? 'border-amber-500/50 bg-amber-950/20' : 'border-slate-800' }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-sm font-bold text-white shadow-xl shadow-emerald-500/20 transition flex items-center gap-2">
                    <span>💾 {{ __('Save All Changes') }}</span>
                </button>
            </div>
        </form>

        <!-- Add Key Modal -->
        <div x-show="showAddModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-100">＋ {{ __('Add New Translation Key') }}</h3>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <form method="POST" action="{{ route('localization.editor.add-key') }}" class="space-y-4 text-xs font-sans">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-300 mb-1">{{ __('Translation Key Name') }} *</label>
                        <input type="text" name="key" required placeholder="Example: Welcome Back" class="w-full p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-400 mb-1">🇬🇧 English (en)</label>
                            <input type="text" name="values[en]" placeholder="Welcome Back" class="w-full p-2 rounded-lg bg-slate-950 border border-slate-800 text-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-400 mb-1">🇮🇳 Hindi (hi)</label>
                            <input type="text" name="values[hi]" placeholder="वाપसी पर स्वागत है" class="w-full p-2 rounded-lg bg-slate-950 border border-slate-800 text-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-400 mb-1">🇮🇳 Gujarati (gu)</label>
                            <input type="text" name="values[gu]" placeholder="પાછા આવવા બદલ સ્વાગત છે" class="w-full p-2 rounded-lg bg-slate-950 border border-slate-800 text-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-400 mb-1">🇪🇸 Spanish (es)</label>
                            <input type="text" name="values[es]" placeholder="Bienvenido de nuevo" class="w-full p-2 rounded-lg bg-slate-950 border border-slate-800 text-slate-200">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">{{ __('Cancel') }}</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow">{{ __('Add Key') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</body>
</html>

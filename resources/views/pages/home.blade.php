<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Home</title>
</head>
<body class="bg-slate-100">
    @include('layouts.navbar')

    <main class="mt-6 mx-auto space-y-6 px-4 py-6 sm:px-6">
        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-lg bg-white p-6 shadow">
            <h1 class="text-2xl font-bold text-slate-900">
                {{ $isAdmin ? 'Dashboard Admin' : 'Dashboard Siswa' }}
            </h1>
        </section>

        <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Total Aspirasi</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['total'] ?? 0 }}</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Menunggu</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['menunggu'] ?? 0 }}</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Proses</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['proses'] ?? 0 }}</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Selesai</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['selesai'] ?? 0 }}</p>
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow">
            <h2 class="text-lg font-semibold text-slate-900">Aspirasi Terbaru</h2>
            <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Siswa</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Kategori</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Lokasi</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr class="border-t border-slate-200 transition hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $report->siswa?->nis ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $report->kategori?->ket_kategori ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $report->lokasi }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $report->aspirasi?->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($report->aspirasi?->status === 'Proses' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $report->aspirasi?->status ?? 'Menunggu' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada data aspirasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>

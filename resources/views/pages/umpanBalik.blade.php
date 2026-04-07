<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Umpan Balik</title>
</head>
<body class="bg-slate-100">
    @include('layouts.navbar')

    <main class="mt-6 mx-auto space-y-6 px-4 py-6 sm:px-6">
        <section class="rounded-lg bg-white p-6 shadow">
            <h1 class="text-2xl font-bold text-slate-900">Umpan Balik dan Progres</h1>
        </section>

        @if ($isAdmin)
            <section class="rounded-lg bg-white p-6 shadow">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Siswa</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Kategori</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Lokasi</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Keterangan</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Feedback</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr class="border-t border-slate-200 transition hover:bg-slate-50/80 align-top">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $report->siswa?->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->kategori?->ket_kategori ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->lokasi }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->ket }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $report->aspirasi?->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($report->aspirasi?->status === 'Proses' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $report->aspirasi?->status ?? 'Menunggu' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->aspirasi?->feedback ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('aspirasi.update', $report) }}" class="space-y-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                                @foreach (['Menunggu', 'Proses', 'Selesai'] as $status)
                                                    <option value="{{ $status }}" @selected(($report->aspirasi?->status ?? 'Menunggu') === $status)>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <textarea name="feedback" rows="2" placeholder="Feedback" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $report->aspirasi?->feedback }}</textarea>
                                            <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada aspirasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="rounded-lg bg-white p-6 shadow">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Siswa</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Kategori</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Feedback</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Progres</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr class="border-t border-slate-200 transition hover:bg-slate-50/80 align-top">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $report->siswa?->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->kategori?->ket_kategori ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $report->aspirasi?->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($report->aspirasi?->status === 'Proses' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $report->aspirasi?->status ?? 'Menunggu' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->aspirasi?->feedback ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $report->progress_label ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>
</body>
</html>

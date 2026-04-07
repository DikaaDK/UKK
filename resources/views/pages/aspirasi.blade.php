<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Aspirasi</title>
</head>
<body class="bg-slate-100">
    @include('layouts.navbar')

    <main class="mt-6 mx-auto space-y-6 px-4 py-6 sm:px-6">
        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($isAdmin)
            <section class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow lg:col-span-1">
                    <h1 class="text-2xl font-bold text-slate-900">Tambah Kategori</h1>
                    <form method="POST" action="{{ route('kategori.store') }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="ket_kategori" class="mb-1 block text-sm font-medium text-slate-700">Nama Kategori</label>
                            <input type="text" id="ket_kategori" name="ket_kategori" value="{{ old('ket_kategori') }}" maxlength="30" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="Contoh: Kerusakan Fasilitas">
                        </div>
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Simpan Kategori</button>
                    </form>
                </div>

                <div class="rounded-lg bg-white p-6 shadow lg:col-span-2">
                    <h2 class="text-lg font-semibold text-slate-900">Daftar Kategori</h2>
                    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="w-full border-collapse text-left text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600">
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">ID</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Nama Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $kategori)
                                    <tr class="border-t border-slate-200 transition hover:bg-slate-50/80">
                                        <td class="px-4 py-3 font-medium text-slate-900">{{ $kategori->id_kategori }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $kategori->ket_kategori }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500">Belum ada kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-slate-900">Filter Data Aspirasi</h2>
                <form method="GET" class="mt-4 grid gap-3 md:grid-cols-4">
                    <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <input type="number" name="nis" value="{{ $filters['nis'] ?? '' }}" placeholder="NIS Siswa" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <select name="id_kategori" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $kategori)
                            <option value="{{ $kategori->id_kategori }}" @selected(($filters['id_kategori'] ?? '') == $kategori->id_kategori)>
                                {{ $kategori->ket_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
                        <a href="{{ route('aspirasi') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                    </div>
                </form>
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-slate-900">Data Aspirasi Terbaru</h2>

                <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Siswa</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Kategori</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Lokasi</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Keterangan</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Feedback</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada data aspirasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow lg:col-span-1">
                    <h1 class="text-2xl font-bold text-slate-900">Buat Aspirasi</h1>
                    <form method="POST" action="{{ route('aspirasi.store') }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Kategori</label>
                            <select name="id_kategori" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->ket_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Lokasi</label>
                            <input type="text" name="lokasi" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="Contoh: Ruang kelas C1">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Keterangan</label>
                            <textarea name="ket" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="Tulis aspirasi"></textarea>
                        </div>
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Kirim</button>
                    </form>
                </div>

                <div class="rounded-lg bg-white p-6 shadow lg:col-span-2">
                    <h2 class="text-lg font-semibold text-slate-900">Histori Aspirasi Saya</h2>
                    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="w-full border-collapse text-left text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600">
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Kategori</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Lokasi</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Keterangan</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    <tr class="border-t border-slate-200 transition hover:bg-slate-50/80 align-top">
                                        <td class="px-4 py-3 font-medium text-slate-900">{{ $report->kategori?->ket_kategori ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $report->lokasi }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $report->ket }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $report->aspirasi?->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($report->aspirasi?->status === 'Proses' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                                {{ $report->aspirasi?->status ?? 'Menunggu' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700">{{ $report->aspirasi?->feedback ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada aspirasi yang kamu kirim.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </main>
</body>
</html>

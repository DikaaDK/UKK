<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Admin</title>
</head>
<body class="bg-slate-100">
    @include('layouts.navbar')

    <main class="mt-6 space-y-6 px-4 py-6 sm:px-6">
        @if (session('error'))
            <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-lg bg-white p-6 shadow lg:col-span-1">
                <h1 class="text-2xl font-bold text-slate-900">Tambah Admin</h1>
                <p class="mt-2 text-sm text-slate-600">Halaman ini hanya bisa diakses oleh admin.</p>

                <form method="POST" action="{{ route('admin.store') }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="mb-1 block text-sm font-medium text-slate-700">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" class="w-full rounded-md border border-slate-300 px-3 pAsm" laceholder="Contoh: admin2">
                    </div>
                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" id="password" name="password" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Simpan Admin</button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow lg:col-span-2">
                <h2 class="text-lg font-semibold text-slate-900">Daftar Admin</h2>
                <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">ID</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Username</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Dibuat</th>
                                <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr class="border-t border-slate-200 transition hover:bg-slate-50/80">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $admin->id }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $admin->username }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $admin->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        @if (auth()->guard('portal')->user()?->is($admin))
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Akun aktif</span>
                                        @else
                                            <form method="POST" action="{{ route('admin.destroy', $admin) }}" onsubmit="return confirm('Hapus admin {{ $admin->username }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada admin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>
</html>

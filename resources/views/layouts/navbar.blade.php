<nav class="sticky top-0 z-50 border-b border-white/10 bg-linear-to-r from-blue-700 via-blue-800 to-blue-900 text-white shadow-[0_16px_40px_rgba(30,64,175,0.22)] backdrop-blur-xl">
    @php($currentUser = auth()->guard('portal')->user())
    <div class="mx-auto flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
        <span class="text-lg sm:text-2xl">Pengaduan Sekolah</span>
        <ul class="flex flex-wrap items-center gap-2 text-sm p-3 font-medium sm:gap-3 lg:gap-4">
            <li>
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-full px-4 py-2 transition {{ request()->routeIs('home') ? 'bg-white text-blue-800 shadow-md shadow-blue-950/20' : 'text-white/85 hover:bg-white/10 hover:text-white' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('aspirasi') }}" class="inline-flex items-center rounded-full px-4 py-2 transition {{ request()->routeIs('aspirasi') ? 'bg-white text-blue-800 shadow-md shadow-blue-950/20' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    Aspirasi
                </a>
            </li>
            <li>
                <a href="{{ route('umpanBalik') }}" class="inline-flex items-center rounded-full px-4 py-2 transition {{ request()->routeIs('umpanBalik') ? 'bg-white text-blue-800 shadow-md shadow-blue-950/20' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    Umpan Balik
                </a>
            </li>
            @if ($currentUser instanceof \App\Models\Admin)
                <li class="ml-8 border-l border-white/20 pl-4">
                    <a href="{{ route('admin.create') }}" class="inline-flex items-center rounded-full px-4 py-2 transition {{ request()->routeIs('admin.create') ? 'bg-white text-blue-800 shadow-md shadow-blue-950/20' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        Tambah Admin
                    </a>
                </li>
            @endif
            <li>
                <form action="{{ route('logout') }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 font-semibold text-white transition hover:border-white/30 hover:bg-white/20 hover:shadow-md hover:shadow-blue-950/20">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

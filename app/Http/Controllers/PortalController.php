<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    public function home(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user instanceof Admin;

        $reports = $this->buildReportsQuery($request, $isAdmin, $user)->latest('id_pelaporan')->get();

        return view('pages.home', [
            'isAdmin' => $isAdmin,
            'user' => $user,
            'summary' => $this->buildSummary($reports, $isAdmin),
            'reports' => $reports->take(5),
        ]);
    }

    public function aspirasi(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user instanceof Admin;

        $categories = Kategori::orderBy('ket_kategori')->get();
        $reports = $this->buildReportsQuery($request, $isAdmin, $user)->latest('id_pelaporan')->get();

        return view('pages.aspirasi', [
            'isAdmin' => $isAdmin,
            'user' => $user,
            'categories' => $categories,
            'reports' => $reports,
            'summary' => $this->buildSummary($reports, $isAdmin),
            'filters' => $request->only(['date', 'nis', 'id_kategori']),
        ]);
    }

    public function storeKategori(Request $request)
    {
        $user = $request->user();

        abort_unless($user instanceof Admin, 403);

        $data = $request->validate([
            'ket_kategori' => ['required', 'string', 'max:30', 'unique:kategori,ket_kategori'],
        ]);

        Kategori::create($data);

        return redirect()->route('aspirasi')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function createAdmin(Request $request)
    {
        $user = $request->user();

        abort_unless($user instanceof Admin, 403);

        return view('pages.admin-create', [
            'admins' => Admin::orderBy('username')->get(),
        ]);
    }

    public function storeAdmin(Request $request)
    {
        $user = $request->user();

        abort_unless($user instanceof Admin, 403);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:admin,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Admin::create([
            'username' => $data['username'],
            'password' => $data['password'],
        ]);

        return redirect()->route('admin.create')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function destroyAdmin(Request $request, Admin $admin)
    {
        $user = $request->user();

        abort_unless($user instanceof Admin, 403);

        if ($admin->is($user)) {
            return redirect()->route('admin.create')->with('error', 'Akun admin yang sedang dipakai tidak bisa dihapus.');
        }

        if (Admin::query()->count() <= 1) {
            return redirect()->route('admin.create')->with('error', 'Minimal harus ada satu admin di sistem.');
        }

        $admin->delete();

        return redirect()->route('admin.create')->with('success', 'Admin berhasil dihapus.');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        abort_unless($user instanceof Siswa, 403);

        $data = $request->validate([
            'id_kategori' => ['required', 'exists:kategori,id_kategori'],
            'lokasi' => ['required', 'string', 'max:50'],
            'ket' => ['required', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($user, $data) {
            $aspirasi = Aspirasi::create([
                'status' => 'Menunggu',
                'id_kategori' => $data['id_kategori'],
                'feedback' => null,
            ]);

            InputAspirasi::create([
                'nis' => $user->nis,
                'id_kategori' => $data['id_kategori'],
                'lokasi' => $data['lokasi'],
                'ket' => $data['ket'],
                'id_aspirasi' => $aspirasi->id_aspirasi,
            ]);
        });

        return redirect()->route('aspirasi')->with('success', 'Aspirasi berhasil dikirim.');
    }

    public function update(Request $request, InputAspirasi $inputAspirasi)
    {
        $user = $request->user();

        abort_unless($user instanceof Admin, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['Menunggu', 'Proses', 'Selesai'])],
            'feedback' => ['nullable', 'string', 'max:255'],
        ]);

        $aspirasi = $inputAspirasi->aspirasi;

        if (! $aspirasi) {
            $aspirasi = Aspirasi::create([
                'status' => $data['status'],
                'id_kategori' => $inputAspirasi->id_kategori,
                'feedback' => $data['feedback'],
            ]);

            $inputAspirasi->update([
                'id_aspirasi' => $aspirasi->id_aspirasi,
            ]);
        } else {
            $aspirasi->update([
                'status' => $data['status'],
                'feedback' => $data['feedback'],
            ]);
        }

        return back()->with('success', 'Status aspirasi berhasil diperbarui.');
    }

    public function umpanBalik(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user instanceof Admin;

        $reports = $this->buildReportsQuery($request, $isAdmin, $user)->latest('id_pelaporan')->get();

        $reports->each(function ($report) {
            $status = $report->aspirasi?->status;

            $report->progress_label = match ($status) {
                'Menunggu' => 'Menunggu pemeriksaan admin.',
                'Proses' => 'Sedang diproses oleh admin.',
                'Selesai' => 'Sudah selesai ditangani.',
                default => 'Belum ada status.',
            };
        });

        return view('pages.umpanBalik', [
            'isAdmin' => $isAdmin,
            'user' => $user,
            'reports' => $reports,
        ]);
    }

    private function buildReportsQuery(Request $request, bool $isAdmin, $user)
    {
        $query = InputAspirasi::with(['siswa', 'kategori', 'aspirasi']);

        if (! $isAdmin && $user instanceof Siswa) {
            $query->where('nis', $user->nis);
        }

        $request->validate([
            'date' => ['nullable', 'date'],
            'nis' => ['nullable', 'integer'],
            'id_kategori' => ['nullable', 'integer', 'exists:kategori,id_kategori'],
        ]);

        $query->when($request->filled('date'), function ($query) use ($request) {
            $query->whereDate('created_at', $request->string('date')->toString());
        });

        $query->when($request->filled('nis') && $isAdmin, function ($query) use ($request) {
            $query->where('nis', (int) $request->input('nis'));
        });

        $query->when($request->filled('id_kategori'), function ($query) use ($request) {
            $query->where('id_kategori', (int) $request->input('id_kategori'));
        });

        return $query;
    }

    private function buildSummary($reports, bool $isAdmin): array
    {
        $statusCounts = [
            'Menunggu' => 0,
            'Proses' => 0,
            'Selesai' => 0,
        ];

        foreach ($reports as $report) {
            $status = $report->aspirasi?->status;

            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }

        $summary = [
            'total' => $reports->count(),
            'menunggu' => $statusCounts['Menunggu'],
            'proses' => $statusCounts['Proses'],
            'selesai' => $statusCounts['Selesai'],
        ];

        if ($isAdmin) {
            $summary['siswa'] = $reports->pluck('nis')->unique()->count();
            $summary['kategori'] = $reports->pluck('id_kategori')->unique()->count();
        }

        return $summary;
    }
}

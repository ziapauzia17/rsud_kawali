<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Services\KegiatanService;
use App\Models\RencanaHasilKinerjaPegawai;
use App\Http\Requests\HarianPegawaiRequest;

class KegiatanHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $kegiatanService;

    public function __construct(KegiatanService $kegiatanService)
    {
        $this->kegiatanService = $kegiatanService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil data rencana kerja pegawai untuk user yang sedang login
        $rencanaKerjaPegawai = RencanaHasilKinerjaPegawai::where('user_id', $user->id)->get();

        // Ambil data kegiatan harian hanya untuk user yang sedang login
        $kegiatanHarian = KegiatanHarian::with(['user.pangkat'])
            ->where('user_id', $user->id) // Filter berdasarkan user_id
            ->orderBy('tanggal', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->get();

        // Kembalikan data ke view
        return view('backend.harian.index', compact('rencanaKerjaPegawai', 'kegiatanHarian'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, KegiatanService $service)
    {
        try {
            $data = $request->all();

            // Ambil nilai is_draft dan konversi ke boolean
            $isDraft = $request->input('is_draft') === '1'; // Draft jika nilai is_draft adalah string '0'

            $kegiatanHarian = $service->saveKegiatanHarian($data, $isDraft);

            return redirect()->back()->with('success', 'Kegiatan Harian berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Kegiatan Harian.', [
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KegiatanService $service, $uuid)
    {
        // Validasi data yang masuk
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_kegiatan' => 'required|string',
            'uraian' => 'required|string',
            'rencana_pegawai_id' => 'required|integer',
            'output' => 'required|string',
            'jumlah' => 'required|numeric',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'biaya' => 'nullable|numeric',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Sesuaikan dengan jenis file yang diizinkan
        ]);

        try {
            $data = $request->all();

            // Ambil nilai is_draft dan konversi ke boolean
            $isDraft = $request->input('is_draft') === '1'; // Draft jika nilai is_draft adalah string '0'

            // Panggil service untuk memperbarui kegiatan harian
            $kegiatanHarian = $service->updateKegiatanHarian($uuid, $data, $isDraft);

            return redirect()->back()->with('success', 'Kegiatan Harian berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui Kegiatan Harian.', [
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->kegiatanService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}

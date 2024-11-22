<?php

namespace App\Http\Services;

use App\Models\RencanaHasilKinerja;
use App\Models\Skp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class RencanaKerjaAtasanService
{
    public function store(array $data)
    {
        try {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Ambil SKP terkait dengan user, pastikan bahwa user sudah memiliki SKP
            $skp = Skp::where('user_id', $user->id)->first(); // Sesuaikan query jika diperlukan untuk kondisi lain

            if (!$skp) {
                throw new Exception("SKP tidak ditemukan.");
            }

            // Menyimpan data RencanaHasilKerja
            $rencanaHasilKerja = RencanaHasilKinerja::create([
                'rencana' => $data['rencana_hasil_kerja'],
                'user_id' => $user->id,
                'skp_id' => $skp->id,
            ]);

            return $rencanaHasilKerja;
        } catch (Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Rencana Hasil Kerja', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $data,
            ]);

            // Melemparkan error untuk ditangani oleh controller atau service lainnya
            throw new Exception('Gagal menyimpan Rencana Hasil Kerja: ' . $e->getMessage());
        }
    }



    public function delete($uuid)
    {
        $skp = Skp::where('uuid', $uuid)->firstOrFail();

        return $skp->delete();
    }

    // Tambahkan fungsi untuk mendapatkan data detail

}
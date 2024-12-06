<?php

namespace App\Http\Controllers\Bpjs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PesertaBpjController extends Controller
{
    public function index()
    {
        // Define base query
        $query = DB::connection('mysql6')->table('bpjs.peserta as peserta')
            ->select(
                'peserta.noKartu',
                'peserta.nik',
                'pasien.NORM as norm',
                'peserta.nama',
                'peserta.nmJenisPeserta',
                'peserta.ketStatusPeserta',
            )
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik');

        // Apply search filter if 'subject' query parameter is present
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->where(function ($q) use ($searchSubject) {
                $q->where('peserta.noKartu', 'LIKE', '%' . $searchSubject . '%')
                    ->orWhere('peserta.nik', 'LIKE', '%' . $searchSubject . '%')
                    ->orWhere('peserta.nama', 'LIKE', '%' . $searchSubject . '%')
                    ->orWhere('pasien.NORM', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('peserta.tanggal')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Peserta/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        //$query = BpjsPesertaModel::where('noKartu', $id)->first();
        $query = DB::connection('mysql6')->table('bpjs.peserta as peserta')
            ->select(
                'peserta.noKartu',
                'peserta.nik',
                'pasien.NORM as norm',
                'peserta.nama',
                'peserta.pisa',
                'peserta.sex',
                'peserta.tglLahir',
                'peserta.tglCetakKartu',
                'peserta.kdProvider',
                'peserta.nmProvider',
                'peserta.kdCabang',
                'peserta.nmCabang',
                'peserta.kdJenisPeserta',
                'peserta.nmJenisPeserta',
                'peserta.kdKelas',
                'peserta.nmKelas',
                'peserta.tglTAT',
                'peserta.tglTMT',
                'peserta.umurSaatPelayanan',
                'peserta.umurSekarang',
                'peserta.dinsos',
                'peserta.iuran',
                'peserta.noSKTM',
                'peserta.prolanisPRB',
                'peserta.kdStatusPeserta',
                'peserta.ketStatusPeserta',
                'peserta.noTelepon',
                'peserta.noAsuransi',
                'peserta.nmAsuransi',
                'peserta.cobTglTAT',
                'peserta.cobTglTMT',
                'peserta.tanggal',
            )
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->where('peserta.noKartu', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('pesertaBpjs.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the data
        return inertia("Bpjs/Peserta/Detail", [
            'detail' => $query,
        ]);
    }
}

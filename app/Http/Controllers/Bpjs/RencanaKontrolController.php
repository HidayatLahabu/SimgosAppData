<?php

namespace App\Http\Controllers\Bpjs;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RencanaKontrolController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'pasien.NORM as norm',
                'peserta.nama as namaPasien',
                'rekon.noSurat',
                'rekon.tglRencanaKontrol as tanggal',
                'poli.nama as poliTujuan',
                'dpjp.nama as namaDokter',
                'pasienBpjs.nama as pasienNama',
                'jadwalKontrol.DIBUAT_TANGGAL as tglDibuat',
            )
            ->leftJoin('bpjs.kunjungan as kunjungan', 'kunjungan.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('bpjs.peserta as pasienBpjs', 'pasienBpjs.noKartu', '=', 'rekon.nomor')
            ->leftJoin('medicalrecord.jadwal_kontrol as jadwalKontrol', 'rekon.noSurat', '=', 'jadwalKontrol.NOMOR_REFERENSI')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->groupBy(
                'rekon.noSurat',
                'pasien.NORM',
                'peserta.nama',
                'rekon.tglRencanaKontrol',
                'poli.nama',
                'dpjp.nama',
                'pasienBpjs.nama',
                'jadwalKontrol.DIBUAT_TANGGAL'
            );

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(rekon.noSurat) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(rekon.nomor) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhere('pasien.NORM', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query
            ->orderByDesc('rekon.tglRencanaKontrol')
            ->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Rekon/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'pasien.NORM as norm',
                'peserta.nama as namaPasien',
                'rekon.noSurat',
                'rekon.tglRencanaKontrol as tanggal',
                'poli.nama as poliTujuan',
                'dpjp.nama as namaDokter',
                'pasienBpjs.nama as pasienNama',
                'jadwalKontrol.DIBUAT_TANGGAL as tglDibuat',
            )
            ->leftJoin('bpjs.kunjungan as kunjungan', 'kunjungan.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('bpjs.peserta as pasienBpjs', 'pasienBpjs.noKartu', '=', 'rekon.nomor')
            ->leftJoin('medicalrecord.jadwal_kontrol as jadwalKontrol', 'rekon.noSurat', '=', 'jadwalKontrol.NOMOR_REFERENSI')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->groupBy(
                'rekon.noSurat',
                'pasien.NORM',
                'peserta.nama',
                'rekon.tglRencanaKontrol',
                'poli.nama',
                'dpjp.nama',
                'pasienBpjs.nama',
                'jadwalKontrol.DIBUAT_TANGGAL'
            );

        // Add filter based on the provided filter
        if ($filter === 'hariIni') {
            $query->whereDate('rekon.tglRencanaKontrol', now()->format('Y-m-d'));
            $header = 'HARI INI';
            $text = 'PASIEN';
        } else {
            abort(404, 'Filter not found');
        }

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(rekon.noSurat) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(rekon.nomor) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhere('pasien.NORM', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Get paginated data and total count
        $data = $query->paginate(5)->appends(request()->query());
        $count = $data->total(); // Get total count directly from the paginated data

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Rekon/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
            'text' => $text,
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'rekon.noSurat',
                'rekon.jnsKontrol',
                'rekon.nomor',
                'pasien.NORM as norm',
                'peserta.nama',
                'rekon.tglRencanaKontrol',
                'dpjp.nama as namaDokter',
                'poli.nama as poliKontrol',
                'rekon.user',
                'rekon.status',
                'jadwalKontrol.DIBUAT_TANGGAL as tglDibuat',
                'jadwalKontrol.KUNJUNGAN as kunjungan',
            )
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.kunjungan as kunjungan', 'kunjungan.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->leftJoin('medicalrecord.jadwal_kontrol as jadwalKontrol', 'rekon.noSurat', '=', 'jadwalKontrol.NOMOR_REFERENSI')
            ->where('rekon.noSurat', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('rekonBpjs.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Rekon/Detail", [
            'detail' => $query,
        ]);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'pasien.NORM as norm',
                'peserta.nama as namaPasien',
                'rekon.noSurat',
                'rekon.tglRencanaKontrol as tanggal',
                'poli.nama as poliTujuan',
                'dpjp.nama as namaDokter',
                'pasienBpjs.nama as pasienNama',
                'monitor.noSuratKontrol as kunjungan',
                'kunjungan.noKartu',
            )
            ->leftJoin('bpjs.kunjungan as kunjungan', 'kunjungan.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('bpjs.peserta as pasienBpjs', 'pasienBpjs.noKartu', '=', 'rekon.nomor')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->leftJoin('bpjs.monitoring_rencana_kontrol as monitor', 'monitor.noSuratKontrol', '=', 'rekon.noSurat')
            ->whereBetween('rekon.tglRencanaKontrol', [$dariTanggal, $sampaiTanggal])
            ->where('rekon.jnsKontrol', 2)
            ->orderByDesc('rekon.tglRencanaKontrol')
            ->orderBy('peserta.nama')
            ->orderBy('pasienBpjs.nama')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Bpjs/Rekon/Print", [
            'data'              => $query,
            'dariTanggal'       => $dariTanggal,
            'sampaiTanggal'     => $sampaiTanggal,
        ]);
    }
}
<?php

namespace App\Http\Controllers\Bpjs;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BatalControlController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'peserta.noKartu',
                'rekon.noSurat',
                'rekon.tglRencanaKontrol',
                'peserta.nama as namaPasien',
                'pasien.NORM as norm',
                'poli.nama as ruangan',
                'dpjp.nama as namaDokter'
            )
            ->leftJoin('monitoring_rencana_kontrol as monitor', 'monitor.noSuratKontrol', '=', 'rekon.noSurat')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.kunjungan as kunjunganBpjs', 'kunjunganBpjs.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjunganBpjs.noKartu')
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', 'asuransi.NOMOR', '=', 'peserta.noKartu')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'asuransi.NORM')
            ->whereNull('monitor.noSuratKontrol')
            ->where('rekon.tglRencanaKontrol', '<=', Carbon::now())
            ->orderByDesc('rekon.tglRencanaKontrol');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(poli.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhere('dpjp.nama', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('rekon.tglRencanaKontrol')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/BatalKontrol/Index", [
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
                'peserta.noKartu',
                'rekon.noSurat',
                'rekon.tglRencanaKontrol',
                'peserta.nama as namaPasien',
                'pasien.NORM as norm',
                'poli.nama as ruangan',
                'dpjp.nama as namaDokter'
            )
            ->leftJoin('monitoring_rencana_kontrol as monitor', 'monitor.noSuratKontrol', '=', 'rekon.noSurat')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.kunjungan as kunjunganBpjs', 'kunjunganBpjs.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjunganBpjs.noKartu')
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', 'asuransi.NOMOR', '=', 'peserta.noKartu')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'asuransi.NORM')
            ->whereNull('monitor.noSuratKontrol')
            ->where('rekon.tglRencanaKontrol', '<=', Carbon::now())
            ->orderByDesc('rekon.tglRencanaKontrol');

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('rekon.tglRencanaKontrol', now()->format('Y-m-d'));
                $countQuery->whereDate('rekon.tglRencanaKontrol', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'PASIEN';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(poli.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhere('dpjp.nama', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('rekon.tglRencanaKontrol')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/BatalKontrol/Index", [
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
                'peserta.noKartu',
                'rekon.noSurat',
                'rekon.tglRencanaKontrol',
                'peserta.nama as namaPasien',
                'pasien.NORM as norm',
                'poli.nama as ruangan',
                'dpjp.nama as namaDokter'
            )
            ->leftJoin('monitoring_rencana_kontrol as monitor', 'monitor.noSuratKontrol', '=', 'rekon.noSurat')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.kunjungan as kunjunganBpjs', 'kunjunganBpjs.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjunganBpjs.noKartu')
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', 'asuransi.NOMOR', '=', 'peserta.noKartu')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'asuransi.NORM')
            ->whereNull('monitor.noSuratKontrol')
            ->whereBetween('rekon.tglRencanaKontrol', [$dariTanggal, $sampaiTanggal])
            ->orderByDesc('rekon.tglRencanaKontrol')
            ->orderBy('peserta.nama')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Bpjs/BatalKontrol/Print", [
            'data'              => $query,
            'dariTanggal'       => $dariTanggal,
            'sampaiTanggal'     => $sampaiTanggal,
        ]);
    }
}
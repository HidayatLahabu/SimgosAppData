<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordAsuhanKeperawatanModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'asuhan_keperawatan';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.asuhan_keperawatan as askep')
            ->select([
                'askep.*',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH')
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'askep.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('askep.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        foreach ((array)$query as $key => $value) {
            $decoded = json_decode($value, true);

            // Remove fields with empty arrays
            if (is_array($decoded) && empty($decoded)) {
                unset($query->{$key});
            }
            // For arrays with data, convert them to a comma-separated string
            elseif (is_array($decoded)) {
                $query->{$key} = implode(', ', $decoded);
            }
        }

        return $query;
    }
}

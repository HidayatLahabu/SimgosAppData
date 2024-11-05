import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';

export default function LaporanRl12({ auth, items = [], tgl_awal, tgl_akhir }) {
    // Debugging: log items to see what data is being received
    console.log('Received items:', items);

    // Check if items is defined and is an array
    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div>Error: Data not available</div>;
    }

    // Assuming that 'items' is an array with the first element being the object containing the data
    const data = items[0] || {}; // Access the first item in the array

    // Object to map original field names to the desired names
    const fieldMap = {
        KODERS: "Kode Rumah Sakit",
        NAMAINST: "Nama Instansi",
        KODEPROP: "Kode Provinsi",
        KOTA: "Kota/Kabupaten",
        TAHUN: "Tahun",
        AWAL: "Jumlah Awal",
        MASUK: "Jumlah Masuk",
        PINDAHAN: "Pindahan",
        DIPINDAHKAN: "Dipindahkan",
        HIDUP: "Pasien Hidup",
        MATI: "Pasien Mati",
        MATIKURANG48: "Mati < 48 Jam",
        MATILEBIH48: "Mati > 48 Jam",
        LD: "Lama Dirawat",
        SISA: "Sisa",
        HP: "Hari Perawatan",
        JMLTT: "Jumlah Tempat Tidur",
        BOR: "Bed Occupancy Rate",
        BORLK: "BOR Laki-laki",
        BORPR: "BOR Perempuan",
        AVLOS: "Average Length of Stay",
        AVLOSLK: "AVLOS Laki-laki",
        AVLOSPR: "AVLOS Perempuan",
        BTO: "Bed Turn Over",
        BTOLK: "BTO Laki-laki",
        BTOPR: "BTO Perempuan",
        TOI: "Turn Over Interval",
        TOILK: "TOI Laki-laki",
        TOIPR: "TOI Perempuan",
        NDR: "Net Death Rate",
        NDRLK: "NDR Laki-laki",
        NDRPR: "NDR Perempuan",
        GDR: "Gross Death Rate",
        GDRLK: "GDR Laki-laki",
        GDRPR: "GDR Perempuan",
        JMLKUNJUNGAN: "Jumlah Kunjungan",
    };

    return (

        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN RL 1.2</h1>
                            <p className="text-center pb-4">
                                <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} - {formatDate(tgl_akhir)}
                            </p>
                            <div className="overflow-x-auto">
                                <table className="min-w-full table-auto w-full border">
                                    <thead>
                                        <tr className='uppercase dark:bg-indigo-700'>
                                            <th className="border px-4 py-2">Nama/Uraian</th>
                                            <th className="border px-4 py-2">Nilai/Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {Object.entries(data).map(([key, value], index) => (
                                            <tr key={index}>
                                                <td className="border px-4 py-2">{fieldMap[key] || key}</td>
                                                <td className="border px-4 py-2 text-center">{value}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

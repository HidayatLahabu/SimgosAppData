import React from 'react';

export default function InformasiKunjungan({
    nomorPendaftaran,
    nomorKunjungan,
    normPasien,
    namaPasien,
    ruanganTujuan,
    dpjp,
    tanggalKeluar,
    statusKunjungan,
}) {
    return (
        <>
            {/* Baris pertama */}
            <div className="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-4 gap-2 pb-2 text-sm">
                <div className="flex flex-col border p-2 rounded">
                    Pendaftaran : <br />
                    <span className="block text-yellow-500">{nomorPendaftaran}</span>
                </div>
                <div className="flex flex-col border p-2 rounded">
                    Kunjungan : <br />
                    <span className="block text-yellow-500">{nomorKunjungan}</span>
                </div>
                <div className="flex flex-col border p-2 rounded">
                    NORM : <br />
                    <span className="block text-yellow-500">{normPasien}</span>
                </div>
                <div className="flex flex-col border p-2 rounded">
                    Pasien : <br />
                    <span className="block text-yellow-500">{namaPasien}</span>
                </div>
            </div>

            {/* Baris kedua */}
            <div className="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-4 gap-2 pb-4 text-sm">
                <div className="flex flex-col border p-2 rounded">
                    Ruangan : <br />
                    <span className="block text-yellow-500">{ruanganTujuan}</span>
                </div>
                <div className="flex flex-col border p-2 rounded">
                    DPJP : <br />
                    <span className="block text-yellow-500">{dpjp}</span>
                </div>
                <div className="flex flex-col border p-2 rounded">
                    Keluar : <br />
                    <span className="block text-yellow-500">{tanggalKeluar}</span>
                </div>
                <div className="flex flex-col border p-2 rounded">
                    Status : <br />
                    <span className="block text-yellow-500">
                        {statusKunjungan === 0
                            ? 'Batal'
                            : statusKunjungan === 1
                                ? 'Sedang Dilayani'
                                : 'Selesai'}
                    </span>
                </div>
            </div>
        </>
    );
}

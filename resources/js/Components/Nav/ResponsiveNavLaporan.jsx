import React, { useState } from 'react';
import ResponsiveNavLink from '@/Components/Nav/ResponsiveNavLink';

export default function ResponsiveNavLaporan() {
    const [isOpen, setIsOpen] = useState(false);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className="relative">
            <button
                onClick={toggleDropdown}
                className="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-amber-400 hover:text-gray-800 dark:hover:text-amber-300 hover:bg-gray-50 dark:hover:bg-indigo-800 hover:border-gray-300 dark:hover:border-gray-600 text-base font-medium focus:outline-none transition duration-150 ease-in-out"
            >
                Logs
                <svg
                    className={`w-4 h-4 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`}
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            {isOpen && (
                <div className="absolute left-0 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-indigo-950 z-10">
                    <div className="rounded-md shadow-xs">
                        <div className="py-1">
                            <ResponsiveNavLink href={route('laporanRl12.index')}>Laporan RL 1.2</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('laporanRl31.index')}>Laporan RL 3.1</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('laporanRl32.index')}>Laporan RL 3.2</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('laporanRl314.index')}>Laporan RL 3.14</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('laporanRl315.index')}>Laporan RL 3.15</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('laporanRl51.index')}>Laporan RL 5.1</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('pengunjungPerPasien.index')}>Pengunjung Per Pasien</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('pengunjungPerHari.index')}>Pengunjung Per Hari</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('pengunjungCaraBayar.index')}>Pengunjung Cara Bayar</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('pengunjungRekap.index')}>Pengunjung Rekap</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('pengunjungWaktuTunggu.index')}>Waktu Tunggu</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kunjunganPerPasien.index')}>Kunjungan Per Pasien</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kunjunganPerHari.index')}>Kunjungan Per Hari</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kunjunganCaraBayar.index')}>Kunjungan Cara Bayar</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kunjunganPerUnit.index')}>Kunjungan Per Unit</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kunjunganRekap.index')}>Rekap Kunjungan</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('tindakanPasien.index')}>Tindakan Per Pasien</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('tindakanRekap.index')}>Rekap Tindakan</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('tindakanLabGroup.index')}>Tindakan Laboratorium</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('tindakanRadGroup.index')}>Tindakan Radiologi</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('tindakanRespondTime.index')}>Tindakan Respond Time</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kegiatanPasienMasuk.index')}>Pasien Masuk</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kegiatanPasienKeluarRanap.index')}>Pasien Keluar Ranap</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kegiatanPasienKeluarDarurat.index')}>Pasien Keluar Darurat</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kegiatanPasienMeninggal.index')}>Pasien Meninggal</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kegiatanPasienMeninggalRekap.index')}>Rekap Meninggal</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('lamaDirawat.index')}>Lama Dirawat</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('hariPerawatan.index')}>Hari Perawatan</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('pasienDirawat.index')}>Pasien Dirawat</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('kegiatanRanap.index')}>Rekap Kegiatan RI</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('laporanRP1.index')}>Rekap Sensus Harian</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

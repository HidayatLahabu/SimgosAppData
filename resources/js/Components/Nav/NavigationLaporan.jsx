import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/Nav/NavLink';

export default function NavigationLaporan() {
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    // Toggle dropdown visibility
    const toggleDropdown = (e) => {
        e.preventDefault();
        setIsDropdownOpen(!isDropdownOpen);
    };

    // Close the dropdown if clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setIsDropdownOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    // Function to check if any of the dropdown routes are active
    const isAnyDropdownLinkActive = () => {
        return route().current('laporanRl12.index') ||
            route().current('laporanRl31.index') ||
            route().current('laporanRl32.index') ||
            route().current('laporanRl314.index') ||
            route().current('laporanRl315.index') ||
            route().current('laporanRl51.index') ||
            route().current('pengunjungWaktuTunggu.index') ||
            route().current('pengunjungPerPasien.index') ||
            route().current('pengunjungPerHari.index') ||
            route().current('pengunjungCaraBayar.index') ||
            route().current('pengunjungRekap.index') ||
            route().current('kunjunganPerPasien.index') ||
            route().current('kunjunganPerHari.index') ||
            route().current('kunjunganCaraBayar.index') ||
            route().current('kunjunganPerUnit.index') ||
            route().current('kunjunganRekap.index') ||
            route().current('tindakanPasien.index') ||
            route().current('tindakanRekap.index') ||
            route().current('tindakanLabGroup.index') ||
            route().current('tindakanRadGroup.index') ||
            route().current('tindakanRespondTime.index') ||
            route().current('kegiatanPasienMasuk.index') ||
            route().current('kegiatanPasienKeluarRanap.index') ||
            route().current('kegiatanPasienKeluarDarurat.index') ||
            route().current('kegiatanPasienMeninggal.index') ||
            route().current('kegiatanPasienMeninggalRekap.index') ||
            route().current('lamaDirawat.index') ||
            route().current('hariPerawatan.index') ||
            route().current('pasienDirawat.index') ||
            route().current('laporanRP1.index') ||
            route().current('pasienBelumGrouping.index') ||
            route().current('rekapPasienKeluarRi.index') ||
            route().current('kegiatanRiPerUnit.index') ||
            route().current('respondTimeIgd.index') ||
            route().current('monitoringKegiatan.index') ||
            route().current('monitoringKegiatanRekap.index') ||
            route().current('kegiatanRawatInap.index');
    };

    const navLinks = [
        { label: 'Laporan RL 1.2', route: 'laporanRl12.index' },
        { label: 'Laporan RL 3.1', route: 'laporanRl31.index' },
        { label: 'Laporan RL 3.2', route: 'laporanRl32.index' },
        { label: 'Laporan RL 3.14', route: 'laporanRl314.index' },
        { label: 'Laporan RL 3.15', route: 'laporanRl315.index' },
        { label: 'Laporan RL 5.1', route: 'laporanRl51.index' },
        { label: 'Pengunjung Per Pasien', route: 'pengunjungPerPasien.index' },
        { label: 'Pengunjung Per Hari', route: 'pengunjungPerHari.index' },
        { label: 'Pengunjung Cara Bayar', route: 'pengunjungCaraBayar.index' },
        { label: 'Pengunjung Rekap', route: 'pengunjungRekap.index' },
        { label: 'Waktu Tunggu', route: 'pengunjungWaktuTunggu.index' },
        { label: 'Kunjungan Per Pasien', route: 'kunjunganPerPasien.index' },
        { label: 'Kunjungan Per Hari', route: 'kunjunganPerHari.index' },
        { label: 'Kunjungan Cara Bayar', route: 'kunjunganCaraBayar.index' },
        { label: 'Kunjungan Per Unit', route: 'kunjunganPerUnit.index' },
        { label: 'Rekap Kunjungan', route: 'kunjunganRekap.index' },
        { label: 'Tindakan Per Pasien', route: 'tindakanPasien.index' },
        { label: 'Rekap Tindakan', route: 'tindakanRekap.index' },
        { label: 'Tindakan Laboratorium', route: 'tindakanLabGroup.index' },
        { label: 'Tindakan Radiologi', route: 'tindakanRadGroup.index' },
        { label: 'Tindakan Respond Time', route: 'tindakanRespondTime.index' },
        { label: 'Pasien Masuk', route: 'kegiatanPasienMasuk.index' },
        { label: 'Pasien Keluar RI', route: 'kegiatanPasienKeluarRanap.index' },
        { label: 'Pasien Keluar IGD', route: 'kegiatanPasienKeluarDarurat.index' },
        { label: 'Pasien Meninggal', route: 'kegiatanPasienMeninggal.index' },
        { label: 'Rekap Meninggal', route: 'kegiatanPasienMeninggalRekap.index' },
        { label: 'Lama Dirawat', route: 'lamaDirawat.index' },
        { label: 'Hari Perawatan', route: 'hariPerawatan.index' },
        { label: 'Pasien Dirawat', route: 'pasienDirawat.index' },
        { label: 'Rekap Kegiatan RI', route: 'kegiatanRawatInap.index' },
        { label: 'Rekap Sensus Harian', route: 'laporanRP1.index' },
        { label: 'Belum Final Grouping', route: 'pasienBelumGrouping.index' },
        { label: 'Rekap Pasien Keluar RI', route: 'rekapPasienKeluarRi.index' },
        { label: 'Kegiatan RI Per Unit', route: 'kegiatanRiPerUnit.index' },
        { label: 'Respond Time IGD', route: 'respondTimeIgd.index' },
        { label: 'Monitoring Kegiatan', route: 'monitoringKegiatan.index' },
        { label: 'Rekap Kegiatan', route: 'monitoringKegiatanRekap.index' },
    ];

    // Sort the links alphabetically by label
    const sortedNavLinks = navLinks.sort((a, b) => a.label.localeCompare(b.label));

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Laporan
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-[600px] grid grid-cols-3 gap-2">
                    {sortedNavLinks.map((link) => (
                        <NavLink
                            key={link.route}
                            href={route(link.route)}
                            active={route().current(link.route)}
                            className="flex justify-between items-center px-4 py-2 w-full"
                        >
                            {link.label}
                        </NavLink>
                    ))}
                </div>
            )}
        </div>
    );
}

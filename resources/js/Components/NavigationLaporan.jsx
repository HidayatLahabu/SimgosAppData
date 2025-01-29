import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

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
            route().current('laporanWaktuTunggu.index') ||
            route().current('pengunjungPerPasien.index');
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
        { label: 'Waktu Tunggu', route: 'pengunjungWaktuTunggu.index' },
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
                // <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                //     <NavLink
                //         href={route('laporanRl12.index')}
                //         active={route().current('laporanRl12.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Laporan RL 1.2
                //     </NavLink>
                //     <NavLink
                //         href={route('laporanRl31.index')}
                //         active={route().current('laporanRl31.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Laporan RL 3.1
                //     </NavLink>
                //     <NavLink
                //         href={route('laporanRl32.index')}
                //         active={route().current('laporanRl32.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Laporan RL 3.2
                //     </NavLink>
                //     <NavLink
                //         href={route('laporanRl314.index')}
                //         active={route().current('laporanRl314.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Laporan RL 3.14
                //     </NavLink>
                //     <NavLink
                //         href={route('laporanRl315.index')}
                //         active={route().current('laporanRl315.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Laporan RL 3.15
                //     </NavLink>
                //     <NavLink
                //         href={route('laporanRl51.index')}
                //         active={route().current('laporanRl51.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Laporan RL 5.1
                //     </NavLink>
                //     <NavLink
                //         href={route('pengunjungPerPasien.index')}
                //         active={route().current('pengunjungPerPasien.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Pengunjung Per Pasien
                //     </NavLink>
                //     <NavLink
                //         href={route('laporanWaktuTunggu.index')}
                //         active={route().current('laporanWaktuTunggu.index')}
                //         className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                //     >
                //         Pengunjung Waktu Tunggu
                //     </NavLink>
                // </div>
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-96 grid grid-cols-2 gap-2">
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

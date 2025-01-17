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
            route().current('laporanRl51.index') ||
            route().current('laporanWaktuTunggu.index');
    };

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
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('laporanRl12.index')}
                        active={route().current('laporanRl12.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Laporan RL 1.2
                    </NavLink>
                    <NavLink
                        href={route('laporanRl31.index')}
                        active={route().current('laporanRl31.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Laporan RL 3.1
                    </NavLink>
                    <NavLink
                        href={route('laporanRl32.index')}
                        active={route().current('laporanRl32.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Laporan RL 3.2
                    </NavLink>
                    <NavLink
                        href={route('laporanRl314.index')}
                        active={route().current('laporanRl314.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Laporan RL 3.14
                    </NavLink>
                    <NavLink
                        href={route('laporanRl51.index')}
                        active={route().current('laporanRl51.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Laporan RL 5.1
                    </NavLink>
                    <NavLink
                        href={route('laporanWaktuTunggu.index')}
                        active={route().current('laporanWaktuTunggu.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Waktu Tunggu
                    </NavLink>
                </div>
            )}
        </div>
    );
}

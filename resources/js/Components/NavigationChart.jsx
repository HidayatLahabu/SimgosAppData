import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

export default function NavigationLogs() {
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
        return route().current('chartPendaftaran.index') ||
            route().current('chartBpjs.index') ||
            route().current('chartLayanan.index') ||
            route().current('chartInformasi.index') ||
            route().current('chartLaporan.index');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Chart
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('chartPendaftaran.index')}
                        active={route().current('chartPendaftaran.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Pendaftaran
                    </NavLink>
                    <NavLink
                        href={route('chartBpjs.index')}
                        active={route().current('chartBpjs.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        BPJS
                    </NavLink>
                    <NavLink
                        href={route('chartLayanan.index')}
                        active={route().current('chartLayanan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Layanan
                    </NavLink>
                    <NavLink
                        href={route('chartInformasi.index')}
                        active={route().current('chartInformasi.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Informasi
                    </NavLink>
                    <NavLink
                        href={route('chartLaporan.index')}
                        active={route().current('chartLaporan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Laporan
                    </NavLink>
                </div>
            )}
        </div>
    );
}

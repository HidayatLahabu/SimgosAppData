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
        return route().current('logsBridge.index') ||
            route().current('logsAkses.index') ||
            route().current('logsRequest.index');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Informasi
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('statistikKunjungan.index')}
                        active={route().current('statistikKunjungan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Statistik
                    </NavLink>
                    <NavLink
                        href={route('informasiKunjungan.index')}
                        active={route().current('informasiKunjungan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Kunjungan
                    </NavLink>
                    <NavLink
                        href={route('informasiPengunjung.index')}
                        active={route().current('informasiPengunjung.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Pengunjung
                    </NavLink>
                    <NavLink
                        href={route('informasiPenunjang.index')}
                        active={route().current('informasiPenunjang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Penunjang
                    </NavLink>
                </div>
            )}
        </div>
    );
}

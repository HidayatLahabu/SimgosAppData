import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/Nav/NavLink';

export default function NavigationBpjs() {
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
        return route().current('pesertaBpjs.index') ||
            route().current('kunjunganBpjs.index') ||
            route().current('kunjunganBpjs.filterByTime', 'hariIni') ||
            route().current('kunjunganBpjs.filterByTime', 'mingguIni') ||
            route().current('kunjunganBpjs.filterByTime', 'bulanIni') ||
            route().current('kunjunganBpjs.filterByTime', 'tahunIni') ||
            route().current('pengajuanSep.index') ||
            route().current('rekonBpjs.index') ||
            route().current('rekonBpjs.filterByTime', 'hariIni') ||
            route().current('monitoringRekon.index') ||
            route().current('monitoringRekon.filterByTime', 'hariIni') ||
            route().current('monitoringRekon.filterByTime', 'mingguIni') ||
            route().current('monitoringRekon.filterByTime', 'bulanIni') ||
            route().current('monitoringRekon.filterByTime', 'tahunIni') ||
            route().current('batalKontrol.index') ||
            route().current('batalKontrol.filterByTime', 'hariIni');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                BPJS
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('pesertaBpjs.index')}
                        active={route().current('pesertaBpjs.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Peserta
                    </NavLink>
                    <NavLink
                        href={route('kunjunganBpjs.index')}
                        active={route().current('kunjunganBpjs.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Kunjungan
                    </NavLink>
                    <NavLink
                        href={route('pengajuanSep.index')}
                        active={route().current('pengajuanSep.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Pengajuan SEP
                    </NavLink>
                    <NavLink
                        href={route('rekonBpjs.index')}
                        active={route().current('rekonBpjs.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Rencana Kontrol
                    </NavLink>
                    <NavLink
                        href={route('monitoringRekon.index')}
                        active={route().current('monitoringRekon.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Monitoring Rekon
                    </NavLink>
                    <NavLink
                        href={route('batalKontrol.index')}
                        active={route().current('batalKontrol.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Batal Kontrol
                    </NavLink>
                </div>
            )}
        </div>
    );
}

import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

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

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink href="#" onClick={toggleDropdown}>
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
                        Pengajuan
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
                        href={route('rujukanBpjs.index')}
                        active={route().current('rujukanBpjs.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Rujukan Masuk
                    </NavLink>
                </div>
            )}
        </div>
    );
}

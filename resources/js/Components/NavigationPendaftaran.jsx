import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

export default function NavigationPendaftaran() {
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
                Pendaftaran
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('pendaftaran.index')}
                        active={route().current('pendaftaran.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Pendaftaran
                    </NavLink>
                    <NavLink
                        href={route('kunjungan.index')}
                        active={route().current('kunjungan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Kunjungan
                    </NavLink>
                    <NavLink
                        href={route('konsul.index')}
                        active={route().current('konsul.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Konsul
                    </NavLink>
                    <NavLink
                        href={route('mutasi.index')}
                        active={route().current('mutasi.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Mutasi
                    </NavLink>
                    <NavLink
                        href={route('reservasi.index')}
                        active={route().current('reservasi.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Reservasi
                    </NavLink>
                    <NavLink
                        href={route('antrian.index')}
                        active={route().current('antrian.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Antrian Ruangan
                    </NavLink>
                </div>
            )}
        </div>
    );
}

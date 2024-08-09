import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

export default function NavigationMaster() {
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
        <div className="relative" ref={dropdownRef}>
            <NavLink href="#" onClick={toggleDropdown}>
                Master
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 w-48">
                    <NavLink
                        href={route('pasien.index')}
                        active={route().current('pasien.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Pasien
                    </NavLink>
                    <NavLink
                        href={route('pegawai.index')}
                        active={route().current('pegawai.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Pegawai
                    </NavLink>
                    <NavLink
                        href={route('dokter.index')}
                        active={route().current('dokter.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Dokter
                    </NavLink>
                    <NavLink
                        href={route('perawat.index')}
                        active={route().current('perawat.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Perawat
                    </NavLink>
                    <NavLink
                        href={route('staf.index')}
                        active={route().current('staf.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Staf
                    </NavLink>
                    <NavLink
                        href={route('referensi.index')}
                        active={route().current('referensi.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Referensi
                    </NavLink>
                    <NavLink
                        href={route('tindakan.index')}
                        active={route().current('tindakan.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Tindakan
                    </NavLink>
                </div>
            )}
        </div>
    );
}

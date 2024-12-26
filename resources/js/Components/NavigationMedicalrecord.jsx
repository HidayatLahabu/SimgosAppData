import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

export default function NavigationMedicalrecord() {
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
        return route().current('anamnesis.index') ||
            route().current('askep.index') ||
            route().current('cppt.index') ||
            route().current('jadwalKontrol.index');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Medicalrecord
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('anamnesis.index')}
                        active={route().current('anamnesis.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Anamnesis
                    </NavLink>
                    <NavLink
                        href={route('askep.index')}
                        active={route().current('askep.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Asuhan Keperawatan
                    </NavLink>
                    <NavLink
                        href={route('cppt.index')}
                        active={route().current('cppt.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        CPPT
                    </NavLink>
                    <NavLink
                        href={route('jadwalKontrol.index')}
                        active={route().current('jadwalKontrol.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Jadwal Kontrol
                    </NavLink>

                </div>
            )}
        </div>
    );
}

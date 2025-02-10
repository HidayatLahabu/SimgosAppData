import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/Nav/NavLink';

export default function NavigationLab() {
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
        return route().current('layananLab.index') ||
            route().current('layananLab.hasil') ||
            route().current('layananLab.catatan');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Laboratorium
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('layananLab.index')}
                        active={route().current('layananLab.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Data Order
                    </NavLink>

                    <NavLink
                        href={route('layananLab.hasil')}
                        active={route().current('layananLab.hasil')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Data Hasil
                    </NavLink>

                    <NavLink
                        href={route('layananLab.catatan')}
                        active={route().current('layananLab.catatan')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Catatan
                    </NavLink>
                </div>
            )}
        </div>
    );
}

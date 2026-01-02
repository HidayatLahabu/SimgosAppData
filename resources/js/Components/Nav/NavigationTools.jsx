import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/Nav/NavLink';
import { Link } from '@inertiajs/react';

export default function NavigationTools() {
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
        return route().current('toolsKunjungan.index');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Tools
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('toolsKunjungan.index')}
                        active={route().current('toolsKunjungan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Kunjungan
                    </NavLink>

                    <NavLink
                        href={route('toolsResep.index')}
                        active={route().current('toolsResep.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Order Resep
                    </NavLink>

                    <NavLink
                        href={route('toolsSO.index')}
                        active={route().current('toolsSO.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Stock Opname
                    </NavLink>
                </div>
            )}
        </div>
    );
}

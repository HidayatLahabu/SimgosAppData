import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/Nav/NavLink';

export default function NavigationRadiologi() {
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
        return route().current('layananRad.index') ||
            route().current('layananRad.hasil');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Radiologi
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('layananRad.index')}
                        active={route().current('layananRad.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Data Order
                    </NavLink>
                    <NavLink
                        href={route('layananRad.hasil')}
                        active={route().current('layananRad.hasil')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Data Hasil
                    </NavLink>
                </div>
            )}
        </div>
    );
}

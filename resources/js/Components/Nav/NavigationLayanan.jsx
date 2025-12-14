import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/Nav/NavLink';

export default function NavigationLayanan() {
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
            route().current('layananLab.orderLabDetail') ||
            route().current('layananLab.hasil') ||
            route().current('layananLab.catatan') ||
            route().current('layananRad.index') ||
            route().current('layananRad.hasil') ||
            route().current('layananResep.index') ||
            route().current('layananPulang.index');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Layanan
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('layananLab.index')}
                        active={route().current('layananLab.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Order Laboratorium
                    </NavLink>
                    <NavLink
                        href={route('layananLab.orderLabDetail')}
                        active={route().current('layananLab.orderLabDetail')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Order Lab Detail
                    </NavLink>
                    <NavLink
                        href={route('layananLab.hasil')}
                        active={route().current('layananLab.hasil')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Hasil Laboratorium
                    </NavLink>
                    <NavLink
                        href={route('layananLab.catatan')}
                        active={route().current('layananLab.catatan')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Catatan Laboratorium
                    </NavLink>
                    <NavLink
                        href={route('layananRad.index')}
                        active={route().current('layananRad.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Order Radiologi
                    </NavLink>
                    <NavLink
                        href={route('layananRad.hasil')}
                        active={route().current('layananRad.hasil')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Hasil Radiologi
                    </NavLink>
                    <NavLink
                        href={route('layananResep.index')}
                        active={route().current('layananResep.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Order Resep
                    </NavLink>
                    <NavLink
                        href={route('layananPulang.index')}
                        active={route().current('layananPulang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Pasien Pulang
                    </NavLink>
                </div>
            )}
        </div>
    );
}

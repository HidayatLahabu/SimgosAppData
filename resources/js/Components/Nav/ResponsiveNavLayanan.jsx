import React, { useState } from 'react';
import ResponsiveNavLink from '@/Components/Nav/ResponsiveNavLink';

export default function ResponsiveNavLayanan() {
    const [isOpen, setIsOpen] = useState(false);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className="relative">
            <button
                onClick={toggleDropdown}
                className="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-amber-400 hover:text-gray-800 dark:hover:text-amber-300 hover:bg-gray-50 dark:hover:bg-indigo-800 hover:border-gray-300 dark:hover:border-gray-600 text-base font-medium focus:outline-none transition duration-150 ease-in-out"
            >
                Layanan
                <svg
                    className={`w-4 h-4 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`}
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            {isOpen && (
                <div className="absolute left-0 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-indigo-950 z-10">
                    <div className="rounded-md shadow-xs">
                        <div className="py-1">
                            <ResponsiveNavLink href={route('layananLab.index')}>Order Laboratorium</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('layananLab.hasil')}>Hasil Laboratorium</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('layananLab.catatan')}>Catatan Laboratorium</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('layananRad.index')}>Order Radiologi</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('layananRad.hasil')}>Hasil Radiologi</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('layananResep.index')}>Resep</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('layananPulang.index')}>Pasien Pulang</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

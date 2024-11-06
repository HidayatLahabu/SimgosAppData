import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

export default function NavigationInventory() {
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
        return route().current('daftarBarang.index') ||
            route().current('barangRuangan.index') ||
            route().current('penerimaanBarang.index') ||
            route().current('pengirimanBarang.index') ||
            route().current('permintaanBarang.index') ||
            route().current('orderBarang.index') ||
            route().current('stockBarang.index') ||
            route().current('transaksiBarang.index');
    };

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                Inventory
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-48">
                    <NavLink
                        href={route('daftarBarang.index')}
                        active={route().current('daftarBarang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Daftar Barang
                    </NavLink>
                    <NavLink
                        href={route('barangRuangan.index')}
                        active={route().current('barangRuangan.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Barang Ruangan
                    </NavLink>
                    <NavLink
                        href={route('penerimaanBarang.index')}
                        active={route().current('penerimaanBarang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Penerimaan Barang
                    </NavLink>
                    <NavLink
                        href={route('pengirimanBarang.index')}
                        active={route().current('pengirimanBarang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Pengiriman Barang
                    </NavLink>
                    <NavLink
                        href={route('permintaanBarang.index')}
                        active={route().current('permintaanBarang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Permintaan Barang
                    </NavLink>
                    <NavLink
                        href={route('orderBarang.index')}
                        active={route().current('orderBarang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Order Barang
                    </NavLink>
                    <NavLink
                        href={route('stockBarang.index')}
                        active={route().current('stockBarang.index')}
                        className="flex justify-between items-center px-4 py-2 mb-1 w-full"
                    >
                        Stock Opname
                    </NavLink>
                    <NavLink
                        href={route('transaksiBarang.index')}
                        active={route().current('transaksiBarang.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Transaksi Ruangan
                    </NavLink>
                </div>
            )}
        </div>
    );
}

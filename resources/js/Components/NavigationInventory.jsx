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

    return (
        <div className="relative" ref={dropdownRef}>
            <NavLink href="#" onClick={toggleDropdown}>
                Inventory
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 w-48">
                    <NavLink
                        href={route('barang.index')}
                        active={route().current('barang.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Barang
                    </NavLink>
                    <NavLink
                        href={route('barangRuangan.index')}
                        active={route().current('barangRuangan.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Barang Ruangan
                    </NavLink>
                    <NavLink
                        href={route('penerimaan.index')}
                        active={route().current('penerimaan.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Penerimaan
                    </NavLink>
                    <NavLink
                        href={route('pengiriman.index')}
                        active={route().current('pengiriman.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Pengiriman
                    </NavLink>
                    <NavLink
                        href={route('permintaan.index')}
                        active={route().current('permintaan.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Permintaan
                    </NavLink>
                    <NavLink
                        href={route('order.index')}
                        active={route().current('order.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Purchase Order
                    </NavLink>
                    <NavLink
                        href={route('stock.index')}
                        active={route().current('stock.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Stock Opname
                    </NavLink>
                    <NavLink
                        href={route('transaksi.index')}
                        active={route().current('transaksi.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Transaksi Ruangan
                    </NavLink>
                </div>
            )}
        </div>
    );
}

import React from 'react';
import NavigationSatusehat from '@/Components/NavigationSatusehat';
import NavigationInventory from '@/Components/NavigationInventory';
import NavLink from '@/Components/NavLink';
import NavigationMaster from '@/Components/NavigationMaster';
import NavigationPendaftaran from '@/Components/NavigationPendaftaran';
import NavigationBpjs from '@/Components/NavigationBpjs';

export default function Navigation() {
    return (
        <div className="hidden sm:flex items-center space-x-8">
            <div className="flex-grow flex items-center">
                <div className="relative flex items-center pl-0 pr-1">
                    <NavLink
                        href={route('dashboard')}
                        active={route().current('dashboard')}
                        className="flex"
                    >
                        Beranda
                    </NavLink>
                </div>
                <NavigationSatusehat />
                <NavigationPendaftaran />
                <NavigationBpjs />
                <NavigationInventory />
                <NavigationMaster />
            </div>
        </div>

    );
}

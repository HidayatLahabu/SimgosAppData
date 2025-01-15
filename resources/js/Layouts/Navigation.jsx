import React from 'react';
import NavLink from '@/Components/NavLink';
import NavigationSatusehat from '@/Components/NavigationSatusehat';
import NavigationInventory from '@/Components/NavigationInventory';
import NavigationMaster from '@/Components/NavigationMaster';
import NavigationPendaftaran from '@/Components/NavigationPendaftaran';
import NavigationBpjs from '@/Components/NavigationBpjs';
import NavigationLayanan from '@/Components/NavigationLayanan';
import NavigationLogs from '@/Components/NavigationLogs';
import NavigationLaporan from '@/Components/NavigationLaporan';
import NavigationRadiologi from '@/Components/NavigationRadiologi';
import NavigationMedicalrecord from '@/Components/NavigationMedicalrecord';
import NavigationInformasi from '@/Components/NavigationInformasi';
import NavigationKontrol from '@/Components/NavigationKontrol';
import NavigationManajemen from '@/Components/NavigationManajemen';
import NavigationChart from '@/Components/NavigationChart';

export default function Navigation({ user }) {
    const userName = user.name || '';

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
                {userName.includes('Radiologi') ? (
                    <NavigationRadiologi />
                ) : userName.includes('Pendaftaran') ? (
                    <NavigationKontrol />
                ) : userName.includes('Manajemen') ? (
                    <>
                        <NavLink
                            href={route('sinkronisasi.index')}
                            active={route().current('sinkronisasi.index')}
                            className="flex"
                        >
                            Satusehat
                        </NavLink>
                        <NavigationManajemen />
                        <NavigationLaporan />
                        <NavigationChart />
                    </>

                ) :
                    (
                        <>
                            <NavigationSatusehat />
                            <NavigationPendaftaran />
                            <NavigationBpjs />
                            <NavigationLayanan />
                            <NavigationMedicalrecord />
                            <NavigationInventory />
                            <NavigationInformasi />
                            <NavigationLaporan />
                            <NavigationChart />
                            <NavigationLogs />
                            <NavigationMaster />
                        </>
                    )
                }
            </div>
        </div>
    );
}

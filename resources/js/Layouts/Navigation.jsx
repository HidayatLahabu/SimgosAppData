import React from 'react';
import NavLink from '@/Components/Nav/NavLink';
import NavigationSatusehat from '@/Components/Nav/NavigationSatusehat';
import NavigationInventory from '@/Components/Nav/NavigationInventory';
import NavigationMaster from '@/Components/Nav/NavigationMaster';
import NavigationPendaftaran from '@/Components/Nav/NavigationPendaftaran';
import NavigationBpjs from '@/Components/Nav/NavigationBpjs';
import NavigationLayanan from '@/Components/Nav/NavigationLayanan';
import NavigationLogs from '@/Components/Nav/NavigationLogs';
import NavigationLaporan from '@/Components/Nav/NavigationLaporan';
import NavigationRadiologi from '@/Components/Nav/NavigationRadiologi';
import NavigationMedicalrecord from '@/Components/Nav/NavigationMedicalrecord';
import NavigationInformasi from '@/Components/Nav/NavigationInformasi';
import NavigationKontrol from '@/Components/Nav/NavigationKontrol';
import NavigationManajemen from '@/Components/Nav/NavigationManajemen';
import NavigationChart from '@/Components/Nav/NavigationChart';
import NavigationLab from '@/Components/Nav/NavigationLab';
import NavigationTools from '@/Components/Nav/NavigationTools';

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
                ) : userName.includes('Laboratorium') ? (
                    <NavigationLab />
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
                            <NavigationTools />
                        </>
                    )
                }
            </div>
        </div>
    );
}

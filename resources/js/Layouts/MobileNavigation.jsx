import ResponsiveNavLink from '@/Components/Nav/ResponsiveNavLink';
import ResponsiveNavSatusehat from '@/Components/Nav/ResponsiveNavSatusehat';
import ResponsiveNavPendaftaran from '@/Components/Nav/ResponsiveNavPendaftaran';
import ResponsiveNavBpjs from '@/Components/Nav/ResponsiveNavBpjs';
import ResponsiveNavLayanan from '@/Components/Nav/ResponsiveNavLayanan';
import ResponsiveNavInventory from '@/Components/Nav/ResponsiveNavInventory';
import ResponsiveNavLogs from '@/Components/Nav/ResponsiveNavLogs';
import ResponsiveNavMaster from '@/Components/Nav/ResponsiveNavMaster';
import ResponsiveNavLaporan from '@/Components/Nav/ResponsiveNavLaporan';
import ResponsiveNavMedicalrecord from '@/Components/Nav/ResponsiveNavMedicalrecord';
import ResponsiveNavRadiologi from '@/Components/Nav/ResponsiveNavRadiologi';
import ResponsiveNavInformasi from '@/Components/Nav/ResponsiveNavInformasi';
import ResponsiveNavChart from '@/Components/Nav/ResponsiveNavChart';

export default function MobileNavigation({ user }) {
    const userName = user.name || '';

    return (
        <>
            <div className="pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div className="mt-1 space-y-1">
                    {userName.includes('Radiologi') ? (
                        <ResponsiveNavRadiologi />
                    ) : (
                        <>
                            <ResponsiveNavSatusehat />
                            <ResponsiveNavPendaftaran />
                            <ResponsiveNavBpjs />
                            <ResponsiveNavLayanan />
                            <ResponsiveNavMedicalrecord />
                            <ResponsiveNavInventory />
                            <ResponsiveNavInformasi />
                            <ResponsiveNavLaporan />
                            <ResponsiveNavChart />
                            <ResponsiveNavLogs />
                            <ResponsiveNavMaster />
                        </>
                    )}
                    <ResponsiveNavLink method="post" href={route('logout')} as="button">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </>
    );
}

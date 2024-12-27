import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import ResponsiveNavSatusehat from '@/Components/ResponsiveNavSatusehat';
import ResponsiveNavPendaftaran from '@/Components/ResponsiveNavPendaftaran';
import ResponsiveNavBpjs from '@/Components/ResponsiveNavBpjs';
import ResponsiveNavLayanan from '@/Components/ResponsiveNavLayanan';
import ResponsiveNavInventory from '@/Components/ResponsiveNavInventory';
import ResponsiveNavLogs from '@/Components/ResponsiveNavLogs';
import ResponsiveNavMaster from '@/Components/ResponsiveNavMaster';
import ResponsiveNavLaporan from '@/Components/ResponsiveNavLaporan';
import ResponsiveNavMedicalrecord from '@/Components/ResponsiveNavMedicalrecord';
import ResponsiveNavRadiologi from '@/Components/ResponsiveNavRadiologi';
import ResponsiveNavInformasi from '@/Components/ResponsiveNavInformasi';

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

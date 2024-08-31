import { usePage } from '@inertiajs/react';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import ResponsiveNavSatusehat from '@/Components/ResponsiveNavSatusehat';

export default function Navigation() {
    const { auth } = usePage().props;
    const userId = auth.user.id;

    return (
        <>
            <div className="pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div className="mt-1 space-y-1">
                    <ResponsiveNavSatusehat />
                    <ResponsiveNavLink method="post" href={route('logout')} as="button">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </>
    )
}

import { usePage } from '@inertiajs/react';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';

export default function Navigation() {
    const { auth } = usePage().props;
    const userId = auth.user.id;

    return (
        <>
            <div className="pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div className="mt-1 space-y-1">
                    <ResponsiveNavLink href={route('organization.index')}>Organization</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('location.index')}>Location</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('patient.index')}>Patient</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('practitioner.index')}>Practitioner</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('encounter.index')}>Encounter</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('condition.index')}>Condition</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('observation.index')}>Observation</ResponsiveNavLink>
                    <ResponsiveNavLink href={route('procedure.index')}>Procedure</ResponsiveNavLink>
                    <ResponsiveNavLink method="post" href={route('logout')} as="button">Log Out</ResponsiveNavLink>
                </div>
            </div>
        </>
    )
}

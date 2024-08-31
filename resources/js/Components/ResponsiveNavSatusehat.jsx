import React, { useState } from 'react';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';

export default function ResponsiveNavLinkSatusehat() {
    const [isOpen, setIsOpen] = useState(false);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className="relative">
            <button
                onClick={toggleDropdown}
                className="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-indigo-800 hover:border-gray-300 dark:hover:border-gray-600 text-base font-medium focus:outline-none transition duration-150 ease-in-out"
            >
                SatuSehat
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
                <div className="absolute left-0 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-gray-800 z-10">
                    <div className="rounded-md shadow-xs">
                        <div className="py-1">
                            <ResponsiveNavLink href={route('organization.index')}>Organization</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('location.index')}>Location</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('patient.index')}>Patient</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('practitioner.index')}>Practitioner</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('encounter.index')}>Encounter</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('condition.index')}>Condition</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('observation.index')}>Observation</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('procedure.index')}>Procedure</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('composition.index')}>Composition</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('consent.index')}>Consent</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('diagnosticReport.index')}>Diagnostic Report</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('medication.index')}>Medication</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('medicationDispanse.index')}>Medication Dispanse</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('medicationRequest.index')}>Medication Request</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('serviceRequest.index')}>Service Request</ResponsiveNavLink>
                            <ResponsiveNavLink href={route('specimen.index')}>Specimen</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

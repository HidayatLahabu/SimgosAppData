import React, { useState } from 'react';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';

export default function ResponsiveNavSatusehat() {
    const [isOpen, setIsOpen] = useState(false);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    const navLinks = [
        { href: 'organization.index', label: 'Organization' },
        { href: 'location.index', label: 'Location' },
        { href: 'patient.index', label: 'Patient' },
        { href: 'practitioner.index', label: 'Practitioner' },
        { href: 'encounter.index', label: 'Encounter' },
        { href: 'condition.index', label: 'Condition' },
        { href: 'observation.index', label: 'Observation' },
        { href: 'procedure.index', label: 'Procedure' },
        { href: 'composition.index', label: 'Composition' },
        { href: 'consent.index', label: 'Consent' },
        { href: 'diagnosticReport.index', label: 'Diagnostic Report' },
        { href: 'medication.index', label: 'Medication' },
        { href: 'medicationDispanse.index', label: 'Medication Dispanse' },
        { href: 'medicationRequest.index', label: 'Medication Request' },
        { href: 'serviceRequest.index', label: 'Service Request' },
        { href: 'specimen.index', label: 'Specimen' },
        { href: 'barangBza.index', label: 'Barang' },
        { href: 'carePlan.index', label: 'Care Plan' },
        { label: 'Condition Hasil PA', route: 'conditionPa.index' },
        { label: 'Condition Penilaian Tumor', route: 'conditionTumor.index' },
        { label: 'Imaging Study', route: 'imagingStudy.index' },
    ];

    // Sort navLinks by label in ascending order
    const sortedNavLinks = navLinks.sort((a, b) => a.label.localeCompare(b.label));

    return (
        <div className="relative">
            <button
                onClick={toggleDropdown}
                className="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-amber-400 hover:text-gray-800 dark:hover:text-amber-300 hover:bg-gray-50 dark:hover:bg-indigo-800 hover:border-gray-300 dark:hover:border-gray-600 text-base font-medium focus:outline-none transition duration-150 ease-in-out"
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
                <div className="absolute left-0 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-indigo-950 z-10">
                    <div className="rounded-md shadow-xs">
                        <div className="py-1">
                            {sortedNavLinks.map((link) => (
                                <ResponsiveNavLink
                                    key={link.href}
                                    href={route(link.href)}
                                >
                                    {link.label}
                                </ResponsiveNavLink>
                            ))}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

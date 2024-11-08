import React, { useState, useRef, useEffect } from 'react';
import NavLink from '@/Components/NavLink';

export default function NavigationSatusehat() {
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

    // Function to check if any of the dropdown routes are active
    const isAnyDropdownLinkActive = () => {
        return route().current('organization.index') ||
            route().current('location.index') ||
            route().current('patient.index') ||
            route().current('practitioner.index') ||
            route().current('encounter.index') ||
            route().current('condition.index') ||
            route().current('observation.index') ||
            route().current('procedure.index') ||
            route().current('composition.index') ||
            route().current('consent.index') ||
            route().current('diagnosticReport.index') ||
            route().current('medication.index') ||
            route().current('medicationDispanse.index') ||
            route().current('medicationRequest.index') ||
            route().current('serviceRequest.index') ||
            route().current('specimen.index') ||
            route().current('allergy.index') ||
            route().current('barangBza.index') ||
            route().current('carePlan.index') ||
            route().current('conditionPa.index') ||
            route().current('conditionTumor.index') ||
            route().current('imagingStudy.index') ||
            route().current('tindakanToLoinc.index');
    };

    // List of dropdown items
    const navLinks = [
        { label: 'Organization', route: 'organization.index' },
        { label: 'Location', route: 'location.index' },
        { label: 'Patient', route: 'patient.index' },
        { label: 'Practitioner', route: 'practitioner.index' },
        { label: 'Encounter', route: 'encounter.index' },
        { label: 'Condition', route: 'condition.index' },
        { label: 'Observation', route: 'observation.index' },
        { label: 'Procedure', route: 'procedure.index' },
        { label: 'Composition', route: 'composition.index' },
        { label: 'Consent', route: 'consent.index' },
        { label: 'Diagnostic Report', route: 'diagnosticReport.index' },
        { label: 'Medication', route: 'medication.index' },
        { label: 'Medication Dispanse', route: 'medicationDispanse.index' },
        { label: 'Medication Request', route: 'medicationRequest.index' },
        { label: 'Service Request', route: 'serviceRequest.index' },
        { label: 'Specimen', route: 'specimen.index' },
        { label: 'Allergy Intolerance', route: 'allergy.index' },
        { label: 'Barang', route: 'barangBza.index' },
        { label: 'Care Plan', route: 'carePlan.index' },
        { label: 'Condition Hasil PA', route: 'conditionPa.index' },
        { label: 'Condition Penilaian Tumor', route: 'conditionTumor.index' },
        { label: 'Imaging Study', route: 'imagingStudy.index' },
        { label: 'Tindakan To Loinc', route: 'tindakanToLoinc.index' },
    ];

    // Sort the links alphabetically by label
    const sortedNavLinks = navLinks.sort((a, b) => a.label.localeCompare(b.label));

    return (
        <div className="relative pr-1" ref={dropdownRef}>
            <NavLink
                href="#"
                onClick={toggleDropdown}
                active={isAnyDropdownLinkActive()}
            >
                SatuSehat
            </NavLink>
            {isDropdownOpen && (
                <div className="absolute dark:bg-indigo-900 text-white shadow-md mt-2 rounded-lg py-2 px-1 w-96 grid grid-cols-2 gap-2">
                    {sortedNavLinks.map((link) => (
                        <NavLink
                            key={link.route}
                            href={route(link.route)}
                            active={route().current(link.route)}
                            className="flex justify-between items-center px-4 py-2 w-full"
                        >
                            {link.label}
                        </NavLink>
                    ))}
                </div>
            )}
        </div>
    );
}

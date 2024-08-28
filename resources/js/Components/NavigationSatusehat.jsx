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
            route().current('specimen.index');
    };

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
                    <NavLink
                        href={route('organization.index')}
                        active={route().current('organization.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Organization
                    </NavLink>
                    <NavLink
                        href={route('location.index')}
                        active={route().current('location.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Location
                    </NavLink>
                    <NavLink
                        href={route('patient.index')}
                        active={route().current('patient.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Patient
                    </NavLink>
                    <NavLink
                        href={route('practitioner.index')}
                        active={route().current('practitioner.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Practitioner
                    </NavLink>
                    <NavLink
                        href={route('encounter.index')}
                        active={route().current('encounter.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Encounter
                    </NavLink>
                    <NavLink
                        href={route('condition.index')}
                        active={route().current('condition.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Condition
                    </NavLink>
                    <NavLink
                        href={route('observation.index')}
                        active={route().current('observation.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Observation
                    </NavLink>
                    <NavLink
                        href={route('procedure.index')}
                        active={route().current('procedure.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Procedure
                    </NavLink>
                    <NavLink
                        href={route('composition.index')}
                        active={route().current('composition.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Composition
                    </NavLink>
                    <NavLink
                        href={route('consent.index')}
                        active={route().current('consent.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Consent
                    </NavLink>
                    <NavLink
                        href={route('diagnosticReport.index')}
                        active={route().current('diagnosticReport.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Diagnostic Report
                    </NavLink>
                    <NavLink
                        href={route('medication.index')}
                        active={route().current('medication.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Medication
                    </NavLink>
                    <NavLink
                        href={route('medicationDispanse.index')}
                        active={route().current('medicationDispanse.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Medication Dispanse
                    </NavLink>
                    <NavLink
                        href={route('medicationRequest.index')}
                        active={route().current('medicationRequest.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Medication Request
                    </NavLink>
                    <NavLink
                        href={route('serviceRequest.index')}
                        active={route().current('serviceRequest.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Service Request
                    </NavLink>
                    <NavLink
                        href={route('specimen.index')}
                        active={route().current('specimen.index')}
                        className="flex justify-between items-center px-4 py-2 w-full"
                    >
                        Specimen
                    </NavLink>
                </div>
            )}
        </div>
    );
}

import { Link, usePage } from "@inertiajs/react";

export default function ButtonHari({ href, text = 'Hari Ini' }) {
    const { url } = usePage(); // Get current page URL from Inertia.js

    // Check if the current URL matches the href to apply active class
    const isActive = url === href;

    return (
        <Link
            href={href}
            className={`text-sm font-bold py-2 px-3 rounded 
                ${isActive ? 'bg-green-500 text-gray-200' : 'bg-amber-400 hover:bg-blue-500 text-gray-800 hover:text-gray-200'}`}
        >
            {text}
        </Link>
    );
}

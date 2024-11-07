import { Link } from "@inertiajs/react";

export default function ButtonKunjungan({ href, text = 'Kunjungan' }) {
    return (
        <Link
            href={href}
            className="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-3 rounded-lg">
            {text}
        </Link>
    );
}

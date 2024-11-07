import { Link } from "@inertiajs/react";

export default function PrintButton({ href, text = 'Cetak' }) {
    return (

        <Link
            href={href}
            className="bg-yellow-500 hover:bg-yellow-600 text-sm text-gray-800 font-bold py-2 px-4 rounded-lg">
            {text}
        </Link>

    );
}

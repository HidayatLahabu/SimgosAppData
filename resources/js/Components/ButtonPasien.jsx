import { Link } from "@inertiajs/react";

export default function ButtonPasien({ href, text = 'Pasien' }) {
    return (
        <Link
            href={href}
            className="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-3 rounded-lg">
            {text}
        </Link>
    );
}

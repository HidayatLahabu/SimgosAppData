import { Link } from "@inertiajs/react";

export default function CreateButton({ href, text = 'Kunjungan' }) {
    return (
        <Link href={href} className="bg-red-500 hover:bg-red-600 text-gray-800 text-sm font-bold py-2 px-3 rounded-lg">
            {text}
        </Link>
    );
}

import { Link } from "@inertiajs/react";

export default function CreateButton({ href, text = '+ Data' }) {
    return (
        <Link href={href} className="bg-green-500 hover:bg-green-600 text-gray-800 text-sm font-bold py-2 px-3 rounded-lg">
            {text}
        </Link>
    );
}

import { Link } from "@inertiajs/react";
import { ArrowLeftCircleIcon, PencilIcon } from "@heroicons/react/16/solid";

export default function ButtonBack({ href, text = 'Update Kunjugan' }) {
    return (
        <Link
            href={href}
            className="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-2 rounded-lg flex items-center"
        >
            <PencilIcon className="w-4 h-4 mr-1" />
            {text}
        </Link>
    );
}

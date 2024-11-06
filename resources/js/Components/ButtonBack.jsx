import { Link } from "@inertiajs/react";
import { ArrowLeftCircleIcon } from "@heroicons/react/16/solid";

export default function ButtonBack({ href, text = 'Back' }) {
    return (
        <Link
            href={href}
            className="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-2 rounded-lg flex items-center"
        >
            <ArrowLeftCircleIcon className="w-4 h-4 mr-1" />
            {text}
        </Link>
    );
}

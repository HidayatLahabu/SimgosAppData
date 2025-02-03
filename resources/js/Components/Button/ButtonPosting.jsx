import { Link } from "@inertiajs/react";

export default function PostingButton({ href, text = 'Post Data' }) {
    return (

        <Link href={href} className="bg-red-500 hover:bg-red-600 text-sm text-gray-800 font-bold py-2 px-4 rounded-lg">
            {text}
        </Link>

    );
}

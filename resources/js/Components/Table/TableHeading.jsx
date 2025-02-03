import { ChevronUpIcon, ChevronDownIcon } from "@heroicons/react/16/solid";

export default function TableHeading({
    name,
    sortable = true,
    sort_field = null,
    sort_direction = null,
    shortChanged = () => { },
    children,
}) {
    return (
        <th onClick={(e) => shortChanged(name)}>
            <div className="px-3 py-2 flex items-center justify-between cursor-pointer">
                {children}
                {sortable && (
                    <div>
                        <ChevronUpIcon
                            className={
                                "w-4 " +
                                (sort_field === name && sort_direction === "asc"
                                    ? "text-yellow-500"
                                    : "")
                            }
                        />
                        <ChevronDownIcon
                            className={
                                "w-4 -mt-2 " +
                                (sort_field === name &&
                                    sort_direction === "desc"
                                    ? "text-yellow-500"
                                    : "")
                            }
                        />
                    </div>
                )}
            </div>
        </th>
    );
}

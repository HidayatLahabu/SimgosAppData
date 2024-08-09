import { useState, useEffect } from 'react';

export default function SuccessMessage({ message }) {
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        if (message) {
            setVisible(true);

            const timer = setTimeout(() => {
                setVisible(false);
            }, 2000); // 2 seconds

            return () => clearTimeout(timer);
        }
    }, [message]);

    return visible ? (
        <div className="bg-lime-500 text-gray-800 font-bold py-2 px-4 rounded mb-2">
            {message}
        </div>
    ) : null;
}

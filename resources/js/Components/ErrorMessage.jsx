import { useState, useEffect } from 'react';

export default function ErrorMessage({ message }) {
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        if (message) {
            setVisible(true);

            const timer = setTimeout(() => {
                setVisible(false);
            }, 5000); // 5 seconds

            return () => clearTimeout(timer);
        }
    }, [message]);

    return visible ? (
        <div className="bg-red-600 text-gray-200 font-bold py-2 px-4 rounded mb-2">
            {message}
        </div>
    ) : null;
}

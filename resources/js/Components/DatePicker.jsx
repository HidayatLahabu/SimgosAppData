import React, { useState } from 'react';
import dayjs from 'dayjs'; // For date manipulation
import 'tailwindcss/tailwind.css'; // Ensure Tailwind CSS is imported

const DatePicker = ({ value, onChange, className = '' }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [currentMonth, setCurrentMonth] = useState(dayjs().format('YYYY-MM'));

    const handleDateClick = (date) => {
        onChange(date.toDate()); // Call onChange with the selected date
        setIsOpen(false);
    };

    const handleMonthChange = (direction) => {
        const newMonth = dayjs(currentMonth).add(direction, 'month').format('YYYY-MM');
        setCurrentMonth(newMonth);
    };

    const renderDays = () => {
        const startOfMonth = dayjs(currentMonth + '-01');
        const endOfMonth = startOfMonth.endOf('month');
        const days = [];

        for (let day = startOfMonth.startOf('week'); day.isBefore(endOfMonth.endOf('week')); day = day.add(1, 'day')) {
            days.push(day);
        }

        return (
            <div className="grid grid-cols-7 gap-1">
                {days.map(day => (
                    <button
                        key={day.format('DD-MM-YYYY')}
                        className={`p-2 rounded-full ${day.isSame(dayjs(value), 'day') ? 'bg-indigo-700 text-white' : 'hover:bg-indigo-500'}`}
                        onClick={() => handleDateClick(day)}
                    >
                        {day.format('D')}
                    </button>
                ))}
            </div>
        );
    };

    return (
        <div className={`relative ${className}`}>
            <input
                type="text"
                readOnly
                value={dayjs(value).format('DD-MM-YYYY')}
                onClick={() => setIsOpen(!isOpen)}
                className="w-full border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-950 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 p-2"
            />
            {isOpen && (
                <div className="fixed z-50 mt-1 p-2 border border-indigo-500 text-gray-200 bg-white dark:bg-gray-950 rounded-md shadow-lg">
                    <div className="flex justify-between items-center mb-2">
                        <button onClick={() => handleMonthChange(-1)}>&lt;</button>
                        <span>{dayjs(currentMonth).format('MMMM YYYY')}</span>
                        <button onClick={() => handleMonthChange(1)}>&gt;</button>
                    </div>
                    {renderDays()}
                </div>
            )}
        </div>
    );
};

export default DatePicker;

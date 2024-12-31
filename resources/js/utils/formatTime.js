// utils/formatTime.js

export function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = Math.round(seconds % 60);

    return `${hours} Jam ${minutes} Menit ${secs} Detik`;
}

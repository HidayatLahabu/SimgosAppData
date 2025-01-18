import React, { useRef, useState, useEffect } from "react";
import KunjunganRanap from "./KunjunganRanap"; // Import KunjunganRanap
import RencanaKontrol from "./RencanaKontrol"; // Import RencanaKontrol

const RanapKontrolWrapper = ({ kunjunganRanap, rekonBpjs }) => {
    const kunjunganRef = useRef(null); // Ref untuk KunjunganRanap
    const [kunjunganHeight, setKunjunganHeight] = useState(0);

    useEffect(() => {
        if (kunjunganRef.current) {
            // Perbarui tinggi KunjunganRanap
            setKunjunganHeight(kunjunganRef.current.offsetHeight);
        }
    }, [kunjunganRanap]); // Update jika kunjunganRanap berubah

    return (
        <div className="pb-2 flex flex-row gap-2 justify-center items-start w-full">
            <div className="flex flex-col w-2/3" ref={kunjunganRef}>
                <KunjunganRanap kunjunganRanap={kunjunganRanap} />
            </div>
            <div
                className="flex flex-col w-1/3"
                style={{ height: kunjunganHeight }}
            >
                <RencanaKontrol rekonBpjs={rekonBpjs} />
            </div>
        </div>
    );
};

export default RanapKontrolWrapper;

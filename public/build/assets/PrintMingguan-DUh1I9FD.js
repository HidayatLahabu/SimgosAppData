import{j as r}from"./app-1kLFVW50.js";import{f as d}from"./formatRibuan-BoXPYuQw.js";function x({rajalMingguan:s,ranapMingguan:t}){return r.jsx("div",{className:"overflow-auto mt-5",children:r.jsxs("div",{className:"flex space-x-4",children:[r.jsxs("div",{className:"w-1/2",children:[r.jsx("h2",{className:"text-center font-bold text-2xl",children:"RAWAT JALAN & RAWAT DARURAT MINGGUAN"}),r.jsxs("table",{className:"w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500",children:[r.jsx("thead",{className:"text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500",children:r.jsxs("tr",{children:[r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"TAHUN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"MINGGU"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right",children:"RAWAT JALAN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right",children:"RAWAT DARURAT"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right",children:"RAWAT INAP"})]})}),r.jsx("tbody",{children:s.map((e,a)=>r.jsxs("tr",{className:"border-b bg-white dark:border-gray-500",children:[r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.tahun}),r.jsx("td",{className:"px-3 py-2 border border-gray-500 border-solid",children:e.minggu}),r.jsx("td",{className:"px-3 py-2 text-nowrap text-right border border-gray-500 border-solid",children:d(e.rajal)}),r.jsx("td",{className:"px-3 py-2 text-nowrap text-right border border-gray-500 border-solid",children:d(e.darurat)}),r.jsx("td",{className:"px-3 py-2 border text-nowrap text-right border-gray-500 border-solid",children:d(e.semua)})]},e.minggu))})]})]}),r.jsxs("div",{className:"w-1/2",children:[r.jsx("h2",{className:"text-center font-bold text-2xl",children:"RAWAT INAP MINGGUAN"}),r.jsxs("table",{className:"w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500",children:[r.jsx("thead",{className:"text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500",children:r.jsxs("tr",{children:[r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"TAHUN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"MINGGU"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right",children:"PASIEN MASUK"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right",children:"PASIEN DIRAWAT"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right",children:"PASIEN KELUAR"})]})}),r.jsx("tbody",{children:t.map((e,a)=>r.jsxs("tr",{className:"border-b bg-white dark:border-gray-500",children:[r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.tahun}),r.jsx("td",{className:"px-3 py-2 border border-gray-500 border-solid",children:e.minggu}),r.jsx("td",{className:"px-3 py-2 text-nowrap text-right border border-gray-500 border-solid",children:d(e.masuk)}),r.jsx("td",{className:"px-3 py-2 text-nowrap text-right border border-gray-500 border-solid",children:d(e.dirawat)}),r.jsx("td",{className:"px-3 py-2 border text-right border-gray-500 border-solid",children:d(e.keluar)})]},e.minggu))})]})]})]})})}export{x as default};
import{j as e}from"./app-ied06ZXI.js";import{T as t}from"./ButtonDetail-CMg370hU.js";function c({nomorPendaftaran:l,dataKunjungan:a={}}){return e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-xl pb-2",children:["DAFTAR KUNJUNGAN ",e.jsx("br",{}),"DENGAN NOMOR PENDAFTARAN : ",l]}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NO"}),e.jsx("th",{className:"px-3 py-2",children:"NOMOR KUNJUNGAN"}),e.jsx("th",{className:"px-3 py-2",children:"NOMOR PENDAFTARAN"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL MASUK"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL KELUAR"}),e.jsx("th",{className:"px-3 py-2",children:"RUANGAN TUJUAN"}),e.jsx("th",{className:"px-3 py-2",children:"MENU"})]})}),e.jsx("tbody",{children:a.length>0?a.map((s,r)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:r+1}),e.jsx("td",{className:"px-3 py-3",children:s.nomor}),e.jsx("td",{className:"px-3 py-3",children:s.pendaftaran}),e.jsx("td",{className:"px-3 py-3",children:s.masuk}),e.jsx("td",{className:"px-3 py-3",children:s.keluar}),e.jsx("td",{className:"px-3 py-3",children:s.ruangan}),e.jsx("td",{className:"px-3 py-3",children:s.nomor?e.jsx(t,{href:route("kunjungan.tableRme",{id:s.nomor})}):e.jsx("span",{className:"text-gray-500",children:"No detail available"})})]},r)):e.jsx("tr",{children:e.jsx("td",{colSpan:"8",className:"text-center px-3 py-3",children:"No data available"})})})]})]})})})})})}export{c as default};
import{j as e,Y as g,y as m}from"./app-Cv_FtKNC.js";import{A as p}from"./AuthenticatedLayout-J9zICOLn.js";import{T as y}from"./TextInput-YpzqUUu0.js";import{P as b}from"./Pagination-D7oLwUhC.js";import"./transition-BTs6vKcd.js";function w({auth:x,dataTable:d,reservasiData:a,filter:l,header:o,queryParams:i={}}){const n=(r,s)=>{const t={...i,page:1};s?t[r]=s:delete t[r],m.get(route("antrian.index"),t,{preserveState:!0,preserveScroll:!0})},c=(r,s)=>{const t=s.target.value;n(r,t)},h=(r,s)=>{s.key==="Enter"&&n(r,s.target.value)};return e.jsxs(p,{user:x.user,children:[e.jsx(g,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Reservasi ",o]}),e.jsxs("div",{className:"flex flex-wrap gap-4 justify-between mb-4",children:[e.jsxs("a",{href:route("reservasi.index"),className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block",children:[e.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"JUMLAH DATA RESERVASI"}),e.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[a.total_reservasi," PASIEN"]})]}),e.jsxs("a",{href:route("reservasi.filterByStatus","batal"),className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block",children:[e.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"BATAL RESERVASI"}),e.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[a.total_batal_reservasi," PASIEN"]})]}),e.jsxs("a",{href:route("reservasi.filterByStatus","reservasi"),className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block",children:[e.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"PROSES RESERVASI"}),e.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[a.total_proses_reservasi," PASIEN"]})]}),e.jsxs("a",{href:route("reservasi.filterByStatus","selesai"),className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block",children:[e.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"SELESAI RESERVASI"}),e.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[a.total_selesai_reservasi," PASIEN"]})]})]}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:7,className:"px-3 py-2",children:e.jsx(y,{className:"w-full",defaultValue:i.search||"",placeholder:"Cari data berdasarkan nomor antrian, NORM, atau nama pasien",onChange:r=>c("search",r),onKeyPress:r=>h("search",r)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NOMOR"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL"}),e.jsx("th",{className:"px-3 py-2",children:"ATAS NAMA"}),e.jsx("th",{className:"px-3 py-2",children:"RUANGAN"}),e.jsx("th",{className:"px-3 py-2",children:"KAMAR"}),e.jsx("th",{className:"px-3 py-2",children:"TEMPAT TIDUR"}),l?e.jsx("th",{className:"px-3 py-2",children:"NOMOR KONTAK"}):e.jsx("th",{className:"px-3 py-2",children:"STATUS"})]})}),e.jsx("tbody",{children:d.data.length>0?d.data.map((r,s)=>e.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${s%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[e.jsx("td",{className:"px-3 py-3",children:r.nomor}),e.jsx("td",{className:"px-3 py-3",children:r.tanggal}),e.jsx("td",{className:"px-3 py-3 uppercase",children:r.pasien}),e.jsx("td",{className:"px-3 py-3",children:r.ruangan}),e.jsx("td",{className:"px-3 py-3",children:r.kamar}),e.jsx("td",{className:"px-3 py-3",children:r.tempatTidur}),l?e.jsx("td",{className:"px-3 py-3",children:r.kontak}):e.jsx("td",{className:"px-3 py-3",children:r.status===0?"Batal Reservasi":r.status===1?"Reservasi":"Selesai"})]},`${r.nomor}-${s}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(b,{links:d.links})]})})})})})]})}export{w as default};
import{j as e}from"./app-yhfm9Cl-.js";import{P as o}from"./Pagination-BTrs9T48.js";import{T as c,a as m,b as x}from"./TableHeaderCell-DvGVFM9U.js";import{T as A,a as n}from"./TableCell-CD-1nHQ7.js";function h(a){const r=Math.floor(a/3600),l=Math.floor(a%3600/60),i=Math.round(a%60);return`${r} Jam ${l} Menit ${i} Detik`}function b({averageWaitData:a,namaBulan:r,tahun:l}){const i=[{name:"RUANGAN RAWATAN",className:"w-[25%]"},{name:"NAMA DOKTER PELAKSANA LAYANAN"},{name:"RATA-RATA WAKTU TUNGGU",className:"w-[20%]"},{name:"JUMLAH PASIEN DILAYANI",className:"text-right w-[15%]"}],d=(s=>{const t=new Date(2024,s-1);return new Intl.DateTimeFormat("id-ID",{month:"long"}).format(t)})(r);return e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"RATA-RATA WAKTU TUNGGU RAWAT JALAN"}),e.jsxs("p",{className:"text-center pb-4",children:[e.jsx("strong",{children:"Periode : "}),d," ",l]}),e.jsxs(c,{children:[e.jsx(m,{children:e.jsx("tr",{children:i.map((s,t)=>e.jsx(x,{className:s.className||"",children:s.name},t))})}),e.jsx("tbody",{children:a.data.length>0?a.data.map((s,t)=>e.jsxs(A,{isEven:t%2===0,children:[e.jsx(n,{children:s.UNITPELAYANAN}),e.jsx(n,{children:s.DOKTER_REG}),e.jsx(n,{children:h(s.AVERAGE_SELSIH)}),e.jsx(n,{className:"text-right",children:s.JUMLAH_PASIEN})]},s.NOMOR)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(o,{links:a.links})]})})})})})}export{b as default};
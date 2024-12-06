import{j as e,Y as h,y as u}from"./app-Cv_FtKNC.js";import{A as y}from"./AuthenticatedLayout-J9zICOLn.js";import{T as j}from"./TextInput-YpzqUUu0.js";import{P as N}from"./Pagination-D7oLwUhC.js";import{T as f}from"./ButtonDetail-ljgeon42.js";import k from"./Cetak-DlTaEhoe.js";import{B as t}from"./ButtonTime-CB1tXuYX.js";import"./transition-BTs6vKcd.js";import"./SelectTwoInput-DOHeVD-0.js";import"./InputLabel-CS7TAvdE.js";function S({auth:d,dataTable:n,header:x,totalCount:c,text:o,keadaanPulang:p,queryParams:l={}}){const i=(a,r)=>{const s={...l,page:1};r?s[a]=r:delete s[a],u.get(route("layananPulang.index"),s,{preserveState:!0,preserveScroll:!0})},g=(a,r)=>{const s=r.target.value;i(a,s)},m=(a,r)=>{r.key==="Enter"&&i(a,r.target.value)};return e.jsxs(y,{user:d.user,children:[e.jsx(h,{title:"Layanan"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Pasien Pulang ",x," ",c," ",o]}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:9,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(j,{className:"w-full",defaultValue:l.search||"",placeholder:"Cari data berdasarkan nomor kunjungan, NORM, atau nama pasien",onChange:a=>g("search",a),onKeyPress:a=>m("search",a)}),e.jsx(t,{href:route("layananPulang.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(t,{href:route("layananPulang.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(t,{href:route("layananPulang.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(t,{href:route("layananPulang.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"KUNJUNGAN"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PASIEN"}),e.jsx("th",{className:"px-3 py-2",children:"KEADAAN"}),e.jsx("th",{className:"px-3 py-2",children:"DPJP"}),e.jsx("th",{className:"px-3 py-2 text-center",children:"MENU"})]})}),e.jsx("tbody",{children:n.data.length>0?n.data.map((a,r)=>e.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${r%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[e.jsx("td",{className:"px-3 py-3",children:a.kunjungan}),e.jsx("td",{className:"px-3 py-3",children:a.tanggal}),e.jsx("td",{className:"px-3 py-3",children:a.norm}),e.jsx("td",{className:"px-3 py-3 uppercase",children:a.nama}),e.jsx("td",{className:"px-3 py-3",children:a.keadaan}),e.jsxs("td",{className:"px-3 py-3",children:[a.gelarDepan," ",e.jsx("span",{className:"uppercase",children:a.dokter}),"  ",a.gelarBelakang]}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(f,{href:route("layananPulang.detail",{id:a.id})})})]},`${a.id}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(N,{links:n.links})]})})})})}),e.jsx("div",{className:"w-full",children:e.jsx(k,{keadaanPulang:p})})]})}export{S as default};
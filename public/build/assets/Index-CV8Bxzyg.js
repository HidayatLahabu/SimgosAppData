import{j as e,Y as m,y as h}from"./app-e2GjmbHP.js";import{A as g}from"./AuthenticatedLayout-CyKUd5Ks.js";import{T as j}from"./TextInput-D3k_5YG3.js";import{P as f}from"./Pagination-BFLi1ybH.js";import{T as y}from"./ButtonDetail-jE7HBVkl.js";import{B as s}from"./ButtonTime-H_wCs4nb.js";import"./transition-Aa_Yv-07.js";function T({auth:d,dataTable:n,header:x,totalCount:c,queryParams:i={}}){const l=(a,r)=>{const t={...i,page:1};r?t[a]=r:delete t[a],h.get(route("pendaftaran.index"),t,{preserveState:!0,preserveScroll:!0})},o=(a,r)=>{const t=r.target.value;l(a,t)},p=(a,r)=>{r.key==="Enter"&&l(a,r.target.value)};return e.jsxs(g,{user:d.user,children:[e.jsx(m,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Pendaftaran ",x," ",c," Pasien"]}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:7,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(j,{className:"flex-1",defaultValue:i.search||"",placeholder:"Cari data berdasarkan nomor pendaftaran, NORM, atau nama pasien",onChange:a=>o("search",a),onKeyPress:a=>p("search",a)}),e.jsx(s,{href:route("pendaftaran.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(s,{href:route("pendaftaran.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(s,{href:route("pendaftaran.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(s,{href:route("pendaftaran.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NOMOR"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PASIEN"}),e.jsx("th",{className:"px-3 py-2",children:"ALAMAT"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL"}),e.jsx("th",{className:"px-3 py-2",children:"STATUS"}),e.jsx("th",{className:"px-3 py-2 text-center",children:"MENU"})]})}),e.jsx("tbody",{children:n.data.length>0?n.data.map((a,r)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:a.nomor}),e.jsx("td",{className:"px-3 py-3",children:a.norm}),e.jsx("td",{className:"px-3 py-3 data",children:a.nama}),e.jsx("td",{className:"px-3 py-3",children:a.alamat}),e.jsx("td",{className:"px-3 py-3",children:a.tanggal}),e.jsx("td",{className:"px-3 py-3",children:a.status===0?"Batal":a.status===1?"Aktif":"Selesai"}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(y,{href:route("pendaftaran.detail",{id:a.nomor})})})]},`${a.nomor}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(f,{links:n.links})]})})})})})]})}export{T as default};
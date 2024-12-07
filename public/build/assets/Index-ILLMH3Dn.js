import{j as r,Y as h,y as m}from"./app-BZRUO3DV.js";import{A as p}from"./AuthenticatedLayout-BAx1oDWl.js";import{T as y}from"./TextInput-ULnzWxAt.js";import{P as j}from"./Pagination-2EDbv7qa.js";import{T as N}from"./ButtonDetail-CRNKnZga.js";import{B as s}from"./ButtonTime-BbvBmmbv.js";import"./transition-Daa6mMa6.js";function I({auth:i,dataTable:n,header:x,totalCount:g,rataRata:d,queryParams:l={}}){const o=(e,a)=>{const t={...l,page:1};a?t[e]=a:delete t[e],m.get(route("pendaftaran.index"),t,{preserveState:!0,preserveScroll:!0})},c=(e,a)=>{const t=a.target.value;o(e,t)},b=(e,a)=>{a.key==="Enter"&&o(e,a.target.value)};return r.jsxs(p,{user:i.user,children:[r.jsx(h,{title:"Pendaftaran"}),r.jsx("div",{className:"py-5",children:r.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:r.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:r.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:r.jsxs("div",{className:"overflow-auto w-full",children:[r.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Pendaftaran ",x," ",g," Pasien"]}),r.jsxs("div",{className:"flex flex-wrap gap-4 justify-between mb-4",children:[r.jsxs("div",{className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700",children:[r.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"RATA-RATA PER HARI"}),r.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[d.rata_rata_per_hari," PASIEN"]})]}),r.jsxs("div",{className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700",children:[r.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"RATA-RATA PER MINGGU"}),r.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[d.rata_rata_per_minggu," PASIEN"]})]}),r.jsxs("div",{className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700",children:[r.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"RATA-RATA PER BULAN"}),r.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[d.rata_rata_per_bulan," PASIEN"]})]}),r.jsxs("div",{className:"flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700",children:[r.jsx("h2",{className:"text-lg font-bold text-gray-700 dark:text-yellow-400",children:"RATA-RATA PER TAHUN"}),r.jsxs("p",{className:"text-2xl font-semibold text-indigo-600 dark:text-white mt-2",children:[d.rata_rata_per_tahun," PASIEN"]})]})]}),r.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600",children:[r.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100",children:r.jsx("tr",{children:r.jsx("th",{colSpan:7,className:"px-3 py-2",children:r.jsxs("div",{className:"flex items-center space-x-2",children:[r.jsx(y,{className:"flex-1",defaultValue:l.search||"",placeholder:"Cari data berdasarkan NORM, nama pasien atau nomor pendaftaran",onChange:e=>c("search",e),onKeyPress:e=>b("search",e)}),r.jsx(s,{href:route("pendaftaran.filterByTime","hariIni"),text:"Hari Ini"}),r.jsx(s,{href:route("pendaftaran.filterByTime","mingguIni"),text:"Minggu Ini"}),r.jsx(s,{href:route("pendaftaran.filterByTime","bulanIni"),text:"Bulan Ini"}),r.jsx(s,{href:route("pendaftaran.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),r.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500",children:r.jsxs("tr",{children:[r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"NORM"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"NAMA PASIEN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"NOMOR"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"TANGGAL"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"PENJAMIN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"STATUS"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600 text-center",children:"MENU"})]})}),r.jsx("tbody",{children:n.data.length>0?n.data.map((e,a)=>r.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${a%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.norm}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.nama}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.nomor}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.tanggal}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.penjamin}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.status===0?"Batal":e.status===1?"Aktif":"Selesai"}),r.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:r.jsx(N,{href:route("pendaftaran.detail",{id:e.nomor})})})]},`${e.nomor}-${a}`)):r.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:r.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),r.jsx(j,{links:n.links})]})})})})})]})}export{I as default};
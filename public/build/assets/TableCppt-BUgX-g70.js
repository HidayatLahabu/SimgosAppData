import{j as e,S as p}from"./app-CiDKe7ft.js";import{A as u}from"./AuthenticatedLayout-v1EIKjjC.js";import{B as N}from"./ButtonBack-BndT2FPC.js";import{T as f}from"./ButtonDetail-Co_RRfqA.js";import{T as b,a as g,b as T}from"./TableHeaderCell-Dit1MAtR.js";import{T as v,a as s}from"./TableCell-BgSBgdE5.js";import{T as k}from"./TableCellMenu-BgFsg06U.js";import w from"./InfoKunjungan-DAIHB9Ow.js";import"./transition-BmadNWPB.js";function D({auth:t,dataTable:r,nomorKunjungan:n,nomorPendaftaran:i,namaPasien:d,normPasien:c,ruanganTujuan:x,statusKunjungan:m,tanggalKeluar:o,dpjp:j}){const h=[{name:"NO"},{name:"NOMOR KUNJUNGAN"},{name:"ID CPPT"},{name:"TANGGAL",className:"text-center"},{name:"OLEH"},{name:"STATUS"},{name:"MENU",className:"text-center"}];return e.jsxs(u,{user:t.user,children:[e.jsx(p,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("div",{className:"relative flex items-center justify-between pb-2",children:[e.jsx(N,{href:route("kunjungan.detail",{id:n})}),e.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl",children:"DAFTAR CPPT"})]}),e.jsx(w,{nomorPendaftaran:i,nomorKunjungan:n,normPasien:c,namaPasien:d,ruanganTujuan:x,dpjp:j,tanggalKeluar:o,statusKunjungan:m}),e.jsxs(b,{children:[e.jsx(g,{children:e.jsx("tr",{children:h.map((a,l)=>e.jsx(T,{className:a.className||"",children:a.name},l))})}),e.jsx("tbody",{children:r.length>0?r.map((a,l)=>e.jsxs(v,{children:[e.jsx(s,{children:l+1}),e.jsx(s,{children:a.kunjungan}),e.jsx(s,{children:a.id}),e.jsx(s,{className:"text-center",children:a.tanggal}),e.jsx(s,{children:a.oleh}),e.jsx(s,{children:a.status?"Selesai":"Sedang Dilayani"}),e.jsx(k,{children:a.id?e.jsx(f,{href:route("kunjungan.detailCppt",{id:a.id})}):e.jsx("span",{className:"text-gray-500",children:"No detail available"})})]},l)):e.jsx("tr",{children:e.jsx("td",{colSpan:"8",className:"text-center px-3 py-3",children:"No data available"})})})]})]})})})})})]})}export{D as default};
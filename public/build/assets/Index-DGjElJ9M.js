import{j as e,S as g,A}from"./app-BnYswUBu.js";import{A as N}from"./AuthenticatedLayout-BHGmwhNy.js";import{T}from"./TextInput-C8zasAw4.js";import{P as b}from"./Pagination-RKOr90UQ.js";import{T as v}from"./ButtonDetail-BcyZr5hq.js";import{B as i}from"./ButtonTime-COkSxdoi.js";import{T as y,a as x,b as I}from"./TableHeaderCell-Bs1KywQ8.js";import{T as R,a as s}from"./TableCell-BIGv_O-8.js";import{C as l}from"./Card-BxkZABJO.js";import{T as k}from"./TableCellMenu-Dw1e1tA1.js";import"./transition-D0d7qM0-.js";import"./formatRibuan-BoXPYuQw.js";function L({auth:c,dataTable:d,header:p,totalCount:h,rataRata:t,queryParams:m={}}){const u=[{name:"NORM"},{name:"NAMA PASIEN"},{name:"NOMOR"},{name:"TANGGAL",className:"text-center"},{name:"PENJAMIN"},{name:"STATUS"},{name:"MENU",className:"text-center"}],o=(a,r)=>{const n={...m,page:1};r?n[a]=r:delete n[a],A.get(route("pendaftaran.index"),n,{preserveState:!0,preserveScroll:!0})},f=(a,r)=>{const n=r.target.value;o(a,n)},j=(a,r)=>{r.key==="Enter"&&o(a,r.target.value)};return e.jsxs(N,{user:c.user,children:[e.jsx(g,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Pendaftaran ",p," ",h," Pasien"]}),e.jsxs("div",{className:"flex flex-wrap gap-4 justify-between mb-4",children:[e.jsx(l,{title:"RATA-RATA PER HARI",value:t.rata_rata_per_hari}),e.jsx(l,{title:"RATA-RATA PER MINGGU",value:t.rata_rata_per_minggu}),e.jsx(l,{title:"RATA-RATA PER BULAN",value:t.rata_rata_per_bulan}),e.jsx(l,{title:"RATA-RATA PER TAHUN",value:t.rata_rata_per_tahun})]}),e.jsxs(y,{children:[e.jsx(x,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:7,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(T,{className:"flex-1",defaultValue:m.search||"",placeholder:"Cari data berdasarkan NORM, nama pasien atau nomor pendaftaran",onChange:a=>f("search",a),onKeyPress:a=>j("search",a)}),e.jsx(i,{href:route("pendaftaran.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(i,{href:route("pendaftaran.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(i,{href:route("pendaftaran.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(i,{href:route("pendaftaran.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx(x,{children:e.jsx("tr",{children:u.map((a,r)=>e.jsx(I,{className:a.className||"",children:a.name},r))})}),e.jsx("tbody",{children:d.data.length>0?d.data.map((a,r)=>e.jsxs(R,{isEven:r%2===0,children:[e.jsx(s,{children:a.norm}),e.jsx(s,{className:"uppercase",children:a.nama}),e.jsx(s,{children:a.nomor}),e.jsx(s,{className:"text-center",children:a.tanggal}),e.jsx(s,{children:a.penjamin}),e.jsx(s,{children:a.status===0?"Batal":a.status===1?"Aktif":"Selesai"}),e.jsx(k,{children:e.jsx(v,{href:route("pendaftaran.detail",{id:a.nomor})})})]},a.nomor)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(b,{links:d.links})]})})})})})]})}export{L as default};
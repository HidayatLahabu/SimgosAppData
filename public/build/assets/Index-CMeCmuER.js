import{j as e,Y as p,y as h}from"./app-s8l1-nAC.js";import{A as j}from"./AuthenticatedLayout-BFOhAUoj.js";import{T as u}from"./TextInput-Bth7CK2Y.js";import{P as g}from"./Pagination-JLO6BIjT.js";import{f as N}from"./formatDate-Dh0UuduE.js";import{T as f}from"./ButtonDetail-Bg_E9c2e.js";import{T as b,a as c,b as A,c as k,d as r}from"./TableCell-9DR3p-X7.js";import"./transition-C5fQKN4W.js";function E({auth:d,dataTable:t,queryParams:l={}}){const m=[{name:"NORM"},{name:"NAMA PASIEN"},{name:"TANGGAL LAHIR"},{name:"ALAMAT"},{name:"KARTU ASURANSI"},{name:"NIK"},{name:"TANGGAL TERDAFTAR"},{name:"MENU",className:"text-center"}],i=(a,s)=>{const n={...l,page:1};s?n[a]=s:delete n[a],h.get(route("pasien.index"),n,{preserveState:!0,preserveScroll:!0})},o=(a,s)=>{const n=s.target.value;i(a,n)},x=(a,s)=>{s.key==="Enter"&&i(a,s.target.value)};return e.jsxs(j,{user:d.user,children:[e.jsx(p,{title:"Master"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Pasien"}),e.jsxs(b,{children:[e.jsx(c,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsx(u,{className:"w-full",defaultValue:l.search||"",placeholder:"Cari data berdasarkan NORM, nama pasien, kartu asuransi atau NIK",onChange:a=>o("search",a),onKeyPress:a=>x("search",a)})})})}),e.jsx(c,{children:e.jsx("tr",{children:m.map((a,s)=>e.jsx(A,{className:a.className||"",children:a.name},s))})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((a,s)=>e.jsxs(k,{isEven:s%2===0,children:[e.jsx(r,{children:a.norm}),e.jsx(r,{className:"uppercase",children:a.nama}),e.jsx(r,{children:N(a.tanggal)}),e.jsx(r,{children:a.alamat}),e.jsx(r,{children:a.bpjs?a.bpjs:""}),e.jsx(r,{children:a.nik?a.nik:""}),e.jsx(r,{children:a.terdaftar}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(f,{href:route("pasien.detail",{id:a.norm})})})]},a.norm)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(g,{links:t.links})]})})})})})]})}export{E as default};
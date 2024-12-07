import{j as e,Y as c,y as i}from"./app-BZRUO3DV.js";import{A as o}from"./AuthenticatedLayout-BAx1oDWl.js";import{T as p}from"./TextInput-ULnzWxAt.js";import{P as g}from"./Pagination-2EDbv7qa.js";import"./transition-Daa6mMa6.js";function N({auth:l,dataTable:t,queryParams:d={}}){const n=(s,r)=>{const a={...d,page:1};r?a[s]=r:delete a[s],i.get(route("referensi.index"),a,{preserveState:!0,preserveScroll:!0})},x=(s,r)=>{const a=r.target.value;a===""?i.get(route("referensi.index"),{...d,name:"",page:1},{preserveState:!0,preserveScroll:!0}):n(s,a)};return e.jsxs(o,{user:l.user,children:[e.jsx(c,{title:"SatuSehat"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Referensi"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:5,className:"px-3 py-2",children:e.jsx(p,{className:"w-full",defaultValue:d.deskripsi||"",placeholder:"Cari referensi",onChange:s=>x("deskripsi",s)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500 border-b-2 border-gray-500",children:e.jsxs("tr",{className:"text-nowrap",children:[e.jsx("th",{className:"px-3 py-2",children:"TABLE ID"}),e.jsx("th",{className:"px-3 py-2",children:"ID JENIS"}),e.jsx("th",{className:"px-3 py-2",children:"JENIS"}),e.jsx("th",{className:"px-3 py-2",children:"ID"}),e.jsx("th",{className:"px-3 py-2",children:"DESKRIPSI"})]})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((s,r)=>e.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${r%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[e.jsx("td",{className:"px-3 py-3",children:s.tabel_id}),e.jsx("td",{className:"px-3 py-3",children:s.idJenis}),e.jsx("td",{className:"px-3 py-3",children:s.jenis}),e.jsx("td",{className:"px-3 py-3",children:s.id}),e.jsx("td",{className:"px-3 py-3",children:s.deskripsi})]},`${s.tabel_id}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"5",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(g,{links:t.links})]})})})})})]})}export{N as default};
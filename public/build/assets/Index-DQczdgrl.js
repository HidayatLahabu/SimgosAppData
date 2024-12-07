import{j as e,Y as o,y as g}from"./app-BZRUO3DV.js";import{A as p}from"./AuthenticatedLayout-BAx1oDWl.js";import{T as h}from"./TextInput-ULnzWxAt.js";import{P as m}from"./Pagination-2EDbv7qa.js";import{f as i}from"./formatNumber-DqAyQwKe.js";import"./transition-Daa6mMa6.js";function k({auth:n,dataTable:s,queryParams:d={}}){const l=(a,r)=>{const t={...d,page:1};r?t[a]=r:delete t[a],g.get(route("barang.index"),t,{preserveState:!0,preserveScroll:!0})},x=(a,r)=>{const t=r.target.value;l(a,t)},c=(a,r)=>{r.key==="Enter"&&l(a,r.target.value)};return e.jsxs(p,{user:n.user,children:[e.jsx(o,{title:"Inventory"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Barang"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:6,className:"px-3 py-2",children:e.jsx(h,{className:"w-full",defaultValue:d.nama||"",placeholder:"Cari dataTable",onChange:a=>x("nama",a),onKeyPress:a=>c("nama",a)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"ID"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA dataTable"}),e.jsx("th",{className:"px-3 py-2",children:"KATEGORI"}),e.jsx("th",{className:"px-3 py-2 text-center",children:"SATUAN"}),e.jsx("th",{className:"px-3 py-2 text-right",children:"HARGA BELI"}),e.jsx("th",{className:"px-3 py-2 text-right",children:"HARGA JUAL"})]})}),e.jsx("tbody",{children:s.data.length>0?s.data.map((a,r)=>e.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${r%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[e.jsx("td",{className:"px-3 py-3",children:a.id}),e.jsx("td",{className:"px-3 py-3",children:a.nama}),e.jsx("td",{className:"px-3 py-3",children:a.kategori}),e.jsx("td",{className:"px-3 py-3 text-center",children:a.satuan}),e.jsx("td",{className:"px-3 py-3 text-right",children:i(a.beli)}),e.jsx("td",{className:"px-3 py-3 text-right",children:i(a.jual)})]},`${a.id}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(m,{links:s.links})]})})})})})]})}export{k as default};
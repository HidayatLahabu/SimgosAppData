import{j as e,Y as p,y as h}from"./app-s8l1-nAC.js";import{A as j}from"./AuthenticatedLayout-BFOhAUoj.js";import{T as u}from"./TextInput-Bth7CK2Y.js";import{P as g}from"./Pagination-JLO6BIjT.js";import{T as N,a as d,b,c as f,d as n}from"./TableCell-9DR3p-X7.js";import"./transition-C5fQKN4W.js";function I({auth:c,dataTable:l,queryParams:t={}}){const x=[{name:"ID"},{name:"NAMA"},{name:"NIP"},{name:"NIK"},{name:"RUANGAN BERTUGAS"}],i=(a,s)=>{const r={...t,page:1};s?r[a]=s:delete r[a],h.get(route("perawat.index"),r,{preserveState:!0,preserveScroll:!0})},o=(a,s)=>{const r=s.target.value;i(a,r)},m=(a,s)=>{s.key==="Enter"&&i(a,s.target.value)};return e.jsxs(j,{user:c.user,children:[e.jsx(p,{title:"Master"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Perawat"}),e.jsxs(N,{children:[e.jsx(d,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:5,className:"px-3 py-2",children:e.jsx(u,{className:"w-full",defaultValue:t.search||"",placeholder:"Cari data berdasarkan nama perawat",onChange:a=>o("search",a),onKeyPress:a=>m("search",a)})})})}),e.jsx(d,{children:e.jsx("tr",{children:x.map((a,s)=>e.jsx(b,{className:a.className||"",children:a.name},s))})}),e.jsx("tbody",{children:l.data.length>0?l.data.map((a,s)=>e.jsxs(f,{isEven:s%2===0,children:[e.jsx(n,{children:a.id}),e.jsxs(n,{children:[a.depan," ",e.jsx("span",{className:"uppercase",children:a.nama})," ",a.belakang]}),e.jsx(n,{children:a.nip?a.nip:""}),e.jsx(n,{children:a.nik?a.nik:e.jsx("span",{className:"text-red-500",children:"Belum ada NIK"})}),e.jsx(n,{children:a.ruangan})]},a.id)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"5",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(g,{links:l.links})]})})})})})]})}export{I as default};
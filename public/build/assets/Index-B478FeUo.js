import{j as e,Y as x,y as o}from"./app-CPQXeuDs.js";import{A as p}from"./AuthenticatedLayout-C5Uz1_Wp.js";import{T as h}from"./TextInput-D3pF9Y14.js";import{P as m}from"./Pagination-UyfSd9lz.js";import{T as g}from"./ButtonDetail-PpCAmebG.js";import"./transition-D7ffdLYN.js";function k({auth:n,dataTable:a,queryParams:d={}}){const l=(s,r)=>{const t={...d,page:1};r?t[s]=r:delete t[s],o.get(route("consent.index"),t,{preserveState:!0,preserveScroll:!0})},i=(s,r)=>{const t=r.target.value;l(s,t)},c=(s,r)=>{r.key==="Enter"&&l(s,r.target.value)};return e.jsxs(p,{user:n.user,children:[e.jsx(x,{title:"SatuSehat"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Consent"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:6,className:"px-3 py-2",children:e.jsx(h,{className:"w-full",defaultValue:d.patient||"",placeholder:"Cari body send",onChange:s=>i("bodySend",s),onKeyPress:s=>c("bodySend",s)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"ID"}),e.jsx("th",{className:"px-3 py-2",children:"PATIENT"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"REF ID"}),e.jsx("th",{className:"px-3 py-2",children:"SEND DATE"}),e.jsx("th",{className:"px-3 py-2",children:"MENU"})]})}),e.jsx("tbody",{children:a.data.length>0?a.data.map((s,r)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:s.id}),e.jsx("td",{className:"px-3 py-3",children:s.bodySend}),e.jsx("td",{className:"px-3 py-3",children:s.norm}),e.jsx("td",{className:"px-3 py-3",children:s.refId}),e.jsx("td",{className:"px-3 py-3",children:s.sendDate}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(g,{href:route("consent.detail",{id:s.refId})})})]},`${s.refId}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(m,{links:a.links})]})})})})})]})}export{k as default};
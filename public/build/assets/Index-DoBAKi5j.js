import{j as e,Y as g,y as l}from"./app-BAapsLKb.js";import{A as u}from"./AuthenticatedLayout-BQYs31YB.js";import{T as j}from"./TextInput-BnjpEWg6.js";import{P as y}from"./Pagination-D_N7liIy.js";import{T as f}from"./ButtonDetail-ytLhR4sF.js";import{B as a}from"./ButtonTime-BHQxXu8h.js";import"./transition-CK11B9b-.js";function D({auth:c,dataTable:n,header:x,totalCount:o,text:p,queryParams:i={}}){const d=(r,t)=>{const s={...i,page:1};t?s[r]=t:delete s[r],l.get(route("consent.index"),s,{preserveState:!0,preserveScroll:!0})},h=(r,t)=>{const s=t.target.value;s===""?l.get(route("consent.index"),{...i,search:"",page:1},{preserveState:!0,preserveScroll:!0}):d(r,s)},m=(r,t)=>{t.key==="Enter"&&d(r,t.target.value)};return e.jsxs(u,{user:c.user,children:[e.jsx(g,{title:"SatuSehat"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Consent ",x," ",o," ",p]}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(j,{className:"w-full",defaultValue:i.search||"",placeholder:"Cari consent berdasarkan body send, NORM atau ref ID",onChange:r=>h("search",r),onKeyPress:r=>m("search",r)}),e.jsx(a,{href:route("consent.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(a,{href:route("consent.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(a,{href:route("consent.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(a,{href:route("consent.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"ID"}),e.jsx("th",{className:"px-3 py-2",children:"BODY SEND"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"REF ID"}),e.jsx("th",{className:"px-3 py-2",children:"SEND DATE"}),e.jsx("th",{className:"px-3 py-2",children:"MENU"})]})}),e.jsx("tbody",{children:n.data.length>0?n.data.map((r,t)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:r.id}),e.jsx("td",{className:"px-3 py-3",children:r.bodySend}),e.jsx("td",{className:"px-3 py-3",children:r.norm}),e.jsx("td",{className:"px-3 py-3",children:r.refId}),e.jsx("td",{className:"px-3 py-3",children:r.sendDate}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(f,{href:route("consent.detail",{id:r.refId})})})]},`${r.refId}-${t}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(y,{links:n.links})]})})})})})]})}export{D as default};
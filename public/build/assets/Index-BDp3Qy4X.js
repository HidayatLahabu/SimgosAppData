import{j as e,Y as g,y as f}from"./app-BBFb7Tij.js";import{A as b}from"./AuthenticatedLayout-BYatZYdi.js";import{T as N}from"./TextInput-CXLVfbEe.js";import{P as y}from"./Pagination-Do2ugF3n.js";import{T as I}from"./ButtonDetail-NOkdb9Lb.js";import{B as n}from"./ButtonTime-d0U8UmFA.js";import{T,a as c,b as v,c as k,d as t}from"./TableCell-DsP5ZglV.js";import"./transition-Db2fcTbx.js";function H({auth:o,dataTable:i,header:x,totalCount:m,text:h,queryParams:l={}}){const p=[{name:"ID"},{name:"SUBJECT"},{name:"REF ID"},{name:"NOPEN"},{name:"SEND DATE"},{name:"MENU",className:"text-center"}],d=(s,r)=>{const a={...l,page:1};r?a[s]=r:delete a[s],f.get(route("diagnosticReport.index"),a,{preserveState:!0,preserveScroll:!0})},u=(s,r)=>{const a=r.target.value;d(s,a)},j=(s,r)=>{r.key==="Enter"&&d(s,r.target.value)};return e.jsxs(b,{user:o.user,children:[e.jsx(g,{title:"SatuSehat"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Diagnostic Report ",x," ",m," ",h]}),e.jsxs(T,{children:[e.jsx(c,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(N,{className:"w-full",defaultValue:l.search||"",placeholder:"Cari data berdasarkan subject, ref id dan nopen",onChange:s=>u("search",s),onKeyPress:s=>j("search",s)}),e.jsx(n,{href:route("diagnosticReport.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(n,{href:route("diagnosticReport.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(n,{href:route("diagnosticReport.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(n,{href:route("diagnosticReport.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx(c,{children:e.jsx("tr",{children:p.map((s,r)=>e.jsx(v,{className:s.className||"",children:s.name},r))})}),e.jsx("tbody",{children:i.data.length>0?i.data.map((s,r)=>e.jsxs(k,{isEven:r%2===0,children:[e.jsx(t,{children:s.id}),e.jsx(t,{children:s.subject}),e.jsx(t,{children:s.refId}),e.jsx(t,{children:s.nopen}),e.jsx(t,{children:s.sendDate}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(I,{href:route("diagnosticReport.detail",{id:s.refId})})})]},s.refId)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(y,{links:i.links})]})})})})})]})}export{H as default};
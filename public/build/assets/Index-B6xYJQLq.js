import{j as e,Y as p,y as h}from"./app-BuXQ04Wh.js";import{A as y}from"./AuthenticatedLayout-Cby1FNRL.js";import{T as m}from"./TextInput-DJBvdjNv.js";import{P as u}from"./Pagination-CtUBykV4.js";import{T as j}from"./ButtonDetail-kB_BcyhW.js";import{B as t}from"./ButtonTime-BSeWQ3QF.js";import"./transition-DS7517jV.js";function R({auth:n,dataTable:d,header:l,totalCount:c,text:x,queryParams:i={}}){const o=(r,a)=>{const s={...i,page:1};a?s[r]=a:delete s[r],h.get(route("serviceRequest.index"),s,{preserveState:!0,preserveScroll:!0})},g=(r,a)=>{const s=a.target.value;o(r,s)},b=(r,a)=>{a.key==="Enter"&&o(r,a.target.value)};return e.jsxs(y,{user:n.user,children:[e.jsx(p,{title:"SatuSehat"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Service Request ",l," ",c," ",x]}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600",children:[e.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(m,{className:"w-full",defaultValue:i.search||"",placeholder:"Cari data berdasarkan subject, ref id dan nopen",onChange:r=>g("search",r),onKeyPress:r=>b("search",r)}),e.jsx(t,{href:route("serviceRequest.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(t,{href:route("serviceRequest.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(t,{href:route("serviceRequest.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(t,{href:route("serviceRequest.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"ID"}),e.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"SUBJECT"}),e.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"REF ID"}),e.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"NOPEN"}),e.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"SEND DATE"}),e.jsx("th",{className:"px-3 py-2 text-center border border-gray-500 dark:border-gray-600",children:"MENU"})]})}),e.jsx("tbody",{children:d.data.length>0?d.data.map((r,a)=>e.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${a%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[e.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:r.id}),e.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:r.subject}),e.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:r.refId}),e.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:r.nopen}),e.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:r.sendDate}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(j,{href:route("serviceRequest.detail",{id:r.refId})})})]},`${r.nopen}-${a}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(u,{links:d.links})]})})})})})]})}export{R as default};
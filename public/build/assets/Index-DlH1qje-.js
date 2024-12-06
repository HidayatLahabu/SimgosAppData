import{j as r,Y as p,y as i}from"./app-Cv_FtKNC.js";import{A as y}from"./AuthenticatedLayout-J9zICOLn.js";import{T as m}from"./TextInput-YpzqUUu0.js";import{P as u}from"./Pagination-D7oLwUhC.js";import{T as j}from"./ButtonDetail-ljgeon42.js";import{B as s}from"./ButtonTime-CB1tXuYX.js";import"./transition-BTs6vKcd.js";function D({auth:l,dataTable:d,header:c,totalCount:x,text:g,queryParams:n={}}){const o=(e,a)=>{const t={...n,page:1};a?t[e]=a:delete t[e],i.get(route("consent.index"),t,{preserveState:!0,preserveScroll:!0})},b=(e,a)=>{const t=a.target.value;t===""?i.get(route("consent.index"),{...n,search:"",page:1},{preserveState:!0,preserveScroll:!0}):o(e,t)},h=(e,a)=>{a.key==="Enter"&&o(e,a.target.value)};return r.jsxs(y,{user:l.user,children:[r.jsx(p,{title:"SatuSehat"}),r.jsx("div",{className:"py-5",children:r.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:r.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:r.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:r.jsxs("div",{className:"overflow-auto w-full",children:[r.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Consent ",c," ",x," ",g]}),r.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600",children:[r.jsx("thead",{className:"text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500",children:r.jsx("tr",{children:r.jsx("th",{colSpan:8,className:"px-3 py-2",children:r.jsxs("div",{className:"flex items-center space-x-2",children:[r.jsx(m,{className:"w-full",defaultValue:n.search||"",placeholder:"Cari consent berdasarkan body send, NORM atau ref ID",onChange:e=>b("search",e),onKeyPress:e=>h("search",e)}),r.jsx(s,{href:route("consent.filterByTime","hariIni"),text:"Hari Ini"}),r.jsx(s,{href:route("consent.filterByTime","mingguIni"),text:"Minggu Ini"}),r.jsx(s,{href:route("consent.filterByTime","bulanIni"),text:"Bulan Ini"}),r.jsx(s,{href:route("consent.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),r.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500",children:r.jsxs("tr",{children:[r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"ID"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"BODY SEND"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"NORM"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"REF ID"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 dark:border-gray-600",children:"SEND DATE"}),r.jsx("th",{className:"px-3 py-2 border text-center border-gray-500 dark:border-gray-600",children:"MENU"})]})}),r.jsx("tbody",{children:d.data.length>0?d.data.map((e,a)=>r.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${a%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.id}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.bodySend}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.norm}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.refId}),r.jsx("td",{className:"px-3 py-3 border border-gray-500 dark:border-gray-600",children:e.sendDate}),r.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:r.jsx(j,{href:route("consent.detail",{id:e.refId})})})]},`${e.refId}-${a}`)):r.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:r.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),r.jsx(u,{links:d.links})]})})})})})]})}export{D as default};
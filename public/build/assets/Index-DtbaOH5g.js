import{j as e,S as p,A as h}from"./app-Bya0VxHD.js";import{A as j}from"./AuthenticatedLayout-DzUHcLKj.js";import{T as u}from"./TextInput-BzNbzsG0.js";import{P as g}from"./Pagination-DHkbUvMU.js";import{T as b,a as d,b as f}from"./TableHeaderCell-Cq-C6n0m.js";import{T as N,a as n}from"./TableCell-DQW6Vsbw.js";import"./transition-D4TNTeDW.js";function E({auth:c,dataTable:t,queryParams:l={}}){const o=[{name:"ID"},{name:"SUBJECT"},{name:"REF ID"},{name:"NOPEN"},{name:"SEND DATE"}],i=(s,a)=>{const r={...l,page:1};a?r[s]=a:delete r[s],h.get(route("medicationDispanse.index"),r,{preserveState:!0,preserveScroll:!0})},x=(s,a)=>{const r=a.target.value;i(s,r)},m=(s,a)=>{a.key==="Enter"&&i(s,a.target.value)};return e.jsxs(j,{user:c.user,children:[e.jsx(p,{title:"SatuSehat"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Medication Dispanse"}),e.jsxs(b,{children:[e.jsx(d,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:5,className:"px-3 py-2",children:e.jsx(u,{className:"w-full",defaultValue:l.subject||"",placeholder:"Cari medication dispanse",onChange:s=>x("subject",s),onKeyPress:s=>m("subject",s)})})})}),e.jsx(d,{children:e.jsx("tr",{children:o.map((s,a)=>e.jsx(f,{className:s.className||"",children:s.name},a))})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((s,a)=>e.jsxs(N,{isEven:a%2===0,children:[e.jsx(n,{children:s.id}),e.jsx(n,{children:s.subject}),e.jsx(n,{children:s.refId}),e.jsx(n,{children:s.nopen}),e.jsx(n,{children:s.sendDate})]},s.nopen)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(g,{links:t.links})]})})})})})]})}export{E as default};
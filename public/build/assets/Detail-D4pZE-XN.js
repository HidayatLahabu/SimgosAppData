import{j as e,S as m}from"./app-Bya0VxHD.js";import{A as u}from"./AuthenticatedLayout-DzUHcLKj.js";import{B as h}from"./ButtonBack-Bkv0ASVo.js";import{T as N,a as j,b as f}from"./TableHeaderCell-Cq-C6n0m.js";import{T as p,a as n}from"./TableCell-DQW6Vsbw.js";import"./transition-D4TNTeDW.js";function U({auth:c,detail:i}){const x=[{name:"NO"},{name:"COLUMN NAME"},{name:"VALUE"}],r=Object.keys(i).map(a=>({uraian:a,value:i[a]})).filter(a=>a.value!==null&&a.value!==void 0&&a.value!==""&&(a.value!==0||a.uraian==="STATUS_KUNJUNGAN"||a.uraian==="STATUS_AKTIFITAS_KUNJUNGAN")),t=Math.ceil(r.length/2),d=[];for(let a=0;a<r.length;a+=t)d.push(r.slice(a,a+t));return e.jsxs(u,{user:c.user,children:[e.jsx(m,{title:"BPJS"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("div",{className:"relative flex items-center justify-between pb-2",children:[e.jsx(h,{href:route("monitoringRekon.index")}),e.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl",children:"DATA DETAIL MONITORING RENCANA KONTROL"})]}),e.jsx("div",{className:"flex flex-wrap gap-2",children:d.map((a,o)=>e.jsx("div",{className:"flex-1 shadow-md rounded-lg",children:e.jsxs(N,{children:[e.jsx(j,{children:e.jsx("tr",{children:x.map((l,s)=>e.jsx(f,{className:`${s===0?"w-[5%]":s===1?"w-[25%]":"w-[auto]"} 
                                                            ${l.className||""}`,children:l.name},s))})}),e.jsx("tbody",{children:a.map((l,s)=>e.jsxs(p,{children:[e.jsx(n,{children:s+1+o*t}),e.jsx(n,{children:l.uraian}),e.jsx(n,{className:"text-wrap",children:l.value})]},s))})]})},o))})]})})})})})]})}export{U as default};
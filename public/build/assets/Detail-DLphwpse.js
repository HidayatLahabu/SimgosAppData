import{j as a,S as m}from"./app-C9Opettx.js";import{A as h}from"./AuthenticatedLayout-sTrIvXct.js";import j from"./DetailHasil-BkcYfpUH.js";import{B as p}from"./ButtonBack-CHMGBbCh.js";import{T as f,a as N,b as v}from"./TableHeaderCell-DgJXbq6Y.js";import{T as A,a as i}from"./TableCell-DLN35TKF.js";import"./transition-DN0z1QmZ.js";function B({auth:d,detail:t,detailHasil:u}){const x=[{name:"NO"},{name:"COLUMN NAME"},{name:"VALUE"}],r=Object.keys(t).map(e=>({uraian:e,value:t[e]})).filter(e=>e.value!==null&&e.value!==void 0&&e.value!==""&&(e.value!==0||e.uraian==="STATUS_KUNJUNGAN"||e.uraian==="STATUS_AKTIFITAS_KUNJUNGAN")),n=Math.ceil(r.length/2),o=[];for(let e=0;e<r.length;e+=n)o.push(r.slice(e,e+n));return a.jsxs(h,{user:d.user,children:[a.jsx(m,{title:"BPJS"}),a.jsx("div",{className:"py-5",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:a.jsxs("div",{className:"overflow-auto w-full",children:[a.jsxs("div",{className:"relative flex items-center justify-between pb-2",children:[a.jsx(p,{href:route("layananRad.index")}),a.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl",children:"DATA DETAIL LAYANAN RADIOLOGI"})]}),a.jsx("div",{className:"flex flex-wrap gap-2",children:o.map((e,c)=>a.jsx("div",{className:"flex-1 shadow-md rounded-lg",children:a.jsxs(f,{children:[a.jsx(N,{children:a.jsx("tr",{children:x.map((s,l)=>a.jsx(v,{className:`${l===0?"w-[5%]":l===1?"w-[35%]":"w-[auto]"} 
                                                            ${s.className||""}`,children:s.name},l))})}),a.jsx("tbody",{children:e.map((s,l)=>a.jsxs(A,{children:[a.jsx(i,{children:l+1+c*n}),a.jsx(i,{children:s.uraian}),a.jsx(i,{className:"text-wrap",children:s.uraian==="STATUS"?s.value===1?"Belum Final":s.value===2?"Sudah Final":s.value:s.value})]},l))})]})},c))})]})})})})}),a.jsx(j,{detailHasil:u})]})}export{B as default};
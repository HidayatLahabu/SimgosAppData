import{j as a}from"./app-CRYISuyJ.js";import{P as d}from"./Pagination-d0dNlDcG.js";import{T as i,a as t,b as m}from"./TableHeaderCell-Cnpvvd-Q.js";import{T as c,a as s}from"./TableCell-BkJfbhCb.js";import{f as o}from"./formatRibuan-B2UQKMTQ.js";function b({tahunan:l}){const n=[{name:"TAHUN",className:"w-[9%]"},{name:"SUB UNIT"},{name:"KUNJUNGAN",className:"text-right w-[10%]"},{name:"LAST UPDATED",className:"w-[25%]"}];return a.jsx("div",{className:"py-5",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:pl-2 sm:pr-5 lg:pl-2 lg:pr-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:a.jsxs("div",{className:"overflow-auto w-full",children:[a.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Kunjungan Rawat Jalan Tahunan"}),a.jsxs(i,{children:[a.jsx(t,{children:a.jsx("tr",{children:n.map((e,r)=>a.jsx(m,{className:e.className||"",children:e.name},r))})}),a.jsx("tbody",{children:l.data.length>0?l.data.map((e,r)=>a.jsxs(c,{isEven:r%2===0,children:[a.jsx(s,{children:e.tahun}),a.jsx(s,{children:e.subUnit}),a.jsx(s,{className:"text-right",children:o(e.jumlah)}),a.jsx(s,{children:e.lastUpdated})]},e.lastUpdated)):a.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:a.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),a.jsx(d,{links:l.links})]})})})})})}export{b as default};
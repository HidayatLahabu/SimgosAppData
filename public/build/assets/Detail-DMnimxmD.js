import{j as a,S as i}from"./app-CiDKe7ft.js";import{A as x}from"./AuthenticatedLayout-v1EIKjjC.js";import n from"./DetailHasil-BA7EzIiG.js";import{B as c}from"./ButtonBack-BndT2FPC.js";import"./transition-BmadNWPB.js";function j({auth:t,detail:s,detailHasil:l}){const d=Object.keys(s).map(e=>({uraian:e,value:s[e]}));return a.jsxs(x,{user:t.user,children:[a.jsx(i,{title:"BPJS"}),a.jsx("div",{className:"py-5",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:a.jsxs("div",{className:"overflow-auto w-full",children:[a.jsxs("div",{className:"relative flex items-center justify-between pb-2",children:[a.jsx(c,{href:route("layananRad.index")}),a.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl",children:"DATA DETAIL LAYANAN RADIOLOGI"})]}),a.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[a.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:a.jsxs("tr",{children:[a.jsx("th",{className:"px-3 py-2",children:"NO"}),a.jsx("th",{className:"px-3 py-2",children:"COLUMN"}),a.jsx("th",{className:"px-3 py-2",children:"VALUE"})]})}),a.jsx("tbody",{children:d.map((e,r)=>a.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[a.jsx("td",{className:"px-3 py-3 w-16",children:r+1}),a.jsx("td",{className:"px-3 py-3 w-56",children:e.uraian}),a.jsx("td",{className:"px-3 py-3 break-words",children:e.uraian==="STATUS"?e.value===1?"Belum Final":e.value===2?"Sudah Final":e.value:e.value})]},r))})]})]})})})})}),a.jsx(n,{detailHasil:l})]})}export{j as default};
import{j as r}from"./app-DZ56VpWn.js";import{f as d}from"./formatDate-Dh0UuduE.js";import{f as t}from"./formatRibuan-B2UQKMTQ.js";function b({harian:a}){return r.jsxs("div",{className:"overflow-auto mt-2",children:[r.jsx("h1",{className:"text-center font-bold text-2xl",children:"HARIAN"}),r.jsxs("table",{className:"w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500",children:[r.jsx("thead",{className:"text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500",children:r.jsxs("tr",{children:[r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid w-[15%]",children:"TANGGAL"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"SUB UNIT"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid text-right w-[15%]",children:"JUMLAH"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid w-[25%]",children:"LAST UPDATE"})]})}),r.jsx("tbody",{children:a.map((e,s)=>r.jsxs("tr",{className:"border-b bg-white dark:border-gray-500",children:[r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:d(e.tanggal)}),r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.subUnit}),r.jsx("td",{className:"px-3 py-2 border text-nowrap text-right border-gray-500 border-solid",children:t(e.jumlah)}),r.jsx("td",{className:"px-3 py-2 border border-gray-500 border-solid",children:e.lastUpdated})]},e.tanggal))})]})]})}export{b as default};
import{r as c,j as a}from"./app-yhfm9Cl-.js";import{P as x}from"./Pagination-BTrs9T48.js";import{T as m,a as g,b as j}from"./TableHeaderCell-DvGVFM9U.js";import{T as h,a as e}from"./TableCell-CD-1nHQ7.js";function b({mingguan:s}){const r=[{name:"TAHUN",className:"w-[9%]"},{name:"MINGGU KE",className:"w-[10%]"},{name:"JENIS KUNJUNGAN",className:"w-[14%]"},{name:"INSTALASI"},{name:"UNIT",className:"w-[10%]"},{name:"SUB UNIT"},{name:"KUNJUNGAN",className:"text-right w-[10%]"},{name:"LAST UPDATED",className:"w-[12%]"}],[i,t]=c.useState(s.linksKunjunganMingguan),d=n=>{t(n)};return a.jsx("div",{className:"py-5",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:a.jsxs("div",{className:"overflow-auto w-full",children:[a.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Kunjungan Rawat Jalan Mingguan"}),a.jsxs(m,{children:[a.jsx(g,{children:a.jsx("tr",{children:r.map((n,l)=>a.jsx(j,{className:n.className||"",children:n.name},l))})}),a.jsx("tbody",{children:Array.isArray(s==null?void 0:s.dataKunjunganMingguan)&&s.dataKunjunganMingguan.length>0?s.dataKunjunganMingguan.map((n,l)=>a.jsxs(h,{isEven:l%2===0,children:[a.jsx(e,{children:n.tahun}),a.jsx(e,{children:n.minggu}),a.jsx(e,{children:n.jenisKunjungan}),a.jsx(e,{children:n.instalasi}),a.jsx(e,{children:n.unit}),a.jsx(e,{children:n.subUnit}),a.jsx(e,{className:"text-right",children:n.jumlah}),a.jsx(e,{children:n.lastUpdated})]},n.lastUpdated)):a.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:a.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),a.jsx(x,{links:i,onPageChange:d})]})})})})})}export{b as default};
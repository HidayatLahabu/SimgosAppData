import{r as x,j as a}from"./app-yhfm9Cl-.js";import{P as c}from"./Pagination-BTrs9T48.js";import{T as h,a as o,b as r}from"./TableHeaderCell-DvGVFM9U.js";import{T as g,a as t}from"./TableCell-CD-1nHQ7.js";function N({kunjunganMingguan:s}){const[l,n]=x.useState(s.linksKunjunganMingguan),i=e=>{n(e)};return a.jsx("div",{className:"max-w-full mx-auto w-full",children:a.jsx("div",{className:"bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full",children:a.jsxs("div",{className:"p-5 text-gray-900 dark:text-gray-100 w-full",children:[a.jsx("h1",{className:"uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2",children:"Kunjungan Mingguan"}),a.jsxs(h,{children:[a.jsx(o,{children:a.jsxs("tr",{children:[a.jsx(r,{className:"text-nowrap w-[5%]",children:"TAHUN"}),a.jsx(r,{className:"text-nowrap w-[12%]",children:"MINGGU KE"}),a.jsx(r,{className:"text-right",children:"RAWAT JALAN"}),a.jsx(r,{className:"text-right",children:"RAWAT DARURAT"}),a.jsx(r,{className:"text-right",children:"RAWAT INAP"})]})}),a.jsx("tbody",{children:Array.isArray(s==null?void 0:s.dataKunjunganMingguan)&&s.dataKunjunganMingguan.length>0?s.dataKunjunganMingguan.map((e,d)=>a.jsxs(g,{isEven:d%2===0,children:[a.jsx(t,{children:e.tahun}),a.jsx(t,{children:e.minggu}),a.jsx(t,{className:"text-right",children:e.total_rj}),a.jsx(t,{className:"text-right",children:e.total_rd}),a.jsx(t,{className:"text-right",children:e.total_ri})]},e.minggu)):a.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:a.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),a.jsx(c,{links:l,onPageChange:i})]})})})}export{N as default};
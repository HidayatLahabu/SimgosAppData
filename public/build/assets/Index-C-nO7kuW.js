import{j as e,Y as p,y as h}from"./app-CPQXeuDs.js";import{A as m}from"./AuthenticatedLayout-C5Uz1_Wp.js";import{T as g}from"./TextInput-D3pF9Y14.js";import{P as j}from"./Pagination-UyfSd9lz.js";import{T as y}from"./ButtonDetail-PpCAmebG.js";import"./transition-D7ffdLYN.js";function w({auth:c,dataTable:t,queryParams:l={}}){const n=(s,r)=>{const a={...l,page:1};r?a[s]=r:delete a[s],h.get(route("pesertaBpjs.index"),a,{preserveState:!0,preserveScroll:!0})},x=(s,r)=>{const a=r.target.value;n(s,a)},o=(s,r)=>{r.key==="Enter"&&n(s,r.target.value)},d=s=>{const r=s.split("");for(let a=r.length-1;a>0;a--){const i=Math.floor(Math.random()*(a+1));[r[a],r[i]]=[r[i],r[a]]}return r.join("")};return e.jsxs(m,{user:c.user,children:[e.jsx(p,{title:"BPJS"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Peserta BPJS"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:7,className:"px-3 py-2",children:e.jsx(g,{className:"w-full",defaultValue:l.nama||"",placeholder:"Cari peserta",onChange:s=>x("nama",s),onKeyPress:s=>o("nama",s)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NO KARTU"}),e.jsx("th",{className:"px-3 py-2",children:"NIK"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PESERTA"}),e.jsx("th",{className:"px-3 py-2",children:"JENIS PESERTA"}),e.jsx("th",{className:"px-3 py-2",children:"KELAS"}),e.jsx("th",{className:"px-3 py-2 text-center",children:"MENU"})]})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((s,r)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:d(s.noKartu)}),e.jsx("td",{className:"px-3 py-3",children:d(s.nik)}),e.jsx("td",{className:"px-3 py-3",children:s.norm}),e.jsx("td",{className:"px-3 py-3 uppercase",children:s.nama}),e.jsx("td",{className:"px-3 py-3",children:s.nmJenisPeserta}),e.jsx("td",{className:"px-3 py-3",children:s.nmKelas}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(y,{href:route("pesertaBpjs.detail",{id:s.noKartu})})})]},`${s.noKartu}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(j,{links:t.links})]})})})})})]})}export{w as default};
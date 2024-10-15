import{j as e,Y as x,y as p}from"./app-CPQXeuDs.js";import{A as o}from"./AuthenticatedLayout-C5Uz1_Wp.js";import{T as h}from"./TextInput-D3pF9Y14.js";import{P as g}from"./Pagination-UyfSd9lz.js";import"./transition-D7ffdLYN.js";function k({auth:l,dataTable:t,queryParams:d={}}){const n=(s,a)=>{const r={...d,page:1};a?r[s]=a:delete r[s],p.get(route("layananPulang.index"),r,{preserveState:!0,preserveScroll:!0})},i=(s,a)=>{const r=a.target.value;n(s,r)},c=(s,a)=>{a.key==="Enter"&&n(s,a.target.value)};return e.jsxs(o,{user:l.user,children:[e.jsx(x,{title:"Layanan"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Pasien Pulang"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:7,className:"px-3 py-2",children:e.jsx(h,{className:"w-full",defaultValue:d.nama||"",placeholder:"Cari pasien",onChange:s=>i("nama",s),onKeyPress:s=>c("nama",s)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NOMOR"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PASIEN"}),e.jsx("th",{className:"px-3 py-2",children:"STATUS"}),e.jsx("th",{className:"px-3 py-2",children:"KEADAAN"}),e.jsx("th",{className:"px-3 py-2",children:"DPJP"})]})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((s,a)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:s.id}),e.jsx("td",{className:"px-3 py-3",children:s.tanggal}),e.jsx("td",{className:"px-3 py-3",children:s.norm}),e.jsx("td",{className:"px-3 py-3 uppercase",children:s.nama}),e.jsx("td",{className:"px-3 py-3",children:s.status}),e.jsx("td",{className:"px-3 py-3",children:s.keadaan}),e.jsxs("td",{className:"px-3 py-3",children:[s.gelarDepan," ",e.jsx("span",{className:"uppercase",children:s.dokter}),"  ",s.gelarBelakang]})]},`${s.id}-${a}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(g,{links:t.links})]})})})})})]})}export{k as default};
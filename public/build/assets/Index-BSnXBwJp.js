import{j as e,Y as i,y as o}from"./app-DnDAVf2a.js";import{A as p}from"./AuthenticatedLayout-m4uA_d28.js";import{T as h}from"./TextInput-M8WMPSHD.js";import{P as m}from"./Pagination-DibsxUA3.js";import"./transition-Df0PGNpW.js";function u({auth:n,antrian:t,queryParams:d={}}){const l=(a,s)=>{const r={...d,page:1};s?r[a]=s:delete r[a],o.get(route("antrian.index"),r,{preserveState:!0,preserveScroll:!0})},x=(a,s)=>{const r=s.target.value;l(a,r)},c=(a,s)=>{s.key==="Enter"&&l(a,s.target.value)};return e.jsxs(p,{user:n.user,children:[e.jsx(i,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Antrian Ruangan"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:6,className:"px-3 py-2",children:e.jsx(h,{className:"w-full",defaultValue:d.nama||"",placeholder:"Cari pasien",onChange:a=>x("nama",a),onKeyPress:a=>c("nama",a)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NOMOR"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PASIEN"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL"}),e.jsx("th",{className:"px-3 py-2",children:"RUANGAN"}),e.jsx("th",{className:"px-3 py-2",children:"NO URUT"})]})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((a,s)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:a.nomor}),e.jsx("td",{className:"px-3 py-3",children:a.norm}),e.jsx("td",{className:"px-3 py-3",children:a.nama}),e.jsx("td",{className:"px-3 py-3",children:a.tanggal}),e.jsx("td",{className:"px-3 py-3",children:a.ruangan}),e.jsx("td",{className:"px-3 py-3",children:a.urut})]},`${a.nomor}-${s}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"6",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(m,{links:t.links})]})})})})})]})}export{u as default};
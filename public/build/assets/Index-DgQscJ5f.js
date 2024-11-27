import{j as e,Y as x,y as o}from"./app-DKzvDt8Y.js";import{A as p}from"./AuthenticatedLayout-DSCq9Vjl.js";import{T as m}from"./TextInput-BxpVYypg.js";import{P as h}from"./Pagination-DT3Cta8X.js";import{f as g}from"./formatDate-Dh0UuduE.js";import"./transition-xEOBz0hF.js";function k({auth:l,dataTable:t,queryParams:n={}}){const d=(a,r)=>{const s={...n,page:1};r?s[a]=r:delete s[a],o.get(route("antrian.index"),s,{preserveState:!0,preserveScroll:!0})},i=(a,r)=>{const s=r.target.value;d(a,s)},c=(a,r)=>{r.key==="Enter"&&d(a,r.target.value)};return e.jsxs(p,{user:l.user,children:[e.jsx(x,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Antrian Ruangan"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:7,className:"px-3 py-2",children:e.jsx(m,{className:"w-full",defaultValue:n.search||"",placeholder:"Cari data berdasarkan nomor antrian, NORM, atau nama pasien",onChange:a=>i("search",a),onKeyPress:a=>c("search",a)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NOMOR"}),e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PASIEN"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL"}),e.jsx("th",{className:"px-3 py-2",children:"RUANGAN"}),e.jsx("th",{className:"px-3 py-2",children:"NO URUT"}),e.jsx("th",{className:"px-3 py-2",children:"STATUS"})]})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((a,r)=>e.jsxs("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:[e.jsx("td",{className:"px-3 py-3",children:a.nomor}),e.jsx("td",{className:"px-3 py-3",children:a.norm}),e.jsx("td",{className:"px-3 py-3 uppercase",children:a.nama}),e.jsx("td",{className:"px-3 py-3",children:g(a.tanggal)}),e.jsx("td",{className:"px-3 py-3",children:a.ruangan}),e.jsx("td",{className:"px-3 py-3",children:a.urut}),e.jsx("td",{className:"px-3 py-3",children:a.status===0?"Batal":a.status===1?"Belum Diterima":"Diterima"})]},`${a.nomor}-${r}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"7",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(h,{links:t.links})]})})})})})]})}export{k as default};
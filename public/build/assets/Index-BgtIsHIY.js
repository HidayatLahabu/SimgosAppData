import{j as e,Y as x,y as p}from"./app-BZRUO3DV.js";import{A as o}from"./AuthenticatedLayout-BAx1oDWl.js";import{T as m}from"./TextInput-ULnzWxAt.js";import{P as h}from"./Pagination-2EDbv7qa.js";import{f as g}from"./formatDate-Dh0UuduE.js";import{T as j}from"./ButtonDetail-CRNKnZga.js";import"./transition-Daa6mMa6.js";function A({auth:d,dataTable:t,queryParams:l={}}){const n=(s,a)=>{const r={...l,page:1};a?r[s]=a:delete r[s],p.get(route("pasien.index"),r,{preserveState:!0,preserveScroll:!0})},i=(s,a)=>{const r=a.target.value;n(s,r)},c=(s,a)=>{a.key==="Enter"&&n(s,a.target.value)};return e.jsxs(o,{user:d.user,children:[e.jsx(x,{title:"Master"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"Data Pasien"}),e.jsxs("table",{className:"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900",children:[e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500",children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsx(m,{className:"w-full",defaultValue:l.nama||"",placeholder:"Cari pasien",onChange:s=>i("nama",s),onKeyPress:s=>c("nama",s)})})})}),e.jsx("thead",{className:"text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500 border-b-2 border-gray-500",children:e.jsxs("tr",{children:[e.jsx("th",{className:"px-3 py-2",children:"NORM"}),e.jsx("th",{className:"px-3 py-2",children:"NAMA PASIEN"}),e.jsx("th",{className:"px-3 py-2",children:"TANGGAL LAHIR"}),e.jsx("th",{className:"px-3 py-2",children:"ALAMAT"}),e.jsx("th",{className:"px-3 py-2",children:"BPJS"}),e.jsx("th",{className:"px-3 py-2",children:"NIK"}),e.jsx("th",{className:"px-3 py-2",children:"TERDAFTAR"}),e.jsx("th",{className:"px-3 py-2 text-center",children:"MENU"})]})}),e.jsx("tbody",{children:t.data.length>0?t.data.map((s,a)=>e.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${a%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[e.jsx("td",{className:"px-3 py-3",children:s.norm}),e.jsx("td",{className:"px-3 py-3 uppercase",children:s.nama}),e.jsx("td",{className:"px-3 py-3",children:g(s.tanggal)}),e.jsx("td",{className:"px-3 py-3 uppercase",children:s.alamat}),e.jsx("td",{className:"px-3 py-3",children:s.bpjs?t.bpjs:""}),e.jsx("td",{className:"px-3 py-3",children:s.nik?s.nik:""}),e.jsx("td",{className:"px-3 py-3",children:s.terdaftar}),e.jsx("td",{className:"px-1 py-1 text-center flex items-center justify-center space-x-1",children:e.jsx(j,{href:route("pasien.detail",{id:s.norm})})})]},`${s.nomor}-${a}`)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(h,{links:t.links})]})})})})})]})}export{A as default};
import{j as e,A as h}from"./app-1kLFVW50.js";import{T as p}from"./TextInput-CQsOQg0u.js";import{P as j}from"./Pagination-ikc0tIOM.js";import{T as u,a as i,b as A}from"./TableHeaderCell-CbYBJ8DU.js";import{T as g,a as r}from"./TableCell-C3A2Pjd1.js";function G({dataTable:l,queryParams:t={}}){const d=[{name:"NORM",className:"w-[7%]"},{name:"NAMA PASIEN"},{name:"PENDAFTARAN",className:"w-[9%]"},{name:"RUANGAN"},{name:"DPJP"},{name:"TANGGAL REGISTRASI",className:"w-[12%]"},{name:"TANGGAL DITERIMA",className:"w-[12%"},{name:"WAKTU TUNGGU",className:"w-[9%"}],c=(a,s)=>{const n={...t,page:1};s?n[a]=s:delete n[a],h.get(route("laporanWaktuTungguRajal.index"),n,{preserveState:!0,preserveScroll:!0})},o=(a,s)=>{const n=s.target.value;c(a,n)},m=(a,s)=>{s.key==="Enter"&&c(a,s.target.value)},x=a=>{const[s,n,N]=a.split(":").map(Number);return s*60+n+N/60};return e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"WAKTU TUNGGU RAWAT JALAN"}),e.jsxs(u,{children:[e.jsx(i,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsx("div",{className:"flex items-center space-x-2",children:e.jsx(p,{className:"flex-1",defaultValue:t.search||"",placeholder:"Cari data berdasarkan NORM, nama pasien, nomor pendaftaran, ruangan atau DPJP",onChange:a=>o("search",a),onKeyPress:a=>m("search",a)})})})})}),e.jsx(i,{children:e.jsx("tr",{children:d.map((a,s)=>e.jsx(A,{className:a.className||"",children:a.name},s))})}),e.jsx("tbody",{children:l.data.length>0?l.data.map((a,s)=>e.jsxs(g,{className:`${x(a.SELISIH)>60?"text-red-400":""}`,children:[e.jsx(r,{children:a.NORM}),e.jsx(r,{children:a.NAMALENGKAP}),e.jsx(r,{children:a.NOPEN}),e.jsx(r,{children:a.UNITPELAYANAN}),e.jsx(r,{children:a.DOKTER_REG}),e.jsx(r,{children:a.TGLREG}),e.jsx(r,{children:a.TGLTERIMA}),e.jsx(r,{children:a.SELISIH})]},a.NOMOR)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(j,{links:l.links})]})})})})})}export{G as default};
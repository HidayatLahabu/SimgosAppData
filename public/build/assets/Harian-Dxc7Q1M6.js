import{j as e,A as p}from"./app-uZIXFHN0.js";import{T as j}from"./TextInput-fT0yAdVj.js";import{P as g}from"./Pagination-CavMzPQM.js";import{T as A,a as i,b as T}from"./TableHeaderCell-CXFOY8r5.js";import{T as u,a as r}from"./TableCell-DTacWHEA.js";import{f as d}from"./formatDate-Dh0UuduE.js";function v({dataTable:l,tgl_awal:o,tgl_akhir:m,queryParams:t={}}){const x=[{name:"NORM",className:"w-[7%]"},{name:"NAMA PASIEN"},{name:"PENDAFTARAN",className:"w-[9%]"},{name:"RUANGAN"},{name:"DPJP"},{name:"TANGGAL REGISTRASI",className:"w-[12%]"},{name:"TANGGAL DITERIMA",className:"w-[12%"},{name:"WAKTU TUNGGU",className:"w-[9%"}],c=(a,s)=>{const n={...t,page:1};s?n[a]=s:delete n[a],p.get(route("laporanWaktuTungguRajal.index"),n,{preserveState:!0,preserveScroll:!0})},h=(a,s)=>{const n=s.target.value;c(a,n)},N=(a,s)=>{s.key==="Enter"&&c(a,s.target.value)};return e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsx("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:"WAKTU TUNGGU RAWAT JALAN"}),e.jsxs("p",{className:"text-center pb-4",children:[e.jsx("strong",{children:"Periode Tanggal : "}),d(o)," - ",d(m)]}),e.jsxs(A,{children:[e.jsx(i,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsx("div",{className:"flex items-center space-x-2",children:e.jsx(j,{className:"flex-1",defaultValue:t.search||"",placeholder:"Cari data berdasarkan NORM, nama pasien, nomor pendaftaran, ruangan atau DPJP",onChange:a=>h("search",a),onKeyPress:a=>N("search",a)})})})})}),e.jsx(i,{children:e.jsx("tr",{children:x.map((a,s)=>e.jsx(T,{className:a.className||"",children:a.name},s))})}),e.jsx("tbody",{children:l.data.length>0?l.data.map((a,s)=>e.jsxs(u,{isEven:s%2===0,children:[e.jsx(r,{children:a.NORM}),e.jsx(r,{children:a.NAMALENGKAP}),e.jsx(r,{children:a.NOPEN}),e.jsx(r,{children:a.UNITPELAYANAN}),e.jsx(r,{children:a.DOKTER_REG}),e.jsx(r,{children:a.TGLREG}),e.jsx(r,{children:a.TGLTERIMA}),e.jsx(r,{children:a.SELISIH})]},a.NOMOR)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(g,{links:l.links})]})})})})})}export{v as default};
import{j as e,S as g,A as N}from"./app-CiDKe7ft.js";import{A as T}from"./AuthenticatedLayout-v1EIKjjC.js";import{T as b}from"./TextInput-B3YDPI01.js";import{P as k}from"./Pagination-iC0Pqrr5.js";import{T as v}from"./ButtonDetail-Co_RRfqA.js";import{B as l}from"./ButtonTime-1tY-4VM2.js";import{T as R,a as x,b as y}from"./TableHeaderCell-Dit1MAtR.js";import{T as I,a as r}from"./TableCell-BgSBgdE5.js";import{C as i}from"./Card-BAlGD2f2.js";import w from"./Cetak-Bym79aNb.js";import{T as _}from"./TableCellMenu-BgFsg06U.js";import"./transition-BmadNWPB.js";import"./formatRibuan-BoXPYuQw.js";import"./SelectTwoInput-VQP--I-N.js";import"./InputLabel-DKji9fbd.js";function V({auth:c,dataTable:o,header:u,totalCount:h,rataRata:t,ruangan:p,queryParams:m={}}){const j=[{name:"NORM"},{name:"NAMA PASIEN"},{name:"NOMOR"},{name:"TANGGAL"},{name:"RUANGAN ASAL"},{name:"RUANGAN TUJUAN"},{name:"STATUS"},{name:"MENU",className:"text-center"}],d=(a,s)=>{const n={...m,page:1};s?n[a]=s:delete n[a],N.get(route("konsul.index"),n,{preserveState:!0,preserveScroll:!0})},f=(a,s)=>{const n=s.target.value;d(a,n)},A=(a,s)=>{s.key==="Enter"&&d(a,s.target.value)};return e.jsxs(T,{user:c.user,children:[e.jsx(g,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-bold text-2xl pb-2",children:["Data Konsul ",u," ",h," Pasien"]}),e.jsxs("div",{className:"flex flex-wrap gap-4 justify-between mb-4",children:[e.jsx(i,{title:"RATA-RATA PER HARI",value:t.rata_rata_per_hari}),e.jsx(i,{title:"RATA-RATA PER MINGGU",value:t.rata_rata_per_minggu}),e.jsx(i,{title:"RATA-RATA PER BULAN",value:t.rata_rata_per_bulan}),e.jsx(i,{title:"RATA-RATA PER TAHUN",value:t.rata_rata_per_tahun})]}),e.jsxs(R,{children:[e.jsx(x,{children:e.jsx("tr",{children:e.jsx("th",{colSpan:8,className:"px-3 py-2",children:e.jsxs("div",{className:"flex items-center space-x-2",children:[e.jsx(b,{className:"w-full",defaultValue:m.search||"",placeholder:"Cari data berdasarkan NORM, nama pasien atau nomor konsul",onChange:a=>f("search",a),onKeyPress:a=>A("search",a)}),e.jsx(l,{href:route("konsul.filterByTime","hariIni"),text:"Hari Ini"}),e.jsx(l,{href:route("konsul.filterByTime","mingguIni"),text:"Minggu Ini"}),e.jsx(l,{href:route("konsul.filterByTime","bulanIni"),text:"Bulan Ini"}),e.jsx(l,{href:route("konsul.filterByTime","tahunIni"),text:"Tahun Ini"})]})})})}),e.jsx(x,{children:e.jsx("tr",{children:j.map((a,s)=>e.jsx(y,{className:a.className||"",children:a.name},s))})}),e.jsx("tbody",{children:o.data.length>0?o.data.map((a,s)=>e.jsxs(I,{isEven:s%2===0,children:[e.jsx(r,{children:a.norm}),e.jsx(r,{className:"uppercase",children:a.nama}),e.jsx(r,{children:a.nomor}),e.jsx(r,{children:a.tanggal}),e.jsx(r,{children:a.asal}),e.jsx(r,{children:a.tujuan}),e.jsx(r,{children:a.status===0?"Batal":a.status===1?"Belum Diterima":"Sudah Diterima"}),e.jsx(_,{children:e.jsx(v,{href:route("konsul.detail",{id:a.nomor})})})]},a.nomor)):e.jsx("tr",{className:"bg-white border-b dark:bg-indigo-950 dark:border-gray-500",children:e.jsx("td",{colSpan:"8",className:"px-3 py-3 text-center",children:"Tidak ada data yang dapat ditampilkan"})})})]}),e.jsx(k,{links:o.links})]})})})})}),e.jsx("div",{className:"w-full",children:e.jsx(w,{ruangan:p,queryParams:m||{}})})]})}export{V as default};
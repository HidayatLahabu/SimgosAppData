const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["assets/print-DutYEemu.css"])))=>i.map(i=>d[i]);
import{r as c,_ as b,j as r,S as i}from"./app-1kLFVW50.js";import{f as o}from"./formatDate-Dh0UuduE.js";function h({data:l,dariTanggal:t,sampaiTanggal:d}){c.useEffect(()=>{b(()=>Promise.resolve({}),__vite__mapDeps([0]))},[]);const x=e=>{const[s,a]=e.split(":").map(Number);return s*60+a};return r.jsxs("div",{className:"h-screen w-screen bg-white",children:[r.jsx(i,{title:"Laporan"}),r.jsxs("div",{className:"content",children:[r.jsx("div",{className:"w-full mx-auto sm:px-6 lg:px-5",children:r.jsx("div",{className:"w-full bg-white overflow-hidden",children:r.jsx("div",{className:"p-2 bg-white",children:r.jsxs("div",{className:"overflow-auto",children:[r.jsx("h1",{className:"text-center font-bold text-2xl",children:"LAPORAN WAKTU TUNGGU"}),r.jsx("p",{className:"text-center font-bold text-2xl",children:new Date(t).toLocaleDateString()===new Date(d).toLocaleDateString()?`Tanggal : ${o(d)}`:`Selang Tanggal : ${o(t)} s.d ${o(d)}`}),r.jsxs("table",{className:"w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500",children:[r.jsx("thead",{className:"text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500",children:r.jsxs("tr",{children:[r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid w-[4%]",children:"NO"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid w-[5%]",children:"NORM"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"NAMA PASIEN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"PENDAFTARAN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"RUANGAN"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"DPJP"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"TANGGAL REGISTRASI"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"TANGGAL DITERIMA"}),r.jsx("th",{className:"px-3 py-2 border border-gray-500 border-solid",children:"WAKTU TUNGGU"})]})}),r.jsx("tbody",{children:l.map((e,s)=>{const a=x(e.SELISIH)>60;return r.jsxs("tr",{className:`${a?"text-red-600":""} border-b bg-white dark:border-gray-500`,children:[r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:s+1}),r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.NORM}),r.jsx("td",{className:"px-3 py-2 border border-gray-500 border-solid",children:e.NAMALENGKAP}),r.jsx("td",{className:"px-3 py-2 text-wrap border border-gray-500 border-solid",children:e.NOPEN}),r.jsx("td",{className:"px-3 py-2 text-wrap border border-gray-500 border-solid",children:e.UNITPELAYANAN}),r.jsx("td",{className:"px-3 py-2 text-wrap border border-gray-500 border-solid",children:e.DOKTER_REG}),r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.TGLREG}),r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.TGLTERIMA}),r.jsx("td",{className:"px-3 py-2 text-nowrap border border-gray-500 border-solid",children:e.SELISIH})]},e.noSurat)})})]})]})})})}),r.jsx("footer",{className:"bg-white text-black text-sm",children:r.jsx("div",{className:"text-center",children:r.jsxs("p",{children:["© 2024 - ",new Date().getFullYear()," Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto."]})})})]})]})}export{h as default};
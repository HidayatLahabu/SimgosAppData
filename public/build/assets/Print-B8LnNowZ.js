const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["assets/print-DutYEemu.css"])))=>i.map(i=>d[i]);
import{r as h,_ as N,j as e,S as f}from"./app-CRYISuyJ.js";import{f as r}from"./formatDate-Dh0UuduE.js";import n from"./PrintHarian-_GFy9NiH.js";import x from"./PrintMingguan-CPT71ffS.js";import d from"./PrintBulanan-WJjRmTYO.js";import o from"./PrintTahunan-Ct6abBaR.js";import"./formatRibuan-B2UQKMTQ.js";function b({harian:l,mingguan:i,bulanan:a,tahunan:c,dariTanggal:t,sampaiTanggal:s,namaRuangan:m}){h.useEffect(()=>{N(()=>Promise.resolve({}),__vite__mapDeps([0]))},[]);const j=new Date(t).toLocaleDateString()===new Date(s).toLocaleDateString();return e.jsxs("div",{className:"h-screen w-screen bg-white",children:[e.jsx(f,{title:"Informasi"}),e.jsxs("div",{className:"content",children:[e.jsx("div",{className:"w-full mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"w-full bg-white overflow-hidden",children:e.jsxs("div",{className:"p-2 bg-white",children:[e.jsx("h1",{className:"text-center font-bold text-2xl",children:"INFORMASI KUNJUNGAN RAWAT JALAN"}),e.jsxs("h2",{className:"text-center font-bold text-2xl uppercase",children:["RUANGAN LAYANAN : ",m]}),e.jsx("p",{className:"text-center font-bold text-2xl mb-2",children:new Date(t).toLocaleDateString()===new Date(s).toLocaleDateString()?`Tanggal : ${r(s)}`:`Selang Tanggal : ${r(t)} s.d ${r(s)}`}),j?e.jsx(e.Fragment,{children:e.jsxs("div",{className:"flex flex-wrap w-full",children:[e.jsx("div",{className:"w-1/2 pr-5",children:e.jsx(n,{harian:l})}),e.jsx("div",{className:"w-1/2",children:e.jsx(x,{mingguan:i})}),e.jsx("div",{className:"w-1/2 pr-5 pt-5",children:e.jsx(d,{bulanan:a})}),e.jsx("div",{className:"w-1/2 pt-5",children:e.jsx(o,{tahunan:c})})]})}):e.jsx(e.Fragment,{children:e.jsxs("div",{className:"flex flex-wrap w-full",children:[e.jsx("div",{className:"w-1/2 pr-5",children:e.jsx(n,{harian:l})}),e.jsxs("div",{className:"w-1/2",children:[e.jsx(x,{mingguan:i}),e.jsx("div",{className:"pt-5",children:e.jsx(d,{bulanan:a})}),e.jsx("div",{className:"pt-5",children:e.jsx(o,{tahunan:c})})]})]})})]})})}),e.jsx("footer",{className:"bg-white text-black text-sm",children:e.jsx("div",{className:"text-center",children:e.jsxs("p",{children:["© 2024 - ",new Date().getFullYear()," Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto."]})})})]})]})}export{b as default};
import{G as x,r as h,j as a}from"./app-zIoTxHEc.js";import{S as m}from"./SelectTwoInput-D_1fFy0B.js";import{I as t}from"./InputLabel-DCYHZSnS.js";import{T as g}from"./TextInput-C3nu1uFu.js";function w(){const{data:l,setData:s}=x({dari_tanggal:"",sampai_tanggal:""});h.useEffect(()=>{const e=new Date,n=e.getFullYear(),i=(e.getMonth()+1).toString().padStart(2,"0"),o=`${n}-${i}-01`,r=e.toISOString().split("T")[0];s(j=>({...j,dari_tanggal:o,sampai_tanggal:r}))},[]);const u=e=>{e&&e.value?s(n=>({...n,jenisPenjamin:e.value})):s(n=>({...n,jenisPenjamin:""}))},c=e=>{e&&e.value?s(n=>({...n,jenisKunjungan:e.value})):s(n=>({...n,jenisKunjungan:""}))},d=e=>{e.preventDefault();const n=Object.fromEntries(Object.entries(l).filter(([o,r])=>r!=="")),i=new URLSearchParams(n).toString();window.open(route("layananLab.print")+"?"+i,"_blank")};return a.jsx("div",{className:"pt-2",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsxs("form",{onSubmit:d,className:"p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg",children:[a.jsx("h1",{className:"uppercase text-center font-bold text-2xl pt-2 text-white",children:"Laporan Layanan Laboratorium"}),a.jsxs("div",{className:"mt-4 flex space-x-4",children:[a.jsxs("div",{className:"flex-1",children:[a.jsx(t,{htmlFor:"jenisPasien",value:"Jenis Penjamin"}),a.jsx(m,{id:"jenisPasien",name:"jenisPasien",className:"mt-1 block w-full",placeholder:"Pilih Jenis Penjamin",onChange:u,options:[{value:1,label:"Penjamin Non BPJS Kesehatan"},{value:2,label:"Penjamin BPJS Kesehatan"}]})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(t,{htmlFor:"jenisKunjungan",value:"Jenis Kunjungan"}),a.jsx(m,{id:"jenisKunjungan",name:"jenisKunjungan",className:"mt-1 block w-full",placeholder:"Pilih Jenis Kunjungan",onChange:c,options:[{value:1,label:"Rawat Inap"},{value:2,label:"Rawat Jalan"}]})]})]}),a.jsxs("div",{className:"mt-4 flex space-x-4",children:[a.jsxs("div",{className:"flex-1",children:[a.jsx(t,{htmlFor:"dari_tanggal",value:"Dari Tanggal"}),a.jsx(g,{type:"date",id:"dari_tanggal",name:"dari_tanggal",className:"mt-1 block w-full",value:l.dari_tanggal,onChange:e=>s(n=>({...n,dari_tanggal:e.target.value}))})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(t,{htmlFor:"sampai_tanggal",value:"Sampai Tanggal"}),a.jsx(g,{type:"date",id:"sampai_tanggal",name:"sampai_tanggal",className:"mt-1 block w-full",value:l.sampai_tanggal,onChange:e=>s(n=>({...n,sampai_tanggal:e.target.value}))})]})]}),a.jsx("div",{className:"flex justify-between items-center mt-4",children:a.jsx("button",{className:"bg-red-500 py-1 px-3 text-gray-200 rounded shadow transition-all hover:bg-red-700 ml-auto",type:"submit",children:"Cetak"})})]})})})})}export{w as default};
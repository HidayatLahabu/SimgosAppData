import{G as v,r as j,j as a}from"./app-Bya0VxHD.js";import{S as g}from"./SelectTwoInput-CItzWOCA.js";import{I as r}from"./InputLabel-C4GSuquz.js";import{T as c}from"./TextInput-BzNbzsG0.js";function D({ruangan:u=[]}){const{data:i,setData:s}=v({dari_tanggal:"",sampai_tanggal:"",ruangan:"",statusKonsul:""}),d=()=>{const e=new Date,t=e.getFullYear(),l=(e.getMonth()+1).toString().padStart(2,"0"),n=`${t}-${l}-01`,o=e.toISOString().split("T")[0];s(f=>({...f,dari_tanggal:n,sampai_tanggal:o}))};j.useEffect(()=>{d()},[]);const m=e=>{const{name:t,value:l}=e.target;s(n=>({...n,[t]:l}))},x=e=>{e&&e.value?s(t=>({...t,ruangan:e.value})):s(t=>({...t,ruangan:""}))},h=e=>{e&&e.value?s(t=>({...t,statusKonsul:e.value})):s(t=>({...t,statusKonsul:""}))},p=e=>{e.preventDefault();const t=Object.fromEntries(Object.entries(i).filter(([n,o])=>o!=="")),l=new URLSearchParams(t).toString();window.open(route("konsul.print")+"?"+l,"_blank")};return a.jsx("div",{className:"pt-2",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsxs("form",{onSubmit:p,className:"p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg",children:[a.jsx("h1",{className:"uppercase text-center font-bold text-2xl pt-2 text-white",children:"Laporan Konsul Pasien"}),a.jsxs("div",{className:"mt-4 flex space-x-4",children:[a.jsxs("div",{className:"flex-1",children:[a.jsx(r,{htmlFor:"ruangan",value:"Ruangan Tujuan"}),a.jsx(g,{id:"ruangan",name:"ruangan",className:"mt-1 block w-full",placeholder:"Pilih Ruangan",options:Array.isArray(u)?u.map(e=>({value:e.ID,label:e.ID+". "+e.DESKRIPSI})):[],onChange:x,isClearable:!0})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(r,{htmlFor:"statusKonsul",value:"Status Konsul"}),a.jsx(g,{id:"statusKonsul",name:"statusKonsul",className:"mt-1 block w-full",placeholder:"Pilih Status Konsul",onChange:h,options:[{value:0,label:"Batal Konsul"},{value:1,label:"Belum Diterima"},{value:2,label:"Sudah Diterima"}]})]})]}),a.jsxs("div",{className:"mt-4 flex space-x-4",children:[a.jsxs("div",{className:"flex-1",children:[a.jsx(r,{htmlFor:"dari_tanggal",value:"Dari Tanggal"}),a.jsx(c,{type:"date",id:"dari_tanggal",name:"dari_tanggal",className:"mt-1 block w-full",value:i.dari_tanggal,onChange:m})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(r,{htmlFor:"sampai_tanggal",value:"Sampai Tanggal"}),a.jsx(c,{type:"date",id:"sampai_tanggal",name:"sampai_tanggal",className:"mt-1 block w-full",value:i.sampai_tanggal,onChange:m})]})]}),a.jsx("div",{className:"flex justify-between items-center mt-4",children:a.jsx("button",{className:"bg-red-500 py-1 px-3 text-gray-200 rounded shadow transition-all hover:bg-red-700 ml-auto",type:"submit",children:"Cetak"})})]})})})})}export{D as default};
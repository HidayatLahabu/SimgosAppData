import{G as v,r as b,j as a}from"./app-CRYISuyJ.js";import{S as g}from"./SelectTwoInput-W_P4CaqA.js";import{I as l}from"./InputLabel-BrK704T9.js";import{T as c}from"./TextInput-CmC8mD8n.js";function k({ruangan:o=[]}){const{data:r,setData:s}=v({dari_tanggal:"",sampai_tanggal:"",ruangan:"",statusKunjungan:"",pasien:""}),d=()=>{const n=new Date,e=n.getFullYear(),t=(n.getMonth()+1).toString().padStart(2,"0"),i=`${e}-${t}-01`,u=n.toISOString().split("T")[0];s(f=>({...f,dari_tanggal:i,sampai_tanggal:u}))};b.useEffect(()=>{d()},[]);const m=n=>{const{name:e,value:t}=n.target;s(i=>({...i,[e]:t}))},h=n=>{n&&n.value?s(e=>({...e,ruangan:n.value})):s(e=>({...e,ruangan:""}))},x=n=>{n&&n.value?s(e=>({...e,statusKunjungan:n.value})):s(e=>({...e,statusKunjungan:""}))},p=n=>{n&&n.value?s(e=>({...e,pasien:n.value})):s(e=>({...e,pasien:""}))},j=n=>{n.preventDefault();const e=Object.fromEntries(Object.entries(r).filter(([i,u])=>u!=="")),t=new URLSearchParams(e).toString();window.open(route("kunjungan.print")+"?"+t,"_blank")};return a.jsx("div",{className:"pt-2",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsxs("form",{onSubmit:j,className:"p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg",children:[a.jsx("h1",{className:"uppercase text-center font-bold text-2xl pt-2 text-white",children:"Laporan Kunjungan Pasien"}),a.jsxs("div",{className:"mt-4 flex space-x-4",children:[a.jsxs("div",{className:"flex-1",children:[a.jsx(l,{htmlFor:"ruangan",value:"Ruangan Tujuan"}),a.jsx(g,{id:"ruangan",name:"ruangan",className:"mt-1 block w-full",placeholder:"Pilih Ruangan",options:Array.isArray(o)?o.map(n=>({value:n.ID,label:n.ID+". "+n.DESKRIPSI})):[],onChange:h,isClearable:!0})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(l,{htmlFor:"statusKunjungan",value:"Status Aktifitas Kunjungan"}),a.jsx(g,{id:"statusKunjungan",name:"statusKunjungan",className:"mt-1 block w-full",placeholder:"Pilih Status Aktifitas Kunjungan",onChange:x,options:[{value:1,label:"Batal Kunjungan"},{value:2,label:"Sedang Dilayani"},{value:3,label:"Selesai"}]})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(l,{htmlFor:"pasien",value:"Status Kunjungan"}),a.jsx(g,{id:"pasien",name:"pasien",className:"mt-1 block w-full",placeholder:"Pilih Status Kunjungan",onChange:p,options:[{value:1,label:"Baru"},{value:2,label:"Lama"}]})]})]}),a.jsxs("div",{className:"mt-4 flex space-x-4",children:[a.jsxs("div",{className:"flex-1",children:[a.jsx(l,{htmlFor:"dari_tanggal",value:"Dari Tanggal"}),a.jsx(c,{type:"date",id:"dari_tanggal",name:"dari_tanggal",className:"mt-1 block w-full",value:r.dari_tanggal,onChange:m})]}),a.jsxs("div",{className:"flex-1",children:[a.jsx(l,{htmlFor:"sampai_tanggal",value:"Sampai Tanggal"}),a.jsx(c,{type:"date",id:"sampai_tanggal",name:"sampai_tanggal",className:"mt-1 block w-full",value:r.sampai_tanggal,onChange:m})]})]}),a.jsx("div",{className:"flex justify-between items-center mt-4",children:a.jsx("button",{className:"bg-red-500 py-1 px-3 text-gray-200 rounded shadow transition-all hover:bg-red-700 ml-auto",type:"submit",children:"Cetak"})})]})})})})}export{k as default};
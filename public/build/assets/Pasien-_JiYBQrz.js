import{j as e,S as u}from"./app-BPUvGZWY.js";import{A as f}from"./AuthenticatedLayout-joG-i236.js";import{B as j}from"./ButtonBack-D5Aji53p.js";import{T as p,a as N,b as w}from"./TableHeaderCell-BxxcXo1B.js";import{T as b,a as c}from"./TableCell-Cd-3iJmm.js";import"./transition-Ca6K4WkE.js";function E({auth:o,detail:t}){const m=[{name:"NO",className:"w-[5%]"},{name:"COLUMN NAME",className:"w-[40%]"},{name:"VALUE",className:"w-[auto]"}],x=Object.keys(t).map(a=>({uraian:a,value:t[a]})),h=(a,s)=>a==="TEMPAT_LAHIR"&&s==7501?"GORONTALO":s,i=x.filter(a=>String(a.value||"").trim()!==""),n=Math.ceil(i.length/2),d=[];for(let a=0;a<i.length;a+=n)d.push(i.slice(a,a+n));return e.jsxs(f,{user:o.user,children:[e.jsx(u,{title:"Pendaftaran"}),e.jsx("div",{className:"py-5",children:e.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:e.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:e.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:e.jsxs("div",{className:"overflow-auto w-full",children:[e.jsxs("div",{className:"relative flex items-center justify-between pb-2",children:[e.jsx(j,{href:route("pendaftaran.detail",{id:t.NOMOR_PENDAFTARAN})}),e.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl",children:"DATA DETAIL PASIEN"})]}),e.jsx("div",{className:"flex flex-wrap gap-2",children:d.map((a,s)=>e.jsx("div",{className:"flex-1 shadow-md rounded-lg",children:e.jsxs(p,{children:[e.jsx(N,{children:e.jsx("tr",{children:m.map((r,l)=>e.jsx(w,{className:`${l===0?"w-[10%]":l===1?"w-[30%]":"w-[60%]"} ${r.className||""}`,children:r.name},l))})}),e.jsx("tbody",{children:a.map((r,l)=>e.jsxs(b,{children:[e.jsx(c,{children:l+1+s*n}),e.jsx(c,{children:r.uraian}),e.jsx(c,{className:"text-wrap",children:h(r.uraian,r.value)})]},l))})]})},s))})]})})})})})]})}export{E as default};
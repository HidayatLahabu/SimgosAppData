import{j as e}from"./app-C9Opettx.js";import{f as p}from"./formatRibuan-BoXPYuQw.js";const l=({href:r,title:n,value:a,titleSize:i="text-lg",valueSize:o="text-2xl",description:s="PASIEN"})=>e.jsxs("a",{href:r,className:"flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border border-gray-300 dark:border-gray-700 group",children:[e.jsx("h2",{className:`${i} font-bold text-gray-200 dark:text-yellow-500 uppercase group-hover:text-gray-200`,children:n}),e.jsxs("p",{className:`${o} font-semibold text-white mt-2 group-hover:text-red-500`,children:[p(a)," ",s]})]});function A({pendaftaran:r,kunjungan:n,konsul:a,mutasi:i,kunjunganBpjs:o,laboratorium:s,radiologi:c,resep:S,pulang:f,auth:u}){var d,m;const x=new Date;x.toLocaleDateString("id-ID",{weekday:"long",day:"numeric",month:"long",year:"numeric"})+""+x.toLocaleTimeString("id-ID",{hour:"numeric",minute:"numeric",second:"numeric"});const t=(m=(d=u==null?void 0:u.user)==null?void 0:d.name)==null?void 0:m.includes("Admin");return e.jsx("div",{className:"max-w-full mx-auto sm:px-5 lg:px- w-full",children:e.jsx("div",{className:"text-gray-900 dark:text-gray-100 w-full",children:e.jsxs("div",{className:"flex flex-wrap gap-2 justify-between mb-4",children:[e.jsx(l,{href:t?route("pendaftaran.index"):null,title:"PENDAFTARAN PASIEN",titleSize:"text-normal",valueSize:"text-normal",value:r,description:""}),e.jsx(l,{href:t?route("kunjungan.index"):null,title:"KUNJUNGAN PASIEN",titleSize:"text-normal",valueSize:"text-normal",value:n,description:""}),e.jsx(l,{href:t?route("konsul.index"):null,title:"PERMINTAAN KONSUL",titleSize:"text-normal",valueSize:"text-normal",value:a,description:""}),e.jsx(l,{href:t?route("mutasi.index"):null,title:"PERMINTAAN MUTASI",titleSize:"text-normal",valueSize:"text-normal",value:i,description:""}),e.jsx(l,{href:t?route("kunjunganBpjs.index"):null,title:"PASIEN BPJS KESEHATAN",titleSize:"text-normal",valueSize:"text-normal",value:o,description:""}),e.jsx(l,{href:t?route("layananLab.index"):null,title:"ORDER LABORATORIUM",titleSize:"text-normal",valueSize:"text-normal",value:s,description:""}),e.jsx(l,{href:t?route("layananRad.index"):null,title:"ORDER RADIOLOGI",titleSize:"text-normal",valueSize:"text-normal",value:c,description:""}),e.jsx(l,{href:t?route("layananResep.index"):null,title:"ORDER OBAT/RESEP",titleSize:"text-normal",valueSize:"text-normal",value:S,description:""}),e.jsx(l,{href:t?route("layananPulang.index"):null,title:"PULANG RAWAT INAP",titleSize:"text-normal",valueSize:"text-normal",value:f,description:""})]})})})}export{A as default};
import{j as e}from"./app-uZIXFHN0.js";import{f as c}from"./formatRibuan-BoXPYuQw.js";const t=({href:a,title:r,value:l,titleSize:n="text-lg",valueSize:i="text-2xl"})=>e.jsxs("a",{href:a,className:"flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border border-gray-300 dark:border-gray-700 group",children:[e.jsx("h2",{className:`${n} font-bold text-gray-200 dark:text-yellow-500 uppercase group-hover:text-gray-200`,children:r}),e.jsxs("p",{className:`${i} font-semibold text-white mt-2 group-hover:text-red-500`,children:[c(l)," Pasien"]})]});function g({pendaftaran:a,kunjungan:r,konsul:l,mutasi:n,kunjunganBpjs:i,laboratorium:x,radiologi:s,resep:u,pulang:d}){const o=new Date,m=o.toLocaleDateString("id-ID",{weekday:"long",day:"numeric",month:"long",year:"numeric"})+" "+o.toLocaleTimeString("id-ID",{hour:"numeric",minute:"numeric",second:"numeric"});return e.jsxs("div",{className:"max-w-full mx-auto sm:px-5 lg:px- w-full",children:[e.jsxs("h1",{className:"uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2",children:["Data Hari Ini ",e.jsx("br",{})," ",e.jsx("span",{className:"text-lg",children:m})]}),e.jsx("div",{className:"text-gray-900 dark:text-gray-100 w-full",children:e.jsxs("div",{className:"flex flex-wrap gap-2 justify-between mb-4",children:[e.jsx(t,{href:route("pendaftaran.index"),title:"PENDAFTARAN",titleSize:"text-normal",valueSize:"text-normal",value:a}),e.jsx(t,{href:route("kunjungan.index"),title:"KUNJUNGAN",titleSize:"text-normal",valueSize:"text-normal",value:r}),e.jsx(t,{href:route("konsul.index"),title:"KONSUL",titleSize:"text-normal",valueSize:"text-normal",value:l}),e.jsx(t,{href:route("mutasi.index"),title:"MUTASI",titleSize:"text-normal",valueSize:"text-normal",value:n}),e.jsx(t,{href:route("kunjunganBpjs.index"),title:"BPJS",titleSize:"text-normal",valueSize:"text-normal",value:i}),e.jsx(t,{href:route("layananLab.index"),title:"LABORATORIUM",titleSize:"text-normal",valueSize:"text-normal",value:x}),e.jsx(t,{href:route("layananRad.index"),title:"RADIOLOGI",titleSize:"text-normal",valueSize:"text-normal",value:s}),e.jsx(t,{href:route("layananResep.index"),title:"RESEP",titleSize:"text-normal",valueSize:"text-normal",value:u}),e.jsx(t,{href:route("layananPulang.index"),title:"PULANG",titleSize:"text-normal",valueSize:"text-normal",value:d})]})})]})}export{g as default};
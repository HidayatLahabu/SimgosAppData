import{r as l,j as e}from"./app-Bya0VxHD.js";import{f as g}from"./formatRibuan-B2UQKMTQ.js";import{F as p}from"./HomeIcon-BsiOcOei.js";import{F as j,a as x}from"./UsersIcon-BY7H6dV4.js";function w({title:r,titleId:t,...s},a){return l.createElement("svg",Object.assign({xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 16",fill:"currentColor","aria-hidden":"true","data-slot":"icon",ref:a,"aria-labelledby":t},s),r?l.createElement("title",{id:t},r):null,l.createElement("path",{fillRule:"evenodd",d:"M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Zm7.75-4.25a.75.75 0 0 0-1.5 0V8c0 .414.336.75.75.75h3.25a.75.75 0 0 0 0-1.5h-2.5v-3.5Z",clipRule:"evenodd"}))}const N=l.forwardRef(w),d=({href:r,ruangan:t,dpjp:s,jumlahPasien:a,waktuTunggu:n,waktuStatus:i="normal",waktuColor:m="text-white",iconColor:o="text-white",borderColor:u="border-gray-700",bgGradient:f="bg-gradient-to-r from-indigo-800 to-indigo-900"})=>{const h=i==="tercepat"?"group-hover:text-green-400":i==="terlambat"?"group-hover:text-red-500":"group-hover:text-yellow-400";return e.jsxs("div",{className:`flex flex-col px-5 py-5 bg-gradient-to-r ${f} rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border ${u} group`,children:[e.jsxs("div",{className:"flex items-center mb-2 text-left",children:[e.jsx(p,{className:`w-5 h-5 mr-1 ${o}`}),e.jsx("h2",{className:"text-sm uppercase font-bold text-gray-200 group-hover:text-yellow-500",children:t})]}),e.jsxs("div",{className:"flex items-center mb-3 text-left",children:[e.jsx(j,{className:`w-5 h-5 mr-1 ${o}`}),e.jsx("p",{className:"text-sm font-semibold text-gray-300 group-hover:text-gray-100",children:s})]}),e.jsxs("div",{className:"flex justify-between items-center w-full",children:[e.jsxs("div",{className:"flex items-center mb-2",children:[e.jsx(x,{className:`w-5 h-5 mr-1 ${o}`}),e.jsxs("p",{className:"text-sm font-semibold text-gray-300 group-hover:text-gray-100",children:["Pasien: ",g(a)]})]}),e.jsxs("div",{className:"flex items-center mb-2",children:[e.jsx(N,{className:`w-5 h-5 mr-1 ${o}`}),e.jsx("p",{className:`text-sm font-bold ${m} ${h}`,children:n})]})]})]})},c=r=>{const t=Math.floor(r),s=Math.floor(t/3600),a=Math.floor(t%3600/60),n=t%60;return`${s.toString().padStart(2,"0")}:${a.toString().padStart(2,"0")}:${n.toString().padStart(2,"0")}`};function y({waktuTungguTercepat:r,waktuTungguTerlama:t}){const s=c(r.WAKTU_TUNGGU_RATA_RATA),a=c(t.WAKTU_TUNGGU_RATA_RATA);return e.jsxs("div",{className:"max-w-full mx-auto sm:pr-5 lg:pr-5 w-full",children:[e.jsx("h1",{className:"uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400",children:"Waktu Tunggu"}),e.jsx("div",{className:"text-gray-900 dark:text-gray-100 w-full",children:e.jsxs("div",{className:"grid grid-cols-1 gap-2",children:[e.jsx(d,{ruangan:r.UNITPELAYANAN,dpjp:r.DOKTER_REG,jumlahPasien:r.JUMLAH_PASIEN,waktuTunggu:s,waktuStatus:"Tercepat",waktuColor:"text-green-500",iconColor:"text-green-500"}),e.jsx(d,{ruangan:t.UNITPELAYANAN,dpjp:t.DOKTER_REG,jumlahPasien:t.JUMLAH_PASIEN,waktuTunggu:a,waktuStatus:"normal",waktuColor:"text-red-500",iconColor:"text-red-500",icon:x})]})})]})}export{y as default};
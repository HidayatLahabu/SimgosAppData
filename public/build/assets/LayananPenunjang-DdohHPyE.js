import{r as t,j as e}from"./app-Bya0VxHD.js";import{F as x}from"./HomeIcon-BsiOcOei.js";import{F as h,a as u}from"./UserGroupIcon-D6dCCfRd.js";import{f as s}from"./formatRibuan-B2UQKMTQ.js";function f({title:a,titleId:r,...n},l){return t.createElement("svg",Object.assign({xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 16",fill:"currentColor","aria-hidden":"true","data-slot":"icon",ref:l,"aria-labelledby":r},n),a?t.createElement("title",{id:r},a):null,t.createElement("path",{fillRule:"evenodd",d:"M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z",clipRule:"evenodd"}),t.createElement("path",{fillRule:"evenodd",d:"M2 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm6.585 1.08a.75.75 0 0 1 .336 1.005l-1.75 3.5a.75.75 0 0 1-1.16.234l-1.75-1.5a.75.75 0 0 1 .977-1.139l1.02.875 1.321-2.64a.75.75 0 0 1 1.006-.336Z",clipRule:"evenodd"}))}const g=t.forwardRef(f);function p({title:a,titleId:r,...n},l){return t.createElement("svg",Object.assign({xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 16",fill:"currentColor","aria-hidden":"true","data-slot":"icon",ref:l,"aria-labelledby":r},n),a?t.createElement("title",{id:r},a):null,t.createElement("path",{fillRule:"evenodd",d:"M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z",clipRule:"evenodd"}),t.createElement("path",{fillRule:"evenodd",d:"M3 6a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H3Zm1.75 2.5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4 11.75a.75.75 0 0 1 .75-.75h3.5a.75.75 0 0 1 0 1.5h-3.5a.75.75 0 0 1-.75-.75Z",clipRule:"evenodd"}))}const v=t.forwardRef(p),o=({ruangan:a,jumlahOrder:r,jumlahTindakan:n,catatanHasil:l,jumlahHasil:i,iconColor:d="text-white",borderColor:m="border-gray-700",bgGradient:c="bg-gradient-to-r from-indigo-800 to-indigo-900"})=>e.jsxs("div",{className:`flex flex-col px-5 py-5 bg-gradient-to-r ${c} rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border ${m} group`,children:[e.jsxs("div",{className:"flex items-center mb-2 pt-1 text-left",children:[e.jsx(x,{className:`w-5 h-5 mr-2 ${d}`}),e.jsx("h2",{className:"text-sm uppercase font-bold text-gray-200 group-hover:text-yellow-500",children:a})]}),e.jsxs("div",{className:"flex items-center mb-3 text-left",children:[e.jsx(h,{className:`w-5 h-5 mr-2 ${d}`}),e.jsxs("p",{className:"text-sm font-semibold text-gray-300 group-hover:text-gray-100",children:["Jumlah Order: ",r]})]}),e.jsxs("div",{className:"flex items-center mb-3 text-left",children:[e.jsx(u,{className:`w-5 h-5 mr-2 ${d}`}),e.jsxs("p",{className:"text-sm font-semibold text-gray-300 group-hover:text-gray-100",children:["Jumlah Tindakan: ",n]})]}),e.jsxs("div",{className:"flex items-center mb-3 text-left",children:[e.jsx(v,{className:`w-5 h-5 mr-2 ${d}`}),e.jsxs("p",{className:"text-sm font-semibold text-gray-300 group-hover:text-gray-100",children:["Jumlah Hasil : ",i]})]}),e.jsxs("div",{className:"flex items-center mb-2 text-left",children:[e.jsx(g,{className:`w-5 h-5 mr-2 ${d}`}),e.jsxs("p",{className:"text-sm font-semibold text-gray-300 group-hover:text-gray-100",children:["Catatan Hasil: ",l]})]})]});function N({dataLaboratorium:a,hasilLaboratorium:r,catatanLaboratorium:n,dataRadiologi:l,hasilRadiologi:i,catatanRadiologi:d}){return e.jsx("div",{className:"max-w-full mx-auto sm:pl-5 lg:pl-5 w-full",children:e.jsx("div",{className:"text-gray-900 dark:text-gray-100 w-full",children:e.jsxs("div",{className:"grid grid-cols-1 gap-2",children:[e.jsx(o,{ruangan:"LABORATORIUM",jumlahOrder:s(a.orderLab),jumlahTindakan:s(a.tindakanLab),jumlahHasil:s(r.hasilLab),catatanHasil:s(n.catatanLab),iconColor:"text-orange-500"}),e.jsx(o,{ruangan:"RADIOLOGI",jumlahOrder:s(l.orderRad),jumlahTindakan:s(l.tindakanRad),jumlahHasil:s(i.hasilRad),catatanHasil:s(d.catatanRad),iconColor:"text-pink-500"})]})})})}export{N as default};
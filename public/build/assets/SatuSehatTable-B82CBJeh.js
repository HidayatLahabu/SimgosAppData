import{j as r}from"./app-1kLFVW50.js";import{T as d,a as n,b as x}from"./TableHeaderCell-CbYBJ8DU.js";function E({items:a=[]}){const s=[{name:"PARAMETER SATUSEHAT"},{name:"TOTAL DATA",className:"text-center"},{name:"MEMILIKI ID",className:"text-center"},{name:"TIDAK MEMILIKI ID",className:"text-center"},{name:"PERSENTASE",className:"text-center"},{name:"TANGGAL SINKRONISASI",className:"text-center"}];if(!Array.isArray(a))return console.error("Expected items to be an array but received:",a),r.jsx("div",{className:"text-red-500 text-center mt-5",children:"Error: Data not available"});const l=[...a].sort((e,t)=>e.NAMA.localeCompare(t.NAMA));return r.jsx("div",{className:"max-w-full mx-auto sm:px-5 lg:px-5 w-full",children:r.jsx("div",{className:"bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full",children:r.jsxs("div",{className:"p-5 text-gray-900 dark:text-gray-100 w-full",children:[r.jsx("h1",{className:"uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-gray-200 mb-4",children:"Data SatuSehat"}),r.jsx("div",{className:"overflow-x-auto",children:r.jsxs(d,{children:[r.jsx(n,{children:r.jsx("tr",{children:s.map((e,t)=>r.jsx(x,{className:e.className||"",children:e.name},t))})}),r.jsx("tbody",{children:l.map((e,t)=>r.jsxs("tr",{className:`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${t%2===0,"bg-gray-50 dark:bg-indigo-950"}`,children:[r.jsx("td",{className:`border border-gray-600 px-4 py-2 font-medium ${e.PERSEN>50?"text-green-500":e.PERSEN==0?"text-red-400":"text-yellow-400"}`,children:e.NAMA}),r.jsx("td",{className:`border border-gray-600 px-4 py-2 text-center ${e.PERSEN>50?"text-green-500":e.PERSEN==0?"text-red-400":"text-yellow-400"}`,children:e.TOTAL.toLocaleString()}),r.jsx("td",{className:`border border-gray-600 px-4 py-2 text-center ${e.PERSEN>50?"text-green-500":e.PERSEN==0?"text-red-400":"text-yellow-400"}`,children:e.MEMILIKI_ID.toLocaleString()}),r.jsx("td",{className:`border border-gray-600 px-4 py-2 text-center ${e.PERSEN>50?"text-green-500":e.PERSEN==0?"text-red-400":"text-yellow-400"}`,children:e.TIDAK_MEMILIKI_ID.toLocaleString()}),r.jsx("td",{className:`border border-gray-600 px-4 py-2 text-center ${e.PERSEN>50?"text-green-500":e.PERSEN==0?"text-red-400":"text-yellow-400"}`,children:r.jsxs("span",{children:[parseFloat(e.PERSEN).toFixed(2)," %"]})}),r.jsx("td",{className:`border border-gray-600 px-4 py-2 text-center ${e.PERSEN>50?"text-green-500":e.PERSEN==0?"text-red-400":"text-yellow-400"}`,children:e.LAST_UPDATE&&!isNaN(new Date(e.LAST_UPDATE))?new Date(e.LAST_UPDATE).toLocaleDateString("id-ID",{day:"2-digit",month:"long",year:"numeric"}):"Belum Sinkronisasi"})]},t))})]})})]})})})}export{E as default};
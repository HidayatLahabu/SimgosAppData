import{j as a,x as n}from"./app-CRYISuyJ.js";function o({links:e=[]}){return console.log("Pagination links received:",e),Array.isArray(e)?a.jsx("nav",{className:"text-center mt-4",children:e.length>0?e.map((r,t)=>a.jsx(n,{preserveScroll:!0,href:r.url||"",className:"inline-block py-2 px-3 rounded-lg text-gray-200 text-xs "+(r.active?"bg-gray-950":"hover:bg-gray-950")+(r.url?"":" text-gray-500 cursor-not-allowed"),dangerouslySetInnerHTML:{__html:r.label}},t)):a.jsx("div",{children:"No pagination links available."})}):a.jsx("div",{children:"No pagination links available."})}export{o as P};
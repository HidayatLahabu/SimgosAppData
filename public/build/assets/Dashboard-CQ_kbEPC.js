import{j as e,S as E}from"./app-DZ56VpWn.js";import{A as H}from"./AuthenticatedLayout-BBgHAodS.js";import J from"./TodayData-CvHAC_7A.js";import M from"./StatisticTahun-DszjHHtN.js";import W from"./KunjunganHarian-CpKJ75TQ.js";import q from"./RajalBulanan-IqzfKN6P.js";import z from"./DaruratBulanan-DcB73EmJ.js";import C from"./RanapBulanan-D4Z1_Cn2.js";import F from"./LaboratoriumBulanan-a60IilQw.js";import G from"./RadiologiBulanan-HC6eEame.js";import O from"./WaktuTunggu-CMM4KUpS.js";import Q from"./LayananPenunjang-QjMRD4j5.js";import"./transition-CpC-yBg_.js";import"./formatRibuan-B2UQKMTQ.js";import"./UsersIcon-rTn9DBtq.js";import"./UserGroupIcon-D2uMt_ZZ.js";import"./chart-DTMVf8OI.js";import"./HomeIcon-BqB3Fbyr.js";function ce({auth:a,pendaftaran:t,kunjungan:r,konsul:s,mutasi:n,kunjunganBpjs:i,rencanaKontrol:x,laboratorium:o,radiologi:m,resep:c,pulang:d,statistikKunjungan:p,statistikTahunIni:u,statistikTahunLalu:f,statistikBulanIni:j,statistikBulanLalu:h,rawatJalanBulanan:w,rawatDaruratBulanan:N,rawatInapBulanan:g,laboratoriumBulanan:v,radiologiBulanan:y,waktuTungguTercepat:b,waktuTungguTerlama:D,dataLaboratorium:k,hasilLaboratorium:B,catatanLaboratorium:L,dataRadiologi:S,hasilRadiologi:R,catatanRadiologi:T,dataFarmasi:A,orderFarmasi:I,telaahFarmasi:P}){const l=new Date,K=l.toLocaleDateString("id-ID",{weekday:"long",day:"numeric",month:"long",year:"numeric"})+", JAM "+l.toLocaleTimeString("id-ID",{hour:"numeric",minute:"numeric",second:"numeric",hour12:!1}).replace(/\./g,":");return e.jsxs(H,{user:a.user,children:[e.jsx(E,{title:"Beranda"}),e.jsx("div",{className:"max-w-full mx-auto sm:px-5 lg:px- w-full pt-3",children:e.jsx("h1",{className:"uppercase text-center font-extrabold text-lg text-indigo-700 dark:text-yellow-400",children:K})}),e.jsxs("div",{className:"pt-3 pb-2 flex w-full gap-2",children:[e.jsx("div",{className:"flex-1",children:e.jsx(J,{auth:a,pendaftaran:t,kunjungan:r,konsul:s,mutasi:n,kunjunganBpjs:i,laboratorium:o,radiologi:m,resep:c,pulang:d,rencanaKontrol:x})}),e.jsx("div",{className:"w-1/4",children:e.jsx(O,{waktuTungguTercepat:b,waktuTungguTerlama:D})})]}),e.jsx("div",{className:"pb-2 flex flex-wrap w-full",children:e.jsx(W,{statistikKunjungan:p})}),e.jsxs("div",{className:"flex flex-row gap-2 justify-center items-start w-full",children:[e.jsxs("div",{className:"flex flex-col w-1/4",children:[e.jsx("h1",{className:"pt-3 uppercase text-center font-extrabold text-2xl dark:text-yellow-400",children:"Layanan Penunjang"}),e.jsx(Q,{dataLaboratorium:k,hasilLaboratorium:B,catatanLaboratorium:L,dataRadiologi:S,hasilRadiologi:R,catatanRadiologi:T,dataFarmasi:A,orderFarmasi:I,telaahFarmasi:P})]}),e.jsxs("div",{className:"flex flex-col w-3/4 -mx-1 pr-5",children:[e.jsx("h1",{className:"pt-3 uppercase text-center font-extrabold text-2xl dark:text-yellow-400",children:"Indikator Pelayanan"}),e.jsx("div",{className:"pb-2 flex flex-wrap w-full",children:e.jsx(M,{statistikTahunIni:u,statistikTahunLalu:f,statistikBulanIni:j,statistikBulanLalu:h})})]})]}),e.jsx("h1",{className:"uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400",children:"Kunjungan Bulanan"}),e.jsxs("div",{className:"pb-5 flex flex-wrap w-full",children:[e.jsx("div",{className:"w-1/5",children:e.jsx(q,{rawatJalanBulanan:w})}),e.jsx("div",{className:"w-1/5",children:e.jsx(z,{rawatDaruratBulanan:N})}),e.jsx("div",{className:"w-1/5",children:e.jsx(C,{rawatInapBulanan:g})}),e.jsx("div",{className:"w-1/5",children:e.jsx(F,{laboratoriumBulanan:v})}),e.jsx("div",{className:"w-1/5",children:e.jsx(G,{radiologiBulanan:y})})]})]})}export{ce as default};
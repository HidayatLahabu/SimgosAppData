import{j as e,S as R}from"./app-zIoTxHEc.js";import{A as W}from"./AuthenticatedLayout-zwTt-fZc.js";import B from"./TodayData-Bz5kotOF.js";import E from"./StatisticTahun-p8OagQqR.js";import H from"./KunjunganHarian-CS8G966m.js";import J from"./WaktuTunggu-CzfODY4I.js";import M from"./LayananPenunjang-BQ1rAorP.js";import P from"./RanapKontrolWrapper-Bhgws-70.js";import"./transition-DPx7YgLA.js";import"./formatRibuan-B2UQKMTQ.js";import"./UsersIcon-BkhI_koV.js";import"./UserGroupIcon-NtptalQG.js";import"./chart-DTMVf8OI.js";import"./HomeIcon-DYp2w5dn.js";import"./KunjunganRanap-v5TT84Vz.js";import"./RencanaKontrol-CPCh9AOo.js";function ae({auth:r,pendaftaran:t,kunjungan:l,konsul:i,mutasi:s,kunjunganBpjs:o,penggunaLogin:n,laboratorium:m,radiologi:x,resep:c,pulang:d,statistikKunjungan:p,statistikTahunIni:f,statistikTahunLalu:u,statistikBulanIni:j,statistikBulanLalu:h,waktuTungguTercepat:g,waktuTungguTerlama:w,dataLaboratorium:N,hasilLaboratorium:v,catatanLaboratorium:y,dataRadiologi:D,hasilRadiologi:b,catatanRadiologi:S,dataFarmasi:k,orderFarmasi:T,telaahFarmasi:A,kunjunganRanap:L,rekonBpjs:I}){const a=new Date,K=a.toLocaleDateString("id-ID",{weekday:"long",day:"numeric",month:"long",year:"numeric"})+", JAM "+a.toLocaleTimeString("id-ID",{hour:"numeric",minute:"numeric",second:"numeric",hour12:!1}).replace(/\./g,":");return e.jsxs(W,{user:r.user,children:[e.jsx(R,{title:"Beranda"}),e.jsx("div",{className:"max-w-full mx-auto sm:px-5 lg:px-5 py-3 dark:bg-indigo-900 rounded",children:e.jsx("h1",{className:"uppercase text-center font-extrabold text-lg text-indigo-700 dark:text-yellow-400",children:K})}),e.jsxs("div",{className:"pt-5 pb-2 flex w-full gap-2",children:[e.jsx("div",{className:"flex-1",children:e.jsx(B,{auth:r,pendaftaran:t,kunjungan:l,konsul:i,mutasi:s,kunjunganBpjs:o,laboratorium:m,radiologi:x,resep:c,pulang:d,penggunaLogin:n})}),e.jsx("div",{className:"flex flex-col w-1/4",children:e.jsx(J,{waktuTungguTercepat:g,waktuTungguTerlama:w})})]}),e.jsx("div",{className:"pb-5 flex flex-wrap w-full",children:e.jsx(H,{statistikKunjungan:p})}),e.jsxs("div",{className:"flex flex-row gap-2 justify-center items-start w-full pt-1",children:[e.jsx("div",{className:"flex flex-col w-1/4",children:e.jsx(M,{dataLaboratorium:N,hasilLaboratorium:v,catatanLaboratorium:y,dataRadiologi:D,hasilRadiologi:b,catatanRadiologi:S,dataFarmasi:k,orderFarmasi:T,telaahFarmasi:A})}),e.jsx("div",{className:"flex flex-col w-3/4 -mx-1 pr-5",children:e.jsx("div",{className:"pb-2 flex flex-wrap w-full",children:e.jsx(E,{statistikTahunIni:f,statistikTahunLalu:u,statistikBulanIni:j,statistikBulanLalu:h})})})]}),e.jsx(P,{kunjunganRanap:L,rekonBpjs:I})]})}export{ae as default};
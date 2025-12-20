import{G as x,j as a,S as b}from"./app-Byiaslgf.js";import{A as j}from"./AuthenticatedLayout-1U-mImWn.js";import"./transition-CHlty3Ck.js";const d=n=>n?n.replace(" ","T"):"",i=n=>n??"-";function y({auth:n,kunjungan:e}){const{data:r,setData:l,put:m,processing:u}=x({nomor_pendaftaran:e.nomor_pendaftaran,nomor_kunjungan:e.nomor_kunjungan,norm:e.norm,nama_pasien:e.nama_pasien,ruangan_tujuan:e.ruangan_tujuan,dpjp:e.dpjp,tanggal_masuk:d(e.tanggal_masuk),tanggal_keluar:d(e.tanggal_keluar),status_kunjungan:e.status_kunjungan??1}),c=s=>{s.preventDefault(),m(route("kunjungan.update",r.nomor_kunjungan))};return a.jsxs(j,{user:n.user,children:[a.jsx(b,{title:"Edit Kunjungan"}),a.jsx("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-900 shadow-sm rounded-lg",children:a.jsxs("div",{className:"p-5 dark:bg-indigo-950",children:[a.jsxs("div",{className:"text-center mb-2",children:[a.jsx("h1",{className:"text-xl font-bold uppercase tracking-wide",children:"Edit Kunjungan"}),a.jsx("p",{className:"text-sm text-red-500 dark:text-red-500",children:"*) Field berwarna kuning dapat diubah"})]}),a.jsxs("form",{onSubmit:c,className:"space-y-8",children:[a.jsxs(p,{title:"Informasi Kunjungan",children:[a.jsxs(o,{children:[a.jsx(t,{label:"No Pendaftaran",value:r.nomor_pendaftaran}),a.jsx(t,{label:"No Kunjungan",value:r.nomor_kunjungan}),a.jsx(t,{label:"NORM",value:r.norm}),a.jsx(t,{label:"Nama Pasien",value:r.nama_pasien})]}),a.jsxs(o,{children:[a.jsx(t,{label:"Ruangan Tujuan",value:r.ruangan_tujuan}),a.jsx(t,{label:"DPJP",value:r.dpjp}),a.jsx(t,{label:"Tanggal Masuk",value:i(e.tanggal_masuk)}),a.jsx(t,{label:"Tanggal Keluar",value:i(e.tanggal_keluar)})]})]}),a.jsx(h,{title:"Edit Data Kunjungan",children:a.jsxs(k,{children:[a.jsx(g,{label:"Tanggal Masuk",type:"datetime-local",step:"1",value:r.tanggal_masuk,onChange:s=>l("tanggal_masuk",s.target.value)}),a.jsx(g,{label:"Tanggal Keluar",type:"datetime-local",step:"1",value:r.tanggal_keluar,onChange:s=>l("tanggal_keluar",s.target.value)}),a.jsx(f,{label:"Status Kunjungan",value:r.status_kunjungan,onChange:s=>l("status_kunjungan",s.target.value)})]})}),a.jsxs("div",{className:"flex justify-between pt-2",children:[a.jsx("a",{href:route("kunjungan.detail",r.nomor_kunjungan),className:`
                                            px-4 py-2 rounded
                                            bg-red-500 hover:bg-red-600
                                            text-white text-sm transition
                                        `,children:"Kembali"}),a.jsx("button",{type:"submit",disabled:u,className:`
                                            px-5 py-2 rounded
                                            bg-amber-500 hover:bg-amber-600
                                            text-gray-900 font-semibold text-sm
                                            transition
                                            disabled:opacity-50
                                        `,children:"Simpan"})]})]})]})})})})]})}const p=({title:n,children:e})=>a.jsxs("div",{className:"space-y-4",children:[a.jsx("h2",{className:"text-lg font-semibold border-b pb-2",children:n}),e]}),h=({title:n,children:e})=>a.jsxs("div",{className:`
        space-y-4
        border-l-4 border-amber-400
        bg-amber-50/60 dark:bg-indigo-800/40
        p-4 rounded-md
    `,children:[a.jsx("h2",{className:"text-lg font-semibold text-amber-700 dark:text-amber-300",children:n}),e]}),o=({children:n})=>a.jsx("div",{className:"grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3",children:n}),k=({children:n})=>a.jsx("div",{className:"grid grid-cols-1 md:grid-cols-3 gap-4",children:n}),t=({label:n,value:e})=>a.jsxs("div",{className:`
        flex flex-col p-3 rounded
        bg-gray-50 dark:bg-indigo-800
        border border-gray-200 dark:border-indigo-700
        text-sm
    `,children:[a.jsx("span",{className:"text-gray-500 dark:text-gray-300",children:n}),a.jsx("span",{className:"font-medium text-gray-800 dark:text-gray-100",children:e??"-"})]}),g=({label:n,type:e,value:r,onChange:l})=>a.jsxs("div",{className:`
        flex flex-col p-3 rounded
        bg-white dark:bg-indigo-900
        border border-amber-300 dark:border-amber-400
        text-sm
    `,children:[a.jsx("label",{className:"text-amber-700 dark:text-amber-300 font-medium",children:n}),a.jsx("input",{type:e,value:r,onChange:l,className:`
                mt-1 rounded px-2 py-1
                bg-white dark:bg-indigo-950
                border border-amber-300
                focus:ring-2 focus:ring-amber-400
                text-gray-900 dark:text-gray-100
            `})]}),f=({label:n,value:e,onChange:r})=>a.jsxs("div",{className:`
        flex flex-col p-3 rounded
        bg-white dark:bg-indigo-900
        border border-amber-300 dark:border-amber-400
        text-sm
    `,children:[a.jsx("label",{className:"text-amber-700 dark:text-amber-300 font-medium",children:n}),a.jsxs("select",{value:e,onChange:r,className:`
                mt-1 rounded px-2 py-1
                bg-white dark:bg-indigo-950
                border border-amber-300
                focus:ring-2 focus:ring-amber-400
                text-gray-900 dark:text-gray-100
            `,children:[a.jsx("option",{value:0,children:"Batal"}),a.jsx("option",{value:1,children:"Sedang Dilayani"}),a.jsx("option",{value:2,children:"Selesai"})]})]});export{y as default};

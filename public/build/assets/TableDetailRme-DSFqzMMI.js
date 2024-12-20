import{j as a,x as ca}from"./app-CmLGcUd8.js";import{T as Ma,a as xa,b as ha}from"./TableHeaderCell-D6b_cjrw.js";import{T as La,a as r}from"./TableCell-C5Pf9HOL.js";function fa({triage:s,rekonsiliasiObatAdmisi:N,rekonsiliasiObatTransfer:i,rekonsiliasiObatDischarge:m,askep:E,anamnesisDiperoleh:d,keluhanUtama:k,riwayatPenyakitSekarang:o,riwayatPenyakitDahulu:g,riwayatAlergi:I,riwayatPemberianObat:b,riwayatLainnya:R,faktorRisiko:j,riwayatPenyakitKeluarga:S,riwayatTuberkulosis:K,riwayatGinekologi:T,statusFungsional:P,hubunganPsikososial:p,edukasiPasienKeluarga:c,edukasiEmergency:M,edukasiEndOfLife:x,skriningGiziAwal:h,batuk:L,pemeriksaanUmum:G,pemeriksaanFungsional:U,pemeriksaanFisik:D,pemeriksaanKepala:O,pemeriksaanMata:f,pemeriksaanTelinga:y,pemeriksaanHidung:w,pemeriksaanRambut:H,pemeriksaanBibir:C,pemeriksaanGigiGeligi:B,pemeriksaanLidah:v,pemeriksaanLangitLangit:W,pemeriksaanLeher:Y,pemeriksaanTenggorokan:F,pemeriksaanTonsil:J,pemeriksaanDada:z,pemeriksaanPayudara:V,pemeriksaanPunggung:Z,pemeriksaanPerut:$,pemeriksaanGenital:q,pemeriksaanAnus:Q,pemeriksaanLenganAtas:X,pemeriksaanLenganBawah:_,pemeriksaanJariTangan:aa,pemeriksaanKukuTangan:ea,pemeriksaanPersendianTangan:na,pemeriksaanTungkaiAtas:ta,pemeriksaanTungkaiBawah:la,pemeriksaanJariKaki:ua,pemeriksaanKukuKaki:Aa,pemeriksaanPersendianKaki:ra,pemeriksaanFaring:sa,pemeriksaanSaluranCernahBawah:Na,pemeriksaanSaluranCernahAtas:ia,pemeriksaanEeg:ma,pemeriksaanEmg:Ea,pemeriksaanRavenTest:da,pemeriksaanCatClams:ka,pemeriksaanTransfusiDarah:oa,pemeriksaanAsessmentMChat:ga,pemeriksaanEkg:Ia,cppt:ba,diagnosa:Ra,jadwalKontrol:ja}){const Sa=[{name:"MEDICAL RECORD",className:"text-left, w-[70%]"},{name:"INPUT DATA",className:"text-left, w-[auto]"}],t=[{label:"TRIAGE",data:s,routeName:"kunjungan.triage"},{label:"REKONSILIASI OBAT ADMISI",data:N,routeName:"kunjungan.rekonsiliasiObatAdmisi"},{label:"REKONSILIASI OBAT TRANSFER",data:i,routeName:"kunjungan.rekonsiliasiObatTransfer"},{label:"REKONSILIASI OBAT DISCHARGE",data:m,routeName:"kunjungan.rekonsiliasiObatDischarge"},{label:"ASUHAN KEPERAWATAN",data:E,routeName:"kunjungan.askep"},{label:"KELUHAN UTAMA",data:k,routeName:"kunjungan.keluhanUtama"},{label:"ANAMNESIS DIPEROLEH",data:d,routeName:"kunjungan.anamnesisDiperoleh"},{label:"RIWAYAT PENYAKIT SEKARANG",data:o,routeName:"kunjungan.riwayatPenyakitSekarang"},{label:"RIWAYAT PENYAKIT DAHULU",data:g,routeName:"kunjungan.riwayatPenyakitDahulu"},{label:"RIWAYAT ALERGI",data:I,routeName:"kunjungan.riwayatAlergi"},{label:"RIWAYAT PEMBERIAN OBAT",data:b,routeName:"kunjungan.riwayatPemberianObat"},{label:"RIWAYAT LAINNYA",data:R,routeName:"kunjungan.riwayatLainnya"},{label:"FAKTOR RISIKO",data:j,routeName:"kunjungan.faktorRisiko"},{label:"RIWAYAT PENYAKIT KELUARGA",data:S,routeName:"kunjungan.riwayatPenyakitKeluarga"},{label:"RIWAYAT TUBERKULOSIS",data:K,routeName:"kunjungan.riwayatTuberkulosis"},{label:"RIWAYAT GINEKOLOGI",data:T,routeName:"kunjungan.riwayatGinekologi"},{label:"STATUS FUNGSIONAL",data:P,routeName:"kunjungan.statusFungsional"},{label:"HUBUNGAN STATUS PSIKOSOSIAL",data:p,routeName:"kunjungan.hubunganPsikososial"},{label:"EDUKASI PASIEN DAN KELUARGA",data:c,routeName:"kunjungan.edukasiPasienKeluarga"},{label:"EDUKASI EMERGENCY",data:M,routeName:"kunjungan.edukasiEmergency"},{label:"EDUKASI END OF LIFE",data:x,routeName:"kunjungan.edukasiEndOfLife"},{label:"SKRINING GIZI AWAL",data:h,routeName:"kunjungan.skriningGiziAwal"},{label:"BATUK",data:L,routeName:"kunjungan.batuk"},{label:"PEMERIKSAAN UMUM TANDA VITAL",data:G,routeName:"kunjungan.pemeriksaanUmum"},{label:"PEMERIKSAAN UMUM FUNGSIONAL",data:U,routeName:"kunjungan.pemeriksaanFungsional"},{label:"PEMERIKSAAN FISIK",data:D,routeName:"kunjungan.pemeriksaanUmum"},{label:"PEMERIKSAAN KEPALA",data:O,routeName:"kunjungan.pemeriksaanKepala"},{label:"PEMERIKSAAN MATA",data:f,routeName:"kunjungan.pemeriksaanMata"},{label:"PEMERIKSAAN TELINGA",data:y,routeName:"kunjungan.pemeriksaanTelinga"},{label:"PEMERIKSAAN HIDUNG",data:w,routeName:"kunjungan.pemeriksaanHidung"},{label:"PEMERIKSAAN RAMBUT",data:H,routeName:"kunjungan.pemeriksaanRambut"},{label:"PEMERIKSAAN BIBIR",data:C,routeName:"kunjungan.pemeriksaanBibir"},{label:"PEMERIKSAAN GIGI GELIGI",data:B,routeName:"kunjungan.pemeriksaanGigiGeligi"},{label:"PEMERIKSAAN LIDAH",data:v,routeName:"kunjungan.pemeriksaanLidah"},{label:"PEMERIKSAAN LANGIT-LANGIT",data:W,routeName:"kunjungan.pemeriksaanLangitLangit"},{label:"PEMERIKSAAN LEHER",data:Y,routeName:"kunjungan.pemeriksaanLeher"},{label:"PEMERIKSAAN TENGGOROKAN",data:F,routeName:"kunjungan.pemeriksaanTenggorokan"},{label:"PEMERIKSAAN TONSIL",data:J,routeName:"kunjungan.pemeriksaanTonsil"},{label:"PEMERIKSAAN DADA",data:z,routeName:"kunjungan.pemeriksaanDada"},{label:"PEMERIKSAAN PAYUDARA",data:V,routeName:"kunjungan.pemeriksaanPayudara"},{label:"PEMERIKSAAN PUNGGUNG",data:Z,routeName:"kunjungan.pemeriksaanPunggung"},{label:"PEMERIKSAAN PERUT",data:$,routeName:"kunjungan.pemeriksaanPerut"},{label:"PEMERIKSAAN GENITAL",data:q,routeName:"kunjungan.pemeriksaanGenital"},{label:"PEMERIKSAAN ANUS",data:Q,routeName:"kunjungan.pemeriksaanAnus"},{label:"PEMERIKSAAN LENGAN ATAS",data:X,routeName:"kunjungan.pemeriksaanLenganAtas"},{label:"PEMERIKSAAN LENGAN BAWAH",data:_,routeName:"kunjungan.pemeriksaanLenganBawah"},{label:"PEMERIKSAAN JARI TANGAN",data:aa,routeName:"kunjungan.pemeriksaanJariTangan"},{label:"PEMERIKSAAN KUKU TANGAN",data:ea,routeName:"kunjungan.pemeriksaanKukuTangan"},{label:"PEMERIKSAAN PERSENDIAN TANGAN",data:na,routeName:"kunjungan.pemeriksaanPersendianTangan"},{label:"PEMERIKSAAN TUNGKAI ATAS",data:ta,routeName:"kunjungan.pemeriksaanTungkaiAtas"},{label:"PEMERIKSAAN TUNGKAI BAWAH",data:la,routeName:"kunjungan.pemeriksaanTungkaiBawah"},{label:"PEMERIKSAAN JARI KAKI",data:ua,routeName:"kunjungan.pemeriksaanJariKaki"},{label:"PEMERIKSAAN KUKU KAKI",data:Aa,routeName:"kunjungan.pemeriksaanKukuKaki"},{label:"PEMERIKSAAN PERSENDIAN KAKI",data:ra,routeName:"kunjungan.pemeriksaanPersendianKaki"},{label:"PEMERIKSAAN FARING",data:sa,routeName:"kunjungan.pemeriksaanFaring"},{label:"PEMERIKSAAN SALURAN CERNAH BAWAH",data:Na,routeName:"kunjungan.pemeriksaanSaluranCernahBawah"},{label:"PEMERIKSAAN SALURAN CERNAH ATAS",data:ia,routeName:"kunjungan.pemeriksaanSaluranCernahAtas"},{label:"PEMERIKSAAN EEG",data:ma,routeName:"kunjungan.pemeriksaanEeg"},{label:"PEMERIKSAAN EMG",data:Ea,routeName:"kunjungan.pemeriksaanEmg"},{label:"PEMERIKSAAN RAVEN TEST",data:da,routeName:"kunjungan.pemeriksaanRavenTest"},{label:"PEMERIKSAAN CAT CLAMS",data:ka,routeName:"kunjungan.pemeriksaanCatClams"},{label:"PEMERIKSAAN TRANSFUSI DARAH",data:oa,routeName:"kunjungan.pemeriksaanTransfusiDarah"},{label:"PEMERIKSAAN ASESSMENT M CHAT",data:ga,routeName:"kunjungan.pemeriksaanAsessmentMChat"},{label:"PEMERIKSAAN EKG",data:Ia,routeName:"kunjungan.pemeriksaanEkg"},{label:"CPPT",data:ba,routeName:"kunjungan.cppt"},{label:"DIAGNOSA",data:Ra,routeName:"kunjungan.diagnosa"},{label:"JADWAL KONTROL",data:ja,routeName:"kunjungan.jadwalKontrol"}].filter(e=>e.data&&e.data!==""),l=t.length<5?t.length:Math.ceil(t.length/3),Ka=t.slice(0,l),Ta=t.slice(l,l*2),Pa=t.slice(l*2),pa=({href:e})=>e?a.jsx("div",{className:"flex items-center",children:a.jsxs(ca,{href:e,className:"font-semibold text-gray-800 dark:text-green-500 hover:no-underline",children:["ADA",a.jsx("span",{className:"text-xs text-gray-500 ml-2",children:"Lihat Disini"})]})}):a.jsx("span",{className:"block font-semibold text-red-800 dark:text-red-500",children:"TIDAK ADA"}),u=e=>a.jsxs(Ma,{children:[a.jsx(xa,{children:a.jsx("tr",{children:Sa.map((n,A)=>a.jsx(ha,{className:n.className,children:n.name},A))})}),a.jsx("tbody",{children:e.map((n,A)=>a.jsxs(La,{children:[a.jsx(r,{children:n.label}),a.jsx(r,{children:a.jsx(pa,{href:route(n.routeName,{id:n.data}),label:`Data ${n.label}`})})]},A))})]});return a.jsx("div",{className:"py-2",children:a.jsx("div",{className:"max-w-8xl mx-auto sm:px-6 lg:px-5",children:a.jsx("div",{className:"bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg",children:a.jsxs("div",{className:"p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950",children:[a.jsx("div",{className:"relative flex items-center justify-between pb-7 pt-2",children:a.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl text-center",children:"DATA MEDICAL RECORD"})}),a.jsx("div",{className:"relative flex items-center justify-between pb-7",children:a.jsx("h1",{className:"absolute left-1/2 transform -translate-x-1/2 font-bold italic text-center text-red-400",children:"Hanya menampilkan data yang telah diinput"})}),a.jsxs("div",{className:"grid grid-cols-1 gap-6 md:grid-cols-3",children:[u(Ka),u(Ta),u(Pa)]})]})})})})}export{fa as default};

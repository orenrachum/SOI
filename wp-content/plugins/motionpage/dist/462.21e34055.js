"use strict";(globalThis.webpackChunkmotionpage=globalThis.webpackChunkmotionpage||[]).push([[462],{4462:(R,c,s)=>{s.r(c),s.d(c,{default:()=>w});var a=s(5893),o=s(7294),m=s(4480),p=s(8034),n=s(8300),h=s(3139),P=s(9022),f=s(4712);const j=(0,P.k)("1234567890abcdefghijklm",7);function w(){const[_,I]=(0,o.useState)(!1),[e,T]=(0,o.useState)(null),{processType:u,progressFloat:U}=(0,m.sJ)(p.Hj),[l,v]=(0,m.FV)(p.Ob),S=e?.files?.length,A=e?.totalSize?(0,n.iI)(e.totalSize):"0",{addNewSequence:C}=(0,n.Ah)(),{fileUploadManager:F}=(0,n.NY)();async function b(){if(!e)return;if(e.files.some(t=>t?.data?.type!==e?.files[0]?.data?.type)){f.A.error("Please, use images of the same format.");return}const d=j(),E=e.files.map((t,K)=>{const{data:N}=t,L=`${String(K+1).padStart(4,"0")}.${N.name.split(".").pop()}`;return{...t,data:{...N,name:L}}});await F(E,e?.totalSize,d);const x=`${window.motionpage.upload.uploadsUrl}/${window.motionpage.upload.seqFolder}/${d}/`,M=E.length,O=4,D=Math.floor(Math.random()*M+1),r=e.files[D-1].data.name.split(".").pop(),B=String(D).padStart(O,"0");if(!r)throw new Error("File extension not found.");C({url:`${x}${B}.${r}`,dirDate:(0,n.sw)(),numFiles:M,folderName:d,numPadding:O,baseUrl:x,ext:r}),g(),setTimeout(()=>f.A.success("Image Sequence uploaded."))}function y(i){i?.files&&(T(i),I(!0),v(null))}function g(){if(window.MOTIONPAGE)window.MOTIONPAGE.modalState("");else throw new Error("window.MOTIONPAGE is not defined.")}return(0,o.useEffect)(()=>{l&&"files"in l&&y(l)},[JSON.stringify(l)]),(0,a.jsx)("div",{style:{width:400,gap:10,display:"grid"},children:_&&(0,a.jsxs)(a.Fragment,{children:[(0,a.jsxs)("div",{className:"inner-grid gap-m col-span-4 padding-xl",children:[(0,a.jsxs)("div",{children:[(0,a.jsx)("h2",{className:"bottom-xs",children:"Upload images"}),(0,a.jsx)("span",{className:"label small",children:"This batch of images will create a new image sequence"})]}),(0,a.jsxs)("div",{className:"bordered-block inner-grid table-layout",children:[(0,a.jsxs)("div",{className:"half row between padding-m",children:[(0,a.jsx)("span",{className:"label small",children:"Files:"}),(0,a.jsx)("b",{className:"label main",children:S})]}),(0,a.jsxs)("div",{className:"half row between padding-m",children:[(0,a.jsx)("span",{className:"label small",children:"Total size:"}),(0,a.jsx)("b",{className:"label main",children:A})]})]}),u==="idle"?(0,a.jsx)(h.zx,{variation:"primary",loading:!1,onClick:b,disabled:!1,children:"Upload"}):(0,a.jsx)(h.ko,{progress:U,processType:u,loading:!0})]}),(0,a.jsx)("span",{className:"close-modal pointer",onClick:g,children:(0,a.jsx)("span",{className:"plus-icon remove"})})]})})}}}]);

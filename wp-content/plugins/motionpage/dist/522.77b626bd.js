"use strict";(globalThis.webpackChunkmotionpage=globalThis.webpackChunkmotionpage||[]).push([[522],{3522:(Be,W,o)=>{o.r(W),o.d(W,{default:()=>Me});var e=o(5893),g=o(8300),h=o(3139),t=o(7294),he=o(415),B=o(8034),T=o(4480),P=o(9022),S=o(3446),K=o(4712),k=o(5547),me=o(512);const fe=(0,P.k)("1234567890abcdefghijklm",5),ge=8,p=24,pe={width:1920,height:1080},xe=16/9,c=1920,Ne=[31,29,26,23,19,15,11,8,5,3,2];function je(){const{createFFmpeg:V,fetchFile:H}=window.FFmpeg,Q=(0,T.Zl)(he.cT),{addNewSequence:Ee}=(0,g.Ah)(),[v,we]=(0,T.FV)(B.Ob),[{progressFloat:Fe,processType:G},Y]=(0,T.FV)(B.Hj),l=(0,t.useRef)(null),[z,D]=(0,t.useState)(null),[M,X]=(0,t.useState)(null),[b,_]=(0,t.useState)(24),[J,O]=(0,t.useState)(!1),[Z,Se]=(0,t.useState)(""),[q,ve]=(0,t.useState)(ge),[E,w]=(0,t.useState)(pe),[ee,be]=(0,t.useState)(xe),[y,_e]=(0,t.useState)(!0),[Te,se]=(0,t.useState)(!1),[d,Pe]=(0,t.useState)(null),[x,I]=(0,t.useState)(null),{fileUploadManager:De}=(0,g.NY)(),L=(0,t.useMemo)(()=>d?.width&&d?.height?{width:d.width,height:d.height}:null,[d]),A=(0,t.useMemo)(()=>!x||Number.isNaN(x)?null:String(x),[x]),C=(0,t.useMemo)(()=>l?.current?.duration?Number.isNaN(l?.current?.duration)?null:`${(0,g.sT)(l?.current?.duration)}s`:null,[l?.current?.duration]),U=(0,t.useMemo)(()=>Number.isNaN(l?.current?.duration)||!l?.current?.duration?null:String(Math.floor(l?.current?.duration*b)+2),[b,l?.current?.duration]),ne=[A,C,U].some(s=>s===null);function $(s){Y(n=>({...n,processType:s}))}function te(s){Y(n=>({...n,progress:s}))}function ae(){z&&z.exit(),D(null)}async function Oe(s){s?.buffer&&(Se((0,g.h$)(s.buffer,s.data?.type)),X(s),await(0,g.dF)(50),we(null))}async function ye(){if(!l.current)return;const{videoWidth:s,videoHeight:n}=l.current,a=s/n;be(a),Pe({width:s,height:n});let i=s,r=n;s>c&&(i=c),n>c&&(r=c),a>1&&(r=Math.round(i/a)),a<1&&(i=Math.round(r*a)),w({width:i,height:r});try{const u=await V({log:!1});D(u),u.isLoaded()||await u.load();let m=!1;u.setLogger(({message:F})=>{if(!m&&F.includes("Stream")){m=!0;const re=F.split(", "),j=Number(re[5].replace(" fps",""));Number.isNaN(j)?(I(p),_(p)):(I(j),_(j))}});const N=(0,P.x)(5)+(M?.data?.path.match(/\..*?$/gim)?.[0]??0);u.FS("writeFile",N,await H(M?.buffer)),await u.run("-i",N,"-f","null","/dev/null"),u.FS("unlink",N),ae()}catch{I(p),_(p)}}async function Ie(){const s=performance.now(),n=await V({log:!1,progress:f=>te(Number(f?.ratio??0))});D(n),O(!0);const a=(0,P.x)(5)+(M?.data?.path.match(/\..*?$/gim)?.[0]??0);n.isLoaded()||await n.load(),n.FS("writeFile",a,await H(M?.buffer));const i=fe(),r=["-skip_frame","noref","-i",a];r.push("-vsync","cfr","-r",b.toString()),r.push("-q:v",`${Ne.at(q)}`),r.push("-s",`${E.width}x${E.height}`),r.push("-an"),r.push("-dn"),r.push("%04d.jpg"),$("transcoding"),await n.run(...r),n.FS("unlink",a),X(null);let u=0;const m=[],N=performance.now();await n.FS("readdir","/").filter(f=>f.endsWith(".jpg")).forEach(f=>{const{buffer:Re,byteLength:We}=n.FS("readFile",f);m.push({buffer:Re,data:{type:"image/jpg",name:f}}),u+=We,n.FS("unlink",f)}),ae();const F=performance.now();if(te(0),!(m?.length&&u)){O(!1),$("idle"),R(),setTimeout(()=>K.A.error("Error transcoding video",k.Ln),500);return}$("uploading"),await De(m,u,i);const j=performance.now();k.Ts&&console.table({transcoding:`${Math.round((N-s)/1e3)}s`,read:`${Math.round(F-N)}ms`,upload:`${Math.round((j-F)/1e3)}s`,total:`${Math.round((j-s)/1e3)}s`}),O(!1);const de=`${window.motionpage.upload.uploadsUrl}/${window.motionpage.upload.seqFolder}/${i}/`,ce="jpg",ue=4,$e=String(Math.floor(Math.random()*m.length)).padStart(ue,"0");Ee({url:`${de}${$e}.${ce}`,dirDate:(0,g.sw)(),numFiles:m.length,folderName:i,numPadding:ue,baseUrl:de,ext:ce}),R(),setTimeout(()=>K.A.success("Image Sequence uploaded."),500)}function Le(){l?.current?.readyState===4&&(l?.current?.paused?(l.current.play(),se(!0)):(l?.current.pause(),se(!1)))}function Ae(){_e(s=>!s)}function ie(s,n){if(n=Number(n),!Number.isNaN(n)){if(y){const a=s==="width"?"height":"width";let i=Math.round(s==="width"?n/ee:n*ee);Number.isNaN(i)&&(i=0),w(r=>({...r,[s]:n,[a]:i}))}y||w(a=>({...a,[s]:n}))}}function Ce(){const{width:s,height:n}=E;if(!(!d?.width||!d?.height)){if(s>c||s>d.width){const a=Math.min(c,d.width);w(i=>({...i,width:a}))}if(n>c||n>d.height){const a=Math.min(c,d.height);w(i=>({...i,height:a}))}}}function R(){window.MOTIONPAGE&&window.MOTIONPAGE.modalState("")}function Ue(s){ve(Number(s))}const le=()=>Q(!1),oe=()=>{Ce(),Q(!0)};return(0,t.useEffect)(()=>{v&&"buffer"in v&&Oe(v)},[JSON.stringify(v)]),(0,e.jsxs)(e.Fragment,{children:[Z&&(0,e.jsxs)("div",{className:"inner-grid stretch",children:[(0,e.jsxs)("div",{className:"video-wrap relative col-span-8",children:[(0,e.jsx)("video",{onLoadedMetadata:ye,ref:l,src:Z,muted:!0}),(0,e.jsx)("div",{className:"play v-center h-center padding-m pointer",onClick:Le,children:Te?(0,e.jsx)(S.dz,{}):(0,e.jsx)(S.$0,{})})]}),(0,e.jsxs)("div",{className:"inner-grid gap-m col-span-4 padding-xl",children:[(0,e.jsxs)("div",{className:"bordered-block inner-grid",children:[(0,e.jsxs)("div",{className:"padding-l",children:[(0,e.jsx)("h2",{className:"bottom-xs",children:"Transcode a video"}),(0,e.jsx)("span",{className:"label small",children:"The video will get transcoded into an image sequence"})]}),(0,e.jsx)("hr",{}),(0,e.jsxs)("div",{className:(0,me.W)((G!=="idle"||ne)&&"disabled-section","transition"),children:[(0,e.jsxs)("div",{className:"padding-l inner-grid",children:[(0,e.jsxs)("div",{className:"col-span-5",children:[(0,e.jsx)("span",{className:"label bottom-s",children:"Width"}),(0,e.jsx)(h.Y2,{min:100,max:Math.min(c,d?.width??c),step:1,value:E.width,onChange:s=>ie("width",s),onFocus:le,onBlur:oe,allowMouseWheel:!0,onlyInteger:!0})]}),(0,e.jsx)("div",{className:"col-span-2 lock pointer",onClick:Ae,children:y?(0,e.jsx)(S.HE,{}):(0,e.jsx)(S.Sl,{})}),(0,e.jsxs)("div",{className:"col-span-5",children:[(0,e.jsx)("span",{className:"label bottom-s",children:"Height"}),(0,e.jsx)(h.Y2,{min:56,max:Math.min(c,d?.height??c),step:1,value:E.height,onChange:s=>ie("height",s),onFocus:le,onBlur:oe,allowMouseWheel:!0,onlyInteger:!0})]})]}),(0,e.jsx)("hr",{}),(0,e.jsx)("div",{className:"padding-m",children:(0,e.jsx)(h.GT,{name:"FPS",data:b,min:1,max:Math.round(x??p),slideMin:1,slideMax:Math.round(x??p),onChange:s=>_(Number(s)),toFixed:0})}),(0,e.jsx)("hr",{}),(0,e.jsx)("div",{className:"padding-m",children:(0,e.jsx)(h.GT,{name:"Quality",data:q,min:0,max:10,slideMax:10,onChange:Ue,toFixed:0})})]})]}),(0,e.jsxs)("div",{className:"bordered-block inner-grid table-layout",children:[(0,e.jsxs)("div",{className:"half row between padding-m",children:[(0,e.jsx)("span",{className:"label small",children:"Source:"}),(0,e.jsx)("b",{className:"label main",children:L===null?(0,e.jsx)(h.$j,{}):(0,e.jsxs)(e.Fragment,{children:[L?.width,(0,e.jsx)("span",{className:"label small",children:"x"}),L?.height]})})]}),(0,e.jsxs)("div",{className:"half row between padding-m",children:[(0,e.jsx)("span",{className:"label small",children:"Source FPS:"}),(0,e.jsx)("b",{className:"label main",children:A===null?(0,e.jsx)(h.$j,{}):A})]}),(0,e.jsxs)("div",{className:"half row between padding-m",children:[(0,e.jsx)("span",{className:"label small",children:"Total length:"}),(0,e.jsx)("b",{className:"label main",children:C===null?(0,e.jsx)(h.$j,{}):C})]}),(0,e.jsxs)("div",{className:"half row between padding-m",children:[(0,e.jsx)("span",{className:"label small",children:"Total images:"}),(0,e.jsx)("b",{className:"label main",children:U===null?(0,e.jsx)(h.$j,{}):U})]})]}),!J&&(0,e.jsx)(h.zx,{variation:"primary",loading:ne,onClick:Ie,disabled:!M?.buffer,children:"Transcode to Images"}),(0,e.jsx)(h.ko,{loading:J,progress:Fe,processType:G})]})]}),(0,e.jsx)("span",{className:"close-modal pointer",onClick:R,children:(0,e.jsx)("span",{className:"plus-icon remove"})})]})}const Me=je}}]);
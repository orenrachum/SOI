"use strict";(globalThis.webpackChunkmotionpage=globalThis.webpackChunkmotionpage||[]).push([[221],{8221:(Q,F,C)=>{C.r(F),C.d(F,{default:()=>P});var o=C(5893),a=C(7294),V=C(415),K=C(8034),U=C(4480);function H(e,s){var f=typeof Symbol=="function"&&e[Symbol.iterator];if(!f)return e;var m,d,v=f.call(e),g=[];try{for(;(s===void 0||s-- >0)&&!(m=v.next()).done;)g.push(m.value)}catch(c){d={error:c}}finally{try{m&&!m.done&&(f=v.return)&&f.call(v)}finally{if(d)throw d.error}}return g}typeof SuppressedError=="function"&&SuppressedError;var X=function(e){var s=e.children,f=e.prompt;return n.createElement("div",{className:"react-terminal-line react-terminal-input","data-terminal-prompt":f||"$"},s)},h=function(e){var s=e.children;return a.createElement("div",{className:"react-terminal-line"},s)},M;(function(e,s){s===void 0&&(s={});var f=s.insertAt;if(e&&typeof document<"u"){var m=document.head||document.getElementsByTagName("head")[0],d=document.createElement("style");d.type="text/css",f==="top"&&m.firstChild?m.insertBefore(d,m.firstChild):m.appendChild(d),d.styleSheet?d.styleSheet.cssText=e:d.appendChild(document.createTextNode(e))}})(`/**
 * Modfied version of [termynal.js](https://github.com/ines/termynal/blob/master/termynal.css).
 *
 * @author Ines Montani <ines@ines.io>
 * @version 0.0.1
 * @license MIT
 */
 .react-terminal-wrapper {
  width: 100%;
  background: #252a33;
  color: #eee;
  font-size: 18px;
  font-family: 'Fira Mono', Consolas, Menlo, Monaco, 'Courier New', Courier, monospace;
  border-radius: 4px;
  padding: 75px 45px 35px;
  position: relative;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
 }

.react-terminal {
  overflow: auto;
  display: flex;
  flex-direction: column;
}

.react-terminal-wrapper.react-terminal-light {
  background: #ddd;
  color: #1a1e24;
}

.react-terminal-window-buttons {
  position: absolute;
  top: 15px;
  left: 15px;
  display: flex;
  flex-direction: row;
  gap: 10px;
}

.react-terminal-window-buttons button {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  border: 0;
}

.react-terminal-window-buttons button.clickable {
  cursor: pointer;
}

.react-terminal-window-buttons button.red-btn {
  background: #d9515d;
}

.react-terminal-window-buttons button.yellow-btn {
  background: #f4c025;
}

.react-terminal-window-buttons button.green-btn {
  background: #3ec930;
}

.react-terminal-wrapper:after {
  content: attr(data-terminal-name);
  position: absolute;
  color: #a2a2a2;
  top: 5px;
  left: 0;
  width: 100%;
  text-align: center;
  pointer-events: none;
}

.react-terminal-wrapper.react-terminal-light:after {
  color: #D76D77;
}

.react-terminal-line {
  white-space: pre;
}

.react-terminal-line:before {
  /* Set up defaults and ensure empty lines are displayed. */
  content: '';
  display: inline-block;
  vertical-align: middle;
  color: #a2a2a2;
}

.react-terminal-light .react-terminal-line:before {
  color: #D76D77;
}

.react-terminal-input:before {
  margin-right: 0.75em;
  content: '$';
}

.react-terminal-input[data-terminal-prompt]:before {
  content: attr(data-terminal-prompt);
}

.react-terminal-wrapper:focus-within .react-terminal-active-input .cursor {
  position: relative;
  display: inline-block;
  width: 0.55em;
  height: 1em;
  top: 0.225em;
  background: #fff;
  -webkit-animation: blink 1s infinite;
          animation: blink 1s infinite;
}

/* Cursor animation */

@-webkit-keyframes blink {
  50% {
      opacity: 0;
  }
}

@keyframes blink {
  50% {
      opacity: 0;
  }
}

.terminal-hidden-input {
    position: fixed;
    left: -1000px;
}

/* .react-terminal-progress {
  display: flex;
  margin: .5rem 0;
}

.react-terminal-progress-bar {
  background-color: #fff;
  border-radius: .25rem;
  width: 25%;
}

.react-terminal-wrapper.react-terminal-light .react-terminal-progress-bar {
  background-color: #000;
} */
`),function(e){e[e.Light=0]="Light",e[e.Dark=1]="Dark"}(M||(M={}));var Z=function(e){var s=e.name,f=e.prompt,m=e.height,d=m===void 0?"600px":m,v=e.colorMode,g=e.onInput,c=e.children,j=e.startingInputValue,L=j===void 0?"":j,$=e.redBtnCallback,x=e.yellowBtnCallback,N=e.greenBtnCallback,B=H((0,a.useState)(""),2),p=B[0],D=B[1],I=H((0,a.useState)(0),2),J=I[0],A=I[1],T=(0,a.useRef)(null);(0,a.useEffect)(function(){D(L.trim())},[L]);var R=(0,a.useRef)(!1);(0,a.useEffect)(function(){R.current&&setTimeout(function(){var l;return(l=T?.current)===null||l===void 0?void 0:l.scrollIntoView({behavior:"auto",block:"nearest"})},500),R.current=!0},[c]),(0,a.useEffect)(function(){var l,i;if(g!=null){var r=[],y=function(t){var k=function(){var S;return(S=t?.querySelector(".terminal-hidden-input"))===null||S===void 0?void 0:S.focus()};t?.addEventListener("click",k),r.push({terminalEl:t,listener:k})};try{for(var b=function(t){var k=typeof Symbol=="function"&&Symbol.iterator,S=k&&t[k],O=0;if(S)return S.call(t);if(t&&typeof t.length=="number")return{next:function(){return t&&O>=t.length&&(t=void 0),{value:t&&t[O++],done:!t}}};throw new TypeError(k?"Object is not iterable.":"Symbol.iterator is not defined.")}(document.getElementsByClassName("react-terminal-wrapper")),u=b.next();!u.done;u=b.next())y(u.value)}catch(t){l={error:t}}finally{try{u&&!u.done&&(i=b.return)&&i.call(b)}finally{if(l)throw l.error}}return function(){r.forEach(function(t){t.terminalEl.removeEventListener("click",t.listener)})}}},[g]);var z=["react-terminal-wrapper"];return v===M.Light&&z.push("react-terminal-light"),a.createElement("div",{className:z.join(" "),"data-terminal-name":s},a.createElement("div",{className:"react-terminal-window-buttons"},a.createElement("button",{className:(x?"clickable":"")+" red-btn",disabled:!$,onClick:$}),a.createElement("button",{className:(x?"clickable":"")+" yellow-btn",disabled:!x,onClick:x}),a.createElement("button",{className:(N?"clickable":"")+" green-btn",disabled:!N,onClick:N})),a.createElement("div",{className:"react-terminal",style:{height:d}},c,g&&a.createElement("div",{className:"react-terminal-line react-terminal-input react-terminal-active-input","data-terminal-prompt":f||"$",key:"terminal-line-prompt"},p,a.createElement("span",{className:"cursor",style:{left:J+1+"px"}})),a.createElement("div",{ref:T})),a.createElement("input",{className:"terminal-hidden-input",placeholder:"Terminal Hidden Input",value:p,autoFocus:g!=null,onChange:function(l){D(l.target.value)},onKeyDown:function(l){var i,r,y;if(g){if(l.key==="Enter")g(p),A(0),D("");else if(["ArrowLeft","ArrowRight","ArrowDown","ArrowUp","Delete"].includes(l.key)){var b=l.currentTarget,u="",t=p.length-(b.selectionStart||0);i=t,r=0,y=p.length,t=i>y?y:i<r?r:i,l.key==="ArrowLeft"?(t>p.length-1&&t--,u=p.slice(p.length-1-t)):l.key==="ArrowRight"||l.key==="Delete"?u=p.slice(p.length-t+1):l.key==="ArrowUp"&&(u=p.slice(0));var k=function(S,O){var E=document.createElement("span");E.style.visibility="hidden",E.style.position="absolute",E.style.fontSize=window.getComputedStyle(S).fontSize,E.style.fontFamily=window.getComputedStyle(S).fontFamily,E.innerText=O,document.body.appendChild(E);var W=E.getBoundingClientRect().width;return document.body.removeChild(E),-W}(b,u);A(k)}}}}))},G=C(8300);const w=()=>(0,G.sw)().toString();function P(){const[e,s]=(0,a.useState)(!0),[f,m]=(0,a.useState)(""),[d,v]=(0,a.useState)([]),[g,c]=(0,a.useState)([(0,o.jsx)(h,{children:"Type :help"},"welcome-message")]),j=(0,U.sJ)(K.pK),L=(0,U.Zl)(V.d_),$=(0,U.Zl)(K.fU);function x(){$("")}function N(){if(JSON.stringify(d)===JSON.stringify([1,2])){v(i=>[...i,3]);return}x()}function B(){v(i=>[...i,2])}function p(){v(i=>[...i,1])}function D(){e||(v([]),s(!0))}function I(){if(e){if(JSON.stringify(d)!==JSON.stringify([1,2,3])){v([]);return}s(!1)}}function J(i){if(!i&&!f){c(r=>[...r,(0,o.jsx)(h,{children:"License key is missing!"},w())]);return}L(r=>({...r,license_status:"valid",license_key:i??f})),c(r=>[...r,(0,o.jsx)(h,{children:"License activated!"},w())])}function A(...i){if(!e)switch(i[0]){case"activate":case"enable":case"change":J(i[1]??"");break;case"deactivate":case"disable":{L(r=>(m(r.license_key),{...r,license_status:"invalid",license_key:""})),c(r=>[...r,(0,o.jsx)(h,{children:"License deactivated!"},w())]);break}case"left":{const{activations_left:r,site_count:y,license_limit:b}=j,t=r==="unlimited"?"unlimited":`${y} / ${b}`;c(k=>[...k,(0,o.jsx)(h,{children:`License left: ${t}`},w())]);break}default:c(r=>[...r,(0,o.jsx)(h,{children:"License available commands:"},w()),(0,o.jsx)(h,{children:"activate | deactivate | change | left"},`${w()}_2`)])}}function T(){if(e)return;const i=j.sites.map(({activated:r,is_local:y,license_id:b,site_id:u,site_name:t})=>(0,o.jsx)(h,{children:(0,o.jsxs)("div",{style:{display:"flex",alignItems:"center",gap:"0.5rem"},children:[t.replace(/\/$/,""),y==="1"&&(0,o.jsx)("span",{style:{borderRadius:"var(--radius)",padding:"0.2rem 0.4rem",border:"solid 1px var(--accent_1)",lineHeight:1,fontSize:"0.5rem",backgroundColor:"var(--accent_1_transp)",color:"var(--accent_1"},children:"local"})]},t)},u));c(r=>[...r,...i])}function R(...i){if(!e)switch(i[0]){case"enable":c(r=>[...r,(0,o.jsx)(h,{children:"Debug mode enabled!"},w())]);break;case"disable":c(r=>[...r,(0,o.jsx)(h,{children:"Debug mode disabled!"},w())]);break;default:c(r=>[...r,(0,o.jsx)(h,{children:"Debug available commands: enable | disable"},w())])}}const z={license:A,sites:T,lock:D,unlock:I,debug:R,clear:()=>c(i=>[i[0]]),close:x,exit:()=>x(),quit:()=>x()};function l(i){const[r,...y]=i.split(" "),b=z[r];if(b){b(...y);return}const u=`Unknown command: ${i}`;c(t=>[...t,(0,o.jsx)(h,{children:u},w())])}return(0,o.jsx)("div",{style:{width:700},children:(0,o.jsx)(Z,{name:"Motion.page : Services Menu",colorMode:M.Light,onInput:l,prompt:e?"$":"[sudo] $",redBtnCallback:N,yellowBtnCallback:B,greenBtnCallback:p,children:g})})}}}]);

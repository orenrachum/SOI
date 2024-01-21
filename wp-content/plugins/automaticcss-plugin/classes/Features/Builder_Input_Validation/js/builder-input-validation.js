import{waitForEl}from"../../../../assets/js/helpers.js?ver=2.7.1";import{Builder}from"../../../../assets/js/builder-apis.js?ver=2.7.1";!function(){let u=[{regex:/: *([^;]*?)(?=;|$)/g,prefix:": ",replaceWith:"<group1>"}],g=[{regex:/(\d+\.?\d*)ctr(?= |$)/g,replaceWith:(e,t)=>{var r=p_acssSettings_object.settings["root-font-size"]??100;return t[0]/(r/100*16)+"rem"}},{regex:/(var\(|var\( )(--[^\s\(\)\+\*\/]+)(\)| \))?/g,replaceWith:"<group2>"},{regex:/^calc\( *(.*?[^\)]) *$/g,replaceWith:"calc(<group1>)"},{regex:/--[^\s\(\)\+\*\/]+/g,replaceWith:"var(<match>)"},{regex:/^(?!calc\()([^\s]+ +[\*\+\/\-] +[^\s]+)/g,replaceWith:"calc(<match>)"},{regex:/^(?!calc\()(var\(\S+|\d+\.?\d*[a-z]{0,3}|\d+) *([\*\+]) *(var\(\S+|\d+\.?\d*[a-z]{0,3}|\d+)/g,replaceWith:"calc(<group1> <group2> <group3>)"},{regex:/^(calc\([^\s]+) *([\*\+\/]) *([^\s]+)/g,replaceWith:"<group1> <group2> <group3>"}],t=async()=>{let e,s="input[type='text']",n,l=".CodeMirror",c=(document.querySelector(".ng-scope")?(e="#oxygen-sidebar",n=".oxygen-classes-dropdown-input, .custom-attributes, .custom-js, .code-php"):document.querySelector(".brx-body.main")?(e="#bricks-panel",n="#bricks-panel-element-classes input"):(e=".interface-navigable-region.interface-interface-skeleton__sidebar",await waitForEl(e),l=""),(e,t,r)=>!e.closest(r)&&!!e.closest(t)),d=(e,t,a)=>(e.forEach(o=>{t=t.replace(o.regex,(e,...t)=>{if("function"==typeof o.replaceWith)return o.replaceWith(e,t);let r="";r=o.replaceWith.replace("<match>",e);for(let e=0;e<t.length;e++)r=r.replace(`<group${e+1}>`,t[e]);return a&&(r=d(a,r)),r=""+(o.prefix??"")+r+(o.suffix??"")})}),t);var t;document.querySelector(e).addEventListener("keydown",e=>{let t=e.target;var r,o;c(t,s,n)&&")"===e.key&&((r=(o=t.value).match(/\(/g))?r.length:0)===((r=o.match(/\)/g))?r.length:0)&&(e.preventDefault(),o=t.selectionStart,")"==t.value.charAt(o)&&(t.selectionStart=t.selectionStart+1),t.classList.add("acss-input-error"),setTimeout(()=>{t.classList.remove("acss-input-error")},500))}),document.querySelector(e).addEventListener("keydown",e=>{var t=e.target;c(t,s,n)&&"Enter"===e.key&&(e=d(g,t.value),Builder.setValue(e,t),Builder.setUnitToNone(t))}),""!=l&&document.querySelector(e).addEventListener("keydown",e=>{var t=document.querySelector(l),r=["grid-area:","grid-template:","grid-row:","grid-column:","grid:"];if(t&&t.contains(e.target)&&";"==e.key){var o=t.CodeMirror.doc.getValue(),a=t.CodeMirror.doc.getCursor(),s={...a,ch:0},n=t.CodeMirror.doc.indexFromPos(a),s=t.CodeMirror.doc.indexFromPos(s),c=o.slice(s,n);for(let e=0;e<r.length;e++){var i=r[e];if(c.trim().startsWith(i))return}";"===o.charAt(n)&&e.preventDefault();s=d(u,c,g);s.includes("var(--")&&s!==c&&(n=o.replace(c,s),t.CodeMirror.doc.setValue(n),t.CodeMirror.doc.setCursor(a.line,9999))}}),document.querySelector(e).addEventListener("input",function(t,r){let o;return(...e)=>{clearTimeout(o),o=setTimeout(()=>{t.apply(this,e)},r)}}(t=>{let r=null,e=null,o;if((o=""!=l?document.querySelector(l):o)?(r=o,e=r.CodeMirror.doc.getValue()):(r=t.target,c(r,s,n)&&(e=r.value)),null!==e){var t=e.match(/\(/g),t=t?t.length:0,a=e.match(/\)/g);if(t!==(a?a.length:0)){var t="Your input contains unbalanced brackets.",a=r;console.warn(t,a);let e=document.querySelector("#acss-error-message");e||((e=document.createElement("div")).id="acss-error-message"),e.style.top=a.getBoundingClientRect().bottom+"px",a.matches(l)&&(e.style.top=a.getBoundingClientRect().top-15+"px"),e.style.left=a.getBoundingClientRect().left+"px",document.querySelector("body").appendChild(e),e.innerHTML=t,e.classList.remove("acss-hidden"),setTimeout(()=>{e.classList.add("acss-hidden")},5e3),a.classList.add("acss-input-error")}else t=r,(a=document.querySelector("#acss-error-message"))&&(a.innerHTML="",a.classList.add("acss-hidden")),t.classList.remove("acss-input-error")}},750)),(t=document.createElement("style")).textContent=`
:root {
    --acss-red: #ff0000;
}

#acss-error-message {
    position: absolute;
    background-color: var(--acss-red);
    color: white;
    padding: .1em;
    font-size: .6em;
    font-weight: bold;
    z-index: 9999;
}

.acss-input-error {
    outline: 1px solid var(--acss-red) !important;
    transition: border-width 0.3s linear;
}

.acss-hidden {
    display: none;
}
`,document.head.appendChild(t)};document.addEventListener("DOMContentLoaded",()=>{let e;null==(e=null==(e=document.getElementById("bricks-builder-iframe"))?document.getElementById("ct-artificial-viewport"):e)?t():e.addEventListener("load",()=>{t()})})}();
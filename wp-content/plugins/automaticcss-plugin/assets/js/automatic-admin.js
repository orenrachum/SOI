!function(){function N(e){j("on");var t=new FormData;t.append("action","automaticcss_save_settings"),t.append("nonce",automatic_css_settings.nonce);let o={};e.forEach(t=>{if("submit"!==t.type&&"button"!==t.type){var s=t.getAttribute("name");let e=t.value;if("radio"===t.type){if(o.hasOwnProperty(s))return;t=document.querySelector("input[name='"+s+"']:checked");t&&t.value&&(e=t.value)}o[s]=e}});e=JSON.stringify(o);t.append("variables",e);let p=document.getElementById("acss-settings__response-message");console.log("Submitting form to Automatic.css backend"),fetch(automatic_css_settings.ajax_url,{method:"POST",credentials:"same-origin",body:t}).then(e=>e.json()).then(o=>{var[e=null]=[];if(null!==e){var e=document.getElementById(e);e&&((s=e.closest(".acss-var"))?(s.classList.remove("error"),(s=s.querySelector(".acss-var__error_message"))&&(s.innerHTML="")):(s=e.closest(".acss-group"))&&(s.classList.remove("error"),s=s.querySelector(".acss-group__error_message"))&&(s.innerHTML=""),(s=e.closest(".acss-accordion"))&&s.classList.remove("error"),s=e.closest(".acss-tab"))&&(e=s.id,(s=s.querySelectorAll(".acss-var.error"))&&0!==s.length||(s=document.querySelector(`a[href="#${e}"]`))&&s.classList.remove("error"))}else{e=document.querySelectorAll(".acss-var.error");if(0<e.length)for(var t of e)t.classList.remove("error");var s=document.querySelectorAll(".acss-group.error");if(0<s.length)for(var a of s)a.classList.remove("error");e=document.querySelectorAll(".acss-accordion.error");if(0<e.length)for(var r of e)r.classList.remove("error");e=document.querySelectorAll(".acss-var__error_message");if(0<e.length)for(var n of e)n.innerHTML="";e=document.querySelectorAll(".acss-group__error_message");if(0<e.length)for(var c of e)c.innerHTML="";e=document.querySelectorAll(".acss-nav a.error");if(0<e.length)for(var l of e)l.classList.remove("error")}if(console.log("Received response from Automatic.css backend",o),o.hasOwnProperty("success")){let e,t="",s=null;if(!0===o.success)t="success",e=o.data;else{t="error",e=o.data.hasOwnProperty("message")?o.data.message:o.data,s=1e4;var i=o.data.hasOwnProperty("errors")?o.data.errors:null;if(i&&0<Object.keys(i).length)for(const m in i){var u=i[m];{d=void 0;v=void 0;f=void 0;var d=m;var v=u;var f=document.getElementById(d);let e=null;e=f?f.closest(".acss-var"):document.querySelector("#acss-var-"+d);e?(e.classList.add("error"),e.classList.remove("hidden"),(d=e.querySelector(".acss-var__error_message"))&&(d.innerHTML=v)):(d=f.closest(".acss-group"))&&(d.classList.add("error"),d.classList.remove("hidden"),d=d.querySelector(".acss-group__error_message"))&&(d.innerHTML+=v+"<br/>");d=f.closest(".acss-accordion");d&&d.classList.add("error");v=f.closest(".acss-tab");v&&(d=v.id,f=document.querySelector(`a[href="#${d}"]`))&&f.classList.add("error")}}}h(p,e,t,s)}else e="Expecting a success status from the AJAX call, but got this instead: "+o.success,console.error(e,o),h(p,e,"error",0);j("off")}).catch(e=>{var t="Received an error from Automatic.css backend: "+e.message;console.error(t,e),h(p,t,"error",0),j("off")})}function h(e,t,s,o=5e3){o=o||5e3,e.innerHTML=`<p class="${s}">${t}</p>`,(e.style.bottom=0)<o&&setTimeout(function(){e.style.bottom="-1000px"},o)}function I(e,t){const s=document.getElementById(e)||document.querySelector("#acss-var-"+e);var e=t.field,o=document.getElementsByName(e);if(null===o||0===o.length)console.warn("Condition field element is null for "+e);else{var a,e=U(o[0]);r(s,t,e);for(a of o)a.addEventListener("change",function(e){e=String(e.target.value);r(s,t,e)})}}function r(e,t,s){var o=t.type||"show_only_if",a=String(t.value);if("show_only_if"===o){var r=document.getElementById(t.field_wrapper_id)||e.closest(".acss-var")||e,s=(s===a?(o=t.required||!0,r.classList.remove("hidden"),e.classList.remove("hidden"),o&&e.setAttribute("required","required")):(r.classList.add("hidden"),e.classList.add("hidden"),e.removeAttribute("required")),[".acss-group",".acss-accordion",".acss-panel"]);for(const c of s){var n=r.closest(c);n&&(n.querySelector(".acss-value__input:not(.hidden)")?n.classList.remove("hidden"):n.classList.add("hidden")),"acss-accordion"===c&&D(n)}}}function O(e,t){var s=K(t.value);t.dataset.h=s.h,t.dataset.s=s.s,t.dataset.l=s.l,e.innerHTML=`HSL: ${s.h}°, ${s.s}%, ${s.l}%`}function P(e,t){var s,o,t=K(t.value),a=e.getElementsByClassName("acss-value");3!==a.length?console.error("Shade group does not have three shade elements."):(e=e.querySelector(".acss-var__swatch"),s=a[0].querySelector(".acss-value__input").value,o=a[1].querySelector(".acss-value__input").value,t=(a=a[2].querySelector(".acss-value__input")).name.includes("-hover-")?Math.round(t.l*a.value):Math.round(a.value),e.dataset.h=s,e.dataset.s=o,e.dataset.l=t,e.style.backgroundColor=`hsl(${s}, ${o}%, ${t}%)`)}function D(e,t=null){e=e.closest(".acss-accordion__content-wrapper");e&&(null===t&&(t=e.scrollHeight),e.style.maxHeight=t+"px")}function R(e){const t=e.closest(".acss-value__input-wrapper").querySelector(".acss-reset-button");var s,o,a;t&&(s=J(e),o=U(e),a=(e=e.closest(".acss-group")||e.closest(".acss-var")).classList.contains("acss-group")?"acss-group--changed":"acss-var--changed",s!==o?(t.removeAttribute("disabled"),e.classList.add(a)):(e.classList.remove(a),t.setAttribute("disabled","disabled"),t.classList.add("rotate-reset"),setTimeout(function(){t.classList.remove("rotate-reset")},400)))}function F(e){history.pushState({},"",e),history.pushState({},"",e),history.back()}function j(e){show="on"===e;e=document.querySelector(".acss-loading-wrapper");show?e.classList.remove("hidden"):e.classList.add("hidden")}function U(e){switch(e.type){case"radio":var t=e.getAttribute("name"),t=document.querySelector("input[name='"+t+"']:checked");if(t&&t.value)return t.value;case"submit":case"button":return;default:return e.value}return null}function J(e){switch(e.type){case"radio":var t=e.closest(".acss-value__input-wrapper--toggle");if(t)return t.dataset.default;case"submit":case"button":return;default:return e.dataset.default}return null}function K(e){let t=0,s=0,o=0,a=(4===e.length?(t="0x"+e[1]+e[1],s="0x"+e[2]+e[2],o="0x"+e[3]+e[3]):7===e.length&&(t="0x"+e[1]+e[2],s="0x"+e[3]+e[4],o="0x"+e[5]+e[6]),t/=255,s/=255,o/=255,Math.min(t,s,o)),r=Math.max(t,s,o),n=r-a,c=0,l,i;return c=0==n?0:r===t?(s-o)/n%6:r===s?(o-t)/n+2:(t-s)/n+4,(c=Math.round(60*c))<0&&(c+=360),i=(r+a)/2,l=+(100*(0==n?0:n/(1-Math.abs(2*i-1)))).toFixed(1),i=+(100*i).toFixed(1),{h:Math.round(c),s:Math.round(l),l:Math.round(i)}}window.onload=function(){if(automatic_css_settings){var s,e,t,o,a;j("on"),Coloris({el:".acss-value__input--color",alpha:!1});for(const L of document.querySelectorAll(".acss-value__input--color")){var r=L.closest(".acss-panel");if(r){var r=r.id.includes("-contextual-colors"),n=r?L.closest(".acss-accordion__content"):L.closest(".acss-var").nextElementSibling.querySelector(".acss-accordion__content");if(n){const b=function(e,t){var s=document.createElement("div"),e=(s.classList.add("acss-var__hsl"),e.querySelector(".acss-group:first-of-type")||e.querySelector(".acss-var:first-of-type"));e&&(e.before(s),O(s,t));return s}(n,L);const q=(r?L.closest(".acss-accordion"):L.closest(".acss-var").nextElementSibling).querySelectorAll(".acss-group");for(const w of q){!function(e,t){var s=e.querySelector(".acss-group__title"),o=document.createElement("div");o.classList.add("acss-var__swatch"),s.appendChild(o),P(e,t)}(w,L);var c=w.getElementsByClassName("acss-value");if(3!==c.length)console.error("Shade group does not have three shade elements.");else for(const E of c)E.querySelector(".acss-value__input").addEventListener("change",function(e){P(w,L)})}L.addEventListener("change",function(e){O(b,e.target);var t=q,s=e.target,o=K(s.value);for(const n of t){var a=n.getElementsByClassName("acss-value");if(3!==a.length)return void console.error("Shade group does not have three shade elements.");var r=a[0].querySelector(".acss-value__input"),a=(r.value=o.h,r.dataset.default=o.h,a[1].querySelector(".acss-value__input"));a.value=o.s,a.dataset.default=o.s,R(r),R(a),P(n,s)}})}}}automatic_css_settings.tab_ids?(t=automatic_css_settings.tab_ids,s=document.getElementById("last-tab"),h=new URL(document.URL),e=h.hash,p=null,o=!1,""!==e?(e=e.replace("#",""),t.includes(e)||(o=!0)):null!==s&&""!==s.value&&t.includes(s.value)?p=s.value:o=!0,(p=o?"acss-tab-viewport":p)&&(h.hash=p,document.location.href=h.href,F(h.href)),e=".acss-nav a[href^='#"+h.hash.replace("#","")+"']",null!==(t=document.querySelector(e).parentNode||null)&&t.classList.add("active"),o=document.querySelectorAll(".acss-nav a[href^='#']"),[].forEach.call(o,function(t){t.addEventListener("click",function(e){e.preventDefault(),document.querySelectorAll(".acss-nav li.active").forEach(function(e){e.classList.remove("active")}),e.target.parentNode.classList.add("active"),s.value=new URL(t.href).hash.replace("#",""),F(t.href),document.querySelector(".acss-tabs-wrapper").scrollTop=0})})):console.error("Tab information not found in the automatic_css_settings object.");{const x=document.querySelectorAll(".acss-accordion");for(const A of x)A.querySelector(".acss-accordion__header").addEventListener("click",function(e){for(const s of x){var t=s.querySelector(".acss-accordion__header");const o=s.querySelector(".acss-accordion__content-wrapper");this!==t||s.classList.contains("acss-accordion--open")?(s.classList.remove("acss-accordion--open"),o.classList.remove("acss-accordion--visible"),D(o,0)):(s.classList.add("acss-accordion--open"),setTimeout(function(){o.classList.add("acss-accordion--visible")},400),D(o))}})}var l=automatic_css_settings.variables;for(const k in l){var i=l[k].type,u=l[k].no_reset||!1;document.getElementById(k);u||!function(e,n){const c=document.getElementsByName(e),t=document.getElementById(e);var s,l;if("toggle"===n){if(null===c||0===c.length)return console.warn("toggle_elements is null or empty for "+e);s=c[0].closest(".acss-value__input-wrapper"),l=s.querySelector(".acss-value__input-wrapper--toggle").dataset.default}else{if(null===t)return console.warn("variable_element is null for "+e);s=t.closest(".acss-value__input-wrapper"),l=t.dataset.default}const i=s.querySelector(".acss-reset-button"),o=(i.addEventListener("click",function(e){e.preventDefault(),this.classList.toggle("tooltip-active")}),i.closest(".acss-wrapper")),a=(o.addEventListener("click",function(e){e.target!==i&&i.classList.contains("tooltip-active")&&i.classList.toggle("tooltip-active")}),s.querySelector(".acss-tooltip__accept"));a.addEventListener("click",function(e){e.preventDefault();var t=!1,s=null;if("toggle"===n)for(const r of c){var o=!1;l===r.value&&(t=o=!0,s=r),r.checked=o}else{var e=e.target.closest(".acss-value__input-wrapper").querySelector(".acss-value__input"),a=U(e);const l=J(e);a!==l&&(e.value=l,t=!0,s=e,"color"===n)&&(a=e.dataset.default,e.closest(".clr-field").setAttribute("style",`color: ${a};`))}t&&(s.focus(),i.classList.remove("tooltip-active"),setTimeout(function(){i.classList.remove("rotate-reset")},400),s.dispatchEvent(new Event("change")))})}(k,i),l[k].hasOwnProperty("condition")&&(u=l[k].condition,I(k,u))}for(const M of document.querySelectorAll(".acss-divider")){var d=M.dataset.conditionField,v=M.dataset.conditionValue;d&&v&&(d={field:d,value:v},I(M.id,d))}for(const T of["xxl","xl","l","m","s","xs"]){var f=document.querySelector("#acss-var-header-height-"+T);if(f){var m=document.querySelector("#breakpoint-"+T);if(m){f=f.querySelector(".acss-var__title");if(f){let t=f.querySelector(".acss-var__breakpoint");t||((t=document.createElement("span")).classList.add("acss-var__breakpoint"),f.appendChild(t)),t&&(t.innerHTML=m.value),m.addEventListener("change",function(e){t.innerHTML=e.target.value})}}}}var p=document.querySelector("#button-pro-mode"),h=(p&&p.addEventListener("click",function(e){e.stopPropagation();var t;for(t of["option-owl","option-margin","option-padding","option-overlays","option-text-color","option-link-color","option-opacities","option-box-shadows","option-flip","option-object-fit","option-height","option-aspect-ratios","option-z-index","option-marker-colors","option-visibility","option-flex-grids","option-contextual-color-classes"]){var s=document.querySelector(`#${t}-on`),o=document.querySelector(`#${t}-off`);s.checked=!1,o.checked=!0,R(s)}}),(p=document.querySelector("#button-pro-mode-reset"))&&p.addEventListener("click",function(e){var t;e.stopPropagation();for(t of document.querySelector("#acss-panel-framework-settings").querySelectorAll(".acss-value__input-wrapper--toggle")){var s=t.dataset.toggleName,o=document.querySelector(`#${s}-on`),s=document.querySelector(`#${s}-off`);o.checked=!0,s.checked=!1,R(o)}}),document.querySelectorAll(".acss-value__input"));for(const C of h){var g=C.parentNode.querySelector(".acss-value__unit");C.nextElementSibling==g&&(C.style.borderRadius="var(--acss-border-radius) 0 0 var(--acss-border-radius)"),C.addEventListener("change",function(e){R(e.target)})}null===(a=document.getElementById("acss-settings-form"))?console.error("Missing #acss_settings_form element, can't hook submit event listener"):(a.addEventListener("submit",function(e){e.preventDefault(),N([...e.currentTarget.elements])}),document.addEventListener("keydown",function(e){(e.metaKey||e.ctrlKey)&&"s"===e.key&&(e.preventDefault(),N([...a.elements]))}));for(const B of document.querySelectorAll(".acss-copy-to-clipboard"))B.addEventListener("click",async e=>{if(navigator.clipboard){const t=e.target.dataset.content||"";if(""!==t)try{await navigator.clipboard.writeText(t),e.target.textContent="Copied to clipboard",setTimeout(()=>{e.target.textContent=t},1500)}catch(e){console.error("Failed to copy!",e)}}else console.warn("Clipboard API not available")});j("off");var _=document.getElementsByClassName("acss-info-tooltip__text");for(let e=0;e<_.length;e++){var y=_[e].textContent.length;_[e].style.width=26<y?"26ch":y-10+"ch"}for(const $ of document.querySelectorAll(".acss-value__unit")){var S=$.textContent.length;$.style.width=S+"ch"}for(const H of document.querySelectorAll(".acss-value__input--option"))H.style.fontFamily='"DM sans", sans-serif'}else console.error("automatic-css-plugin: automatic_css_settings is not defined")},document.addEventListener("DOMContentLoaded",function(e){for(const s of document.querySelectorAll("input.acss-value__input")){const o=s.nextElementSibling.classList.contains("acss-value__unit");var t=s.value.length;const a=s.classList.contains("acss-value__input--dropdown"),r=s.classList.contains("acss-value__input--color");!o||a||r?o||a||r?a&&(s.style.paddingRight="6ch"):s.style.width=t+6+"ch":s.style.width=t+2+"ch",s.addEventListener("input",()=>{var e=s.value.length;!o||a||r?o||a||r?a&&(s.style.paddingRight="6ch"):s.style.width=e+6+"ch":s.style.width=e+2+"ch"})}})}();
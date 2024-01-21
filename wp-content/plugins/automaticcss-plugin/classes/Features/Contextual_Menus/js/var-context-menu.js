import{Builder}from"../../../../assets/js/builder-apis.js?ver=2.7.1";import{waitForEl}from"../../../../assets/js/helpers.js?ver=2.7.1";import{updateDefaultBlueprint,defaultBlueprint}from"./helpers.js?ver=2.7.1";import{getVarContextMenu}from"./vars.js?ver=2.7.1";document.addEventListener("DOMContentLoaded",async()=>{let e;null==(e=null==(e=document.getElementById("bricks-builder-iframe"))?document.getElementById("ct-artificial-viewport"):e)?(await waitForEl(Builder.mainPanelSelector),p_acssVarCheatSheet().init()):e.addEventListener("load",()=>{p_acssVarCheatSheet().init()})});let p_acssVarCheatSheet=()=>{let s=document.createElement("style"),c,u=!1;return{init:()=>{let t={sections:[]},e=getVarContextMenu(),a=(e.sections.forEach(e=>{(e.filterIfFalse?.()??!0)&&(e.groups=e.groups.filter(e=>e.filterIfFalse?.()??!0),0<e.groups.length)&&t.sections.push(e)}),function(e){let d=document.createElement("div"),s=(d.classList.add("plstr-context-menu-wrapper"),d.classList.add("plstr-context-menu-wrapper--var"),document.createElement("div")),t=(s.classList.add("plstr-context-menu"),s.classList.add("plstr-context-menu--var"),document.createElement("div")),n=(t.classList.add("plstr-context-menu__header"),document.createElement("a")),o=(n.setAttribute("data-balloon-pos","right"),n.setAttribute("aria-label","open cheat-sheet"),n.setAttribute("data-balloon","open cheat-sheet"),n.href="https://automaticcss.com/cheat-sheet/",n.target="_blank",n.innerHTML=`<?xml version="1.0" encoding="UTF-8"?>
		<svg id="b" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 772.02 97.2">
		  <g id="c" data-name="Layer 1">
			<g>
			  <g>
				<path d="m59.08,33.51c-2.47-3.28-5.58-5.91-9.32-7.89-4.29-2.27-9.17-3.41-14.62-3.41-6.67,0-12.65,1.67-17.95,5-5.3,3.33-9.49,7.83-12.57,13.48C1.54,46.35,0,52.71,0,59.78s1.54,13.43,4.62,19.09c3.08,5.66,7.29,10.13,12.65,13.41,5.35,3.28,11.31,4.92,17.88,4.92,5.55,0,10.48-1.14,14.77-3.41,3.69-1.95,6.74-4.57,9.16-7.83v9.72h13.79V23.73h-13.79v9.79Zm-5.3,43.92c-4.14,4.6-9.6,6.89-16.36,6.89-4.54,0-8.59-1.06-12.12-3.18-3.54-2.12-6.29-5.02-8.26-8.71-1.97-3.69-2.95-7.95-2.95-12.8s.98-8.96,2.95-12.65c1.97-3.69,4.7-6.59,8.18-8.71,3.48-2.12,7.5-3.18,12.04-3.18s8.53,1.04,11.97,3.11c3.43,2.07,6.08,4.95,7.95,8.63,1.87,3.69,2.8,8,2.8,12.95,0,7.17-2.07,13.05-6.21,17.65Z" fill="#fff"/>
				<path d="m127.31,64.93c0,5.96-1.52,10.63-4.54,14.01-3.03,3.39-7.22,5.08-12.57,5.08-3.54,0-6.62-.76-9.24-2.27-2.63-1.51-4.62-3.71-5.98-6.59-1.36-2.88-2.05-6.29-2.05-10.23V23.73h-13.63v41.81c0,6.26,1.31,11.77,3.94,16.51,2.62,4.75,6.29,8.46,10.98,11.13,4.7,2.68,10.02,4.01,15.98,4.01s11.39-1.34,15.98-4.01c4.59-2.68,8.2-6.39,10.83-11.13,2.62-4.75,3.94-10.25,3.94-16.51V23.73h-13.63v41.2Z" fill="#fff"/>
				<path d="m167.43,36.14v43.34c0,1.42,1.15,2.57,2.57,2.57h11.06l-6.42,13.63h-15.7c-2.84,0-5.14-2.3-5.14-5.14v-54.4h-6.42v-12.41h6.42V6.42h13.63v17.31h19.27l-6.42,12.41h-12.85Z" fill="#fff"/>
				<path d="m236.9,27.13c-5.71-3.28-12.04-4.92-19.01-4.92s-13.43,1.67-19.09,5c-5.66,3.33-10.15,7.8-13.48,13.41-3.33,5.6-5,11.89-5,18.86s1.67,13.46,5,19.16c3.33,5.71,7.83,10.23,13.48,13.56,5.65,3.33,12.02,5,19.09,5s13.3-1.67,19.01-5c5.71-3.33,10.23-7.85,13.56-13.56,3.33-5.71,5-12.09,5-19.16s-1.67-13.28-5-18.94c-3.33-5.65-7.85-10.12-13.56-13.41Zm1.44,44.99c-2.02,3.69-4.8,6.59-8.33,8.71-3.54,2.12-7.57,3.18-12.12,3.18s-8.59-1.06-12.12-3.18c-3.54-2.12-6.31-5.02-8.33-8.71-2.02-3.69-3.03-7.9-3.03-12.65s1.01-8.79,3.03-12.42c2.02-3.63,4.8-6.49,8.33-8.56,3.53-2.07,7.57-3.11,12.12-3.11s8.56,1.04,12.04,3.11c3.48,2.07,6.26,4.92,8.33,8.56,2.07,3.64,3.11,7.78,3.11,12.42s-1.01,8.96-3.03,12.65Z" fill="#fff"/>
				<path d="m359.29,25.54c-4.14-2.22-8.84-3.33-14.09-3.33s-9.7,1.14-13.94,3.41c-4.24,2.27-7.7,5.4-10.38,9.39-.05.07-.09.15-.14.23-.06-.1-.1-.2-.16-.3-2.38-4.04-5.61-7.17-9.7-9.39-4.09-2.22-8.66-3.33-13.71-3.33s-9.8,1.14-13.94,3.41c-3.06,1.68-5.63,3.84-7.73,6.48v-8.38h-13.63v71.96h13.63v-43.63c0-3.53.81-6.56,2.42-9.09,1.61-2.52,3.74-4.49,6.36-5.91,2.62-1.41,5.6-2.12,8.94-2.12,4.95,0,9.06,1.54,12.35,4.62,3.28,3.08,4.92,7.3,4.92,12.65v43.48h13.63v-43.63c0-3.53.81-6.56,2.42-9.09,1.61-2.52,3.76-4.49,6.44-5.91,2.68-1.41,5.68-2.12,9.01-2.12,4.85,0,8.91,1.54,12.19,4.62,3.28,3.08,4.92,7.3,4.92,12.65v43.48h13.79v-46.2c0-5.65-1.24-10.5-3.71-14.54-2.48-4.04-5.78-7.17-9.92-9.39Z" fill="#fff"/>
				<path d="m438.43,33.51c-2.47-3.28-5.58-5.91-9.32-7.89-4.29-2.27-9.17-3.41-14.62-3.41-6.67,0-12.65,1.67-17.95,5-5.3,3.33-9.49,7.83-12.57,13.48-3.08,5.66-4.62,12.02-4.62,19.09s1.54,13.43,4.62,19.09c3.08,5.66,7.29,10.13,12.65,13.41,5.35,3.28,11.31,4.92,17.88,4.92,5.55,0,10.48-1.14,14.77-3.41,3.69-1.95,6.74-4.57,9.16-7.83v9.72h13.79V23.73h-13.79v9.79Zm-5.3,43.92c-4.14,4.6-9.6,6.89-16.36,6.89-4.54,0-8.59-1.06-12.12-3.18-3.54-2.12-6.29-5.02-8.26-8.71-1.97-3.69-2.95-7.95-2.95-12.8s.98-8.96,2.95-12.65c1.97-3.69,4.7-6.59,8.18-8.71,3.48-2.12,7.5-3.18,12.04-3.18s8.53,1.04,11.97,3.11c3.43,2.07,6.08,4.95,7.95,8.63,1.87,3.69,2.8,8,2.8,12.95,0,7.17-2.07,13.05-6.21,17.65Z" fill="#fff"/>
				<path d="m511.21,0c-2.53,0-4.6.83-6.21,2.5-1.62,1.67-2.42,3.71-2.42,6.14s.81,4.62,2.42,6.29c1.61,1.67,3.69,2.5,6.21,2.5s4.59-.83,6.21-2.5c1.61-1.67,2.42-3.76,2.42-6.29s-.81-4.47-2.42-6.14c-1.62-1.67-3.69-2.5-6.21-2.5Z" fill="#fff"/>
				<rect x="504.39" y="23.73" width="13.63" height="71.96" fill="#fff"/>
				<path d="m572.54,81.97c-3.18,1.36-6.69,2.05-10.53,2.05-4.54,0-8.59-1.06-12.12-3.18-3.54-2.12-6.31-5-8.33-8.63-2.02-3.64-3.03-7.83-3.03-12.57s1.01-8.94,3.03-12.57c2.02-3.63,4.8-6.49,8.33-8.56,3.53-2.07,7.57-3.11,12.12-3.11,3.84,0,7.35.68,10.53,2.05,3.18,1.36,5.88,3.36,8.1,5.98l9.09-9.09c-3.44-3.94-7.53-6.94-12.27-9.01-4.75-2.07-9.9-3.11-15.45-3.11-7.07,0-13.46,1.64-19.16,4.92-5.71,3.28-10.2,7.75-13.48,13.41-3.28,5.66-4.92,12.02-4.92,19.09s1.64,13.31,4.92,19.01c3.28,5.71,7.78,10.23,13.48,13.56,5.71,3.33,12.09,5,19.16,5,5.55,0,10.73-1.06,15.53-3.18,4.8-2.12,8.86-5.1,12.2-8.94l-8.94-9.09c-2.32,2.63-5.07,4.62-8.26,5.98Z" fill="#fff"/>
				<path d="m478.69,36.14v43.34c0,1.42,1.15,2.57,2.57,2.57h11.06l-6.42,13.63h-15.7c-2.84,0-5.14-2.3-5.14-5.14v-54.4h-6.42v-12.41h6.42V6.42h13.63v17.31h19.27l-6.42,12.41h-12.85Z" fill="#fff"/>
			  </g>
			  <path d="m772.02,33.61v52.18c0,6.3-5.11,11.4-11.4,11.4h-154.26c-8.47,0-13.98-8.91-10.21-16.49l25.99-52.18c1.93-3.87,5.88-6.32,10.21-6.32h128.27c6.3,0,11.4,5.11,11.4,11.4Z" fill="#fff"/>
			  <g>
				<path d="m619.88,84.44c-1.87,0-3.42-.65-4.65-1.95-1.23-1.3-1.85-2.85-1.85-4.65s.62-3.41,1.85-4.65c1.23-1.23,2.78-1.85,4.65-1.85s3.41.62,4.65,1.85c1.23,1.23,1.85,2.78,1.85,4.65s-.62,3.35-1.85,4.65c-1.23,1.3-2.78,1.95-4.65,1.95Z" fill="#08012f"/>
				<path d="m658.76,84.44c-4.66,0-8.88-1.1-12.64-3.3-3.76-2.2-6.73-5.18-8.89-8.94-2.17-3.76-3.25-7.95-3.25-12.54s1.08-8.86,3.25-12.59c2.16-3.73,5.13-6.68,8.89-8.84,3.76-2.16,7.98-3.25,12.64-3.25,3.66,0,7.06.68,10.19,2.05,3.13,1.37,5.83,3.35,8.1,5.95l-6,6c-1.47-1.73-3.25-3.05-5.35-3.95-2.1-.9-4.41-1.35-6.95-1.35-3,0-5.66.68-8,2.05-2.33,1.37-4.17,3.25-5.5,5.65-1.33,2.4-2,5.16-2,8.29s.67,5.9,2,8.3c1.33,2.4,3.16,4.3,5.5,5.7,2.33,1.4,5,2.1,8,2.1,2.53,0,4.85-.45,6.95-1.35,2.1-.9,3.91-2.21,5.45-3.95l5.9,6c-2.2,2.53-4.88,4.5-8.05,5.9-3.17,1.4-6.58,2.1-10.24,2.1Z" fill="#08012f"/>
				<path d="m699.04,84.44c-2.67,0-5.18-.35-7.55-1.05-2.37-.7-4.55-1.7-6.55-3-2-1.3-3.73-2.85-5.2-4.65l5.8-5.8c1.73,2.13,3.73,3.71,6,4.75,2.26,1.03,4.8,1.55,7.6,1.55s4.96-.48,6.5-1.45c1.53-.97,2.3-2.31,2.3-4.05s-.62-3.08-1.85-4.05c-1.23-.96-2.82-1.76-4.75-2.4-1.93-.63-3.98-1.26-6.15-1.9-2.17-.63-4.21-1.45-6.15-2.45-1.93-1-3.51-2.36-4.75-4.1-1.23-1.73-1.85-4.03-1.85-6.9s.7-5.35,2.1-7.45c1.4-2.1,3.35-3.71,5.85-4.85,2.5-1.13,5.51-1.7,9.04-1.7,3.73,0,7.05.65,9.94,1.95,2.9,1.3,5.28,3.25,7.15,5.85l-5.8,5.8c-1.33-1.73-2.98-3.06-4.95-4-1.97-.93-4.18-1.4-6.65-1.4-2.6,0-4.58.45-5.95,1.35-1.37.9-2.05,2.15-2.05,3.75s.6,2.83,1.8,3.7c1.2.87,2.78,1.6,4.75,2.2,1.96.6,4.01,1.22,6.15,1.85,2.13.63,4.16,1.48,6.1,2.55,1.93,1.07,3.51,2.5,4.75,4.3,1.23,1.8,1.85,4.16,1.85,7.1,0,4.46-1.58,8-4.75,10.59-3.17,2.6-7.41,3.9-12.74,3.9Z" fill="#08012f"/>
				<path d="m739.71,84.44c-2.67,0-5.18-.35-7.55-1.05-2.37-.7-4.55-1.7-6.55-3-2-1.3-3.73-2.85-5.2-4.65l5.8-5.8c1.73,2.13,3.73,3.71,6,4.75,2.26,1.03,4.8,1.55,7.6,1.55s4.96-.48,6.5-1.45c1.53-.97,2.3-2.31,2.3-4.05s-.62-3.08-1.85-4.05c-1.23-.96-2.82-1.76-4.75-2.4-1.93-.63-3.98-1.26-6.15-1.9-2.17-.63-4.21-1.45-6.15-2.45-1.93-1-3.51-2.36-4.75-4.1-1.23-1.73-1.85-4.03-1.85-6.9s.7-5.35,2.1-7.45c1.4-2.1,3.35-3.71,5.85-4.85,2.5-1.13,5.51-1.7,9.04-1.7,3.73,0,7.05.65,9.94,1.95,2.9,1.3,5.28,3.25,7.15,5.85l-5.8,5.8c-1.33-1.73-2.98-3.06-4.95-4-1.97-.93-4.18-1.4-6.65-1.4-2.6,0-4.58.45-5.95,1.35-1.37.9-2.05,2.15-2.05,3.75s.6,2.83,1.8,3.7c1.2.87,2.78,1.6,4.75,2.2,1.96.6,4.01,1.22,6.15,1.85,2.13.63,4.16,1.48,6.1,2.55,1.93,1.07,3.51,2.5,4.75,4.3,1.23,1.8,1.85,4.16,1.85,7.1,0,4.46-1.58,8-4.75,10.59-3.17,2.6-7.41,3.9-12.74,3.9Z" fill="#08012f"/>
			  </g>
			</g>
		  </g>
		</svg>`,document.createElement("div")),r=(o.classList.add("plstr-close-button"),o.innerHTML=`<svg width="13" height="13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g opacity="0.5">
<path d="M17.25 17.25L6.75 6.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.25 6.75L6.75 17.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</g>
</svg>`,document.createElement("div")),l=(r.classList.add("plstr-info-box"),document.createElement("div")),a=(l.classList.add("plstr-info-icon"),l.innerHTML=`<svg width="13" height="13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g opacity="0.5">
<path d="M9.1875 10.3125H12.1875V18.375" stroke="white" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8.76562 18.5625H15.2344" stroke="white" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round"/>
<path d="M12 7.5C11.7033 7.5 11.4133 7.41203 11.1666 7.24721C10.92 7.08238 10.7277 6.84811 10.6142 6.57403C10.5006 6.29994 10.4709 5.99834 10.5288 5.70737C10.5867 5.41639 10.7296 5.14912 10.9393 4.93934C11.1491 4.72956 11.4164 4.5867 11.7074 4.52882C11.9983 4.47094 12.2999 4.50065 12.574 4.61418C12.8481 4.72771 13.0824 4.91997 13.2472 5.16665C13.412 5.41332 13.5 5.70333 13.5 6C13.5 6.39783 13.342 6.77936 13.0607 7.06066C12.7794 7.34197 12.3978 7.5 12 7.5Z" fill="white"/>
</g>
</svg>`,document.createElement("div")),c=(a.innerHTML=`
        <p>The most fitting accordions will be expanded. If not, click to open/close the accordions.</p>
        <p>Hover over the variable to get a preview, left click to apply the variable.</p>
        <p>Hold <kbd>CTRL</kbd> (Windows) or <kbd>CMD</kbd> (Mac) while hovering to expand more options (e.g. transparent colors).</p>
        `,a.classList.add("plstr-hover-info"),r.appendChild(a),r.appendChild(l),t.appendChild(n),t.appendChild(r),t.appendChild(o),s.appendChild(t),document.createElement("div"));return c.classList.add("plstr-context-menu__section-wrapper"),e.sections.forEach(e=>{let t=document.createElement("div");t.classList.add("plstr-context-menu__section"),t.setAttribute("data-tags",e.tags.join(" "));var n=document.createElement("div");n.classList.add("plstr-context-menu__heading"),n.textContent=e.heading,n.addEventListener("click",e=>{t.classList.toggle("plstr-context-menu__section--open"),e.stopPropagation()}),t.appendChild(n),e.groups.forEach(c=>{let i=document.createElement("div");var e;i.classList.add("plstr-context-menu__group"),c.heading&&((e=document.createElement("div")).classList.add("plstr-context-menu__group-heading"),e.textContent=c.heading,t.appendChild(e)),c.classes&&i.classList.add(...c.classes),c.groupItems.forEach((e,a)=>{var t=document.createElement("div");if(t.classList.add("plstr-context-menu__group-item"),t.style=e.options[0].inlineStyles,t.innerHTML=e.options[0].displayText??"",e.options){let l=document.createElement("div");l.classList.add("plstr-context-menu__options-wrapper"),t.addEventListener("mouseenter",e=>{l.style.display="flex"}),t.addEventListener("mouseleave",e=>{l.style.display="none"}),1<e.options.length&&t.classList.add("plstr-context-menu__group-item--expandable"),e.options.forEach(e=>{var t=document.createElement("div");t.classList.add("plstr-context-menu__option"),t.addEventListener("click",e=>{u=!0,d.style.display="none",e.stopPropagation()}),t.style=e.inlineStyles,t.setAttribute("data-var",e.value),t.innerHTML=e.displayText??"";let n=3;1===c.groupItems.length?(n=1,i.style.setProperty("--plaster-grid-columns",n)):2===c.groupItems.length||10<=e.displayText.length?(n=2,i.style.setProperty("--plaster-grid-columns",n)):0===e.displayText.length&&(n=8);let s,o,r;r=document.querySelector("html.ng-scope")?(s="up-left",o="up","up-right"):(s="top-left",o="top","top-right"),e.tooltip&&(t.setAttribute("data-balloon",e.tooltip),t.setAttribute("aria-label",e.tooltip),e=a+1,1!==c.groupItems.length&&e%n==0?t.setAttribute("data-balloon-pos",r):e%n==1?t.setAttribute("data-balloon-pos",s):t.setAttribute("data-balloon-pos",o)),l.appendChild(t)}),t.appendChild(l)}i.appendChild(t)}),t.appendChild(i)}),c.appendChild(t),s.appendChild(c),d.appendChild(s)}),d}(e=t));a.style.display="none",document.body.appendChild(a),(Builder.getIframeDocument().body?Builder.getIframeDocument().body:Builder.getIframeDocument()).appendChild(s),s.id="preview-style",a.addEventListener("contextmenu",e=>{e.preventDefault()}),document.querySelector(Builder.mainPanelSelector).addEventListener("contextmenu",e=>{null!=e.target.closest(Builder.getExcludedInputSelectors())||null==e.target.closest("input")&&!e.target.classList.contains("color-value-tooltip")||e.preventDefault()}),document.querySelector(Builder.mainPanelSelector).addEventListener("contextmenu",t=>{if(2!=t.button)return;if(null!=t.target.closest(Builder.mainInputSelector))return;if(null!=t.target.closest(Builder.getExcludedInputSelectors()))return;let e;if(t.target.classList.contains("color-value-tooltip"))document.querySelector(".raw input")||t.target.click(),setTimeout(()=>{c=document.querySelector(".raw input")},200),a.style.display="flex",e=t.target;else if(t.target.classList.contains("gblocks-color-component__toggle-indicator"))a.style.display="flex",e=t.target,c=t.target;else{if(null===t.target.closest("input"))return;c=Builder.getCurrentInput(t.target),Builder.setUnitToNone(c),e=c}t.preventDefault();var n=e.getBoundingClientRect().right,s=e.getBoundingClientRect().bottom,o=(document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section").forEach(e=>{e.style.order="",e.classList.remove("plstr-context-menu__section--open")}),(e,...t)=>{for(var n of t)if(e.includes(n))return!0;return!1});if(Builder.getVarSections)Builder.getVarSections(t.target).forEach((e,t)=>{document.querySelectorAll(`.plstr-context-menu--var .plstr-context-menu__section[data-tags*='${e}'`).forEach(e=>{e.style.order=t-10,e.classList.add("plstr-context-menu__section--open")})});else{let e=Builder.getCurrentInputOption(t.target);t=Builder.getCurrentElementId();"string"==typeof e&&(o(e=e.toLowerCase(),"padding","margin","gap","top","left","right","bottom","gap")?(document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='space'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}),o(e,"gap")&&document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='gap'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}),"section"==Builder.getIframeDocument().querySelector(t)?.tagName.toLowerCase()&&document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='section'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}),("button"==Builder.getIframeDocument().querySelector(t)?.tagName.toLowerCase()||Builder.getIframeDocument().querySelector(t).classList.contains("bricks-button")||Builder.getIframeDocument().querySelector(t).classList.contains("ct-link-button"))&&document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='button'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")})):o(e,"border-width","border-size")?(document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='border-size'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}),"section"==Builder.getIframeDocument().querySelector(t).tagName.toLowerCase()&&document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='section'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")})):o(e,"font-size","line-height","letter-spacing","word-spacing")?document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='font-size'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}):o(e,"width")?document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='width'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}):o(e,"height")?document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='height'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}):o(e,"color","background","raw","_gradient","_boxshadow","_border")?document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='color'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}):o(e,"border-radius","radius")?(document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='radius'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}),("button"==Builder.getIframeDocument().querySelector(t).tagName.toLowerCase()||Builder.getIframeDocument().querySelector(t).classList.contains("bricks-button")||Builder.getIframeDocument().querySelector(t).classList.contains("ct-link-button"))&&document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='button'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")})):o(e,"grid")&&document.querySelectorAll(".plstr-context-menu--var .plstr-context-menu__section[data-tags*='grid'").forEach(e=>{e.style.order="-1",e.classList.add("plstr-context-menu__section--open")}))}a.style.display="flex",a.firstElementChild.style.left=n-275<=0?"0px":n-275+"px";t=a.firstElementChild.getBoundingClientRect().height;s+t>window.innerHeight?a.firstElementChild.style.top=window.innerHeight-t+"px":a.firstElementChild.style.top=s+"px";let r=e=>{null==e.target.closest(".plstr-close-button")&&(null!=e.target.closest(".plstr-context-menu--var")||2==e.button||e.target.classList.contains("color-value-tooltip"))?document.addEventListener("mousedown",r,{once:!0}):l()},l=()=>{document.querySelectorAll(".plstr-context-menu__section").forEach(e=>{e.classList.remove("plstr-context-menu__section--open")}),a.style.display="none"};document.addEventListener("mousedown",r,{once:!0}),document.addEventListener("keydown",e=>{"Escape"===e.key&&l()},{once:!0}),Builder.getIframeDocument().body?.addEventListener("click",()=>{a.style.display="none"},{once:!0})}),(async()=>{let e=document.querySelectorAll(".plstr-context-menu__option");const n=e=>{Builder.removeValuePreview(c,s)};e.forEach(e=>{e.addEventListener("click",e=>{let t=e.target.getAttribute("data-var");if(!t)return;Builder.removeValuePreview(c,s);e.target.removeEventListener("mouseleave",n);Builder.setValue(t,c)});e.addEventListener("mouseenter",e=>{let t=e.target.getAttribute("data-var");if(!t)return;Builder.displayValuePreview(t,c,s);e.target.addEventListener("mouseleave",n,{once:true})})})})()}}};
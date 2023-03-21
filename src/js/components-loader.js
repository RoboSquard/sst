!function(o){"function"==typeof define&&define.amd?define(o):o()}(function(){"use strict";function n(n,o){var e=Object.keys(n);if(Object.getOwnPropertySymbols){var t=Object.getOwnPropertySymbols(n);o&&(t=t.filter(function(o){return Object.getOwnPropertyDescriptor(n,o).enumerable})),e.push.apply(e,t)}return e}function e(i){for(var o=1;o<arguments.length;o++){var s=null!=arguments[o]?arguments[o]:{};o%2?n(s,!0).forEach(function(o){var n,e,t;n=i,t=s[e=o],e in n?Object.defineProperty(n,e,{value:t,enumerable:!0,configurable:!0,writable:!0}):n[e]=t}):Object.getOwnPropertyDescriptors?Object.defineProperties(i,Object.getOwnPropertyDescriptors(s)):n(s).forEach(function(o){Object.defineProperty(i,o,Object.getOwnPropertyDescriptor(s,o))})}return i}function o(o){return function(o){if(Array.isArray(o)){for(var n=0,e=new Array(o.length);n<o.length;n++)e[n]=o[n];return e}}(o)||function(o){if(Symbol.iterator in Object(o)||"[object Arguments]"===Object.prototype.toString.call(o))return Array.from(o)}(o)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}function r(o,n,e){var t=2<arguments.length&&void 0!==e?e:"0 0 32 32",i=function(o){return"#mosaic_icon_".concat(o)}(function(o){return o.substring(o.lastIndexOf("/svg/")+5,o.lastIndexOf(".")).replace(/\//g,"_")}(o));if(!function(o){var n=document.getElementById("page-svg-icons");return!!n&&null!=n.querySelector(o)}(i)){var s=function(o){var n=document.createElement("div");return n.innerHTML=o.trim(),n.firstChild}('<svg xmlns="http://www.w3.org/2000/svg">'.concat(n,"</svg>")),a=document.createElementNS("http://www.w3.org/2000/svg","symbol");a.setAttributeNS(null,"id",i.substring(1)),a.setAttributeNS(null,"viewBox",t),function(o,n){for(;o.hasChildNodes();)n.appendChild(o.removeChild(o.firstChild))}(s,a),function(o){var n=document.getElementById("page-svg-icons");n?n.appendChild(o):((n=document.createElementNS("http://www.w3.org/2000/svg","svg")).setAttributeNS(null,"id","page-svg-icons"),n.setAttributeNS(null,"style","position:absolute;height:0;width:0;"),n.appendChild(o),document.body.insertBefore(n,document.body.firstChild))}(a)}}function a(n,e){e&&Object.keys(e).forEach(function(o){n.setAttribute(o,e[o])})}function c(o){return!(0<arguments.length&&void 0!==o)||o?"":"?".concat(Date.now())}function d(o,n,e){var t=1<arguments.length&&void 0!==n?n:null,i=!(2<arguments.length&&void 0!==e)||e,s=document.createElement("script");return s.setAttribute("type","text/javascript"),s.setAttribute("src","".concat(o).concat(c(i))),a(s,t),s}function m(o,n,e){var t=1<arguments.length&&void 0!==n?n:null,i=!(2<arguments.length&&void 0!==e)||e,s=document.createElement("link");return s.setAttribute("rel","stylesheet"),s.setAttribute("type","text/css"),s.setAttribute("href","".concat(o).concat(c(i))),a(s,t),s}function t(){window.mosaic=window.mosaic||{},window.mosaic.components=window.mosaic.components||{},window.mosaic.components.libraries=window.mosaic.components.libraries||{};for(var o=arguments.length,n=new Array(o),e=0;e<o;e++)n[e]=arguments[e];var t=n.map(function(o){return function(o){var c=o;if(Array.isArray(o)&&2===o.length&&o[0].test&&(o[0].test()?"script"===(c=o[0]).is&&/\.esm\.js/.test(c.url)&&(c.attributes={type:"module"}):c=o[1]),!c.url||!c.is)return Promise.reject(new Error("No url or type given!"));if(window.mosaic.components.libraries[c.url])return window.mosaic.components.libraries[c.url].promise;var n=new Promise(function(s,a){function o(o){o.error||o.type&&"error"===o.type?a(o.error):(window.mosaic.components.libraries[c.url].hasLoaded=!0,s())}if("script"===c.is){var n=d(c.url,c.attributes,c.cache);n.onerror=o,n.onload=o,document.body.appendChild(n)}else if("style"===c.is){var e=m(c.url,c.attributes,c.cache);if(c.fallback){var t=m(c.fallback,c.attributes,c.cache),i=function(o){o.error||o.type&&"error"===o.type?document.getElementsByTagName("head")[0].appendChild(t):(window.mosaic.components.libraries[c.url].hasLoaded=!0,s())};e.onerror=i,e.onload=i,t.onerror=o,t.onload=o,document.getElementsByTagName("head")[0].appendChild(e)}else e.onerror=o,e.onload=o,document.getElementsByTagName("head")[0].appendChild(e)}else"svg"===c.is&&window.fetch(c.url).then(function(o){return o.text()}).then(function(o){var n=o.match(/<svg.*?viewBox="([^"]+)".*?>/im),e=o.match(/<svg.*?>([\s\S]+?)<\/svg>/m);if(e&&2===e.length){var t=e[1],i="0 0 32 32";2===n.length&&(i=n[1]),r(c.url,t,i),s()}else a(new Error("Unable to load SVG contents for icon: ".concat(c.url)))})});return window.mosaic.components.libraries[c.url]={type:c.is,hasLoaded:!1,promise:n},n}(o)});return Promise.all(t)}function i(o,n,e){var t,i=2<arguments.length&&void 0!==e?e:{},s=Object.assign({},{bubbles:!0,cancelable:!0,composed:!1},i);return"composed"in CustomEvent.prototype?t=new CustomEvent(n,s):((t=document.createEvent("CustomEvent")).initCustomEvent(n,s.bubbles,s.cancelable,s.detail),Object.defineProperty(t,"composed",{value:s.composed})),o.dispatchEvent(t)}function s(o){try{return new Function(o)(),!0}catch(o){return!1}}window.mosaic=window.mosaic||{},window.mosaic.colors={primary:"#1d428a",secondary:"#414141",success:"#00823b",info:"#007377",warning:"#ffc107",danger:"#dc3545",light:"#f8f8f8",dark:"#242424",advanced:"#e9510e","advanced-sky":"#009FDF","advanced-green":"#009A44",muted:"#767676"};var u={class:function(){return s("class Something {}")},const:function(){return s("const a = 1")},let:function(){return s("let a = 1")},arrowFunction:function(){return s("var f = x => 1")},spread:function(){return s("Math.max(...[ 5, 10 ])")},symbols:function(){return"undefined"!=typeof Symbol&&"function"==typeof Symbol.for},customElement:function(){return s('window.customElements.define("mosaic-is-es6-and-custom-elements-test", class extends HTMLElement {})')},promise:function(){return void 0!==window.Promise&&"function"==typeof window.Promise.resolve},fetch:function(){return void 0!==window.fetch&&void 0!==window.Headers&&void 0!==window.Request&&void 0!==window.Response},typeModule:function(){return"noModule"in HTMLScriptElement.prototype}};function l(){for(var o=arguments.length,n=new Array(o),e=0;e<o;e++)n[e]=arguments[e];return function(e){for(var o=arguments.length,n=new Array(1<o?o-1:0),t=1;t<o;t++)n[t-1]=arguments[t];return n.every(function(o){var n=e[o];return n&&"function"==typeof n?n():(console.warn("No detection available for '".concat(o,"'")),!1)})}.apply(void 0,[u].concat(n))}function w(o){return window.mosaic.config.vendorPath?"".concat(window.mosaic.config.vendorPath).concat(o):"".concat(h[p].vendor).concat(o)}var p="production",f="https://cdn.svc.oneadvanced.com",h={development:{component:"../../tmp",icon:"../../node_modules/@advanced/mosaic-icons",vendor:"../../node_modules",theme:"../../tmp"},production:{component:"".concat(f,"/mosaic-components"),icon:"".concat(f,"/mosaic-icons"),vendor:"".concat(f,"/mosaic-components/vendor"),theme:"".concat(f,"/mosaic-components")}},g=function(o){return window.mosaic.config&&window.mosaic.config.componentPath?"".concat(window.mosaic.config.componentPath).concat(o):"".concat(h[p].component).concat(o)};function v(){var o={componentPath:null,iconPath:null,vendorPath:null,themePath:null,isStorybook:!1,useLayout:!1,noControls:!1,noData:!1,noForms:!1,noMessaging:!1,noPages:!1,noTools:!1,noPageLoader:!1,componentBundle:null,extensionsPath:null};return window.mosaicConfigure?o="function"==typeof window.mosaicConfigure?e({},o,{},window.mosaicConfigure()):e({},o,{},window.mosaicConfigure):(window.mosaicComponentPath&&(o.componentPath=window.mosaicComponentPath),window.mosaicIconPath&&(o.iconPath=window.mosaicIconPath),window.mosaicVendorPath&&(o.vendorPath=window.mosaicVendorPath),window.mosaicThemePath&&(o.themePath=window.mosaicThemePath),window.mosaicIsStorybook&&(o.isStorybook=!0),window.mosaicUseLayout&&(o.useLayout=!0),window.mosaicNoControls&&(o.noControls=!0),window.mosaicNoData&&(o.noData=!0),window.mosaicNoForms&&(o.noForms=!0),window.mosaicNoMessaging&&(o.noMessaging=!0),window.mosaicNoPages&&(o.noPages=!0),window.mosaicNoTools&&(o.noTools=!0),window.mosaicNoPageLoader&&(o.noPageLoader=!0),window.mosaicComponentBundle&&Array.isArray(window.mosaicComponentBundle)&&(o.componentBundle=window.mosaicComponentBundle),window.mosaicExtensionsPath&&(o.extensionsPath=window.mosaicExtensionsPath)),o.componentPath||(o.componentPath=h[p].component),o.vendorPath||(o.vendorPath=h[p].vendor),o.iconPath||(o.iconPath=h[p].icon),o.themePath||(o.themePath=h[p].theme),o}function y(){var o,n=Object.keys(window.mosaic.components.loader.tags).length,e=0;for(o in window.mosaic.components.loader.tags)window.mosaic.components.loader.tags.hasOwnProperty(o)&&window.mosaic.components.loader.tags[o].depsLoaded&&++e;e===n&&(window.mosaic.components.loader.loaded=!0,document.removeEventListener("mosaicComponentDependenciesLoaded",b),window.mosaic.componentsReady=!0,i(document,"mosaicComponentsReady"))}function b(o){window.mosaic.components.loader.tags[o.detail.tag]&&!window.mosaic.components.loader.tags[o.detail.tag].depsLoaded&&(window.mosaic.components.loader.tags[o.detail.tag].depsLoaded=!0,y())}function P(){for(var o={},n=document.body.getElementsByTagName("*"),e=0;e<n.length;e++){var t=n[e].tagName.toLowerCase();/mosaic-[a-z-]+/.test(t)&&!o[t]&&(o[t]={depsLoaded:!1})}window.mosaic=window.mosaic||{},window.mosaic.components=window.mosaic.components||{},window.mosaic.components.loader=window.mosaic.components.loader||{},window.mosaic.components.loader.loaded=!1,window.mosaic.components.loader.tags=o,0===Object.keys(o).length&&function(o){var t=0<arguments.length&&void 0!==o?o:1e4;return new Promise(function(n,e){setTimeout(function o(){window.mosaic&&window.mosaic.messages?n():(t-=100)<0?e(new Error("Timed out waiting for i18n messages!")):setTimeout(o,100)},100)})}().then(function(){return window.mosaic.messages.waitForTranslator()}).then(function(){y()}),document.addEventListener("mosaicComponentDependenciesLoaded",b),window.mosaic.config.noPageLoader||document.addEventListener("mosaicComponentsReady",function(){var o=function(o){var n=document.createElement("style");return n.type="text/css",n.styleSheet?n.styleSheet.cssText=o:n.appendChild(document.createTextNode(o)),n}("body:before{display:none}");setTimeout(function(){document.getElementsByTagName("head")[0].appendChild(o),i(document,"mosaicPageLoaderDone")},500)})}function C(o){var n=[],e=v();return e.isStorybook?(n.push(g("/layout.".concat(o,".js"))),n.push(g("/scaffold.".concat(o,".js")))):e.useLayout?n.push(g("/layout.".concat(o,".js"))):n.push(g("/scaffold.".concat(o,".js"))),n.push(g("/js/messages.".concat(o,".js"))),e.componentBundle&&n.push("esm"===o?e.componentBundle[0]:e.componentBundle[1]),e.noControls||n.push(g("/controls.".concat(o,".js"))),e.noData||n.push(g("/data.".concat(o,".js"))),e.noForms||n.push(g("/forms.".concat(o,".js"))),e.noMessaging||n.push(g("/messaging.".concat(o,".js"))),e.noPages||n.push(g("/pages.".concat(o,".js"))),e.noTools||n.push(g("/tools.".concat(o,".js"))),n.push(g("/core.".concat(o,".js"))),e.extensionsPath&&n.push("".concat(e.extensionsPath,"/components.").concat(o,".js")),n}function j(){return l("symbols","class","const","arrowFunction","typeModule")}function E(n){if(-1<["controls","data","forms","layout","messaging","pages","scaffold"].indexOf(n))return t([{url:g("/".concat(n,".esm.js")),is:"script",test:j},{url:g("/".concat(n,".umd.js")),is:"script"}]).then(function(){var o="no".concat(function(o){return"".concat(o.toLowerCase().substring(0,1).toUpperCase()).concat(o.toLowerCase().substring(1))}(n));window.mosaic.config[o]=!1})}function L(){window.mosaic=window.mosaic||{},window.mosaic.componentsLoaded=!1,window.mosaic.componentsReady=!1,window.mosaic.messages.initialiseMessages().then(function(){window.mosaic.componentsLoaded=!0,i(document,"mosaicComponentsLoaded")})}function O(){window.mosaic=window.mosaic||{},window.mosaic.loadBundle=E,window.mosaic.config=v(),P(),l("symbols","class","const","arrowFunction","customElement","typeModule")?t.apply(void 0,N.concat(o(C("esm").map(function(o){return{url:o,is:"script",attributes:{type:"module"}}})))).then(L):t.apply(void 0,N).then(function(){return t({url:w("/document-register-element/build/document-register-element.js"),is:"script"},{url:w("/@advanced/mosaic-polyfills/dist/polyfills.umd.js"),is:"script"})}).then(function(){return t.apply(void 0,o(C("umd").map(function(o){return{url:o,is:"script"}})))}).then(L)}var N=[];l("promise","fetch")?O():function(n,e){if(window.mosaic=window.mosaic||{},window.mosaic.components=window.mosaic.components||{},window.mosaic.components.libraries=window.mosaic.components.libraries||{},!n.url||!n.is)return e(new Error("No url or type given!"));if(window.mosaic.components.libraries[n.url])e(null);else{var o=function(o){o.error?e(o.error):(window.mosaic.components.libraries[n.url].hasLoaded=!0,e(null))};if("script"===n.is){var t=d(n.url,n.attributes,n.cache);t.onerror=o,t.onload=o,document.body.appendChild(t)}else if("style"===n.is){var i=m(n.url,n.attributes,n.cache);i.onerror=o,i.onload=o,document.getElementsByTagName("head")[0].appendChild(i)}window.mosaic.components.libraries[n.url]={type:n.is,hasLoaded:!1,promise:null}}}({url:g("/deps.umd.js"),is:"script"},O)});
import{A as C}from"./AdminLayout-BO53Krua.js";import{d as M}from"./index-DwhLvwSz.js";import A from"./senTalk-BB9VSYXd.js";import{I as g,_ as D,p as y,i as q,d as _,o as h,b as i,a as s,w as B,s as d,k as j,t as p,F as L,J as b}from"./app-DFJ527Xl.js";import"./thunderstorm-Ctj7gDyo.js";/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const f=t=>t.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase(),S=t=>t.replace(/^([A-Z])|[\s-_]+(\w)/g,(o,a,l)=>l?l.toUpperCase():a.toLowerCase()),U=t=>{const o=S(t);return o.charAt(0).toUpperCase()+o.slice(1)},I=(...t)=>t.filter((o,a,l)=>!!o&&o.trim()!==""&&l.indexOf(o)===a).join(" ").trim(),x=t=>t==="";/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var u={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":2,"stroke-linecap":"round","stroke-linejoin":"round"};/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const V=({name:t,iconNode:o,absoluteStrokeWidth:a,"absolute-stroke-width":l,strokeWidth:n,"stroke-width":m,size:r=u.width,color:c=u.stroke,...e},{slots:w})=>g("svg",{...u,...e,width:r,height:r,stroke:c,"stroke-width":x(a)||x(l)||a===!0||l===!0?Number(n||m||u["stroke-width"])*24/Number(r):n||m||u["stroke-width"],class:I("lucide",e.class,...t?[`lucide-${f(U(t))}-icon`,`lucide-${f(t)}`]:["lucide-icon"])},[...o.map(k=>g(...k)),...w.default?[w.default()]:[]]);/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const v=(t,o)=>(a,{slots:l,attrs:n})=>g(V,{...n,...a,iconNode:o,name:t},l);/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const $=v("lock",[["rect",{width:"18",height:"11",x:"3",y:"11",rx:"2",ry:"2",key:"1w4ew1"}],["path",{d:"M7 11V7a5 5 0 0 1 10 0v4",key:"fwvmzm"}]]);/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const E=v("smile",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M8 14s1.5 2 4 2 4-2 4-2",key:"1y1vjs"}],["line",{x1:"9",x2:"9.01",y1:"9",y2:"9",key:"yxxnd0"}],["line",{x1:"15",x2:"15.01",y1:"9",y2:"9",key:"1p4y9e"}]]);/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const F=v("users",[["path",{d:"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2",key:"1yyitq"}],["path",{d:"M16 3.128a4 4 0 0 1 0 7.744",key:"16gr8j"}],["path",{d:"M22 21v-2a4 4 0 0 0-3-3.87",key:"kshegd"}],["circle",{cx:"9",cy:"7",r:"4",key:"nufk8"}]]);/**
 * @license lucide-vue-next v0.552.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const N=v("wrench",[["path",{d:"M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z",key:"1ngwbx"}]]),H={class:"col-12 px-lg-3"},O={key:0,class:"text-grey"},P={key:1,class:"col-12 tweets-report-wrapper rounded mt-3 mx-0 px-0"},R={class:"row"},Z={class:"col-lg-3 col-6 pr-0"},z={class:"col-12 pending-companies rounded py-4 pl-4"},J={class:"tweets-value pb-3 pt-3"},T={class:"col-lg-3 col-6"},Y={class:"col-12 company-requests rounded py-4 tweet-box pl-4"},G={class:"tweets-value pb-3 pt-3"},Q={class:"col-lg-3 col-6 pr-0 pt-lg-0 pt-3 pl-lg-0"},X={class:"col-12 system-users rounded py-4 tweet-box pl-4"},K={class:"tweets-value pb-3 pt-3"},W={class:"col-lg-3 col-6 pt-3 pt-lg-0"},ss={class:"col-12 customer-feedback rounded py-4 tweet-box pl-4"},es={class:"tweets-value pb-3 pt-3"},ts={key:2,class:"col-12 icon-grid-wrapper rounded mt-3 mx-0 px-0"},os={class:"row text-center"},as={class:"col-lg-3 col-6 mb-3"},ls={href:"/admin/sentiments/all",class:"icon-button full-box icon-sentiment"},ns={class:"col-lg-3 col-6 mb-3"},cs={href:"/admin/predictive-maintenance/index",class:"icon-button full-box icon-maintenance"},is={class:"col-lg-3 col-6 mb-3"},rs={href:"/admin/roles",class:"icon-button full-box icon-roles"},ds={class:"col-lg-3 col-6 mb-3"},ps={href:"/admin/getActiveUsers",class:"icon-button full-box icon-users"},us={class:"px-0 mx-auto mt-4",style:{width:"85%"}},ms=Object.assign({layout:C},{__name:"Dashboard",props:{refresh:{type:String,required:!0}},setup(t){const o=t,a=y([]),l=y([]),n=y({pending_companies:0,company_requests:0,system_users:0,customer_feedback:0}),m=async()=>{const c=await b.get("/user");a.value=c.data,l.value=a.value},r=async()=>{try{const c=await b.get("/admin/dashboard/stats");n.value=c.data}catch(c){console.error("Failed to fetch dashboard stats",c)}};return q(()=>{o.refresh==!0&&(window.location.href="/admin/dashboard"),m(),r()}),(c,e)=>(h(),_(L,null,[i(d(M.Head),{title:"Dashboard"},{default:B(()=>[...e[0]||(e[0]=[s("title",null,"Dashboard",-1)])]),_:1}),s("div",H,[a.value[0]?(h(),_("p",O," Welcome Back, "+p(a.value[0].first_name),1)):j("",!0),e[9]||(e[9]=s("h2",null,[s("strong",null,"Your Dashboard")],-1)),c.can("companies-read_approved")?(h(),_("div",P,[s("div",R,[s("div",Z,[s("div",z,[e[1]||(e[1]=s("div",{class:"tweets-label pt-4"},"Pending companies",-1)),s("div",J,[s("strong",null,p(n.value.pending_companies),1)])])]),s("div",T,[s("div",Y,[e[2]||(e[2]=s("div",{class:"tweets-label pt-4"},"Company Requests",-1)),s("div",G,[s("strong",null,p(n.value.company_requests),1)])])]),s("div",Q,[s("div",X,[e[3]||(e[3]=s("div",{class:"tweets-label pt-4"},"System users",-1)),s("div",K,[s("strong",null,p(n.value.system_users),1)])])]),s("div",W,[s("div",ss,[e[4]||(e[4]=s("div",{class:"tweets-label pt-4"},"Customer feedback",-1)),s("div",es,[s("strong",null,p(n.value.customer_feedback),1)])])])])])):(h(),_("div",ts,[s("div",os,[s("div",as,[s("a",ls,[i(d(E),{class:"icon-img"}),e[5]||(e[5]=s("span",{class:"icon-label"},"Sentiment",-1))])]),s("div",ns,[s("a",cs,[i(d(N),{class:"icon-img"}),e[6]||(e[6]=s("span",{class:"icon-label"},"Maintenance",-1))])]),s("div",is,[s("a",rs,[i(d($),{class:"icon-img"}),e[7]||(e[7]=s("span",{class:"icon-label"},"Roles",-1))])]),s("div",ds,[s("a",ps,[i(d(F),{class:"icon-img"}),e[8]||(e[8]=s("span",{class:"icon-label"},"Users",-1))])])])])),s("div",us,[i(A)])])],64))}}),ws=D(ms,[["__scopeId","data-v-591defeb"]]);export{ws as default};

import{A as D}from"./AdminLayout-06db7bcc.js";import{H as M}from"./index-98f44740.js";import S from"./senTalk-cfb00dee.js";import{G as w,_ as A,p as g,i as I,o as h,d as m,b as i,w as q,x as d,a as s,t as _,k as B,F as j,H as b,I as L,J as U}from"./app-23dc5c6f.js";import"./thunderstorm-d5a4eabc.js";import"./index-abb17552.js";/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const x=e=>e.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase(),H=e=>e.replace(/^([A-Z])|[\s-_]+(\w)/g,(t,o,a)=>a?a.toUpperCase():o.toLowerCase()),V=e=>{const t=H(e);return t.charAt(0).toUpperCase()+t.slice(1)},$=(...e)=>e.filter((t,o,a)=>!!t&&t.trim()!==""&&a.indexOf(t)===o).join(" ").trim(),k=e=>e==="";/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var p={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":2,"stroke-linecap":"round","stroke-linejoin":"round"};/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const F=({name:e,iconNode:t,absoluteStrokeWidth:o,"absolute-stroke-width":a,strokeWidth:c,"stroke-width":u,size:r=p.width,color:l=p.stroke,...y},{slots:f})=>w("svg",{...p,...y,width:r,height:r,stroke:l,"stroke-width":k(o)||k(a)||o===!0||a===!0?Number(c||u||p["stroke-width"])*24/Number(r):c||u||p["stroke-width"],class:$("lucide",y.class,...e?[`lucide-${x(V(e))}-icon`,`lucide-${x(e)}`]:["lucide-icon"])},[...t.map(C=>w(...C)),...f.default?[f.default()]:[]]);/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const v=(e,t)=>(o,{slots:a,attrs:c})=>w(F,{...c,...o,iconNode:t,name:e},a);/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const N=v("lock",[["rect",{width:"18",height:"11",x:"3",y:"11",rx:"2",ry:"2",key:"1w4ew1"}],["path",{d:"M7 11V7a5 5 0 0 1 10 0v4",key:"fwvmzm"}]]);/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const E=v("smile",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M8 14s1.5 2 4 2 4-2 4-2",key:"1y1vjs"}],["line",{x1:"9",x2:"9.01",y1:"9",y2:"9",key:"yxxnd0"}],["line",{x1:"15",x2:"15.01",y1:"9",y2:"9",key:"1p4y9e"}]]);/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const O=v("users",[["path",{d:"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2",key:"1yyitq"}],["path",{d:"M16 3.128a4 4 0 0 1 0 7.744",key:"16gr8j"}],["path",{d:"M22 21v-2a4 4 0 0 0-3-3.87",key:"kshegd"}],["circle",{cx:"9",cy:"7",r:"4",key:"nufk8"}]]);/**
 * @license lucide-vue-next v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const P=v("wrench",[["path",{d:"M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z",key:"1ngwbx"}]]);const n=e=>(L("data-v-f2fef029"),e=e(),U(),e),R=n(()=>s("title",null,"Dashboard",-1)),Z={class:"col-12 px-lg-3"},z={key:0,class:"text-grey"},G=n(()=>s("h2",null,[s("strong",null,"Your Dashboard")],-1)),J={key:1,class:"col-12 tweets-report-wrapper rounded mt-3 mx-0 px-0"},T={class:"row"},Y={class:"col-lg-3 col-6 pr-0"},Q={class:"col-12 pending-companies rounded py-4 pl-4"},X=n(()=>s("div",{class:"tweets-label pt-4"},"Pending companies",-1)),K={class:"tweets-value pb-3 pt-3"},W={class:"col-lg-3 col-6"},ss={class:"col-12 company-requests rounded py-4 tweet-box pl-4"},es=n(()=>s("div",{class:"tweets-label pt-4"},"Company Requests",-1)),ts={class:"tweets-value pb-3 pt-3"},os={class:"col-lg-3 col-6 pr-0 pt-lg-0 pt-3 pl-lg-0"},as={class:"col-12 system-users rounded py-4 tweet-box pl-4"},cs=n(()=>s("div",{class:"tweets-label pt-4"},"System users",-1)),ns={class:"tweets-value pb-3 pt-3"},ls={class:"col-lg-3 col-6 pt-3 pt-lg-0"},is={class:"col-12 customer-feedback rounded py-4 tweet-box pl-4"},rs=n(()=>s("div",{class:"tweets-label pt-4"},"Customer feedback",-1)),ds={class:"tweets-value pb-3 pt-3"},_s={key:2,class:"col-12 icon-grid-wrapper rounded mt-3 mx-0 px-0"},ps={class:"row text-center"},us={class:"col-lg-3 col-6 mb-3"},hs={href:"/admin/sentiments/all",class:"icon-button full-box icon-sentiment"},ms=n(()=>s("span",{class:"icon-label"},"Sentiment",-1)),vs={class:"col-lg-3 col-6 mb-3"},ys={href:"/admin/predictive-maintenance/index",class:"icon-button full-box icon-maintenance"},gs=n(()=>s("span",{class:"icon-label"},"Maintenance",-1)),ws={class:"col-lg-3 col-6 mb-3"},fs={href:"/admin/roles",class:"icon-button full-box icon-roles"},bs=n(()=>s("span",{class:"icon-label"},"Roles",-1)),xs={class:"col-lg-3 col-6 mb-3"},ks={href:"/admin/getActiveUsers",class:"icon-button full-box icon-users"},Cs=n(()=>s("span",{class:"icon-label"},"Users",-1)),Ds={class:"px-0 mx-auto mt-4",style:{width:"85%"}},Ms=Object.assign({layout:D},{__name:"Dashboard",props:{refresh:{type:String,required:!0}},setup(e){const t=e,o=g([]),a=g([]),c=g({pending_companies:0,company_requests:0,system_users:0,customer_feedback:0}),u=async()=>{const l=await b.get("/user");o.value=l.data,a.value=o.value},r=async()=>{try{const l=await b.get("/admin/dashboard/stats");c.value=l.data}catch(l){console.error("Failed to fetch dashboard stats",l)}};return I(()=>{t.refresh==!0&&(window.location.href="/admin/dashboard"),u(),r()}),(l,y)=>(h(),m(j,null,[i(d(M),{title:"Dashboard"},{default:q(()=>[R]),_:1}),s("div",Z,[o.value[0]?(h(),m("p",z," Welcome Back, "+_(o.value[0].first_name),1)):B("",!0),G,l.can("companies-read_approved")?(h(),m("div",J,[s("div",T,[s("div",Y,[s("div",Q,[X,s("div",K,[s("strong",null,_(c.value.pending_companies),1)])])]),s("div",W,[s("div",ss,[es,s("div",ts,[s("strong",null,_(c.value.company_requests),1)])])]),s("div",os,[s("div",as,[cs,s("div",ns,[s("strong",null,_(c.value.system_users),1)])])]),s("div",ls,[s("div",is,[rs,s("div",ds,[s("strong",null,_(c.value.customer_feedback),1)])])])])])):(h(),m("div",_s,[s("div",ps,[s("div",us,[s("a",hs,[i(d(E),{class:"icon-img"}),ms])]),s("div",vs,[s("a",ys,[i(d(P),{class:"icon-img"}),gs])]),s("div",ws,[s("a",fs,[i(d(N),{class:"icon-img"}),bs])]),s("div",xs,[s("a",ks,[i(d(O),{class:"icon-img"}),Cs])])])])),s("div",Ds,[i(S)])])],64))}}),Ls=A(Ms,[["__scopeId","data-v-f2fef029"]]);export{Ls as default};

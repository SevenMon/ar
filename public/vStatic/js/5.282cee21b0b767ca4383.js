webpackJsonp([5],{"0JwG":function(t,e){},EcO4:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});n("i0mo");var a=n("Hkar"),i=(n("OWWB"),n("1fWZ")),s=n("Xxa5"),r=n.n(s),c=n("exGp"),l=n.n(c),o=n("//Fk"),d=n.n(o),u=n("Dd8w"),f=n.n(u),v=n("cJ47"),_=n("AEPM");var m=n("yt7g"),g={data:function(){return{cardId:0,info:null}},created:function(){Object(m.a)("cardId")&&(this.cardId=Object(m.a)("cardId")),this._getCardInfo()},methods:{_getCardInfo:function(){var t=this;return l()(r.a.mark(function e(){var n;return r.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a={id1:t.cardId,user_id:""},void 0,i=_.a+"/Api/BusinessCard/getCarInfo",v.a.post(i,f()({},a)).then(function(t){return d.a.resolve(t)});case 2:1===(n=e.sent).data.status&&(t.info=n.data.data);case 4:case"end":return e.stop()}var a,i},e,t)}))()},goCodeImg:function(){this.$router.push("/codeimg?url="+encodeURIComponent(this.info.code_img))}},components:{Cell:i.a,CellGroup:a.a}},p={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.info?n("div",{staticClass:"me"},[n("div",{staticClass:"main"},[n("img",{staticClass:"avatar",attrs:{src:t.info.head_img,alt:""}}),t._v(" "),n("cell",{attrs:{title:"公司名称："+t.info.company}}),t._v(" "),n("cell",{attrs:{title:"姓名："+t.info.name}}),t._v(" "),n("cell",{attrs:{title:"职位："+t.info.position}}),t._v(" "),n("cell",{attrs:{title:"电话："+t.info.phone}}),t._v(" "),n("cell",{attrs:{title:"邮件："+t.info.email}})],1),t._v(" "),n("div",{staticClass:"bt-line"},[t.info.code_img?n("cell",{attrs:{title:"我的名片","is-link":""},on:{click:t.goCodeImg}}):t._e(),t._v(" "),n("cell",{attrs:{title:"个性签名",value:t.info.signature}}),t._v(" "),t.info.describe_imgs.length>0?n("div",{staticClass:"van-cell van-hairline me"},[t._m(0),t._v(" "),n("div",{staticClass:"van-cell__value"},t._l(t.info.describe_imgs,function(t,e){return n("img",{key:e,attrs:{src:t,alt:""}})}))]):t._e(),t._v(" "),n("cell",{attrs:{title:"使用介绍","is-link":""}}),t._v(" "),n("cell",{attrs:{title:"意见反馈","is-link":""}})],1)]):t._e()},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"van-cell__title"},[e("span",[this._v("上传图片")])])}]};var h=n("VU/8")(g,p,!1,function(t){n("0JwG")},"data-v-212b135a",null);e.default=h.exports},OWWB:function(t,e,n){"use strict";var a=n("f4F5");n.n(a)},i0mo:function(t,e,n){"use strict";var a=n("f4F5");n.n(a)}});
//# sourceMappingURL=5.282cee21b0b767ca4383.js.map
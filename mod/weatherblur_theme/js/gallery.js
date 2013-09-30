
// minifier: path aliases

enyo.path.addPaths({js: "../js/"});

// BingWebService.js

enyo.kind({
name: "nbt.BingWebService",
geocodeUrl: "https://dev.virtualearth.net/REST/v1/Locations",
published: {
key: null,
query: null
},
geocode: function(e) {
this.setQuery(e);
var t = new enyo.JsonpRequest({
url: this.geocodeUrl + "?key=" + this.key,
callbackName: "jsonp"
});
t.go({
q: e
}), t.response(this, "success"), t.error(this, "failure");
}
});

// Util.js

String.prototype.format || (String.prototype.format = function() {
var e = arguments;
return this.replace(/{(\d+)}/g, function(t, n) {
return typeof e[n] != "undefined" ? e[n] : t;
});
}), enyo.kind({
name: "nbt.Util",
statics: {
isScrollbarRendered: function(e) {
return e.scrollHeight > e.clientHeight;
},
getScrollbarWidth: function() {
var e = document.createElement("p");
e.style.width = "100%", e.style.height = "200px";
var t = document.createElement("div");
t.style.position = "absolute", t.style.top = "0px", t.style.left = "0px", t.style.visibility = "hidden", t.style.width = "200px", t.style.height = "150px", t.style.overflow = "hidden", t.appendChild(e), document.body.appendChild(t);
var n = e.offsetWidth;
t.style.overflow = "scroll";
var r = e.offsetWidth;
return n == r && (r = t.clientWidth), document.body.removeChild(t), n - r;
},
zeroFill: function(e, t) {
return t -= e.toString().length, t > 0 ? (new Array(t + (/\./.test(e) ? 2 : 1))).join("0") + e : e + "";
}
}
});

// nbt-util/Util/geo/Util.js

var nbt = nbt || {};

nbt.geo = nbt.geo || {}, nbt.geo.Util = {
distVincenty: function(e, t, n, r) {
var i = 6378137, s = 6356752.314245, o = 1 / 298.257223563, u = (r - t).toRad(), a = Math.atan((1 - o) * Math.tan(e.toRad())), f = Math.atan((1 - o) * Math.tan(n.toRad())), l = Math.sin(a), c = Math.cos(a), h = Math.sin(f), p = Math.cos(f), d = u, v, m = 100;
do {
var g = Math.sin(d), y = Math.cos(d), b = Math.sqrt(p * g * p * g + (c * h - l * p * y) * (c * h - l * p * y));
if (b == 0) return 0;
var w = l * h + c * p * y, E = Math.atan2(b, w), S = c * p * g / b, x = 1 - S * S, T = w - 2 * l * h / x;
isNaN(T) && (T = 0);
var N = o / 16 * x * (4 + o * (4 - 3 * x));
v = d, d = u + (1 - N) * o * S * (E + N * b * (T + N * w * (-1 + 2 * T * T)));
} while (Math.abs(d - v) > 1e-12 && --m > 0);
if (m == 0) return NaN;
var C = x * (i * i - s * s) / (s * s), k = 1 + C / 16384 * (4096 + C * (-768 + C * (320 - 175 * C))), L = C / 1024 * (256 + C * (-128 + C * (74 - 47 * C))), A = L * b * (T + L / 4 * (w * (-1 + 2 * T * T) - L / 6 * T * (-3 + 4 * b * b) * (-3 + 4 * T * T))), O = s * k * (E - A);
O = O.toFixed(3);
return O;
var M, _;
}
}, typeof Number.prototype.toRad == "undefined" && (Number.prototype.toRad = function() {
return this * Math.PI / 180;
});

// nbt-util/Util/geo/leaflet.js

var nbt = nbt || {};

nbt.geo = nbt.geo || {}, nbt.geo.leaflet = {
getNbtAttribution: function(e, t, n) {
return new L.Control.Attribution({
position: e || "bottomright",
prefix: "<span style='font-size: " + (n || "11px") + "'>Powered by <a target='_blank' href='http://www.nbtsolutions.com' style='color: " + (t || "#0078A8") + "'><i class='icon-map-marker'></i> NBT Solutions</a></span>"
});
},
getNbtAttributionBasic: function(e, t, n) {
return new L.Control.Attribution({
position: e || "bottomright",
prefix: "<span style='font-size: " + (n || "11px") + "'>Powered by <a target='_blank' href='http://www.nbtsolutions.com' style='color: " + (t || "#0078A8") + "'>NBT Solutions</a></span>"
});
},
getMapquestTileLayer: function(e, t) {
return L.tileLayer("http" + (e ? "s" : "") + "://otile{s}" + (e ? "-s" : "") + ".mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpg", _.extend(t, {
subdomains: "1234",
attribution: "Tiles: <a href='http://www.mapquest.com/' target='_blank'>MapQuest</a>"
}));
},
getArcGISTileLayer: function(e, t) {
return L.tileLayer("http://services.arcgisonline.com/ArcGIS/rest/services/" + e + "/MapServer/tile/{z}/{y}/{x}", _.extend(t, {
attribution: "Tiles: &copy; Esri"
}));
}
};

// CalendarSelector.js

enyo.kind({
name: "CalendarSelector",
classes: "enyo-unselectable",
published: {
dayNames: [ "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" ],
value: new Date,
dayColorDefault: "LightSlateGray",
dayColorSelected: "Gold",
dayColorToday: "GoldenRod",
dayColorOtherMonth: "Silver",
numberColorThisMonth: "#202020",
numberColorOtherMonth: "dimgrey"
},
events: {
onSelect: "",
onDaySet: ""
},
components: [ {
name: "day_names",
classes: "top-day-box"
}, {
fit: !0,
name: "calVBox",
kind: "CalMonth",
style: "height: auto;"
} ],
create: function() {
this.inherited(arguments);
for (var e = 0; e < 7; e++) this.$.day_names.createComponent({
content: this.dayNames[e]
});
},
rendered: function() {
this.inherited(arguments), this.updateCalendar();
},
updateCalendar: function() {
this.dayArray = [], this.getDays(), this.fillData();
},
fillData: function() {
var e = this.$.calVBox.getControls(), t = 0;
for (var n = 0; n < e.length; n++) {
var r = e[n].getControls();
for (var i = 0; i < r.length; i++) {
r[i].setValue(this.dayArray[t]), r[i].setClassAttribute("");
var s = this.getRelativeClass(this.dayArray[t]);
r[i].addClass(s), t++, this.doDaySet({
control: r[i]
});
}
}
},
getRelativeClass: function(e) {
if (e.getMonth() < this.value.getMonth()) return "prev-month";
if (e.getMonth() > this.value.getMonth()) return "next-month";
var t = new Date;
return t.getFullYear() == e.getFullYear() && t.getDate() == e.getDate() && t.getMonth() == e.getMonth() ? "today" : "day";
},
getColors: function(e) {
var t = this.dayColorDefault, n = this.numberColorThisMonth, r = (new Date).getDate(), i = (new Date).getMonth(), s = (new Date).getFullYear();
return e.getMonth() != this.value.getMonth() ? (t = this.dayColorOtherMonth, n = this.numberColorOtherMonth) : this.value.getFullYear() == e.getFullYear() && this.value.getDate() == e.getDate() && this.value.getMonth() == e.getMonth() ? t = this.dayColorSelected : s == e.getFullYear() && r == e.getDate() && i == e.getMonth() && (t = this.dayColorToday), [ t, n ];
},
getDays: function() {
var e = new Date(this.value);
e.setDate(1);
var t = e.getDay(), n = new Date(e);
n.setDate(0);
var r = n.getDate(), s = new Date(e);
s.setMonth(e.getMonth() + 1), s.setDate(0);
var o = s.getDate(), u = r - t + 1;
for (i = 0; i < t; i++) this.dayArray.push(new Date(n.getFullYear(), n.getMonth(), u, 12, 0, 0)), u++;
u = 1;
while (u <= o) this.dayArray.push(new Date(s.getFullYear(), s.getMonth(), u, 12, 0, 0)), u++;
u = 1, s.setDate(s.getDate() + 1);
while (this.dayArray.length < 42) this.dayArray.push(new Date(s.getFullYear(), s.getMonth(), u, 12, 0, 0)), u++;
},
calTap: function(e) {
this.value = e.value, this.updateCalendar(), this.doSelect({
value: this.value
});
},
setValue: function(e) {
this.value = new Date(e), this.updateCalendar();
},
nextMonth: function() {
var e = this.value.getDate();
this.value.setMonth(this.value.getMonth() + 1), e != this.value.getDate() && this.value.setDate(0), this.updateCalendar(), this.doSelect({
value: this.value
});
},
prevMonth: function() {
var e = this.value.getDate();
this.value.setMonth(this.value.getMonth() - 1), e != this.value.getDate() && this.value.setDate(0), this.updateCalendar(), this.doSelect({
value: this.value
});
}
}), enyo.kind({
name: "CalDay",
classes: "day-container",
published: {
value: {}
},
events: {
onDayPicked: ""
},
handlers: {
ontap: "tapMe"
},
valueChanged: function(e, t) {
this.setContent(this.value.getDate());
},
setValue: function(e) {
this.value = e, this.valueChanged();
},
tapMe: function(e, t) {
return this.owner.owner.owner.calTap({
value: this.value
}), this.doDayPicked({
day: this
}), !0;
}
}), enyo.kind({
name: "CalWeek",
classes: "onyx-toolbar-inline week-container",
defaultKind: "CalDay",
components: [ {}, {}, {}, {}, {}, {}, {} ]
}), enyo.kind({
name: "CalMonth",
defaultKind: "CalWeek",
components: [ {}, {}, {}, {}, {}, {} ]
});

// DatePicker.js

enyo.kind({
name: "DatePicker",
published: {
value: new Date,
minYear: 2012,
maxYear: 2020
},
events: {
onSelect: ""
},
components: [ {
name: "topBar"
} ],
rendered: function() {
this.createTopBar(), this.updateStuff(), this.inherited(arguments);
},
valueChanged: function() {
this.render();
},
createTopBar: function() {
var e = [];
this.years = [];
for (var t = this.minYear; t <= this.maxYear; t++) this.years.push(t), e.push({
content: t,
value: t - this.minYear
});
this.getDays();
var n = [];
for (t = 0; t < this.days.length; t++) n.push({
content: t + 1,
value: t + 1
});
var r = [ {
content: "January",
value: 0
}, {
content: "February",
value: 1
}, {
content: "March",
value: 2
}, {
content: "April",
value: 3
}, {
content: "May",
value: 4
}, {
content: "June",
value: 5
}, {
content: "July",
value: 6
}, {
content: "August",
value: 7
}, {
content: "September",
value: 8
}, {
content: "October",
value: 9
}, {
content: "November",
value: 10
}, {
content: "December",
value: 11
} ], i = [ {
kind: "Select",
name: "monthSel",
onchange: "monthChanged",
components: r
}, {
kind: "Select",
name: "daySel",
onchange: "dayChanged",
components: n
}, {
kind: "Select",
name: "yearSel",
onchange: "yearChanged",
components: e
} ];
this.$.topBar.destroyClientControls(), this.$.topBar.createComponents(i, {
owner: this
}), this.$.topBar.render();
},
setValue: function(e) {
this.value = e, this.valueChanged();
},
getValue: function() {
return this.value;
},
dayChanged: function(e) {
return this.value.setDate(e.selected + 1), this.doSelect({
value: new Date(this.value)
}), !0;
},
monthChanged: function(e) {
return this.value.setMonth(e.selected), this.updateDays(), this.doSelect({
value: new Date(this.value)
}), !0;
},
yearChanged: function(e) {
return this.value.setFullYear(this.years[e.selected]), this.updateDays(), this.doSelect({
value: new Date(this.value)
}), !0;
},
updateStuff: function() {
this.$.monthSel.setSelected(this.value.getMonth()), this.$.yearSel.setSelected(this.value.getFullYear() - this.minYear), this.$.daySel.setSelected(this.value.getDate() - 1), this.updateDays();
},
updateDays: function() {
this.getDays();
var e = [];
for (var t = 0; t < this.days.length; t++) e.push({
content: t + 1,
value: t + 1
});
this.$.daySel.destroyClientControls(), this.$.daySel.createComponents(e, {
owner: this
}), this.$.daySel.render();
},
getDays: function() {
this.days = [];
var e = new Date(this.value);
e.setDate(1);
var t = new Date(e);
t.setMonth(e.getMonth() + 1), t.setDate(0);
var n = t.getDate(), r = 1;
while (r <= n) this.days.push(r), r++;
}
});

// namespace.js

wb = window.wb || {}, wb.map = {
model: {},
assets: {},
constants: {}
};

// Constants.js

wb.map.assets = {}, wb.map.env = {
momentDateFormat: "MM/DD/YYYY"
}, wb.constants = wb.constants || {
isGallery: !0
}, wb.gallery = {
userImages: {},
userNames: {}
};

// CalendarInputs.js

enyo.kind({
name: "CalendarInputs",
classes: "control-sub-content ",
events: {
onPickDate: ""
},
components: [ {
controlClasses: "enyo-inline",
components: [ {
content: "From: "
}, {
kind: "onyx.InputDecorator",
alwaysLooksFocused: !0,
components: [ {
kind: "onyx.Input",
name: "fromDate",
classes: "",
placeholder: "MM/DD/YY"
} ]
}, {
kind: "onyx.Icon",
src: "assets/calendar.png",
ontap: "pickFromDate"
} ]
}, {
controlClasses: "enyo-inline",
components: [ {
content: "To: "
}, {
kind: "onyx.InputDecorator",
alwaysLooksFocused: !0,
components: [ {
kind: "onyx.Input",
name: "toDate",
classes: "",
placeholder: "MM/DD/YY"
} ]
}, {
kind: "onyx.Icon",
src: "assets/calendar.png",
ontap: "doShowCalendar"
} ]
} ],
doShowCalendar: function(e, t) {},
pickToDate: function(e, t) {
this.doPickDate({
activeInput: this.$.toDate,
isFromDate: !1
});
}
});

// DatePickCalendar.js

enyo.kind({
kind: "onyx.Popup",
name: "wb.DatePickCalendar",
centered: !0,
floating: !0,
scrim: !0,
autoDismiss: !1,
modal: !0,
classes: "calendar-popup",
components: [ {
name: "header",
components: [ {
tag: "i",
classes: "icon-circle-arrow-left",
ontap: "prevMonth"
}, {
kind: "onyx.DatePicker",
onSelect: "doPickerChanged"
}, {
tag: "i",
classes: "icon-circle-arrow-right",
ontap: "nextMonth"
} ]
}, {
kind: "CalendarSelector",
onDaySet: "doCalendarChanged"
}, {
kind: "onyx.Button",
content: " Cancel",
classes: "icon-remove",
ontap: "hide"
}, {
kind: "onyx.Button",
content: " Done",
classes: "icon-ok",
ontap: "doSaveAndHide"
} ],
create: function() {
this.inherited(arguments);
if (!this.field) throw "wb.DatePickCalendar requires a 'field' property.";
},
doCalendarChanged: function(e, t) {
var n = moment(e.getValue()), r = moment(t.control.getValue());
if (!n.isSame(r, "day")) return;
_.each(e.getDays(), function(e) {
e.addRemoveClass("day-selected", !1);
}, this), t.control.addRemoveClass("day-selected", !0), this.$.datePicker.setValue(n.toDate());
},
doPickerChanged: function(e, t) {
this.$.calendarSelector.setValue(t.value);
},
nextMonth: function() {
this.$.calendarSelector.nextMonth();
},
prevMonth: function() {
this.$.calendarSelector.prevMonth();
},
doSaveAndHide: function() {
this.field.setValue(moment(this.$.datePicker.getValue()).format(wb.map.env.momentDateFormat)), this.field.bubble("onValueChanged"), this.hide();
}
});

// GalleryPanel.js

enyo.kind({
name: "wb.GalleryPanel",
classes: "gallery-panel",
published: {
observation: null,
categories: null,
image: null,
video: null,
likesLoaded: !1
},
components: [ {
name: "container",
components: [ {
tag: "img",
name: "thumbnail",
ontap: "handleObservationTap"
}, {
name: "caption",
components: [ {
controlClasses: "enyo-inline",
components: [ {
tag: "img",
name: "userIcon"
}, {
components: [ {
controlClasses: "enyo-inline",
components: [ {
content: "Recorded:",
classes: "label"
}, {
name: "date",
classes: "label"
} ]
}, {
controlClasses: "enyo-inline",
components: [ {
content: "By:",
classes: "label"
}, {
name: "username",
classes: "label"
} ]
}, {
controlClasses: "enyo-inline",
classes: "like-comment-group",
components: [ {
tag: "i",
classes: "icon-comments",
ontap: "handleObservationTap"
}, {
name: "commentsCount"
}, {
name: "likesIcon",
tag: "i",
classes: "icon-thumbs-up-alt",
ontap: "handleLikesTap"
}, {
name: "likesCount"
} ]
} ]
} ]
} ]
} ]
} ],
rendered: function() {
this.inherited(arguments), this.categories = this.observation.get("categories");
var e = this.observation.get("observer"), t = null;
wb.gallery.userImages[e.get("elggId")] && (t = wb.gallery.userImages[e.get("elggId")], this.$.userIcon.setSrc(t));
var n = e.get("label");
if (n && n.length > 0 || wb.gallery.userNames[e.get("elggId")]) n = wb.gallery.userNames[e.get("elggId")], this.$.username.setContent(n.substr(0, 15) + "...");
this.$.date.setContent(moment(this.observation.get("timestamp")).format("MMM DD, YYYY"));
if (!t || !n) var r = {
method: "wb.get_user_info",
user_guid: e.get("elggId"),
icon_size: "medium"
}, i = (new enyo.Ajax({
url: wb.env.elggPath
})).response(enyo.bind(this, function(t, n, r) {
n.status === 0 && (wb.gallery.userImages[e.get("elggId")] = n.result.image, wb.gallery.userNames[e.get("elggId")] = n.result.users_display_name, this.$.userIcon.setSrc(n.result.image), this.$.username.setContent(n.result.users_display_name));
})).go(r);
if (this.categories.media) this.observation.get("measurements").length > 0 ? this.handleMeasurementsResponse(this.observation.get("measurements")) : (this.observation.set({
measurements: this.observation.id
}), this.observation.fetchRelated("measurements", {
success: enyo.bind(this, "handleMeasurementsResponse")
})); else {
var s = _(_(this.categories).keys()).max(function(e) {
return this.categories[e].length;
}, this);
this.$.thumbnail.setSrc("assets/" + s + "-150x150.png"), this.$.thumbnail.addClass("category-icon");
}
this.likesLoaded || (this.doILikeThisObservation(), this.doLikesCount(), this.doCommentsCount(), this.likesLoaded = !0);
},
doILikeThisObservation: function() {
var e = {
method: "wb.get_my_obs_like_by_agg_id",
agg_id: this.observation.get("id")
}, t = function(e, t, n) {
t.status == 0 && (t.result > 0 ? (this.$.likesIcon.removeClass("icon-thumbs-up-alt"), this.$.likesIcon.addClass("icon-thumbs-up")) : (this.$.likesIcon.addClass("icon-thumbs-up-alt"), this.$.likesIcon.removeClass("icon-thumbs-up")));
};
this.doElggAjax(e, t);
},
doLikesCount: function() {
var e = {
method: "wb.get_likes_by_agg_id",
agg_id: this.observation.get("id")
}, t = function(e, t, n) {
if (t.status == 0) {
var r = "";
t.result.all_likes > 0 && (r = "(" + t.result.all_likes + ")"), this.$.likesCount && this.$.likesCount.setContent(r);
}
}, n = this.doElggAjax(e, t);
},
doCommentsCount: function() {
var e = {
method: "wb.get_comments_on_obs_by_agg_id",
agg_id: this.observation.get("id")
}, t = function(e, t, n) {
if (t.status == 0) {
var r = "";
t.result.length > 0 && (r = "(" + t.result.length + ")"), this.$.commentsCount && this.$.commentsCount.setContent(r);
}
}, n = this.doElggAjax(e, t);
},
imageChanged: function(e) {
this.$.image && this.$.image.setSrc(this.image);
},
handleMeasurementsResponse: function(e, t, n) {
var r = _(this.categories.media).contains("video") ? "video" : "image", i = e.findWhere({
value: r
});
if (r == "image") {
var s = new Image, o = i.get("meta").url, u = o.lastIndexOf("."), a = o.substr(u), f = o.substr(0, u) + "-thumb" + a;
s.onload = enyo.bind(this, function() {
this.$.thumbnail.setSrc(f);
}), s.onerror = enyo.bind(this, function() {
this.$.thumbnail.setSrc(o);
}), s.src = f;
} else r == "video" && this.$.thumbnail.setSrc(i.get("meta").thumbnailUrl);
},
handleObservationTap: function(e, t) {
window.location = "//" + wb.env.elggHost + "/observation/" + this.observation.id;
},
handleLikesTap: function(e, t) {
var n = {
method: "wb.toggle_like_obs_by_agg_id",
agg_id: this.observation.get("id")
}, r = function() {
this.doLikesCount(), this.doILikeThisObservation();
}, i = this.doElggAjax(n, r);
},
doElggAjax: function(e, t) {
var n = (new enyo.Ajax({
url: wb.env.elggPath
})).response(this, t).go(e);
return n;
}
});

// ObservationInfoWindow.js

enyo.kind({
name: "wb.ObservationInfoWindow",
classes: "observation-info-window",
kind: "Panels",
animated: !1,
fit: !0,
narrowFit: !1,
margin: 0,
published: {
observationList: null
},
events: {
onShowObservationList: "",
onObservationSelect: ""
},
handlers: {
onObservationSelect: "handleObservationSelect",
onShowObservationList: "handleShowObservationList"
},
components: [ {
kind: "wb.ObservationList"
}, {
kind: "wb.ObservationDetail"
} ],
observationListChanged: function(e) {
console.log(this.observationList), this.$.observationList.setList(this.observationList), this.observationList.length > 1 ? this.doShowObservationList() : this.doObservationSelect({
observation: this.observationList[0]
});
},
handleObservationSelect: function(e, t) {
this.$.observationDetail.resetPanels(), this.$.observationDetail.setObservation(t.observation), this.$.observationDetail.setHasList(e === this.$.observationList), this.setIndex(1), this.observationList = null;
},
handleShowObservationList: function(e, t) {
this.$.observationDetail.resetPanels(), this.setIndex(0);
}
});

// ObservationList.js

enyo.kind({
name: "wb.ObservationList",
classes: "observation-list",
published: {
list: null,
maxCount: 99
},
components: [ {
classes: "header",
components: [ {
classes: "title",
content: "Observation List"
}, {
content: "Location: ",
classes: "phenomenon"
}, {
name: "geom",
classes: "value"
} ]
}, {
kind: "Scroller",
fit: !0,
components: [ {
kind: "Repeater",
classes: "list",
onSetupItem: "setupItem",
components: [ {
kind: "wb.ObservationListItem"
} ]
} ]
} ],
setupItem: function(e, t) {
var n = this.list[t.index];
this.log(n), t.item.$.observationListItem.setObservation(n), t.item.$.observationListItem.setObservationIndex(t.index + 1), t.item.$.observationListItem.addRemoveClass("highlighted", t.index % 2);
},
listChanged: function(e) {
this.$.repeater.setCount(Math.min(this.list.length, this.maxCount)), this.$.geom.setContent(wb.ObservationDetail.formatLocation(this.list[0].get("geometry")));
}
});

// ObservationListItem.js

enyo.kind({
name: "wb.ObservationListItem",
classes: "observation-item",
published: {
observation: null,
observationIndex: null
},
events: {
onObservationSelect: "",
onObservationResponse: ""
},
components: [ {
name: "index",
classes: "column index"
}, {
classes: "column",
components: [ {
classes: "row",
controlClasses: "enyo-inline",
components: [ {
content: "Recorded by: ",
classes: "label"
}, {
name: "username",
classes: "value"
}, {
name: "group",
classes: "value"
} ]
}, {
classes: "row",
controlClasses: "enyo-inline",
components: [ {
content: "Date: ",
classes: "label"
}, {
name: "date",
classes: "value"
} ]
} ]
} ],
observationIndexChanged: function(e) {
this.$.index.setContent(this.observationIndex);
},
tap: function(e, t) {
this.doObservationSelect({
observation: this.observation
});
},
observationChanged: function(e) {
this.$.date.setContent(moment(this.observation.get("timestamp")).format("MMMM Do YYYY, h:mm a"));
var t = this.observation.get("observer"), n = (new enyo.Ajax({
url: wb.env.elggPath
})).response(this, function(e, n, r) {
console.log(n), n.status === 0 ? (t.set({
image: n.result.image
}), t.set({
group: n.result.profile_type
}), t.set({
label: n.result.username
}), this.$.username.setContent(t.get("label")), this.$.group.setContent("( " + t.get("group") + " )")) : (t.set({
image: "assets/icon.png"
}), t.set({
group: "Weatherblur"
}), t.set({
label: "User"
}), this.$.username.setContent(t.get("label")), this.$.group.setContent("( " + t.get("group") + " )"));
}).go({
method: "wb.get_user_info",
user_guid: t.get("elggId"),
icon_size: "small"
});
this.$.username.setContent(t.get("label")), this.$.group.setContent("(" + t.get("elggGroup") + ")");
}
});

// ObservationDetail.js

enyo.kind({
name: "wb.ObservationDetail",
kind: "FittableRows",
classes: "observation-detail",
published: {
observation: null,
hasList: null
},
events: {
onShowObservationList: ""
},
handlers: {
onImageThumbnailTap: "handleImageThumbnailTap",
onVideoThumbnailTap: "handleVideoThumbnailTap"
},
statics: {
formatLocation: function(e) {
return "( " + e.get("coordinates")[0].toFixed(4) + ", " + e.get("coordinates")[1].toFixed(4) + " )";
}
},
components: [ {
kind: "FittableColumns",
classes: "header",
components: [ {
kind: "Image",
name: "userAvatar",
classes: "avatar"
}, {
kind: "FittableRows",
classes: "rows",
components: [ {
controlClasses: "enyo-inline",
classes: "username-row",
components: [ {
name: "username"
}, {
name: "group"
} ]
}, {
name: "investigationTitle"
}, {
controlClasses: "enyo-inline",
components: [ {
content: "Recorded Date: ",
classes: "phenomenon"
}, {
name: "recordedDate",
style: "margin-right: 1em"
}, {
content: "Location: ",
classes: "phenomenon"
}, {
name: "geom",
classes: "value"
} ]
} ]
}, {
tag: "i",
name: "back",
classes: "icon-arrow-left leaflet-popup-close-button",
showing: !1,
ontap: "handleBackTap"
} ]
}, {
kind: "Panels",
draggable: !1,
animated: !1,
fit: !0,
components: [ {
kind: "wb.ObservationDetailPanel"
}, {
kind: "wb.ObservationImagePanel"
}, {
kind: "wb.ObservationVideoPanel"
} ]
} ],
observationChanged: function(e) {
this.log(this.observation), this.$.recordedDate.setContent(moment(this.observation.get("timestamp")).format("MMMM Do YYYY, h:mm a")), this.$.geom.setContent(wb.ObservationDetail.formatLocation(this.observation.get("geometry")));
var t = this.observation.get("observer");
t.on("change:group", function(e, t) {
this.$.group.setContent("( " + t + " )");
}, this), t.on("change:label", function(e, t) {
this.$.username.setContent(t);
}, this), t.on("change:image", function(e, t) {
this.$.userAvatar.setSrc(t);
}, this), this.$.investigationTitle.setContent("Recorded with Weatherblur");
var n = this;
this.observation.set({
measurements: this.observation.id
}), this.observation.fetchRelated("measurements", {
reset: !0,
success: function(e, t, r) {
n.$.observationDetailPanel.setMeasurements(e);
}
}, this);
},
handleMeasurementsResponse: function(e) {
this.$.observationDetailPanel.setMeasurements(this.observation.get("measurements"));
},
resetPanels: function() {
this.$.panels.setIndex(0);
},
hasListChanged: function(e) {
this.$.back.setShowing(this.hasList);
},
handleBackTap: function(e, t) {
this.$.panels.getIndex() === 0 ? this.doShowObservationList() : (this.$.panels.setIndex(0), this.$.back.setShowing(this.hasList));
},
handleImageThumbnailTap: function(e, t) {
this.$.panels.setIndex(1), this.$.observationImagePanel.setImage(t.image), this.$.back.setShowing(!0);
},
handleVideoThumbnailTap: function(e, t) {
this.$.panels.setIndex(2), this.$.observationVideoPanel.setVideo(t.video), this.$.back.setShowing(!0);
}
});

// ObservationDetailPanel.js

enyo.kind({
name: "wb.ObservationDetailPanel",
classes: "observation-detail-panel",
events: {
onImageThumbnailTap: "",
onVideoThumbnailTap: ""
},
published: {
measurements: null,
groupedMeasurements: null,
video: null,
image: null,
defaultVideoCaption: "No video available for this observation.",
defaultImageCaption: "No image available for this observation."
},
components: [ {
name: "mediaContainer",
classes: "thumbnailContainer",
controlClasses: "enyo-inline",
components: [ {
tag: "i",
name: "imageThumbnailPlaceholder",
classes: "icon-camera placeholder"
}, {
tag: "i",
name: "videoThumbnailPlaceholder",
classes: "icon-facetime-video placeholder"
}, {
kind: "onyx.TooltipDecorator",
components: [ {
kind: "Image",
name: "imageThumbnail",
classes: "thumbnail image",
ontap: "handleImageThumbnailTap"
}, {
kind: "onyx.Tooltip",
name: "imageThumbnailTooltip",
allowHtml: !0
} ]
}, {
kind: "onyx.TooltipDecorator",
components: [ {
kind: "Image",
name: "videoThumbnail",
classes: "thumbnail video",
ontap: "handleVideoThumbnailTap"
}, {
kind: "onyx.Tooltip",
name: "videoThumbnailTooltip",
allowHtml: !0
} ]
} ]
}, {
controlClasses: "enyo-inline",
classes: "measurements-header",
components: [ {
tag: "h3",
content: "Measurements"
}, {
content: "(Scroll for more)",
classes: "subtitle"
} ]
}, {
kind: "Scroller",
classes: "measurements-container",
components: [ {
name: "categoryRepeater",
kind: "Repeater",
onSetupItem: "setupMeasurementCategory",
components: [ {
name: "categoryTitle",
classes: "category-title list-item"
}, {
name: "itemRepeater",
kind: "Repeater",
onSetupItem: "setupMeasurementItem",
components: [ {
controlClasses: "enyo-inline",
classes: "list-item",
components: [ {
name: "phenomenon",
classes: "phenomenon"
}, {
name: "value",
classes: "value",
allowHtml: !0
} ]
} ]
} ]
} ]
} ],
setupMeasurementCategory: function(e, t) {
var n = _(this.groupedMeasurements).keys()[t.index], r = this.groupedMeasurements[n];
t.item.$.categoryTitle.setContent(n), t.item.$.itemRepeater.category = n, t.item.$.itemRepeater.setCount(r.length);
},
setupMeasurementItem: function(e, t) {
var n = this.groupedMeasurements[e.category].pop(), r = n.get("phenomenon");
return t.item.$.phenomenon.setContent(r.get("description")), r.get("unit").get("type") == "text" ? t.item.$.value.setContent(n.get("value")) : t.item.$.value.setContent(n.get("value") + " " + r.get("unit").get("name")), !0;
},
groupedMeasurementsChanged: function(e) {
this.$.categoryRepeater.setCount(_(this.groupedMeasurements).size());
},
measurementsChanged: function(e) {
this.setImage(this.measurements.find(function(e) {
return e.get("phenomenon").get("name") == "image";
})), this.setVideo(this.measurements.find(function(e) {
return e.get("phenomenon").get("name") == "video";
})), this.$.mediaContainer.setShowing(this.image || this.video), this.$.scroller.addRemoveClass("full", !this.image && !this.video), this.setGroupedMeasurements(_(this.measurements.filter(function(e) {
return _([ "scalar", "text" ]).contains(e.get("phenomenon").get("unit").get("type"));
})).groupBy(function(e) {
return e.get("phenomenon").get("category").get("name");
}));
},
imageChanged: function(e) {
this.$.imageThumbnailPlaceholder.setShowing(!this.image), this.log(this.image), this.image ? (this.$.imageThumbnail.removeClass("empty"), this.$.imageThumbnail.setSrc(this.image.get("meta").url), this.$.imageThumbnailTooltip.setContent("<i class='icon-comment-alt'></i>" + (this.image.get("meta").caption || "No image caption provided."))) : (this.$.imageThumbnail.addClass("empty"), this.$.imageThumbnail.setSrc("data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAIAOw=="), this.$.imageThumbnailTooltip.setContent(this.defaultImageCaption));
},
videoChanged: function(e) {
this.$.videoThumbnailPlaceholder.setShowing(!this.video), this.video ? (this.$.videoThumbnail.removeClass("empty"), this.$.videoThumbnail.setSrc(this.video.get("meta").thumbnailUrl), this.$.videoThumbnailTooltip.setContent("<i class='icon-comment-alt'></i>" + (this.video.get("meta").caption || "No image caption provided."))) : (this.$.videoThumbnail.addClass("empty"), this.$.videoThumbnail.setSrc("data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAIAOw=="), this.$.videoThumbnailTooltip.setContent(this.defaultVideoCaption));
},
handleImageThumbnailTap: function(e, t) {
this.image && (t.image = this.image, this.doImageThumbnailTap(t));
},
handleVideoThumbnailTap: function(e, t) {
this.video && (t.video = this.video, this.doVideoThumbnailTap(t));
}
});

// ObservationVideoPanel.js

enyo.kind({
name: "wb.ObservationVideoPanel",
classes: "observation-video-panel",
fit: !0,
published: {
video: null
},
components: [ {
tag: "video",
name: "video",
classes: "video-js vjs-default-skin",
attributes: {
"controls preload": "auto",
width: 500,
height: 240
},
components: [ {
tag: "source",
name: "mp4Source"
}, {
tag: "source",
name: "webmSource"
} ]
}, {
classes: "caption",
name: "caption"
} ],
videoChanged: function(e) {
this.$.caption.setContent(this.video.get("meta").caption), this.$.mp4Source.setAttribute("src", this.video.get("meta").url.replace("$ext", "mp4")), this.$.mp4Source.setAttribute("type", "video/mp4"), this.$.webmSource.setAttribute("src", this.video.get("meta").url.replace("$ext", "webm")), this.$.webmSource.setAttribute("type", "video/webm"), videojs(this.$.video.hasNode());
}
});

// ObservationImagePanel.js

enyo.kind({
name: "wb.ObservationImagePanel",
classes: "observation-image-panel",
fit: !0,
published: {
image: null
},
components: [ {
classes: "image-container",
components: [ {
kind: "Image",
fit: !0,
classes: "image-view"
} ]
}, {
classes: "caption",
name: "caption"
} ],
imageChanged: function(e) {
this.$.image.setSrc(this.image.get("meta").url), this.$.caption.setContent(this.image.get("meta").caption);
}
});

// Pagination.js

enyo.kind({
name: "nbt.Pagination",
published: {
listClass: "pagination",
classDisabled: "disabled",
classSelected: "selected"
},
events: {
onOffsetChanged: ""
},
components: [],
update: function(e, t) {
this.destroyComponents();
var n = {
offset: 0,
total: 0,
limit: 10
};
enyo.mixin(n, t);
if (n.total <= n.limit) return;
var r = Math.ceil(n.total / n.limit), i = Math.ceil(n.offset / n.limit) + 1, s = this.createComponent({
name: "pagination",
tag: "ul",
classes: this.listClass
});
this.render();
var o = this._buildItem("prev", "\u00ab Previous", Math.max(0, n.offset - n.limit), n);
i == 1 ? o.classes = this.classDisabled : o.ontap = "handleOffsetTap", s.createComponent(o, {
owner: this
});
for (var u = 1; u <= r; u++) {
var a = this._buildItem("page" + u, u, n.limit * (u - 1), n);
u == i ? a.classes = this.classSelected : a.ontap = "handleOffsetTap", s.createComponent(a, {
owner: this
});
}
var f = this._buildItem("next", "Next \u00bb", n.offset + n.limit, n);
i == r ? f.classes = this.classDisabled : f.ontap = "handleOffsetTap", s.createComponent(f, {
owner: this
}), this.render();
},
_buildItem: function(e, t, n, r) {
var i = enyo.clone(r);
return i.name = e, i.tag = "li", i.offset = n, i.components = [ {
content: t
} ], i;
},
handleOffsetTap: function(e, t) {
this.doOffsetChanged({
newOffset: e.offset
}), this.update(null, {
offset: e.offset,
total: e.total,
limit: e.limit
});
}
});

// MapGrowl.js

enyo.kind({
kind: "enyo.Popup",
name: "nbt.MapGrowl",
published: {
data: null
},
components: [ {
kind: "enyo.Signals",
onMapGrowlReposition: "doReposition"
}, {
name: "growl"
} ],
dataChanged: function(e) {
console.log(this.data), this.$.growl.setContent(this.data);
},
doReposition: function(e, t) {
this.showAtPosition({
top: t.top,
right: t.right
});
}
});

// jumpToggle.js

enyo.kind({
name: "wb.jumpToggle",
kind: "FittableRows",
classes: " wb-jump-toggle",
published: {
map: null
},
components: [ {
kind: "Image",
src: "assets/alaska_off.png",
style: "cursor: pointer",
ontap: "handleAlaskaIn"
}, {
kind: "Image",
src: "assets/maine_off.png",
style: "cursor: pointer",
ontap: "handleMaineIn"
} ],
handleMaineIn: function(e, t) {
this.map.setView(new L.LatLng(44.183899, -67.964899), 8);
},
handleAlaskaIn: function(e, t) {
this.map.setView(new L.LatLng(57.060903, -135.348624), 8);
}
});

// ZoomControl.js

enyo.kind({
name: "wb.ZoomControl",
kind: "FittableRows",
classes: "leaflet-control-zoom leaflet-bar leaflet-control wb-zoom-control",
published: {
map: null
},
components: [ {
kind: "Image",
src: "assets/zoom_in_icon.png",
style: "cursor: pointer",
ontap: "handleZoomIn"
}, {
kind: "Image",
src: "assets/zoom_out_icon.png",
style: "cursor: pointer",
ontap: "handleZoomOut"
} ],
handleZoomIn: function(e, t) {
this.map.zoomIn();
},
handleZoomOut: function(e, t) {
this.map.zoomOut();
}
});

// TitledDrawer.js

enyo.kind({
name: "wb.TitledDrawer",
classes: "titled-drawer",
style: "width: 100%",
components: [ {
controlClasses: "enyo-inline",
name: "container",
ontap: "doDrawerToggle",
components: [ {
tag: "i",
name: "icon",
classes: "icon-collapse-alt"
}, {
classes: "text",
name: "title"
} ]
}, {
kind: "onyx.Drawer"
} ],
create: function() {
this.inherited(arguments), this.$.container.setClasses(this.titleClasses), this.$.title.setContent(this.title), this.$.drawer.createComponents(this.drawerComponents, {
owner: this.owner
});
},
doDrawerToggle: function(e, t) {
this.setOpen(!this.$.drawer.open);
},
setOpen: function(e) {
this.$.drawer.setOpen(e), this.$.icon.addRemoveClass("icon-collapse-alt", e), this.$.icon.addRemoveClass("icon-expand-alt", !e);
}
});

// MapView.js

enyo.kind({
name: "wb.MapView",
statics: {
userObservationMarker: function(e) {
return L.divIcon({
className: "div_icon",
html: "<img src='assets/user_icon.png'><div class='num_of_samples'>" + e + "</div>",
iconSize: [ 32, 32 ],
popupAnchor: [ 0, -16 ]
});
},
buoyMarker: function() {
return L.divIcon({
className: "div_icon",
html: "<img src='assets/buoy_icon.png'>",
iconSize: [ 32, 32 ],
popupAnchor: [ 0, -16 ]
});
},
schoolMarker: function() {
return L.divIcon({
className: "div_icon",
html: "<img src='assets/station_icon.png'>",
iconSize: [ 32, 32 ],
popupAnchor: [ 0, -16 ]
});
},
schoolPopup: function(e) {
return L.popup({
closeButton: !1,
closeOnClick: !0
}).setContent("<span class='school-popup'>" + e + "</span>");
},
buoyPopup: function(e) {
return L.popup({
closeButton: !1,
closeOnClick: !0
}).setContent("<span class='buoy-popup'>" + e + "</span>");
},
hashGeometry: function(e) {
return e.get("coordinates").join(",");
},
stratifyObservationsByLocation: function(e) {
return _(e).groupBy(function(e) {
return wb.MapView.hashGeometry(e.get("geometry"));
});
},
reverseCoordinates: function(e) {
return [ e[1], e[0] ];
}
},
components: [ {
name: "mapContainer",
classes: "enyo-fit"
}, {
kind: "wb.ZoomControl"
}, {
kind: "wb.ObservationInfoWindow",
showing: !1
}, {
kind: "wb.jumpToggle"
} ],
published: {
map: null,
userLayer: null,
buoyLayer: null,
schoolLayer: null,
radarLayer: null,
seaTempLayer: null,
userGroupLayers: {}
},
handlers: {
onObservationCollectionResponse: "handleObservationCollectionResponse",
onLayerVisibilityChange: "handleLayerVisibilityChange",
onUserLayerVisibilityChange: "handleUserLayerVisibilityChange"
},
events: {
onLayerVisibilityChange: "",
onUserLayerReady: ""
},
rendered: function() {
this.inherited(arguments), this.setMap(L.map(this.$.mapContainer.getId(), {
center: [ 44.183899, -67.964899 ],
zoom: 8,
minZoom: 2,
maxZoom: 11,
zoomControl: !1,
attributionControl: !1,
closePopupOnClick: !1
}));
},
mapChanged: function(e) {
if (!this.map) return;
this.map.invalidateSize(!0), this.$.zoomControl.setMap(this.map), this.$.jumpToggle.setMap(this.map), nbt.geo.leaflet.getNbtAttribution("bottomleft", "maroon").addTo(this.map), nbt.geo.leaflet.getArcGISTileLayer("Ocean_Basemap").addTo(this.map), this.setUserLayer(L.layerGroup()), this.setSchoolLayer(L.layerGroup()), this.setBuoyLayer(L.layerGroup()), this.setRadarLayer(L.tileLayer.wms("http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs", {
layers: "RAS_RIDGE_NEXRAD",
format: "image/png",
transparent: !0,
attribution: "Weather: NOAA",
reuseTiles: !0,
opacity: .5
})), this.setSeaTempLayer(L.tileLayer.wms("http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs", {
layers: "OBS_MAR_SSTF",
format: "image/png",
transparent: !0,
attribution: "Weather: NOAA",
reuseTiles: !0,
zoomOffset: 15
})), this.doUserLayerReady();
},
buildLocationGroupLayer: function(e) {
return this.userLayer || (this.userLayer = L.layerGroup(), this.userLayer.addTo(this.map)), L.featureGroup(_(_(_(e).values()).map(_.first)).map(function(t) {
var n = e[wb.MapView.hashGeometry(t.get("geometry"))], r = L.marker(wb.MapView.reverseCoordinates(t.get("geometry").get("coordinates")), {
icon: wb.MapView.userObservationMarker(n.length)
});
return r.bindPopup(this.$.observationInfoWindow.hasNode(), {
maxWidth: 540,
minWidth: 540
}), r.on("popupopen", function(e) {
this.$.observationInfoWindow.setObservationList(n), this.$.observationInfoWindow.setShowing(!0), e.popup._update();
}, this), r;
}, this));
},
schoolLayerChanged: function(e) {
if (!this.schoolLayer) return;
(new wb.api.ObserverCollection).fetch({
query: "/station",
success: L.bind(function(e, t, n) {
L.geoJson(t, {
pointToLayer: function(e, t) {
var n = L.marker(t, {
icon: wb.MapView.schoolMarker()
});
return n.on("mouseover", function(t) {
n.bindPopup(wb.MapView.schoolPopup(e.properties.label)).openPopup();
}), n;
}
}).addTo(this.schoolLayer);
}, this)
});
},
buoyLayerChanged: function(e) {
if (!this.buoyLayer) return;
(new wb.api.ObserverCollection).fetch({
query: "/buoy",
success: L.bind(function(e, t, n) {
L.geoJson(t, {
pointToLayer: function(e, t) {
var n = L.marker(t, {
icon: wb.MapView.buoyMarker()
});
return n.on("mouseover", function(t) {
n.bindPopup(wb.MapView.buoyPopup(e.properties.label)).openPopup();
}), n;
}
}).addTo(this.buoyLayer);
}, this)
});
},
onStudentLayerChange: function(e) {
this.studentLayer.addTo(this.map);
},
handleLayerVisibilityChange: function(e, t) {
if (!this[t.layer]) return;
t.visible ? this.map.addLayer(this[t.layer]) : this.map.removeLayer(this[t.layer]);
},
handleUserLayerVisibilityChange: function(e, t) {
var n = t.group + "Layer";
if (!this[n]) return;
t.visible ? this.userLayer.addLayer(this[n]) : this.userLayer.removeLayer(this[n]);
},
handleObservationCollectionResponse: function(e, t) {
var n = t.group + "Layer";
this[n] && this.userLayer.removeLayer(this[n]), this[n] = this.buildLocationGroupLayer(wb.MapView.stratifyObservationsByLocation(t.collection)), t.visible && this.userLayer.addLayer(this[n]);
}
});

// ControlView.js

enyo.kind({
name: "wb.ControlView",
kind: "enyo.Slideable",
classes: "layer-control",
max: 100,
value: 0,
unit: "%",
overMoving: !1,
deferreds: [],
cache: {},
events: {
onObservationCollectionResponse: "",
onLayerVisibilityChange: "",
onUserLayerVisibilityChange: ""
},
handlers: {
onUserLayerReady: "handleUserLayerReady"
},
published: {
dateRange: null,
selectedPhenomenonCategories: null,
groupCollections: null
},
components: [ {
kind: "onyx.Grabber",
classes: "pullout-grabbutton",
ontap: "onGrabberTap"
}, {
kind: "Scroller",
touch: !0,
classes: "enyo-fit",
components: [ {
name: "userDrawer",
kind: "wb.TitledDrawer",
title: "User Observations",
titleClasses: "heading",
drawerComponents: [ {
classes: "sub-content",
components: [ {
content: "Observations By: ",
classes: "sub-heading"
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "student",
kind: "onyx.Checkbox",
checked: !0,
onchange: "handleGroupFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/user_student.png"
}, {
tag: "label",
content: "Students"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "teacher",
kind: "onyx.Checkbox",
checked: !0,
onchange: "handleGroupFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/user_teacher.png"
}, {
tag: "label",
content: "Teachers"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "fisherman",
kind: "onyx.Checkbox",
checked: !0,
onchange: "handleGroupFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/user_fisherman.png"
}, {
tag: "label",
content: "Fishermen"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "scientist",
kind: "onyx.Checkbox",
checked: !0,
onchange: "handleGroupFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/user_scientist.png"
}, {
tag: "label",
content: "Scientists"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "community member",
kind: "onyx.Checkbox",
checked: !0,
onchange: "handleGroupFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/user_community.png"
}, {
tag: "label",
content: "Community Members"
} ]
}, {
content: "Observations With:",
classes: "sub-heading"
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "sky",
kind: "onyx.Checkbox",
classes: "filter-checkbox",
checked: !0,
filter: "Sky",
onchange: "handleCategoryFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/sky_icon.png"
}, {
tag: "label",
content: "Sky"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "preciptitation",
kind: "onyx.Checkbox",
classes: "filter-checkbox",
checked: !0,
filter: "Precipitation",
onchange: "handleCategoryFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/precipitation_icon.png"
}, {
tag: "label",
content: "Precipitation"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "ocean",
kind: "onyx.Checkbox",
classes: "filter-checkbox",
checked: !0,
filter: "Ocean",
onchange: "handleCategoryFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/ocean_icon.png"
}, {
tag: "label",
content: "Ocean"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
name: "media",
kind: "onyx.Checkbox",
classes: "filter-checkbox",
checked: !0,
filter: "Media",
onchange: "handleCategoryFilterChange"
}, {
kind: "Image",
classes: "mapLegend",
src: "assets/media_icon.png"
}, {
tag: "label",
content: "Media"
} ]
} ]
} ]
}, {
kind: "wb.TitledDrawer",
title: "Date Filter",
titleClasses: "heading",
classes: "date",
drawerComponents: [ {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
content: "From: ",
classes: "left-column"
}, {
kind: "onyx.InputDecorator",
alwaysLooksFocused: !0,
components: [ {
kind: "onyx.Input",
name: "fromDate",
classes: "",
placeholder: "MM/DD/YY",
onValueChanged: "handleDateFieldChange"
} ]
}, {
kind: "onyx.Button",
classes: "icon-calendar",
ontap: "doShowCalendar",
field: "fromDate"
} ]
}, {
controlClasses: "enyo-inline",
classes: "filter",
components: [ {
content: "To: ",
classes: "left-column"
}, {
kind: "onyx.InputDecorator",
alwaysLooksFocused: !0,
components: [ {
kind: "onyx.Input",
name: "toDate",
classes: "",
placeholder: "MM/DD/YY",
onValueChanged: "handleDateFieldChange"
} ]
}, {
kind: "onyx.Button",
classes: "icon-calendar",
ontap: "doShowCalendar",
field: "toDate"
} ]
}, {
kind: "onyx.Button",
content: "Today",
classes: "button",
ontap: "handleDateRangeOptionSelect"
}, {
kind: "onyx.Button",
content: "Past Week",
ontap: "handleDateRangeOptionSelect"
}, {
kind: "onyx.Button",
content: "Past Month",
ontap: "handleDateRangeOptionSelect"
} ]
} ]
} ],
create: function() {
this.inherited(arguments), (enyo.dom.getWindowWidth() <= 760 || wb.constants.isGallery) && this.toggleMinMax();
},
handleUserLayerReady: function(e, t) {
_.each(t.hide, function(e) {
this.$[e] && this.$[e].setShowing(!1);
}, this), this.collection = new wb.api.ObservationCollection, this.selectedPhenomenonCategories = [ "Sky", "Precipitation", "Ocean", "Media" ], this.setDateRange({
begin: moment().subtract("months", 1),
end: moment()
});
},
updateObservations: function() {
this.xhr && this.xhr.abort(), this.gallerySubset = null, this.xhr = this.collection.fetch({
data: {
inDateRange: {
begin: this.dateRange.begin.startOf("day").format(),
end: this.dateRange.end.endOf("day").format()
}
},
success: enyo.bind(this, "updateFilter"),
error: enyo.bind(this, "handleObservationJsonError")
});
},
handleObservationJsonError: function() {},
updateFilter: function(e) {
var t = _.map(this.selectedPhenomenonCategories, function(e) {
return e.toLowerCase();
});
if (wb.constants.isGallery) {
this.gallerySubset || (this.gallerySubset = _(this.collection.sortBy(function(e) {
return moment(e).utc().valueOf();
})).first(30));
var n = _(this.gallerySubset).filter(function(e) {
var n = _(e.get("categories")).keys(), r = _(n).intersection(t), i = n.length > 0 && r.length > 0 && this.$[e.get("observer").get("elggGroup").toLowerCase()].getValue();
return i;
}, this);
this.doObservationCollectionResponse({
collection: n
});
} else _(wb.api.User.groupList).each(function(e) {
var n = this.collection.filter(function(n) {
var r = n.get("categories"), i = _(_(r).keys()).intersection(t);
return i.length == t.length && n.get("observer").get("elggGroup").toLowerCase() == e.toLowerCase();
}, this);
this.doObservationCollectionResponse({
group: e,
collection: n,
visible: this.$[e].getValue()
});
}, this);
},
selectedPhenomenonCategoriesChanged: function(e) {
this.updateFilter();
},
dateRangeChanged: function(e) {
this.$.fromDate.setValue(this.dateRange.begin.format(wb.map.env.momentDateFormat)), this.$.toDate.setValue(this.dateRange.end.format(wb.map.env.momentDateFormat)), this.updateObservations();
},
handleDateFieldChange: function(e, t) {
console.debug(t);
var n = this.$.fromDate.getValue(), r = this.$.toDate.getValue();
if (!n || !r) return;
moment(r).isBefore(moment(n)) && (alert("Your end date must not occur before your start date"), e.setValue(null)), this.setDateRange({
begin: moment(n, wb.map.env.momentDateFormat),
end: moment(r, wb.map.env.momentDateFormat)
});
},
doShowCalendar: function(e, t) {
(new wb.DatePickCalendar({
field: this.$[e.field]
})).show();
},
handleDateRangeOptionSelect: function(e, t) {
var n = moment();
e.getContent() == "Past Week" ? n = n.subtract("weeks", 1) : e.getContent() == "Past Month" && (n = n.subtract("months", 1)), this.setDateRange({
begin: n,
end: moment()
});
},
onGrabberTap: function(e, t) {
this.toggleMinMax();
},
handleLayerToggle: function(e, t) {
var n = e.getValue();
e.getName() == "userLayer" && (this.$.student.setDisabled(!n), this.$.teacher.setDisabled(!n), this.$.fisherman.setDisabled(!n), this.$.scientist.setDisabled(!n), this.$["community member"].setDisabled(!n), this.$.sky.setDisabled(!n), this.$.preciptitation.setDisabled(!n), this.$.ocean.setDisabled(!n), this.$.media.setDisabled(!n), this.$.userDrawer.setOpen(n)), this.doLayerVisibilityChange({
layer: e.getName(),
visible: n
});
},
handleCategoryFilterChange: function(e, t) {
var n = e.getValue() ? _.union(this.selectedPhenomenonCategories, [ e.filter ]) : _.without(this.selectedPhenomenonCategories, e.filter);
this.setSelectedPhenomenonCategories(n);
},
handleGroupFilterChange: function(e, t) {
this.doUserLayerVisibilityChange({
group: e.getName(),
visible: e.getValue()
});
}
});

// GalleryView.js

enyo.kind({
name: "wb.GalleryView",
classes: "gallery",
published: {
limit: 48,
offset: 0
},
events: {
onUserLayerReady: "",
onUpdatePagination: ""
},
handlers: {
onObservationCollectionResponse: "handleObservationCollectionResponse",
onUserLayerVisibilityChange: "handleUserLayerVisibilityChange",
onOffsetChanged: "handleOffsetChanged"
},
cache: {},
create: function() {
this.inherited(arguments);
var e = nbt.generic.Util.getParameterMap();
_.isNumber(e.offset) && (this.offset = e.offset), this.doUserLayerReady({
hide: [ "datasources" ]
});
},
handleOffsetChanged: function(e, t) {
t.newOffset != "undefined" && (this.offset = t.newOffset, this.updateGalleryPanels());
},
updateGalleryPanels: function() {
if (this.collection.length == 0) return;
var e = [];
_.each(this.collection, function(t, n) {
n >= this.offset && e.length < this.limit && e.push(t);
}, this), _.each(_(this.cache).keys(), function(t) {
if (!this.cache[t]) return;
var n = _(e).findWhere({
id: t
});
n || (this.removeGalleryPanel(this.cache[t]), this.cache[t] = undefined);
}, this), _.each(e, function(e) {
if (this.cache[e.id]) return;
this.cache[e.id] = this.createComponent({
kind: "wb.GalleryPanel",
observation: e
}), this.cache[e.id].render();
}, this);
},
handleObservationCollectionResponse: function(e, t) {
this.collection = t.collection, this.offset = 0, this.updateGalleryPanels(), this.doUpdatePagination({
offset: this.offset,
total: this.collection.length,
limit: this.limit
});
},
handleUserLayerVisibilityChange: function(e, t) {
t.visible ? _.each(this.collection, function(e) {
if (t.group.toLowerCase() != e.get("observer").get("elggGroup").toLowerCase() || this.cache[e.id]) return;
this.cache[e.id] = this.createComponent({
kind: "wb.GalleryPanel",
observation: e
}), this.cache[e.id].render();
}, this) : _.each(_(this.cache).keys(), function(e) {
if (!this.cache[e]) return;
var n = this.cache[e];
t.group.toLowerCase() == n.observation.get("observer").get("elggGroup").toLowerCase() && (this.cache[e] = undefined, this.removeGalleryPanel(n));
}, this);
},
removeGalleryPanel: function(e) {
e.destroy(), this.render();
}
});

// App.js

enyo.kind({
name: "wb.Map",
handlers: {
onObservationCollectionResponse: "handleObservationCollectionResponse",
onUserLayerReady: "handleUserLayerReady",
onLayerVisibilityChange: "handleLayerVisibilityChange",
onUserLayerVisibilityChange: "handleUserLayerVisibilityChange"
},
components: [ {
kind: "wb.ControlView"
}, {
kind: "wb.MapView"
} ],
handleObservationCollectionResponse: function(e, t) {
this.waterfallDown("onObservationCollectionResponse", t);
},
handleUserLayerReady: function(e, t) {
this.waterfallDown("onUserLayerReady", t);
},
handleLayerVisibilityChange: function(e, t) {
this.waterfallDown("onLayerVisibilityChange", t);
},
handleUserLayerVisibilityChange: function(e, t) {
this.waterfallDown("onUserLayerVisibilityChange", t);
}
});

// Gallery.js

enyo.kind({
name: "wb.Gallery",
handlers: {
onUserLayerVisibilityChange: "handleUserLayerVisibilityChange",
onObservationCollectionResponse: "handleObservationCollectionResponse",
onUserLayerReady: "handleUserLayerReady",
onUpdatePagination: "handleUpdatePagination",
onOffsetChanged: "handleOffsetChanged"
},
components: [ {
kind: "wb.ControlView"
}, {
kind: "wb.GalleryView"
}, {
kind: "nbt.Pagination"
} ],
handleObservationCollectionResponse: function(e, t) {
this.waterfallDown("onObservationCollectionResponse", t);
},
handleUserLayerReady: function(e, t) {
this.waterfallDown("onUserLayerReady", t);
},
handleUserLayerVisibilityChange: function(e, t) {
this.waterfallDown("onUserLayerVisibilityChange", t);
},
handleUpdatePagination: function(e, t) {
this.$.pagination.update(e, t);
},
handleOffsetChanged: function(e, t) {
this.waterfallDown("onOffsetChanged", t);
}
});

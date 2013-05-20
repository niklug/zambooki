var JSON;
if (!JSON) {
    JSON = {};
}
(function() {
    function str(a, b) {
        var c, d, e, f, g = gap, h, i = b[a];
        if (i && typeof i === "object" && typeof i.toJSON === "function") {
            i = i.toJSON(a);
        }
        if (typeof rep === "function") {
            i = rep.call(b, a, i);
        }
        switch (typeof i) {
            case"string":
                return quote(i);
            case"number":
                return isFinite(i) ? String(i) : "null";
            case"boolean":
            case"null":
                return String(i);
            case"object":
                if (!i) {
                    return"null";
                }
                gap += indent;
                h = [];
                if (Object.prototype.toString.apply(i) === "[object Array]") {
                    f = i.length;
                    for (c = 0; c < f; c += 1) {
                        h[c] = str(c, i) || "null";
                    }
                    e = h.length === 0 ? "[]" : gap ? "[\n" + gap + h.join(",\n" + gap) + "\n" + g + "]" : "[" + h.join(",") + "]";
                    gap = g;
                    return e;
                }
                if (rep && typeof rep === "object") {
                    f = rep.length;
                    for (c = 0;
                            c < f; c += 1) {
                        if (typeof rep[c] === "string") {
                            d = rep[c];
                            e = str(d, i);
                            if (e) {
                                h.push(quote(d) + (gap ? ": " : ":") + e);
                            }
                        }
                    }
                } else {
                    for (d in i) {
                        if (Object.prototype.hasOwnProperty.call(i, d)) {
                            e = str(d, i);
                            if (e) {
                                h.push(quote(d) + (gap ? ": " : ":") + e);
                            }
                        }
                    }
                }
                e = h.length === 0 ? "{}" : gap ? "{\n" + gap + h.join(",\n" + gap) + "\n" + g + "}" : "{" + h.join(",") + "}";
                gap = g;
                return e;
            }
    }
    function quote(a) {
        escapable.lastIndex = 0;
        return escapable.test(a) ? '"' + a.replace(escapable, function(a) {
            var b = meta[a];
            return typeof b === "string" ? b : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + a + '"';
    }
    function f(a) {
        return a < 10 ? "0" + a : a;
    }
    "use strict";
    if (typeof Date.prototype.toJSON !== "function") {
        Date.prototype.toJSON = function(a) {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null;
        };
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function(a) {
            return this.valueOf();
        };
    }
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, gap, indent, meta = {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", '"': '\\"', "\\": "\\\\"}, rep;
    if (typeof JSON.stringify !== "function") {
        JSON.stringify = function(a, b, c) {
            var d;
            gap = "";
            indent = "";
            if (typeof c === "number") {
                for (d = 0; d < c; d += 1) {
                    indent += " ";
                }
            } else {
                if (typeof c === "string") {
                    indent = c;
                }
            }
            rep = b;
            if (b && typeof b !== "function" && (typeof b !== "object" || typeof b.length !== "number")) {
                throw new Error("JSON.stringify");
            }
            return str("", {"": a});
        };
    }
    if (typeof JSON.parse !== "function") {
        JSON.parse = function(text, reviver) {
            function walk(a, b) {
                var c, d, e = a[b];
                if (e && typeof e === "object") {
                    for (c in e) {
                        if (Object.prototype.hasOwnProperty.call(e, c)) {
                            d = walk(e, c);
                            if (d !== undefined) {
                                e[c] = d;
                            } else {
                                delete e[c];
                            }
                        }
                    }
                }
                return reviver.call(a, b, e);
            }
            var j;
            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function(a) {
                    return"\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
                j = eval("(" + text + ")");
                return typeof reviver === "function" ? walk({"": j}, "") : j;
            }
            throw new SyntaxError("JSON.parse");
        };
    }
})();
if (typeof(joms) == "undefined") {
    joms = {jQuery: window.jQuery, extend: function(a) {
            this.jQuery.extend(this, a);
        }};
}
var joms_message_sending = false;
joms.extend({plugins: {extend: function(a) {
            joms.jQuery.extend(joms.plugins, a);
        }, initialize: function() {
            joms.jQuery.each(joms.plugins, function(a, c) {
                try {
                    c.initialize();
                } catch (b) {
                }
            });
        }}, activities: {init: function() {
            joms.jQuery("#activity-stream-container li[data-streamid] [href=#deletePost]").live("click", function() {
                joms.activities.remove("", joms.jQuery(this).parents("[data-streamid]").data("streamid"));
            });
            joms.activities.initMap();
            joms.activities.loadNewStream();
        }, showMap: function(e, d) {
            if (joms.jQuery("li[data-streamid=" + e + "]").find(".cMapHeatzone").length == 0) {
                var b = joms.jQuery("li[data-streamid=" + e + "]").find(".cStream-Actions").width();
                var a = '<div class="cMapFade">';
                a += '<div class="cMapHeatzone"> </div>';
                a += '<div class="cMapFiller"></div>';
                a += '<img src="http://maps.google.com/maps/api/staticmap?center=' + d + "&amp;zoom=14&amp;size=" + b + "x150&amp;sensor=false&amp;markers=color:red|" + d + '" />';
                a += '<img src="http://maps.google.com/maps/api/staticmap?center=' + d + "&amp;zoom=5&amp;size=" + b + "x150&amp;sensor=false&amp;markers=color:red|" + d + '" />';
                a += '<img src="http://maps.google.com/maps/api/staticmap?center=' + d + "&amp;zoom=2&amp;size=" + b + "x150&amp;sensor=false&amp;markers=color:red|" + d + '" />';
                a += "</div>";
                joms.jQuery("#newsfeed-map-" + e + " .newsfeed-mapFade").append(a);
                joms.jQuery("li[data-streamid=" + e + "]").find(".cStream-Actions").prepend(a);
                var c = (b / 2) - 15;
                joms.jQuery("li[data-streamid=" + e + "]").find(".cMapHeatzone").css({top: "40px", left: c + "px"});
                joms.maps.init();
            }
            if (joms.jQuery("#video-" + e).length) {
                var b = joms.jQuery("#video-" + e).find(".video-map-location").width();
                var a = '<img src="http://maps.google.com/maps/api/staticmap?center=' + d + "&amp;zoom=5&amp;size=" + b + "x250&amp;sensor=false&amp;markers=color:red|" + d + '" />';
                if (joms.jQuery(".video-map").height() == 0) {
                    joms.jQuery("#video-" + e).find(".video-map-location").html(a);
                    joms.jQuery("#video-" + e).find(".video-map").css({height: "auto"});
                } else {
                    joms.jQuery("#video-" + e).find(".video-map-location").html("");
                    joms.jQuery("#video-" + e).find(".video-map").css({height: "auto"});
                }
            }
        }, getContent: function(a) {
            jax.call("community", "activities,ajaxGetContent", a);
        }, setContent: function(a, b) {
            joms.jQuery("#profile-newsfeed-item-content-" + a).html(b).removeClass("small profile-newsfeed-item-action").addClass("newsfeed-content-hidden").slideDown();
        }, showVideo: function(a) {
            joms.jQuery("#profile-newsfeed-item-content-" + a + " .video-object").slideDown();
            joms.jQuery("#profile-newsfeed-item-content-" + a + " .video-object embed").css("width", joms.jQuery("#profile-newsfeed-item-content-" + a).width());
        }, selectCustom: function(a) {
            if (a == "predefined") {
                joms.jQuery("#custom-text").css("display", "none");
                joms.jQuery("#custom-predefined").css("display", "block");
            } else {
                joms.jQuery("#custom-text").css("display", "block");
                joms.jQuery("#custom-predefined").css("display", "none");
            }
        }, addCustom: function() {
            if (joms.jQuery("input[name=custom-message]:checked").val() == "predefined") {
                var a = joms.jQuery("#custom-predefined").val();
                var b = joms.jQuery("#custom-predefined :selected").html();
                if (a != "default") {
                    jax.call("community", "activities,ajaxAddPredefined", a, b);
                }
            } else {
                customText = joms.jQuery.trim(joms.jQuery("#custom-text").val());
                if (customText.length != 0) {
                    jax.call("community", "activities,ajaxAddPredefined", "system.message", customText);
                }
            }
        }, append: function(a) {
            var b = joms.jQuery(a).find("ul.cStreamList li");
            joms.jQuery("#activity-more,#activity-exclusions").remove();
            joms.jQuery("#activity-stream-container ul.cStreamList").append(a);
            joms.jQuery("body").focus();
        }, initMap: function() {
            if (joms.jQuery(".newsfeed-mapFade") != null || joms.jQuery(".newsfeed-map-heatzone") != null) {
                if (joms.jQuery(".newsfeed-mapFade").length) {
                    joms.jQuery(".newsfeed-mapFade").live("mouseover", function(a) {
                        joms.jQuery(this).find("img:eq(2)").fadeOut(0);
                    });
                    joms.jQuery(".newsfeed-mapFade").live("mouseout", function(a) {
                        joms.jQuery(this).find("img:eq(2)").fadeIn(0);
                    });
                    joms.jQuery(".newsfeed-map-heatzone").live("mouseover", function(a) {
                        joms.jQuery(this).parent().find("img:eq(1)").fadeOut();
                    });
                    joms.jQuery(".newsfeed-map-heatzone").live("mouseout", function(a) {
                        joms.jQuery(this).parent().find("img:eq(1)").fadeIn(0);
                    });
                }
            }
        }, more: function() {
            var f = joms.jQuery("#activity-stream-container ul.cStreamList");
            var b = "";
            if (f.find("li[data-streamid]").length != 0) {
                b = f.find("li[data-streamid]").last().data("streamid");
            }
            var d = "";
            var e = "";
            if (joms.jQuery("#apptype").length != 0) {
                d = joms.jQuery("#apptype").val();
            }
            if (joms.jQuery("#appid").length != 0) {
                e = joms.jQuery("#appid").val();
            }
            var c = f.data("filter");
            var a = f.data("filterid");
            joms.jQuery("#activity-more .more-activity-text").hide();
            joms.jQuery("#activity-more .loading").show();
            jax.call("community", "activities,ajaxGetOlderActivities", b, c, a);
        }, prependOldStream: function(b) {
            var a = joms.jQuery(b).find("li");
            joms.jQuery("li[data-streamid]").last().after(a);
            joms.jQuery("#activity-more .more-activity-text").show();
            joms.jQuery("#activity-more .loading").hide();
        }, loadNewStream: function() {
            var a = joms.jQuery("#activity-stream-container ul.cStreamList");
            jax.loadingFunction = function() {
            };
            jax.call("community", "activities,ajaxGetRecentActivitiesCount", a.find("li[data-streamid]").first().data("streamid"), a.data("filter"), a.data("filterid"));
        }, announceNewStream: function(c, a, b) {
            if (c > 0) {
                joms.jQuery(".joms-latest-activities-container").html('<div class="cAlert alert-success"><a href="#showNewStream" onclick="joms.activities.showNewStream();">' + b + "</div>").show();
            } else {
                joms.jQuery(".joms-latest-activities-container").hide();
            }
            if (a != 0) {
                setTimeout("joms.activities.loadNewStream()", a);
            }
        }, appendNewStream: function(c, a) {
            var b = joms.jQuery(c).find("li");
            joms.jQuery("li[data-streamid]").first().before(b);
            joms.jQuery(".joms-latest-activities-container").html("").hide();
        }, showNewStream: function() {
            var a = joms.jQuery("#activity-stream-container ul.cStreamList");
            jax.call("community", "activities,ajaxGetRecentActivities", a.find("li[data-streamid]").first().data("streamid"), a.data("filter"), a.data("filterid"));
        }, remove: function(c, a) {
            var b = "jax.call('community', 'activities,ajaxConfirmDeleteActivity', '" + c + "', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }}, apps: {windowTitle: "", toggle: function(a) {
            joms.jQuery(a).children(".app-box-actions").slideToggle("fast");
            joms.jQuery(a).children(".app-box-footer").slideToggle("fast");
            joms.jQuery(a).children(".app-box-content").slideToggle("fast", function() {
                joms.jQuery.cookie(a, joms.jQuery(this).css("display"));
                joms.jQuery(a).toggleClass("collapse", (joms.jQuery(this).css("display") == "none"));
            });
        }, showAboutWindow: function(a) {
            var b = "jax.call('community', 'apps,ajaxShowAbout', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, showPrivacyWindow: function(a) {
            var b = "jax.call('community', 'apps,ajaxShowPrivacy', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, showSettingsWindow: function(c, a) {
            var b = "jax.call('community', 'apps,ajaxShowSettings', '" + c + "', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, savePrivacy: function() {
            var b = joms.jQuery("input[name=privacy]:checked").val();
            var a = joms.jQuery("input[name=appname]").val();
            jax.call("community", "apps,ajaxSavePrivacy", a, b);
        }, saveSettings: function() {
            jax.call("community", "apps,ajaxSaveSettings", jax.getFormValues("appSetting"));
        }, remove: function(a) {
            var b = "jax.call('community', 'apps,ajaxRemove', '" + a + "');";
            cWindowShow(b, this.windowTitle, 450, 100);
        }, add: function(a) {
            jax.call("community", "apps,ajaxAdd", a);
        }, initToggle: function() {
            joms.jQuery(".app-box").each(function() {
                var a = "#" + joms.jQuery(this).attr("id");
                if (joms.jQuery.cookie(a) == "none") {
                    joms.jQuery(a).addClass("collapse");
                    joms.jQuery(a).children(".app-box-actions").css("display", "none");
                    joms.jQuery(a).children(".app-box-footer").css("display", "none");
                    joms.jQuery(a).children(".app-box-content").css("display", "none");
                }
            });
        }}, bookmarks: {show: function(a) {
            var b = "jax.call('community', 'bookmarks,ajaxShowBookmarks','" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, email: function(a) {
            var d = jax.getFormValues("bookmarks-email");
            var c = d[1][1];
            var b = d[0][1];
            var e = "jax.call('community', 'bookmarks,ajaxEmailPage','" + a + "','" + b + "',\"" + c + '");';
            cWindowShow(e, "", 450, 100);
        }}, report: {emptyMessage: "", checkReport: function() {
            if (joms.jQuery("#report-message").val() == "") {
                joms.jQuery("#report-message-error").html(this.emptyMessage).css("color", "red");
                return false;
            }
            return true;
        }, showWindow: function(a, b) {
            var c = 'jax.call("community" , "system,ajaxReport" , "' + a + '","' + location.href + '" ,' + b + ");";
            cWindowShow(c, "", 450, 100);
        }, submit: function(b, c, d) {
            if (joms.report.checkReport()) {
                var e = jax.getFormValues("report-form");
                var a = 'jax.call("community", "system,ajaxSendReport","' + b + '","' + location.href + '","' + e[1][1] + '" , ' + d + ")";
                cWindowShow(a, "", 450, 100);
            }
        }}, featured: {add: function(c, a) {
            var b = "jax.call('community', '" + a + ",ajaxAddFeatured', '" + c + "');";
            cWindowShow(b, "", 450, 100);
        }, remove: function(c, a) {
            var b = "jax.call('community','" + a + ",ajaxRemoveFeatured','" + c + "');";
            cWindowShow(b, "", 450, 100);
        }}, flash: {enabled: function() {
            try {
                try {
                    var a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
                    try {
                        a.AllowScriptAccess = "always";
                    } catch (b) {
                        return"6,0,0";
                    }
                } catch (b) {
                }
                return new ActiveXObject("ShockwaveFlash.ShockwaveFlash").GetVariable("$version").replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
            } catch (b) {
                try {
                    if (navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin) {
                        return(navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
                    }
                } catch (b) {
                }
            }
            return false;
        }}, invitation: {showForm: function(g, f, e, d, b) {
            var c = 'jax.call("community", "system,ajaxShowInvitationForm","' + g + '","' + f + '","' + e + '","' + d + '","' + b + '")';
            var a = 520;
            a = d != "0" ? a : a - 108;
            a = b != "0" ? a : a - 108;
            cWindowShow(c, "", 550, a);
        }, send: function(b, a) {
            jax.call("community", "system,ajaxSubmitInvitation", b, a, jax.getFormValues("community-invitation-form"));
        }, selectMember: function(a) {
            if (joms.jQuery(a + " input").is(":checked")) {
                joms.jQuery(a).clone().appendTo("#community-invited-list");
                joms.jQuery(a).remove();
                joms.jQuery(a).addClass("invitation-item-invited").children(".invitation-checkbox").show();
            } else {
                joms.jQuery(a).remove();
            }
        }, selectMemberUnique: function(a) {
            if (joms.jQuery(a + " input").is(":checked")) {
                joms.jQuery("#community-invited-list").empty();
                joms.jQuery(a).clone().appendTo("#community-invited-list");
                joms.jQuery(a).remove();
                joms.jQuery(a).addClass("invitation-item-invited").children(".invitation-checkbox").show();
            } else {
                joms.jQuery(a).remove();
            }
        }, filterMember: function() {
            joms.jQuery("#community-invitation-list li").each(function(a) {
                element = joms.jQuery(this).attr("id");
                if (joms.jQuery("#community-invited-list #" + element).is("li")) {
                    joms.jQuery(this).remove();
                }
                if (joms.jQuery("#inbox-selected-to").length > 0) {
                    if (joms.jQuery("#inbox-selected-to #" + element).is("li")) {
                        joms.jQuery(this).remove();
                    }
                }
            });
        }, showResult: function() {
            joms.jQuery("#cInvitationTabContainer div").removeClass("active");
            joms.jQuery("#cInvitationTabContainer #community-invitation").addClass("active");
        }, showSelected: function() {
            joms.jQuery("#cInvitationTabContainer div").removeClass("active");
            joms.jQuery("#cInvitationTabContainer #community-invited").addClass("active");
        }, selectNone: function(a) {
            joms.jQuery(a).find("li").each(function() {
                joms.jQuery(this).remove();
            });
        }, selectAll: function(a) {
            joms.jQuery(a).find("li").each(function() {
                joms.jQuery(this).find("input").attr("checked", "checked");
                if (joms.jQuery(this).find("input").attr("checked")) {
                    joms.invitation.selectMember("#" + joms.jQuery(this).attr("id"));
                }
            });
        }}, memberlist: {submit: function() {
            if (joms.jQuery("input#title").val() == "") {
                joms.jQuery("#filter-title-error").show();
                return false;
            }
            if (joms.jQuery("textarea#description").val() == "") {
                joms.jQuery("#filter-description-error").show();
                return false;
            }
            joms.jQuery("#jsform-memberlist-addlist").submit();
        }, showSaveForm: function(k, b) {
            var k = k.split(",");
            var j = Array();
            var e = joms.jQuery("#avatar:checked").val() != 1 ? 0 : 1;
            for (var c = 0; c < k.length; c++) {
                var l = new Array();
                var g = "";
                var h = k[c];
                if ((b["fieldType" + h] == "date" || b["fieldType" + h] == "birthdate") && b["condition" + h] == "between") {
                    g = b["value" + k[c]] + "," + b["value" + k[c] + "_2"];
                } else {
                    g = b["value" + k[c]];
                }
                j[c] = new Array("field=" + b["field" + k[c]], "condition=" + b["condition" + k[c]], "fieldType=" + b["fieldType" + k[c]], "value=" + g);
            }
            var a = "";
            for (var f = 0; f < j.length; f++) {
                a += '"' + j[f] + '"';
                if ((f + 1) != j.length) {
                    a += ",";
                }
            }
            var d = 'jax.call("community", "memberlist,ajaxShowSaveForm","' + joms.jQuery("input[name=operator]:checked").val() + '","' + e + '",' + a + ");";
            cWindowShow(d, "", 470, 300);
        }}, notifications: {showWindow: function() {
            var a = 'jax.call("community", "notification,ajaxGetNotification", "")';
            cWindowShow(a, "", 450, 100);
        }, updateNotifyCount: function() {
            var a = joms.jQuery("#toolbar-item-notify-count").text();
            if (joms.jQuery.trim(a) != "" && a > 0) {
                a = a - 1;
                joms.jQuery("#toolbar-item-notify-count").html(a);
                if (a == 0) {
                    joms.jQuery("#toolbar-item-notify").hide();
                    setTimeout("cWindowHide()", 1000);
                }
            }
        }, showRequest: function() {
            var a = 'jax.call("community", "notification,ajaxGetRequest", "")';
            cMiniWindowShow(a, "", 450, 100);
        }, showInbox: function() {
            var a = 'jax.call("community", "notification,ajaxGetInbox", "")';
            cMiniWindowShow(a, "", 450, 100);
        }, showUploadPhoto: function(a, b) {
            var c = 'jax.call("community", "photos,ajaxUploadPhoto","' + a + '","' + b + '")';
            cWindowShow(c, "", 600, 400);
        }}, filters: {bind: function() {
            var a = this.loading;
            joms.jQuery(document).ready(function() {
                joms.jQuery(".newest-member").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetNewestMember", frontpageUsers);
                    }
                });
                joms.jQuery(".active-member").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetActiveMember", frontpageUsers);
                    }
                });
                joms.jQuery(".popular-member").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetPopularMember", frontpageUsers);
                    }
                });
                joms.jQuery(".featured-member").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetFeaturedMember", frontpageUsers);
                    }
                });
                joms.jQuery(".all-activity").bind("click", function() {
                    a(joms.jQuery(this).attr("class"));
                    joms.ajax.call("frontpage,ajaxGetActivities", ["all"], {success: function() {
                            alert("asdfsaf");
                        }});
                    joms.jQuery(".all-activity").parents("li.filter").addClass("active").siblings().removeClass("active");
                });
                joms.jQuery(".me-and-friends-activity").bind("click", function() {
                    a(joms.jQuery(this).attr("class"));
                    joms.ajax.call("frontpage,ajaxGetActivities", ["me-and-friends"], {success: function() {
                        }});
                    joms.jQuery(".me-and-friends-activity").parents("li.filter").addClass("active").siblings().removeClass("active");
                });
                joms.jQuery(".active-profile-and-friends-activity").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetActivities", "active-profile-and-friends", joms.user.getActive());
                    }
                });
                joms.jQuery(".active-profile-activity").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetActivities", "active-profile", joms.user.getActive());
                    }
                });
                joms.jQuery(".p-active-profile-and-friends-activity").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetActivities", "active-profile-and-friends", joms.user.getActive(), "profile");
                    }
                });
                joms.jQuery(".p-active-profile-activity").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetActivities", "active-profile", joms.user.getActive(), "profile");
                    }
                });
                joms.jQuery(".newest-videos").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetNewestVideos", frontpageVideos);
                    }
                });
                joms.jQuery(".popular-videos").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetPopularVideos", frontpageVideos);
                    }
                });
                joms.jQuery(".featured-videos").bind("click", function() {
                    if (!joms.jQuery(this).hasClass("active-state")) {
                        a(joms.jQuery(this).attr("class"));
                        jax.call("community", "frontpage,ajaxGetFeaturedVideos", frontpageVideos);
                    }
                });
                joms.jQuery(".popular-member").css("border-right", "0").css("padding-right", "0");
            });
        }, loading: function(a) {
            elParent = joms.jQuery("." + a).parent().parent().attr("id");
            if (typeof elParent == "undefined") {
                elParent = joms.jQuery("." + a).parent().attr("id");
            }
            joms.jQuery("#" + elParent + " .loading").show();
            joms.jQuery("#" + elParent + " a").removeClass("active-state");
            joms.jQuery("." + a).addClass("active-state");
        }, hideLoading: function() {
            joms.jQuery(".loading").hide();
            joms.jQuery(".jomTipsJax").addClass("jomTips");
            joms.tooltip.setup();
        }}, groups: {invitation: {accept: function(a) {
                jax.call("community", "groups,ajaxAcceptInvitation", a);
            }, reject: function(a) {
                jax.call("community", "groups,ajaxRejectInvitation", a);
            }}, addInvite: function(a) {
            var b = joms.jQuery("#" + a).parent().attr("id");
            if (b == "friends-list") {
                joms.jQuery("#friends-invited").append(joms.jQuery("#" + a)).html();
            } else {
                joms.jQuery("#friends-list").append(joms.jQuery("#" + a)).html();
            }
        }, removeTopic: function(d, a, b) {
            var c = 'jax.call("community","groups,ajaxShowRemoveDiscussion", "' + a + '","' + b + '");';
            cWindowShow(c, d, 450, 100);
        }, lockTopic: function(d, a, b) {
            var c = 'jax.call("community","groups,ajaxShowLockDiscussion", "' + a + '","' + b + '");';
            cWindowShow(c, d, 450, 100);
        }, editBulletin: function() {
            if (joms.jQuery("#bulletin-edit-data").css("display") == "none") {
                joms.jQuery("#bulletin-edit-data").show();
            } else {
                joms.jQuery("#bulletin-edit-data").hide();
            }
        }, removeBulletin: function(d, a, b) {
            var c = 'jax.call("community", "groups,ajaxShowRemoveBulletin", "' + a + '","' + b + '");';
            cWindowShow(c, d, 450, 100);
        }, unpublish: function(a) {
            jax.call("community", "groups,ajaxUnpublishGroup", a);
        }, leave: function(a) {
            var b = 'jax.call("community", "groups,ajaxShowLeaveGroup", "' + a + '");';
            cWindowShow(b, "", 450, 100);
        }, join: function(a, b) {
            jax.call("community", "groups,ajaxJoinGroup", [a], [b]);
        }, joinComplete: function(a) {
            joms.jQuery("input#join").val(a);
            joms.jQuery(".loading-icon").hide();
            joms.jQuery("#add-reply").show();
            joms.jQuery("#add-reply").click(function() {
                joms.jQuery("div#community-groups-wrap").hide();
                joms.jQuery("textarea#wall-message").css("width", "100%");
                joms.jQuery(".reply-form").show();
            });
        }, edit: function() {
            joms.jQuery("#community-group-info .cdata").each(function() {
                if (joms.jQuery(this).next().html() && joms.jQuery(this).css("display") != "none") {
                    joms.jQuery(this).css("display", "none");
                } else {
                    joms.jQuery(this).css("display", "block");
                }
            });
            joms.jQuery("#community-group-info .cinput").each(function() {
                if (joms.jQuery(this).css("display") == "none") {
                    joms.jQuery(this).css("display", "block");
                } else {
                    joms.jQuery(this).css("display", "none");
                }
            });
            if (joms.jQuery("div#community-group-info-actions").css("display") != "none") {
                joms.jQuery("div#community-group-info-actions").css("display", "none");
            } else {
                joms.jQuery("div#community-group-info-actions").css("display", "block");
            }
        }, save: function(b) {
            var d = joms.jQuery("#community-group-name").val();
            var f = joms.jQuery("#community-group-description").val();
            var c = joms.jQuery("#community-group-website").val();
            var e = joms.jQuery("#community-group-category").val();
            var a = joms.jQuery("input[@name='group-approvals']:checked").val();
            jax.call("community", "groups,ajaxSaveGroup", b, d, f, c, e, a);
        }, update: function(d, c, a, b) {
            joms.jQuery("#community-group-data-name").html(d);
            joms.jQuery("#community-group-data-description").html(c);
            joms.jQuery("#community-group-data-website").html(a);
            joms.jQuery("#community-group-data-category").html(b);
            this.edit();
        }, deleteGroup: function(a) {
            var b = "jax.call('community', 'groups,ajaxWarnGroupDeletion', '" + a + "');";
            cWindowShow(b, "", 450, 100, "error");
        }, toggleSearchSubmenu: function(a) {
            joms.jQuery(a).next("ul").toggle().find("input[type=text]").focus();
        }, confirmMemberRemoval: function(a, b) {
            var c = function() {
                jax.call("community", "groups,ajaxConfirmMemberRemoval", a, b);
            };
            cWindowShow(c, "", 450, 80, "warning");
        }, removeMember: function(a, b) {
            var c = joms.jQuery("#cWindow input[name=block]").attr("checked");
            if (c) {
                jax.call("community", "groups,ajaxBanMember", a, b);
            } else {
                jax.call("community", "groups,ajaxRemoveMember", a, b);
            }
        }}, photos: {multiUpload: {label: {filename: "Filename", size: "Size", status: "Status", filedrag: "Drag files here.", addfiles: "Add Files", startupload: "Start Upload", invalidfiletype: "Invalid File Type", exceedfilesize: "Image file size exceeded limit", stopupload: "Stop Upload"}, groupid: "", noticeDivId: "photoUploaderNotice", defaultMsg: "", groupEmptyValidateMsg: "", uploadingCreateMsg: "", uploadingSelectMsg: "", refreshNeeded: false, defaultUploadUrl: "index.php?option=com_community&view=photos&task=multiUpload", maxFileSize: "10mb", _init: function(a, c) {
                if (a != undefined && parseInt(a) > 0) {
                    joms.photos.multiUpload.groupid = a;
                }
                if (c != undefined && typeof c == "object") {
                    for (var b in c) {
                        joms.photos.multiUpload[b] = c[b];
                    }
                }
                pluploader = joms.jQuery("#multi_uploader").pluploadQueue({runtimes: "gears,html5,browserplus,html4", url: "index.php?option=com_community&view=photos&task=multiUpload", max_file_size: joms.photos.multiUpload.maxFileSize, chunk_size: joms.photos.multiUpload.maxFileSize, unique_names: true, resize: {width: 2100, height: 2100, quality: 90}, filters: [{title: "Image files", extensions: "jpg,gif,png,jpeg"}], flash_swf_url: "components/com_community/assets/multiupload_js/plupload.flash.swf", silverlight_xap_url: "components/com_community/assets/multiupload_js/plupload.silverlight.xap", preinit: {Init: function(d, e) {
                            joms.photos.multiUpload.log("[Init]", "Info:", e, "Features:", d.features);
                        }, BeforeUpload: function() {
                            if (joms.jQuery("#new-album").css("display") == "inline") {
                                if (joms.photos.multiUpload.groupid != "" && joms.jQuery("#album-name").val() == "") {
                                    joms.photos.multiUpload.stopUploading();
                                    alert(joms.photos.multiUpload.groupEmptyValidateMsg);
                                    return;
                                } else {
                                    joms.photos.multiUpload.stopUploading();
                                    var e = joms.jQuery("#album-name").val().trim();
                                    jax.call("community", "photos,ajaxCreateAlbum", e, joms.photos.multiUpload.groupid);
                                }
                            } else {
                                var d = joms.jQuery("#albumid option:selected").html();
                                var f = joms.photos.multiUpload.uploadingSelectMsg.replace("%1$s", "<strong>" + d + "</strong>");
                                joms.photos.multiUpload.displayNotice(f);
                                joms.photos.multiUpload.assignUploadUrl(joms.photos.multiUpload.getSelectedAlbumId());
                            }
                            joms.photos.multiUpload.hideShowInput(true);
                            joms.jQuery("#photo-uploader").css("overflow", "hidden");
                        }, UploadFile: function(d, e) {
                            joms.photos.multiUpload.log("[UploadFile]", e);
                            joms.jQuery(".plupload_start").addClass("plupload_disabled");
                        }, FileUploaded: function(j, f, d) {
                            try {
                                var h = joms.jQuery.parseJSON(d.response);
                            } catch (g) {
                                joms.photos.multiUpload.removeQueue(f.id);
                                pluploader.pluploadQueue().stop();
                                joms.photos.multiUpload.displayNotice("<strong>Error Uploading.</strong>");
                            }
                            var k = pluploader.pluploadQueue().getFile(f.id);
                            if (h != undefined && k != undefined) {
                                if (h.error != undefined && h.photoId == "") {
                                    joms.photos.multiUpload.removeQueue(f.id);
                                    joms.jQuery("#upload-footer").show();
                                    joms.jQuery("#message-between").hide();
                                    joms.jQuery(".add-more").hide();
                                    try {
                                        pluploader.pluploadQueue().stop();
                                    } catch (g) {
                                    }
                                    joms.photos.multiUpload.showThumbnail();
                                    joms.photos.multiUpload.displayNotice("<strong>" + h.msg + "</strong>");
                                    joms.jQuery(".plupload_file_name span").bind("click", function(e) {
                                        joms.photos.multiUpload.editTitle(e);
                                    });
                                } else {
                                    k.imgSrc = h.info;
                                    k.photoId = h.photoId;
                                    joms.photos.multiUpload.refreshNeeded = true;
                                }
                            }
                        }, FilesAdded: function(d, e) {
                            joms.jQuery(".plupload_file_name span").unbind("click");
                            joms.jQuery(".plupload_button.plupload_start").show();
                            joms.jQuery(".plupload_button.plupload_start").css("display", "inline-block");
                        }, FilesRemoved: function(d, e) {
                            if (pluploader.pluploadQueue().files.length == 0) {
                                joms.jQuery(".plupload_button.plupload_start").hide();
                            }
                        }, UploadComplete: function(d) {
                            joms.photos.multiUpload.uploadCompleteShow();
                            setTimeout("joms.photos.multiUpload.showThumbnail(); joms.photos.multiUpload.enableEditTitle();", 1000);
                        }}, init: {Refresh: function(d) {
                            joms.photos.multiUpload.log("[Refresh]");
                        }, StateChanged: function(d) {
                            joms.photos.multiUpload.log("[StateChanged]", d.state == plupload.STARTED ? "STARTED" : "STOPPED");
                        }, QueueChanged: function(d) {
                            joms.photos.multiUpload.log("[QueueChanged]");
                        }, UploadProgress: function(d, e) {
                            joms.photos.multiUpload.log("[UploadProgress]", "File:", e, "Total:", d.total);
                        }, FilesAdded: function(d, e) {
                            joms.photos.multiUpload.log("[FilesAdded]");
                            plupload.each(e, function(f) {
                                joms.photos.multiUpload.log("  File:", f);
                            });
                        }, FilesRemoved: function(d, e) {
                            joms.photos.multiUpload.log("[FilesRemoved]");
                            plupload.each(e, function(f) {
                                joms.photos.multiUpload.log("  File:", f);
                            });
                        }, FileUploaded: function(d, e, f) {
                            joms.photos.multiUpload.log("[FileUploaded] File:", e, "Info:", f);
                        }, ChunkUploaded: function(d, e, f) {
                            joms.photos.multiUpload.log("[ChunkUploaded] File:", e, "Info:", f);
                        }, Error: function(d, e) {
                            if (e.file) {
                                joms.photos.multiUpload.log("[error]", e, "File:", e.file);
                            } else {
                                joms.photos.multiUpload.log("[error]", e);
                            }
                        }}});
                if ((pluploader.pluploadQueue().runtime == "html4") || (joms.jQuery.browser.msie && (parseInt(joms.jQuery.browser.version.substr(0, 1)) <= 7)) || (joms.jQuery.browser.mozilla && (joms.jQuery.browser.version.slice(0, 3)) == 1.9)) {
                    joms.photos.multiUpload.getBrowserNotSupport();
                }
                joms.jQuery(".plupload_button.plupload_start").hide();
                joms.jQuery("#cwin_close_btn").click(function() {
                    if (joms.photos.multiUpload.refreshNeeded) {
                        joms.photos.multiUpload.refreshBrowser();
                    }
                });
            }, log: function() {
                var a = "";
                plupload.each(arguments, function(b) {
                    var c = "";
                    if (typeof(b) != "string") {
                        plupload.each(b, function(e, d) {
                            if (b instanceof plupload.File) {
                                switch (e) {
                                    case plupload.QUEUED:
                                        e = "QUEUED";
                                        break;
                                    case plupload.UPLOADING:
                                        e = "UPLOADING";
                                        break;
                                    case plupload.FAILED:
                                        e = "FAILED";
                                        break;
                                    case plupload.DONE:
                                        e = "DONE";
                                        break;
                                    }
                            }
                            if (typeof(e) != "function") {
                                c += (c ? ", " : "") + d + "=" + e;
                            }
                        });
                        a += c + " ";
                    } else {
                        a += b + " ";
                    }
                });
                joms.jQuery("#log").val(joms.jQuery("#log").val() + a + "\r\n");
            }, getSelectedAlbumId: function() {
                if (typeof pluploader == "undefined") {
                    return false;
                }
                if (joms.jQuery("#album-name").length <= 0) {
                    return false;
                }
                if (joms.jQuery("#album-name").attr("albumid") != undefined && parseInt(joms.jQuery("#album-name").attr("albumid")) > 0 && joms.jQuery("#new-album").css("display") == "inline") {
                    return joms.jQuery("#album-name").attr("albumid");
                } else {
                    return joms.jQuery("#albumid").val();
                }
            }, showThumbnail: function() {
                var d = pluploader.pluploadQueue();
                for (var b = 0; b < d.files.length; b++) {
                    var c = document.createElement("img");
                    c.id = "plupload_" + d.files[b].id;
                    c.setAttribute("class", "plupupload_thumbnail");
                    c.src = d.files[b].imgSrc;
                    joms.jQuery("#" + d.files[b].id + ".plupload_done .plupload_file_name").append(c);
                }
                var a = joms.jQuery("#albumid option:selected").html();
                var e = joms.photos.multiUpload.uploadedCompleteMsg.replace("%1$s", "<strong>" + a + "</strong>");
                joms.photos.multiUpload.displayNotice(e);
            }, assignUploadUrl: function(b) {
                if (typeof pluploader == "undefined") {
                    return false;
                }
                var a = joms.photos.multiUpload.defaultUploadUrl;
                if (a.match(/&+/)) {
                    a = a + "&albumid=" + b;
                } else {
                    a = a + "?albumid=" + b;
                }
                joms.jQuery("#multi_uploader").pluploadQueue().settings.url = a;
            }, assignNewAlbum: function(b, d) {
                if (typeof pluploader == "undefined") {
                    return false;
                }
                if (joms.jQuery("#albumid").length <= 0) {
                    return false;
                }
                if (d != undefined) {
                    var a = d;
                    var c = new Option(a, b);
                    joms.jQuery("#albumid").append(c).val(b);
                    joms.photos.multiUpload.assignUploadUrl(b);
                    joms.jQuery("#album-name").val("");
                } else {
                    joms.jQuery("#albumid").val(b);
                    var a = joms.jQuery("#albumid option:selected").html();
                }
                var e = joms.photos.multiUpload.uploadingCreateMsg.replace("%1$s", "<strong>" + a + "</strong>");
                joms.photos.multiUpload.displayNotice(e);
                joms.photos.multiUpload.showExistingAlbum();
                setTimeout("joms.jQuery('#multi_uploader').pluploadQueue().start()", 500);
            }, showExistingAlbum: function() {
                joms.jQuery("#select-album").css("display", "inline");
                joms.jQuery("#new-album").hide();
                joms.jQuery("#newalbum").hide();
                if (pluploader.pluploadQueue().files.length > 0) {
                    joms.jQuery(".plupload_start").removeClass("plupload_disabled");
                }
            }, createNewAlbum: function() {
                joms.jQuery("#select-album").hide();
                joms.jQuery("#newalbum").show();
                joms.jQuery("#new-album").css("display", "inline");
            }, startUploading: function() {
                joms.jQuery("#multi_uploader").pluploadQueue().start();
            }, stopUploading: function() {
                joms.jQuery("#multi_uploader").pluploadQueue().stop();
            }, goToAlbum: function(a) {
                document.location.href = a;
            }, getBrowserNotSupport: function() {
                jax.call("community", "photos,ajaxGotoOldUpload", joms.photos.multiUpload.getSelectedAlbumId(), joms.photos.multiUpload.groupid);
            }, goToOldUpload: function(a) {
                document.location.href = a;
            }, refreshBrowser: function() {
                window.location.reload();
            }, updateLabel: function(c) {
                c = joms.jQuery.parseJSON(c);
                var a = ["filename", "size", "status", "filedrag", "addfiles", "startupload", "stopupload"];
                for (var b = 0; b < a.length; b++) {
                    if (typeof c[a[b]] != "undefined" && c[a[b]] != "") {
                        joms.photos.multiUpload.label[a[b]] = c[a[b]];
                    }
                }
            }, enableEditTitle: function() {
                if (joms.jQuery(".plupload_file_name span").length > 0) {
                    joms.jQuery(".plupload_file_name span").unbind("click");
                    joms.jQuery(".plupload_file_name span").bind("click", function(a) {
                        joms.photos.multiUpload.editTitle(a);
                    });
                }
            }, editTitle: function(b) {
                joms.jQuery(".plupload_file_name span").unbind("click");
                var d = (b.target) ? b.target : b.which;
                var a = joms.jQuery(d).parent().parent().attr("id");
                var c = '<input type="text" target="' + a + '" name="photoTitle" value="' + joms.jQuery(d).html() + '" />';
                joms.jQuery(d).html(c);
                joms.jQuery(d).find("input").unbind("keypress").unbind("focusout");
                joms.jQuery(d).find("input").bind("keypress", function(e) {
                    keyCode = e.keyCode;
                    if (keyCode == 13) {
                        joms.photos.multiUpload.saveCaption(this, joms.jQuery(this).parent());
                        joms.jQuery(".plupload_file_name span").bind("click", function(f) {
                            joms.photos.multiUpload.editTitle(f);
                        });
                    }
                });
                joms.jQuery(d).find("input").bind("focusout", function() {
                    joms.photos.multiUpload.saveCaption(this, joms.jQuery(this).parent());
                    joms.jQuery(".plupload_file_name span").bind("click", function(e) {
                        joms.photos.multiUpload.editTitle(e);
                    });
                });
            }, saveCaption: function(d, f) {
                var b = joms.jQuery(d).attr("target");
                var c = pluploader.pluploadQueue().getFile(b);
                var e = joms.jQuery(d).val();
                c.name = e;
                var a = encodeURIComponent(e);
                jax.call("community", "photos,ajaxSaveCaption", c.photoId, a, false);
                joms.jQuery(f).html(e);
            }, displayNotice: function(a) {
                joms.jQuery("#" + joms.photos.multiUpload.noticeDivId).html(a);
            }, hideShowInput: function(a) {
                if (a != undefined && a === true) {
                    joms.jQuery("#upload-header").hide();
                    joms.jQuery(".custom_plupload_buttons").hide();
                } else {
                    joms.jQuery("#upload-header").show();
                    joms.jQuery(".custom_plupload_buttons").show();
                    joms.jQuery(".plupload_button.plupload_start").hide();
                }
            }, uploadCompleteShow: function() {
                joms.jQuery("div#upload-footer").show();
                joms.ajax.call("photos,ajaxUpdateCounter", [joms.photos.multiUpload.getSelectedAlbumId()]);
            }, removeQueue: function(b) {
                var d = pluploader.pluploadQueue().files;
                var a = [];
                var e = 0;
                for (var c = 0; c < d.length; c++) {
                    if (d[c].id == b) {
                        a[e] = d[c];
                        e++;
                    } else {
                        if (e > 0) {
                            a[e] = d[c];
                            e++;
                        }
                    }
                }
                for (var c = 0; c < a.length; c++) {
                    pluploader.pluploadQueue().removeFile(a[c]);
                    a[c] = "";
                }
            }}, uploadAvatar: function(b, c) {
            var a = jax.call("community", "photos,ajaxUploadAvatar", b, c);
            cWindowShow(a, "", 450, 100);
        }, ajaxUpload: function(c, d, b) {
            var a = joms.jQuery("#jsform-uploadavatar").prop("action");
            joms.jQuery.ajaxFileUpload({url: a, secureuri: false, fileElementId: "filedata", dataType: "json", beforeSend: function() {
                }, complete: function() {
                }, success: function(j, f) {
                    if (j.error == "true") {
                        joms.jQuery("span.error").remove();
                        joms.jQuery("#avatar-upload").prepend('<span class="error">' + j.msg + "</span>");
                        return false;
                    } else {
                        a = j.msg;
                        joms.jQuery("span.error").remove();
                        var e = joms.jQuery("#cWindowContent").height();
                        var g = "";
                        var h = "";
                        joms.jQuery("#thumb-crop").css("min-height", 0);
                        joms.jQuery("#large-avatar-pic").prop("src", a);
                        joms.jQuery("#thumb-hold  >img").prop("src", a);
                        joms.jQuery("div.status-anchor img").prop("src", a);
                        joms.jQuery("#large-avatar-pic").load(function() {
                            var m = joms.jQuery("#cWindowContent").height();
                            if (e > m) {
                                var l = "-=" + (e - m);
                            } else {
                                if (e < m) {
                                    var l = "+=" + (m - e);
                                }
                            }
                            joms.jQuery("#cwin_ml , #cwin_mr, #cWindowContentOuter, #cWindowContentWrap").animate({height: l});
                            joms.photos.ajaxImgSelect();
                            delete e;
                            delete m;
                            delete l;
                        });
                        switch (c) {
                            case"event":
                                g = joms.jQuery(".cPageAvatar img").prop("src");
                                joms.jQuery(".cPageAvatar img").prop("src", a);
                                if (g.search("assets/event.png") > -1) {
                                    if (b) {
                                        selected = joms.jQuery("input:radio[name=repeattype]:checked").val();
                                        custom = '{"call":["CEvents","getEventRepeatSaveHTML","' + selected + '"], "library":"events", "arg":["repeattype"]}';
                                        jax.call("community", "photos,ajaxUploadAvatar", c, d, custom);
                                    } else {
                                        jax.call("community", "photos,ajaxUploadAvatar", c, d);
                                    }
                                }
                                break;
                            case"group":
                                g = joms.jQuery(".cPageAvatar img").prop("src");
                                joms.jQuery(".cPageAvatar img").prop("src", a);
                                if (g.search("assets/group.png") > -1) {
                                    jax.call("community", "photos,ajaxUploadAvatar", c, d);
                                }
                                break;
                            case"profile":
                                g = joms.jQuery(".cPageAvatar img").prop("src");
                                joms.jQuery(".cPageAvatar img").prop("src", a);
                                if (j.info.length != 0) {
                                    var k = j.info;
                                    joms.jQuery(".status-author > img").prop("src", k);
                                    joms.jQuery('.cStream-Avatar img[data-author="' + d + '"]').prop("src", k);
                                }
                                if (g.search("assets/user.png") > -1) {
                                    jax.call("community", "photos,ajaxUploadAvatar", c, d);
                                }
                                break;
                            }
                    }
                }});
            return false;
        }, ajaxImgSelect: function() {
            var a = document.getElementById("large-avatar-pic");
            var c = joms.jQuery(a).height();
            var b = 160;
            if (c < 160) {
                b = c;
            }
            if (c > 160) {
                c = 160;
            }
            joms.jQuery("#large-avatar-pic").imgAreaSelect({maxWidth: 160, maxHeight: 160, handles: true, aspectRatio: "1:1", x1: 0, y1: 0, x2: b, y2: c, show: false, hide: true, enable: false, parent: "#cWindow", minHeight: 64, minWidth: 64, onInit: joms.photos.previewThumb, onSelectChange: joms.photos.previewThumb});
            var d = joms.jQuery("#large-avatar-pic").imgAreaSelect({instance: true});
            d.setOptions({show: true, hide: false, enable: true});
            d.update();
        }, saveThumb: function(a, d) {
            var c = joms.jQuery("#large-avatar-pic").imgAreaSelect({instance: true});
            var b = c.getSelection();
            jax.call("community", "photos,ajaxUpdateThumbnail", a, d, b.x1, b.y1, b.width, b.height);
        }, previewThumb: function(b, d) {
            var c = 64 / (d.width || 1);
            var a = 64 / (d.height || 1);
            joms.jQuery("#thumb-hold  >img").css({width: Math.round(c * 160) + "px", height: Math.round(a * this.height) + "px", marginLeft: "-" + Math.round(c * d.x1) + "px", marginTop: "-" + Math.round(a * d.y1) + "px"});
        }, ajaxRemoveImgSelect: function() {
            joms.jQuery(".imgareaselect-selection").parent().remove();
            joms.jQuery(".imgareaselect-outer").remove();
        }, photoSlider: {moveSpace: 4, partialOpacity: 0.6, fullOpacity: 1, intervalTime: 30, timer: "", controlObj: "", event: "", parentElem: "", stopAnimeId: "", img_thumbId: "photoSlider_thumb", img_thumbClass: "currentView", thumbnail: {width: 0, height: 0}, _init: function(a, d, c) {
                if (c != undefined) {
                    joms.photos.photoSlider.updateConfig(c);
                }
                var e = joms.jQuery("#" + a);
                joms.photos.photoSlider.controlObj = e;
                joms.photos.photoSlider.parentElem = joms.jQuery(e.parent());
                if (d != undefined && d != "") {
                    joms.photos.photoSlider.stopAnimeId = d;
                    joms.jQuery("." + joms.photos.photoSlider.stopAnimeId).css("opacity", joms.photos.photoSlider.partialOpacity);
                    var b = joms.gallery.getPlaylistIndex(joms.gallery.currentPhoto().id);
                    joms.jQuery("." + joms.photos.photoSlider.stopAnimeId).eq(b).css("opacity", joms.photos.photoSlider.fullOpacity);
                }
                joms.photos.photoSlider.parentElem.bind({mouseover: function(j) {
                        var h = joms.photos.photoSlider.getValue(joms.jQuery(this).css("width"));
                        var g = joms.photos.photoSlider.getValue(joms.jQuery(this).css("height"));
                        var k = joms.jQuery(this).offset();
                        if ((j.pageY > (k.top + g * 0.1)) && (j.pageY < (k.top + g * 0.9))) {
                            joms.photos.photoSlider.moveContent(j);
                        }
                    }, mousemove: function(g) {
                        joms.photos.photoSlider.updateMousePos(g);
                    }, mouseout: function(g) {
                        joms.photos.photoSlider.reset(g);
                    }});
                var f = joms.photos.photoSlider.parentElem.parent().width();
                joms.jQuery(".photo_slider").css("width", f + "px");
                joms.photos.photoSlider.switchPhoto();
            }, updateConfig: function(b) {
                var a = ["moveSpace", "partialOpacity", "fullOpacity", "intervalTime"];
                if (typeof b == "object") {
                    for (var c = 0; c < a.length; c++) {
                        if (b[a[c]] != undefined && parseInt(b[a[c]]) != "NaN" && b[a[c]] > 0) {
                            joms.photos.photoSlider[a[c]] = b[a[c]];
                        }
                    }
                }
            }, moveContent: function(b) {
                joms.photos.photoSlider.parentElem.unbind("mouseover");
                joms.photos.photoSlider.timer = setInterval("joms.photos.photoSlider.animate()", joms.photos.photoSlider.intervalTime);
                if (joms.photos.photoSlider.stopAnimeId != "" && joms.jQuery("." + joms.photos.photoSlider.stopAnimeId).length > 0) {
                    var a = joms.jQuery("." + joms.photos.photoSlider.stopAnimeId).eq(0);
                    joms.photos.photoSlider.thumbnail.width = joms.photos.photoSlider.getValue(a.css("width"));
                    joms.photos.photoSlider.thumbnail.height = joms.photos.photoSlider.getValue(a.css("height"));
                }
            }, animate: function() {
                if (joms.jQuery("#startTagMode").length > 0 && joms.jQuery("#startTagMode").css("display") == "none") {
                    joms.photos.photoSlider.stop();
                    return false;
                }
                var b = joms.photos.photoSlider.getValue(joms.photos.photoSlider.controlObj.css("left"));
                var c = joms.photos.photoSlider.getValue(joms.photos.photoSlider.controlObj.css("width"));
                var a = joms.photos.photoSlider.getValue(joms.photos.photoSlider.parentElem.css("width"));
                var d = a / 2;
                if (joms.photos.photoSlider.getMouseXPosition() > d) {
                    if (((b + c) - a) >= 0) {
                        joms.photos.photoSlider.controlObj.css("left", (b - joms.photos.photoSlider.moveSpace) + "px");
                    }
                } else {
                    if (b < 0) {
                        joms.photos.photoSlider.controlObj.css("left", (b + joms.photos.photoSlider.moveSpace) + "px");
                    }
                }
            }, updateMousePos: function(a) {
                joms.photos.photoSlider.event = a;
            }, getMouseXPosition: function() {
                return joms.photos.photoSlider.event.pageX - joms.photos.photoSlider.parentElem.offset().left;
            }, getMouseYPosition: function() {
                return joms.photos.photoSlider.event.pageY - joms.photos.photoSlider.parentElem.offset().top;
            }, getValue: function(a) {
                if (a == "" || a == "auto") {
                    intVal = 0;
                } else {
                    intVal = parseInt(a.replace("px", ""));
                    if (typeof intVal != "number" || intVal == "NaN") {
                        intVal = 0;
                    }
                }
                return intVal;
            }, viewImage: function(a) {
                if (joms.jQuery("#startTagMode").length > 0 && joms.jQuery("#startTagMode").css("display") == "none") {
                    return false;
                }
                if (joms.gallery != undefined && jsPlaylist != undefined) {
                    joms.photos.photoSlider.stop();
                    joms.gallery.displayPhoto(jsPlaylist.photos[joms.gallery.getPlaylistIndex(a)]);
                    joms.photos.photoSlider.switchPhoto();
                }
            }, switchPhoto: function() {
                if (joms.photos.photoSlider.controlObj == undefined || joms.photos.photoSlider.controlObj == "") {
                    return false;
                }
                var b = document.location.href;
                a = (decodeURI((RegExp("photoid=(.+?)(&|$)").exec(b) || [, null])[1]));
                if (a == "") {
                    var a = joms.photos.photoSlider.controlObj.find(img).eq(0).attr("id");
                }
                joms.photos.photoSlider.controlObj.find("img").removeClass(joms.photos.photoSlider.img_thumbClass);
                joms.photos.photoSlider.controlObj.find('img[id="' + a + '"]').addClass(joms.photos.photoSlider.img_thumbClass);
                joms.jQuery(".image_thumb").css("opacity", joms.photos.photoSlider.partialOpacity);
                joms.jQuery("img#photoSlider_thumb" + a).css("opacity", joms.photos.photoSlider.fullOpacity);
            }, updateThumb: function(c, a) {
                var b = 'img[id="' + joms.photos.photoSlider.img_thumbId + c + '"]';
                if (joms.jQuery(".slider-gallery").find(b).length == 1) {
                    joms.jQuery(".slider-gallery").find(b).attr("src", a);
                }
            }, removeThumb: function(c) {
                joms.jQuery("#cPhoto" + c).remove();
                var a = joms.photos.photoSlider.controlObj.find("img").length;
                var b = 79;
                joms.photos.photoSlider.controlObj.css("width", (a * b) + "px");
            }, reset: function(a) {
                var b = joms.photos.photoSlider.parentElem.offset();
                if ((a.pageX > b.left && a.pageX < (b.left + joms.photos.photoSlider.getValue(joms.photos.photoSlider.parentElem.css("width")))) && (a.pageY > b.top && a.pageY < (b.top + joms.photos.photoSlider.getValue(joms.photos.photoSlider.parentElem.css("height"))))) {
                    return false;
                }
                joms.photos.photoSlider.stop();
            }, stop: function() {
                clearInterval(joms.photos.photoSlider.timer);
                joms.photos.photoSlider.timer = "";
                joms.photos.photoSlider.event = "";
                joms.photos.photoSlider.parentElem.unbind("mouseover").unbind("mousemove").unbind("mouseout");
                joms.photos.photoSlider._init(joms.photos.photoSlider.controlObj.attr("id"), joms.photos.photoSlider.stopAnimeId);
            }}}, tooltips: {currentJaxCall: "", currentJElement: "", currentTimeout: "", minitipStyle: {top: 0, left: 0, width: 500, height: 100}, showDialog: function(a) {
            if (joms.tooltips.currentJElement == "" || joms.tooltips.currentJaxCall == "") {
                if (joms.tooltips.currentTimeout != "") {
                    clearTimeout(joms.tooltips.currentTimeout);
                }
                return false;
            }
            if (joms.tooltips.currentJElement.hasClass(a)) {
                joms.minitip._init(joms.tooltips.currentJaxCall, "", joms.tooltips.minitipStyle.width, joms.tooltips.minitipStyle.height);
                joms.tooltips.repositionMinitip();
                joms.jQuery("#" + joms.minitip.id.canvas).addClass(a);
                joms.jQuery("#" + joms.minitip.id.canvas).attr("currentMinitip", joms.tooltips.currentJElement.attr("id"));
            }
        }, addMinitipContent: function(a) {
            if (joms.jQuery("#" + joms.minitip.id.canvas).length > 0) {
                joms.tooltips.repositionMinitip();
                joms.minitip.addContent(a);
            }
        }, repositionMinitip: function() {
            var a = joms.tooltips.currentJElement.offset().top - (joms.jQuery("#" + joms.minitip.id.canvas).height() + 20);
            if (joms.jQuery.browser.msie && joms.jQuery.browser.version.substr(0, 1) <= 7) {
                var b = joms.tooltips.currentJElement.offset().left - 65;
            } else {
                var b = joms.tooltips.currentJElement.offset().left + 30;
            }
            joms.jQuery("#" + joms.minitip.id.canvas).css({top: a, left: b});
        }, setDelay: function(e, b, a, f, d, c) {
            if (e.attr("id") == undefined) {
                e.attr("id", "temp_minitooltip" + Math.random().toString());
            }
            if (joms.jQuery("#" + joms.minitip.id.canvas).length > 0) {
                if (e.attr("id") == joms.jQuery("#" + joms.minitip.id.canvas).attr("currentMinitip")) {
                    return false;
                }
            }
            if (f != undefined && parseInt(f) > 0) {
                joms.tooltips.minitipStyle.width = f;
            }
            if (d != undefined && parseInt(d) > 0) {
                joms.tooltips.minitipStyle.height = d;
            }
            joms.tooltips.currentJElement = e;
            joms.tooltips.currentJElement.bind("mouseout", function() {
                joms.jQuery(this).removeClass(a);
                clearTimeout(joms.tooltips.currentTimeout);
                joms.tooltips.reset();
                joms.minitip.hide();
            });
            joms.tooltips.currentJElement.addClass(a);
            joms.tooltips.currentJaxCall = b;
            joms.tooltips.currentTimeout = setTimeout("joms.tooltips.showDialog('" + a + "')", 500);
        }, reset: function() {
            joms.tooltips.currentJaxCall = "";
            joms.tooltips.currentTimeout = "";
        }}, friends: {saveTag: function() {
            var a = jax.getFormValues("tagsForm");
            jax.call("community", "friends,ajaxFriendTagSave", a);
            return false;
        }, saveGroup: function(a) {
            if (document.getElementById("newtag").value == "") {
                window.alert("TPL_DB_INVALIDTAG");
            } else {
                jax.call("community", "friends,ajaxAddGroup", a, joms.jQuery("#newtag").val());
            }
        }, cancelRequest: function(a) {
            var b = 'jax.call("community" , "friends,ajaxCancelRequest" , "' + a + '");';
            cWindowShow(b, "", 450, 100);
        }, connect: function(a) {
            var b = 'jax.call("community", "friends,ajaxConnect", ' + a + ")";
            cWindowShow(b, "", 450, 100);
        }, addNow: function() {
            var a = jax.getFormValues("addfriend");
            jax.call("community", "friends,ajaxSaveFriend", a);
            return false;
        }, confirmFriendRemoval: function(a) {
            var b = function() {
                jax.call("community", "friends,ajaxConfirmFriendRemoval", a);
            };
            cWindowShow(b, "", 450, 80, "warning");
        }, remove: function(b) {
            var a = joms.jQuery("#cWindow input[name=block]").attr("checked");
            var c;
            if (a) {
                c = function() {
                    jax.call("community", "friends,ajaxBlockFriend", b);
                };
            } else {
                c = function() {
                    jax.call("community", "friends,ajaxRemoveFriend", b);
                };
            }
            cWindowShow(c, "", 450, 80, "warning");
        }, updateFriendList: function(b, a) {
            currentFriends = "";
            noFriend = "";
            if (joms.jQuery("#community-invitation-list").hasClass("load-more")) {
                currentFriends = joms.jQuery("#community-invitation-list").html();
            } else {
                if (joms.jQuery.trim(b) == "") {
                    noFriend = a;
                }
            }
            newFriends = currentFriends + b + noFriend;
            joms.jQuery("#community-invitation-list").html(newFriends);
            joms.invitation.filterMember();
            joms.jQuery(".cTabNav #ctab-result").click();
        }, loadFriend: function(b, e, d, c, a) {
            if (joms.jQuery("#community-invitation-list").hasClass("load-more")) {
                joms.jQuery("#community-invitation-list").removeClass("load-more");
            }
            jax.call("community", "system,ajaxLoadFriendsList", b, e, d, c, a);
        }, loadMoreFriend: function(d, c, b, a) {
            name = joms.jQuery("#friend-search-filter").val();
            joms.jQuery("#community-invitation-list").addClass("load-more");
            jax.call("community", "system,ajaxLoadFriendsList", name, d, c, b, a);
        }, showForm: function(g, f, e, d, b) {
            var c = 'jax.call("community", "system,ajaxShowFriendsForm","' + g + '","' + f + '","' + e + '","' + d + '","' + b + '")';
            var a = 520;
            a = d != "0" ? a : a - 108;
            a = a - 108;
            cWindowShow(c, "", 550, a);
        }, selectRecipients: function() {
            joms.jQuery("#community-invited-list li").each(function(b) {
                var a = joms.jQuery(this).attr("id");
                if (joms.jQuery("#inbox-selected-to #" + a).length > 0) {
                } else {
                    var c = joms.jQuery(this).clone();
                    c.appendTo("#inbox-selected-to");
                }
            });
            joms.jQuery(".invitation-check label").empty();
            cWindowHide();
        }}, messaging: {loadComposeWindow: function(a) {
            var b = 'jax.call("community", "inbox,ajaxCompose", ' + a + ")";
            cWindowShow(b, "", 450, 100);
        }, sendCompleted: function() {
            joms_message_sending = false;
        }, send: function() {
            if (joms_message_sending) {
                return false;
            }
            joms_message_sending = true;
            var a = jax.getFormValues("writeMessageForm");
            jax.call("community", "inbox,ajaxSend", a);
            return false;
        }, confirmDeleteMarked: function(a) {
            var b = 'jax.call("community", "inbox,ajaxDeleteMessages", "' + a + '")';
            cWindowShow(b, "", 450, 100);
        }, deleteMarked: function(b) {
            var a = new Array();
            joms.jQuery("#inbox-listing INPUT[type='checkbox']").each(function() {
                if (joms.jQuery(this).attr("checked")) {
                    a.push(joms.jQuery(this).attr("value"));
                }
            });
            if (b == "inbox") {
                jax.call("community", "inbox,ajaxRemoveFullMessages", a.toString());
            } else {
                jax.call("community", "inbox,ajaxRemoveSentMessages", a.toString());
            }
            return false;
        }}, walls: {insertOrder: "prepend", add: function(b, a) {
            jax.loadingFunction = function() {
                joms.jQuery("#wall-message,#wall-submit").attr("disabled", true);
            };
            jax.doneLoadingFunction = function() {
                joms.jQuery("#wall-message,#wall-submit").attr("disabled", false);
            };
            if (typeof getCacheId == "function") {
                cache_id = getCacheId();
            } else {
                cache_id = "";
            }
            jax.call("community", a, joms.jQuery("#wall-message").val(), b, cache_id);
        }, insert: function(a) {
            joms.jQuery("#wall-message").val("");
            if (joms.walls.insertOrder == "prepend") {
                joms.jQuery("#wall-containter").prepend(a);
            } else {
                joms.jQuery("#wall-containter .wallComments:last").after(a);
            }
        }, remove: function(a, b, c) {
            if (confirm("Are you sure you want to delete this wall?")) {
                jax.call("community", a + ",ajaxRemoveWall", b, c);
                joms.jQuery("#wall_" + b).fadeOut("normal", function() {
                    joms.jQuery(this).remove();
                });
            }
        }, update: function(b, a) {
            cWindowHide();
            joms.jQuery("#wall_" + b).replaceWith(a);
        }, save: function(b, a) {
            jax.call("community", "system,ajaxUpdateWall", b, joms.jQuery("#wall-edit-" + b).val(), a);
        }, edit: function(b, a) {
            if (joms.jQuery("#wall-edit-" + b).val() != null) {
                joms.jQuery("#wall-message-" + b).show();
                joms.jQuery("#wall-edit-container-" + b).children().remove();
            } else {
                joms.jQuery("#wall-message-" + b).hide();
                joms.jQuery("#wall_" + b + " div.content").prepend('<span id="wall-edit-container-' + b + '"></span>').prepend('<div class="loading" style="display:block;float: left;"></div>');
                jax.call("community", "system,ajaxEditWall", b, a);
                joms.utils.textAreaWidth("#wall-edit-" + b);
                joms.utils.autogrow("#wall-edit-" + b);
            }
        }, more: function() {
            var b = joms.jQuery("#wall-groupId").val();
            var a = joms.jQuery("#wall-discussionId").val();
            var c = joms.jQuery("#wall-limitStart").val();
            joms.jQuery("#wall-more .more-wall-text").hide();
            joms.jQuery("#wall-more .loading").show();
            jax.call("community", "system,ajaxGetOlderWalls", b, a, c);
        }, append: function(a) {
            joms.jQuery("#wall-more,#wall-groupId,#wall-discussionId,#wall-limitStart").remove();
            joms.jQuery("#wall-containter").append(a);
        }, prepend: function(a) {
            joms.jQuery("#wall-more").remove();
            joms.jQuery("#wall-groupId").remove();
            joms.jQuery("#wall-discussionId").remove();
            joms.jQuery("#wall-limitStart").remove();
            joms.jQuery("#wall-containter").prepend(a);
        }, showVideoWindow: function(a) {
            var b = 'jax.call("community" , "videos,ajaxShowVideoWindow", "' + a + '");';
            cWindowShow(b, "", 640, 360);
        }}, toolbar: {timeout: 500, closetimer: 0, ddmenuitem: 0, open: function(a) {
            if (joms.jQuery("#" + a).length > 0) {
                joms.toolbar.cancelclosetime();
                if (joms.toolbar.ddmenuitem) {
                    joms.toolbar.ddmenuitem.style.visibility = "hidden";
                }
                joms.toolbar.ddmenuitem = document.getElementById(a);
                joms.toolbar.ddmenuitem.style.visibility = "visible";
            }
        }, close: function() {
            if (joms.toolbar.ddmenuitem) {
                joms.toolbar.ddmenuitem.style.visibility = "hidden";
            }
        }, closetime: function() {
            joms.toolbar.closetimer = window.setTimeout(joms.toolbar.close, joms.toolbar.timeout);
        }, cancelclosetime: function() {
            if (joms.toolbar.closetimer) {
                window.clearTimeout(joms.toolbar.closetimer);
                joms.toolbar.closetimer = null;
            }
        }}, registrations: {showTermsWindow: function(a) {
            var b = 'jax.call("community", "register,ajaxShowTnc", "' + a + '")';
            cWindowShow(b, this.windowTitle, 600, 350);
        }, authenticate: function() {
            jax.call("community", "register,ajaxGenerateAuthKey");
        }, authenticateAssign: function() {
            jax.call("community", "register,ajaxAssignAuthKey");
        }, assignAuthKey: function(fname, lblname, authkey) {
            eval("document.forms['" + fname + "'].elements['" + lblname + "'].value = '" + authkey + "';");
        }, showWarning: function(a, b) {
            cWindowShow("joms.jQuery('#cWindowContent').html('" + a + "')", b, 450, 200, "warning");
        }}, miniwall: {initialize: function() {
            joms.jQuery("[data-commentid]").live({mouseover: function() {
                    joms.jQuery(this).find("[href=#removeComment]").show();
                }, mouseout: function() {
                    joms.jQuery(this).find("[href=#removeComment]").hide();
                }});
            joms.jQuery('a[href="#removeComment"]').live("click", function() {
                var a = joms.jQuery(this).parents("[data-commentid]").data("commentid");
                joms.miniwall.remove(a);
            });
            joms.jQuery('a[href="#showallcomments"]').live("click", function() {
                var a = joms.jQuery(this).parents("li.stream").data("streamid");
                jax.call("community", "system,ajaxStreamShowComments", a);
            });
            joms.jQuery(".cStream-Form textarea").width(joms.jQuery(".cStream-Form").width() - 12);
        }, add: function(b) {
            var a = joms.jQuery("#wall-cmt-" + b + " textarea").val();
            a = joms.jQuery.trim(a);
            if (a.length > 0) {
                joms.jQuery("#wall-cmt-" + b + " .wall-coc-form-action.add").attr("disabled", true);
                joms.jQuery("#wall-cmt-" + b + " .wall-coc-errors").hide();
                jax.loadingFunction = function() {
                    joms.jQuery("#wall-cmt-" + b + " textarea").attr("disabled", true);
                    joms.jQuery("#wall-cmt-" + b + " .wall-coc-form-actions").append('<em class="wall-cmt-loading">Posting...</em>');
                    jax.loadingFunction = function() {
                    };
                };
                jax.doneLoadingFunction = function() {
                    joms.jQuery("#wall-cmt-" + b + " .wall-coc-form-actions").find("em").remove();
                    joms.jQuery("#wall-cmt-" + b + " textarea").attr("disabled", false).val("");
                    joms.jQuery("#wall-cmt-" + b + " .wall-coc-form-action.add").attr("disabled", false);
                    cmtCountObj = joms.jQuery("#wall-cmt-" + b).parent().parent().find(".wall-cmt-count");
                    curCmtCount = parseInt(cmtCountObj.html());
                    cmtCountObj.parent().fadeOut("fast", function() {
                        cmtCountObj.html(curCmtCount + 1);
                        cmtCountObj.parent().fadeIn("fast");
                    });
                    jax.doneLoadingFunction = function() {
                    };
                };
                jax.call("community", "system,ajaxStreamAddComment", b, a);
            }
        }, insert: function(b, a) {
            joms.jQuery("#wall-cmt-" + b + " .wallform").before(a);
            joms.jQuery("#wall-cmt-" + b + " .wallnone").removeClass("wallnone");
            joms.miniwall.cancel(b);
        }, loadall: function(b, a) {
            joms.jQuery("li[data-streamid=" + b + "] div[data-commentid]").remove();
            joms.jQuery("li[data-streamid=" + b + "] div[data-commentmore]").replaceWith(a);
        }, cancel: function(a) {
            joms.jQuery("#wall-cmt-" + a + " textarea").val("");
            joms.jQuery("#wall-cmt-" + a + " .wall-coc-errors").hide();
            joms.jQuery("#wall-cmt-" + a + " .wall-coc-form-action.add").removeAttr("disabled");
            joms.jQuery("#wall-cmt-" + a + " [data-formblock]").hide();
            if (joms.jQuery("li[data-streamid=" + a + "] [data-commentid]").length > 0) {
                joms.jQuery("li[data-streamid=" + a + "] [data-replyblock]").show();
            }
        }, remove: function(b) {
            var a = joms.jQuery("#wall-" + b).parent().parent().parent().find(".wall-cmt-count");
            jax.loadingFunction = function() {
                joms.jQuery("#wall-" + b).css({backgroundColor: "#ffdddd"}).find(".wall-coc-remove-link").show().html('<em class="wall-cmt-loading wall-cmt-loading-inline">Removing...</em>');
                jax.loadingFunction = function() {
                };
            };
            jax.doneLoadingFunction = function() {
                var c = parseInt(a.html());
                if (c > 0) {
                    if (c == 1) {
                        joms.jQuery("#wall-" + b).parent().parent().find(".cComment:last").addClass("wallnone");
                    }
                    a.parent().fadeOut("fast", function() {
                        a.html(c - 1);
                        a.parent().fadeIn("fast");
                    });
                }
                joms.jQuery("[data-commentid=" + b + "]").fadeOut("fast", function() {
                    joms.jQuery(this).remove();
                }).find(".wall-coc-remove-link").hide();
                jax.doneLoadingFunction = function() {
                };
            };
            jax.call("community", "system,ajaxStreamRemoveComment", b);
        }, show: function(c) {
            var b = joms.jQuery("#" + c + " form").parent().width();
            joms.jQuery("li[data-streamid=" + c + "] [data-formblock]").show();
            joms.jQuery("li[data-streamid=" + c + "] [data-formblock] form").width(b);
            joms.jQuery("li[data-streamid=" + c + "] [data-replyblock]").hide();
            var a = joms.jQuery("li[data-streamid=" + c + "] .cStream-Form").find("textarea");
            if (!a.data("autogrow")) {
                joms.utils.textAreaWidth(a);
                joms.utils.autogrow(a);
                a.focus();
                a.blur(function() {
                    if (joms.jQuery(this).val() == "") {
                        joms.miniwall.cancel(c);
                    }
                }).data("autogrow", true);
            }
        }}, comments: {add: function(b) {
            var a = joms.jQuery("#" + b + " textarea").val();
            if (a != "") {
                joms.jQuery("#" + b + " .wall-coc-form-action.add").attr("disabled", true);
                if (typeof getCacheId == "function") {
                    cache_id = getCacheId();
                } else {
                    cache_id = "";
                }
                jax.call("community", "plugins,walls,ajaxAddComment", b, a, cache_id);
            }
        }, insert: function(b, a) {
            joms.jQuery("#" + b + " form").before(a);
            joms.comments.cancel(b);
        }, remove: function(d) {
            var c = joms.jQuery(d).parents(".cComment");
            var a = joms.jQuery(c).index();
            try {
                console.log(a);
            } catch (b) {
            }
            var e = joms.jQuery(d).parents(".cComment").parent().attr("id");
            try {
                console.log(e);
            } catch (b) {
            }
            jax.call("community", "plugins,walls,ajaxRemoveComment", e, a);
        }, cancel: function(a) {
            joms.jQuery("#" + a + " textarea").val("");
            joms.jQuery("#" + a + " textarea").height("auto");
            joms.jQuery("#" + a + " form").hide();
            joms.jQuery("#" + a + " .show-cmt").show();
            joms.jQuery("#" + a + " .wall-coc-errors").hide();
            joms.jQuery("#" + a + " .wall-coc-form-action.add").removeAttr("disabled");
        }, show: function(c) {
            var b = joms.jQuery("#" + c + " form").parent().width();
            joms.jQuery("#" + c + " .wall-coc-form-action.add").removeAttr("disabled");
            joms.jQuery("#" + c + " form").width(b).show();
            joms.jQuery("#" + c + " .show-cmt").hide();
            var a = joms.jQuery("#" + c + " textarea");
            if (!a.data("autogrow")) {
                joms.utils.textAreaWidth(a);
                joms.utils.autogrow(a);
                a.blur(function() {
                    if (joms.jQuery(this).val() == "") {
                        joms.comments.cancel(c);
                    }
                }).data("autogrow", true);
            }
        }}, utils: {textAreaWidth: function(target) {
            with (joms.jQuery(target)) {
                css("width", "100%");
                css("width", width() - parseInt(css("borderLeftWidth")) - parseInt(css("borderRightWidth")) - parseInt(css("padding-left")) - parseInt(css("padding-right")));
            }
        }, autogrow: function(b, a) {
            if (a == undefined) {
                a = {};
            }
            a.maxHeight = a.maxHeight || 300;
            joms.jQuery(b).autogrow(a);
        }}, maps: {mapsObj: null, geocoder: null, init: function() {
            if (joms.jQuery(".cMapFade") != null || joms.jQuery(".cMapHeatzone") != null) {
                joms.jQuery(".cMapFade").live("mouseover", function(a) {
                    joms.jQuery(this).find("img:eq(2)").fadeOut(0);
                });
                joms.jQuery(".cMapFade").live("mouseout", function(a) {
                    joms.jQuery(this).find("img:eq(2)").fadeIn(0);
                });
                joms.jQuery(".cMapHeatzone").live("mouseover", function(a) {
                    joms.jQuery(this).parent().find("img:eq(1)").fadeOut(0);
                });
                joms.jQuery(".cMapHeatzone").live("mouseout", function(a) {
                    joms.jQuery(this).parent().find("img:eq(1)").fadeIn(0);
                });
            }
        }, initialize: function(c, a, d, b) {
            if (typeof google.maps == "undefined") {
                setTimeout("joms.maps.initialize('" + c + "', '" + a + "')", 1000);
            } else {
                joms.maps.geocoder = new google.maps.Geocoder();
                joms.maps.geocoder.geocode({address: a}, function(j, f) {
                    if (f == google.maps.GeocoderStatus.OK) {
                        if (joms.maps.mapsObj == null) {
                            joms.maps.mapsObj = new Array();
                        }
                        var l = new google.maps.LatLng(-34.397, 150.644);
                        var g = {zoom: 14, center: l, mapTypeId: google.maps.MapTypeId.ROADMAP};
                        var h = joms.maps.mapsObj.length;
                        joms.maps.mapsObj[h] = new google.maps.Map(document.getElementById(c), g);
                        joms.maps.mapsObj[h].setCenter(j[0].geometry.location);
                        var e = new google.maps.Marker({map: joms.maps.mapsObj[h], position: j[0].geometry.location, title: d});
                        if (b.length > 0) {
                            var k = new google.maps.InfoWindow({content: b});
                            google.maps.event.addListener(e, "click", function() {
                                var m = joms.jQuery("div#" + c).data("maps");
                                k.open(joms.maps.mapsObj[m], e);
                            });
                        }
                        joms.jQuery("div#" + c).data("maps", h);
                    } else {
                        alert("Geocode was not successful for the following reason: " + f);
                    }
                });
            }
        }, addMarker: function(e, f, g, h, a) {
            if (joms.maps.mapsObj == null) {
                setTimeout("joms.maps.addMarker('" + e + "', " + f + ", " + g + ", '" + h + "', '" + a + "')", 1000);
            } else {
                var j = joms.jQuery("div#" + e).data("maps");
                var c = new google.maps.LatLng(f, g);
                var d = new google.maps.Marker({position: c, map: joms.maps.mapsObj[j], title: h});
                if (a.length > 0) {
                    var b = new google.maps.InfoWindow({content: a});
                    google.maps.event.addListener(d, "click", function() {
                        var k = joms.jQuery("div#" + e).data("maps");
                        b.open(joms.maps.mapsObj[k], d);
                    });
                }
            }
        }}, connect: {checkRealname: function(a) {
            var b = jax.loadingFunction;
            jax.loadingFunction = function() {
            };
            jax.doneLoadingFunction = function() {
                jax.loadingFunction = b;
            };
            jax.call("community", "connect,ajaxCheckName", a);
        }, checkEmail: function(a) {
            var b = jax.loadingFunction;
            jax.loadingFunction = function() {
            };
            jax.doneLoadingFunction = function() {
                jax.loadingFunction = b;
            };
            jax.call("community", "connect,ajaxCheckEmail", a);
        }, checkUsername: function(a) {
            var b = jax.loadingFunction;
            jax.loadingFunction = function() {
            };
            jax.doneLoadingFunction = function() {
                jax.loadingFunction = b;
            };
            jax.call("community", "connect,ajaxCheckUsername", a);
        }, update: function() {
            var a = "jax.call('community', 'connect,ajaxUpdate' );";
            cWindowShow(a, "", 450, 200);
        }, updateEmail: function() {
            joms.jQuery("#facebook-email-update").submit();
        }, importData: function() {
            var b = joms.jQuery("#importstatus").is(":checked") ? 1 : 0;
            var a = joms.jQuery("#importavatar").is(":checked") ? 1 : 0;
            jax.call("community", "connect,ajaxImportData", b, a);
        }, mergeNotice: function() {
            var a = "jax.call('community','connect,ajaxMergeNotice');";
            cWindowShow(a, "", 450, 200);
        }, merge: function() {
            var a = "jax.call('community','connect,ajaxMerge');";
            cWindowShow(a, "", 450, 200);
        }, validateUser: function() {
            var a = "jax.call('community','connect,ajaxValidateLogin','" + joms.jQuery("#existingusername").val() + "','" + joms.jQuery("#existingpassword").val() + "');";
            cWindowShow(a, "", 450, 200);
        }, newUser: function() {
            var a = "jax.call('community','connect,ajaxShowNewUserForm');";
            cWindowShow(a, "", 450, 200);
        }, existingUser: function() {
            var a = "jax.call('community','connect,ajaxShowExistingUserForm');";
            cWindowShow(a, "", 450, 200);
        }, selectType: function() {
            if (joms.jQuery("[name=membertype]:checked").val() == "1") {
                var a = joms.jQuery("#tnc:checked").val();
                if ((joms.jQuery("#tnc:checked").length == 0) && (joms.jQuery("#tnc").length == 1)) {
                    joms.jQuery("span#err_msg").css("display", "block");
                    return false;
                }
                joms.connect.newUser();
            } else {
                joms.connect.existingUser();
            }
        }, validateNewAccount: function() {
            jax.call("community", "connect,ajaxCheckEmail", joms.jQuery("#newemail").val());
            jax.call("community", "connect,ajaxCheckUsername", joms.jQuery("#newusername").val());
            jax.call("community", "connect,ajaxCheckName", joms.jQuery("#newname").val());
            var b = true;
            if (joms.jQuery("#newname").val() == "" || joms.jQuery("#error-newname").css("display") != "none") {
                b = false;
            }
            if (joms.jQuery("#newusername").val() == "" || joms.jQuery("#error-newusername").css("display") != "none") {
                b = false;
            }
            if (joms.jQuery("#newemail").val() == "" || joms.jQuery("#error-newemail").css("display") != "none") {
                b = false;
            }
            if (b) {
                var a = "";
                if (joms.jQuery(".jsProfileType").length > 0 && joms.jQuery(".jsProfileType input").length > 0) {
                    a = (joms.jQuery(".jsProfileType input:checked").length > 0) ? joms.jQuery(".jsProfileType input:checked").val() : a;
                }
                var c = "jax.call('community', 'connect,ajaxCreateNewAccount' , '" + joms.jQuery("#newname").val() + "', '" + joms.jQuery("#newusername").val() + "','" + joms.jQuery("#newemail").val() + "','" + a + "');";
                cWindowShow(c, "", 450, 200);
            }
        }}, videos: {playProfileVideo: function(c, a) {
            var b = "jax.call('community', 'profile,ajaxPlayProfileVideo', " + c + ", " + a + ")";
            cWindowShow(b, "", 640, 360);
        }, linkConfirmProfileVideo: function(b) {
            var a = "jax.call('community', 'profile,ajaxConfirmLinkProfileVideo', '" + b + "');";
            cWindowShow(a, "", 450, 100);
        }, linkProfileVideo: function(b) {
            var a = "jax.call('community', 'profile,ajaxLinkProfileVideo', '" + b + "');";
            cWindowShow(a, "", 450, 100);
        }, removeConfirmProfileVideo: function(a, b) {
            var c = "jax.call('community', 'profile,ajaxRemoveConfirmLinkProfileVideo', '" + a + "', '" + b + "');";
            cWindowShow(c, "", 450, 100);
        }, removeLinkProfileVideo: function(a, b) {
            var c = "jax.call('community', 'profile,ajaxRemoveLinkProfileVideo', '" + a + "', '" + b + "');";
            cWindowShow(c, "", 450, 100);
        }, showEditWindow: function(c, a) {
            if (typeof a == "undefined") {
                a = "";
            }
            var b = "jax.call('community', 'videos,ajaxEditVideo', '" + c + "' , '" + a + "');";
            cWindowShow(b, "", 450, 400);
        }, deleteVideo: function(b, a) {
            var c = "jax.call('community' , 'videos,ajaxRemoveVideo', '" + b + "','" + a + "');";
            cWindowShow(c, "", 450, 150);
        }, playerConf: {}, addVideo: function(b, a) {
            if (typeof b == "undefined" || b == "") {
                var b = "";
                var a = "";
            }
            var c = "jax.call('community', 'videos,ajaxAddVideo', '" + b + "', '" + a + "');";
            cWindowShow(c, "", 600, 500);
        }, linkVideo: function(b, a) {
            var c = "jax.call('community', 'videos,ajaxLinkVideo', '" + b + "', '" + a + "');";
            cWindowShow(c, "", 450, 100);
        }, uploadVideo: function(b, a) {
            var c = "jax.call('community', 'videos,ajaxUploadVideo', '" + b + "', '" + a + "');";
            cWindowShow(c, "", 450, 100);
        }, submitLinkVideo: function() {
            var a = true;
            joms.jQuery("form#linkVideo li button").prop("disabled", true);
            videoLinkUrl = "#linkVideo input[name='videoLinkUrl']";
            if (joms.jQuery.trim(joms.jQuery(videoLinkUrl).val()) == "") {
                joms.jQuery(videoLinkUrl).addClass("invalid");
                a = false;
            } else {
                joms.jQuery(videoLinkUrl).removeClass("invalid");
            }
            if (a) {
                joms.jQuery("#cwin-wait").css("margin-left", "20px");
                joms.jQuery("#cwin-wait").show();
                document.linkVideo.submit();
            }
        }, submitUploadVideo: function() {
            var a = true;
            videoFile = "#uploadVideo input[name='videoFile']";
            if (joms.jQuery.trim(joms.jQuery(videoFile).val()) == "") {
                joms.jQuery(videoFile).addClass("invalid");
                a = false;
            } else {
                joms.jQuery(videoFile).removeClass("invalid");
            }
            videoTitle = "#uploadVideo input[name='title']";
            if (joms.jQuery.trim(joms.jQuery(videoTitle).val()) == "") {
                joms.jQuery(videoTitle).addClass("invalid");
                a = false;
            } else {
                joms.jQuery(videoTitle).removeClass("invalid");
            }
            if (a) {
                joms.jQuery("#cwin-wait").css("margin-left", "20px");
                joms.jQuery("#cwin-wait").show();
                document.uploadVideo.submit();
            }
        }, fetchThumbnail: function(a) {
            var b = "jax.call('community' , 'videos,ajaxFetchThumbnail', '" + a + "','myvideos');";
            cWindowShow(b, "", 450, 150);
        }, toggleSearchSubmenu: function(a) {
            joms.jQuery(a).next("ul").toggle().find("input[type=text]").focus();
        }, selectVideoTagFriends: function(a) {
            joms.jQuery("#community-invited-list li").each(function(c) {
                var b = joms.jQuery(this).attr("id");
                b = b.substr("invitation-friend-".length);
                jax.call("community", "videos,ajaxAddVideoTag", a, b);
            });
            joms.jQuery(".invitation-check label").empty();
            cWindowHide();
        }, addVideoTextTag: function(tags, textRemove) {
            var videoTextTags = joms.jQuery(".videoTextTags");
            if (typeof(tags) == "string") {
                tags = eval("(" + tags + ")");
            }
            var singleTag = false;
            if (!joms.jQuery.isArray(tags)) {
                tags = [tags];
                singleTag = true;
            }
            joms.jQuery.each(tags, function(i, tag) {
                if (tag.id == undefined) {
                    return;
                }
                var videoTextTag = joms.jQuery('<span class="videoTextTag"></span>');
                videoTextTag.data("tag", tag).attr("id", "videoTextTag-" + tag.id).appendTo(videoTextTags);
                var videoTextTagLink = joms.jQuery("<a>");
                videoTextTagLink.attr("href", tag.profileUrl).html(tag.displayName).prependTo(videoTextTag);
                if (tag.canRemove) {
                    var videoTextTagActions = joms.jQuery('<span class="videoTextTagActions"></span>');
                    videoTextTagActions.appendTo(videoTextTag);
                    var videoTextTagAction_remove = joms.jQuery('<a class="videoTextTagAction" href="javascript: void(0);"></a>');
                    videoTextTagAction_remove.addClass("_remove").html(textRemove).click(function() {
                        joms.videos.removeVideoTag(tag);
                    }).appendTo(videoTextTagActions);
                    videoTextTagActions.before(" ").prepend("(").append(")");
                }
            });
            joms.videos.commifyTextTags();
        }, removeVideoTag: function(a) {
            jax.call("community", "videos,ajaxRemoveVideoTag", a.videoId, a.userId);
            if (a == undefined) {
                joms.jQuery(".videoTextTags").remove();
            } else {
                joms.jQuery("#videoTextTag-" + a.id).remove();
            }
            joms.videos.commifyTextTags();
        }, commifyTextTags: function() {
            joms.jQuery(".videoTextTags .comma").remove();
            videoTextTag = joms.jQuery(".videoTextTag");
            videoTextTag.each(function(b) {
                if (b == 0) {
                    return;
                }
                var a = joms.jQuery('<span class="comma"></span>');
                a.html(", ").prependTo(this);
            });
        }, checkSize: function(a) {
            joms.jQuery("form#uploadVideo button").prop("disabled", true);
            if (joms.jQuery.browser.msie) {
                return false;
            } else {
                Size = a.files[0].size;
            }
            jax.call("community", "videos,ajaxCheckFileSize", Size);
        }}, users: {banUser: function(a, b) {
            var c = "jax.call('community', 'profile,ajaxBanUser', '" + a + "' , '" + b + "');";
            cWindowShow(c, "", 450, 100);
        }, removePicture: function(a) {
            var b = "jax.call('community', 'profile,ajaxRemovePicture', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, updateURL: function(a) {
            var b = "jax.call('community', 'profile,ajaxUpdateURL', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, uploadNewPicture: function(a) {
            var b = "jax.call('community', 'profile,ajaxUploadNewPicture', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, blockUser: function(a) {
            var b = 'jax.call("community", "profile,ajaxBlockUser", "' + a + '");';
            cWindowShow(b, "", 450, 100);
        }, unBlockUser: function(a, b) {
            b = b || null;
            var c = 'jax.call("community", "profile,ajaxUnblockUser", "' + a + '", "' + b + '");';
            cWindowShow(c, "", 450, 100);
        }}, user: {getActive: function() {
            return js_profileId;
        }}, events: {deleteEvent: function(a) {
            var b = "jax.call('community', 'events,ajaxWarnEventDeletion', '" + a + "');";
            cWindowShow(b, "", 450, 100, "warning");
        }, join: function(a) {
            var b = 'jax.call("community", "events,ajaxRequestInvite", "' + a + '", location.href );';
            cWindowShow(b, "", 450, 100);
        }, leave: function(a) {
            var b = 'jax.call("community", "events,ajaxIgnoreEvent", "' + a + '");';
            cWindowShow(b, "", 450, 100);
        }, sendmail: function(a) {
            var b = 'jax.call("community", "events,ajaxSendEmail", "' + a + '");';
            cWindowShow(b, "", 450, 300);
        }, confirmBlockGuest: function(a, b) {
            var c = 'jax.call("community", "events,ajaxConfirmBlockGuest", "' + a + '", "' + b + '");';
            cWindowShow(c, "", 450, 100);
        }, blockGuest: function(a, b) {
            var c = 'jax.call("community", "events,ajaxBlockGuest", "' + a + '", "' + b + '");';
            cWindowShow(c, "", 450, 100);
        }, confirmUnblockGuest: function(a, b) {
            var c = 'jax.call("community", "events,ajaxConfirmUnblockGuest", "' + a + '", "' + b + '");';
            cWindowShow(c, "", 450, 100);
        }, unblockGuest: function(a, b) {
            var c = 'jax.call("community", "events,ajaxUnblockGuest", "' + a + '", "' + b + '");';
            cWindowShow(c, "", 450, 100);
        }, confirmRemoveGuest: function(a, b) {
            var c = 'jax.call("community", "events,ajaxConfirmRemoveGuest", "' + a + '", "' + b + '");';
            cWindowShow(c, "", 450, 80, "warning");
        }, removeGuest: function(b, c) {
            var a = joms.jQuery("#cWindow input[name=block]").attr("checked");
            var d = "";
            if (a) {
                d = 'jax.call("community", "events,ajaxBlockGuest", "' + b + '", "' + c + '");';
            } else {
                d = 'jax.call("community", "events,ajaxRemoveGuest", "' + b + '", "' + c + '");';
            }
            cWindowShow(d, "", 450, 100, "warning");
        }, joinNow: function(a) {
            jax.call("community", "events,ajaxJoinInvitation", a);
        }, rejectNow: function(a) {
            jax.call("community", "events,ajaxRejectInvitation", a);
        }, toggleSearchSubmenu: function(a) {
            joms.jQuery(a).next("ul").toggle().find("input[type=text]").focus();
        }, displayNearbyEvents: function(a) {
            joms.ajax.call("events,ajaxDisplayNearbyEvents", [a], {success: function(b) {
                    joms.jQuery("#community-event-nearby-listing").html(b);
                }});
        }, getDayEvent: function(b, d, c, a) {
            jax.loadingFunction = function() {
                joms.jQuery(".loading-icon").show();
            };
            jax.doneLoadingFunction = function() {
                joms.jQuery(".loading-icon").hide();
            };
            jax.cacheCall("community", "events,ajaxGetEvents", b, d, c, a);
        }, displayDayEvent: function(response) {
            joms.jQuery(".events-list").html("");
            var day_event = eval("(" + response + ")");
            if (day_event.length > 0) {
                joms.jQuery("strong.happening_title").show();
            } else {
                joms.jQuery("strong.happening_title").hide();
            }
            for (var i = 0; i < day_event.length;
                    i++) {
                var start_day = day_event[i]["start"];
                var end_day = day_event[i]["end"];
                joms.jQuery(".events-list").html(joms.jQuery(".events-list").html() + '<li><a class="date_day_' + start_day + "_" + end_day + '" href="' + day_event[i]["link"] + '"> ' + day_event[i]["title"] + "</a></li>");
                joms.jQuery('a[class^="date_day_"]').mouseenter(function() {
                    var date_str = joms.jQuery(this).attr("class").split("_");
                    var start = date_str[2];
                    var end = parseInt(date_str[3]) + 1;
                    for (var j = start; j < end; j++) {
                        joms.jQuery(".event_date_" + j).addClass("highlightrunning");
                    }
                });
                joms.jQuery('a[class^="date_day_"]').mouseleave(function() {
                    joms.jQuery(".highlightrunning").removeClass("highlightrunning");
                });
                if (i + 1 != day_event.length) {
                    joms.jQuery(".events-list").html(joms.jQuery(".events-list").html());
                }
            }
        }, getCalendar: function(b, a) {
            jax.cacheCall("community", "events,ajaxGetCalendar", b, a);
        }, displayCalendar: function(a) {
            joms.jQuery("div#event").html(a);
            init_calendar();
        }, switchImport: function(a) {
            if (a == "file") {
                joms.jQuery("#event-import-url").css("display", "none");
                joms.jQuery("#event-import-file").css("display", "block");
                joms.jQuery("#import-type").val("file");
            }
            if (a == "url") {
                joms.jQuery("#event-import-file").css("display", "none");
                joms.jQuery("#event-import-url").css("display", "block");
                joms.jQuery("#import-type").val("url");
            }
        }, showMapWindow: function() {
            var a = 'jax.call("community", "events,ajaxShowMap");';
            cWindowShow(a, "", 450, 100);
        }, showDesc: function() {
            joms.jQuery("#event-discription").show();
            joms.jQuery("#event-description-link").hide();
        }, submitRSVP: function(a, b) {
            rsvpres = b.value;
            joms.ajax.call("events,ajaxUpdateStatus", [a, rsvpres], {success: function(d) {
                    joms.jQuery("#community-event-members").replaceWith(d);
                    var c = joms.jQuery(b.children[rsvpres - 1]).prop("class");
                    joms.jQuery(b.parentNode.children[0]).prop("class", null);
                    joms.jQuery(b.parentNode.children[0]).addClass(c);
                }});
        }, save: function() {
            var a = "jax.call('community', 'events,ajaxShowRepeatOption');";
            cWindowShow(a, "", 450, 100);
        }, uploadAvatar: function(c, e, b) {
            var d = null;
            if (b) {
                d = '{"call":["CEvents","getEventRepeatSaveHTML"], "library":"events", "arg":{"radio":"repeattype"}}';
            }
            var a = jax.call("community", "photos,ajaxUploadAvatar", c, e, d);
            cWindowShow(a, "", 450, 100);
        }}, profile: {confirmRemoveAvatar: function() {
            var a = 'jax.call("community", "profile,ajaxConfirmRemoveAvatar");';
            cWindowShow(a, "", 450, 100);
        }, setStatusLimit: function(a) {
            joms.jQuery(a).keyup(function() {
                var b = parseInt(joms.jQuery(this).attr("maxlength"));
                if (joms.jQuery(this).val().length > b) {
                    joms.jQuery(this).val(joms.jQuery(this).val().substr(0, joms.jQuery(this).attr("maxlength")));
                }
                joms.jQuery("#profile-status-notice span").html((b - joms.jQuery(this).val().length));
            });
        }}, privacy: {init: function() {
            joms.jQuery("select.js_PrivacySelect").each(function() {
                var a = "";
                var b;
                joms.jQuery(this).find("option").each(function() {
                    if (joms.jQuery(this).attr("selected")) {
                        b = joms.jQuery(this).val();
                    }
                });
                a += "<dl class='js_dropDownMaster'>\n";
                a += "<dt name=" + b + " class='js_dropDown js_dropSelect-" + b + "'><strong>" + joms.jQuery(this).find('option[selected="selected"]').text() + "</strong><span></span></dt>\n";
                a += "<dd>\n<ul class='js_dropDownParent'>\n";
                joms.jQuery(this).find("option").each(function() {
                    var c = joms.jQuery(this).val();
                    if (c == b) {
                        a += "<li class='js_dropDownCurrent'>";
                    } else {
                        a += "<li>";
                    }
                    a += "<a href='javascript:void()' name='" + c + "' class='js_dropDownChild js_dropDown-" + c + "'>" + joms.jQuery(this).text() + "</a></li>\n";
                });
                a += "</ul>\n</dd>\n</dl>";
                joms.jQuery(this).parent().prepend(a);
                joms.jQuery(this).hide();
            });
            joms.jQuery(".js_dropDownChild").die("click touchstart");
            joms.jQuery(".js_dropDownChild").live("click touchstart", function(d) {
                d.preventDefault();
                var b = joms.jQuery(this).attr("name");
                var f = "";
                joms.jQuery(this).closest(".js_PriContainer").find("option").each(function() {
                    if (joms.jQuery(this).val() == b) {
                        joms.jQuery(this).attr("selected", "selected");
                        f = joms.jQuery(this).text();
                    } else {
                        joms.jQuery(this).attr("selected", false);
                    }
                });
                var c = joms.jQuery(this).parent().parent().parent().parent().find("dt");
                var a = c.attr("name");
                c.removeClass("js_dropSelect-" + a).addClass("js_dropSelect-" + b).attr("name", b).html("<strong>" + f + "</strong><span></span>");
                joms.privacy.closeAll();
            });
            joms.jQuery(".js_dropDownMaster dt").die("click touchstart");
            joms.jQuery(".js_dropDownMaster dt").live("click touchstart", function(a) {
                a.preventDefault();
                if (joms.jQuery(this).parent().data("state")) {
                    joms.privacy.closeAll();
                    joms.jQuery("body").unbind("click");
                } else {
                    joms.privacy.closeAll();
                    joms.jQuery(this).parent().parent().addClass("js_PrivacyOpen");
                    joms.jQuery(this).parent().data("state", 1).addClass("js_Current").find("dd").show();
                    joms.jQuery("body").bind("click", function(b) {
                        var c = joms.jQuery(b.target);
                        if (c.parents(".js_PriContainer").length == 0) {
                            joms.privacy.closeAll();
                        }
                    });
                }
            });
        }, closeAll: function() {
            joms.jQuery(".js_PriContainer").removeClass("js_PrivacyOpen");
            joms.jQuery(".js_dropDownMaster").each(function() {
                joms.jQuery(this).data("state", 0).removeClass("js_Current").find("dd").hide();
            });
        }}, tooltip: {setup: function() {
            joms.jQuery(".jomNameTips").tipsy({live: true, gravity: "sw"});
            joms.jQuery(".qtip-active").hide();
            setTimeout("joms.jQuery('.qtip-active').hide()", 150);
            try {
                clearTimeout(joms.jQuery.fn.qtip.timers.show);
            } catch (a) {
            }
            joms.jQuery(".jomTips").each(function() {
                var c = "tipNormal";
                var b = 220;
                var j = {corner: {target: "topMiddle", tooltip: "bottomMiddle"}};
                var d = true;
                var g = {when: {event: "mouseout"}, effect: {length: 10}};
                if (joms.jQuery(this).hasClass("tipRight")) {
                    c = "tipRight";
                    b = 320;
                    j = {corner: {target: "rightMiddle", tooltip: "leftMiddle"}};
                }
                if (joms.jQuery(this).hasClass("tipWide")) {
                    b = 420;
                }
                if (joms.jQuery(this).hasClass("tipFullWidth")) {
                    b = joms.jQuery(this).innerWidth() - 20;
                }
                var h = "";
                var f = joms.jQuery(this).attr("title");
                var e = "";
                if (f) {
                    e = f.split("::");
                }
                joms.jQuery(this).attr("title", "");
                if (e.length == 2) {
                    f = e[1];
                    h = {text: e[0]};
                } else {
                    h = h = {text: ""};
                }
                joms.jQuery(this).qtip({content: {text: f, title: h}, style: {name: c, width: b}, position: j, hide: g, show: {solo: true, effect: {length: 50}}}).removeClass("jomTips");
            });
            return true;
        }, setStyle: function() {
            joms.jQuery.fn.qtip.styles.tipNormal = {width: 320, border: {width: 7, radius: 5}, tip: true, name: "dark"};
            joms.jQuery.fn.qtip.styles.tipRight = {tip: "leftMiddle", name: "tipNormal"};
            return true;
        }}, like: {init: function() {
            joms.jQuery('a[href="#like"]').live("click", function() {
                var a = joms.jQuery(this).parents("li.stream").data("streamid");
                jax.call("community", "system,ajaxStreamAddLike", a);
            });
            joms.jQuery('a[href="#unlike"]').live("click", function() {
                var a = joms.jQuery(this).parents("li.stream").data("streamid");
                jax.call("community", "system,ajaxStreamUnlike", a);
            });
        }, extractData: function(b) {
            b = b.split("-");
            var a = [];
            a.element = b[1];
            a.itemid = b[2];
            a.element = a.element.replace("_", ".");
            return a;
        }, like: function(c) {
            var a = joms.jQuery(c).parents(".like-snippet");
            var b = this.extractData(a.attr("id"));
            joms.jQuery(c).attr("onclick", "");
            joms.ajax.call("system,ajaxLike", [b.element, b.itemid], {success: function(d) {
                    a.replaceWith(d);
                }});
        }, dislike: function(c) {
            var a = joms.jQuery(c).parents(".like-snippet");
            var b = this.extractData(a.attr("id"));
            joms.jQuery(c).attr("onclick", "");
            joms.ajax.call("system,ajaxDislike", [b.element, b.itemid], {success: function(d) {
                    a.replaceWith(d);
                }});
        }, unlike: function(c) {
            var a = joms.jQuery(c).parents(".like-snippet");
            var b = this.extractData(a.attr("id"));
            joms.jQuery(c).attr("onclick", "");
            joms.ajax.call("system,ajaxUnlike", [b.element, b.itemid], {success: function(d) {
                    a.replaceWith(d);
                }});
        }}, tag: {add: function(a, b) {
            jax.call("community", "system,ajaxAddTag", a, b, joms.jQuery("#tag-addbox").val());
        }, pick: function(b, c, a) {
            jax.call("community", "system,ajaxAddTag", b, c, a);
        }, remove: function(a) {
            jax.call("community", "system,ajaxRemoveTag", a);
        }, moreHide: function(a, b) {
            joms.jQuery("#tag-list li").each(function(d, c) {
                if (d > 8) {
                    joms.jQuery(c).hide();
                }
            });
            joms.jQuery(".more-tag-show").show();
            joms.jQuery(".more-tag-hide").hide();
        }, moreShow: function(a, b) {
            joms.jQuery("#tag-list li").each(function(d, c) {
                if (d > 8) {
                    joms.jQuery(c).show();
                }
            });
            joms.jQuery(".more-tag-show").hide();
            joms.jQuery(".more-tag-hide").show();
        }, toggleMore: function(a, b) {
            joms.jQuery("#tag-list li").each(function(d, c) {
                if (d > 8) {
                    joms.jQuery(c).toggle();
                }
            });
        }, list: function(a) {
            var b = "jax.call('community', 'system,ajaxShowTagged', '" + a + "');";
            cWindowShow(b, "", 450, 100);
        }, edit: function(a, c) {
            joms.tag.moreShow(a, c);
            var b = a + "-" + c;
            joms.jQuery("#tag-editor.tag-editor-" + b + ",.tag-token a.tag-delete").show();
        }, done: function(a, c) {
            joms.tag.moreHide(a, c);
            var b = a + "-" + c;
            joms.jQuery("#tag-editor.tag-editor-" + b + ",.tag-token a.tag-delete").hide();
        }}, geolocation: {showNearByEvents: function(a) {
            joms.jQuery("#community-event-nearby-listing").show();
            joms.jQuery("#showNearByEventsLoading").show();
            if (typeof(a) == "undefined") {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(b) {
                        var d = b.coords.latitude;
                        var c = b.coords.longitude;
                        geocoder = new google.maps.Geocoder();
                        var e = new google.maps.LatLng(d, c);
                        geocoder.geocode({latLng: e}, function(g, f) {
                            if (f == google.maps.GeocoderStatus.OK) {
                                if (g[4]) {
                                    b = g[4].formatted_address;
                                    joms.geolocation.setCookie("currentLocation", b);
                                    joms.events.displayNearbyEvents(b);
                                }
                            } else {
                                alert("Geocoder failed due to: " + f);
                            }
                        });
                    });
                } else {
                    alert("Sorry, your browser does not support this feature.");
                    joms.jQuery("#community-event-nearby-listing").hide();
                    joms.jQuery("#showNearByEventsLoading").hide();
                }
            } else {
                joms.events.displayNearbyEvents(a);
            }
        }, validateNearByEventsForm: function() {
            var a = joms.jQuery("#userInputLocation").val();
            if (a.length != 0) {
                joms.geolocation.showNearByEvents(a);
            }
        }, setCookie: function(a, b) {
            var c = new Date();
            c.setTime(c.getTime() + (60 * 60 * 1000));
            document.cookie = a + "=" + escape(b) + ";expires=" + c;
        }, getCookie: function(a) {
            if (document.cookie.length > 0) {
                c_start = document.cookie.indexOf(a + "=");
                if (c_start != -1) {
                    c_start = c_start + a.length + 1;
                    c_end = document.cookie.indexOf(";", c_start);
                    if (c_end == -1) {
                        c_end = document.cookie.length;
                    }
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return"";
        }}, file: {showFileUpload: function(a, c) {
            var b = 'jax.call("community", "files,ajaxFileUploadForm","' + a + '",' + c + ")";
            cWindowShow(b, "", 600, 400);
        }, enableEditName: function() {
            if (joms.jQuery(".plupload_file_name span").length > 0) {
                joms.jQuery(".plupload_file_name span").unbind("click");
                joms.jQuery(".plupload_file_name span").bind("click", function(a) {
                    joms.file.editName(a);
                });
            }
        }, editName: function(b) {
            joms.jQuery(".plupload_file_name span").unbind("click");
            var d = (b.target) ? b.target : b.which;
            var a = joms.jQuery(d).parent().parent().attr("id");
            var c = '<input type="text" target="' + a + '" name="filename" value="' + joms.jQuery(d).html() + '" />';
            joms.jQuery(d).html(c);
            joms.jQuery(d).find("input").unbind("keypress").unbind("focusout");
            joms.jQuery(d).find("input").bind("keypress", function(e) {
                keyCode = e.keyCode;
                if (keyCode == 13) {
                    joms.file.saveName(this, joms.jQuery(this).parent());
                    joms.jQuery(".plupload_file_name span").bind("click", function(f) {
                        joms.file.editName(f);
                    });
                }
            });
            joms.jQuery(d).find("input").bind("focusout", function() {
                joms.file.saveName(this, joms.jQuery(this).parent());
                joms.jQuery(".plupload_file_name span").bind("click", function(e) {
                    joms.file.editName(e);
                });
            });
        }, saveName: function(d, f) {
            var b = joms.jQuery(d).attr("target");
            var c = joms.jQuery("#html5_uploader").pluploadQueue().getFile(parseInt(b));
            var e = joms.jQuery(d).val();
            c.name = e;
            var a = encodeURIComponent(e);
            jax.call("community", "files,ajaxSaveName", c.id, a);
            joms.jQuery(f).html(e);
        }, ajaxDeleteFile: function(a, b) {
            jax.call("community", "files,ajaxDeleteFile", a, b);
        }, ajaxUploadFile: function(b, d, c) {
            var a = false;
            joms.jQuery("#html5_uploader").pluploadQueue({runtimes: "html5,html4", url: b, max_file_size: c + "mb", chunk_size: c + "mb", unique_names: true, filters: [{title: "Media files", extensions: d}], preinit: {FileUploaded: function(k, g, f) {
                        try {
                            var j = joms.jQuery.parseJSON(f.response);
                        } catch (h) {
                            joms.jQuery("#html5_uploader").pluploadQueue().stop();
                        }
                        var l = joms.jQuery("#html5_uploader").pluploadQueue().getFile(g.id);
                        l.id = j.id;
                        if (typeof j.msg != "undefined") {
                            joms.jQuery("#html5_uploader").pluploadQueue().stop();
                            joms.jQuery("#cWindowContent").prepend("<strong>" + j.msg + " </strong>");
                        }
                    }, UploadComplete: function(e) {
                        setTimeout("joms.file.enableEditName();", 1000);
                        a = true;
                        joms.jQuery("#upload-footer").show();
                        cWindowAutoResize();
                        joms.jQuery("a.add-more").click(function() {
                            joms.jQuery("#html5_uploader").pluploadQueue().splice();
                            joms.jQuery(".plupload_buttons").show();
                            joms.jQuery(".plupload_file_status").hide();
                            joms.jQuery("div#upload-footer").hide();
                        });
                    }}});
            joms.jQuery(".plupload_header").remove();
            joms.jQuery("span.plupload_upload_status").remove();
            joms.jQuery("input:file").parent().css("top", "265px");
            joms.jQuery("#cwin_close_btn").click(function() {
                if (a) {
                    window.location.reload();
                }
            });
        }, viewFile: function(a, c) {
            var b = 'jax.call("community", "files,ajaxviewFiles","' + a + '","' + c + '")';
            cWindowShow(b, "", 600, 400);
        }, ajaxdownloadFile: function(a, c) {
            var b = 'jax.call("community","files,ajaxFileDownload","' + a + '","' + c + '")';
            cWindowShow(b, "", 600, 200);
        }, getFileList: function(e, c, b, a, d) {
            joms.jQuery("#load-more-btn").html("");
            jax.call("community", "files,ajaxgetFileList", e, c, b, a, d);
        }, updateFileList: function(a, b) {
            currentFiles = joms.jQuery("#" + b).html();
            newFiles = currentFiles + a;
            joms.jQuery("#" + b).html(newFiles);
        }, viewMore: function(a, c) {
            var b = 'jax.call("community","files,ajaxviewMore","' + a + '","' + c + '")';
            cWindowShow(b, "", 600, 200);
        }, loadFile: function(d, c, b, a, e) {
            joms.jQuery("#load-more-btn").html("");
            jax.call("community", "files,ajaxgetFileList", d, c, b, a, e);
        }, searchFileUpdate: function(a, b) {
            joms.jQuery(joms.jQuery("ul.cTabNav li.active").children().attr("href")).html(a);
        }}});
joms.jQuery(document).click(function() {
    joms.toolbar.close();
});
function update_counter(a, c) {
    if (!c) {
        c = 0;
    }
    var b = parseInt(joms.jQuery(a).html(), 10);
    c = parseInt(c, 10);
    if (b <= 1) {
        joms.jQuery(a).css("display", "none");
    } else {
        joms.jQuery(a).html(b + c);
    }
}
function get_cookies_array() {
    var d = {};
    if (document.cookie && document.cookie != "") {
        var b = document.cookie.split(";");
        for (var a = 0; a < b.length; a++) {
            var c = b[a].split("=");
            c[0] = c[0].replace(/^ /, "");
            d[decodeURIComponent(c[0])] = decodeURIComponent(c[1]);
        }
    }
    return d;
}
joms.jQuery(document).ready(function() {
    joms.tooltip.setStyle();
    joms.tooltip.setup();
    joms.apps.initToggle();
    joms.plugins.initialize();
    if (joms.jQuery(".cStreamList li").length) {
        joms.miniwall.initialize();
    }
    var a = window.location;
    joms.maps.init();
    joms.like.init();
    var b = 0;
    joms.jQuery(".cTabsBar li").each(function() {
        joms.jQuery(this).attr("id", "cTab-" + b).children("a").click(function() {
            if (joms.jQuery(this).parent("li").hasClass("cTabDisabled")) {
                joms.jQuery(this).blur();
                return;
            }
            joms.jQuery(".cTabsBar li").removeClass("cTabCurrent");
            if (!joms.jQuery(joms.jQuery(this).parents().find("li#cTab-0")).hasClass("cTabDisabled")) {
                joms.jQuery(".cTabsContent").removeClass("cTabsContentCurrent").trigger("onAfterHide");
            }
            var c = joms.jQuery(this).parent("li").index();
            joms.jQuery("#cTab-" + c).addClass("cTabCurrent");
            joms.jQuery("#cTabContent-" + c).addClass("cTabsContentCurrent").trigger("onAfterShow");
        });
        joms.jQuery(".cTabsContentWrap .cTabsContent:eq(" + (b) + ")").attr("id", "cTabContent-" + b);
        if (joms.jQuery(this).hasClass("cTabCurrent")) {
            joms.jQuery(".cTabsContentWrap .cTabsContent:eq(" + (b) + ")").data("status", 1);
        }
        b++;
    });
});
joms.jQuery.cookie = function(b, j, m) {
    if (typeof j != "undefined") {
        m = m || {};
        if (j === null) {
            j = "";
            m.expires = -1;
        }
        var e = "";
        if (m.expires && (typeof m.expires == "number" || m.expires.toUTCString)) {
            var f;
            if (typeof m.expires == "number") {
                f = new Date();
                f.setTime(f.getTime() + (m.expires * 24 * 60 * 60 * 1000));
            } else {
                f = m.expires;
            }
            e = "; expires=" + f.toUTCString();
        }
        var l = m.path ? "; path=" + (m.path) : "";
        var g = m.domain ? "; domain=" + (m.domain) : "";
        var a = m.secure ? "; secure" : "";
        document.cookie = [b, "=", encodeURIComponent(j), e, l, g, a].join("");
    } else {
        var d = null;
        if (document.cookie && document.cookie != "") {
            var k = document.cookie.split(";");
            for (var h = 0; h < k.length; h++) {
                var c = joms.jQuery.trim(k[h]);
                if (c.substring(0, b.length + 1) == (b + "=")) {
                    d = decodeURIComponent(c.substring(b.length + 1));
                    break;
                }
            }
        }
        return d;
    }
};
(function(a) {
    a.fn.autogrow = function(b) {
        var b = b || {};
        this.filter("textarea").each(function() {
            var c = a(this);
            if (c.hasClass("shadow")) {
                return;
            }
            var f = c.data("shadow");
            var e = c.outerHeight() - c.innerHeight();
            if (!f) {
                f = c.clone().unbind().removeAttr("name").addClass("shadow").css({position: "absolute", visibility: "hidden", height: 0}).insertAfter(c);
                if (b.lineHeight == undefined) {
                    b.lineHeight = f.val(" ")[0].scrollHeight;
                }
                c.data("shadow", f).bind("focus blur keyup keypress autogrow", d);
            }
            if (b.minHeight == undefined) {
                b.minHeight = c.height();
            }
            if (b.maxHeight == undefined) {
                b.maxHeight = 0;
            }
            function d() {
                f.val(c.val());
                f[0].scrollHeight;
                var g = f[0].scrollHeight;
                if (g > b.maxHeight && b.maxHeight > 0) {
                    g = b.maxHeight;
                    c.css("overflow", "auto");
                } else {
                    g = (g < b.minHeight) ? b.minHeight : g;
                    c.css("overflow", "hidden");
                }
                c.height(g);
            }
            d();
        });
        return this;
    };
})(joms.jQuery);
(function(D) {
    D.fn.qtip = function(a, h) {
        var d, j, b, k, e, f, g, c;
        if (typeof a == "string") {
            if (typeof D(this).data("qtip") !== "object") {
                D.fn.qtip.log.error.call(self, 1, D.fn.qtip.constants.NO_TOOLTIP_PRESENT, false);
            }
            if (a == "api") {
                return D(this).data("qtip").interfaces[D(this).data("qtip").current];
            } else {
                if (a == "interfaces") {
                    return D(this).data("qtip").interfaces;
                }
            }
        } else {
            if (!a) {
                a = {};
            }
            if (typeof a.content !== "object" || (a.content.jquery && a.content.length > 0)) {
                a.content = {text: a.content};
            }
            if (typeof a.content.title !== "object") {
                a.content.title = {text: a.content.title};
            }
            if (typeof a.position !== "object") {
                a.position = {corner: a.position};
            }
            if (typeof a.position.corner !== "object") {
                a.position.corner = {target: a.position.corner, tooltip: a.position.corner};
            }
            if (typeof a.show !== "object") {
                a.show = {when: a.show};
            }
            if (typeof a.show.when !== "object") {
                a.show.when = {event: a.show.when};
            }
            if (typeof a.show.effect !== "object") {
                a.show.effect = {type: a.show.effect};
            }
            if (typeof a.hide !== "object") {
                a.hide = {when: a.hide};
            }
            if (typeof a.hide.when !== "object") {
                a.hide.when = {event: a.hide.when};
            }
            if (typeof a.hide.effect !== "object") {
                a.hide.effect = {type: a.hide.effect};
            }
            if (typeof a.style !== "object") {
                a.style = {name: a.style};
            }
            a.style = G(a.style);
            k = D.extend(true, {}, D.fn.qtip.defaults, a);
            k.style = I.call({options: k}, k.style);
            k.user = D.extend(true, {}, a);
        }
        return D(this).each(function() {
            if (typeof a == "string") {
                f = a.toLowerCase();
                b = D(this).qtip("interfaces");
                if (typeof b == "object") {
                    if (h === true && f == "destroy") {
                        while (b.length > 0) {
                            b[b.length - 1].destroy();
                        }
                    } else {
                        if (h !== true) {
                            b = [D(this).qtip("api")];
                        }
                        for (d = 0; d < b.length; d++) {
                            if (f == "destroy") {
                                b[d].destroy();
                            } else {
                                if (b[d].status.rendered === true) {
                                    if (f == "show") {
                                        b[d].show();
                                    } else {
                                        if (f == "hide") {
                                            b[d].hide();
                                        } else {
                                            if (f == "focus") {
                                                b[d].focus();
                                            } else {
                                                if (f == "disable") {
                                                    b[d].disable(true);
                                                } else {
                                                    if (f == "enable") {
                                                        b[d].disable(false);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                g = D.extend(true, {}, k);
                g.hide.effect.length = k.hide.effect.length;
                g.show.effect.length = k.show.effect.length;
                if (g.position.container === false) {
                    g.position.container = D(document.body);
                }
                if (g.position.target === false) {
                    g.position.target = D(this);
                }
                if (g.show.when.target === false) {
                    g.show.when.target = D(this);
                }
                if (g.hide.when.target === false) {
                    g.hide.when.target = D(this);
                }
                j = D.fn.qtip.interfaces.length;
                for (d = 0; d < j; d++) {
                    if (typeof D.fn.qtip.interfaces[d] == "undefined") {
                        j = d;
                        break;
                    }
                }
                e = new F(D(this), g, j);
                D.fn.qtip.interfaces[j] = e;
                if (typeof D(this).data("qtip") == "object") {
                    if (typeof D(this).attr("qtip") === "undefined") {
                        D(this).data("qtip").current = D(this).data("qtip").interfaces.length;
                    }
                    D(this).data("qtip").interfaces.push(e);
                } else {
                    D(this).data("qtip", {current: 0, interfaces: [e]});
                }
                if (g.content.prerender === false && g.show.when.event !== false && g.show.ready !== true) {
                    g.show.when.target.bind(g.show.when.event + ".qtip-" + j + "-create", {qtip: j}, function(l) {
                        c = D.fn.qtip.interfaces[l.data.qtip];
                        c.options.show.when.target.unbind(c.options.show.when.event + ".qtip-" + l.data.qtip + "-create");
                        c.cache.mouse = {x: l.pageX, y: l.pageY};
                        u.call(c);
                        c.options.show.when.target.trigger(c.options.show.when.event);
                    });
                } else {
                    e.cache.mouse = {x: g.show.when.target.offset().left, y: g.show.when.target.offset().top};
                    u.call(e);
                }
            }
        });
    };
    function F(b, c, a) {
        var d = this;
        d.id = a;
        d.options = c;
        d.status = {animated: false, rendered: false, disabled: false, focused: false};
        d.elements = {target: b.addClass(d.options.style.classes.target), tooltip: null, wrapper: null, content: null, contentWrapper: null, title: null, button: null, tip: null, bgiframe: null};
        d.cache = {mouse: {}, position: {}, toggle: 0};
        d.timers = {};
        D.extend(d, d.options.api, {show: function(h) {
                var e, g;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "show");
                }
                if (d.elements.tooltip.css("display") !== "none") {
                    return d;
                }
                d.elements.tooltip.stop(true, false);
                e = d.beforeShow.call(d, h);
                if (e === false) {
                    return d;
                }
                function f() {
                    if (d.options.position.type !== "static") {
                        d.focus();
                    }
                    d.onShow.call(d, h);
                    if (D.browser.msie) {
                        d.elements.tooltip.get(0).style.removeAttribute("filter");
                    }
                }
                d.cache.toggle = 1;
                if (d.options.position.type !== "static") {
                    d.updatePosition(h, (d.options.show.effect.length > 0));
                }
                if (typeof d.options.show.solo == "object") {
                    g = D(d.options.show.solo);
                } else {
                    if (d.options.show.solo === true) {
                        g = D("div.qtip").not(d.elements.tooltip);
                    }
                }
                if (g) {
                    g.each(function() {
                        if (D(this).qtip("api").status.rendered === true) {
                            D(this).qtip("api").hide();
                        }
                    });
                }
                if (typeof d.options.show.effect.type == "function") {
                    d.options.show.effect.type.call(d.elements.tooltip, d.options.show.effect.length);
                    d.elements.tooltip.queue(function() {
                        f();
                        D(this).dequeue();
                    });
                } else {
                    switch (d.options.show.effect.type.toLowerCase()) {
                        case"fade":
                            d.elements.tooltip.fadeIn(d.options.show.effect.length, f);
                            break;
                        case"slide":
                            d.elements.tooltip.slideDown(d.options.show.effect.length, function() {
                                f();
                                if (d.options.position.type !== "static") {
                                    d.updatePosition(h, true);
                                }
                            });
                            break;
                        case"grow":
                            d.elements.tooltip.show(d.options.show.effect.length, f);
                            break;
                        default:
                            d.elements.tooltip.show(null, f);
                            break;
                    }
                    d.elements.tooltip.addClass(d.options.style.classes.active);
                }
                return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_SHOWN, "show");
            }, hide: function(g) {
                var e;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "hide");
                } else {
                    if (d.elements.tooltip.css("display") === "none") {
                        return d;
                    }
                }
                clearTimeout(d.timers.show);
                d.elements.tooltip.stop(true, false);
                e = d.beforeHide.call(d, g);
                if (e === false) {
                    return d;
                }
                function f() {
                    d.onHide.call(d, g);
                }
                d.cache.toggle = 0;
                if (typeof d.options.hide.effect.type == "function") {
                    d.options.hide.effect.type.call(d.elements.tooltip, d.options.hide.effect.length);
                    d.elements.tooltip.queue(function() {
                        f();
                        D(this).dequeue();
                    });
                } else {
                    switch (d.options.hide.effect.type.toLowerCase()) {
                        case"fade":
                            d.elements.tooltip.fadeOut(d.options.hide.effect.length, f);
                            break;
                        case"slide":
                            d.elements.tooltip.slideUp(d.options.hide.effect.length, f);
                            break;
                        case"grow":
                            d.elements.tooltip.hide(d.options.hide.effect.length, f);
                            break;
                        default:
                            d.elements.tooltip.hide(null, f);
                            break;
                    }
                    d.elements.tooltip.removeClass(d.options.style.classes.active);
                }
                return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_HIDDEN, "hide");
            }, updatePosition: function(q, o) {
                var f, M, k, n, r, O, m, p, g, e, l, h, N, j;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "updatePosition");
                } else {
                    if (d.options.position.type == "static") {
                        return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.CANNOT_POSITION_STATIC, "updatePosition");
                    }
                }
                M = {position: {left: 0, top: 0}, dimensions: {height: 0, width: 0}, corner: d.options.position.corner.target};
                k = {position: d.getPosition(), dimensions: d.getDimensions(), corner: d.options.position.corner.tooltip};
                if (d.options.position.target !== "mouse") {
                    if (d.options.position.target.get(0).nodeName.toLowerCase() == "area") {
                        n = d.options.position.target.attr("coords").split(",");
                        for (f = 0; f < n.length; f++) {
                            n[f] = parseInt(n[f]);
                        }
                        r = d.options.position.target.parent("map").attr("name");
                        O = D('img[usemap="#' + r + '"]:first').offset();
                        M.position = {left: Math.floor(O.left + n[0]), top: Math.floor(O.top + n[1])};
                        switch (d.options.position.target.attr("shape").toLowerCase()) {
                            case"rect":
                                M.dimensions = {width: Math.ceil(Math.abs(n[2] - n[0])), height: Math.ceil(Math.abs(n[3] - n[1]))};
                                break;
                            case"circle":
                                M.dimensions = {width: n[2] + 1, height: n[2] + 1};
                                break;
                            case"poly":
                                M.dimensions = {width: n[0], height: n[1]};
                                for (f = 0; f < n.length; f++) {
                                    if (f % 2 == 0) {
                                        if (n[f] > M.dimensions.width) {
                                            M.dimensions.width = n[f];
                                        }
                                        if (n[f] < n[0]) {
                                            M.position.left = Math.floor(O.left + n[f]);
                                        }
                                    } else {
                                        if (n[f] > M.dimensions.height) {
                                            M.dimensions.height = n[f];
                                        }
                                        if (n[f] < n[1]) {
                                            M.position.top = Math.floor(O.top + n[f]);
                                        }
                                    }
                                }
                                M.dimensions.width = M.dimensions.width - (M.position.left - O.left);
                                M.dimensions.height = M.dimensions.height - (M.position.top - O.top);
                                break;
                            default:
                                return D.fn.qtip.log.error.call(d, 4, D.fn.qtip.constants.INVALID_AREA_SHAPE, "updatePosition");
                                break;
                        }
                        M.dimensions.width -= 2;
                        M.dimensions.height -= 2;
                    } else {
                        if (d.options.position.target.add(document.body).length === 1) {
                            M.position = {left: D(document).scrollLeft(), top: D(document).scrollTop()};
                            M.dimensions = {height: D(window).height(), width: D(window).width()};
                        } else {
                            if (typeof d.options.position.target.attr("qtip") !== "undefined") {
                                M.position = d.options.position.target.qtip("api").cache.position;
                            } else {
                                M.position = d.options.position.target.offset();
                            }
                            M.dimensions = {height: d.options.position.target.outerHeight(), width: d.options.position.target.outerWidth()};
                        }
                    }
                    m = D.extend({}, M.position);
                    if (M.corner.search(/right/i) !== -1) {
                        m.left += M.dimensions.width;
                    }
                    if (M.corner.search(/bottom/i) !== -1) {
                        m.top += M.dimensions.height;
                    }
                    if (M.corner.search(/((top|bottom)Middle)|center/) !== -1) {
                        m.left += (M.dimensions.width / 2);
                    }
                    if (M.corner.search(/((left|right)Middle)|center/) !== -1) {
                        m.top += (M.dimensions.height / 2);
                    }
                } else {
                    M.position = m = {left: d.cache.mouse.x, top: d.cache.mouse.y};
                    M.dimensions = {height: 1, width: 1};
                }
                if (k.corner.search(/right/i) !== -1) {
                    m.left -= k.dimensions.width;
                }
                if (k.corner.search(/bottom/i) !== -1) {
                    m.top -= k.dimensions.height;
                }
                if (k.corner.search(/((top|bottom)Middle)|center/) !== -1) {
                    m.left -= (k.dimensions.width / 2);
                }
                if (k.corner.search(/((left|right)Middle)|center/) !== -1) {
                    m.top -= (k.dimensions.height / 2);
                }
                p = (D.browser.msie) ? 1 : 0;
                g = (D.browser.msie && parseInt(D.browser.version.charAt(0)) === 6) ? 1 : 0;
                if (d.options.style.border.radius > 0) {
                    if (k.corner.search(/Left/) !== -1) {
                        m.left -= d.options.style.border.radius;
                    } else {
                        if (k.corner.search(/Right/) !== -1) {
                            m.left += d.options.style.border.radius;
                        }
                    }
                    if (k.corner.search(/Top/) !== -1) {
                        m.top -= d.options.style.border.radius;
                    } else {
                        if (k.corner.search(/Bottom/) !== -1) {
                            m.top += d.options.style.border.radius;
                        }
                    }
                }
                if (p) {
                    if (k.corner.search(/top/) !== -1) {
                        m.top -= p;
                    } else {
                        if (k.corner.search(/bottom/) !== -1) {
                            m.top += p;
                        }
                    }
                    if (k.corner.search(/left/) !== -1) {
                        m.left -= p;
                    } else {
                        if (k.corner.search(/right/) !== -1) {
                            m.left += p;
                        }
                    }
                    if (k.corner.search(/leftMiddle|rightMiddle/) !== -1) {
                        m.top -= 1;
                    }
                }
                if (d.options.position.adjust.screen === true) {
                    m = v.call(d, m, M, k);
                }
                if (d.options.position.target === "mouse" && d.options.position.adjust.mouse === true) {
                    if (d.options.position.adjust.screen === true && d.elements.tip) {
                        l = d.elements.tip.attr("rel");
                    } else {
                        l = d.options.position.corner.tooltip;
                    }
                    m.left += (l.search(/right/i) !== -1) ? -6 : 6;
                    m.top += (l.search(/bottom/i) !== -1) ? -6 : 6;
                }
                if (!d.elements.bgiframe && D.browser.msie && parseInt(D.browser.version.charAt(0)) == 6) {
                    D("select, object").each(function() {
                        h = D(this).offset();
                        h.bottom = h.top + D(this).height();
                        h.right = h.left + D(this).width();
                        if (m.top + k.dimensions.height >= h.top && m.left + k.dimensions.width >= h.left) {
                            z.call(d);
                        }
                    });
                }
                m.left += d.options.position.adjust.x;
                m.top += d.options.position.adjust.y;
                N = d.getPosition();
                if (m.left != N.left || m.top != N.top) {
                    j = d.beforePositionUpdate.call(d, q);
                    if (j === false) {
                        return d;
                    }
                    d.cache.position = m;
                    if (o === true) {
                        d.status.animated = true;
                        d.elements.tooltip.animate(m, 200, "swing", function() {
                            d.status.animated = false;
                        });
                    } else {
                        d.elements.tooltip.css(m);
                    }
                    d.onPositionUpdate.call(d, q);
                    if (typeof q !== "undefined" && q.type && q.type !== "mousemove") {
                        D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_POSITION_UPDATED, "updatePosition");
                    }
                }
                return d;
            }, updateWidth: function(f) {
                var e;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "updateWidth");
                } else {
                    if (f && typeof f !== "number") {
                        return D.fn.qtip.log.error.call(d, 2, "newWidth must be of type number", "updateWidth");
                    }
                }
                e = d.elements.contentWrapper.siblings().add(d.elements.tip).add(d.elements.button);
                if (!f) {
                    if (typeof d.options.style.width.value == "number") {
                        f = d.options.style.width.value;
                    } else {
                        d.elements.tooltip.css({width: "auto"});
                        e.hide();
                        if (D.browser.msie) {
                            d.elements.wrapper.add(d.elements.contentWrapper.children()).css({zoom: "normal"});
                        }
                        f = d.getDimensions().width + 1;
                        if (!d.options.style.width.value) {
                            if (f > d.options.style.width.max) {
                                f = d.options.style.width.max;
                            }
                            if (f < d.options.style.width.min) {
                                f = d.options.style.width.min;
                            }
                        }
                    }
                }
                if (f % 2 !== 0) {
                    f -= 1;
                }
                d.elements.tooltip.width(f);
                e.show();
                if (d.options.style.border.radius) {
                    d.elements.tooltip.find(".qtip-betweenCorners").each(function(g) {
                        D(this).width(f - (d.options.style.border.radius * 2));
                    });
                }
                if (D.browser.msie) {
                    d.elements.wrapper.add(d.elements.contentWrapper.children()).css({zoom: "1"});
                    d.elements.wrapper.width(f);
                    if (d.elements.bgiframe) {
                        d.elements.bgiframe.width(f).height(d.getDimensions.height);
                    }
                }
                return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_WIDTH_UPDATED, "updateWidth");
            }, updateStyle: function(g) {
                var h, f, e, k, j;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "updateStyle");
                } else {
                    if (typeof g !== "string" || !D.fn.qtip.styles[g]) {
                        return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.STYLE_NOT_DEFINED, "updateStyle");
                    }
                }
                d.options.style = I.call(d, D.fn.qtip.styles[g], d.options.user.style);
                d.elements.content.css(t(d.options.style));
                if (d.options.content.title.text !== false) {
                    d.elements.title.css(t(d.options.style.title, true));
                }
                d.elements.contentWrapper.css({borderColor: d.options.style.border.color});
                if (d.options.style.tip.corner !== false) {
                    if (D("<canvas>").get(0).getContext) {
                        h = d.elements.tooltip.find(".qtip-tip canvas:first");
                        e = h.get(0).getContext("2d");
                        e.clearRect(0, 0, 300, 300);
                        k = h.parent("div[rel]:first").attr("rel");
                        j = H(k, d.options.style.tip.size.width, d.options.style.tip.size.height);
                        B.call(d, h, j, d.options.style.tip.color || d.options.style.border.color);
                    } else {
                        if (D.browser.msie) {
                            h = d.elements.tooltip.find('.qtip-tip [nodeName="shape"]');
                            h.attr("fillcolor", d.options.style.tip.color || d.options.style.border.color);
                        }
                    }
                }
                if (d.options.style.border.radius > 0) {
                    d.elements.tooltip.find(".qtip-betweenCorners").css({backgroundColor: d.options.style.border.color});
                    if (D("<canvas>").get(0).getContext) {
                        f = C(d.options.style.border.radius);
                        d.elements.tooltip.find(".qtip-wrapper canvas").each(function() {
                            e = D(this).get(0).getContext("2d");
                            e.clearRect(0, 0, 300, 300);
                            k = D(this).parent("div[rel]:first").attr("rel");
                            s.call(d, D(this), f[k], d.options.style.border.radius, d.options.style.border.color);
                        });
                    } else {
                        if (D.browser.msie) {
                            d.elements.tooltip.find('.qtip-wrapper [nodeName="arc"]').each(function() {
                                D(this).attr("fillcolor", d.options.style.border.color);
                            });
                        }
                    }
                }
                return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_STYLE_UPDATED, "updateStyle");
            }, updateContent: function(f, k) {
                var h, e, g;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "updateContent");
                } else {
                    if (!f) {
                        return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.NO_CONTENT_PROVIDED, "updateContent");
                    }
                }
                h = d.beforeContentUpdate.call(d, f);
                if (typeof h == "string") {
                    f = h;
                } else {
                    if (h === false) {
                        return;
                    }
                }
                if (D.browser.msie) {
                    d.elements.contentWrapper.children().css({zoom: "normal"});
                }
                if (f.jquery && f.length > 0) {
                    f.clone(true).appendTo(d.elements.content).show();
                } else {
                    d.elements.content.html(f);
                }
                e = d.elements.content.find("img[complete=false]");
                if (e.length > 0) {
                    g = 0;
                    e.each(function(l) {
                        D('<img src="' + D(this).attr("src") + '" />').load(function() {
                            if (++g == e.length) {
                                j();
                            }
                        });
                    });
                } else {
                    j();
                }
                function j() {
                    d.updateWidth();
                    if (k !== false) {
                        if (d.options.position.type !== "static") {
                            d.updatePosition(d.elements.tooltip.is(":visible"), true);
                        }
                        if (d.options.style.tip.corner !== false) {
                            w.call(d);
                        }
                    }
                }
                d.onContentUpdate.call(d);
                return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_CONTENT_UPDATED, "loadContent");
            }, loadContent: function(g, h, f) {
                var j;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "loadContent");
                }
                j = d.beforeContentLoad.call(d);
                if (j === false) {
                    return d;
                }
                if (f == "post") {
                    D.post(g, h, e);
                } else {
                    D.get(g, h, e);
                }
                function e(k) {
                    d.onContentLoad.call(d);
                    D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_CONTENT_LOADED, "loadContent");
                    d.updateContent(k);
                }
                return d;
            }, updateTitle: function(e) {
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "updateTitle");
                } else {
                    if (!e) {
                        return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.NO_CONTENT_PROVIDED, "updateTitle");
                    }
                }
                returned = d.beforeTitleUpdate.call(d);
                if (returned === false) {
                    return d;
                }
                if (d.elements.button) {
                    d.elements.button = d.elements.button.clone(true);
                }
                d.elements.title.html(e);
                if (d.elements.button) {
                    d.elements.title.prepend(d.elements.button);
                }
                d.onTitleUpdate.call(d);
                return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_TITLE_UPDATED, "updateTitle");
            }, focus: function(f) {
                var j, e, g, h;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "focus");
                } else {
                    if (d.options.position.type == "static") {
                        return D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.CANNOT_FOCUS_STATIC, "focus");
                    }
                }
                j = parseInt(d.elements.tooltip.css("z-index"));
                e = 6000 + D("div.qtip[qtip]").length - 1;
                if (!d.status.focused && j !== e) {
                    h = d.beforeFocus.call(d, f);
                    if (h === false) {
                        return d;
                    }
                    D("div.qtip[qtip]").not(d.elements.tooltip).each(function() {
                        if (D(this).qtip("api").status.rendered === true) {
                            g = parseInt(D(this).css("z-index"));
                            if (typeof g == "number" && g > -1) {
                                D(this).css({zIndex: parseInt(D(this).css("z-index")) - 1});
                            }
                            D(this).qtip("api").status.focused = false;
                        }
                    });
                    d.elements.tooltip.css({zIndex: e});
                    d.status.focused = true;
                    d.onFocus.call(d, f);
                    D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_FOCUSED, "focus");
                }
                return d;
            }, disable: function(e) {
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "disable");
                }
                if (e) {
                    if (!d.status.disabled) {
                        d.status.disabled = true;
                        D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_DISABLED, "disable");
                    } else {
                        D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.TOOLTIP_ALREADY_DISABLED, "disable");
                    }
                } else {
                    if (d.status.disabled) {
                        d.status.disabled = false;
                        D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_ENABLED, "disable");
                    } else {
                        D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.TOOLTIP_ALREADY_ENABLED, "disable");
                    }
                }
                return d;
            }, destroy: function() {
                var f, e, g;
                e = d.beforeDestroy.call(d);
                if (e === false) {
                    return d;
                }
                if (d.status.rendered) {
                    d.options.show.when.target.unbind("mousemove.qtip", d.updatePosition);
                    d.options.show.when.target.unbind("mouseout.qtip", d.hide);
                    d.options.show.when.target.unbind(d.options.show.when.event + ".qtip");
                    d.options.hide.when.target.unbind(d.options.hide.when.event + ".qtip");
                    d.elements.tooltip.unbind(d.options.hide.when.event + ".qtip");
                    d.elements.tooltip.unbind("mouseover.qtip", d.focus);
                    d.elements.tooltip.remove();
                } else {
                    d.options.show.when.target.unbind(d.options.show.when.event + ".qtip-create");
                }
                if (typeof d.elements.target.data("qtip") == "object") {
                    g = d.elements.target.data("qtip").interfaces;
                    if (typeof g == "object" && g.length > 0) {
                        for (f = 0; f < g.length - 1;
                                f++) {
                            if (g[f].id == d.id) {
                                g.splice(f, 1);
                            }
                        }
                    }
                }
                delete D.fn.qtip.interfaces[d.id];
                if (typeof g == "object" && g.length > 0) {
                    d.elements.target.data("qtip").current = g.length - 1;
                } else {
                    d.elements.target.removeData("qtip");
                }
                d.onDestroy.call(d);
                D.fn.qtip.log.error.call(d, 1, D.fn.qtip.constants.EVENT_DESTROYED, "destroy");
                return d.elements.target;
            }, getPosition: function() {
                var f, e;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "getPosition");
                }
                f = (d.elements.tooltip.css("display") !== "none") ? false : true;
                if (f) {
                    d.elements.tooltip.css({visiblity: "hidden"}).show();
                }
                e = d.elements.tooltip.offset();
                if (f) {
                    d.elements.tooltip.css({visiblity: "visible"}).hide();
                }
                return e;
            }, getDimensions: function() {
                var f, e;
                if (!d.status.rendered) {
                    return D.fn.qtip.log.error.call(d, 2, D.fn.qtip.constants.TOOLTIP_NOT_RENDERED, "getDimensions");
                }
                f = (!d.elements.tooltip.is(":visible")) ? true : false;
                if (f) {
                    d.elements.tooltip.css({visiblity: "hidden"}).show();
                }
                e = {height: d.elements.tooltip.outerHeight(), width: d.elements.tooltip.outerWidth()};
                if (f) {
                    d.elements.tooltip.css({visiblity: "visible"}).hide();
                }
                return e;
            }});
    }
    function u() {
        var f, b, d, e, c, g, a;
        f = this;
        f.beforeRender.call(f);
        f.status.rendered = true;
        f.elements.tooltip = '<div qtip="' + f.id + '" class="qtip ' + (f.options.style.classes.tooltip || f.options.style) + '"style="display:none; -moz-border-radius:0; -webkit-border-radius:0; border-radius:0;position:' + f.options.position.type + ';">  <div class="qtip-wrapper" style="position:relative; overflow:hidden; text-align:left;">    <div class="qtip-contentWrapper" style="overflow:hidden;">       <div class="qtip-content ' + f.options.style.classes.content + '"></div></div></div></div>';
        f.elements.tooltip = D(f.elements.tooltip);
        f.elements.tooltip.appendTo(f.options.position.container);
        f.elements.tooltip.data("qtip", {current: 0, interfaces: [f]});
        f.elements.wrapper = f.elements.tooltip.children("div:first");
        f.elements.contentWrapper = f.elements.wrapper.children("div:first").css({background: f.options.style.background});
        f.elements.content = f.elements.contentWrapper.children("div:first").css(t(f.options.style));
        if (D.browser.msie) {
            f.elements.wrapper.add(f.elements.content).css({zoom: 1});
        }
        if (f.options.hide.when.event == "unfocus") {
            f.elements.tooltip.attr("unfocus", true);
        }
        if (typeof f.options.style.width.value == "number") {
            f.updateWidth();
        }
        if (D("<canvas>").get(0).getContext || D.browser.msie) {
            if (f.options.style.border.radius > 0) {
                x.call(f);
            } else {
                f.elements.contentWrapper.css({border: f.options.style.border.width + "px solid " + f.options.style.border.color});
            }
            if (f.options.style.tip.corner !== false) {
                E.call(f);
            }
        } else {
            f.elements.contentWrapper.css({border: f.options.style.border.width + "px solid " + f.options.style.border.color});
            f.options.style.border.radius = 0;
            f.options.style.tip.corner = false;
            D.fn.qtip.log.error.call(f, 2, D.fn.qtip.constants.CANVAS_VML_NOT_SUPPORTED, "render");
        }
        if ((typeof f.options.content.text == "string" && f.options.content.text.length > 0) || (f.options.content.text.jquery && f.options.content.text.length > 0)) {
            d = f.options.content.text;
        } else {
            if (typeof f.elements.target.attr("title") == "string" && f.elements.target.attr("title").length > 0) {
                d = f.elements.target.attr("title").replace("\\n", "<br />");
                f.elements.target.attr("title", "");
            } else {
                if (typeof f.elements.target.attr("alt") == "string" && f.elements.target.attr("alt").length > 0) {
                    d = f.elements.target.attr("alt").replace("\\n", "<br />");
                    f.elements.target.attr("alt", "");
                } else {
                    d = " ";
                    D.fn.qtip.log.error.call(f, 1, D.fn.qtip.constants.NO_VALID_CONTENT, "render");
                }
            }
        }
        if (f.options.content.title.text !== false) {
            A.call(f);
        }
        f.updateContent(d);
        y.call(f);
        if (f.options.show.ready === true) {
            f.show();
        }
        if (f.options.content.url !== false) {
            e = f.options.content.url;
            c = f.options.content.data;
            g = f.options.content.method || "get";
            f.loadContent(e, c, g);
        }
        f.onRender.call(f);
        D.fn.qtip.log.error.call(f, 1, D.fn.qtip.constants.EVENT_RENDERED, "render");
    }
    function x() {
        var m, e, n, c, g, p, l, k, a, f, h, b, d, o, j;
        m = this;
        m.elements.wrapper.find(".qtip-borderBottom, .qtip-borderTop").remove();
        n = m.options.style.border.width;
        c = m.options.style.border.radius;
        g = m.options.style.border.color || m.options.style.tip.color;
        p = C(c);
        l = {};
        for (e in p) {
            l[e] = '<div rel="' + e + '" style="' + ((e.search(/Left/) !== -1) ? "left" : "right") + ":0; position:absolute; height:" + c + "px; width:" + c + 'px; overflow:hidden; line-height:0.1px; font-size:1px">';
            if (D("<canvas>").get(0).getContext) {
                l[e] += '<canvas height="' + c + '" width="' + c + '" style="vertical-align: top"></canvas>';
            } else {
                if (D.browser.msie) {
                    k = c * 2 + 3;
                    l[e] += '<v:arc stroked="false" fillcolor="' + g + '" startangle="' + p[e][0] + '" endangle="' + p[e][1] + '" style="width:' + k + "px; height:" + k + "px; margin-top:" + ((e.search(/bottom/) !== -1) ? -2 : -1) + "px; margin-left:" + ((e.search(/Right/) !== -1) ? p[e][2] - 3.5 : -1) + 'px; vertical-align:top; display:inline-block; behavior:url(#default#VML)"></v:arc>';
                }
            }
            l[e] += "</div>";
        }
        a = m.getDimensions().width - (Math.max(n, c) * 2);
        f = '<div class="qtip-betweenCorners" style="height:' + c + "px; width:" + a + "px; overflow:hidden; background-color:" + g + '; line-height:0.1px; font-size:1px;">';
        h = '<div class="qtip-borderTop" dir="ltr" style="height:' + c + "px; margin-left:" + c + 'px; line-height:0.1px; font-size:1px; padding:0;">' + l.topLeft + l.topRight + f;
        m.elements.wrapper.prepend(h);
        b = '<div class="qtip-borderBottom" dir="ltr" style="height:' + c + "px; margin-left:" + c + 'px; line-height:0.1px; font-size:1px; padding:0;">' + l.bottomLeft + l.bottomRight + f;
        m.elements.wrapper.append(b);
        if (D("<canvas>").get(0).getContext) {
            m.elements.wrapper.find("canvas").each(function() {
                d = p[D(this).parent("[rel]:first").attr("rel")];
                s.call(m, D(this), d, c, g);
            });
        } else {
            if (D.browser.msie) {
                m.elements.tooltip.append('<v:image style="behavior:url(#default#VML);"></v:image>');
            }
        }
        o = Math.max(c, (c + (n - c)));
        j = Math.max(n - c, 0);
        m.elements.contentWrapper.css({border: "0px solid " + g, borderWidth: j + "px " + o + "px"});
    }
    function s(c, a, e, d) {
        var b = c.get(0).getContext("2d");
        b.fillStyle = d;
        b.beginPath();
        b.arc(a[0], a[1], e, 0, Math.PI * 2, false);
        b.fill();
    }
    function E(c) {
        var e, f, a, d, b;
        e = this;
        if (e.elements.tip !== null) {
            e.elements.tip.remove();
        }
        f = e.options.style.tip.color || e.options.style.border.color;
        if (e.options.style.tip.corner === false) {
            return;
        } else {
            if (!c) {
                c = e.options.style.tip.corner;
            }
        }
        a = H(c, e.options.style.tip.size.width, e.options.style.tip.size.height);
        e.elements.tip = '<div class="' + e.options.style.classes.tip + '" dir="ltr" rel="' + c + '" style="position:absolute; height:' + e.options.style.tip.size.height + "px; width:" + e.options.style.tip.size.width + 'px; margin:0 auto; line-height:0.1px; font-size:1px;">';
        if (D("<canvas>").get(0).getContext) {
            e.elements.tip += '<canvas height="' + e.options.style.tip.size.height + '" width="' + e.options.style.tip.size.width + '"></canvas>';
        } else {
            if (D.browser.msie) {
                d = e.options.style.tip.size.width + "," + e.options.style.tip.size.height;
                b = "m" + a[0][0] + "," + a[0][1];
                b += " l" + a[1][0] + "," + a[1][1];
                b += " " + a[2][0] + "," + a[2][1];
                b += " xe";
                e.elements.tip += '<v:shape fillcolor="' + f + '" stroked="false" filled="true" path="' + b + '" coordsize="' + d + '" style="width:' + e.options.style.tip.size.width + "px; height:" + e.options.style.tip.size.height + "px; line-height:0.1px; display:inline-block; behavior:url(#default#VML); vertical-align:" + ((c.search(/top/) !== -1) ? "bottom" : "top") + '"></v:shape>';
                e.elements.tip += '<v:image style="behavior:url(#default#VML);"></v:image>';
                e.elements.contentWrapper.css("position", "relative");
            }
        }
        e.elements.tooltip.prepend(e.elements.tip + "</div>");
        e.elements.tip = e.elements.tooltip.find("." + e.options.style.classes.tip).eq(0);
        if (D("<canvas>").get(0).getContext) {
            B.call(e, e.elements.tip.find("canvas:first"), a, f);
        }
        if (c.search(/top/) !== -1 && D.browser.msie && parseInt(D.browser.version.charAt(0)) === 6) {
            e.elements.tip.css({marginTop: -4});
        }
        w.call(e, c);
    }
    function B(c, a, d) {
        var b = c.get(0).getContext("2d");
        b.fillStyle = d;
        b.beginPath();
        b.moveTo(a[0][0], a[0][1]);
        b.lineTo(a[1][0], a[1][1]);
        b.lineTo(a[2][0], a[2][1]);
        b.fill();
    }
    function w(d) {
        var e, b, f, a, c;
        e = this;
        if (e.options.style.tip.corner === false || !e.elements.tip) {
            return;
        }
        if (!d) {
            d = e.elements.tip.attr("rel");
        }
        b = positionAdjust = (D.browser.msie) ? 1 : 0;
        e.elements.tip.css(d.match(/left|right|top|bottom/)[0], 0);
        if (d.search(/top|bottom/) !== -1) {
            if (D.browser.msie) {
                if (parseInt(D.browser.version.charAt(0)) === 6) {
                    positionAdjust = (d.search(/top/) !== -1) ? -3 : 1;
                } else {
                    positionAdjust = (d.search(/top/) !== -1) ? 1 : 2;
                }
            }
            if (d.search(/Middle/) !== -1) {
                e.elements.tip.css({left: "50%", marginLeft: -(e.options.style.tip.size.width / 2)});
            } else {
                if (d.search(/Left/) !== -1) {
                    e.elements.tip.css({left: e.options.style.border.radius - b});
                } else {
                    if (d.search(/Right/) !== -1) {
                        e.elements.tip.css({right: e.options.style.border.radius + b});
                    }
                }
            }
            if (d.search(/top/) !== -1) {
                e.elements.tip.css({top: -positionAdjust});
            } else {
                e.elements.tip.css({bottom: positionAdjust});
            }
        } else {
            if (d.search(/left|right/) !== -1) {
                if (D.browser.msie) {
                    positionAdjust = (parseInt(D.browser.version.charAt(0)) === 6) ? 1 : ((d.search(/left/) !== -1) ? 1 : 2);
                }
                if (d.search(/Middle/) !== -1) {
                    e.elements.tip.css({top: "50%", marginTop: -(e.options.style.tip.size.height / 2)});
                } else {
                    if (d.search(/Top/) !== -1) {
                        e.elements.tip.css({top: e.options.style.border.radius - b});
                    } else {
                        if (d.search(/Bottom/) !== -1) {
                            e.elements.tip.css({bottom: e.options.style.border.radius + b});
                        }
                    }
                }
                if (d.search(/left/) !== -1) {
                    e.elements.tip.css({left: -positionAdjust});
                } else {
                    e.elements.tip.css({right: positionAdjust});
                }
            }
        }
        f = "padding-" + d.match(/left|right|top|bottom/)[0];
        a = e.options.style.tip.size[(f.search(/left|right/) !== -1) ? "width" : "height"];
        e.elements.tooltip.css("padding", 0);
        e.elements.tooltip.css(f, a);
        if (D.browser.msie && parseInt(D.browser.version.charAt(0)) == 6) {
            c = parseInt(e.elements.tip.css("margin-top")) || 0;
            c += parseInt(e.elements.content.css("margin-top")) || 0;
            e.elements.tip.css({marginTop: c});
        }
    }
    function A() {
        var a = this;
        if (a.elements.title !== null) {
            a.elements.title.remove();
        }
        a.elements.title = D('<div class="' + a.options.style.classes.title + '">').css(t(a.options.style.title, true)).css({zoom: (D.browser.msie) ? 1 : 0}).prependTo(a.elements.contentWrapper);
        if (a.options.content.title.text) {
            a.updateTitle.call(a, a.options.content.title.text);
        }
        if (a.options.content.title.button !== false && typeof a.options.content.title.button == "string") {
            a.elements.button = D('<a class="' + a.options.style.classes.button + '" style="float:right; position: relative"></a>').css(t(a.options.style.button, true)).html(a.options.content.title.button).prependTo(a.elements.title).click(function(b) {
                if (!a.status.disabled) {
                    a.hide(b);
                }
            });
        }
    }
    function y() {
        var e, c, d, f;
        e = this;
        c = e.options.show.when.target;
        d = e.options.hide.when.target;
        if (e.options.hide.fixed) {
            d = d.add(e.elements.tooltip);
        }
        if (e.options.hide.when.event == "inactive") {
            f = ["click", "dblclick", "mousedown", "mouseup", "mousemove", "mouseout", "mouseover", "mouseleave", "mouseover"];
            function g(h) {
                if (e.status.disabled === true) {
                    return;
                }
                clearTimeout(e.timers.inactive);
                e.timers.inactive = setTimeout(function() {
                    D(f).each(function() {
                        d.unbind(this + ".qtip-inactive");
                        e.elements.content.unbind(this + ".qtip-inactive");
                    });
                    e.hide(h);
                }, e.options.hide.delay);
            }}
        else {
            if (e.options.hide.fixed === true) {
                e.elements.tooltip.bind("mouseover.qtip", function() {
                    if (e.status.disabled === true) {
                        return;
                    }
                    clearTimeout(e.timers.hide);
                });
            }
        }
        function a(h) {
            if (e.status.disabled === true) {
                return;
            }
            if (e.options.hide.when.event == "inactive") {
                D(f).each(function() {
                    d.bind(this + ".qtip-inactive", g);
                    e.elements.content.bind(this + ".qtip-inactive", g);
                });
                g();
            }
            clearTimeout(e.timers.show);
            clearTimeout(e.timers.hide);
            e.timers.show = setTimeout(function() {
                e.show(h);
            }, e.options.show.delay);
        }
        function b(h) {
            if (e.status.disabled === true) {
                return;
            }
            if (e.options.hide.fixed === true && e.options.hide.when.event.search(/mouse(out|leave)/i) !== -1 && D(h.relatedTarget).parents("div.qtip[qtip]").length > 0) {
                h.stopPropagation();
                h.preventDefault();
                clearTimeout(e.timers.hide);
                return false;
            }
            clearTimeout(e.timers.show);
            clearTimeout(e.timers.hide);
            e.elements.tooltip.stop(true, true);
            e.timers.hide = setTimeout(function() {
                e.hide(h);
            }, e.options.hide.delay);
        }
        if ((e.options.show.when.target.add(e.options.hide.when.target).length === 1 && e.options.show.when.event == e.options.hide.when.event && e.options.hide.when.event !== "inactive") || e.options.hide.when.event == "unfocus") {
            e.cache.toggle = 0;
            c.bind(e.options.show.when.event + ".qtip", function(h) {
                if (e.cache.toggle == 0) {
                    a(h);
                } else {
                    b(h);
                }
            });
        } else {
            c.bind(e.options.show.when.event + ".qtip", a);
            if (e.options.hide.when.event !== "inactive") {
                d.bind(e.options.hide.when.event + ".qtip", b);
            }
        }
        if (e.options.position.type.search(/(fixed|absolute)/) !== -1) {
            e.elements.tooltip.bind("mouseover.qtip", e.focus);
        }
        if (e.options.position.target === "mouse" && e.options.position.type !== "static") {
            c.bind("mousemove.qtip", function(h) {
                e.cache.mouse = {x: h.pageX, y: h.pageY};
                if (e.status.disabled === false && e.options.position.adjust.mouse === true && e.options.position.type !== "static" && e.elements.tooltip.css("display") !== "none") {
                    e.updatePosition(h);
                }
            });
        }
    }
    function v(g, f, a) {
        var b, j, d, c, h, e;
        b = this;
        if (a.corner == "center") {
            return f.position;
        }
        j = D.extend({}, g);
        c = {x: false, y: false};
        h = {left: (j.left < D.fn.qtip.cache.screen.scroll.left), right: (j.left + a.dimensions.width + 2 >= D.fn.qtip.cache.screen.width + D.fn.qtip.cache.screen.scroll.left), top: (j.top < D.fn.qtip.cache.screen.scroll.top), bottom: (j.top + a.dimensions.height + 2 >= D.fn.qtip.cache.screen.height + D.fn.qtip.cache.screen.scroll.top)};
        d = {left: (h.left && (a.corner.search(/right/i) != -1 || (a.corner.search(/right/i) == -1 && !h.right))), right: (h.right && (a.corner.search(/left/i) != -1 || (a.corner.search(/left/i) == -1 && !h.left))), top: (h.top && a.corner.search(/top/i) == -1), bottom: (h.bottom && a.corner.search(/bottom/i) == -1)};
        if (d.left) {
            if (b.options.position.target !== "mouse") {
                j.left = f.position.left + f.dimensions.width;
            } else {
                j.left = b.cache.mouse.x;
            }
            c.x = "Left";
        } else {
            if (d.right) {
                if (b.options.position.target !== "mouse") {
                    j.left = f.position.left - a.dimensions.width;
                } else {
                    j.left = b.cache.mouse.x - a.dimensions.width;
                }
                c.x = "Right";
            }
        }
        if (d.top) {
            if (b.options.position.target !== "mouse") {
                j.top = f.position.top + f.dimensions.height;
            } else {
                j.top = b.cache.mouse.y;
            }
            c.y = "top";
        } else {
            if (d.bottom) {
                if (b.options.position.target !== "mouse") {
                    j.top = f.position.top - a.dimensions.height;
                } else {
                    j.top = b.cache.mouse.y - a.dimensions.height;
                }
                c.y = "bottom";
            }
        }
        if (j.left < 0) {
            j.left = g.left;
            c.x = false;
        }
        if (j.top < 0) {
            j.top = g.top;
            c.y = false;
        }
        if (b.options.style.tip.corner !== false) {
            j.corner = new String(a.corner);
            if (c.x !== false) {
                j.corner = j.corner.replace(/Left|Right|Middle/, c.x);
            }
            if (c.y !== false) {
                j.corner = j.corner.replace(/top|bottom/, c.y);
            }
            if (j.corner !== b.elements.tip.attr("rel")) {
                E.call(b, j.corner);
            }
        }
        return j;
    }
    function t(b, c) {
        var a, d;
        a = D.extend(true, {}, b);
        for (d in a) {
            if (c === true && d.search(/(tip|classes)/i) !== -1) {
                delete a[d];
            } else {
                if (!c && d.search(/(width|border|tip|title|classes|user)/i) !== -1) {
                    delete a[d];
                }
            }
        }
        return a;
    }
    function G(a) {
        if (typeof a.tip !== "object") {
            a.tip = {corner: a.tip};
        }
        if (typeof a.tip.size !== "object") {
            a.tip.size = {width: a.tip.size, height: a.tip.size};
        }
        if (typeof a.border !== "object") {
            a.border = {width: a.border};
        }
        if (typeof a.width !== "object") {
            a.width = {value: a.width};
        }
        if (typeof a.width.max == "string") {
            a.width.max = parseInt(a.width.max.replace(/([0-9]+)/i, "$1"));
        }
        if (typeof a.width.min == "string") {
            a.width.min = parseInt(a.width.min.replace(/([0-9]+)/i, "$1"));
        }
        if (typeof a.tip.size.x == "number") {
            a.tip.size.width = a.tip.size.x;
            delete a.tip.size.x;
        }
        if (typeof a.tip.size.y == "number") {
            a.tip.size.height = a.tip.size.y;
            delete a.tip.size.y;
        }
        return a;
    }
    function I() {
        var f, e, d, a, c, b;
        f = this;
        d = [true, {}];
        for (e = 0; e < arguments.length; e++) {
            d.push(arguments[e]);
        }
        a = [D.extend.apply(D, d)];
        while (typeof a[0].name == "string") {
            a.unshift(G(D.fn.qtip.styles[a[0].name]));
        }
        a.unshift(true, {classes: {tooltip: "qtip-" + (arguments[0].name || "defaults")}}, D.fn.qtip.styles.defaults);
        c = D.extend.apply(D, a);
        b = (D.browser.msie) ? 1 : 0;
        c.tip.size.width += b;
        c.tip.size.height += b;
        if (c.tip.size.width % 2 > 0) {
            c.tip.size.width += 1;
        }
        if (c.tip.size.height % 2 > 0) {
            c.tip.size.height += 1;
        }
        if (c.tip.corner === true) {
            c.tip.corner = (f.options.position.corner.tooltip === "center") ? false : f.options.position.corner.tooltip;
        }
        return c;
    }
    function H(a, b, c) {
        var d = {bottomRight: [[0, 0], [b, c], [b, 0]], bottomLeft: [[0, 0], [b, 0], [0, c]], topRight: [[0, c], [b, 0], [b, c]], topLeft: [[0, 0], [0, c], [b, c]], topMiddle: [[0, c], [b / 2, 0], [b, c]], bottomMiddle: [[0, 0], [b, 0], [b / 2, c]], rightMiddle: [[0, 0], [b, c / 2], [0, c]], leftMiddle: [[b, 0], [b, c], [0, c / 2]]};
        d.leftTop = d.bottomRight;
        d.rightTop = d.bottomLeft;
        d.leftBottom = d.topRight;
        d.rightBottom = d.topLeft;
        return d[a];
    }
    function C(b) {
        var a;
        if (D("<canvas>").get(0).getContext) {
            a = {topLeft: [b, b], topRight: [0, b], bottomLeft: [b, 0], bottomRight: [0, 0]};
        } else {
            if (D.browser.msie) {
                a = {topLeft: [-90, 90, 0], topRight: [-90, 90, -b], bottomLeft: [90, 270, 0], bottomRight: [90, 270, -b]};
            }
        }
        return a;
    }
    function z() {
        var c, b, a;
        c = this;
        a = c.getDimensions();
        b = '<iframe class="qtip-bgiframe" frameborder="0" tabindex="-1" src="javascript:false" style="display:block; position:absolute; z-index:-1; filter:alpha(opacity=\'0\'); border: 1px solid red; height:' + a.height + "px; width:" + a.width + 'px" />';
        c.elements.bgiframe = c.elements.wrapper.prepend(b).children(".qtip-bgiframe:first");
    }
    D(document).ready(function() {
        D.fn.qtip.cache = {screen: {scroll: {left: D(window).scrollLeft(), top: D(window).scrollTop()}, width: D(window).width(), height: D(window).height()}};
        var a;
        D(window).bind("scroll", function(b) {
            clearTimeout(a);
            a = setTimeout(function() {
                if (b.type === "scroll") {
                    D.fn.qtip.cache.screen.scroll = {left: D(window).scrollLeft(), top: D(window).scrollTop()};
                } else {
                    D.fn.qtip.cache.screen.width = D(window).width();
                    D.fn.qtip.cache.screen.height = D(window).height();
                }
                for (i = 0; i < D.fn.qtip.interfaces.length; i++) {
                    var c = D.fn.qtip.interfaces[i];
                    if (c.status.rendered === true && (c.options.position.type !== "static" || c.options.position.adjust.scroll && b.type === "scroll")) {
                        c.updatePosition(b, true);
                    }
                }
            }, 100);
        });
        D(document).bind("mousedown.qtip", function(b) {
            if (D(b.target).parents("div.qtip").length === 0) {
                D(".qtip[unfocus]").each(function() {
                    var c = D(this).qtip("api");
                    if (D(this).is(":visible") && !c.status.disabled && D(b.target).add(c.elements.target).length > 1) {
                        c.hide(b);
                    }
                });
            }
        });
    });
    D.fn.qtip.interfaces = [];
    D.fn.qtip.log = {error: function() {
            return this;
        }};
    D.fn.qtip.constants = {};
    D.fn.qtip.defaults = {content: {prerender: false, text: false, url: false, data: null, title: {text: false, button: false}}, position: {target: false, corner: {target: "bottomRight", tooltip: "topLeft"}, adjust: {x: 0, y: 0, mouse: true, screen: false, scroll: true}, type: "absolute", container: false}, show: {when: {target: false, event: "mouseover"}, effect: {type: "fade", length: 100}, delay: 140, solo: false, ready: false}, hide: {when: {target: false, event: "mouseout"}, effect: {type: "fade", length: 100}, delay: 0, fixed: false}, api: {beforeRender: function() {
            }, onRender: function() {
            }, beforePositionUpdate: function() {
            }, onPositionUpdate: function() {
            }, beforeShow: function() {
            }, onShow: function() {
            }, beforeHide: function() {
            }, onHide: function() {
            }, beforeContentUpdate: function() {
            }, onContentUpdate: function() {
            }, beforeContentLoad: function() {
            }, onContentLoad: function() {
            }, beforeTitleUpdate: function() {
            }, onTitleUpdate: function() {
            }, beforeDestroy: function() {
            }, onDestroy: function() {
            }, beforeFocus: function() {
            }, onFocus: function() {
            }}};
    D.fn.qtip.styles = {defaults: {background: "white", color: "#111", overflow: "hidden", textAlign: "left", width: {min: 0, max: 250}, padding: "5px 9px", border: {width: 1, radius: 0, color: "#d3d3d3"}, tip: {corner: false, color: false, size: {width: 13, height: 13}, opacity: 1}, title: {background: "#e1e1e1", fontWeight: "bold", padding: "7px 12px"}, button: {cursor: "pointer"}, classes: {target: "", tip: "qtip-tip", title: "qtip-title", button: "qtip-button", content: "qtip-content", active: "qtip-active"}}, cream: {border: {width: 3, radius: 0, color: "#F9E98E"}, title: {background: "#F0DE7D", color: "#A27D35"}, background: "#FBF7AA", color: "#A27D35", classes: {tooltip: "qtip-cream"}}, light: {border: {width: 3, radius: 0, color: "#E2E2E2"}, title: {background: "#f1f1f1", color: "#454545"}, background: "white", color: "#454545", classes: {tooltip: "qtip-light"}}, dark: {border: {width: 3, radius: 0, color: "#303030"}, title: {background: "#404040", color: "#f3f3f3"}, background: "#505050", color: "#f3f3f3", classes: {tooltip: "qtip-dark"}}, red: {border: {width: 3, radius: 0, color: "#CE6F6F"}, title: {background: "#f28279", color: "#9C2F2F"}, background: "#F79992", color: "#9C2F2F", classes: {tooltip: "qtip-red"}}, green: {border: {width: 3, radius: 0, color: "#A9DB66"}, title: {background: "#b9db8c", color: "#58792E"}, background: "#CDE6AC", color: "#58792E", classes: {tooltip: "qtip-green"}}, blue: {border: {width: 3, radius: 0, color: "#ADD9ED"}, title: {background: "#D0E9F5", color: "#5E99BD"}, background: "#E5F6FE", color: "#4D9FBF", classes: {tooltip: "qtip-blue"}}};
})(joms.jQuery);
(function(a) {
    a.fn.stretchToFit = function(c) {
        (function b(d) {
            d.css("width", "100%");
            d.css("width", d.width() - parseInt(d.css("borderLeftWidth")) - parseInt(d.css("borderRightWidth")) - parseInt(d.css("padding-left")) - parseInt(d.css("padding-right")));
        })(this);
        return this;
    };
})(joms.jQuery);
(function(a) {
    a.fn.defaultValue = function(c, b) {
        var f = this;
        function e() {
            if (f.val() == c) {
                f.val("");
            }
            f.removeClass(b);
        }
        function d() {
            var j = f.data("defaultText");
            var g = f.data("defaultClass");
            var h = f.val().length < 1 || f.val() == j || f.hasClass(g);
            if (h) {
                f.val(c);
            }
            if (b != g) {
                f.removeClass("_defaultClass");
            }
            f.toggleClass(b, h);
        }
        f.focus(e).blur(d);
        d();
        f.data("defaultText", c);
        f.data("defaultClass", b);
        return f;
    };
})(joms.jQuery);
(function(a) {
    a.fn.serializeJSON = function() {
        var b = {};
        a.each(this.serializeArray(), function() {
            b[this.name] = this.value;
        });
        return b;
    };
})(joms.jQuery);
(function(a) {
    a.fn.tipsy = function(b) {
        b = a.extend({}, a.fn.tipsy.defaults, b);
        return this.each(function() {
            var c = a.fn.tipsy.elementOptions(this, b);
            a(this).hover(function() {
                a.data(this, "cancel.tipsy", true);
                var d = a.data(this, "active.tipsy");
                if (!d) {
                    d = a('<div class="tipsy"><div class="tipsy-inner"/></div>');
                    d.css({position: "absolute", zIndex: 100000});
                    a.data(this, "active.tipsy", d);
                }
                if (a(this).attr("title") || typeof(a(this).attr("original-title")) != "string") {
                    a(this).attr("original-title", a(this).attr("title") || "").removeAttr("title");
                }
                var f;
                if (typeof c.title == "string") {
                    f = a(this).attr(c.title == "title" ? "original-title" : c.title);
                } else {
                    if (typeof c.title == "function") {
                        f = c.title.call(this);
                    }
                }
                d.find(".tipsy-inner")[c.html ? "html" : "text"](f || c.fallback);
                var j = a.extend({}, a(this).offset(), {width: this.offsetWidth, height: this.offsetHeight});
                d.get(0).className = "tipsy";
                d.remove().css({top: 0, left: 0, visibility: "hidden", display: "block"}).appendTo(document.body);
                var e = d[0].offsetWidth, h = d[0].offsetHeight;
                var g = (typeof c.gravity == "function") ? c.gravity.call(this) : c.gravity;
                switch (g.charAt(0)) {
                    case"n":
                        d.css({top: j.top + j.height, left: j.left + j.width / 2 - e / 2}).addClass("tipsy-north");
                        break;
                    case"s":
                        d.css({top: j.top - h, left: j.left + j.width / 2 - e / 2}).addClass("tipsy-south");
                        break;
                    case"e":
                        d.css({top: j.top + j.height / 2 - h / 2, left: j.left - e}).addClass("tipsy-east");
                        break;
                    case"w":
                        d.css({top: j.top + j.height / 2 - h / 2, left: j.left + j.width}).addClass("tipsy-west");
                        break;
                }
                if (c.fade) {
                    d.css({opacity: 0, display: "block", visibility: "visible"}).animate({opacity: 0.8});
                } else {
                    d.css({visibility: "visible"});
                }
            }, function() {
                a.data(this, "cancel.tipsy", false);
                var d = this;
                setTimeout(function() {
                    if (a.data(this, "cancel.tipsy")) {
                        return;
                    }
                    var e = a.data(d, "active.tipsy");
                    if (c.fade) {
                        e.stop().fadeOut(function() {
                            a(this).remove();
                        });
                    } else {
                        e.remove();
                    }
                }, 100);
            });
        });
    };
    a.fn.tipsy.elementOptions = function(c, b) {
        return a.metadata ? a.extend({}, b, a(c).metadata()) : b;
    };
    a.fn.tipsy.defaults = {fade: false, fallback: "", gravity: "n", html: false, title: "title"};
    a.fn.tipsy.autoNS = function() {
        return a(this).offset().top > (a(document).scrollTop() + a(window).height() / 2) ? "s" : "n";
    };
    a.fn.tipsy.autoWE = function() {
        return a(this).offset().left > (a(document).scrollLeft() + a(window).width() / 2) ? "e" : "w";
    };
})(joms.jQuery);
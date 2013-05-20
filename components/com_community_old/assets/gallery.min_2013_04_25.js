joms.extend({gallery: {loaderTimeout: null, inputFocused: false, bindFocus: function() {
            joms.jQuery("textarea, [type=text]").each(function() {
                joms.jQuery(this).focus(function() {
                    joms.gallery.inputFocused = true;
                }).blur(function() {
                    joms.gallery.inputFocused = false;
                });
            });
        }, bindKeys: function() {
            joms.jQuery(document.documentElement).keyup(function(a) {
                if (!joms.gallery.inputFocused) {
                    if (a.keyCode == 37) {
                        joms.gallery.displayPhoto(joms.gallery.prevPhoto());
                        joms.photos.photoSlider.switchPhoto();
                    } else {
                        if (a.keyCode == 39) {
                            joms.gallery.displayPhoto(joms.gallery.nextPhoto());
                            joms.photos.photoSlider.switchPhoto();
                        }
                    }
                }
            });
        }, showDeletePhoto: function(a) {
            if (joms.jQuery(a).children(".photo-action")) {
                if (joms.jQuery(a).children(".photo-action").css("display") == "none") {
                    joms.jQuery(a).children(".photo-action").show();
                } else {
                    joms.jQuery(a).children(".photo-action").hide();
                }
            }
        }, confirmRemovePhoto: function(e) {
            var c = "";
            var b = 0;
            if (e == null) {
                var a = joms.gallery.currentPhoto();
                e = a.id;
                b = 1;
            }
            var d = "jax.call('community', 'photos,ajaxConfirmRemovePhoto', '" + e + "' , '" + c + "','" + b + "');";
            cWindowShow(d, "", 450, 100);
        }, removePhoto: function(f, d, c) {
            if (c == "1") {
                var e = jsPlaylist.photos;
                var b = joms.gallery.currentPhoto();
                e.splice(joms.gallery.getPlaylistIndex(b.id), 1);
                var a = (jsPlaylist.photos.length < 1) ? 1 : 0;
                if (!a) {
                    d = "joms.gallery.displayPhoto(joms.gallery.nextPhoto());cWindowHide();";
                }
            }
            joms.ajax.call("photos,ajaxRemovePhoto", [f, d]);
        }, getPlaylistIndex: function(b) {
            if (b == undefined) {
                return 0;
            }
            var a;
            joms.jQuery.each(jsPlaylist.photos, function(c) {
                if (this.id == b) {
                    a = c;
                }
            });
            return a;
        }, nextPhoto: function(b) {
            var a = 0;
            if (b != undefined) {
                a = joms.gallery.getPlaylistIndex(b.id);
            } else {
                a = jsPlaylist.currentPlaylistIndex + 1;
                if (a >= jsPlaylist.photos.length) {
                    a = 0;
                }
            }
            return jsPlaylist.photos[a];
        }, prevPhoto: function(b) {
            var a = 0;
            if (b != undefined) {
                a = joms.gallery.getPlaylistIndex(b.id);
            } else {
                a = jsPlaylist.currentPlaylistIndex - 1;
                if (a < 0) {
                    a = jsPlaylist.photos.length - 1;
                }
            }
            return jsPlaylist.photos[a];
        }, currentPhoto: function(b) {
            var a = jsPlaylist.currentPlaylistIndex;
            if (b != undefined) {
                a = joms.gallery.getPlaylistIndex(b.id);
                joms.gallery.urlPhotoId(b.id);
            }
            if (a == undefined) {
                a = joms.gallery.getPlaylistIndex(joms.gallery.urlPhotoId());
            }
            jsPlaylist.currentPlaylistIndex = a;
            return jsPlaylist.photos[a];
        }, urlPhotoId: function(a) {
            if (a == undefined) {
                return jsPlaylist.customSetting.defaultId;
            } else {
                if (window.history.pushState && jsPlaylist.customSetting.defaultId != a) {
                    window.history.pushState("string", "title", jsPlaylist.photos[joms.gallery.getPlaylistIndex(a)].sefURL);
                }
            }
        }, init: function() {
            if (typeof(joms.jQuery.isArray) == "undefined") {
                joms.jQuery.extend({isArray: function(a) {
                        return a.constructor == Array;
                    }});
            }
            joms.gallery.displayViewport();
            joms.gallery.displayPhoto(joms.gallery.currentPhoto());
            joms.gallery.editablePhotoCaption();
        }, displayViewport: function() {
            var f = joms.jQuery("#cGallery .photoViewport");
            f.unbind();
            f.hover(function() {
                joms.jQuery("#cGallery .photoAction").fadeIn("fast");
            }, function() {
                joms.jQuery("#cGallery .photoAction").fadeOut("fast");
            });
            var c = joms.jQuery("#cGallery .photoDisplay");
            c.css("height", Math.floor(c.width() / 16 * 12));
            var e = joms.jQuery("#cGallery .photoLoad");
            e.css({top: Math.floor((c.height() / 2) - (e.height() / 2)), left: Math.floor((c.width() / 2) - (e.width() / 2))});
            var d = joms.jQuery("#cGallery .photoActions");
            d.css({width: c.width(), height: 0, top: 0, left: 0});
            var g = joms.jQuery("#cGallery .photoAction._next");
            g.css({top: Math.floor((c.height() / 2) - (g.height() / 2)), right: 0});
            var a = joms.jQuery("#cGallery .photoAction._prev");
            a.css({top: Math.floor((c.height() / 2) - (a.height() / 2)), left: 0});
            var b = joms.jQuery("#cGallery .photoTags");
            b.css({width: c.width(), height: 0, top: 0, left: 0});
        }, displayPhoto: function(a) {
            var b = joms.jQuery("#cGallery .photoLoad");
            clearTimeout(joms.gallery.loaderTimeout);
            joms.gallery.loaderTimeout = setTimeout("joms.jQuery('#cGallery .photoLoad').show()", 300);
            joms.gallery.currentPhoto(a);
            joms.jQuery("#cGallery .photoAction._next img").attr("src", joms.gallery.nextPhoto().thumbnail);
            joms.jQuery("#cGallery .photoAction._prev img").attr("src", joms.gallery.prevPhoto().thumbnail);
            joms.gallery.displayPhotoCaption(a.caption);
            joms.gallery.createPhotoImage(a, function(c) {
                var f = joms.jQuery("#cGallery .photoDisplay");
                var e = joms.jQuery("#cGallery .photoImage");
                f.empty();
                c.appendTo(f);
                c.css({top: "3000px", left: "4000px", visibility: "visible", position: "absolute"});
                var d = c[0].height;
                var h = c[0].width;
                if (c[0].height > f.height()) {
                    h = h * (f.height() / d);
                    d = f.height();
                }
                if (h > f.width()) {
                    d = d * (f.width() / h);
                    h = f.width();
                }
                var g = {width: h, height: d, top: Math.floor((f.height() - d) / 2), left: Math.floor((f.width() - h) / 2), visibility: "visible", display: "none"};
                c.data("properties", g).css(g);
                joms.gallery.switchPhoto(a.id);
                joms.gallery.displayPhotoTags(a.tags);
            });
            joms.gallery.prefetchPhoto([joms.gallery.prevPhoto(), joms.gallery.nextPhoto()]);
        }, createPhotoImage: function(b, c) {
            if (typeof(c) != "function") {
                c = function() {
                };
            }
            var a = joms.jQuery(new Image());
            a.load(function() {
                c.apply(this, [a]);
            }).attr({id: "photo-" + b.id, "class": "photoImage", alt: b.caption, title: "", src: joms.gallery.getPhotoUrl(b)});
            return a;
        }, prefetchPhoto: function(a) {
            if (!joms.jQuery.isArray(a)) {
                a = [a];
            }
            joms.jQuery.each(a, function(c, b) {
                if (!b.loaded) {
                    joms.gallery.createPhotoImage(b, function() {
                        b.loaded = true;
                    });
                }
            });
        }, getPhotoUrl: function(b) {
            var a = joms.jQuery("#cGallery .photoDisplay");
            var c = "";
            if (b.url.indexOf("option=com_community") == -1) {
                c = b.url;
            } else {
                c = b.url + "&" + joms.jQuery.param({maxW: a.width(), maxH: a.height()});
            }
            return c;
        }, displayPhotoHits: function(b) {
            var a = joms.jQuery("#cGallery .photoHitsText");
            a.text((b != "") ? b : jsPlaylist.language.COM_COMMUNITY_PHOTOS_NO_CAPTIONS_YET);
        }, addPhotoHits: function() {
            jax.call("community", "photos,ajaxAddPhotoHits", joms.gallery.currentPhoto().id);
        }, displayPhotoCaption: function(a) {
            var b = joms.jQuery("#cGallery .photoCaptionText");
            a = a.replace(/\<br ?\/?\>/g, "\n");
            b.val((a != "") ? a : jsPlaylist.language.COM_COMMUNITY_PHOTOS_NO_CAPTIONS_YET).trigger("autogrow");
        }, editablePhotoCaption: function() {
            var a = joms.jQuery("#cGallery .photoCaptionText");
            if (!a.hasClass("editable")) {
                return;
            }
            a.stretchToFit().autogrow({lineHeight: 0, minHeight: 0}).focus(function() {
                a.addClass("editing").stretchToFit().data("oldPhotoCaption", a.val());
            }).blur(function() {
                a.removeClass("editing").stretchToFit();
                var c = joms.jQuery.trim(a.data("oldPhotoCaption"));
                var b = joms.jQuery.trim(a.val());
                if (b == "" || b == c) {
                    a.val(c).trigger("autogrow");
                    return;
                }
                jax.call("community", "photos,ajaxSaveCaption", joms.gallery.currentPhoto().id, b);
            });
        }, editPhotoCaption: function() {
            var b = joms.jQuery("#cGallery .photoCaption");
            b.addClass("editMode");
            var c = joms.jQuery("#cGallery .photoCaptionText");
            var a = joms.jQuery("#cGallery .photoCaptionInput");
            a.val(joms.jQuery.trim(c.text()));
        }, cancelPhotoCaption: function() {
            var b = joms.jQuery("#cGallery .photoCaption");
            b.removeClass("editMode");
            var a = joms.jQuery("#cGallery .photoCaptionInput");
            a.val("");
        }, savePhotoCaption: function() {
            var d = joms.jQuery("#cGallery .photoCaptionText");
            var b = joms.jQuery("#cGallery .photoCaptionInput");
            var c = joms.jQuery.trim(d.text());
            var a = joms.jQuery.trim(b.val());
            if (a == "" || a == c) {
                joms.gallery.cancelPhotoCaption();
            } else {
                jax.call("community", "photos,ajaxSaveCaption", joms.gallery.currentPhoto().id, a);
            }
        }, updatePhotoCaption: function(a, b) {
            var c = joms.jQuery("#cGallery .photoCaptionText");
            c.text(b);
            jsPlaylist.photos[joms.gallery.getPlaylistIndex(a)].caption = b;
            joms.gallery.cancelPhotoCaption();
        }, switchPhoto: function(a) {
            joms.jQuery("#cGallery .photoDisplay img").show();
            joms.ajax.call("photos,ajaxSwitchPhotoTrigger", [a], {success: function(b) {
                    joms.jQuery("#like-container").html(b);
                    clearTimeout(joms.gallery.loaderTimeout);
                    joms.jQuery("#cGallery .photoLoad").hide();
                }});
        }, displayPhotoWalls: function(a) {
            joms.gallery.switchPhoto(a);
        }, setPhotoAsDefault: function() {
            var a = "jax.call('community', 'photos,ajaxConfirmDefaultPhoto', jsPlaylist.album, joms.gallery.currentPhoto().id);";
            cWindowShow(a, "", 450, 100);
        }, downloadPhoto: function() {
            window.open(jsPlaylist.photos[jsPlaylist.currentPlaylistIndex].originalUrl);
        }, updatePhotoReport: function(a) {
            joms.jQuery(".page-action#report-this").remove();
            joms.jQuery(".page-actions").prepend(a);
        }, updatePhotoBookmarks: function(a) {
            joms.jQuery(".page-action#social-bookmarks").remove();
            joms.jQuery(".page-actions").append(a);
        }, newPhotoTag: function(b) {
            var a = {id: null, userId: null, photoId: null, displayName: null, profileUrl: null, top: null, left: null, width: null, height: null, displayTop: null, displayLeft: null, displayWidth: null, displayHeight: null, "canRemove:": null};
            joms.jQuery.extend(a, b);
            return a;
        }, createPhotoTag: function(tag) {
            var photo = joms.jQuery("#cGallery .photoImage");
            var photoTags = joms.jQuery("#cGallery .photoTags");
            if (typeof(tag) == "string") {
                tag = eval("(" + tag + ")");
            }
            var singleTag = false;
            if (!joms.jQuery.isArray(tag)) {
                tag = [tag];
                singleTag = true;
            }
            var newPhotoTags = new Array();
            joms.jQuery.each(tag, function(i, tag) {
                var photoTag = joms.gallery.drawPhotoTag(tag, photo);
                photoTag.data("tag", tag).attr("id", "photoTag-" + tag.id).hover(function() {
                    joms.gallery.showPhotoTag(tag.id, "Label");
                }, function() {
                    joms.gallery.hidePhotoTag(tag.id);
                }).appendTo(photoTags);
                var photoTagLabel = joms.jQuery('<div class="photoTagLabel">');
                photoTagLabel.html(tag.displayName);
                photoTagLabel.wrapInner("<span></span>").appendTo(photoTag);
                newPhotoTags.push(photoTag);
            });
            if (singleTag) {
                return newPhotoTags[0];
            } else {
                return newPhotoTags;
            }
        }, drawPhotoTag: function(b, c) {
            var a = (b.displayWidth != b.width * c.width());
            if (a) {
                b.displayWidth = b.width * c.width();
                b.displayHeight = b.height * c.height();
                b.displayTop = (b.top * c.height()) - (b.displayHeight / 2);
                if (b.displayTop < 0) {
                    b.displayTop = 0;
                }
                maxTop = c.height() - b.displayHeight;
                if (b.displayTop > maxTop) {
                    b.displayTop = maxTop;
                }
                b.displayLeft = (b.left * c.width()) - (b.displayWidth / 2);
                if (b.displayLeft < 0) {
                    b.displayLeft = 0;
                }
                maxLeft = c.width() - b.displayWidth;
                if (b.displayLeft > maxLeft) {
                    b.displayLeft = maxLeft;
                }
            }
            var d = joms.jQuery('<div class="photoTag">');
            d.css({width: b.displayWidth, height: b.displayHeight, top: b.displayTop, left: b.displayLeft});
            var e = joms.jQuery('<div class="photoTagBorder">');
            e.css({width: b.displayWidth - 4, height: b.displayHeight - 4, border: "2px solid #222"}).appendTo(d);
            if (b.id != null) {
                joms.gallery.updatePlaylistTag(b);
            }
            return d;
        }, updatePlaylistTag: function(a) {
            var c;
            var b = jsPlaylist.photos[joms.gallery.getPlaylistIndex(a.photoId)].tags;
            joms.jQuery.each(b, function() {
                if (this.id == a.id) {
                    c = this;
                }
            });
            if (c == undefined) {
                c = b[b.push(joms.gallery.newPhotoTag()) - 1];
            }
            joms.jQuery.extend(c, a);
        }, displayPhotoTags: function(c) {
            joms.gallery.clearPhotoTag();
            joms.gallery.clearPhotoTextTag();
            var b = joms.jQuery("#cGallery .photoImage");
            var a = joms.jQuery("#cGallery .photoTags");
            a.css({width: b.width(), height: b.height(), top: b.data("properties").top, left: b.data("properties").left});
            joms.gallery.createPhotoTag(c);
            joms.gallery.createPhotoTextTag(c);
        }, addPhotoTag: function(c) {
            var b = joms.jQuery("#cGallery .photoTags");
            var a = b.data("newPhotoTag");
            if (c > 0) {
                jax.call("community", "photos,ajaxAddPhotoTag", a.photoId, c, a.top, a.left, a.width, a.height);
            }
            joms.gallery.cancelNewPhotoTag();
        }, removePhotoTag: function(a) {
            jax.call("community", "photos,ajaxRemovePhotoTag", a.photoId, a.userId);
            joms.gallery.clearPhotoTag(a);
            joms.gallery.clearPhotoTextTag(a);
            var b = jsPlaylist.photos[joms.gallery.getPlaylistIndex(a.photoId)].tags;
            joms.jQuery.each(b, function(c) {
                if (this.id == a.id) {
                    b.splice(c, 1);
                }
            });
        }, clearPhotoTag: function(a) {
            if (a == undefined) {
                joms.jQuery("#cGallery .photoTag").remove();
            } else {
                joms.jQuery("#photoTag-" + a.id).remove();
            }
        }, showPhotoTag: function(b, a) {
            joms.jQuery("#photoTag-" + b).addClass("show" + a);
        }, hidePhotoTag: function(a) {
            joms.jQuery("#photoTag-" + a).removeClass("show showLabel showForce");
        }, createPhotoTextTag: function(tags) {
            var photoTextTags = joms.jQuery(".photoTextTags");
            if (typeof(tags) == "string") {
                tags = eval("(" + tags + ")");
            }
            var singleTag = false;
            if (!joms.jQuery.isArray(tags)) {
                tags = [tags];
                singleTag = true;
            }
            var newPhotoTextTags = new Array();
            var a = 1;
            if (joms.jQuery(".photoTextTags").children().length > 0) {
                joms.jQuery(".photoTextTags .photoTextTag .photoTextTagActions").append(", ");
            }
            joms.jQuery.each(tags, function(i, tag) {
                if (tag.id == undefined) {
                    return;
                }
                var photoTextTag = joms.jQuery('<span class="photoTextTag"></span>');
                photoTextTag.data("tag", tag).attr("id", "photoTextTag-" + tag.id).hover(function() {
                    joms.gallery.showPhotoTag(tag.id, "Force");
                }, function() {
                    joms.gallery.hidePhotoTag(tag.id);
                }).appendTo(photoTextTags);
                var photoTextTagLink = joms.jQuery("<a>");
                photoTextTagLink.attr("href", tag.profileUrl).html(tag.displayName).prependTo(photoTextTag);
                if (tag.canRemove) {
                    var photoTextTagActions = joms.jQuery('<span class="photoTextTagActions"></span>');
                    photoTextTagActions.appendTo(photoTextTag);
                    var photoTextTagAction_remove = joms.jQuery('<a class="photoTextTagAction" href="javascript: void(0);"></a>');
                    photoTextTagAction_remove.addClass("_remove").html(jsPlaylist.language.COM_COMMUNITY_REMOVE).click(function() {
                        joms.gallery.removePhotoTag(tag);
                    }).appendTo(photoTextTagActions);
                    photoTextTagActions.before(" ").prepend("(").append(")");
                }
                if (a < tags.length && photoTextTagActions) {
                    photoTextTagActions.append(", ");
                }
                a++;
                newPhotoTextTags.push(photoTextTag);
            });
            joms.gallery.commifyTextTags();
            return newPhotoTextTags;
        }, commifyTextTags: function() {
            joms.jQuery(".photoTextTags .comma").remove();
            photoTextTag = joms.jQuery("#cGallery .photoTextTag");
            photoTextTag.each(function(b) {
                if (b == 0) {
                    return;
                }
                var a = joms.jQuery('<span class="comma"></span>');
                a.html(", ").prependTo(this);
            });
        }, clearPhotoTextTag: function(a) {
            if (a == undefined) {
                joms.jQuery("#cGallery .photoTextTag").remove();
            } else {
                joms.jQuery("#photoTextTag-" + a.id).remove();
                joms.gallery.commifyTextTags();
            }
        }, startTagMode: function() {
            joms.jQuery("#cGallery .photoTagInstructions").slideDown("fast");
            joms.jQuery("#startTagMode").hide();
            var f = joms.jQuery("#cGallery .photoViewport");
            f.addClass("tagMode");
            var b = joms.jQuery("#cGallery .photoImage");
            var c = b;
            var e = joms.jQuery("#cGallery .photoTags");
            var h = joms.jQuery("#cGallery .photoTagActions");
            var j = joms.jQuery("#cGallery .photoTagAction._select");
            var d = (b.width() / 2) - (jsPlaylist.config.defaultTagWidth / 2);
            var i = (b.height() / 2) - (jsPlaylist.config.defaultTagHeight / 2);
            var a = d + jsPlaylist.config.defaultTagWidth;
            var g = i + jsPlaylist.config.defaultTagHeight;
            var k = function(l) {
                e.data("newPhotoTag", joms.gallery.newPhotoTag({photoId: joms.gallery.currentPhoto().id, top: (l.y1 + (l.height / 2)) / b.height(), left: (l.x1 + (l.width / 2)) / b.width(), width: l.width / b.width(), height: l.height / b.height()}));
                h.css({top: l.y1, left: l.x1, width: l.width, height: l.height}).show();
                j.css({bottom: j.outerHeight(true) * -1, left: (l.width - j.outerWidth(true)) / 2});
                joms.jQuery("div.autocomplete-w1").parent().css("top", "-1000px").show();
            };
            e.imgAreaSelect({parent: e, x1: d, y1: i, x2: a, y2: g, minWidth: 50, minHeight: 50, zIndex: 6630, show: true, handles: true, movable: true, persistent: true, onInit: function(l, m) {
                    k(m);
                }, onSelectStart: function(l, m) {
                    h.hide();
                }, onSelectChange: function(l, n) {
                    h.hide();
                    var m = e.imgAreaSelect({instance: true});
                    if ((n.x1 - 75) < 0) {
                        n.x1 = 0;
                    }
                    if ((n.y1 - 75) < 0) {
                        n.y1 = 0;
                    }
                    if ((n.x1 + 150) > e.width()) {
                        n.x1 = e.width() - 150;
                    }
                    if ((n.y1 + 150) > e.height()) {
                        n.y1 = e.height() - 150;
                    }
                    m.setSelection(n.x1, n.y1, n.x1 + 150, n.y1 + 150, true);
                    m.update();
                }, onSelectEnd: function(l, m) {
                    k(m);
                    e.css({cursor: "pointer"}).imgAreaSelect({persistent: true});
                }});
        }, stopTagMode: function() {
            var c = joms.jQuery("#cGallery .photoViewport");
            c.removeClass("tagMode");
            var b = joms.jQuery("#cGallery .photoTags");
            b.css({cursor: "default"}).imgAreaSelect({remove: true});
            var a = joms.jQuery("#cGallery .photoTagActions");
            a.hide();
            joms.jQuery("#cGallery .photoTagInstructions").hide();
            joms.jQuery("#startTagMode").show();
            cWindowHide();
        }, selectNewPhotoTagFriend: function() {
            var a = joms.jQuery("#cGallery .photoTagFriend");
            cWindowShow(function() {
                joms.gallery.showPhotoTagFriends();
            }, jsPlaylist.language.COM_COMMUNITY_SELECT_PERSON, 300, 300);
            cWindowActions('<button class="button" onclick="joms.gallery.confirmPhotoTagFriend();">' + jsPlaylist.language.COM_COMMUNITY_CONFIRM + "</button>");
        }, confirmPhotoTagFriend: function() {
            joms.jQuery("#cWindow .js-system-message").hide();
            var a = joms.jQuery("#cWindow .invitation-item-invited input:checked");
            if (a.length > 0) {
                joms.gallery.addPhotoTag(a.val());
            } else {
                joms.jQuery("#cWindow .js-system-message").show();
                joms.jQuery("#cWindow .js-system-message").fadeOut(5000);
            }
        }, showPhotoTagFriends: function() {
            var b = joms.jQuery("#cGallery .photoTags");
            var a = b.data("newPhotoTag");
            var c = a.photoId;
            joms.jQuery("#cWindowContent").empty();
            joms.jQuery("#cGallery .photoTagSelectFriend").clone().appendTo("#cWindowContent");
            jax.loadingFunction();
            joms.friends.showForm("", "photos", c, 1, "joms.gallery.confirmPhotoTagFriend();");
            jax.doneLoadingFunction();
            setTimeout("joms.jQuery('#cWindowContent .photoTagFriendFilter').focus()", 300);
        }, filterPhotoTagFriend: function(d) {
            var b = joms.jQuery("#cWindow .photoTagFriend");
            var e = joms.jQuery("#cWindow .photoTagFriendFilter");
            var f = joms.jQuery.trim(e.val());
            var c = joms.jQuery("#cGallery .photoTags");
            var a = c.data("newPhotoTag");
            var g = a.photoId;
            jax.loadingFunction();
            joms.friends.loadFriend(f, "photos", g, "0", "9");
            jax.doneLoadingFunction();
        }, cancelNewPhotoTag: function() {
            var c = joms.jQuery("#cGallery .photoTags");
            c.css({cursor: "crosshair"}).imgAreaSelect({hide: true, persistent: false});
            var b = joms.jQuery("#cGallery .photoTagActions");
            b.hide();
            var c = joms.jQuery("#cGallery .photoTags");
            var a = c.data("newPhotoTag", {});
            joms.jQuery("div.autocomplete-w1").parent().hide();
        }, displayCreator: function(a) {
            jax.call("community", "photos,ajaxDisplayCreator", a);
        }, setProfilePicture: function() {
            var a = "jax.call('community', 'photos,ajaxLinkToProfile', '" + joms.gallery.currentPhoto().id + "');";
            cWindowShow(a, "", 450, 100);
        }, rotatePhoto: function(b) {
            if (joms.jQuery("#startTagMode").css("display") == "none") {
                return false;
            }
            var a = joms.gallery.currentPhoto().id;
            joms.ajax.call("photos,ajaxRotatePhoto", [a, b], {success: function(c, e, f) {
                    var d = jsPlaylist.photos[joms.gallery.getPlaylistIndex(c)];
                    d.url = e;
                    d.thumbnail = f;
                    joms.gallery.displayPhoto(d);
                }});
        }}});
function getPlaylistIndex(a) {
    joms.gallery.getPlaylistIndex(a);
}
function nextPhoto(a) {
    joms.gallery.nextPhoto(a);
}
function prevPhoto(a) {
    joms.gallery.prevPhoto(a);
}
function currentPhoto(a) {
    joms.gallery.currentPhoto(a);
}
function urlPhotoId(a) {
    joms.gallery.urlPhotoId(a);
}
function initGallery() {
    joms.gallery.init();
}
function displayViewport() {
    joms.gallery.displayViewPort();
}
function displayPhoto(a) {
    joms.gallery.displayPhoto(a);
}
function createPhotoImage(a, b) {
    joms.gallery.createPhotoImage(a, b);
}
function prefetchPhoto(a) {
    joms.gallery.prefetchPhoto(a);
}
function getPhotoUrl(a) {
    joms.gallery.getPhotoUrl(a);
}
function displayPhotoCaption(a) {
    joms.gallery.displayPhotoCaption(a);
}
function editPhotoCaption() {
    joms.gallery.editPhotoCaption();
}
function cancelPhotoCaption() {
    joms.gallery.cancelPhotoCaption();
}
function savePhotoCaption() {
    joms.gallery.savePhotoCaption();
}
function updatePhotoCaption(a, b) {
    joms.gallery.updatePhotoCaption(a, b);
}
function displayPhotoWalls(a) {
    joms.gallery.displayPhotoWalls(a);
}
function setPhotoAsDefault() {
    joms.gallery.setPhotoAsDefault();
}
function removePhoto() {
    joms.gallery.confirmRemovePhoto();
}
function downloadPhoto() {
    joms.gallery.downloadPhoto();
}
function updatePhotoReport(a) {
    joms.gallery.updatePhotoReport(a);
}
function newPhotoTag(a) {
    joms.gallery.newPhotoTag(a);
}
function createPhotoTag(a) {
    joms.gallery.createPhotoTag(a);
}
function drawPhotoTag(a, b) {
    joms.gallery.drawPhotoTag(a, b);
}
function updatePlaylistTag(a) {
    joms.gallery.updatePlaylistTag(a);
}
function displayPhotoTags(a) {
    joms.gallery.displayPhotoTags(a);
}
function addPhotoTag(a) {
    joms.gallery.addPhotoTag(a);
}
function removePhotoTag(a) {
    joms.gallery.removePhotoTag(a);
}
function clearPhotoTag(a) {
    joms.gallery.clearPhotoTag(a);
}
function showPhotoTag(b, a) {
    joms.gallery.showPhotoTag(b, a);
}
function hidePhotoTag(a) {
    joms.gallery.hidePhotoTag(a);
}
function createPhotoTextTag(a) {
    joms.gallery.createPhotoTextTag(a);
}
function commifyTextTags() {
    joms.gallery.commifyTextTags();
}
function clearPhotoTextTag(a) {
    joms.gallery.clearPhotoTextTag(a);
}
function startTagMode() {
    joms.gallery.startTagMode();
}
function stopTagMode() {
    joms.gallery.stopTagMode();
}
function selectNewPhotoTagFriend() {
    joms.gallery.selectNewPhotoTagFriend();
}
function confirmPhotoTagFriend() {
    joms.gallery.confirmPhotoTagFriend();
}
function showPhotoTagFriends() {
    joms.gallery.showPhotoTagFriends();
}
function filterPhotoTagFriend(a) {
    joms.gallery.filterPhotoTagFriend(a);
}
function cancelNewPhotoTag() {
    joms.gallery.cancelNewPhotoTag();
}
function displayCreator(a) {
    joms.gallery.displayCreator(a);
}
function setProfilePicture() {
    joms.gallery.setProfilePicture();
}
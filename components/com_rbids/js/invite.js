/******************************************************
 *               jQuery Code
 ********************************************************/

jQuery(document).ready(function () {

	jQuery.fn.filterOptions = function (inputText, autoSelectSingleMatch) {
		return this.each(function () {
			var select = this;
			var options = [];

			jQuery(select).find('option').each(function () {
				options.push({value:jQuery(this).val(), text:jQuery(this).text()});
			});
			jQuery(select).data('options', options);
			jQuery(inputText).bind('keyup', function () {
				var options = jQuery(select).empty().scrollTop(0).data('options');
				var search = jQuery.trim(jQuery(this).val());
				var regex = new RegExp(search, 'gi');

				jQuery.each(options, function (i) {
					var option = options[i];
					if (option.text.match(regex) !== null) {
						jQuery(select).append(
							jQuery('<option>').text(option.text).val(option.value)
						);
					}
				});
				if (autoSelectSingleMatch === true &&
					jQuery(select).children().length === 1) {
					jQuery(select).children().get(0).selected = true;
				}
			});
		});
	};

//	jQuery('#inviteusers').filterOptions(jQuery('#filterInviteUsers'), true);
});

function setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}

function getCookie(c_name) {
	var i, x, y, ARRcookies = document.cookie.split(";");
	for (i = 0; i < ARRcookies.length; i++) {
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g, "");
		if (x == c_name) {
			return unescape(y);
		}
	}
}

function cookInvites() {
	var inviteusers = document.getElementById('inviteusers');
	var invitegroups = document.getElementById('invitegroups');

	var users = [];
	var groups = [];
	var i;
	// Prepare selected users
	for (i = 0; i < inviteusers.options.length; i++) {
		if (inviteusers.options[i].selected) {
			users[i] = inviteusers.options[i].value;
		}
	}
	// Prepare selected groups
	for (i = 0; i < invitegroups.options.length; i++) {
		if (invitegroups.options[i].selected) {
			groups[i] = invitegroups.options[i].value;
		}
	}
	users.join(',');
	groups.join(',');

	//prepend a 1 as a control value
	setCookie('rbidsCookInvites', '1#' + users + '#' + groups, 1);

	refreshInvitesTooltip();

	window.parent.SqueezeBox.close();
}

function resetCookInvites() {
	setCookie('rbidsCookInvites', '', 0);
	window.parent.SqueezeBox.close();
}

function refreshInvitesTooltip() {
	getCookie('rbidsCookInvites');
}




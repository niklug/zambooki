document.formvalidator = null;
window.addEvent('domready', function () {
	$$('.required').each(function (el) {
		elm = el.getParent();
		children = elm.getChildren();

		var hasalready = false;
		$$(children).each(function (el1) { //for checkboxes and radiobuttons
			if (el1.hasClass('required_span')) hasalready = true;
		});
		if (hasalready) return;
		var span = new Element('span', {style:'color:red;', title:language["bid_required"], class:'required_span'});
		span.appendText('(*)');
		elm.adopt(span);
	});
	document.formvalidator = new AuctionFormValidator();

});

var AuctionFormValidator = new Class({
	initialize:function () {
		// Initialize variables
		this.handlers = Object();
		this.custom = Object();

		this.setHandler('numeric',
			function (value) {
				this.lastError = "";
				regex = /^(\d|-)?(\d|,)*\.?\d*$/;
				val = regex.test(value);
				if (!val) this.lastError = language["bid_err_numeric"];
				return val;
			}
		);
		this.setHandler('email',
			function (value) {
				if (!value) return true;
				regex = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
				val = regex.test(value);
				if (!val) this.lastError = language["bid_err_email"];
				return val;
			}
		);
		this.setHandler('start-date',
			function (value) {
				var currentTime = new Date();
				var month = currentTime.getMonth() + 1;
				var day = currentTime.getDate();
				var year = currentTime.getFullYear();

				var nowDate = year + '-' + month + '-' + day;

				var joomlaformat = bid_date_format;
				joomlaformat = joomlaformat.replace('m', 'M');
				joomlaformat = joomlaformat.replace('Y', 'y');
				//'yyyy-MM-dd'
				if (!isDate(value, joomlaformat)) {
					this.lastError = language["bid_err_startdate"];
					return false;
				}

				if (compareDates(value, joomlaformat, nowDate, 'yyyy-M-d') < 0 || dateGt(value, joomlaformat, nowDate, 'yyyy-M-d') < 0) {
					this.lastError = language["bid_err_startdate"];
					return false;
				}

				return true;
			}
		);
		this.setHandler('end-date',
			function (value) {
				var start_date = $('start_date');

				var joomlaformat = bid_date_format;
				joomlaformat = joomlaformat.replace('m', 'M');
				joomlaformat = joomlaformat.replace('Y', 'y');

				if (!isDate(value, joomlaformat)) {
					this.lastError = language["bid_err_enddate"];
					return false;
				}
				if (start_date) {
					if (compareDates(value, joomlaformat, start_date.value, joomlaformat) < 0) {
						this.lastError = language["bid_err_enddate"];
						return false;
					}

					var d1 = new Date(getDateFromFormat(start_date.value, joomlaformat));
					d1.setMonth(d1.getMonth() + bid_max_availability);
					var d2 = getDateFromFormat(value, joomlaformat);

					if (bid_max_availability > 0 && d1.getTime() < d2) {
						this.lastError = language["bid_err_max_valability"];
						return false;
					}
				}
				return true;
			});


		var form = $('rAuctionForm');
		this.attachToForm(form);
	},

	setHandler:function (name, fn, en) {
		en = (en == '') ? true : en;
		this.handlers[name] = { enabled:en, exec:fn };
	},

	attachToForm:function (form) {
		// Iterate through the form object and attach the validate method to all input fields.
		form.getElements('input,textarea,select,button').each(function (el) {
			el = $(el);
			if ((el.get('tag') == 'input' || el.get('tag') == 'button') && el.getProperty('type') == 'submit') {
				if (el.hasClass('validate')) {
					el.onclick = function () {
						var getIsValid = document.formvalidator.isValid(this.form);
						// Prevent multiple form submissions at time
						/*if (getIsValid) {
						 el.disabled = true;
						 } else {
						 el.removeAttribute("disabled");
						 }*/
						return getIsValid;
					};
				}
			} else {
				el.addEvent('blur', function () {
					return document.formvalidator.validate(this);
				});
			}
		});
	},
	focusOnError:function (obj) {
		var dest; //moved this up to stop redclariations
		//This can changed to a single switch using default:
		dest = obj.getCoordinates().top - 30;
		if (window.getScrollTop() != dest) {
			new Fx.Scroll(window, {
				wait:false,
				duration:1500,
				transition:Fx.Transitions.Quad.easeInOut,
				onComplete:function () {
					if (obj.getProperty('type') != 'hidden') obj.focus();
				}.bind(this)
			}).toElement(obj);
		} else {
			obj.focus();
		}
	},

	validate:function (el) {
		// If the field is required make sure it has a value
		this.lastError = '';

		if ($(el).hasClass('required')) {
			val = $(el).get('value');
			if ($(el).get('tag') == 'input' && $(el).type == 'file')
				val = $(el).value;
			if ($(el).get('tag') == 'input' && $(el).type == 'checkbox') {
				var regExp = /\[\]/;

				if (el.name.search(regExp)) {
					var val = '';
					$$('input').each(function (el1) {
						if (el1.name == el.name && el1.type == 'checkbox' && el1.checked)
							val += ((val) ? ',' : '') + el1.value;
					});

				}

			}

			if (!(val)) {
				this.lastError = language["bid_required"];
				this.handleResponse(false, el);
				return false;
			}
			if (typeof(val) !== 'string' && val.length <= 0) {
				this.lastError = language["bid_required"];
				this.handleResponse(false, el);
				return false;
			}
		}

		// Only validate the field if the validate class is set
		var handler = (el.className && el.className.search(/validate-([a-zA-Z0-9\_\-]+)/) != -1) ? el.className.match(/validate-([a-zA-Z0-9\_\-]+)/)[1] : "";
		if (handler == '') {
			this.handleResponse(true, el);
			return true;
		}

		// Check the additional validation types
		if ((handler) && (handler != 'none') && (this.handlers[handler]) && $(el).get('value')) {
			// Execute the validation handler and return result
			if (this.handlers[handler].exec($(el).get('value')) != true) {
				this.lastError = this.handlers[handler].lastError;
				this.handleResponse(false, el);
				return false;
			}
		}

		// Return validation state
		this.handleResponse(true, el);
		return true;
	},

	isValid:function (form) {
		var valid = true;
		var wasscroll = false;

		// Validate form fields
		for (var i = 0; i < form.elements.length; i++) {
			if (this.validate(form.elements[i]) == false) {
				valid = false;
				if (!wasscroll) {
					wasscroll = true;
					this.focusOnError(form.elements[i]);
				}
			}
		}

		// Run custom form validators if present
		new Hash(this.custom).each(function (validator) {
			if (validator.exec() != true) {
				valid = false;
			}
		});
		return valid;
	},

	addError:function (obj, errtxt) {
		//determine position
		var coord = obj.target ? $(obj.target).getCoordinates() : obj.getCoordinates();
		if ($(obj).get('tag') == 'input' && $(obj).type == 'checkbox') {
			var regExp = /\[\]/;
			if (obj.name.search(regExp)) {
				$$('input').each(function (el1) {
					if (el1.name == obj.name && el1.type == 'checkbox') {
						var coord1 = el1.target ? $(el1.target).getCoordinates() : el1.getCoordinates();
						if (coord.right < coord1.right)
							coord = coord1;
					}
				});
			}

		}
		if (!obj.element) {
			var pos = coord.right;
			var options = {
				'opacity':0,
				'position':'absolute',
				'float':'left',
				'left':pos + 40,
				'top':coord.top
			};
			obj.element = new Element('div', {'class':'auctionErrorTip', 'styles':options}).injectInside(document.body);
		}
		if (obj.element && obj.element != true) {
			obj.element.empty();
			error = new Element('p').set('html', errtxt);
			error.injectInside(obj.element);
			obj.element.set('tween', {
				duration:2500,
				wait:true
			});
			obj.element.tween('opacity', 0, 1);
		}
	},
	removeError:function (obj) {
		if (obj.element && obj.element != true) {
			obj.element.set('tween', {
				duration:2500,
				wait:true
			});
			obj.element.tween('opacity', obj.element.style.opacity, 0);
		}
	},
	handleResponse:function (state, el) {
		// Find the label object for the given field if it exists
		if (!(el.labelref)) {
			var labels = $$('label');
			labels.each(function (label) {
				if (label.getProperty('for') == el.getProperty('id')) {
					el.labelref = label;
				}
			});
		}

		// Set the element and its label (if exists) invalid state
		if (state == false) {
			el.addClass('invalid');
			if (this.lastError) this.addError(el, this.lastError);
			if (el.labelref) {
				$(el.labelref).addClass('invalid');
			}
		} else {
			el.removeClass('invalid');
			if (el.labelref) {
				$(el.labelref).removeClass('invalid');
			}
			this.removeError(el);
		}
	}
});

function reverseRefreshCustomFields(catselect) {
	frm = catselect.form;
	if (frm.has_custom_fields_with_cat.value) {
		frm.task.value = 'refreshcategory';
		frm.submit();
	}
}

function deleteMainToggleRequire(checked) {
	var my_file_element = document.getElementById('my_file_element');
	if (checked) {
		my_file_element.addClass('required');
	} else {
		my_file_element.removeClass('required');
	}

}


function auctionTypeExtras(type) {
	var el = document.getElementById('inviteSettingsContainer');
	// Auction 'Invite' type
	if (type == 5) {
		el.style.display = 'inline';
	} else {
		el.style.display = 'none';
	}
}

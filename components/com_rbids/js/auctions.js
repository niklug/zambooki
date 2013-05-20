var auctionObject = {
	toggleDescription:function (id, image) {
		$$('#' + id).each(function (el) {
			if (el.getStyle('display') == 'none') {
				el.setStyle('display', 'block');
				image.src = image.src.replace('_01.png', '_02.png');
			}
			else {
				el.setStyle('display', 'none');
				image.src = image.src.replace('_02.png', '_01.png');
			}
		})
	},
	BidFormValidate:function (form) {

		var initial_price = form.elements['initial_price'];
		var terms = form.elements['agreement'];
		var maxbid = form.elements['max_price'];
		var bid_amount = form.elements['amount'];
		var attach_to_bid = form.elements['attach_to_bid'];
		// verify if Terms and conditions are checked

		if (must_accept_term && !terms.checked) {
			alert(language["bid_err_terms"]);
			$(terms).addClass('invalid');
			$(terms).getParent().addClass('invalid');
			return false;
		}
		if (parseFloat(bid_amount.value) <= 0 || isNaN(parseFloat(bid_amount.value))) {
			alert(language["bid_err_empty_bid"]);
			$(bid_amount).addClass('invalid');
			return false;

		}

		if (parseFloat(maxbid.value) > 0) {
			accepted_price = parseFloat(maxbid.value);
			if (accepted_price < parseFloat(bid_amount.value)) {
				alert(language["bid_err_bid_greather_max_price"] + ' ' + accepted_price + ' ' + auction_currency);
				$(bid_amount).addClass('invalid');
				return false;
			}
		}

		if (attach_to_bid.hasClass('required') && !attach_to_bid.value) {
				alert(language["bid_err_attach_to_bid"]);
				$(attach_to_bid).addClass('invalid');
				return false;
		}

		return true;

	},
	submitListForm:function (order_by, order_direction) {
		if (order_direction == '!ASC')
			order_direction = 'DESC';
		if (order_direction == '!DESC')
			order_direction = 'ASC';
		if (order_direction == '')
			order_direction = 'ASC';

		document.rbidsForm.filter_order.value = order_by;
		document.rbidsForm.filter_order_Dir.value = order_direction;
		document.rbidsForm.submit();
	},

	SendMessage:function (link, message_id, bidder_id, username, isprivate) {
		if (!bidder_id) bidder_id = 0;
		if (!message_id) message_id = 0;
		if (!isprivate) isprivate = 0;

		//if (link) link.style.display='none';
		$$("dt.tab2").fireEvent("click");
		document.getElementById('bidder_id').value = bidder_id;
		document.getElementById('idmsg').value = message_id;
		document.getElementById('msgisprivate').value = isprivate;
		document.getElementById('auction_message_box').style.display = 'block';
		document.getElementById('message_to').innerHTML = username;
		document.getElementById('message').focus();
	},

	SendBroadcastMessage:function (link) {

		if (link) link.style.display = 'none';

		document.getElementById('broadcast_msg').style.display = 'block';
		document.getElementById('broadcast_message').focus();
	}

};

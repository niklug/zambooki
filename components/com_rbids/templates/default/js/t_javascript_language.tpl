{import_js_block}
var language=Array();
language["bid_err_fields_compulsory"]='{"COM_RBIDS_COMPULSORY_FIELDS_UNFILLED"|translate}';
language["bid_err_empty_bid"]='{"COM_RBIDS_PRICE_CAN_NOT_BE_EMTPY"|translate}';
language["bid_err_must_be_greater"]='{"COM_RBIDS_BID_MUST_BE_GREATER_THAN_THE_INITIAL_PRICE"|translate}';
language["bid_err_terms"]='{"COM_RBIDS_YOU_MUST_CHECK_THE_TERMS_AND_CONDITIONS"|translate}';
language["bid_err_attach_to_bid"]='{"COM_RBIDS_ERR_BID_ATTACHMENT_COMPULSORY"|translate}';
language["bid_err_bid_not_numeric"]='{"COM_RBIDS_BID_IS_NOT_NUMERIC"|translate}';
language["bid_err_bid_greather_max_price"]='{"COM_RBIDS_BIDDED_AMOUNT_IS_BIGGER_THAN_MAXIMUM_ALLOWED"|translate}';

language["bid_confirm_close_auction"]='{"COM_RBIDS_ARE_YOU_SURE_YOU_WANT_TO_CLOSE_THIS_AUCTION"|translate}';
language["bid_bid_price"]='{"COM_RBIDS_BID_PRICE"|translate}';
language["bid_required"]='{"COM_RBIDS_FIELD_REQUIRED"|translate}';
language["bid_err_numeric"]='{"COM_RBIDS_FIELD_MUST_BE_NUMERIC"|translate}';
language["bid_err_email"]='{"COM_RBIDS_EMAIL_IS_NOT_VALID"|translate}';
language["bid_err_startdate"]='{"COM_RBIDS_AUCTION_START_DATE_IS_NOT_VALID"|translate}';
language["bid_err_enddate"]='{"COM_RBIDS_AUCTION_END_DATE_IS_NOT_VALID"|translate}';
language["bid_err_max_valability"]='{"COM_RBIDS_AUCTION_END_DATE_EXCEEDS_MAXIMUM_ALLOWED_LENGTH"|translate}';
language["bid_rating"]='{"COM_RBIDS_USER_RATING"|translate}';
language["bid_notfound"]='{"COM_RBIDS_NOT_FOUND"|translate}';
language["bid_noresults"]='{"COM_RBIDS_NO_RESULTS_FOUND"|translate}';
language["bid_post_in_cat"]='{"COM_RBIDS_POST_AUCTION_IN_THIS_CATEGORY"|translate}';
language["bid_days"]='{"COM_RBIDS_DAYS"|translate}';
language["bid_expired"]='{"COM_RBIDS_EXPIRED"|translate}';

{if $terms_and_conditions}
    var must_accept_term= true;
{else}
    var must_accept_term= false;
{/if}

var auction_currency='{$auction->currency}';

var bid_max_availability={$cfg->availability|default:0};
var bid_date_format='{$cfg->date_format}';
{/import_js_block}

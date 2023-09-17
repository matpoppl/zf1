import $ from './libs/pquery/pquery.js';
import { removeStatusAlerts } from './ui/alert.js';

function trans(key)
{
	return key.replace(/[\W+]+/g, ' ');
}

$('.js-confirm').on('click', (evt) => {
	
	if (! window.confirm( trans($(evt.target).attr('data-confirm')) || 'Are you sure ?' )) return false;
	
	$('<input type="hidden" name="confirmed" value="1"/>').appendTo(evt.target.form);
});

removeStatusAlerts();

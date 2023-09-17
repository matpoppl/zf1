import $ from './../libs/pquery/pquery.js';

export function removeStatusAlerts()
{
  setTimeout(() => {
    $('[role="status"].js-status').remove();
  }, 2000);
}


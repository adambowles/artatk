var start_time = new Date().getTime();

$('a.vote').each(function(){
  $(this).attr('start_time', start_time);
});

function insert_time(anchor, time)
{
  anchor = $(anchor);

  var start_time = anchor.attr('start_time');
  var end_time = new Date().getTime();
  var deliberation_time = end_time - start_time;

  var new_href = anchor.attr('href') + '&delib_time=' + deliberation_time;

  anchor.attr('href', new_href);
}

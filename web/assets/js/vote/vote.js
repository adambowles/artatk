var start_time = new Date().getTime();

$('a.vote').each(function(){
  $(this).attr('start_time', start_time);
});

$('a.vote').find('i').on({
  mouseenter: function(){
    var star = $(this).parent().attr('id').replace('star','');
    highlight_stars(star);
  },
  click: function(){
    var star = $(this).parent().attr('id').replace('star','');
    highlight_stars(star);
  },
  mouseleave: function(){
    unhighlight_stars();
  }
});

/**
 * Highlight all the stars up to a certain one
 */
function highlight_stars(amount)
{
  unhighlight_stars();

  if(!isNaN(amount)) {
    for(i = 1; i <= amount; i++) {
      $('#star'+i).children().addClass('fa-star');
      $('#star'+i).children().removeClass('fa-star-o');
    }
  }
}

/**
 * Makes all voting stars on the page become unhighlighted
 */
function unhighlight_stars()
{
  $('a.vote').find('i').addClass('fa-star-o');
  $('a.vote').find('i').removeClass('fa-star');
}

function insert_time(anchor, time)
{
  anchor = $(anchor);

  var start_time = anchor.attr('start_time');
  var end_time = new Date().getTime();
  var deliberation_time = end_time - start_time;

  var new_href = anchor.attr('href') + '&delib_time=' + deliberation_time;

  anchor.attr('href', new_href);
}


$j(document).ready(function(){
	/* This code is executed after the DOM has been completely loaded */
	
	var $j = jQuery.noConflict(), totWidth = 0, positions = new Array(), current = 0, pos = 1;
	
	$j('.slides .slide').each(function(i){
		
		/* Traverse through all the slides and store their accumulative widths in totWidth */
		
		positions[i] = totWidth;
		totWidth += $j(this).width();
		
		/* The positions array contains each slide's commulutative offset from the left part of the container */
		
		if(!$j(this).width()){
			alert('Please, fill in width & height for all your images!');
			return false;
		}
		
	});
	
	$j('.slides').width(totWidth); /* Change the container div's width to the exact width of all the slides combined */

	$j('.thumbs ul li a').click(function(e){

			e.preventDefault();
	});
	$j('.thumbs ul li a').hover(function(e){
		$j('li.thumb-item').removeClass('act').addClass('inact');
		$j(this).parent().addClass('act');
		pos = $j(this).parent().prevAll('.thumb-item').length;
		$j('.slides').animate({marginLeft:-positions[pos]+'px'},450);
		current = $j(this).eq(current).length;
		if (current !== $j('.thumbs ul li a').length - 1) {
	    current++;
	  } else {
	    current = 0;
	  }
		},
  function () {
	});
	
	$j('.thumbs ul li.thumb-item:eq(0)').addClass('act').siblings().addClass('inact');
	/* On page load, mark the first thumbnail as active */

	function autoAdvance(current) {

	$j('li.thumb-item').removeClass('act').addClass('inact');
	$j('.thumbs ul li a').eq(current).parent().addClass('act');
	pos = $j('.thumbs ul li a').eq(current).parent().prevAll('.thumb-item').length;
	$j('.slides').stop().animate({
	  marginLeft: -positions[pos] + 'px'
	},450);

	}
	var changeEvery = 10;
	setInterval(function(){
	  	  if (current !== $j('.thumbs ul li a').length - 1) {
	    current++;
	  } else {
	    current = 0;
	  }
	  autoAdvance(current);
	},changeEvery * 1000);

	/* End of customizations */
});
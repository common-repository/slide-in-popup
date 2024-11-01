<?php
/*

Copyright (C) <2009>  <Dragan Vuletic>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.opensource.org/licenses/gpl-3.0.html>


*/
header("content-type: application/x-javascript");
$script = <<<EOS
window.addEvent('domready', function(){
	/* Horizontalni sidebar
	$('slidein_slider').setStyle('width','auto');
	var mySlide = new Fx.Slide('slidein_slider',{
		mode:'horizontal'
	}).hide();  //starts the panel in closed state  

    
	(function(){ mySlide.toggle('horizontal');}).delay(1000*3);
	*/
	
	$('slidein_slider').setStyle('height','auto');
	var mySlide = new Fx.Slide('slidein_slider',{
		mode:'vertical'
	}).hide();  //starts the panel in closed state  

    
	(function(){ mySlide.toggle('vertical');}).delay(1000*$_GET[delay]);
		

	if ($$('.slidein_close')) {
    	$$('.slidein_close').each(function(item){
    		item.addEvent('click', function(e){
				e = new Event(e);
				mySlide.slideOut();
				e.stop();
			});
    	});
    }
    if ($$('.slidein_custom_close')) {
    	$$('.slidein_custom_close').each(function(item){
    		item.addEvent('click', function(e){
				e = new Event(e);
				mySlide.slideOut();
				e.stop();
			});
    	});
    }
    if ($$('.slidein_toggle')) {
    	$$('.slidein_toggle').each(function(item){
    		item.addEvent('click', function(e){
				e = new Event(e);
				mySlide.toggle();
				item.toggleClass('closed');
				e.stop();
			});
    	});
    }

});
EOS;
echo $script;
?>
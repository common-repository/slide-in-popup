=== Slide In Popup ===
Contributors: pajko
Donate link: http://vuleticd.com/
Tags: popups, widgets
Requires at least: 2.8.6
Tested up to: 2.9
Stable tag: 0.1

Define the bottom edge slide-in box with any HTML/PHP globally or for each WP page/post.

== Description ==

This plugin will enable you to add any kind of slide in/out popups to your Wordpress website.
New widget, called "SlideIn Box", is added. Each of these widgets can hold one slidein box content, whitch can be any HTML or PHP code. Inside these widgets you will have complete control over your slidein box layout.
New widgets sidebar, called "SlideIn Boxes", is added, where you should place all defined "SlideIn Box" widgets. After that, they will be selectable from the dropdown menu on Global SlideIn Options page, and inside page/post meta boxes. 


*  You can control the position for each SlideIn box. 
*  You can control when will these SlideIn boxes display to your visitors.
*  You can specify delay for each SlideIn box.
*  With little HTML/CSS knowleadge you can have your own styles for toggle and close buttons. 
*  Page/post settings will override global settings for SlideIn boxes.


== Installation ==


1. Download the plugin zip file, and unzip the content.
2. Upload the content of downloaded zip file to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Widgets page, and add your "SlideIn Box" widgets in "SlideIn Boxes" sidebar
5. Go to Settings->Global Slide In page to select your global options and/or select your SlideIn preferences for each single post/page.

== Frequently Asked Questions ==

= I've installed the plugin, but I can't see the slidein box ? =

You must create at least one SlideIn Box first, in order to be able to select it on settings page. Go to Widgets page and insert one SlideIn Box widget into SlideIn Boxes sidebar.
Name your widget with some easy to remember name, and insert some content inside.
This can be used as a nice example:

	<div style="background:gray;height:100px;">
		<div style="float:left;width:40%;">
			<h2 style="color:red;">Spread Your Message with SlideIn Boxes Plugin</h2>
		</div>
		<div style="float:left;width:45%;">
			<form action="" method="post">
				<p>Or do your marketing stuff more efficiant.</p>
				Name : <input type="text" name="name"/> 
				Email :  <input type="text" name="email"/>  
				<input type="submit" name="submit" value="Subscribe"/> 
			</form>
		</div>
		<div style="clear:both;"></div>
		<a style="position:absolute;top:20px;right:10px;" href="javascript:void(0);" class="slidein_custom_close">Close</a>
	</div>
Now, you will be able to select that widget on settings page.   


== Screenshots ==

1. Global options page
2. Meta box for each post/page
3. Widgets page
4. Front End example

== Changelog ==

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.1 =
* Initial release
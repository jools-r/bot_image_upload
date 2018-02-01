<?php

	if(@txpinterface == 'admin') {
		register_callback('bot_image_upload','article');
		register_callback('bot_image_css','admin_side','head_end');
		bot_image_img();
	}

/*******************************************************************************
	Global preferences - Configuration. Read comments and modify to your needs.
*******************************************************************************/

	function bot_iu_prefs() {
		return
			array(
				'bot_iu_fields' => '#article-image', // fields to be used (comma separated | use #custom-n for custom fields)
				'bot_iu_mono_list' => '', // fields with single image (comma separated | use #custom-n for cfs)
				'bot_choose_image_text' => gTxt('choose_images'), // text for "Choose images" link and button
				'bot_image_delete_text' => gTxt('delete_image'), // text and title for "delete image" button
				'bot_image_edit_text' => gTxt('edit_image'), // text and title for "edit image" button"
				'bot_add_image_text' => gTxt('add_image'), // text for "add image" checkbox
				'bot_iu_save_text' => gTxt('save'), // text for "Save" button"
				'bot_iu_cancel_text' => gTxt('cancel'), // text for "Cancel" button"
				'bot_iu_row_bg' => '#eaeaea', // selected row background
				'bot_iu_ui_path' => '', // path to the jQuery ui script. Defaults to 'textpattern' directory. Set to '' if ui already loaded
			);
	}

/**
	CSS for the image upload. You are free to modify these
	to match your own backend theme.
*/

	function bot_image_css() {

		global $event;
		if($event != 'article') { // Outputs css only in 'write' tab.
			return;
		}

		echo '<style type="text/css">
				.bot_image_delete {
					background: url("?bot_image_img=delete") top center no-repeat;
					display:inline-block;
					text-indent: -99999px;
					width:15px;
					height:20px;
				}
				.bot_image_edit {
					background:url("?bot_image_img=edit") top center no-repeat;
					display:inline-block;
					text-indent:-99999px;
					width:15px;
					height:20px;
					margin-right: 5px;
				}
				#bot_iu_loading {
					background:#000 url("?bot_image_img=bot_iu_loading") center center no-repeat;
					text-indent:-99999px;
					width:50px;
					height:50px;
					padding:10px;
					position:fixed;
					left:50%;
					margin-left:-30px;
					top:50%;
					margin-top:-30px;
					z-index:9999;
					-moz-border-radius:10px;
					-webkit-border-radius:10px;
					border-radius: 10px;
				}
				#bot_iu_fade {
					display: none;
					background: #000;
					position: fixed; left: 0; top: 0;
					width: 100%; height: 100%;
					opacity: .80;
					z-index: 999;
				}
				#bot_iu_iframe {
					width:100%;
				}
				#bot_iu_iframe_container {
					/* height and margin-top are set by js */
					display:none;
					position:fixed;
					left:50%;
					z-index: 99999;
					overflow: hidden;
					background: #fff;
					width:80%;
					padding:0 2%;
					margin-left: -42%;
					text-align:right;
					-moz-border-radius:5px;
					-webkit-border-radius:10px;
					border-radius: 10px;
					-moz-box-shadow: 0 0 20px #000;
					-webkit-box-shadow: 0 0 20px #000;
					box-shadow: 0 0 20px #000;
				}
				#bot_iu_save,
				#bot_iu_cancel {
					background:#666;
					color:#fff;
					font-weight:bold;
					padding:5px 10px;
					margin-left:5px;
					display:inline-block;
					cursor:pointer;
					-moz-border-radius:5px;
					-webkit-border-radius:5px;
					border-radius:5px;
				}
				#bot_iu_save:hover,
				#bot_iu_cancel:hover {
					background:#333;
					text-decoration:none;
				}
				.bot_iu_ul_container {
					padding:0;
					display: flex;
					flex-flow: row wrap;
				}
				.bot_iu_image_container {
					flex: 0 1 40%;
					list-style-type: none;
					border:solid #eaeaea 1px;
					background: #fff;
					padding:10px 10px 0;
					cursor: move;
				}
				.bot_iu_image_container span {
					display:block;
					overflow:hidden;
					padding-top:5px;
				}
				.bot_add_image {
					display:block;
				}
			</style>

			<!--[if lte IE 7]>
				<style>
					/*-- styling for fucking ie7 and below --*/
					.bot_iu_image_container {
						display:inline;
					}
					.bot_image_delete,
					.bot_image_edit {
						float:left;
					}
				</style>
			<![endif]-->

			<!--[if lte IE 6]>
				<style>
					/*-- minimal styling for fucking ie6 and below --*/
					#bot_iu_fade,
					#bot_iu_loading,
					#bot_iu_iframe_container {
						position: absolute;
					}
					#bot_iu_iframe_container {
						border: solid #ccc thick;
					}
				</style>
			<![endif]-->';
	}

/**
	Generates and stores the images required by the design.
	Image URI is: http://example.com/textpattern/?bot_image_img=delete|edit|file
*/

	function bot_image_img() {

	global $event;
		if($event != 'article') { // Generates images only in 'write' tab.
			return;
		}
		$image = gps('bot_image_img');

		if(!$image)
			return;

		switch($image) {
			case 'delete':
				$file = 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAABEElEQVR42mL4//8/Ayn4zJkzXMh8gABiYkACZ8+elQdibQYcACqXCqTtYGIAAcQIMgWmGUg9gIrrGBsbX8Wi+QqUuxKIG4FqrgMEENgANM0M6IagaYaBAiCeDBBAMAM4gJyvQMyEbgiURtf8F4g5gRb8BgggZC+ADPkCxMwM+AFIMw9Q8w8QByCA4DZCBXigCojSDAIAAYTiZCRD/mHR/A9dMwgABBATFoXKOMSZoHIoACCAmPBEFTZwBT2dAAQQEwHNf7F4B8UQgABiIqAZFB7cWAIWbghAAMFcIIYrtPHEDlgPQAAxQUN/P5BywhXaWAxxguphAAgg9JzmCMQceHIiB0gNshhAAMFTIrkAIMAA7k7JUu0YckQAAAAASUVORK5CYII=';
				break;
			case 'edit':
				$file = 'iVBORw0KGgoAAAANSUhEUgAAABMAAAAUCAMAAABYi/ZGAAAABGdBTUEAAK/INwWK6QAAABl0RVh0 U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAACiUExURf7+/v39/bOzs+rq6uHh4dXV1c3N zb6+vqioqLKysq6urqmpqfb29tDQ0O7u7vPz8+3t7d7e3q2trc/Pz7CwsLm5udPT09HR0d3d3fHx 8fT09MXFxaqqqsDAwNra2tTU1Ovr67GxsfLy8qysrNfX17i4uLe3t8HBwaenp/r6+uzs7ODg4N/f 3+/v78nJyenp6bu7u7+/v8zMzNnZ2dLS0v///181u00AAAA2dFJOU/////////////////////// ////////////////////////////////////////////////AKGPTjEAAADaSURBVHjaYjDFBAAB xIDEZuRl5wPRAAGEJMYgziVixANkAAQQkpi4mqoGkzSQARBACDE2WX0TIQ4mIAsggOBiwoZaPLrS 3CxAJkAAwcSE2QX4FYxFJUB8gACCirGx8ykxyzMJgrkAAQQR41UXkGRm5RRkBPMAAggsJmYgKcXM qigBETIFCCCwmLKKnhSriA5UyBQggMBiHKIyLFzaMCFTgAACi3ELMXGyIFwKEEBgFpsYvxyShwAC CGK5Jkq4AAQQA5awAgggbGIAAYRNDCCAsIkBBBA2MYAAAwCXW0H+487c7QAAAABJRU5ErkJggg==';
				break;
			default:
				$file = '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';
		}

		ob_start();
		ob_end_clean();
		header('Content-type: image/gif');
		echo base64_decode($file);

	}


	function bot_image_generate_thumbnail()
	{
		global $prefs, $step;
		extract(bot_iu_prefs());
		$image_path = hu.$prefs['img_dir'];
		$article_id = gps('ID'); // Fetch article id from $_GET, if any, or sets it to ''
		$fields_array = explode(",", $bot_iu_fields); // tranforms in array
		for ($i =0; $i < count($fields_array); $i++) { // changes array values to be used with urls and db
			$current_field = trim($fields_array[$i]);
			$current_field = ($current_field === '#article-image') ? 'Image' : str_replace('#custom-', 'custom_', $current_field);
			$fields_array[$i] = $current_field; // reinserts modified value
		}

		if ($step == 'edit' && $article_id) { // when opening an already existant article
			$fields_string = implode(',', $fields_array);
			$values = safe_row($fields_string, 'textpattern', 'ID = '. $article_id);
		}

		elseif($step == 'create' || $step == 'edit') { // when hitting 'save'
			for ($i =0; $i < count($fields_array); $i++) { // fetch all image ids and put in array 'values'
				$current = $fields_array[$i];
				$values[] = gps($current_field);
			}
		}

		// creates a numerical array of all images for current article
		$values = array_filter(array_values($values)); // convert to numerical array and remove empty values
		$values = explode(',', implode(',', $values)); // only one value per array key
		// Sanitizes values
		foreach($values as $value) {
			if(is_numeric($value)) {
				$ids[] = "'".doSlash($value)."'";
			}
		}

		// Proceed only if an article image exists.
		if (isset($ids)) {

			$ids = implode(',',$ids);
			$rs = safe_rows(
				'ext,id,thumbnail',
				'txp_image',
				'id in ('.$ids.')'
			);

			if ($rs) { // If image exist
				$rnd_number = '?'.time(); // Append to src to force reload.
				foreach($rs as $a) {
					extract($a);
					if($thumbnail == 0){
						$image = $id.$ext.$rnd_number;
					}
					else {
						$image = $id.'t'.$ext.$rnd_number;
					}
					$str[] =
						'<li class="bot_iu_image_container id'.$id.'">'.
							'<img src="'.$image_path.'/'.$image.'" alt="" />'. // smd_mod - prima era: '<img src="'.$image_path.'/'.$image.'" alt="" />'.
							'<span>'.
								'<a class="bot_image_delete" href="#" title="'.$bot_image_delete_text.'">'.$bot_image_delete_text.'</a>'.
								' <a class="bot_image_edit" href="#" title="'.$bot_image_edit_text.'">'.$bot_image_edit_text.'</a>'.
							'</span>'.
						'</li>';
				}
				return implode('',$str);
			}
		}
	}


/**
	Generates js code
*/

	function bot_image_upload()
	{
		global $event;

		if($event != 'article') {
			return;
		}

		extract(bot_iu_prefs());
		$bot_iu_saved_image = bot_image_generate_thumbnail();

		if(!empty($bot_iu_ui_path)) {
			echo <<<JS_CODE
	<script type="text/javascript" src="$bot_iu_ui_path"></script>
JS_CODE;
		}
		echo <<<JS_CODE
<script type="text/javascript">

	$(document).ready(function() {
		// Modify 'write' tab
		if ("$bot_iu_fields" === '#article-image') {
			$("$bot_iu_fields").parents('section#txp-image-group').append('<div><ul class="bot_iu_ul_container"></ul><a class="bot_add_image" href="#" title="$bot_choose_image_text">$bot_choose_image_text</a><br></div>'); // Creates an 'add image' link.
		} else {
			$("$bot_iu_fields").parent().append('<div><ul class="bot_iu_ul_container"></ul><a class="bot_add_image" href="#" title="$bot_choose_image_text">$bot_choose_image_text</a><br></div>'); // Creates an 'add image' link.
		}
		$("body").append('<div id="bot_temporary">$bot_iu_saved_image</div>');
		$(".bot_add_image").each(function(){ // first display all saved images in a temporary div and then clone each to assigned field
			var value = $(this).parent().parent().find("input").val();
			var container = $(this).prev('.bot_iu_ul_container');
			if(value) {
				var ids = value.split(/[ ,]+/);
				for(var i = 0 ; i < ids.length ; i++) {
					var imageId = ids[i];
					if (ids[i]) {
						var idClass = ".id" + ids[i];
						$("#bot_temporary "+idClass).clone().appendTo(container); // clone cos otherwise duplicate images are appended only once
					}
				}
			}
		});
		$("#bot_temporary").remove();
		// $("$bot_iu_fields").hide(); // Hide article image input.

		if (jQuery.ui) {
			$(".bot_iu_ul_container").sortable({
				update: function(event, ui) {
					var input = $("$bot_iu_fields");
					var imgOrder = $(this).sortable('toArray',{attribute:"class"}).toString().replace(/bot_iu_image_container id/g,"").replace(/ ui-sortable-handle/g,"");
					input.val(imgOrder);
				}
			});
		}

		// When clicking 'add' or 'edit' link...
		$("body").on("click", '.bot_add_image, .bot_image_edit', function(){

			// Set variables
			var selector = $(this); // Clicked link
			var input = $("$bot_iu_fields"); // ...corresponding input
			var p = $('#txp-image-group'); // ...surrounding p
			var values = input.val().split(/[ ,]+/); // existant ids array
			var backup = p.clone(); // backup for undo

			// Determines if clicked item is single-image or multi
			var clickedId = input.prop('id');
			var monoList = '$bot_iu_mono_list';
			if (monoList.indexOf(clickedId)!= -1) { // if clicked input id is in $bot_iu_mono_list string
				var type = 'mono';
			}

			// Set the url the iframe must point to.
			if (selector.prop("class") == 'bot_add_image') { // If .bot_add_image is clicked...
				var iframeUrl = "index.php?event=image";
			}
			else {
				// If an edit link is clicked...
				var imageId = $(this).parents(".bot_iu_image_container").prop("class").toString().replace(/bot_iu_image_container id/g,"").replace(/ ui-sortable-handle/g,"");
				var iframeUrl = "index.php?event=image&step=image_edit&id="+imageId;
			}

			// Fade in Background
			$('body').append('<div id="bot_iu_fade"></div>'); //Add the fade layer to bottom of the body tag.
			$('#bot_iu_fade').show().css({'filter' : 'alpha(opacity=80)'}); // show the fade layer -  fix the IE Bug on fading transparencies

			// Creates an hidden iframe.
			$("body").append(
				'<div id="bot_iu_iframe_container">' +
					'<iframe id="bot_iu_iframe" src ="' + iframeUrl + '" frameborder="0"></iframe>' +
				'</div>'
			);

			// creates 'cancel' and 'save' buttons
			$("#bot_iu_iframe").after("<p><a id=\"bot_iu_save\">$bot_iu_save_text</a><a id=\"bot_iu_cancel\">$bot_iu_cancel_text</a></p>");

			// Calculates heights based on window size for vertical centering.
			var windowHeight = $(window).height();
			var containerHeight = (windowHeight * 80) / 100;
			var containerTopMargin = (windowHeight - containerHeight) / 2;
			var iframeHeight = (containerHeight * 78) / 100;
			var loadingTopMargin = (windowHeight / 2) - 30;
			$("#bot_iu_iframe_container").height(containerHeight).css("top", containerTopMargin);
			$("#bot_iu_iframe").height(iframeHeight).css("margin-top", containerTopMargin / 2);
			$("#bot_iu_save").css("margin-top", containerTopMargin / 2);
			$("body").append('<p id="bot_iu_loading">loading</p>');


			// Iframe interactions
			$("#bot_iu_iframe").on('load', function(){

				// Hides in iframe
				var iframe = $("#bot_iu_iframe").contents();
				iframe.find(".content").css("padding","0 1em"); // use more of window but avoid horizontal scrollbars
				iframe.find(".txp-header, .txp-footer").hide(); // some are kept even if now no more used
			 // iframe.find("#image-search option[value=author]").remove(); // remove some options from search
			 // iframe.find("#image-search option[value=id]").remove();
			 // iframe.find("#image-search option[value=alt]").remove();
			 // iframe.find("#image-search option[value=caption]").remove();
				iframe.find(".txp-list-options").remove(); // Hide list options
				iframe.find(".txp-list-col-multi-edit").hide(); // Removes TXP Multiedit Column
				iframe.find(".multi-edit").hide(); // Removes TXP Multiedit Options
				iframe.find(".txp-list .txp-list-col-id a").contents().unwrap(); // unlinks the id #
				iframe.find(".txp-list .date").hide();
				iframe.find(".txp-list-col-tag-build").hide();
				iframe.find(".txp-list-col-author").hide();
				iframe.find(".txp-list-col-category").hide();
				iframe.find(".thumbnail-upload").hide(); // Hides 'upload thumb'.
				iframe.find(".thumbnail-alter").hide(); // Hides 'create thumb'.  (Comment this out if you have images without thumbs so they are easily accessible)
				iframe.find(".edit-image-name").hide(); // Hides 'image name'
				iframe.find("input.publish").hide(); // Hides original save button
				iframe.find("#jbx_div").remove(); // Removes jbd_multiple_upload
				iframe.find("#smd_thumb_profiles").remove(); // Removes smd_thumb

				// Adds 'add' column
				iframe.find('.txp-list thead tr').append('<th>$bot_add_image_text</th>');
				iframe.find('.txp-list tbody tr').append(
					'<td class="add">'+
						'<input type="checkbox" name="bot_image_checkbox[]" class="bot_image_checkbox" />'+
					'</td>');

				// Checks checkboxes depending on already set cfs.
				if (type == 'mono') { // if mono reduce values array to last item
					var imageId = values.pop();
					values=[];
					values.push(imageId);
				}
				for(var i = 0 ; i < values.length ; i++) {
					imageId = values[i];
					iframe.find(".txp-list td.txp-list-col-multi-edit input").filter(function(){
								return $(this).val() == imageId
							;}).parents("tr").find('input.bot_image_checkbox').prop('checked', true).parents("tr").css("background", "$bot_iu_row_bg");
				}

				// everything is done, now loader can be hidden and iframe can be shown.
				$("#bot_iu_loading").hide();
				$("#bot_iu_iframe_container").show();

				// on click
				iframe.find(".bot_image_checkbox[type=checkbox]").click(function(){

					var imageId = $(this).parents("tr").find(".txp-list-col-multi-edit input").val(); // Grabs image id.
					var imageUrl = $(this).parents("tr").find("td.txp-list-col-thumbnail.has-thumbnail img").prop('src'); // Grabs url of currently checked thumb

					if (this.checked){

						if (type == 'mono') {

							iframe.find("tr").css("background","none"); // Eliminates all rows bg.
							iframe.find(".bot_image_checkbox").not($(this)).prop('checked', false); // Unchecks all other checkboxes.
							p.find(".bot_iu_image_container").remove(); // Removes image container
						}

						$(this).parents("tr").css("background","$bot_iu_row_bg"); // Changes checked row bg.
						values.push(imageId); // Inserts new image id.

						p.find(".bot_iu_ul_container").append(
							'<li class="bot_iu_image_container id' + imageId + '">' +
								'<img src="' + imageUrl + '" />' +
								'<span>' +
									'<a class="bot_image_delete" href="#" title="$bot_image_delete_text">$bot_image_delete_text</a>' +
									' <a class="bot_image_edit" href="#" title="$bot_image_edit_text">$bot_image_edit_text</a>' +
								'</span>' +
							'</li>'
						);

					} else {

						var arrayIndex = $.inArray(imageId, values); // checks this id index in array
						values.splice(arrayIndex,1); // eliminates this id from array
						p.find(".bot_iu_image_container.id"+imageId).remove(); // Removes thumbnail container.
						$(this).parents("tr").css("background","none"); // Removes background.

					}
				});

				// Sets again background for selected row.
				iframe.find(".txp-list tr:has(input.bot_image_checkbox:checked)").addClass("bot_iu_row_background");

				// Avoid FOUC when clicking links and submits.
				iframe.find(".txp-list a, input.smallerbox, input.publish").not("#eblcropui a, #eblcropui input").click(function(){
					 $("#bot_iu_iframe_container").hide();
					 $("#bot_iu_loading").show();
				});

				// When clicking 'save' button...
				$("#bot_iu_save").click(function(e){

					var iframe = $("#bot_iu_iframe").contents();
					if (iframe.find(".publish").length) { // we are on edit pane
						iframe.find(".publish").click();
						var editedImageId = iframe.find("#image_details_form input[name=id]").val();
						var imageUrl = iframe.find(".content-image").prop('src'); // Grabs the new image thumbnail. smd_mod - prima era: var imageUrl = iframe.find(".thumbnail-edit img").prop('src');
						if (type == 'mono') {
							values.length = 0;
							p.find(".bot_iu_ul_container li").remove();
						}
						if ($.inArray(editedImageId, values)!=-1) { // When editing an already selected image just refresh image.
							$(".id"+editedImageId+" img").prop('src', imageUrl);
						}
						else {
							values.push(editedImageId);
							p.find("div .bot_iu_ul_container").append(
								'<li class="bot_iu_image_container id' + editedImageId + '">' +
									'<img src="' + imageUrl + '" />' +
									'<span>' +
										'<a class="bot_image_delete" href="#" title="$bot_image_delete_text">$bot_image_delete_text</a>' +
										' <a class="bot_image_edit" href="#" title="$bot_image_edit_text">$bot_image_edit_text</a>' +
									'</span>' +
								'</li>'
							);
						}
					}
					else { // we are on img list
						var oldValues = input.val();
						if (!oldValues[0] && type != 'mono') { // eliminates first (blank) array item if oldValue is empty
							values.shift();
						}
					   if (type == 'mono') {
							var imageId = values.pop();
							values=[];
							values.push(imageId);
						}
						input.val(values); // update cf values
						$("#bot_iu_iframe_container, #bot_iu_loading, #bot_iu_fade").remove();
					}
				})

				// When clicking 'cancel' button...
				$("#bot_iu_cancel").click(function(){
					p.replaceWith(backup); //solution!
					if (jQuery.ui) {
						$(".bot_iu_ul_container").sortable({
							update: function(event, ui) {
								var imgOrder = $(this).sortable('toArray',{attribute:"class"}).toString().replace(/bot_iu_image_container id/g,"").replace(/ ui-sortable-handle/g,"");
								$(this).siblings("input").val(imgOrder);
							}
						});
					}
					$("#bot_iu_iframe_container, #bot_iu_loading, #bot_iu_fade").remove();
				})

			});
			return false;
		});

	   $("body").on('click','.bot_image_delete',function(){ // changed 'live' with 'on' as it is now deprecated
			var input = $("$bot_iu_fields");
			var values = input.val().split(","); // existant ids array
			var imageId = $(this).parents(".bot_iu_image_container").prop("class").replace(" ui-sortable-handle","").replace("bot_iu_image_container id",""); // current id
			var arrayIndex = $.inArray(imageId, values); // checks this id index in array
			values.splice(arrayIndex,1); // eliminates this id from array
			input.val(values);  // updates values
			$(this).parents(".bot_iu_image_container").remove(); // Removes image container
			return false;
		});
	});
</script>
JS_CODE;
	}

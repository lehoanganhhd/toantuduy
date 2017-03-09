<?php
function dthemes_wp_title(  $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	$status = dttheme_is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') || dttheme_is_plugin_active('wordpress-seo/wp-seo.php');
	global $paged, $page;
	
	if ( !$status ) {
		$ctitle = dttheme_public_title();
		return $ctitle;
	}

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'dt_themes' ), max( $paged, $page ) );
	}
	
	return $title;
}
add_filter( 'wp_title', 'dthemes_wp_title', 10, 2 );

# backwards compatibility for title tag support
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function theme_slug_render_title() {?>
    	<title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}
	add_action( 'wp_head', 'theme_slug_render_title' );
}

function dttheme_blog_title() {
	$the_content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', get_option('blogname'));
	return $the_content;
}

add_filter('widget_text', 'do_shortcode');

#FILTER TO MODIFY THE DEFAULT CATEGORY WIDGET
add_filter('wp_list_categories', 'my_wp_list_categories');
function my_wp_list_categories($output) {
	if (strpos($output, "</span>") <= 0) {
		$output = str_replace('</a> (', '<span> ', $output);
		$output = str_replace(')', '</span></a> ', $output);
	}
	return $output;
}

add_filter('get_archives_link', 'my_wp_list_archive');
function my_wp_list_archive($output) {
	$output = str_replace('</a>&nbsp;(', '<span> ', $output);
	$output = str_replace(')', '</span></a> ', $output);
	return $output;
}

/** dttheme_default_navigation()
 * Objective:
 *		To setup default navigation  when no menu is selected
 **/
function dttheme_default_navigation() {
	echo '<ul class="menu">';
	$args = array('depth' => 1, 'title_li' => '', 'echo' => 0, 'post_type' => 'page', 'post_status' => 'publish');
	$pages = wp_list_pages($args);
	if ($pages)
		echo $pages;
	echo '</ul>';
}
### --- ****  dttheme_default_navigation() *** --- ###

add_action('wp_enqueue_scripts', 'plugin_head_styles_scripts');
function plugin_head_styles_scripts() {

	#Theme urls for Style Picker
	global $dt_google_fonts;
	$scroll = dttheme_option('general', 'disable-custom-scroll') ? "disable" : "enable";
	$stickynav = ( dttheme_option("general","enable-sticky-nav") ) ? "enable" : "disable";
	
	$isResponsive = dttheme_option ( "mobile", "is-theme-responsive" ) ? "enable" : "disable";
	

	echo "\n <script type='text/javascript'>\n\t";
	echo "var mytheme_urls = {\n";
	echo "\t\t theme_base_url:'".IAMD_BASE_URL."'";
	echo "\n \t\t,framework_base_url:'".IAMD_FW_URL."'";
	echo "\n \t\t,ajaxurl:'".admin_url('admin-ajax.php')."'";
	echo "\n \t\t,url:'".get_site_url()."'";
	echo "\n \t\t,scroll:'".$scroll."'";
	echo "\n \t\t,stickynav:'".$stickynav."'";
	echo "\n \t\t,is_admin:'".current_user_can('level_10') ."'";
	echo "\n \t\t,skin:'".dttheme_option('appearance','skin')."'";
	echo "\n \t\t,layout:'".dttheme_option('appearance','layout')."'";
	echo "\n \t\t,isResponsive:'{$isResponsive}'";
	echo "\n \t\t,layout_pattern:'".dttheme_option('appearance','boxed-layout-pattern')."'";
	echo "\n\t};\n";
	echo " </script>\n";
	
	#Theme urls for Style Picker End
	wp_enqueue_script('modernizr-script', IAMD_FW_URL.'js/public/modernizr.min.js');
	
	wp_enqueue_script('jquery');

	wp_enqueue_script( 'html5shiv', IAMD_FW_URL . '/js/public/html5shiv.min.js', array(), false, true );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	
	wp_enqueue_script('easing-script', IAMD_FW_URL.'js/public/easing.js',array(),false,true);
	wp_enqueue_script('smartresize-script', IAMD_FW_URL.'js/public/jquery.smartresize.js',array(),false,true);
	
	wp_enqueue_style('prettyphoto',IAMD_BASE_URL.'css/prettyPhoto.css');
	wp_enqueue_script('prettyphoto-script', IAMD_FW_URL.'js/public/jquery.prettyPhoto.js',array(),false,true);
	
	wp_enqueue_script('nicescroll-script', IAMD_FW_URL.'js/public/jquery.nicescroll.min.js',array(),false,true);

	wp_enqueue_script('isotope-script', IAMD_FW_URL.'js/public/jquery.isotope.min.js',array(),false,true);
	
	if( dttheme_option("general","enable-sticky-nav") ):
		wp_enqueue_script('sticky-nav', IAMD_FW_URL.'js/public/jquery.sticky.js',array(),false,true);
	endif;

	#Theme Picker 		
	if( dttheme_option("general","disable-picker") === NULL  && !is_user_logged_in() ):
		wp_enqueue_script('theme-cookies', IAMD_FW_URL.'js/public/jquery.cookie.js',array(),false,true);
		wp_enqueue_script('theme-picker', IAMD_FW_URL.'js/public/picker.js',array(),false,true);
	endif;

	wp_enqueue_script('fitvids-script', IAMD_FW_URL.'js/public/jquery.fitvids.js',array(),false,true);
	wp_enqueue_script('bx-script', IAMD_FW_URL.'js/public/jquery.bxslider.js',array(),false,true);
	wp_enqueue_script('toucheffects-script', IAMD_FW_URL.'js/public/toucheffects.js',array(),false,true);
	wp_enqueue_script('custom-script', IAMD_FW_URL.'js/public/custom.js',array(),false,true);
}

/** dttheme_seo_meta()
 * Objective:
 *		To generate meta tags based on the backend options.
 **/
add_action('wp_head', 'dttheme_seo_meta', 1);
function dttheme_seo_meta() {
	$status = dttheme_is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') || dttheme_is_plugin_active('wordpress-seo/wp-seo.php');
	if (!$status) :
		global $post;
		$output = "";
		$meta_description = '';
		$meta_keywords = '';

		if (is_feed())
			return;

		if (is_404() || is_search())
			return;

		# meta robots Noindex ,NoFollow
		if (is_category() && (dttheme_option('seo', 'use_noindex_in_cats_page'))) :
			$output .= '<meta name="robots" content="noindex,follow" />'."\r";
		elseif (is_archive() && (dttheme_option('seo', 'use_noindex_in_archives_page'))) :
			$output .= '<meta name="robots" content="noindex,follow" />'."\r";
		elseif (is_tag() && !(dttheme_option('seo', 'use_noindex_in_tags_archieve_page'))) :
			$output .= '<meta name="robots" content="noindex,follow" />'."\r";
		endif;
		#End

		### Meta Description ###
		if( is_home () || is_front_page () ):
			if ((get_option ( 'page_on_front' ) != 0) && (get_option ( 'page_on_front' ) == $post->ID)) :
				$meta_description = get_post_meta($post->ID, '_seo_description', true);;
			else:
				$meta_description = dttheme_option('onepage','seo-desc');	
			endif;	
		
		elseif (is_page()) :
			$meta_description = get_post_meta($post->ID, '_seo_description', true);
			if (empty($meta_description) && dttheme_option('seo', 'auto_generate_desc')) :
				$meta_description = substr(strip_shortcodes(strip_tags($post->post_content )), 0, 155);
			endif;
			#post
		elseif (is_singular() || is_single()) :
			$meta_description = get_post_meta($post->ID, '_seo_description', true);
			if (empty($meta_description) && dttheme_option('seo', 'auto_generate_desc')) :
				$meta_description = trim(substr(strip_shortcodes(strip_tags($post->post_content )), 0, 155));
			endif;
			#is_category()
		elseif (is_category()) :
			#$categories = get_the_category();
			#$meta_description = $categories[0]->description;
			$meta_description = strip_tags(category_description());
			#is_tag()
		elseif (is_tag()) :
			$meta_description = strip_tags(tag_description());
			#is_author
		elseif (is_author()) :
			$author_id = get_query_var('author');
			if (!empty($author_id)) :
				$meta_description = get_the_author_meta('description', $author_id);
			endif;
		endif;

		if (!empty($meta_description)) {
			$meta_description = trim(substr($meta_description, 0, 155));
			$meta_description = htmlspecialchars($meta_description);
			$output .= "<meta name='description' content='{$meta_description}' />\r";

		}
		### Meta Description End###


		if( is_home () || is_front_page () ):
			if ((get_option ( 'page_on_front' ) != 0) && (get_option ( 'page_on_front' ) == $post->ID)) :
				$meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);;
			else:
				$meta_keywords = dttheme_option('onepage','seo-keyword');	
			endif;
			
		elseif (is_page()) :
			$meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);
			#post
		elseif (is_singular() || is_single()) :
			$meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);

			#Use Categories in Keyword
			if (dttheme_option('seo', 'use_cats_in_meta_keword')) :
				$categories = get_the_category();
				$c = '';
				foreach ($categories as $category) :
					$c .= $category->name.',';
				endforeach;
				$c = substr(trim($c), "0", strlen(trim($c)) - 1);
				$meta_keywords = $meta_keywords.','.$c;
			endif;

			#Use Tags in Keyword
			if (dttheme_option('seo', 'use_tags_in_meta_keword')) :
				$posttags = get_the_tags();
				$ptags = '';
				if ($posttags) :
					foreach ($posttags as $posttag) :
						$ptags .= $posttag->name.',';
					endforeach;
					$ptags = substr(trim($ptags), "0", strlen(trim($ptags)) - 1);
					$meta_keywords = $meta_keywords.','.$ptags;
				endif;
			endif;

			#Archive
		elseif (is_archive()) :

			global $posts;
			$keywords = array();

			foreach ($posts as $post) :
				# If attachment then use parent post id
				$id = (is_attachment() ? $post->post_parent : (!empty($post->ID ) ? $post->ID : ''));

				$keywords_from_posts = get_post_meta($id, '_seo_keywords', true);
				if (!empty($keywords_from_posts)) :
					$traverse = explode(',', $keywords_from_posts);
					foreach ($traverse as $keyword) :
						$keywords[] = $keyword;
					endforeach;
				endif;

				#Use Tags in Keyword
				if (dttheme_option('seo', 'use_tags_in_meta_keword')) :
					$tags = get_the_tags($id);
					if ($tags && is_array($tags)) :
						foreach ($tags as $tag) :
							$keywords[] = $tag->name;
						endforeach;
					endif;
				endif;

				#Use categories in Keywords
				if (dttheme_option('seo', 'use_cats_in_meta_keword')) :
					$categories = get_the_category($id);
					foreach ($categories as $category) :
						$keywords[] = $category->cat_name;
					endforeach;
				endif;

			endforeach;

			# Make keywords lowercase
			$keywords = array_unique($keywords);
			$small_keywords = array();
			$final_keywords = array();
			foreach ($keywords as $word) :
				$final_keywords[] = strtolower($word);
			endforeach;

			if (!empty($final_keywords)) :
				$meta_keywords = implode(",", $final_keywords);
			endif;

			#search || 404 page
		elseif (is_404() || is_search()) :
			$meta_keywords = '';
		endif;
		if (!empty($meta_keywords)) {
			$output .= "\t<meta name='keywords' content='{$meta_keywords}'/>\r";
		}

		### Meta Keyword End###

		#Generate canonical_url
		if (dttheme_option('seo', 'use_canonical_urls')) :
			$url = dttheme_canonical();
			if ($url) {
				$output .= "<link rel='canonical' href='{$url}'/>\r";
			}
		endif;
		echo $output;
	endif;
}
### --- ****  dttheme_seo_meta() *** --- ###

add_action('wp_head', 'dttheme_appearance_load_fonts', 7);
/** dttheme_appearance_load_fonts()
 * Objective:
 *		To load google fonts based on appearance settings in admin panel.
 **/
function dttheme_appearance_load_fonts() {
	$custom_fonts = array();
	$output = $font = "";

	$subset = dttheme_option('general', 'google-font-subset');
	if ($subset) {
		$subset = strtolower(str_replace(' ', '', $subset));
	}

	#Menu Section
	$disable_menu = dttheme_option("appearance", "disable-menu-settings");
	if (empty($disable_menu)) :
		$font = dttheme_option("appearance", "menu-font");
		if (!empty($font)) :
			$font = str_replace(" ", "+", $font);
			array_push($custom_fonts, $font);
		endif;
	endif; #Menu Secion End

	#Body Section
	$disable_boddy_settings = dttheme_option("appearance", "disable-boddy-settings");

	if (empty($disable_boddy_settings)) :
		$font = dttheme_option("appearance", "body-font");
		$font = str_replace(" ", "+", $font);
		if (!empty($font)) :
			array_push($custom_fonts, $font);
		endif;
	endif;

	#Footer Section
	$disable_footer = dttheme_option("appearance", "disable-footer-settings");
	if (empty($disable_footer)) :
		$footer_title_font = dttheme_option("appearance", "footer-title-font");
		$footer_title_font = !empty($footer_title_font) ? str_replace(" ", "+", $footer_title_font) : NULL;
		if (!empty($footer_title_font)) :
			array_push($custom_fonts, $footer_title_font);
		endif;

		$footer_content_font = dttheme_option("appearance", "footer-content-font");
		$footer_content_font = !empty($footer_content_font) ? str_replace(" ", "+", $footer_content_font) : NULL;
		if (!empty($footer_content_font)) :
			array_push($custom_fonts, $footer_content_font);
		endif;

	endif; #Footer Section End

	#Typography Section
	$disable_typo = dttheme_option("appearance", "disable-typography-settings");
	if (empty($disable_typo)) :
		for ($i = 1; $i <= 6; $i++) :
			$font = dttheme_option("appearance", "H{$i}-font");
			if (!empty($font)) :
				$font = str_replace(" ", "+", $font);
				array_push($custom_fonts, $font);
			endif;
		endfor;
	endif; #Typography Section End

	#404 Section
	$disable_404_settings = dttheme_option("specialty", "disable-404-font-settings");
	if (empty($disable_404_settings)) :
		$font = dttheme_option("specialty", "message-font");
		if (!empty($font)) :
			$font = str_replace(" ", "+", $font);
			array_push($custom_fonts, $font);
		endif;
	endif;


	if (!empty($custom_fonts)) :
		$custom_fonts = array_unique($custom_fonts);
		$font = implode(":300,400,400italic,700%7C", $custom_fonts);
		$font .= ":300,400,400italic,700%7C";
	endif;
	
		$font .= "Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic%7CLato:300,400,700,300italic,400italic,700italic%7CBubblegum+Sans";
		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array('family' => $font, 'subset' => $subset);
		wp_enqueue_style('mytheme-google-fonts', add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ), array(), null);

}
### --- ****  dttheme_appearance_load_fonts() *** --- ###

add_action('wp_head', 'dttheme_appearance_css', 9);
/** dttheme_appearance_css()
 * Objective:
 *		To generate in-line style based on appearance settings in admin panel.
 **/
function dttheme_appearance_css() {
	$output = NULL;
	
	#Layout Section
	if(dttheme_option("appearance","layout") == "boxed"):
		if(dttheme_option("appearance","bg-type") == "bg-patterns"):
			$pattern = dttheme_option("appearance","boxed-layout-pattern");
			$pattern_repeat = dttheme_option("appearance","boxed-layout-pattern-repeat");
			$pattern_opacity = dttheme_option("appearance","boxed-layout-pattern-opacity");
			$disable_color = dttheme_option("appearance","disable-boxed-layout-pattern-color");
			$pattern_color =  dttheme_option("appearance","boxed-layout-pattern-color");
			$output .= "body { ";
				if(!empty($pattern))
					$output .= "background-image:url('".IAMD_FW_URL."theme_options/images/patterns/{$pattern}');"; 
						
				$output .= "background-repeat:$pattern_repeat;";
				if(empty($disable_color)){
					if(!empty($pattern_opacity)){
						$color = hex2rgb($pattern_color);
						$output .= "background-color:rgba($color[0],$color[1],$color[2],$pattern_opacity); ";
					}else{
						$output .= "background-color:$pattern_color;";
					}
				}
			$output .= "}\r\t";
		
		elseif(dttheme_option("appearance","bg-type") == "bg-custom"):
			$bg = dttheme_option("appearance","boxed-layout-bg");
			$bg_repeat = dttheme_option("appearance","boxed-layout-bg-repeat");
			$bg_opacity = dttheme_option("appearance","boxed-layout-bg-opacity");
			$bg_color =  dttheme_option("appearance","boxed-layout-bg-color");
			$disable_color = dttheme_option("appearance","disable-boxed-layout-bg-color");
			$bg_position =  dttheme_option("appearance","boxed-layout-bg-position");
			$output .= "body { ";
			if(!empty($bg)) {
				$output .= "background-image:url($bg);";
				$output .= "background-repeat:$bg_repeat;";
				$output .= "background-position:$bg_position;";
			}
			
			if(empty($disable_color)){
				if(!empty($bg_opacity)){	
					$color = hex2rgb($bg_color);
					$output .= "background-color:rgba($color[0],$color[1],$color[2],$bg_opacity);";
				}else{
					$output .= "background-color:$bg_color;";
				}
			}
			$output .= "}\r\t";
		endif;
	endif;
	#Layout Section
	
	#Top Bar Color
	$diaable_topbar = dttheme_option("appearance", 'disable-top-bar-colors');
	if( empty($diaable_topbar) ) {

		#Top Bar BG Color
		$topbar_bg_color = dttheme_option("appearance", "topbar-bg-color");
		if( !empty($topbar_bg_color) and $topbar_bg_color != "#"  ):
			$output .= "\r\t .top-bar { background-color:{$topbar_bg_color} } \r\t";
		endif;

		#Top Bar Text Color
		$topbar_text_color = dttheme_option("appearance", "topbar-text-color");
		if( !empty($topbar_text_color) and $topbar_text_color != "#"  ):
			$output .= "\r\t .top-bar ul li { color:{$topbar_text_color} } \r\t";
			$output .= "\r\t .top-bar ul.contact-details li, .top-bar ul.cart-details li { border-color:{$topbar_text_color} } \r\t";
		endif;

		#Top Bar Primary Color
		$topbar_primary_color = dttheme_option("appearance", "topbar-primary-color");
		if( !empty($topbar_primary_color) and $topbar_primary_color != "#"  ):
			$output .= "\r\t .top-bar a, .top-bar ul.cart-details li a { color:{$topbar_primary_color} } \r\t";
		endif;

		#Top Bar Secondary Color
		$topbar_secondary_color = dttheme_option("appearance", "topbar-secondary-color");
		if( !empty($topbar_secondary_color) and $topbar_secondary_color != "#"  ):
			$output .= "\r\t .top-bar a:hover, .top-bar ul.cart-details li a:hover { color:{$topbar_secondary_color} } \r\t";
		endif;

		#Top Bar Border Color
		$topbar_border_color = dttheme_option("appearance", "topbar-border-color");
		if( !empty($topbar_border_color) and $topbar_border_color != "#"  ):
			$output .= "\r\t .top-bar { border-color:{$topbar_border_color} } \r\t";
		endif;
	}#Top Bar Color
	
	#Headar Color
	$disable_header = dttheme_option("appearance", 'disable-header-settings');
	if( empty($disable_header) ){
		#Header BG Color
		$header_bg_color = dttheme_option("appearance", "header-bg-color");
		if( !empty($header_bg_color) and $header_bg_color != "#"  ):
			$output .= "\r\t #header, .main-menu-wrapper { background-color:{$header_bg_color} } \r\t";
		endif;
	}#Header Color End

	#Menu Section
	$disable_menu = dttheme_option("appearance", "disable-menu-settings");
	if (empty($disable_menu)) :
		$font_type = dttheme_option("appearance", "menu-font-type");
		$style = dttheme_option("appearance","menu-standard-font-style");
		
		if( !empty($font_type) ){
		#Menu Font: Standard
			$font = dttheme_option("appearance","menu-standard-font");
		} else {
		#Menu Font: Google
			$font = dttheme_option("appearance", "menu-font");
		}

		$size = dttheme_option("appearance", "menu-font-size");
		$primary_color = dttheme_option("appearance", "menu-primary-color");
		$secondary_color = dttheme_option("appearance", "menu-secondary-color");
		
		if (!empty($font) || (!empty($primary_color) and $primary_color != "#") || !empty($size)) :
		
			$output .= "  #main-menu ul.menu > li > a, #main-menu ul li.menu-item-simple-parent ul li a, .megamenu-child-container ul.sub-menu > li > ul li a, .megamenu-child-container > ul.sub-menu > li > a, .megamenu-child-container > ul.sub-menu > li > .nolink-menu { ";
				if (!empty($font)) { $output .= "font-family:{$font},sans-serif; ";	}
				
				if (!empty($primary_color) && ($primary_color != '#')) { $output .= "color:{$primary_color}; "; }
				
				if (!empty($size) and ($size > 0)) { $output .= "font-size:{$size}px; "; }

				if( !empty( $style ) ){ $output .= "font-style: {$style}"; }
			$output .= "}\r\t";

			if (!empty($size) and ($size > 0)) {  $size = $size-2 ;$output .= " #main-menu ul.sub-menu li a, #main-menu ul li.menu-item-simple-parent ul li a { font-size:{$size}px; }\r\t"; }

		endif;

		if (!empty($secondary_color) and $secondary_color != "#") :
		  $output .= "#main-menu ul li a:hover, #main-menu ul > li.current_page_item > a, #main-menu ul > li.current_page_ancestor > a, #main-menu ul > li.current-menu-item > a, #main-menu ul > li.current-menu-ancestor > a, #main-menu ul li.menu-item-simple-parent ul > li.current_page_item > a, #main-menu ul li.menu-item-simple-parent ul > li.current_page_ancestor > a, #main-menu ul li.menu-item-simple-parent ul > li.current-menu-item > a, #main-menu ul li.menu-item-simple-parent ul > li.current-menu-ancestor > a, #main-menu ul li.menu-item-simple-parent ul li a:hover, #main-menu ul li.menu-item-megamenu-parent:hover > a { ";
	  	  $output .= "color:{$secondary_color} !important; ";
		  $output .= "}\r\t";
		endif;
		
		$menu_bg_color = dttheme_option("appearance", "menu-bg-color");
		if( !empty($menu_bg_color) and $menu_bg_color != "#"  ):
			$output .= "\r\t #main-menu { background-color:{$menu_bg_color} } \r\t";
		endif;

		$smenu_bg_color = dttheme_option("appearance", "smenu-bg-color");
		if( !empty($smenu_bg_color) and $smenu_bg_color != "#"  ):
			$output .= "\r\t #main-menu ul li.menu-item-simple-parent ul, .megamenu-child-container  { background-color:{$smenu_bg_color} } \r\t";
		endif;

		#Menu BG
		$menu_bg = dttheme_option("appearance","menu-bg");
		if( !empty($menu_bg) ):
			$repeat = dttheme_option("appearance","menu-bg-repeat");
			$position = dttheme_option("appearance","menu-bg-position");
			$output .= "\r\t #main-menu, #main-menu ul li.menu-item-simple-parent ul, .megamenu-child-container { background-image:url({$menu_bg}); background-repeat:{$repeat}; background-position:{$position} } \r\t";
		endif;
	endif; #Menu Section End

	#Body Section
	$disable_boddy_settings = dttheme_option("appearance", "disable-boddy-settings");
	if (empty($disable_boddy_settings)) :
		$font_type = dttheme_option("appearance", "body-font-type");
		$style = dttheme_option("appearance","body-standard-font-style");

		if( !empty($font_type) ){
		#Body Font: Standard
			$body_font = dttheme_option("appearance","body-standard-font");
			
		} else {
		#Body Font: Google
			$body_font = dttheme_option("appearance", "body-font");
		}

		$body_font_size = dttheme_option("appearance", "body-font-size");
		$body_font_color = dttheme_option("appearance", "body-font-color");

		$body_primary_color = dttheme_option("appearance", "body-primary-color");
		$body_secondary_color = dttheme_option("appearance", "body-secondary-color");


		if (!empty($body_font) || (!empty($body_font_color) and $body_font_color != "#") || !empty($body_font_size)) :
			$output .= "body {";
			if (!empty($body_font)) {	$output .= "font-family:{$body_font} , sans-serif; ";  }

			if (!empty($body_font_color) && ($body_font_color != '#')) { $output .= "color:{$body_font_color}; "; }

			if (!empty($body_font_size)) {	$output .= "font-size:{$body_font_size}px; "; }
			
			if( !empty( $style ) ){ $output .= "font-style: {$style}";	}
			$output .= "}\r\t";
		endif;

		if ((!empty($body_primary_color) and $body_primary_color != "#") || (!empty($body_secondary_color) and $body_secondary_color != "#")) :
			if (!empty($body_primary_color) && ($body_primary_color != '#')) { 	$output .= "a { color:{$body_primary_color}; }"; }

			if (!empty($body_secondary_color) && ($body_secondary_color != '#')) {	$output .= "a:hover { color:{$body_secondary_color}; }";	}
		endif;
	endif; #Body Section End

	#Footer Section
	$disable_footer = dttheme_option("appearance", "disable-footer-settings");
	if (empty($disable_footer)) :

		#Footer Title
		$font_type = dttheme_option("appearance", "footer-title-font-type");
		$style = dttheme_option("appearance","footer-title-standard-font-style");
		
		if( !empty($font_type) ){
			#Footer Title Font : Standard Font
			$footer_title_font = dttheme_option("appearance","footer-title-standard-font");
		} else {
			#Footer Title Font : Google Font	
			$footer_title_font = dttheme_option("appearance", "footer-title-font");		
		}
		
		$footer_title_font_color = dttheme_option("appearance", "footer-title-font-color");
		$footer_title_font_size = dttheme_option("appearance", "footer-font-size");
		$footer_primary_color = dttheme_option("appearance", "footer-primary-color");
		$footer_secondary_color = dttheme_option("appearance", "footer-secondary-color");
		$footer_bg_color = dttheme_option("appearance", "footer-bg-color");
		$copyright_bg_color = dttheme_option("appearance", "copyright-bg-color");

		if (!empty($footer_title_font) || (!empty($footer_title_font_color) and $footer_title_font_color != "#") || !empty($footer_title_font_size)) :
			$output .= "h3.widgettitle, #footer h1, #footer h2, #footer h3, #footer h4, #footer h5, #footer h6, #footer h1 a, #footer h2 a, #footer h3 a, #footer h4 a, #footer h5 a, #footer h6 a, #footer .widget h3.widgettitle, #footer .widget h3.widgettitle a {";
			if (!empty($footer_title_font)) {	$output .= "font-family:{$footer_title_font}; ";	}

			if (!empty($footer_title_font_color) && ($footer_title_font_color != '#')) {	$output .= "color:{$footer_title_font_color}; ";	}

			if (!empty($footer_title_font_size)) {	$output .= "font-size:{$footer_title_font_size}px; ";	}
			
			if( !empty( $style ) ){ $output .= "font-style: {$style}";	}
			$output .= "}\r\t";
		endif;

		if ((!empty($footer_primary_color) and $footer_primary_color != "#") || (!empty($footer_secondary_color) and $footer_secondary_color != "#")) :
			if (!empty($footer_primary_color) && ($footer_primary_color != '#')) {
				$output .= "footer a, footer .footer-widgets-wrapper ul li a, footer .footer-widgets-wrapper .widget_categories ul li a, footer .footer-widgets-wrapper .widget.widget_recent_entries .entry-metadata .tags a, footer .footer-widgets-wrapper .categories a, .copyright a, footer .widget ul li:before, footer .entry-details .entry-metadata p a span { color:{$footer_primary_color} !important;; }";
			}

			if (!empty($footer_secondary_color) && ($footer_secondary_color != '#')) {
				$output .= "footer a:hover, footer .footer-widgets-wrapper h1 a:hover, footer .footer-widgets-wrapper h2 a:hover, footer .footer-widgets-wrapper h3 a:hover, footer .footer-widgets-wrapper h4 a:hover, footer .footer-widgets-wrapper h5 a:hover, footer .footer-widgets-wrapper h6 a:hover, footer .footer-widgets-wrapper ul li a:hover, footer .footer-widgets-wrapper .widget.widget_recent_entries .entry-metadata .tags a:hover, footer .footer-widgets-wrapper .categories a:hover, footer .copyright a:hover, footer .widget ul li:hover:before, footer .entry-details .entry-metadata p a:hover span { color:{$footer_secondary_color} !important; }";
			}
		endif;
		
		#Footer Content
		$font_type = dttheme_option("appearance","footer-content-font-type");
		$style = dttheme_option("appearance","footer-content-standard-font-style");
		if( !empty($font_type) ){
			#Footer Content Font : Standard Font
			$footer_content_font = dttheme_option("appearance","footer-content-standard-font");
		} else {
			#Footer Content Font : Google Font	
			$footer_content_font = dttheme_option("appearance", "footer-content-font");		
		}
		
		$footer_content_font_color = dttheme_option("appearance", "footer-content-font-color");
		$footer_content_font_size = dttheme_option("appearance", "footer-content-font-size");
		
		if (!empty($footer_content_font) || (!empty($footer_content_font_color) and $footer_content_font_color != "#") || !empty($footer_content_font_size)) :
			$output .= "footer, footer, footer .footer-widgets-wrapper .widget.widget_recent_entries .entry-metadata .author, footer .footer-widgets-wrapper .widget.widget_recent_entries .entry-meta .date, footer .footer-widgets-wrapper label, footer .footer-widgets-wrapper .widget ul li, footer .footer-widgets-wrapper .widget ul li:hover, .copyright, footer .footer-widgets-wrapper .widget.widget_recent_entries .entry-metadata .tags, footer .footer-widgets-wrapper .categories, footer .footer-widgets-wrapper .widget.widget_rss .rss-date, footer .footer-widgets-wrapper .widget.widget_rss cite, footer .widget #wp-calendar caption {";
			
			if (!empty($footer_content_font)) {	$output .= "font-family:{$footer_content_font} !important; ";	}

			if (!empty($footer_content_font_color) && ($footer_content_font_color != '#')) {	$output .= "color:{$footer_content_font_color} !important; ";	}

			if (!empty($footer_content_font_size)) {	$output .= "font-size:{$footer_content_font_size}px !important; ";	}
			
			if( !empty( $style ) ){ $output .= "font-style: {$style}";	}

			$output .= "}\r\t";

			if (!empty($footer_content_font_size)) {
				$output .= "footer .widget_categories ul li a, footer .widget.widget_archive ul li a { font-size:{$footer_content_font_size}px !important; } \r\t";
			}
		endif;
		
		if (!empty($footer_bg_color) and $footer_bg_color != "#") {		$output .= "footer .footer-widgets-wrapper { background-color: $footer_bg_color !important; }";	}

		if (!empty($copyright_bg_color) and $copyright_bg_color != "#") {	$output .= "footer .copyright { background-color: $copyright_bg_color !important; }"; }
	endif; #Footer Section End */

	#Typography Settings
	$disable_typo = dttheme_option("appearance", "disable-typography-settings");
	if (empty($disable_typo)) :
		for ($i = 1; $i <= 6; $i++) :
			$font_type = dttheme_option("appearance", "H{$i}-font-type");
			$style = dttheme_option("appearance","H{$i}-standard-font-style");
			
			if( !empty($font_type) ){
			#Menu Font: Standard
				$font = dttheme_option("appearance","H{$i}-standard-font");
			} else {
			#Menu Font: Google
				$font = dttheme_option("appearance", "H{$i}-font");
			}
			
			$color = dttheme_option("appearance", "H{$i}-font-color");
			$size = dttheme_option("appearance", "H{$i}-size");

			if (!empty($font) || (!empty($color) and $color != "#") || !empty($size)) :
				$output .= "H$i {";
				if (!empty($font)) {	$output .= "font-family:{$font}; ";	}

				if (!empty($color) && ($color != '#')) {	$output .= "color:{$color}; ";	}
				
				if (!empty($size)) { $output .= "font-size:{$size}px; "; }

				$output .= "}\r\t";
			endif;
		endfor;
	endif; #Typography Settings end 

	#404 Settings
	$disable_404_settings = dttheme_option("specialty", "disable-404-font-settings");
	if (empty($disable_404_settings)) :
		$font = dttheme_option("specialty", "message-font");
		$color = dttheme_option("specialty", "message-font-color");
		$size = dttheme_option("specialty", "message-font-size");

		if (!empty($font) || (!empty($color) and $color != "#") || !empty($size)) :
			$output .= "div.error-info { ";
			if (!empty($font)) {
				$output .= "font-family:{$font}; ";
			}

			if (!empty($color) && ($color != '#')) {
				$output .= "color:{$color}; ";
			}

			if (!empty($size)) {
				$output .= "font-size:{$size}px; ";
			}
			$output .= "}\r\t";

			$output .= "div.error-info h1, div.error-info h2, div.error-info h3,div.error-info h4,div.error-info h5,div.error-info h6 { ";
			if (!empty($font)) {
				$output .= "font-family:{$font}; ";
			}

			if (!empty($color) && ($color != '#')) {
				$output .= "color:{$color}; ";
			}
			$output .= "}\r\t";

		endif;
	endif; #404 Settings end 


	#custom CSS
	if (dttheme_option('integration', 'enable-custom-css')) :
		$css = dttheme_option('integration', 'custom-css');
		$output .= stripcslashes($css);
	endif; #custom CSS eND

	if (!empty($output)) :
		$output = "\r".'<style type="text/css">'."\r\t".$output."\r".'</style>'."\r";
		echo $output;
	endif;
}

function dttheme_slider_section($post_id) {
	$tpl_default_settings = get_post_meta($post_id, '_tpl_default_settings', TRUE);
	$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings : array();

	if (array_key_exists('show_slider', $tpl_default_settings) && array_key_exists('slider_type', $tpl_default_settings)) :

		echo '<!-- **Slider Section** -->';
		echo '<div id="slider">';

		echo '	<div id="slider-container">';
		if ($tpl_default_settings['slider_type'] === "layerslider") :

			$id = isset( $tpl_default_settings['layerslider_id'])? $tpl_default_settings['layerslider_id'] : "";
			$slider = !empty($id) ? do_shortcode("[layerslider id='{$id}']") : "";
			echo $slider;
		elseif ($tpl_default_settings['slider_type'] === "revolutionslider") :

			$id = isset($tpl_default_settings['revolutionslider_id']) ? $tpl_default_settings['revolutionslider_id'] : "";
			$slider = !empty($id) ? do_shortcode("[rev_slider $id]") : "";
			echo $slider;
		elseif( $tpl_default_settings['slider_type'] === "imageonly" ):
			
			$img = isset($tpl_default_settings['slider-image']) ? "<div class='slider-image-only'><img src='{$tpl_default_settings['slider-image']}' alt=''/></div>" : "";
			echo $img;
		endif;
		echo '</div>';
		echo '</div><!-- **Slider Section - End** -->';
	endif;
}


function dttheme_subtitle_section($id=0,$type,$settings = array() ){
	
	$class = $darkbg = $style = $title = $disable_breadcrumb = '';	
	
	if( $id > 0 ){

		$title = get_the_title($id);

		if( $type === "post" )
			$settings = '_dt_post_settings';
		elseif( $type === "page" )
			$settings = '_tpl_default_settings';
		elseif( $type === "dt_portfolios" )
			$settings = '_portfolio_settings';

		$settings = get_post_meta( $id, $settings, TRUE );
		$settings = is_array($settings) ? $settings : array();

		$darkbg = array_key_exists('dark-bg', $settings) ? "light-bg" : "";	
		
		$disable_breadcrumb = array_key_exists('disable-breadcrumb', $settings ) ? false : true;
		$class = array_key_exists('disable-breadcrumb', $settings ) ? "align-center" : "";


		$style = "";
		if( array_key_exists('sub-title-bg', $settings)  ) {
			$bg = $settings['sub-title-bg'];
			$position = array_key_exists('sub-title-bg-position', $settings) ? $settings['sub-title-bg-position'] :'center center';
			$repeat = array_key_exists('sub-title-bg-repeat', $settings) ? $settings['sub-title-bg-repeat'] :'repeat';
			$style  = "background-image:url($bg);";
			$style .= "background-repeat: $repeat;";
			$style .= "background-position:$position;";
			$style = "style='{$style}'";
		}
	}

	if ( !array_key_exists('show_slider', $settings) ) :
		echo '<!-- breadcrumb-section starts -->';
		echo "<div class='breadcrumb-section {$class} {$darkbg}' {$style}>";
		echo '	<div class="container">';
		echo "		<h1>{$title}</h1>";
					if( $disable_breadcrumb )
						new dttheme_breadcrumb;
		echo '	</div>';
		echo '</div><!-- breadcrumb-section ends -->';
	endif;	
}

function dttheme_custom_subtitle_section( $title ) {

	echo '<!-- breadcrumb-section starts -->';
	echo '<div class="breadcrumb-section">';
	echo '	<div class="container">';

				if ( is_front_page() && is_home() ) {
	echo "			<h1>".get_bloginfo('description')."</h1>";
				} elseif( is_post_type_archive('tribe_events') || is_tax('tribe_events_cat') || in_array('events-single', get_body_class()) || in_array('events-list', get_body_class()) || in_array('tribe-filter-live', get_body_class()) || in_array('tribe-events-week', get_body_class()) || in_array('tribe-events-day', get_body_class()) || in_array('tribe-events-map', get_body_class()) || in_array('tribe-events-photo', get_body_class()) || in_array('tribe-events-venue', get_body_class()) ) {
	echo 			get_events_title();
					new dttheme_events_breadcrumb;					
				} else {					
	echo "			<h1>{$title}</h1>";
					new dttheme_breadcrumb;
				}
	echo '	</div>';
	echo '</div><!-- breadcrumb-section ends -->';
}

function dttheme_bpress_subtitle(){
	global $bp;
	
	if ( !empty( $bp->displayed_user->fullname ) ) { // looking at a user or self
		$title =  bp_current_component() === "profile" ? __("Profile","dt_themes") : __("Member","dt_themes");
		$subtitle = strip_tags( $bp->displayed_user->userdata->display_name );
		$icon = "fa-user";
		$class = "dark-bg dt-bp-member-page";
		dttheme_custom_subtitle_section($title,$subtitle,$icon,$class);

	}elseif( bp_is_members_component() ) {
		dttheme_subtitle_section( $bp->pages->members->id , 'page' );
	}elseif( bp_is_activity_component() ){
		dttheme_subtitle_section( $bp->pages->activity->id , 'page' );
	}elseif( bp_current_component() === "groups" ) {
		dttheme_subtitle_section( $bp->pages->groups->id , 'page' );
	}elseif( bp_current_component() === "register" ) {
		dttheme_subtitle_section( $bp->pages->register->id , 'page' );
	}elseif( bp_current_component() === "activate" ) {
		dttheme_subtitle_section( $bp->pages->activate->id , 'page' );
	}else{
		bp_current_component();
	}	
}

/** dttheme_excerpt()
 * Objective:
 *		To produce the excerpt for the posts.
 **/
function dttheme_excerpt($limit = NULL) {
	$limit = !empty($limit) ? $limit : 10;
	
	

	$excerpt = explode(' ', get_the_excerpt(), $limit);
	$excerpt = array_filter($excerpt);
	
	if (!empty($excerpt)) {
		if (count($excerpt) >= $limit) {
			array_pop($excerpt);
			$excerpt = implode(" ", $excerpt).'...';
		} else {
			$excerpt = implode(" ", $excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
		return "<p>{$excerpt}</p>";
	}
}
### --- ****  dttheme_excerpt() *** --- ###

/** dttheme_custom_comments()
 * Objective:
 *		To customize the post/page comments view.
 **/
function dttheme_custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ($comment->comment_type ) :
	case 'pingback':
	case 'trackback':
		echo '<li class="post pingback">';
		echo "<p>";
		_e('Pingback:', 'dt_themes');
		comment_author_link();
		edit_comment_link(__('Edit', 'dt_themes'), ' ', '');
		echo "</p>";
		break;

	default:
	case '':
		echo "<li ";
		comment_class();
		echo ' id="li-comment-';
		comment_ID();
		echo '">';
		echo '<article class="comment" id="comment-';
		comment_ID();
		echo '">';

		echo '<header class="comment-author">';
		echo '<img class="item-mask" src="'.esc_url( get_template_directory_uri() ).'/images/author-hexa-bg.png" alt="" title="">';
		echo get_avatar($comment, 120);
		echo '</header>';

		echo '<section class="comment-details">';
		echo '	<div class="author-name">'; 
		echo 		comment_reply_link(array_merge($args, array('reply_text' => ucfirst(get_comment_author_link()) , 'depth' => $depth, 'max_depth' => $args['max_depth'])));
		echo '	</div>';
		echo '	<div class="commentmetadata">';
		echo 		get_comment_date('d M Y');
		echo '	</div>';
		echo '	<div class="comment-body">';
		echo '		<div class="comment-content">';
						comment_text();

						if ($comment->comment_approved == '0') :
							_e('Your comment is awaiting moderation.', 'dt_themes');
						endif;

						edit_comment_link(__('Edit', 'dt_themes'));						
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="reply">';
		echo 		comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'dt_themes').'<span class="fa fa-angle-double-right"></span>', 'depth' => $depth, 'max_depth' => $args['max_depth'])));
		echo '	</div>';

		echo '</section>';
		echo '</article><!-- .comment-ID -->';
		break;
	endswitch;
}
### --- ****  dttheme_custom_comments() *** --- ###

#BREADCRUMB
class dttheme_breadcrumb {
	var $options;

	function dttheme_breadcrumb(){
		
		$delimiter =  'fa '.dttheme_option('general', 'breadcrumb-delimiter');
		$this->options = array( 'before' => "<span class='${delimiter}' > ",'after' => ' </span>');
		$markup = $this->options['before'].$this->options['after'];
		
		global $post;
		
		echo '<div class="breadcrumb">				
					<a href="'.home_url().'">'.__('Home','dt_themes').'</a>';
				
			if( !is_front_page() && !is_home()) {
				echo $markup;
			}
			
			$output = $this->simple_breadcrumb_case($post);

		if ( is_page() || is_single() ) {
			echo '<span class="current">';
					the_title();
			echo "</span>";
			
		}elseif( $output !== NULL ){
			echo '<span class="current">'.$output."</span>";
		}else {
			$title =  (get_option( 'page_for_posts' ) > 0) ? get_the_title( get_option( 'page_for_posts' ))  :NULL;
			echo $markup;
			echo '<span class="current">'.$title."</span>";
		}
		echo "</div><!-- ** breadcrumb - End -->";
	}
	
	function simple_breadcrumb_case($der_post){
		$markup = $this->options['before'].$this->options['after'];
		if (is_page()){
			 if($der_post->post_parent) {
				 $my_query = get_post($der_post->post_parent);			 
				 $this->simple_breadcrumb_case($my_query);
				 $link = '<a href="'.get_permalink($my_query->ID).'">';
				 $link .= ''. get_the_title($my_query->ID) . '</a>'. $markup;
				 echo $link;
			 }
		return;	 	
		} 

		if(is_single()){
			$category = get_the_category();
			if (is_attachment()){
				$my_query = get_post($der_post->post_parent);			 
				$category = get_the_category($my_query->ID);
				if( isset($category[0])) {
					$ID = $category[0]->cat_ID;
					echo is_wp_error( $cat_parents = get_category_parents($ID, TRUE, $markup, FALSE) ) ? '' : $cat_parents;					
					previous_post_link("%link $markup");
				}
			}else{
				$postType = get_post_type();

				if($postType == 'post')	{
					$ID = $category[0]->cat_ID;
					echo get_category_parents($ID, TRUE,$markup, FALSE );
					
				} else if($postType == 'dt_portfolios') {
					global $post;
					$terms = get_the_term_list( $post->ID, 'dt_portfolio_entries', '', '$$$', '' );
					$terms =  array_filter(explode('$$$',$terms));
					if( !empty($terms)):
						echo $terms[0].$markup;
				    endif;
					
				} else if($postType == 'product') {
					global $post;
					$terms = get_the_term_list( $post->ID, 'product_cat', '', '$$$', '' );
					$terms =  array_filter(explode('$$$',$terms));
					if( !empty($terms)):
						echo $terms[0].$markup;
				    endif;
				}
			}
		return;
		}

		if(is_tax()){
			  $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			  return __("Archive for Term: ",'dt_themes').$term->name;
		}

		if(is_category()){
			$category = get_the_category(); 
			$i = $category[0]->cat_ID;
			$parent = $category[0]-> category_parent;
			if($parent > 0 && $category[0]->cat_name == single_cat_title("", false)){
				echo get_category_parents($parent, TRUE, $markup, FALSE);
			}
		return __("Archive for Category: ",'dt_themes').single_cat_title('',FALSE);
		}

		if(is_author()){
			$curauth = get_user_by('slug',get_query_var('author_name')) ;
			return __("Archive for Author: ",'dt_themes').$curauth->nickname;
		}

		if(is_tag()){ return __("Archive for Tag: ",'dt_themes').single_tag_title('',FALSE); }

		if(is_404()){ return __("LOST",'dt_themes'); }

		if(is_search()){ return __("Search",'dt_themes'); }	

		if(is_year()){ return get_the_time('Y'); }

		if(is_month()){
			$k_year = get_the_time('Y');
			echo "<a href='".get_year_link($k_year)."'>".$k_year."</a>".$markup;
			return get_the_time('F'); 
		}

		if(is_day() || is_time()){ 
			$k_year = get_the_time('Y');
			$k_month = get_the_time('m');
			$k_month_display = get_the_time('F');
			echo "<a href='".get_year_link($k_year)."'>".$k_year."</a>".$markup;
			echo "<a href='".get_month_link($k_year, $k_month)."'>".$k_month_display."</a>".$markup;
		return get_the_time('jS (l)'); 
		}
		
		if(is_post_type_archive('product')){
			return __('Products','dt_themes');
		}
	}
}
#END OF BREADCRUMB
####################################

class dttheme_events_breadcrumb {

	function dttheme_events_breadcrumb() {
		global $post, $wp_query;

		$delimiter =  'fa '.dttheme_option('general', 'breadcrumb-delimiter');
		$this->options = array( 'before' => "<span class='${delimiter}' > ",'after' => ' </span>');
		$markup = $this->options['before'].$this->options['after'];

		echo '<div class="breadcrumb">
				<a href="'.home_url().'">'.__('Home', 'dt_themes').'</a>';
		echo $markup;
		echo '<a href="'.tribe_get_events_link().'">'.__('Events', 'dt_themes').'</a>';

		if( tribe_is_month() && !is_tax() ) {
			
			echo $markup;
			echo '<span class="current">'.__('Events This Month', 'dt_themes').'</span>';
			
		} elseif( class_exists('TribeEventsPro') && tribe_is_week() ) {
			
			echo $markup;
			echo '<span class="current">'.__('Events This Week', 'dt_themes').'</span>';
			
		} elseif( class_exists('TribeEventsPro') && tribe_is_day() ) {
			
			echo $markup;
			echo '<span class="current">'.__('Events Today', 'dt_themes').'</span>';
			
		} elseif( class_exists('TribeEventsPro') && tribe_is_map() ) {
			
			echo $markup;
			echo '<span class="current">'.__('Upcoming Events', 'dt_themes').'</span>';
	
		} elseif( class_exists('TribeEventsPro') && tribe_is_photo() ) {
			
			echo $markup;
			echo '<span class="current">'.__('Upcoming Events', 'dt_themes').'</span>';
	
		} elseif( tribe_is_list_view() ) {
			
			echo $markup;
			echo '<span class="current">'.__('Upcoming Events', 'dt_themes').'</span>';
			
		} elseif (is_single()) {
	
			echo $markup;
			$post_title = $wp_query->post->post_title;
			echo '<span class="current">'.$post_title.'</span>';
					
		} elseif( tribe_is_month() && is_tax() ) { 
		
			$term_slug = $wp_query->query_vars['tribe_events_cat'];
			$term = get_term_by('slug', $term_slug, 'tribe_events_cat');
			$name = $term->name;
		
			echo $markup;
			echo '<span class="current">'.$name.'</span>';
	
		} elseif( is_tag() ) { 

			echo $markup;
			echo '<span class="current">'.single_tag_title('',FALSE).'</span>';
			
		}
		echo "</div><!-- ** breadcrumb - End -->";			
	}
}
#END OF EVENTS BREADCRUMB
####################################

#Event Titles
function get_events_title() {
	global $wp_query;
	$title = '';
	$date_format = apply_filters( 'tribe_events_pro_page_title_date_format', 'l, F jS Y' );

	if( tribe_is_month() && !is_tax() ) {
		$title = sprintf( __( 'Events for %s', 'tribe-events-calendar' ), date_i18n( 'F Y', strtotime( tribe_get_month_view_date() ) ) );

	} elseif( class_exists('TribeEventsPro') && tribe_is_week() ) {
		$title = sprintf( __('Events for week of %s', 'tribe-events-calendar-pro'), date_i18n( $date_format, strtotime( tribe_get_first_week_day($wp_query->get('start_date') ) ) ) );

	} elseif( class_exists('TribeEventsPro') && tribe_is_day() ) {
		$title = __( 'Events for', 'tribe-events-calendar-pro' ) . ' ' . date_i18n( $date_format, strtotime( $wp_query->get('start_date') ) );

	} elseif( class_exists('TribeEventsPro') && (tribe_is_map() || tribe_is_photo()) ) {

		if( tribe_is_past() ) {
			$title = __( 'Past Events', 'tribe-events-calendar-pro' );
		} else {
			$title = __( 'Upcoming Events', 'tribe-events-calendar-pro' );
		}

	} elseif( tribe_is_list_view() ) {
		$title = __('Upcoming Events', 'dt_themes');
			
	} elseif (is_single()) {
		$title = $wp_query->post->post_title;

	} elseif( tribe_is_month() && is_tax() ) { 
		$term_slug = $wp_query->query_vars['tribe_events_cat'];
		$term = get_term_by('slug', $term_slug, 'tribe_events_cat');
		$name = $term->name;
		$title = $name;

	} elseif( is_tag() ) { 
		$name = __('Tag Archives','dt_themes');;
		$title = $name;
	}
	echo '<h1>'.$title.'</h1>';
}

#MyTheme Color Picker
function dttheme_color_picker(){

	$patterns_url = IAMD_FW_URL."theme_options/images/pattern/";
	$skins_url = IAMD_BASE_URL."images/style-picker/";
	
	$patterns = "";
	$patterns_array =  dttheme_listImage( get_template_directory()."/images/style-picker/patterns/");
	
	foreach($patterns_array as $k => $v){
		$img = 	IAMD_BASE_URL."images/style-picker/patterns/".$k;
		$patterns .= '<li>';
		$patterns .= "<a data-image='{$k}' href='' title=''>";
		$patterns .= "<img src='$img' alt='$v' title='$v' width='30' height='30' />";
		$patterns .= '</a>';
		$patterns .= '</li>'; 
	}
	
	$colors = "";
	$skins = getFolders(get_template_directory()."/skins");
	if( $skins ) {
		foreach( $skins as $skin ):
			$img = 	$skins_url.$skin.".jpg";
			$colors .= '<li>';
			$colors .= '<a id="'.$skin.'" href="" title="">';
			$colors .= '<img src="'.$img.'" alt="color-'.$skin.'" title="'.$skin.'"/>';
			$colors .= '</a>';
			$colors .= '</li>';
		endforeach;
	}
	
	$str = '<!-- **DesignThemes Style Picker Wrapper** -->';
	$str .= '<div class="dt-style-picker-wrapper">';
	$str .= '	<a href="" title="" class="style-picker-ico"> <img class="rotating" src="'.IAMD_BASE_URL.'images/style-picker/picker-icon.png" alt="" title="" /> </a>';
	$str .= '	<div id="dt-style-picker">';
	$str .= '   	<h2>'.__('Select Your Style','dt_themes').'</h2>';
	
	$str .= '       <h3>'.__('Choose your layout','dt_themes').'</h3>';
	$str .= '		<ul class="layout-picker">';
	$str .= '       	<li> <a id="fullwidth" href="" title="" class="selected"> <img src="'.IAMD_BASE_URL.'images/style-picker/fullwidth.jpg" alt="" title="" /> </a> </li>';
	$str .= '       	<li> <a id="boxed" href="" title=""> <img src="'.IAMD_BASE_URL.'images/style-picker/boxed.jpg" alt="" title="" /> </a> </li>';
	$str .= '		</ul>';
	$str .= '		<div class="hr"> </div>';
	$str .= '		<div id="pattern-holder" style="display:none;">';
	$str .='			<h3>'.__('Patterns for Boxed Layout','dt_themes').'</h3>';
	$str .= '			<ul class="pattern-picker">';
	$str .= 				$patterns;
	$str .= '			</ul>';
	$str .= '			<div class="hr"> </div>';
	$str .= '		</div>';
	
	$str .= '		<h3>'.__('Color scheme','dt_themes').'</h3>';
	$str .= '		<ul class="color-picker">';
	$str .= 		$colors;
	$str .= '		</ul>';
	
	
	$str .= '	</div>';
	$str .= '</div><!-- **DesignThemes Style Picker Wrapper - End** -->';
	
echo $str;
}

# Data Validation
global $dt_allowed_html_tags;
$dt_allowed_html_tags = array(
	'a' => array('class' => array(), 'href' => array(), 'title' => array(), 'target' => array()),
	'abbr' => array('title' => array()),
	'address' => array(),
	'area' => array('shape' => array(), 'coords' => array(), 'href' => array(), 'alt' => array()),
	'article' => array(),
	'aside' => array(),
	'audio' => array('autoplay' => array(), 'controls' => array(), 'loop' => array(), 'muted' => array(), 'preload' => array(), 'src' => array()),
	'b' => array(),
	'base' => array('href' => array(), 'target' => array()),
	'bdi' => array(),
	'bdo' => array('dir' => array()), 
	'blockquote' => array('cite' => array()), 
	'br' => array(),
	'button' => array('autofocus' => array(), 'disabled' => array(), 'form' => array(), 'formaction' => array(), 'formenctype' => array(), 'formmethod' => array(), 'formnovalidate' => array(), 'formtarget' => array(), 'name' => array(), 'type' => array(), 'value' => array()),
	'canvas' => array('height' => array(), 'width' => array()),
	'caption' => array('align' => array()),
	'cite' => array(),
	'code' => array(),
	'col' => array(),
	'colgroup' => array(),
	'datalist' => array('id' => array()),
	'dd' => array(),
	'del' => array('cite' => array(), 'datetime' => array()),
	'details' => array('open' => array()),
	'dfn' => array(),
	'dialog' => array('open' => array()),
	'div' => array('class' => array(), 'id' => array(), 'align' => array()),
	'dl' => array(),
	'dt' => array(),
	'em' => array(),
	'embed' => array('height' => array(), 'src' => array(), 'type' => array(), 'width' => array()),
	'fieldset' => array('disabled' => array(), 'form' => array(), 'name' => array()),
	'figcaption' => array(),
	'figure' => array(),
	'form' => array('accept' => array(), 'accept-charset' => array(), 'action' => array(), 'autocomplete' => array(), 'enctype' => array(), 'method' => array(), 'name' => array(), 'novalidate' => array(), 'target' => array(), 'id' => array(), 'class' => array()),
	'h1' => array('class' => array()), 'h2' => array('class' => array()), 'h3' => array('class' => array()), 'h4' => array('class' => array()), 'h5' => array('class' => array()), 'h6' => array('class' => array()),
	'hr' => array(), 
	'i' => array('class' => array()), 
	'iframe' => array('name' => array(), 'seamless' => array(), 'src' => array(), 'srcdoc' => array(), 'width' => array()),
	'img' => array('alt' => array(), 'crossorigin' => array(), 'height' => array(), 'ismap' => array(), 'src' => array(), 'usemap' => array(), 'width' => array()),
	'input' => array('align' => array(), 'alt' => array(), 'autocomplete' => array(), 'autofocus' => array(), 'checked' => array(), 'disabled' => array(), 'form' => array(), 'formaction' => array(), 'formenctype' => array(), 'formmethod' => array(), 'formnovalidate' => array(), 'formtarget' => array(), 'height' => array(), 'list' => array(), 'max' => array(), 'maxlength' => array(), 'min' => array(), 'multiple' => array(), 'name' => array(), 'pattern' => array(), 'placeholder' => array(), 'readonly' => array(), 'required' => array(), 'size' => array(), 'src' => array(), 'step' => array(), 'type' => array(), 'value' => array(), 'width' => array(), 'id' => array(), 'class' => array()),
	'ins' => array('cite' => array(), 'datetime' => array()),
	'label' => array('for' => array(), 'form' => array()),
	'legend' => array('align' => array()), 
	'li' => array('type' => array(), 'value' => array(), 'class' => array()),
	'link' => array('crossorigin' => array(), 'href' => array(), 'hreflang' => array(), 'media' => array(), 'rel' => array(), 'sizes' => array(), 'type' => array()),
	'main' => array(), 
	'map' => array('name' => array()), 
	'mark' => array(), 
	'menu' => array('label' => array(), 'type' => array()),
	'menuitem' => array('checked' => array(), 'command' => array(), 'default' => array(), 'disabled' => array(), 'icon' => array(), 'label' => array(), 'radiogroup' => array(), 'type' => array()),
	'meta' => array('charset' => array(), 'content' => array(), 'http-equiv' => array(), 'name' => array()),
	'object' => array('form' => array(), 'height' => array(), 'name' => array(), 'type' => array(), 'usemap' => array(), 'width' => array()),
	'ol' => array('class' => array(), 'reversed' => array(), 'start' => array(), 'type' => array()),
	'p' => array('class' => array()), 
	'q' => array('cite' => array()), 
	'section' => array(), 
	'select' => array('autofocus' => array(), 'disabled' => array(), 'form' => array(), 'multiple' => array(), 'name' => array(), 'required' => array(), 'size' => array()),
	'small' => array(), 
	'source' => array('media' => array(), 'src' => array(), 'type' => array()),
	'span' => array('class' => array()), 
	'strong' => array(),
	'style' => array('media' => array(), 'scoped' => array(), 'type' => array()),
	'sub' => array(),
	'sup' => array(),
	'table' => array('sortable' => array()), 
	'tbody' => array(), 
	'td' => array('colspan' => array(), 'headers' => array()),
	'textarea' => array('autofocus' => array(), 'cols' => array(), 'disabled' => array(), 'form' => array(), 'maxlength' => array(), 'name' => array(), 'placeholder' => array(), 'readonly' => array(), 'required' => array(), 'rows' => array(), 'wrap' => array()),
	'tfoot' => array(),
	'th' => array('abbr' => array(), 'colspan' => array(), 'headers' => array(), 'rowspan' => array(), 'scope' => array(), 'sorted' => array()),
	'thead' => array(), 
	'time' => array('datetime' => array()), 
	'title' => array(), 
	'tr' => array(), 
	'track' => array('default' => array(), 'kind' => array(), 'label' => array(), 'src' => array(), 'srclang' => array()), 
	'u' => array(), 
	'ul' => array('class' => array()), 
	'var' => array(), 
	'video' => array('autoplay' => array(), 'controls' => array(), 'height' => array(), 'loop' => array(), 'muted' => array(), 'muted' => array(), 'poster' => array(), 'preload' => array(), 'src' => array(), 'width' => array()), 
	'wbr' => array(), 
);
# Data Validation END

# Move comment filed in comment form to bottom
add_filter( 'comment_form_fields', 'dt_move_comment_field_to_bottom' );
function dt_move_comment_field_to_bottom( $fields ) {
	
	$comment_field = $fields['comment'];
	
	unset( $fields['comment'] );
	
	$fields['comment'] = $comment_field;
	return $fields;
}?>
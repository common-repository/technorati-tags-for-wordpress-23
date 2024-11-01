<?php
/*
Plugin Name: Technorati Tags
Plugin URI: http://gormful.com/projects/wp23-technorati-tags/
Description: Returns a list of technorati tags associated to the post (http://www.technorati.com/tag/<tag>). Follows the same convention of listing tags native to WordPress 2.3. the function <code>the_techtags();</code> must be used within the loop.
Version: 1.2
Author: Will Garcia
Author URI: http://gormful.com
*/

/*  Copyright 2007  Will Garcia  (email : will@gormful.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function get_techtag_link( $tag_id ) {
	global $wp_rewrite;
	$taglink = $wp_rewrite->get_tag_permastruct();

	$tag = &get_term($tag_id, 'post_tag');
	if ( is_wp_error( $tag ) )
		return $tag;
	$slug = str_replace('-', '+', wp_specialchars( $tag->slug ));

	if ( empty($taglink) ) {
		$file = '/';
		$taglink = $file . 'tag/' . $slug ;
	} else {
		$taglink = str_replace('%tag%', $slug, $taglink);
	}
	return apply_filters('tag_link', $taglink, $tag_id);
}

function get_the_techtag_list( $before = '', $sep = '', $after = '' ) {
	$tags = get_the_tags();

	if ( empty( $tags ) )
		return false;

	$tag_list = $before;
	foreach ( $tags as $tag ) {
		$link = get_techtag_link($tag->term_id);
		if ( is_wp_error( $link ) )
			return $link;
		$tag_links[] = '<a href="http://www.technorati.com' . $link . '" rel="tag">' . $tag->name . '</a>';
	}

	$tag_links = join( $sep, $tag_links );
	$tag_links = apply_filters( 'the_techtags', $tag_links );
	$tag_list .= $tag_links;

	$tag_list .= $after;

	return $tag_list;
}

function the_techtags( $before = 'Technorati Tags: ', $sep = ', ', $after = '' ) {
	$return = get_the_techtag_list($before, $sep, $after);
	if ( is_wp_error( $return ) )
		return false;
	else
		echo $return;
}
?>
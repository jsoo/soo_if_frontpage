<?php

$plugin['name'] = 'soo_if_frontpage';
$plugin['version'] = '0.1.9';
$plugin['author'] = 'Jeff Soo';
$plugin['author_uri'] = 'http://ipsedixit.net/txp/';
$plugin['description'] = 'Check if page is a section front page';
$plugin['type'] = 0; 

@include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---

if(class_exists('\Textpattern\Tag\Registry')) {
	Txp::get('\Textpattern\Tag\Registry')
		->register('soo_if_frontpage');
}

function soo_if_frontpage ( $atts, $thing )
{
	$atts = lAtts(array(
		'section'	=>  '',
		'pg'		=> false,
	), $atts);
	
	global $s, $pg, $c, $q, $author, $month, $id, $p;
	
	if ( ! $section = $atts['section'] ) 
		$section = 'default';
		
	return parse(EvalElse($thing, 
		( $section == '*' or in_list($s, $section) ) and
		( ! $atts['pg'] or $pg < 2 ) and
		! ( $c or $q or $author or $month or $id or $p )
	));
}

# --- END PLUGIN CODE ---

if (0) {
?>
<!-- CSS SECTION
# --- BEGIN PLUGIN CSS ---
<style type="text/css">
div#sed_help pre {padding: 0.5em 1em; background: #eee; border: 1px dashed #ccc;}
div#sed_help h1, div#sed_help h2, div#sed_help h3, div#sed_help h3 code {font-family: sans-serif; font-weight: bold;}
div#sed_help h1, div#sed_help h2, div#sed_help h3 {margin-left: -1em;}
div#sed_help h2, div#sed_help h3 {margin-top: 2em;}
div#sed_help h1 {font-size: 2.4em;}
div#sed_help h2 {font-size: 1.8em;}
div#sed_help h3 {font-size: 1.4em;}
div#sed_help h4 {font-size: 1.2em;}
div#sed_help h5 {font-size: 1em;margin-left:1em;font-style:oblique;}
div#sed_help h6 {font-size: 1em;margin-left:2em;font-style:oblique;}
div#sed_help li {list-style-type: disc;}
div#sed_help li li {list-style-type: circle;}
div#sed_help li li li {list-style-type: square;}
div#sed_help li a code {font-weight: normal;}
div#sed_help li code:first-child {background: #ddd;padding:0 .3em;margin-left:-.3em;}
div#sed_help li li code:first-child {background:none;padding:0;margin-left:0;}
div#sed_help dfn {font-weight:bold;font-style:oblique;}
div#sed_help .required, div#sed_help .warning {color:red;}
div#sed_help .default {color:green;}
</style>
# --- END PLUGIN CSS ---
-->
<!-- HELP SECTION
# --- BEGIN PLUGIN HELP ---
 <div id="sed_help">

h1. soo_if_frontpage

 <div id="toc">

h2. Contents

* "Overview":#overview
* "Usage":#usage
* "Attributes":#attributes
* "Examples":#examples
** "Most restrictive case":#most
** "Least restrictive case":#least
* "History":#history

 </div>

h2(#overview). Overview

Similar to the @glx_if_frontpage@ tag from the no-longer-supported " @glx_if@ plugin":http://www.markupartist.com/files/glx_if.txt. Most of @glx_if@'s functionality is now available through core Txp tags, but not this (well, not without an absurd stack of conditional tags). To @glx_if_frontpage@ it adds @txp:else />@ compatibility, and a couple of attributes for more control.

h2(#usage). Usage

pre. <txp:soo_if_frontpage>
...
<txp:else />
...
</txp:soo_if_frontpage>

@soo_if_frontpage@ evaluates to @true@ if the current page context is:

* an article list, and;
* not search results, and;
* not a listing of articles by category, and;
* not a listing of articles by author, and;
* not a listing of articles by month, and;
* not an image page, and;
* in one of the sections listed in @section@ (defaults to the 'default' section only), and
* (optionally) a single-page list or the first page of a multi-page list, if the @pg@ attribute is set.

h3(#attributes). Attributes

None %(required)required%.

* @section@ _(Txp section name[s])_
Comma-separated list of allowed sections. Leave empty to restrict condition to the default (i.e. home) section. Use @section="*"@ to include all sections.
* @pg@ _(boolean)_ %(default)default% false
Whether or not to check for the "pg" URL query param (e.g., @http://my-site.com/?pg=2@).
Set @pg="1"@ to allow only single-page lists or the first page of a multi-page list.

h2(#examples). Examples

h3(#most). Most restrictive case

pre. <txp:soo_if_frontpage pg="1">
... Home page ONLY
<txp:else />
... any other page
</txp:soo_if_frontpage>

The above example returns true for the home page only. E.g., @http://example.com/@ will cause the tag to return true, but *any other standard Txp URL will return false*.

h3(#least). Least restrictive case

pre. <txp:soo_if_frontpage section="*">
... Home page, section front page, plus subsequent pages of a paginated list
<txp:else />
... Indivdiual article; individual image; category, author, date, or search result lists
</txp:soo_if_frontpage>

The above example returns true on the home page, any section front page, and any paginated variation thereof. E.g., these pages would all return true:
* @http://example.com/@
* @http://example.com/?pg=2@
* @http://example.com/news/@ (where "news" is a section name)
* @http://example.com/news/?pg=7@

h2(#history). Version History

h3. 0.1.9 (2017/02/13)

Txp 4.6 compatibility update

h3. 0.1.8 (1/14/2011)

Added check for global image context (i.e., the @p@ query parameter)

h3. 0.1.7 (1/4/2011)

Documentation updated with descriptive examples. _(Thanks to Andre D for the suggestion.)_

h3. 0.1.6 (10/7/2010)

More code cleaning, after I remembered that @$pretext@ is already extracted in global scope

h3. 0.1.5 (10/7/2010)

Documentation update, code cleaning

h3. 0.1.4 (10/6/2010)

New feature: @section="*"@ is a shortcut for specifying all sections (including the default section)

h3. 0.1.3 (6/22/2010)

Fixed bug: section front pages now treated correctly (thanks, Victor)

h3. 0.1.2 (7/2009)

Added author and month search to the conditions to check against

h3. 0.1.1 (7/2009)

Added @pg@ attribute; code and documentation cleaning; updated plugin template

h3. 0.1 (1/2008)

@soo_if_frontpage@ tag with @section@ attribute


 </div>
# --- END PLUGIN HELP ---
-->
<?php
}

?>
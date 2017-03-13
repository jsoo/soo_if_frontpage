<?php

$plugin['name'] = 'soo_if_frontpage';
$plugin['version'] = '0.1.9';
$plugin['author'] = 'Jeff Soo';
$plugin['author_uri'] = 'http://ipsedixit.net/txp/';
$plugin['description'] = 'Check if page is a section front page';
$plugin['type'] = 0; 
$plugin['allow_html_help'] = 1;

if (! defined('txpinterface')) {
    global $compiler_cfg;
    @include_once('config.php');
    @include_once($compiler_cfg['path']);
}

# --- BEGIN PLUGIN CODE ---

if(class_exists('\Textpattern\Tag\Registry')) {
    Txp::get('\Textpattern\Tag\Registry')
        ->register('soo_if_frontpage');
}

function soo_if_frontpage ($atts, $thing)
{
    $atts = lAtts(array(
        'section'   =>  '',
        'pg'        => false,
    ), $atts);
    
    global $s, $pg, $c, $q, $author, $month, $id, $p;
    
    if (! $section = $atts['section']) {
        $section = 'default';
    }
        
    return parse(EvalElse($thing, 
        ($section == '*' || in_list($s, $section)) &&
        (! $atts['pg'] || $pg < 2) &&
        ! ($c or $q or $author or $month or $id or $p)
    ));
}

# --- END PLUGIN CODE ---

?>

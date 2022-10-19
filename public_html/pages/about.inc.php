<?php
document::$snippets['title'] = array(language::translate('index:head_title', 'Online Site'), settings::get('site_name'));
$_page = new ent_view();
echo $_page->stitch('pages/about');

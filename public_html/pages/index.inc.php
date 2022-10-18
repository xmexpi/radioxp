<?php

document::$snippets['title'] = array(language::translate('index:head_title', 'Online Site'), settings::get('site_name'));
document::$snippets['description'] = language::translate('index:meta_description', '');
document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="' . document::href_ilink('') . '" />';
document::$snippets['head_tags']['opengraph'] = '<meta property="og:url" content="' . document::href_ilink('') . '" />' . PHP_EOL
  . '<meta property="og:type" content="website" />' . PHP_EOL
  . '<meta property="og:image" content="' . document::href_link(WS_DIR_APP . 'images/logotype.png') . '" />';
$_page = new ent_view();
$_page->snippets = array(
  'pages' => array(),
);
$pages_query = database::query(
  "select * from " . DB_TABLE_PAGES . " p
                                                  left join " . DB_TABLE_PAGES_INFO . " pi on (p.id = pi.page_id and pi.language_code = '" . database::input(language::$selected['code']) . "')
                                                  where status
                                                  and find_in_set('information', dock)
                                                  order by p.priority, pi.title LIMIT 4;"
);
while ($page = database::fetch($pages_query)) {
  $_page->snippets['pages'][$page['id']] = array(
    'id' => $page['id'],
    'title' => $page['title'],
    'content' => $page['content'],
    'link' => document::href_ilink('information', array('page_id' => $page['id'])),
  );
}


echo $_page->stitch('pages/index');

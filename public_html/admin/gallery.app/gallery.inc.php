<?php
if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;

document::$snippets['title'][] = language::translate('title_gallery', 'Galerija Radia');

breadcrumbs::add(language::translate('title_team', 'Galerija Radia'));

if (isset($_POST['enable']) || isset($_POST['disable'])) {

  try {
    if (empty($_POST['photos'])) throw new Exception(language::translate('error_must_select_Galerija Radia', 'You must select Galerija Radia'));

    foreach ($_POST['photos'] as $dj_id) {
      $dj = new ent_gallery($dj_id);
      $dj->data['status'] = !empty($_POST['enable']) ? 1 : 0;
      $dj->save();
    }

    notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
    header('Location: ' . document::link());
    exit;
  } catch (Exception $e) {
    notices::add('errors', $e->getMessage());
  }
}

// Table Rows
$photos = [];

$photos_query = database::query(
  "select * from " . DB_TABLE_PREFIX . "gallery
    order by status desc, priority, name;"
);

if ($_GET['page'] > 1) database::seek($photos_query, settings::get('data_table_rows_per_page') * ($_GET['page'] - 1));

$page_items = 0;
while ($dj = database::fetch($photos_query)) {
  $photos[] = $dj;
  if (++$page_items == settings::get('data_table_rows_per_page')) break;
}

// Number of Rows
$num_rows = database::num_rows($photos_query);

// Pagination
$num_pages = ceil($num_rows / settings::get('data_table_rows_per_page'));
?>

<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo language::translate('title_gallery', 'Galerija Radia'); ?>
  </div>

  <div class="panel-action">
    <ul class="list-inline">
      <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, ['doc' => 'edit_photo'], true), language::translate('title_add_photo', 'Dodaj Sliku'), '', 'add'); ?></li>
    </ul>
  </div>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('gallery_form', 'post'); ?>

    <table class="table table-striped table-hover data-table">
      <thead>
        <tr>
          <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle', 'data-toggle="checkbox-toggle"'); ?></th>
          <th></th>
          <th><?php echo language::translate('title_id', 'ID'); ?></th>
          <th class="main"><?php echo language::translate('title_name', 'Name'); ?></th>
          <th class="text-center"><?php echo language::translate('title_valid_from', 'Valid From'); ?></th>
          <th class="text-center"><?php echo language::translate('title_valid_to', 'Valid To'); ?></th>
          <th><?php echo language::translate('title_priority', 'Priority'); ?></th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($photos as $photo) { ?>
          <tr class="<?php echo empty($photo['status']) ? 'semi-transparent' : null; ?>">
            <td><?php echo functions::form_draw_checkbox('photos[]', $photo['id']); ?></td>
            <td><?php echo functions::draw_fonticon('fa-circle', 'style="color: ' . (!empty($photo['status']) ? '#88cc44' : '#ff6644') . ';"'); ?></td>
            <td><?php echo $photo['id']; ?></td>
            <td><a href="<?php echo document::href_link('', ['doc' => 'edit_photo', 'photo_id' => $photo['id']], true); ?>"><?php echo $photo['name']; ?></a></td>
            <td class="text-end"><?php echo !empty($photo['date_valid_from']) ? language::strftime(language::$selected['format_datetime'], strtotime($photo['date_valid_from'])) : '-'; ?></td>
            <td class="text-end"><?php echo !empty($photo['date_valid_to']) ? language::strftime(language::$selected['format_datetime'], strtotime($photo['date_valid_to'])) : '-'; ?></td>
            <td class="text-end"><?php echo $photo['priority']; ?></td>
            <td class="text-end"><a href="<?php echo document::href_link('', ['doc' => 'edit_photo', 'photo_id' => $photo['id']], true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
          </tr>
        <?php } ?>
      </tbody>

      <tfoot>
        <tr>
          <td colspan="9"><?php echo language::translate('title_gallery', 'Galerija Radia'); ?>: <?php echo $num_rows; ?></td>
        </tr>
      </tfoot>
    </table>

    <div class="btn-group">
      <?php echo functions::form_draw_button('enable', language::translate('title_enable', 'Enable'), 'submit', '', 'on'); ?>
      <?php echo functions::form_draw_button('disable', language::translate('title_disable', 'Disable'), 'submit', '', 'off'); ?>
    </div>

    <?php echo functions::form_draw_form_end(); ?>
  </div>

  <div class="panel-footer">
    <?php echo functions::draw_pagination($num_pages); ?>
  </div>
</div>
<?php

class ent_team
{
    public $data;
    public $previous;

    public function __construct($team_id = null)
    {

        if ($team_id !== null) {
            $this->load($team_id);
        } else {
            $this->reset();
        }
    }

    public function reset()
    {

        $this->data = [];

        $fields_query = database::query(
            "show fields from " . DB_TABLE_PREFIX . "team;"
        );

        while ($field = database::fetch($fields_query)) {
            $this->data[$field['Field']] = null;
        }


        $this->previous = $this->data;
    }

    public function load($team_id)
    {

        if (!preg_match('#^[0-9]+$#', $team_id)) throw new Exception('Invalid slide (ID: ' . $team_id . ')');

        $this->reset();

        $slide_query = database::query(
            "select * from " . DB_TABLE_PREFIX . "team
        where id = " . (int)$team_id . "
        limit 1;"
        );

        if ($slide = database::fetch($slide_query)) {
            $this->data = array_replace($this->data, array_intersect_key($slide, $this->data));
        } else {
            throw new Exception('Could not find slide (ID: ' . (int)$team_id . ') in database.');
        }

        $this->previous = $this->data;
    }

    public function save()
    {

        if (empty($this->data['id'])) {
            database::query(
                "insert into " . DB_TABLE_PREFIX . "team
          (date_created)
          values ('" . ($this->data['date_created'] = date('Y-m-d H:i:s')) . "');"
            );
            $this->data['id'] = database::insert_id();
        }

        database::query(
            "update " . DB_TABLE_PREFIX . "team
        set
          status = " . (int)$this->data['status'] . ",
          type = " . (int)$this->data['type'] . ",
          name = '" . database::input($this->data['name']) . "',
          caption = '" . database::input($this->data['caption']) . "',
          image = '" . database::input($this->data['image']) . "',
          priority = " . (int)$this->data['priority'] . ",
          date_valid_from = " . (empty($this->data['date_valid_from']) ? "null" : "'" . date('Y-m-d H:i:s', strtotime($this->data['date_valid_from'])) . "'") . ",
          date_valid_to = " . (empty($this->data['date_valid_to']) ? "null" : "'" . date('Y-m-d H:i:s', strtotime($this->data['date_valid_to'])) . "'") . ",
          date_updated = '" . ($this->data['date_updated'] = date('Y-m-d H:i:s')) . "'
        where id = " . (int)$this->data['id'] . "
        limit 1;"
        );



        $this->previous = $this->data;

        cache::clear_cache('team');
    }

    public function save_image($file)
    {

        if (empty($this->data['id'])) {
            $this->save();
        }

        if (!empty($this->data['image'])) {
            if (is_file(FS_DIR_APP . 'images/' . basename($this->data['image']))) {
                unlink(FS_DIR_APP . 'images/' . basename($this->data['image']));
            }
            $this->data['image'] = '';
        }

        // SVG
        if (preg_match('#^<svg#m', file_get_contents($file))) {
            $filename = 'team/' . functions::general_path_friendly($this->data['id'] . '-' . $this->data['name'], settings::get('store_language_code')) . '.svg';

            if (!file_exists(FS_DIR_APP . 'images/team/')) mkdir(FS_DIR_APP . 'images/team/', 0777);
            if (file_exists(FS_DIR_APP . 'images/' . $filename)) unlink(FS_DIR_APP . 'images/' . $filename);

            copy($file, FS_DIR_APP . 'images/' . $filename);

            // Image
        } else {
            $image = new ent_image($file);

            $filename = 'team/' . functions::general_path_friendly($this->data['id'] . '-' . $this->data['name'], settings::get('store_language_code')) . '.' . $image->type();

            if (!file_exists(FS_DIR_APP . 'images/team/')) mkdir(FS_DIR_APP . 'images/team/', 0777);
            if (file_exists(FS_DIR_APP . 'images/' . $filename)) unlink(FS_DIR_APP . 'images/' . $filename);

            $image->write(FS_DIR_APP . 'images/' . $filename);
        }

        database::query(
            "update " . DB_TABLE_PREFIX . "team
        set image = '" . database::input($filename) . "'
        where id = " . (int)$this->data['id'] . "
        limit 1;"
        );

        $this->previous['image'] = $this->data['image'] = $filename;
    }

    public function delete()
    {

        database::query(
            "delete from " . DB_TABLE_PREFIX . "team
        where id = " . (int)$this->data['id'] . "
        limit 1;"
        );

        if (!empty($this->data['image']) && file_exists(FS_DIR_APP . 'images/' . $this->data['image'])) {
            unlink(FS_DIR_APP . 'images/' . $this->data['image']);
        }

        $this->reset();

        cache::clear_cache('team');
    }
}

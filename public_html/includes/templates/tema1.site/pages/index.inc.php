{snippet:notices}
<div class="box2">

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_slides.inc.php'); ?>

  <div class="banners">
    <div class="row">
      <div class="span5 banner banner1">
        <div class="banner_inner"><a href="#"><img src="{snippet:template_path}images/banner1.jpg" alt="" class="img"></a></div>
      </div>
      <div class="span5 banner banner2">
        <div class="banner_inner"><a href="#"><img src="{snippet:template_path}images/banner2.jpg" alt="" class="img"></a></div>
      </div>
    </div>
  </div>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_team.inc.php'); ?>

  <div class="row">
    <div class="span7">
      <!-- <h2>Welcome to our online radio station</h2>

      <h4>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna. Ipsum dolor .</h4>

      <div class="thumb1">
        <div class="thumbnail clearfix">
          <figure class="img-polaroid"><img src="{snippet:template_path}images/home01.jpg" alt=""></figure>
          <div class="caption">
            <p>
              Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat consectetuer adipiscing elit. Nunc suscipit. Suspendisse enim arcu, convallis non, cursus sed, dignissim et, est Aenean semper aliquet libero. In ante velit, cursus ut, ultrices vitae, tempor ut, risus. Duis pulvinar. Vestibulum vel pede at sapien sodales mattis. Quisque pretium, lacus nec iaculis vehicula.
            </p>
            <a href="#" class="button1">Read More</a>
          </div>
        </div>
      </div> -->

      <h3><?php echo language::translate('xmexpi_desavanja', 'Novosti'); ?></h3>
      <?php foreach ($pages as $page) echo '<div class="date1">' . substr($page['title'], 0, 150) . '</div><div>' . substr($page['content'], 0, 300) . '</br><a href="' . htmlspecialchars($page['link']) . '">Procitaj Vise</a></div>' . PHP_EOL; ?>


    </div>
    <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_music_player.inc.php'); ?>
    <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_vip.inc.php'); ?>
  </div>
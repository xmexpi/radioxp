<div class="box5">
    <div id="slider3">
        <a class="prev3" href="#"><span></span></a>
        <a class="next3" href="#"><span></span></a>
        <div class="carousel-box row">
            <div class="inner span10">
                <div class="carousel main">
                    <ul>
                        <?php
                        foreach ($team as $key => $dj) {
                            echo '<li><div class="thumb-carousel"><div class="thumbnail clearfix"><a href="#">' . PHP_EOL;
                            echo '<figure><img src="' . document::href_link($dj['image']) . '" style="width: 178px; height: 178px;"></figure>' . PHP_EOL;
                            echo '<div class="caption">' . $dj['name'] . '</div>' . PHP_EOL;
                            echo '</a></div></div></li>' . PHP_EOL;
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
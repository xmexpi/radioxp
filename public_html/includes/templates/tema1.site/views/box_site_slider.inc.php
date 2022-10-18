<div id="slider_wrapper">
    <div id="slider" class="clearfix">
        <div id="camera_wrap">
            <?php
            foreach ($slides as $key => $slide) {
                echo '<div data-src="' . document::href_link($slide['image']) . '" alt="' . $slide['name'] . '" style="width: 100%;" />' . PHP_EOL;



                if (!empty($slide['caption'])) {
                    echo '<div class="camera_caption fadeFromRight"><div class="txt1"><span>' . $slide['caption'] . '</span></div><div class="txt2"><span>' . $slide['name'] . '</span></div></div> ' . PHP_EOL;
                }



                echo '</div>' . PHP_EOL;
            }
            ?>
        </div>
    </div>
</div>
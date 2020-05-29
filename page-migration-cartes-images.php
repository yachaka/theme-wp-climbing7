<?php

$query = new WP_Query([
  'nopaging' => 1,
]);

pre_var_dump(count($query->get_posts()));

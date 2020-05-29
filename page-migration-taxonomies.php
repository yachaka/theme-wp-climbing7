<?php

if (isset($_POST['migrer'])) {
  $term_id_migre = (int) $_POST['term_id'];
  $post_ids = explode(',', $_POST['post_ids']);
  $activites_terms = explode(',', $_POST['activites']);
  $lieux_terms = explode(',', $_POST['lieux']);

  foreach ($post_ids as $post_id) {
    if (count($activites_terms) > 0) {
      wp_set_object_terms(
        $post_id,
        $activites_terms,
        'activites',
      );
    }

    if (count($lieux_terms) > 0) {
      wp_set_object_terms(
        $post_id,
        $lieux_terms,
        'lieux',
      );
    }
  }

  wp_delete_term($term_id_migre, 'category');
}

$offset = isset($_GET['offset'])
  ? (int) $_GET['offset']
  : 0;

$term_to_migrate = array_values(get_terms([
  'taxonomy' => 'category',
  'number' => 1,
  'offset' => $offset,
]))[0];


$posts = (new WP_Query([
  'tax_query' => [
    [
      'taxonomy' => 'category',
      'terms' => $term_to_migrate->term_id,
    ]
  ]
]))->get_posts();

$posts_ids = array_map(
  function ($post) {
    return $post->ID;
  },
  $posts,
);


// pre_var_dump($posts);
?>

<h1>
  <?= $term_to_migrate->term_id ?>: <?= $term_to_migrate->name ?>
</h1>

<a href="<?= get_site_url() ?>/migration-taxonomies/?offset=<?= $offset + 1 ?>">
  Ignorer >>>>
</a>

<br />
<br />

<h4>Posts dans la catégorie</h4>

<?php
foreach ($posts as $post) {
?>
  <a href="<?= $post->guid ?>"><?= $post->post_title ?></a><br/>
<?php
}
?>

<br/><br/><br/>
<form method="post">
  <input type="hidden" name="post_ids" value="<?= join(',', $posts_ids) ?>" />
  <input type="hidden" name="term_id" value="<?= $term_to_migrate->term_id ?>" />

  Activités : <input type="text" name="activites" /><br />
  Lieux : <input type="text" name="lieux" /><br /><br />

  <input type="submit" name="migrer" value="Migrer" />
</form>

<html>
<head>
  <title>ACF Posts Migration</title>
  <link rel="stylesheet" href="<?= get_theme_root_uri() ?>/watson/tmp-migration-posts-acf/prism.css" />
  <script type="text/javascript" src="<?= get_theme_root_uri() ?>/watson/tmp-migration-posts-acf/prism.js"></script>
  <style type="text/css">
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    #header {
      background-color: #666;
      color: white;
      font-size: 18px;
      line-height: 22px;
      padding: 8px;
    }

    a {
      color: lightblue;
    }

    #header #actions {
      float: right;
    }

    #header #actions button, #header #actions a {
      display: inline-block;
      border: 0;
      font-size: 14px;
      color: black;
      background-color: #ddd;
      text-decoration: none;
      font-family: -apple-system;
      padding: 6px 10px;
    }

    #header #actions button.primary {
      background-color: blue;
      color: white;
    }

    .label-acf {
      font-size: 18px;
      margin-bottom: 12px;
    }

    .form-field.field-liens .fake-highlight-textarea,
    .form-field.field-liens textarea,
    .form-field.field-liens pre,
    .form-field.field-duree_approche .fake-highlight-textarea,
    .form-field.field-duree_approche textarea,
    .form-field.field-duree_approche pre,
    .form-field.field-duree_parcours .fake-highlight-textarea,
    .form-field.field-duree_parcours textarea,
    .form-field.field-duree_parcours pre,
    .form-field.field-duree_retour .fake-highlight-textarea,
    .form-field.field-duree_retour textarea,
    .form-field.field-duree_retour pre {
      height: 120px;
    }

    .form-field.field-carte_iframe .fake-highlight-textarea,
    .form-field.field-carte_iframe textarea,
    .form-field.field-carte_iframe pre {
      height: 390px;
    }

    .form-field.field-approche .fake-highlight-textarea,
    .form-field.field-approche textarea,
    .form-field.field-approche pre {
      height: 520px;
    }

    .form-field.field-parcours .fake-highlight-textarea,
    .form-field.field-parcours textarea,
    .form-field.field-parcours pre {
      height: 800px;
    }

    .form-field.post-content textarea,
    .form-field.post-content pre,
    .form-field.post-content .fake-highlight-textarea {
      height: 100%;
    }

    .form-field .fake-highlight-textarea {
      position: relative;
      height: 260px;
      width: 90%;
    }

    .form-field textarea, .form-field pre {
      border: 0;
      font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
      width: 100%;
      height: 260px;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: 0;
      font-size: 16px;
      line-height: 24px;
      display: block;
      padding: 6px;
      overflow-x: hidden;
      overflow-y: scroll;
      white-space: break-spaces;
    }

    .form-field textarea {
      z-index: 2;
      background-color: transparent;
      color: transparent;
      resize: none;
    }

    .form-field code {
      word-break: break-word;
      white-space: break-spaces;
    }

    body, #main_form {
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    #panneaux {
      flex: 1;
      display: flex;
      flex-direction: row;
      overflow: auto;
    }

    .panneau {
      padding: 20px;
      flex: 1;
      border-right: 3px solid #999;
    }

    .form-field {
      margin-bottom: 20px;
    }

    #historique {
      background-color: #666;
      height: 100vh;
      overflow: auto;
      font-size: 18px;
      line-height: 22px;
      color: #eee;
    }

    #historique .entree {
      display: flex;
      flex-direction: row;
      margin: 12px 0;
    }

    #historique .entree p {
      margin: 0 12px;
    }

    #historique .entree p.titre {
      flex: 1;
    }
  </style>
</head>
<body>
<?php

/*
 * Setup
 */
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Entities.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/CharacterReference.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Elements.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/Tokenizer.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/UTF8Utils.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/Scanner.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/TreeBuildingRules.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/EventHandler.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5/Parser/DOMTreeBuilder.php');
include(__DIR__ . '/tmp-migration-posts-acf/html5-php/src/HTML5.php');

$HTMLParser = new Masterminds\HTML5();

global $wpdb;

/*
 * Définitions variables et fonctions
 */
$duree_regex = '/[0-9]{1,2}h[0-9]{1,2}|[0-9]{1,2}([\'"])/';

function is_heading($node) {
  if ($node->nodeType === XML_ELEMENT_NODE
    && (in_array($node->nodeName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']))) {
    return true;
  }

  return false;
}

function is_potential_separator($node) {
  if ($node->nodeType === XML_ELEMENT_NODE
    && in_array($node->nodeName, ['strong'])) {
    return true;
  }

  return false;
}

function node_contains_text(
  $haystack,
  $needles,
  $required_score = 1
) {
  $score = 0;

  foreach($needles as $needle) {
    if ($needle[0] === '/') {
      if (preg_match($needle, $haystack) === 1) {
        $score += 1;
      }
    } else {
      if (stristr($haystack, $needle) !== false) {
        $score += 1;
      }
    }
  }

  return $score >= $required_score;
}

/*
 * Récupération ACF sur topo
 */
$acf_fields_rows = $wpdb->get_results("
  SELECT * FROM {$wpdb->prefix}posts
  WHERE post_parent = 27343
    AND post_type = 'acf-field'
  ORDER BY menu_order ASC
");

// Retrait de carte_image_1//carte_image_2//carte_image_3
$acf_fields_rows = array_filter($acf_fields_rows, function ($row) {
  if (in_array($row->post_excerpt, ['carte_image_1', 'carte_image_2', 'carte_image_3'])) {
    return false;
  }

  return true;
});

$acf_fields = [];
$acf_fields_name_to_key = [];

foreach ($acf_fields_rows as $acf_field_row) {
  $acf_fields[$acf_field_row->post_excerpt] = '';
  $acf_fields_name_to_key[$acf_field_row->post_excerpt] = $acf_field_row->post_name;
}

/*
 * Sauvegarde de la migration lors de la soumissions du formulaire
 */
if (isset($_POST['ignore'])
  || isset($_POST['migrate'])) {
  if (isset($_POST['ignore'])) {
    // Ignorer. Marquer comme migrer et marquer que le post n'a pas été reellement migré

    $wpdb->query(
      $wpdb->prepare("
        INSERT INTO tmp_posts_migres_vers_acf (post_id, migre_ou_non, post_title, at, post_permalink)
        VALUES (%d, 0, %s, NOW(), %s)
      ", [$_POST['post_id'], $_POST['post_title'], $_POST['post_permalink']])
    );
  }

  if (isset($_POST['migrate'])) {
    // Migrer les données du post

    foreach ($_POST['nouveau_acf_field'] as $field_name => $new_field_value) {
      if (isset($acf_fields_name_to_key[$field_name])) {
        $field_key = $acf_fields_name_to_key[$field_name];
      } else {
        $result = $wpdb->get_results("
          SELECT * FROM posts
          WHERE post_excerpt = 'carte_image_1'
            AND post_type = 'acf-field'
        ", ARRAY_A)[0];
        $field_key = $result['post_name'];
      }

      update_field(
        $field_key,
        $new_field_value,
        $_POST['post_id']
      );
    }

    // Marquer
    $wpdb->query(
      $wpdb->prepare("
        INSERT INTO tmp_posts_migres_vers_acf (post_id, migre_ou_non, post_title, at, post_permalink)
        VALUES (%d, 1, %s, NOW(), %s)
      ", [$_POST['post_id'], $_POST['post_title'], $_POST['post_permalink']])
    );
  }
}

/*
 * Récupération du post à migrer
 */
$posts_deja_migres = $wpdb->get_results("
  SELECT * FROM tmp_posts_migres_vers_acf
  ORDER BY at DESC
", ARRAY_A);

/*
 * Affichage historique
 */
if (isset($_GET['historique'])) {
?>
  <div id="historique">
    <?php
    foreach ($posts_deja_migres as $entree) {
    ?>
      <div class="entree">
        <p>Post ID: <?= $entree['post_id'] ?></p>
        <p class="titre">
          <a href="<?= $entree['post_permalink'] ?>">
            <?= $entree['post_title'] ?>      
          </a>
        </p>
        <p>
          <?php
          if ($entree['migre_ou_non'] === '1') {
            echo 'migré';
          } else {
            echo 'IGNORÉ';
          }
          ?> à <?= $entree['at'] ?>
        </p>
      </div>
    <?php
    }
    ?>
  </div>
</body>
</html>
<?php
  exit(0);
}

$posts_ID_deja_migres = array_map(
  function ($entree) {
    return $entree['post_id'];
  },
  $posts_deja_migres,
);

$offset = isset($_GET['offset'])
  ? (int) $_GET['offset']
  : 0;

$query_args = [
  'post__not_in' => $posts_ID_deja_migres,
  'posts_per_page' => 1,
  'offset' => $offset,
];

if (isset($_GET['post_id'])) {
  $query_args['p'] = $_GET['post_id'];
  $query_args['offset'] = 0;
  unset($query_args['post__not_in']);
}

$query = new WP_Query($query_args);

$total = count((new WP_Query([
  'nopaging' => true,
  'fields' => 'ids',
]))->get_posts());


$post_a_migrer = $query->the_post();

/*
 * Boucle principale
 * Récupération des valeurs des ACF depuis le post_content
 */
function collectInnerNodes($node) {
  $html = '';

  foreach ($node->childNodes as $childNode) {
    if ($childNode->nodeType === XML_ELEMENT_NODE
      && $childNode->tagName === 'h3'
      && trim($childNode->textContent) === '') {
      continue ;
    }

    $html .= $node->ownerDocument->saveHTML($childNode);
  }

  return $html;
}

function walkNode(&$node, $fn) {
  if ($node) {
    $ret = $fn($node);

    if ($ret === 'remove_from_body') {

      $cursor = $node;
      while ($cursor->parentNode->tagName !== 'body') {
        $cursor = $cursor->parentNode;
      }
      $next = $cursor->nextSibling;

      // pre_var_dump($cursor);
      $cursor->parentNode->removeChild($cursor);

      walkNode($next, $fn);
    } else {
      if ($node->childNodes) {
        walkNode($node->firstChild, $fn);
      }

      if ($node->nextSibling) {
        walkNode($node->nextSibling, $fn);
      }
    }
  }
}

function deleteMultipleLineBreaks($string) {
  return trim(preg_replace('/\n\n+/', "\n\n", $string));
}


$acf_fields_fn = [
  'presentation' => (function ($body) {
    $node = $body->firstChild;
 
    if ($node->nodeType === XML_ELEMENT_NODE) {

      return [
        'presentation' => deleteMultipleLineBreaks(collectInnerNodes($node)),
      ];
    }

    return [
      'presentation' => deleteMultipleLineBreaks($node),
    ];
  }),

  'fiche_technique' => (function ($body) {
    $html = '';
    foreach ($body->childNodes as $node) {
      if ($node->nodeType === XML_ELEMENT_NODE) {
        if ($html !== '') {
          $html .= "<br/>\n";
        }

        $html .= deleteMultipleLineBreaks(collectInnerNodes($node));
      } else {
        $html .= deleteMultipleLineBreaks($node->textContent);
      }
    }

    return [
      'fiche_technique' => $html,
    ];
  }),

  'acces_au_site' => (function ($body) {
    $node = $body->firstChild;

    if (!$node) {
      return [];
    }

    $html = '';
    foreach ($body->childNodes as $node) {
      if ($node->nodeType === XML_ELEMENT_NODE) {
        if ($html !== '') {
          $html .= "<br/>\n<br/>\n";
        }

        $html .= deleteMultipleLineBreaks(collectInnerNodes($node));
      } else {
        $html .= deleteMultipleLineBreaks($node->textContent);
      }
    }

    return [
      'acces_au_site' => $html,
    ];
  }),

  'texte_carte' => (function ($body) use (&$wpdb) {
    $fieldNode = $body->firstChild;
    $imgs_found = [];

    $nodes_to_remove = [];


    walkNode($fieldNode, function (&$node) use (&$imgs_found, &$fieldNode, &$wpdb) {
      if ($node->nodeType === XML_ELEMENT_NODE
        && $node->tagName === 'h4'
        && $node->textContent === 'Description du parcours'
      ) {
        return 'remove_from_body';
      }

      if ($node->nodeType === XML_ELEMENT_NODE
        && $node->tagName === 'img')  {
        $IMG_ID = null;


        $class = $node->attributes->getNamedItem('class');
        $ID_depuis_la_classe_regex = '/wp-image-([0-9]+)/';
        $ID_matches = [];

        // Find IMG ID
        if ($class && preg_match($ID_depuis_la_classe_regex, $class->value, $ID_matches)) {
          $IMG_ID = $ID_matches[1];
        }

        if (!$IMG_ID) {
          // Find with DB
          $src = $node->attributes->getNamedItem('src')->value;
          $src = preg_replace('/-[0-9]{3,4}x[0-9]{3,4}/', '', $src);

          $result = $wpdb->get_results("
            SELECT * FROM posts
            WHERE guid = '$src'
          ", ARRAY_A)[0];
          $IMG_ID = $result['ID'];
        }

        if ($IMG_ID === null) {
          echo 'IMG ID pas trouvé.<br/>';
          pre_var_dump(htmlentities($node->ownerDocument->saveHTML($node)));
        } else {
          // Add IMG ID to array
          $imgs_found[] = $IMG_ID;

          return 'remove_from_body';
        }
      }
    });

    $return_array = [
      'texte_carte' => deleteMultipleLineBreaks(collectInnerNodes($body)),
    ];

    foreach ($imgs_found as $index => $img_id) {
      $nom_acf_carte = 'carte_image_' . ($index + 1);
      $return_array[$nom_acf_carte] = $img_id;
    }

    // pre_var_dump($return_array);
    if (trim($return_array['texte_carte']) === '<br></br>') {
      $return_array['texte_carte'] = '';
    }

    return $return_array;
  }),

  'carte_iframe' => (function ($body) {
    $firstChild = $body->firstChild;

    if (!$firstChild) {
      return [];
    }

    walkNode($firstChild, function ($node) {
      if ($node->nodeType === XML_TEXT_NODE) {
        $searches = ['[iframe', '[googlemaps'];
        $found = false;

        foreach ($searches as $search) {
          if (strstr($node->textContent, $search) !== false) {
            $found = $search;
            break;
          }
        }

        if ($found) {
          if ($found === '[iframe') {
            $node->textContent = preg_replace('/width="677px"/', 'width="100%"', $node->textContent);
            $node->textContent = preg_replace('/width="677"/', 'width="100%"', $node->textContent);
            $node->textContent = preg_replace('/&scrollWheelZoom=true/', '&scrollWheelZoom=false', $node->textContent);
          } else if ($found === '[googlemaps') {
            $node->textContent = preg_replace('/&w=677/', '&w=800', $node->textContent);
          }
        }
      }
    });

    return [
      'carte_iframe' => collectInnerNodes($body),
    ];
  }),

  'approche' => (function ($body) {
    $firstChild = $body->firstChild;
    
    if (!$firstChild) {
      return [];
    }

    return [
      'approche' => deleteMultipleLineBreaks(collectInnerNodes($body)),
    ];
  }),

  'parcours' => (function ($body) {
    $firstChild = $body->firstChild;
    
    if (!$firstChild) {
      return [];
    }

    return [
      'parcours' => deleteMultipleLineBreaks(collectInnerNodes($body)),
    ];
  }),

  'retour' => (function ($body) {
    $firstChild = $body->firstChild;
    
    if (!$firstChild) {
      return [];
    }

    $video = null;

    walkNode($body, function ($node) use (&$video) {
      if ($node->nodeType === XML_ELEMENT_NODE) {
        $class = $node->attributes->getNamedItem('class');

        if ($class && strstr($class->value, 'wp-block-embed-youtube') !== false) {
          walkNode($node, function ($maybeIframe) use (&$video) {
            if ($maybeIframe->nodeType === XML_ELEMENT_NODE
              && $maybeIframe->tagName === 'iframe') {
              $video = $maybeIframe->ownerDocument->saveHTML($maybeIframe);
            }
          });

          return 'remove_from_body';
        }
      }
    });

    $return_array = [
      'retour' => deleteMultipleLineBreaks(collectInnerNodes($body)),
    ];

    if ($video) {
      $return_array['video'] = $video;
    }

    return $return_array;
  }),

  'galerie' => (function ($body) use (&$wpdb) {
    $firstChild = null;

    foreach ($body->childNodes as $childNode) {
      if ($childNode->nodeType === XML_ELEMENT_NODE) {
        $firstChild = $childNode;
        break;
      }
    }

    if (!$firstChild) {
      return [];
    }

    if ($firstChild->nodeType === XML_ELEMENT_NODE
      && $firstChild->tagName === 'div'
      && $firstChild->firstChild->nodeType === XML_TEXT_NODE
      && strstr($firstChild->firstChild->textContent, '[gallery') !== false) {
      $body->replaceChild($firstChild->firstChild, $firstChild);
    }
    else if (
      $firstChild->tagName === 'figure'
      && strstr(
        $firstChild->ownerDocument->saveHTML($firstChild),
        'dgwt-jg-item'
      ) !== false
    ) {

      $html = $body->ownerDocument->saveHTML($body);
      $matches = [];
      $found = preg_match_all('/img src="(.*?)"/', $html, $matches);
      $matches_transformed = array_map(
        function ($url) {
          // Hacks
          // if ($url === 'http://climbing7.com/wp-content/uploads/2014/08/14-c3a7a-tire-dans-les-bras-e1560953751672.jpg') {
          //   return "'http://climbing7.com/wp-content/uploads/2014/08/14-c3a7a-tire-dans-les-bras.jpg'";
          // } else if ($url === 'http://climbing7.com/wp-content/uploads/2014/07/17-couloirs-e1560588640767.jpg') {
          //   return "'http://climbing7.com/wp-content/uploads/2014/07/17-couloirs.jpg'";
          // } else if ($url === 'http://climbing7.com/wp-content/uploads/2014/07/17-contournement-2de-cascade-dc3a9licat-e1560608525331.jpg') {
          //   return "'http://climbing7.com/wp-content/uploads/2014/07/17-contournement-2de-cascade-dc3a9licat.jpg'";
          // } else if ($url === 'http://climbing7.com/wp-content/uploads/2014/04/18-rappel-cheminc3a9e-e1560676361688.jpg') {
          //   return "'http://climbing7.com/wp-content/uploads/2014/04/18-rappel-cheminc3a9e.jpg'";
          // }

          if ($url === 'http://climbing7.com/wp-content/uploads/2013/08/6-passer-sous-le-tronc-e1471088201542.jpg') {
            return $url;
          } else {
            $url = preg_replace('/-e[0-9]{10,}/', '', $url);
          }

          return "$url";
        },
        $matches[1],
      );
      $where_str = array_map(function ($url) { return "'$url'"; }, $matches_transformed);
      $where_str = join(',', $where_str);

      $results = $wpdb->get_results("
        SELECT ID, guid FROM posts
        WHERE guid IN ($where_str)
      ", ARRAY_A);

      $img_guids = array_map(function ($row) { return $row['guid']; }, $results);
      $img_ids = array_map(function ($row) { return $row['ID']; }, $results);
      if (count($results) !== count($matches[1])) {
        pre_var_dump([
          'text' => 'Oops. Mauvais compte.',
          'm' => count($matches_transformed),
          'c' => count($results),
          'diff' => array_diff($matches_transformed, $img_guids),
        ]);
      }

      $gallery_shortcode_ids_str = join(',', $img_ids);
      
      return [
        'galerie' => "[gallery ids=\"$gallery_shortcode_ids_str\"]",
      ];
    }

    return [
      'galerie' => collectInnerNodes($body),
    ];
  }),
];

// Éxecution
$post_id = get_the_ID();

$actual_field_values = [];
$new_fields_values = [];

foreach ($acf_fields_fn as $field_name => $fn) {
  $field_value = get_field($field_name, $post_id);
  $actual_field_values[$field_name] = $field_value;

  $field_DOM = $HTMLParser->loadHTML("<!DOCTYPE html><html><body>". $field_value ."</body></html>");
  $field_DOM_node = $field_DOM->childNodes[1]->firstChild;

  $fn_return = $fn($field_DOM_node);

  foreach ($fn_return as $new_field_key => $new_field_value) {
    $new_fields_values[$new_field_key] = $new_field_value;
  }

  // pre_var_dump([
  //   'nom' => $field_name,
  //   'ancienne_valeur' => htmlentities($field_value),
  //   'nouvelle_valeur' => $new_fields_values,
  // ]);
}

$changed_fields = [];

foreach ($new_fields_values as $new_field_key => $new_field_value) {
  // if ($new_field_key === 'presentation') {
  //   pre_var_dump([
  //     'avant' => htmlentities($actual_field_values[$new_field_key]),
  //     'apres' => $new_field_value,
  //   ]);
  // }

  if (!isset($actual_field_values[$new_field_key])
    || $actual_field_values[$new_field_key] !== $new_field_value) {
    $changed_fields[] = $new_field_key;
  }
}

/*
 * Affichage des champs textarea
 */
?>

<form id="main_form" method="post">
  <input type="hidden" name="post_id" value="<?= get_the_ID() ?>" />
  <input type="hidden" name="post_title" value="<?= get_the_title() ?>" />
  <input type="hidden" name="post_permalink" value="<?= the_permalink() ?>" />

  <div id="header">
    <div id="actions">
      <a
        href="<?= get_site_url() ?>/migration-posts-acf/?offset=<?= $offset + 1 ?>"
      >
        Ignorer pour l'instant
      </a>

      <button type="submit" name="ignore">
        Ignorer définitivement
      </button>

      <button class="primary" type="submit" name="migrate">
        Migrer
      </button>
    </div>

    (Avancement: <?= count($posts_ID_deja_migres) ?>/<?= $total ?>) En cours de migration de
    <a href="<?= the_permalink() ?>" target="_blank">
      <?= the_title() ?>
    </a>

    <br/><a href="<?= get_site_url() ?>/migration-posts-acf/?historique=1">
      Historique des migrations
    </a>

  </div>
  <div id="panneaux" autocomplete="off">

    <!-- Avant -->
    <div class="panneau">
      <?php
      foreach($changed_fields as $field_name) {
        if (!isset($actual_field_values[$field_name])) {
          continue;
        }

        $field_value = $actual_field_values[$field_name];
        $value = str_replace('<br></br>', "<br/>\n", trim($field_value));
      ?>
        <div class="form-field field-<?= $field_name ?>">
          <h4 class="label-acf"><?= $field_name ?></h4>

          <div class="fake-highlight-textarea">
            <textarea><?=
              $value
            ?></textarea>
            <pre><code class="language-html"><?=
              str_replace('&amp;', '&', htmlentities($value))
            ?></code><div style="height: 4px;"></div></pre>
          </div>
        </div>
      <?php
      }
      ?>
    </div>

    <!-- Après -->
    <div class="panneau">
      <?php
      foreach($changed_fields as $field_name) {
        $field_value = $new_fields_values[$field_name];
        $value = str_replace('<br></br>', "<br/>", trim($field_value));
      ?>
        <div class="form-field field-<?= $field_name ?>">
          <h4 class="label-acf"><?= $field_name ?></h4>

          <div class="fake-highlight-textarea">
            <textarea name="nouveau_acf_field[<?= $field_name ?>]"><?=
              $value
            ?></textarea>
            <pre><code class="language-html"><?=
              str_replace('&amp;', '&', htmlentities($value))
            ?></code><div style="height: 4px;"></div></pre>
          </div>
        </div>
      <?php
      }
      ?>
    </div>

  </div>
</form>

  <script type="text/javascript">
    var form_fields = document.getElementsByClassName('form-field');

    for (var i = 0; i < form_fields.length; i++) {
      var form_field = form_fields[i];

      (function () {
        var textarea = form_field.querySelector('textarea');
        var highlightedPre = form_field.querySelector('pre');
        var highlightedCode = form_field.querySelector('code');

        textarea.addEventListener('scroll', function onScroll() {
          highlightedPre.scrollTop = textarea.scrollTop;
        }, { passive: true });

        textarea.addEventListener('input', function onScroll() {
          highlightedCode.innerHTML = Prism.highlight(textarea.value, Prism.languages.html, 'html');
        }, { passive: true });
      })();
    }
  </script>

</body>
</html>
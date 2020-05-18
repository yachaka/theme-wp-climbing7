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
      overflow: auto;
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
include('tmp-migration-posts-acf/html5-php/src/HTML5/Entities.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/CharacterReference.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Elements.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/Tokenizer.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/UTF8Utils.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/Scanner.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/TreeBuildingRules.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/EventHandler.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5/Parser/DOMTreeBuilder.php');
include('tmp-migration-posts-acf/html5-php/src/HTML5.php');

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

    $new_post_data = array(
      'ID' => $_POST['post_id'],
      'post_content' => $_POST['post_content'],
    );

    // Post content
    wp_update_post($new_post_data);

    foreach ($_POST['acf_field'] as $field_name => $new_field_value) {
      $field_key = $acf_fields_name_to_key[$field_name];
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
$query = new WP_Query([
  'post__not_in' => $posts_ID_deja_migres,
  'posts_per_page' => 1,
  'offset' => $offset,
]);

$total = count((new WP_Query([
  'nopaging' => true,
  'fields' => 'ids',
]))->get_posts());


$post_a_migrer = $query->the_post();

/*
 * Boucle principale
 * Récupération des valeurs des ACF depuis le post_content
 */
$acf_actif = null;

$acf_fields_fn = [
  'presentation' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($acf_fields['presentation'] === ''
      && ($node->nodeName === 'p' || is_heading($node))
      && $acf_actif === null) {
      $acf_actif = 'presentation';
    }
  }),
  
  'fiche_technique' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['fiche', 'technique'], 2)
      && strlen($node->textContent) < 60
    ) {
        $acf_actif = 'fiche_technique';
        $cursor = $cursor->nextSibling;
    }
  }),
  
  'acces_au_site' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['acces', 'accès'], 1)
      && strlen($node->textContent) < 45
    ) {
        $acf_actif = 'acces_au_site';
        $cursor = $cursor->nextSibling;
    }
  }),

  'texte_carte' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['carte', 'topo'], 1)
      && strlen($node->textContent) < 60
    ) {
        $acf_actif = 'texte_carte';
        $cursor = $cursor->nextSibling;
    }
  }),

  'carte_iframe' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && $node->nodeType === 'div'
      && node_contains_text($node->textContent, ['[iframe'], 1)
    ) {
        $acf_actif = 'carte_iframe';
    }
  }),

  'approche' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['approche', $duree_regex], 2)
      && strlen($node->textContent) < 60
    ) {
        $acf_actif = 'approche';
    }
  }),

  'parcours' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['parcours', $duree_regex], 2)
      && strlen($node->textContent) < 60
    ) {
        $acf_actif = 'parcours';
    }
  }),

  'retour' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['retour', $duree_regex], 2)
      && strlen($node->textContent) < 60
    ) {
        $acf_actif = 'retour';
    }
  }),

  'galerie' => (function ($node, &$cursor) use (&$acf_fields, &$acf_actif, &$duree_regex) {
    if ($node->nodeType === XML_ELEMENT_NODE
      && is_heading($node)
      && node_contains_text($node->textContent, ['images', 'galerie'], 1)
      && strlen($node->textContent) < 60
    ) {
        $acf_actif = 'galerie';
        $cursor = $cursor->nextSibling;

        // Retirer le heading
        $prev = $cursor->previousSibling;
        $prev->parentNode->removeChild($prev);
    }
  }),
];

// Éxecution
$post_id = get_the_ID();
$post_content = get_the_content();
$post_content_DOM = $HTMLParser->loadHTML("<!DOCTYPE html><html><body>". $post_content ."</body></html>");

$node = $post_content_DOM->childNodes[1]->firstChild->firstChild;

while ($node) {
  foreach ($acf_fields_fn as $fn) {
    $fn($node, $node);
  }

  $a_retirer_du_dom = false;

  if (strlen($acf_actif) > 0) {
    if ($node->nodeName === 'hr'
      && strstr(
          $node->attributes->getNamedItem('class')->nodeValue,
          'wp-block-separator'
        ) !== false
    ) {
      // <hr />
      $a_retirer_du_dom = true;
    } else if ($node->nodeType === XML_ELEMENT_NODE) {
      // Noeud
      $acf_fields[$acf_actif] .= $node->ownerDocument->saveHTML($node);
      $a_retirer_du_dom = true;
    } else if ($node->nodeType === XML_TEXT_NODE) {
      // Texte
      $acf_fields[$acf_actif] .= $node->textContent;
      $a_retirer_du_dom = true;
    } else if ($node->nodeType === XML_COMMENT_NODE) {
      // Commentaire
      $a_retirer_du_dom = true;
    }
  }

  $node = $node->nextSibling;

  if ($node && $a_retirer_du_dom === true) {
    $node->previousSibling->parentNode->removeChild($node->previousSibling);
  }
}

$new_post_content = '';
$body = $post_content_DOM->childNodes[1]->firstChild;

foreach ($body->childNodes as $childNode) {
  $new_post_content .= $post_content_DOM->saveHTML($childNode);
} 

// function showDOMNode(DOMNode $domNode, $depth = 0) {
//     foreach ($domNode->childNodes as $node)
//     {
//         print str_repeat('----', $depth);
//         print $node->nodeType.'/'.$node->nodeName.':';
//         if ($node->nodeType === XML_TEXT_NODE) {
//           print $node->textContent;
//         }
//         print '<br/>';

//         if($node->hasChildNodes()) {
//             showDOMNode($node, $depth + 1);
//         }
//     }    
// }

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

    <!-- Les ACFs -->
    <div class="panneau">
      <?php
      foreach($acf_fields as $field_name => $field_value) {
        $value = str_replace('<br></br>', "<br/>\n", trim($field_value));
      ?>
        <div class="form-field">
          <h4 class="label-acf"><?= $field_name ?></h4>

          <div class="fake-highlight-textarea">
            <textarea name="acf_field[<?= $field_name ?>]"><?=
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

    <!-- Le post_content -->
    <div class="panneau">
      <div class="form-field post-content">
        <h4 class="label-acf">Nouveau post_content</h4>

        <div class="fake-highlight-textarea">
          <textarea name="post_content"><?=
            $new_post_content
          ?></textarea>
          <pre><code class="language-html"><?=
              str_replace('&amp;', '&', htmlentities($new_post_content))
          ?></code><div style="height: 4px;"></div></pre>
        </div>
      </div>
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
<?php
/**
 * @package Watson
 */

if (!$GLOBALS['searchform_count']) {
  $GLOBALS['searchform_count'] = 0;
}

// Attribution d'un identifiant unique à ce formulaire de recherche,
// Dans le cas où plusieurs sont dans la même page
$GLOBALS['searchform_count'] += 1;
$sf_id = $GLOBALS['searchform_count'];

// Initialisation des listes qu'on va utiliser

// Récupération des lieux et activités
$filtres = get_terms(array(
  'taxonomy' => ['activites', 'lieux'],
  'parent'  => 0, //pour limiter aux parents seulement
));

// Lieux seulement
$lieux = array_filter($filtres, function ($filtre) {
  return $filtre->taxonomy === 'lieux';
});
$slugsLieux = array_map(function ($lieu) { return $lieu->slug; }, $lieux);

// Acitvités seulement
$activites = array_filter($filtres, function ($filtre) {
  return $filtre->taxonomy === 'activites';
});
$slugsActivites = array_map(function ($activite) { return $activite->slug; }, $activites);

// Récupération et normalisation des filtres potentiellement actifs
if (isset($_GET['f_lieux']) && !is_array($_GET['f_lieux'])) {
  $_GET['f_lieux'] = array($_GET['f_lieux']);
}

// Récupération et normalisation des filtres potentiellement actifs
if (isset($_GET['f_activites']) && !is_array($_GET['f_activites'])) {
  $_GET['f_activites'] = array($_GET['f_activites']);
}
?>

<h3 style="text-align:center;padding:10px;margin:0;">
  <span class="texte_ouverture">
    <?php
    if (is_search())
    {
      echo 'Modifier la recherche';
    }
    elseif (is_home())
    {
      echo 'Faire une recherche';
    }
    ?>
  </span>
  <span class="texte_fermeture">
    Fermer la recherche
  </span>
</h3>

<div style="text-align: center;">
  <form
    id="searchform-<?= $sf_id ?>"
    class="searchform"
    method="get"
    role="search"
    action="<?php echo esc_url( home_url( '/' ) ); ?>"
  >
  	<input style="border-radius:9px;"
  		type="text"
  		id="s"
  		name="s"
  		size="80"
  		placeholder="<?php esc_attr_e( 'Rechercher sur le site&hellip;', 'watson' ) ?>"
      value=""
      title="<?php esc_attr_e( 'Type and press Enter to search', 'watson' ); ?>"
  	/>

    <!---------------------------------->
    <!-- Boutons de filtrage par lieu -->
    
    <p style="margin-top:20px;font-family: 'Helvetica'; font-size:0.8rem;color:#555;text-transform: uppercase;font-weight:100;">Filtrer par activité et par pays</p>
    
    <div class="ligne-filtres">
      
      <?php
      foreach ($lieux as $lieu) {
        // Est-ce que nous sommes sur une page filtré par ce lieu ?
        $filtre_actif = (
          isset($_GET['f_lieux'])
          && in_array($lieu->slug, $_GET['f_lieux'])
        );

        $filtre_classe = '';

        if ($filtre_actif) {
          $filtre_classe = 'filtre_actif';
        }
      ?>
        <button
          id="sf-<?= $sf_id ?>_filtre-lieux-<?= $lieu->slug ?>"
          type="button"
          class="filtre_pastille_recherche <?= $filtre_classe; ?>"
        >
          <?= $lieu->name ?>
        </button>

        <?php
        if ($filtre_actif) {
        ?>
          <input
            id="sf-<?= $sf_id ?>_input-filtre-lieux-<?= $lieu->slug; ?>"
            type="hidden"
            name="f_lieux[]"
            value="<?= $lieu->slug; ?>"
          />
        <?php
        }

        // Fin de la boucle
      }
      ?>
    </div>

    <!---------------------------------->
    <!-- Boutons de filtrage par activité -->
    <div class="ligne-filtres">
      
      <?php
      foreach ($activites as $activite) {
        // Est-ce que nous sommes sur une page filtré par cette activité ?
        $filtre_actif = (
          isset($_GET['f_activites'])
          && in_array($activite->slug, $_GET['f_activites'])
        );

        $filtre_classe = '';

        if ($filtre_actif) {
          $filtre_classe = 'filtre_actif';
        }
      ?>
        <button
          id="sf-<?= $sf_id ?>_filtre-activites-<?= $activite->slug ?>"
          type="button"
          class="filtre_pastille_recherche <?= $filtre_classe; ?>"
        >
          <?= $activite->name ?>
        </button>

        <?php
        if ($filtre_actif) {
        ?>
          <input
            id="sf-<?= $sf_id ?>_input-filtre-activites-<?= $activite->slug; ?>"
            type="hidden"
            name="f_activites[]"
            value="<?= $activite->slug; ?>"
          />
        <?php
        }

        // Fin de la boucle
      }
      ?>
    </div>
    <br />
    <button type="submit">
      Rechercher
    </button>
  </form>
</div>

<!-- JavaScript -->
<!-- Activation/désactivation des filtres de recherche -->
<script type="text/javascript">
  (function () {
    // Scoped

    // Création des liste javascript depuis les listes PHP
    var lieux = ['<?= join($slugsLieux, "', '") ?>'];
    var activites = ['<?= join($slugsActivites, "', '") ?>'];

    var searchformEl = document.getElementById('searchform-<?= $sf_id ?>');

    // Fonction outil
    function filtreInterrupteurUsine(filtreTaxonomie, filtreSlug) {
      
      // Fonction d'ajout/retrait d'un <input /> hidden, suivant le filtre et la taxonomie
      return function ajoutRetraitInputHidden() {
        // HTML id
        var filtreHiddenInputId = 'sf-<?= $sf_id ?>_input-filtre-' + filtreTaxonomie + '-' + filtreSlug;
        var btnElId = 'sf-<?= $sf_id ?>_filtre-' + filtreTaxonomie + '-' + filtreSlug;

        // HTML elements
        var filtreHiddenInput = document.getElementById(filtreHiddenInputId);
        var btnEl = document.getElementById(btnElId);

        // Éxecution
        if (!filtreHiddenInput) {
          // Il n'existe pas, création de <input /> et rajout dans le HTML
          var el = document.createElement('input');
          el.id = filtreHiddenInputId;
          el.type = 'hidden';
          el.name = 'f_' + filtreTaxonomie + '[]';
          el.value = filtreSlug;

          searchformEl.appendChild(el);

          // Ajout de la classe active sur le bouton
          btnEl.classList.add('filtre_actif');
        } else {
          // Il existe, retrait du HTML
          filtreHiddenInput.parentNode.removeChild(filtreHiddenInput);

          // Retrait de la classe active sur le bouton
          btnEl.classList.remove('filtre_actif');
        }
      }
    }

    // Ajout des fonctions, sur l'évènement "clic" des boutons lieux
    for (var i = 0; i < lieux.length; i++) {
      var btnEl = document.getElementById('sf-<?= $sf_id ?>_filtre-lieux-' + lieux[i]);
      var fnAjoutRetraitInputHidden = filtreInterrupteurUsine('lieux', lieux[i]);
      
      btnEl.addEventListener('click', fnAjoutRetraitInputHidden);
    }

    // Ajout des fonctions, sur l'évènement "clic" des boutons activites
    for (var j = 0; j < activites.length; j++) {
      var btnEl = document.getElementById('sf-<?= $sf_id ?>_filtre-activites-' + activites[j]);
      var fnAjoutRetraitInputHidden = filtreInterrupteurUsine('activites', activites[j]);
      
      btnEl.addEventListener('click', fnAjoutRetraitInputHidden);
    }
  })();
</script>
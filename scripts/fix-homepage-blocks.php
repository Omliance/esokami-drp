<?php

/**
 * @file
 * Fix homepage block HTML: remove --ek-* CSS vars from inline styles,
 * remove inline styles that should be in CSS, clean up markup.
 *
 * Usage: ddev drush php:script scripts/fix-homepage-blocks.php
 */

use Drupal\block_content\Entity\BlockContent;

$fixes = [

  // 1. HERO — replace var(--ek-gold) in inline style with hardcoded color
  'Homepage — Hero' => <<<'HTML'
<section class="ek-hero" id="accueil" aria-label="Introduction">
  <div class="row align-items-center">

    <div class="col-12 col-md-7 ek-hero__inner">
      <p class="ek-eyebrow">Artisanat &amp; pratiques énergétiques</p>

      <h1 class="ek-hero__title">Matérialisez vos<br><em>intentions.</em><br>Améliorez votre vie.</h1>

      <p class="ek-hero__sub">Des objets symboliques uniques — gravés, purifiés, consacrés — et un accompagnement personnalisé pour traverser les moments de changement avec clarté et intention.</p>

      <div class="ek-accent--hero" aria-hidden="true"></div>

      <div class="ek-hero__ctas">
        <a href="#offres" class="ek-btn-primary">Découvrir les formules</a>
        <a href="#" class="ek-btn-secondary">En savoir plus</a>
      </div>
    </div>

    <div class="col-12 col-md-5 ek-hero__visual">
      <div class="ek-hero__placeholder">
        <svg width="64" height="64" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="0.6">
          <circle cx="16" cy="16" r="15"></circle>
          <circle cx="16" cy="16" r="7"></circle>
          <line x1="1" y1="16" x2="31" y2="16"></line>
          <line x1="16" y1="1" x2="16" y2="31"></line>
        </svg>
      </div>
    </div>

  </div>
</section>
HTML,

  // 4. OFFRES — remove inline style on disclaimer
  'Homepage — Offres' => <<<'HTML'
<section class="ek-section" id="offres" aria-labelledby="offres-title">
  <div class="container-xl">

    <div class="ek-section-header ek-reveal">
      <p class="ek-section-label">Les formules</p>
      <h2 id="offres-title">Trois formules, une même intention</h2>
      <p class="ek-section-sub">Choisissez le niveau d'accompagnement qui correspond à votre besoin et à votre situation.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 ek-reveal-group">

      <div class="col">
        <article class="ek-offer">
          <p class="ek-offer__label">Objet de collection</p>
          <h3 class="ek-offer__title">Étincelle</h3>
          <p class="ek-offer__price"><sup>€</sup>29</p>
          <p class="ek-offer__desc">Une intention simple, un besoin ponctuel, ou l'envie de découvrir. Un objet purifié et consacré, efficace et accessible.</p>
          <ul class="ek-offer__feats">
            <li class="ek-offer__feat">Un objet de la collection Esokami à choisir parmi plusieurs designs</li>
            <li class="ek-offer__feat">Purifié et consacré avec votre intention</li>
            <li class="ek-offer__feat">Guide d'utilisation pour agir en autonomie</li>
            <li class="ek-offer__feat">Expédition sous 3 jours</li>
          </ul>
          <a href="#" class="ek-btn-primary">Choisir Étincelle</a>
        </article>
      </div>

      <div class="col">
        <article class="ek-offer--featured">
          <p class="ek-offer__label">Création unique</p>
          <h3 class="ek-offer__title">Flamme</h3>
          <p class="ek-offer__price"><sup>€</sup>69</p>
          <p class="ek-offer__desc">Vous voulez un objet vraiment unique, créé spécifiquement pour vous ? La personnalisation complète pour incarner votre intention.</p>
          <ul class="ek-offer__feats">
            <li class="ek-offer__feat">Un sigil unique créé à partir de votre intention personnelle</li>
            <li class="ek-offer__feat">Gravé sur bois ou acier inoxydable, colorisé à la main</li>
            <li class="ek-offer__feat">Purifié et consacré</li>
            <li class="ek-offer__feat">Guide complet d'utilisation et d'activation</li>
            <li class="ek-offer__feat">Expédition sous 5 jours</li>
          </ul>
          <a href="#" class="ek-btn-gold">Choisir Flamme</a>
        </article>
      </div>

      <div class="col">
        <article class="ek-offer">
          <p class="ek-offer__label">Accompagnement complet</p>
          <h3 class="ek-offer__title">Brasier</h3>
          <p class="ek-offer__price"><sup>€</sup>249</p>
          <p class="ek-offer__desc">Vous faites face à un défi important et avez besoin d'un soutien renforcé ? Cette formule vous offre un accompagnement complet et personnalisé.</p>
          <ul class="ek-offer__feats">
            <li class="ek-offer__feat">Tout ce qui est inclus dans Flamme</li>
            <li class="ek-offer__feat">Une pratique énergétique personnalisée réalisée en votre nom</li>
            <li class="ek-offer__feat">Un débrief téléphonique de 30 minutes</li>
            <li class="ek-offer__feat">Suivi d'un mois : 4 points de 20 min + échanges par mail</li>
            <li class="ek-offer__feat">Démarrage sous 1 semaine</li>
          </ul>
          <a href="#" class="ek-btn-primary">Choisir Brasier</a>
        </article>
      </div>

    </div>

    <div class="ek-disclaimer--centered mt-5" role="note">
      <p>Mon accompagnement ne remplace en aucun cas un suivi médical. Il vient en complément des traitements conventionnels pour une approche holistique de votre bien-être.</p>
    </div>

  </div>
</section>
HTML,

  // 6. APPROCHE — remove inline style on disclaimer
  'Homepage — Approche' => <<<'HTML'
<section class="ek-section-alt" id="approche" aria-labelledby="approche-title">
  <div class="container-xl">

    <div class="ek-section-header ek-reveal">
      <p class="ek-section-label">Mon approche</p>
      <h2 id="approche-title">Le subtil au service du concret</h2>
      <p class="ek-section-sub">Ni jargon obscur, ni croyance imposée. Une pratique artisanale et intentionnelle, accessible à tous.</p>
    </div>

    <div class="ek-approche__body ek-reveal">
      <p class="ek-approche__text">Imaginez un iceberg. La partie visible, c'est votre quotidien : vos actions, vos décisions, vos efforts concrets. La partie immergée, bien plus vaste, c'est le plan subtil : vos croyances profondes, votre énergie, votre intention.</p>
      <p class="ek-approche__text">Mon travail agit sur cette partie invisible. Non pas pour remplacer vos actions, mais pour les soutenir en profondeur. Vous cherchez un emploi ? Continuez à postuler. Vous voulez améliorer votre santé ? Consultez un médecin. Le subtil ne remplace jamais le concret — il le renforce.</p>
      <p class="ek-approche__quote"><em>Audentes Fortuna Iuvat</em> — À l'audace, la Fortune sourit.</p>
    </div>

    <div class="ek-disclaimer--centered ek-reveal" role="note" aria-label="Mention légale santé">
      <p>Mon accompagnement ne remplace en aucun cas un suivi médical. Il vient en complément des traitements conventionnels pour une approche holistique de votre bien-être.</p>
    </div>

  </div>
</section>
HTML,

  // 7. À PROPOS — remove var(--ek-gold) inline, remove <br> + inline style on btn
  'Homepage — A propos' => <<<'HTML'
<section class="ek-section" id="qui-suis-je" aria-labelledby="about-title">
  <div class="container-xl">
    <div class="row align-items-center ek-about__inner">

      <div class="col-12 col-md-5 ek-about__img ek-reveal">
        <div class="ek-about__placeholder">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8">
            <circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path>
          </svg>
        </div>
      </div>

      <div class="col-12 col-md-7 ek-reveal">
        <div class="ek-section-header ek-section-header--left">
          <p class="ek-section-label">Qui suis-je ?</p>
          <h2 id="about-title">Artisan de l'intention, praticien énergétique</h2>
        </div>
        <p class="ek-about__text">Je suis Christophe. La manifestation n'est pas une théorie pour moi, c'est une pratique personnelle de plus de 30 ans, avec des résultats concrets. J'ai lancé Esokami pour mettre mon expérience à votre service.</p>
        <p class="ek-about__text">Mon approche est pragmatique : je crée des objets symboliques uniques — pensés pour une personne, dans un contexte précis, avec une intention claire. Je ne vends pas du mystère, je propose un outil concret ancré dans le réel, fabriqué avec soin, et un espace d'accompagnement bienveillant.</p>
        <p class="ek-about__sig">— Christophe</p>
        <a href="#" class="ek-btn-secondary mt-4">En savoir plus sur mon parcours</a>
      </div>

    </div>
  </div>
</section>
HTML,

];

$storage = \Drupal::entityTypeManager()->getStorage('block_content');
$updated = 0;

foreach ($fixes as $label => $html) {
  $result = $storage->loadByProperties(['info' => $label]);
  if (empty($result)) { echo "NOT FOUND: $label\n"; continue; }
  $block = reset($result);
  $block->get('body')->setValue(['value' => $html, 'format' => 'full_html']);
  $block->save();
  $updated++;
  echo "✓ Fixed: $label\n";
}

echo "\nDone. Fixed $updated block(s).\n";

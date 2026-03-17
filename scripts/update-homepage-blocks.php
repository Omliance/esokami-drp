<?php

/**
 * @file
 * Drush script to update homepage block content with ek- prefixed HTML.
 *
 * Usage: ddev drush php:script scripts/update-homepage-blocks.php
 */

use Drupal\block_content\Entity\BlockContent;

// Map of block labels to their new HTML content.
$blocks = [

  // 1. HERO
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
        <svg width="64" height="64" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="0.6" style="color:var(--ek-gold);opacity:0.35">
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

  // 2. IDENTIFICATION
  'Homepage — Identification' => <<<'HTML'
<section class="ek-section-alt" id="identification" aria-labelledby="identification-title">
  <div class="container-xl">

    <div class="ek-section-header ek-reveal">
      <p class="ek-section-label">Vous vous reconnaissez ?</p>
      <h2 id="identification-title">Ces situations vous parlent-elles ?</h2>
      <p class="ek-section-sub">Aucune exigence de croyance. Seulement un désir sincère de changement.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 ek-reveal-group">

      <div class="col">
        <article class="ek-card">
          <div class="ek-card__icon" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
            </svg>
          </div>
          <h3 class="ek-card__title">Changement bloqué</h3>
          <p class="ek-card__text">Argent, carrière, projet de vie… Vous tournez en rond malgré vos efforts. La même situation se répète et vous cherchez un levier différent pour enfin avancer.</p>
        </article>
      </div>

      <div class="col">
        <article class="ek-card">
          <div class="ek-card__icon" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </div>
          <h3 class="ek-card__title">Intention sans ancrage</h3>
          <p class="ek-card__text">Amour, santé, bien-être… Vous avez une vision claire de ce que vous voulez, mais elle peine à se concrétiser. Il manque quelque chose de tangible pour ancrer votre intention.</p>
        </article>
      </div>

      <div class="col">
        <article class="ek-card">
          <div class="ek-card__icon" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
          </div>
          <h3 class="ek-card__title">En quête de sens</h3>
          <p class="ek-card__text">Spiritualité, protection, développement personnel… Vous sentez qu'il existe d'autres leviers pour avancer, mais vous ne savez pas par où commencer ni à qui faire confiance.</p>
        </article>
      </div>

    </div>
  </div>
</section>
HTML,

  // 3. OFFRES
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

  // 5. PROCESSUS
  'Homepage — Processus' => <<<'HTML'
<section class="ek-section-dark" id="processus" aria-labelledby="processus-title">
  <div class="container-xl">

    <div class="ek-section-header ek-reveal">
      <p class="ek-section-label">Comment ça marche ?</p>
      <h2 id="processus-title">Un processus en six étapes</h2>
      <p class="ek-section-sub">De la première prise de contact à la livraison de votre objet.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 ek-process-grid ek-reveal-group">

      <div class="col">
        <div class="ek-step">
          <div class="ek-num--step" aria-hidden="true">01</div>
          <h3 class="ek-step__title">Prise de contact</h3>
          <p class="ek-step__text">Vous partagez votre intention, vos aspirations et le contexte de votre démarche via un formulaire simple.</p>
        </div>
      </div>

      <div class="col">
        <div class="ek-step">
          <div class="ek-num--step" aria-hidden="true">02</div>
          <h3 class="ek-step__title">Échange initial</h3>
          <p class="ek-step__text">Un entretien en ligne pour affiner l'intention, comprendre ce que vous traversez et définir la forme de l'objet.</p>
        </div>
      </div>

      <div class="col">
        <div class="ek-step">
          <div class="ek-num--step" aria-hidden="true">03</div>
          <h3 class="ek-step__title">Design &amp; création</h3>
          <p class="ek-step__text">Je crée le motif unique de votre talisman — ou vous choisissez un modèle de la collection. Vous validez avant la gravure.</p>
        </div>
      </div>

      <div class="col">
        <div class="ek-step">
          <div class="ek-num--step" aria-hidden="true">04</div>
          <h3 class="ek-step__title">Gravure &amp; finition</h3>
          <p class="ek-step__text">La gravure laser est réalisée sur bois ou acier inoxydable, suivie d'une colorisation soignée à la main pour révéler le motif.</p>
        </div>
      </div>

      <div class="col">
        <div class="ek-step">
          <div class="ek-num--step" aria-hidden="true">05</div>
          <h3 class="ek-step__title">Purification &amp; consécration</h3>
          <p class="ek-step__text">L'objet est purifié pour le nettoyer de toute énergie résiduelle, puis consacré : une activation qui lui donne sa mission — porter et rayonner votre intention.</p>
        </div>
      </div>

      <div class="col">
        <div class="ek-step">
          <div class="ek-num--step" aria-hidden="true">06</div>
          <h3 class="ek-step__title">Livraison &amp; suivi</h3>
          <p class="ek-step__text">L'objet vous est expédié avec un guide d'utilisation complet. Pour la formule Brasier, l'accompagnement personnalisé commence dès réception.</p>
        </div>
      </div>

    </div>

  </div>
</section>
HTML,

  // 6. APPROCHE
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

  // 7. À PROPOS
  'Homepage — A propos' => <<<'HTML'
<section class="ek-section" id="qui-suis-je" aria-labelledby="about-title">
  <div class="container-xl">
    <div class="row align-items-center ek-about__inner">

      <div class="col-12 col-md-5 ek-about__img ek-reveal">
        <div class="ek-about__placeholder">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8" style="color:var(--ek-gold);opacity:0.4">
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
        <br>
        <a href="#" class="ek-btn-secondary" style="margin-top:24px;display:inline-block;">En savoir plus sur mon parcours</a>
      </div>

    </div>
  </div>
</section>
HTML,

  // 8. FAQ
  'Homepage — FAQ' => <<<'HTML'
<section class="ek-section-alt" id="faq" aria-labelledby="faq-title">
  <div class="container-xl">

    <div class="ek-section-header ek-reveal">
      <p class="ek-section-label">Questions fréquentes</p>
      <h2 id="faq-title">Ce que vous souhaitez savoir</h2>
    </div>

    <div class="ek-faq ek-reveal" role="list">

      <div class="ek-faq__item" role="listitem" id="faq-item-1">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-1">
          Dois-je croire en quoi que ce soit pour que ça fonctionne ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-1" role="region">
          <p>Non. Le talisman est avant tout un objet symbolique qui ancre une intention. L'ouverture d'esprit suffit. Vous n'avez besoin d'aucune connaissance préalable en pratiques énergétiques. Le guide fourni avec chaque objet vous explique tout, étape par étape.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-2">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-2">
          Comment se déroule la commande ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-2" role="region">
          <p>Tout se fait en ligne. Vous choisissez votre formule, remplissez un formulaire pour partager votre intention, et on échange pour affiner votre demande. Je crée ensuite votre objet, le purifie et le consacre avant de vous l'expédier avec un guide complet.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-3">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-3">
          Quels délais pour recevoir mon objet ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-3" role="region">
          <p>La formule Étincelle est expédiée sous 3 jours. La formule Flamme sous 5 jours (le temps de créer votre sigil unique). Pour Brasier, le travail démarre sous 1 semaine, avec un accompagnement sur un mois.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-4">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-4">
          Pouvez-vous travailler sur des intentions liées à la santé ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-4" role="region">
          <p>Oui, dans le cadre d'un complément holistique uniquement. Mon accompagnement ne remplace jamais un avis médical, un traitement ou un suivi psychologique. Si vous suivez un traitement, continuez. Si vous voyez un thérapeute, continuez.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-5">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-5">
          Comment choisir entre les 3 formules ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-5" role="region">
          <p>Étincelle (29€) si vous voulez découvrir ou avez une intention simple et ciblée. Flamme (69€) si vous voulez un objet vraiment unique, créé spécifiquement pour votre intention — c'est la formule idéale pour la plupart des demandes. Brasier (249€) si vous traversez une période importante et avez besoin d'un soutien renforcé avec un accompagnement personnalisé.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-6">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-6">
          Puis-je commander un objet pour quelqu'un d'autre ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-6" role="region">
          <p>Oui, à condition que la personne ait donné son consentement. Je ne travaille pas sur quelqu'un sans son accord. Vous pouvez aussi opter pour un bon cadeau si vous préférez lui laisser le choix.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-7">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-7">
          Comment ça marche si tout se fait à distance ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-7" role="region">
          <p>L'énergie n'a pas de frontière géographique. Concrètement, vous me transmettez votre intention, je crée et consacre votre objet, puis je vous l'envoie par courrier avec le guide d'utilisation. Pour la formule Brasier, les échanges se font par téléphone et par mail.</p>
        </div>
      </div>

      <div class="ek-faq__item" role="listitem" id="faq-item-8">
        <button class="ek-faq__q" aria-expanded="false" aria-controls="faq-answer-8">
          Y a-t-il des demandes que vous refusez ?
          <span class="ek-faq__icon" aria-hidden="true">+</span>
        </button>
        <div class="ek-faq__a" id="faq-answer-8" role="region">
          <p>Oui, celles qui visent à nuire ou manipuler autrui. Pour le reste, toutes les intentions sont légitimes et accueillies sans jugement. Argent, amour, santé, réussite, protection… Il n'y a pas de demande « trop petite » ou « trop ambitieuse ».</p>
        </div>
      </div>

    </div>
  </div>
</section>
HTML,

  // 9. CTA FINAL
  'Homepage — CTA Final' => <<<'HTML'
<section class="ek-cta" id="cta-final" aria-labelledby="cta-final-title">

  <div class="container-xl">
    <h2 class="ek-cta__title ek-reveal" id="cta-final-title">Prêt(e) à ancrer<br>vos <em>intentions</em> dans le réel ?</h2>
    <p class="ek-cta__sub ek-reveal">Et si c'était le bon moment ? Choisissez la formule qui vous parle. Tout commence par une intention.</p>
    <div class="ek-cta__btns ek-reveal">
      <a href="#" class="ek-btn-gold">Étincelle — 29€</a>
      <a href="#" class="ek-btn-outline-light">Flamme — 69€</a>
      <a href="#" class="ek-btn-outline-light">Brasier — 249€</a>
    </div>
    <a href="#" class="ek-cta__contact ek-reveal">Une question ? Je suis là pour vous répondre</a>
  </div>

</section>
HTML,

];

// Find and update each block.
$storage = \Drupal::entityTypeManager()->getStorage('block_content');
$updated = 0;
$not_found = [];

foreach ($blocks as $label => $html) {
  $result = $storage->loadByProperties(['info' => $label]);
  if (empty($result)) {
    $not_found[] = $label;
    continue;
  }

  $block = reset($result);
  $block->get('body')->setValue([
    'value' => $html,
    'format' => 'full_html',
  ]);
  $block->save();
  $updated++;
  echo "✓ Updated: $label\n";
}

if (!empty($not_found)) {
  echo "\n⚠ Not found (skipped):\n";
  foreach ($not_found as $label) {
    echo "  - $label\n";
  }
}

echo "\nDone. Updated $updated block(s).\n";

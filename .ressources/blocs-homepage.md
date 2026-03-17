# Blocs Homepage — Guide de contenu pour l'admin Drupal

> Ce fichier documente chaque bloc a creer dans l'admin Drupal (Structure > Contenu de bloc > Ajouter un bloc).
> Tous les blocs sont places dans la region **Content** et restreints a la page `<front>`.
> L'ordre ci-dessous correspond a l'ordre d'affichage sur la homepage.

---

## 1. Hero

- **Type de bloc** : `hero`
- **Titre admin (info)** : `Homepage — Hero`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Artisanat ésotérique & pratiques énergétiques` |
| **Body** (`body`, format Full HTML) | `<h1 class="ek-hero__title">Matérialisez vos<br><em>intentions.</em><br>Améliorez votre vie.</h1>` |
| **Subtitle** (`field_subtitle`) | `Des objets rituels uniques — gravés, purifiés, consacrés — et un accompagnement personnalisé pour traverser les moments de changement avec clarté et intention.` |
| **CTA Links** (`field_cta_links`) | Lien 1 : Titre = `Voir les offres`, URL = `/offres` |
| | Lien 2 : Titre = `En savoir plus`, URL = `/a-propos` |

---

## 2. Identification / Pain points

- **Type de bloc** : `section_cards`
- **Titre admin (info)** : `Homepage — Pain points`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Vous vous reconnaissez ?` |
| **Section Variant** (`field_section_variant`) | `section-alt` |
| **Subtitle** (`field_subtitle`) | `Aucune exigence de croyance. Seulement un désir sincère de changement.` |
| **Body** (`body`, format Full HTML) | Voir HTML ci-dessous |

**Contenu Body (Full HTML)** :

```html
<h2>Ces situations vous parlent-elles ?</h2>
<div class="row row-cols-1 row-cols-md-3 g-4">
  <div class="col">
    <div class="ek-card">
      <div class="ek-card__icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
          <path d="M12 2a10 10 0 0 0 0 20A10 10 0 0 0 12 2z"/><path d="M12 8v4l3 3"/>
        </svg>
      </div>
      <h3 class="ek-card__title">Changement bloqué</h3>
      <p class="ek-card__text">Vous tournez en rond malgré vos efforts. La même situation se répète et vous cherchez un levier différent.</p>
    </div>
  </div>
  <div class="col">
    <div class="ek-card">
      <div class="ek-card__icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
      </div>
      <h3 class="ek-card__title">Intention sans ancrage</h3>
      <p class="ek-card__text">Vous avez une vision claire mais elle peine à se concrétiser. Il manque quelque chose de tangible, de matériel.</p>
    </div>
  </div>
  <div class="col">
    <div class="ek-card">
      <div class="ek-card__icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
          <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
        </svg>
      </div>
      <h3 class="ek-card__title">Approche alternative</h3>
      <p class="ek-card__text">Curieux(se) des pratiques énergétiques mais sans savoir par où commencer, ni à qui vous adresser en confiance.</p>
    </div>
  </div>
</div>
```

---

## 3. Offres

- **Type de bloc** : `section_cards`
- **Titre admin (info)** : `Homepage — Offres`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Les offres` |
| **Section Variant** (`field_section_variant`) | `section` |
| **Subtitle** (`field_subtitle`) | `Choisissez le niveau d'accompagnement qui correspond à votre besoin.` |
| **Body** (`body`, format Full HTML) | Voir HTML ci-dessous |

**Contenu Body (Full HTML)** :

```html
<h2>Deux voies, une même intention</h2>
<div class="row row-cols-1 row-cols-md-2 g-4">
  <div class="col">
    <div class="ek-offer">
      <p class="ek-offer__label">Objet rituel</p>
      <h3 class="ek-offer__title">Talisman personnalisé</h3>
      <div class="ek-offer__price"><sup>€</sup>39</div>
      <p class="ek-offer__desc">Un objet unique gravé au laser, purifié et consacré selon votre intention. Conçu numériquement, finalisé à la main.</p>
      <ul class="ek-offer__feats">
        <li class="ek-offer__feat">Design personnalisé selon votre intention</li>
        <li class="ek-offer__feat">Gravure laser sur bois ou métal</li>
        <li class="ek-offer__feat">Purification &amp; consécration rituelles</li>
        <li class="ek-offer__feat">Livraison avec notice d'utilisation</li>
      </ul>
      <a href="/talisman" class="ek-btn-primary">Commander mon talisman</a>
    </div>
  </div>
  <div class="col">
    <div class="ek-offer--featured">
      <p class="ek-offer__label">Accompagnement complet</p>
      <h3 class="ek-offer__title">Transformation complète</h3>
      <div class="ek-offer__price"><sup>€</sup>290</div>
      <p class="ek-offer__desc">Le talisman, des rituels personnalisés réalisés pour vous, un accompagnement pas à pas et un suivi dans la durée.</p>
      <ul class="ek-offer__feats">
        <li class="ek-offer__feat">Talisman personnalisé inclus</li>
        <li class="ek-offer__feat">Rituels énergétiques sur mesure</li>
        <li class="ek-offer__feat">Accompagnement &amp; conseils personnalisés</li>
        <li class="ek-offer__feat">Suivi sur 4 semaines</li>
      </ul>
      <a href="/transformation" class="ek-btn-gold">Démarrer ma transformation</a>
    </div>
  </div>
</div>
```

---

## 4. Processus

- **Type de bloc** : `process_steps`
- **Titre admin (info)** : `Homepage — Processus`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Comment ça marche ?` |
| **Subtitle** (`field_subtitle`) | `De la première prise de contact à la livraison de votre objet rituel.` |
| **Body** (`body`, format Full HTML) | Voir HTML ci-dessous |

**Contenu Body (Full HTML)** :

```html
<h2>Un processus en six étapes</h2>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 ek-process-grid">
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
      <h3 class="ek-step__title">Design numérique</h3>
      <p class="ek-step__text">Je crée le motif unique de votre talisman, inspiré de votre intention. Vous validez avant la gravure.</p>
    </div>
  </div>
  <div class="col">
    <div class="ek-step">
      <div class="ek-num--step" aria-hidden="true">04</div>
      <h3 class="ek-step__title">Gravure &amp; finition</h3>
      <p class="ek-step__text">La gravure laser est réalisée à la main, suivie d'une colorisation soignée pour révéler le motif.</p>
    </div>
  </div>
  <div class="col">
    <div class="ek-step">
      <div class="ek-num--step" aria-hidden="true">05</div>
      <h3 class="ek-step__title">Purification &amp; consécration</h3>
      <p class="ek-step__text">L'objet est purifié et chargé lors d'un rituel dédié, réalisé en votre nom et selon votre intention.</p>
    </div>
  </div>
  <div class="col">
    <div class="ek-step">
      <div class="ek-num--step" aria-hidden="true">06</div>
      <h3 class="ek-step__title">Livraison &amp; suivi</h3>
      <p class="ek-step__text">L'objet vous est expédié avec une notice d'utilisation. Pour la Transformation, le suivi commence dès réception.</p>
    </div>
  </div>
</div>
```

---

## 5. Approche

- **Type de bloc** : `text_section`
- **Titre admin (info)** : `Homepage — Approche`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Mon approche` |
| **Section Variant** (`field_section_variant`) | `section-alt` |
| **Subtitle** (`field_subtitle`) | `Ni croyance imposée, ni jargon ésotérique lourd. Une pratique artisanale et intentionnelle, accessible à tous.` |
| **Body** (`body`, format Full HTML) | Voir HTML ci-dessous |

**Contenu Body (Full HTML)** :

```html
<h2>Bienveillante, rigoureuse et sans jugement</h2>
<div class="ek-disclaimer--centered">
  <p>Mon accompagnement ne remplace en aucun cas un suivi médical. Il vient en complément des traitements conventionnels pour une approche holistique de votre bien-être.</p>
</div>
```

---

## 6. A propos

- **Type de bloc** : `about`
- **Titre admin (info)** : `Homepage — A propos`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Qui suis-je ?` |
| **Image** (`field_image`) | Uploader le portrait de l'artisan (photo a fournir, alt text : `Portrait artisan ésotérique`) |
| **Body** (`body`, format Full HTML) | Voir HTML ci-dessous |
| **Signature** (`field_signature`) | `— Omliance` |

**Contenu Body (Full HTML)** :

```html
<h2>Artisan de l'intention, praticien du soin</h2>
<p class="ek-about__text">
  Je crée des objets rituels depuis plusieurs années, à la frontière du design numérique, de l'artisanat manuel et des pratiques énergétiques. Chaque talisman est unique — pensé pour une personne, dans un contexte précis, avec une intention claire.
</p>
<p class="ek-about__text">
  Mon approche est pragmatique : je ne vends pas du mystère, je propose un outil symbolique ancré dans le réel, fabriqué avec soin, et un espace d'accompagnement bienveillant.
</p>
```

---

## 7. FAQ

- **Type de bloc** : `faq`
- **Titre admin (info)** : `Homepage — FAQ`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Eyebrow** (`field_eyebrow`) | `Questions fréquentes` |
| **Body** (`body`, format Full HTML) | Voir HTML ci-dessous |

**Contenu Body (Full HTML)** :

```html
<h2>Ce que vous souhaitez savoir</h2>
<div class="ek-faq">
  <div class="ek-faq__item is-open">
    <button class="ek-faq__q">
      Dois-je croire à l'ésotérisme pour que ça fonctionne ?
      <span class="ek-faq__icon">+</span>
    </button>
    <div class="ek-faq__a">Non. Le talisman est avant tout un objet symbolique qui ancre une intention. L'ouverture d'esprit suffit. La croyance n'est ni requise ni demandée.</div>
  </div>
  <div class="ek-faq__item">
    <button class="ek-faq__q">
      Comment se déroule la commande ?
      <span class="ek-faq__icon">+</span>
    </button>
    <div class="ek-faq__a">Tout se fait en ligne. Vous remplissez un formulaire, on échange pour affiner votre intention, puis je crée, fabrique et vous envoie votre talisman. Pour la Transformation, le suivi démarre à réception.</div>
  </div>
  <div class="ek-faq__item">
    <button class="ek-faq__q">
      Quels délais pour recevoir mon talisman ?
      <span class="ek-faq__icon">+</span>
    </button>
    <div class="ek-faq__a">Entre 10 et 15 jours ouvrés entre votre commande et la livraison, selon les délais de création et d'expédition.</div>
  </div>
  <div class="ek-faq__item">
    <button class="ek-faq__q">
      Pouvez-vous travailler sur des intentions liées à la santé ?
      <span class="ek-faq__icon">+</span>
    </button>
    <div class="ek-faq__a">Oui, dans le cadre d'un complément holistique uniquement. Mon accompagnement ne remplace en aucun cas un suivi médical. Il vient en soutien d'une démarche globale de bien-être.</div>
  </div>
  <div class="ek-faq__item">
    <button class="ek-faq__q">
      Le talisman est-il personnalisé pour moi spécifiquement ?
      <span class="ek-faq__icon">+</span>
    </button>
    <div class="ek-faq__a">Oui, entièrement. Le design, les symboles choisis et le rituel de consécration sont créés pour votre intention, votre contexte et votre énergie. Aucun talisman ne ressemble à un autre.</div>
  </div>
  <div class="ek-faq__item">
    <button class="ek-faq__q">
      Quelle est la différence entre les deux offres ?
      <span class="ek-faq__icon">+</span>
    </button>
    <div class="ek-faq__a">Le Talisman à 39€ est l'objet seul, créé et expédié avec une notice. La Transformation à 290€ inclut le talisman, des rituels réalisés pour vous, un accompagnement personnalisé et un suivi sur 4 semaines.</div>
  </div>
</div>
```

---

## 8. CTA final

- **Type de bloc** : `cta_final`
- **Titre admin (info)** : `Homepage — CTA final`
- **Champs** :

| Champ | Valeur |
|-------|--------|
| **Body** (`body`, format Full HTML) | `<h2 class="ek-cta__title">Prêt(e) à ancrer<br>vos <em>intentions</em> dans le réel ?</h2>` |
| **Subtitle** (`field_subtitle`) | `Choisissez votre point de départ. Tout commence par une intention.` |
| **CTA Links** (`field_cta_links`) | Lien 1 : Titre = `Commander un talisman — 39€`, URL = `/talisman` |
| | Lien 2 : Titre = `Transformation complète — 290€`, URL = `/transformation` |

---

## Resume : Ordre de placement dans la region Content

| # | Bloc | Type | Poids suggere |
|---|------|------|---------------|
| 1 | Homepage — Hero | `hero` | -10 |
| 2 | Homepage — Pain points | `section_cards` | -9 |
| 3 | Homepage — Offres | `section_cards` | -8 |
| 4 | Homepage — Processus | `process_steps` | -7 |
| 5 | Homepage — Approche | `text_section` | -6 |
| 6 | Homepage — A propos | `about` | -5 |
| 7 | Homepage — FAQ | `faq` | -4 |
| 8 | Homepage — CTA final | `cta_final` | -3 |

> **Restriction de visibilite** : Chaque bloc doit etre configure avec la restriction de page `<front>` uniquement.

> **Format de texte** : Tous les champs Body doivent utiliser le format **Full HTML** pour que les classes CSS et le SVG soient preserves.

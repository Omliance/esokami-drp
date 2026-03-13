# Esokami — Design System & Integration Specs

> **Theme base Drupal:** Bootstrap 5 (via `bootstrap` base theme)
> **Objectif:** Reproduire le design Esokami "Terre & Lumiere" dans le theme Bootstrap Drupal.

---

## 1. Identite visuelle — "Terre & Lumiere"

Design **sobre, artisanal et haut de gamme**. Ambiance terre, or, nuit.
Parti pris : **luxe discret**. Pas de bling-bling ni mysticisme criard.

Mots-cles : minimalisme, elegance, artisanat, intentionnalite, geometrie sacree.

---

## 2. Palette de couleurs

### Couleurs principales

| Token | Hex | Usage |
|-------|-----|-------|
| `$cream` | `#F8F2E8` | Fond principal — creme doree chaude |
| `$cream-dark` | `#EDE3D5` | Fond sections alternees |
| `$navy` | `#1A2640` | Fond sections fortes (processus, CTA, footer) — bleu nuit |

### Couleurs d'accent

| Token | Hex | Usage |
|-------|-----|-------|
| `$gold` | `#C4962A` | Or — accents fins, hover, traits decoratifs. Usage **parcimonieux** |
| `$terracotta` | `#B8724F` | Terracotta — accent secondaire chaud |

### Couleurs de texte

| Token | Hex | Usage |
|-------|-----|-------|
| `$earth` | `#302A24` | Texte courant — brun fonce chaud |
| `$earth-light` | `#705A4A` | Texte secondaire, descriptions — brun moyen |
| `$navy` | `#1A2640` | Titres sur fond clair (= navy) |
| `$cream-light` | `#F0E8DE` | Texte sur fond bleu nuit |

### Couleurs derivees (opacites)

| Token | Valeur | Usage |
|-------|--------|-------|
| `$border-terracotta` | `rgba(184, 114, 79, 0.18)` | Bordures subtiles terracotta |
| `$border-structural` | `rgba(26, 38, 64, 0.12)` | Bordures structurelles |
| `$gold-wash` | `rgba(196, 150, 42, 0.1)` | Fond or dilue (disclaimers, blockquotes) |

### Sur fond sombre (`$navy`)

| Element | Valeur |
|---------|--------|
| Sous-titres section | `rgba(240, 232, 222, 0.5)` |
| Descriptions offres featured | `rgba(240, 232, 222, 0.62)` |
| Features offres featured | `rgba(240, 232, 222, 0.65)` |
| Liens footer | `rgba(240, 232, 222, 0.45)` |
| Tagline footer | `rgba(240, 232, 222, 0.35)` |
| Copyright/disclaimer footer | `rgba(240, 232, 222, 0.28)` |
| Bordures sur fond sombre | `rgba(240, 232, 222, 0.06)` |

### Mapping Bootstrap

| Variable Bootstrap | Valeur Esokami |
|-------------------|---------------|
| `$primary` | `$navy` (#1A2640) |
| `$secondary` | `$gold` (#C4962A) |
| `$body-bg` | `$cream` (#F8F2E8) |
| `$body-color` | `$earth` (#302A24) |
| `$link-color` | `$navy` |
| `$link-hover-color` | `$gold` |
| `$link-decoration` | none |
| `$font-family-sans-serif` | `'DM Sans', system-ui, -apple-system, sans-serif` |
| `$headings-font-family` | `'Fraunces', Georgia, serif` |
| `$line-height-base` | 1.75 |
| `$headings-font-weight` | 400 |
| `$headings-line-height` | 1.2 |
| `$headings-color` | `$navy` |
| `$border-radius` | `$radius` |
| `$border-radius-sm` | `$radius-sm` |

---

## 3. Typographie

### Familles

| Usage | Police | Source | Fallbacks |
|-------|--------|--------|-----------|
| Titres (h1-h6, citations) | **Fraunces** | Google Fonts | Georgia, serif |
| Corps (paragraphes, nav, boutons) | **DM Sans** | Google Fonts | system-ui, -apple-system, sans-serif |
| Logo decoratif | **Laviosa** | Fichiers locaux (woff/woff2) | — |

### Google Fonts URL

```
https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap
```

### Echelle des titres

Tous : Fraunces, weight 400, line-height 1.2, couleur `$navy`, letter-spacing -0.01em.

| Niveau | Taille | Notes |
|--------|--------|-------|
| h1 | `clamp(38px, 5.5vw, 62px)` | Fluid |
| h2 | `clamp(28px, 4vw, 42px)` | Titre de section |
| h3 | `clamp(22px, 3vw, 34px)` | Weight **500** |
| h4 | `clamp(18px, 2vw, 24px)` | — |
| h5 | 18px | Fixe |
| h6 | 16px | Fixe |

### Corps de texte

- Famille : DM Sans
- Taille : 16px
- Line-height : 1.75
- Weight : 400
- Couleur : `$earth`

### Italique (`<em>`)

Utilise **Fraunces** en italique (pas DM Sans).

### Liens

- Normal : `$navy`, pas de soulignement, transition 0.25s
- Hover : `$gold`
- Dans contenu pages : soulignement `text-decoration-color: $gold`, epaisseur 1px, offset 3px

---

## 4. Espacements

| Token | Valeur |
|-------|--------|
| `$space-xs` | 8px |
| `$space-sm` | 16px |
| `$space-md` | 32px |
| `$space-lg` | 64px |
| `$space-xl` | 96px |

Padding vertical sections : `clamp(64px, 9vw, 104px)`, horizontal : `5%`.

---

## 5. Formes et ombres

| Token | Valeur | Usage |
|-------|--------|-------|
| `$radius` | 4px | Cartes, boutons, disclaimers |
| `$radius-sm` | 2px | Elements petits |
| `$shadow-rest` | `0 2px 24px rgba(26, 38, 64, 0.07)` | Ombre repos |
| `$shadow-hover` | `0 8px 36px rgba(184, 114, 79, 0.18)` | Ombre hover cartes |

---

## 6. Boutons

Base commune : padding `13px 30px`, DM Sans 12px weight 500, letter-spacing 0.1em, uppercase, border-radius 4px, transition 0.3s.

| Classe | Fond | Texte | Bordure | Hover |
|--------|------|-------|---------|-------|
| `.btn-navy` | `$navy` | `$cream-light` | `$navy` | Opacity 0.88 + trait or anime en bas |
| `.btn-outline` | transparent | `$earth` | `$border-structural` | Bordure/texte → `$gold` |
| `.btn-gold` | `$gold` | `#1A0E05` | `$gold` | Opacity 0.86 |
| `.btn-outline-cream` | transparent | `rgba(240,232,222,0.75)` | `rgba(240,232,222,0.22)` | Bordure/texte → `$gold` |

### CTA navigation

Padding `8px 20px`, bordure `$gold`, texte `$gold`, 11px, uppercase.
Hover : fond `$gold`, texte `$cream`.

---

## 7. Composants

### 7.1 Eyebrow (sur-titre)

DM Sans, weight 300, **10px**, letter-spacing 0.2em, uppercase, `$gold`.
Precede d'un trait horizontal or 28px x 1px.

### 7.2 Section label (centre)

Meme style que eyebrow mais centre, trait or 20px **de chaque cote**. Margin-bottom 14px.

### 7.3 En-tete de section (`.section-header`)

Centre, max-width 560px, margin auto. Contient : section-label + h2 (mb 8px) + paragraphe (weight 300).
Variante `.section-header--left` : label perd son trait droit.

### 7.4 Cartes "Pain points"

- Bordure : 1px solid `$border-terracotta`
- Padding : 32px horizontal, 26px vertical
- Fond : `$cream`, radius 4px
- **Hover** : bordure `$gold`, ombre `$shadow-hover`, translateY(-3px)
- Icone : SVG 30x30 stroke `$gold`, mb 16px
- Titre : Fraunces 20px weight 500, `$navy`
- Texte : 14px, `$earth-light`, line-height 1.65

### 7.5 Cartes offres

- Bordure : 1px solid `$border-terracotta`, padding 36px 28px, radius 4px
- **Hover** : ombre `$shadow-hover`, translateY(-3px) + trait or 2px top (scaleX 0→1)
- Label : 10px, ls 0.18em, uppercase, `$gold`
- Titre : Fraunces 28px weight 400, `$navy`
- Prix : Fraunces 40px weight 300, `$navy`, ls -0.02em. Euro en exposant 0.3em `$gold`
- Description : 14px, `$earth-light`, lh 1.65
- Features : pas de puces, point rond 4px `$gold`, 13px

**Variante featured** : fond `$navy`, texte `$cream-light`, bouton `.btn-gold`.

### 7.6 Etapes processus (fond sombre)

Padding 40px 28px, centre. Numero Fraunces 72px weight 300, `$gold` opacity 0.28.
Titre Fraunces 22px, `$cream-light`. Texte 13px opacity 0.5.

### 7.7 Disclaimer sante

Padding 22px 26px, bordure gauche 2px `$gold`, fond `$gold-wash`.
Radius 0 gauche, 4px droite. Texte 14px italique `$earth-light`.

### 7.8 FAQ (accordeon)

Max-width 640px centre. Un seul item ouvert a la fois.
Question : Fraunces 20px, `$navy`, hover `$gold`.
Icone `+` → rotation 45deg quand ouvert. `aria-expanded` requis.

### 7.9 Section "A propos"

2 colonnes (5/7). Image ratio 3/4, `filter: saturate(0.82)`.
Signature : Fraunces 24px italique, `$navy`, weight 300.

### 7.10 CTA final (fond sombre)

SVG geometrique fond (fleur de vie, opacity 0.04, `$gold`).
Titre avec `<em>` en or. Boutons : 1 gold + 2 outline-cream.

### 7.11 Separateur geometrique

SVG 28x28, `$gold` opacity 0.35, cercles concentriques + croix.

---

## 8. Navigation (header)

- Fond : `$cream`, bordure inf 1px `$border-terracotta`
- Layout : flex space-between (logo | nav | CTA)
- **Scroll > 40px** : classe `is-scrolled` → box-shadow `0 2px 20px rgba(26,38,64,0.08)`
- Logo : Fraunces 22px weight 400, `$navy`
- Liens nav : DM Sans 11px, ls 0.1em, uppercase, `$earth-light`, hover `$gold`

---

## 9. Footer

Fond `$navy`, bordure sup `rgba(240,232,222,0.06)`.
Conteneur max-width 1100px, padding `48px 5% 28px`.
Grille 3 cols desktop (1.6fr 1fr 1fr, gap 48px).

- Logo : Fraunces 22px, `$cream-light`
- Titres colonnes : DM Sans 10px, ls 0.16em, uppercase, `$gold`
- Liens : 13px, `rgba(240,232,222,0.45)`, hover `$gold`

---

## 10. Pages standard

### En-tete page

Fond `$navy`, titre `clamp(38px, 5.5vw, 60px)`, `$cream-light`, centre.
Trait or 64px sous le titre.

### Contenu

Max-width 1200px centre. h2 precede d'un trait or 32px. Listes avec point rond 4px `$gold`.
Blockquote = meme style que disclaimer sante.

---

## 11. Grille

Utilise le systeme de grille Bootstrap 5 (flexbox). Pas de grille CSS custom.

Breakpoints (mobile-first) : sm 576px, md 768px, lg 992px, xl 1200px, xxl 1400px (defauts Bootstrap).

---

## 12. Animations

### Scroll reveal (`.reveal`)

Initial : opacity 0, translateY(18px). Visible : opacity 1, translateY(0).
Transition 0.65s ease. IntersectionObserver threshold 12%.

### Scroll reveal groupe (`.reveal-group`)

Delai echelonne : 0s, 0.1s, 0.2s, 0.3s, 0.35s, 0.4s. Transition 0.55s.

### Parallaxe hero

Facteur 0.06, respecte `prefers-reduced-motion: reduce`.

### Hover cartes

- Pain points : bordure gold, ombre, translateY(-3px)
- Offres : ombre, translateY(-3px), trait or top (scaleX)
- Bouton primaire : trait or bottom (width 0→100%)

---

## 13. Homepage (8 sections)

1. **Hero** — 2 cols (7/5), eyebrow + h1 avec `<em>` or + 2 CTA
2. **Identification** — fond `$cream-dark`, 3 cartes pain points
3. **Separateur geometrique**
4. **Offres** — 3 cartes (Etincelle 29EUR, Flamme 69EUR featured, Brasier 249EUR) + disclaimer
5. **Processus** — fond sombre, 6 etapes
6. **Approche** — fond `$cream-dark`, citation or
7. **Qui suis-je** — 2 cols (5/7), photo + bio + signature
8. **FAQ** — 8 questions accordeon
9. **CTA final** — fond sombre, SVG geometrique, 3 boutons

---

## 14. Pages du site

| Page | URL |
|------|-----|
| Accueil | `/` |
| A propos | `/a-propos` |
| Contact | `/contact` |
| Mentions legales | `/mentions-legales` |
| CGV | `/conditions-generales-de-vente` |
| Confidentialite | `/politique-de-confidentialite` |
| Boutique | `/boutique` |
| Etincelle | `/etincelle` |
| Flamme | `/flamme` |
| Brasier | `/brasier` |

---

## 15. Disclaimer sante (texte exact)

> Mon accompagnement ne remplace en aucun cas un suivi medical. Il vient en complement des traitements conventionnels pour une approche holistique de votre bien-etre.

---

## 16. Accessibilite

- SVGs decoratifs : `aria-hidden="true"`
- Sections : `aria-label` ou `aria-labelledby`
- FAQ : `aria-expanded`, `role="region"`
- Scroll reveal : fallback si pas d'IntersectionObserver
- Parallaxe : respecte `prefers-reduced-motion`
- Skip to content : classe `sr-only`
- FAQ focus-visible : outline 1px `$gold`, offset 2px

---

## 17. Assets requis

### Polices locales

- `fonts/laviosa.woff`
- `fonts/laviosa.woff2`

### Images

- `images/esokami-hero.jpg` (500x600)

### JS (vanilla, sans dependance)

- FAQ accordeon
- Scroll reveal (IntersectionObserver)
- Parallaxe hero
- Ombre nav au scroll

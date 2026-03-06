# Esokami Theme - Bootstrap 5 SASS Drupal 11 Theme

## Description

Esokami est un thème custom pour Drupal 11 basé sur Bootstrap 5.3.0 avec un workflow SASS/Gulp complet. Il utilise le sous-thème Bootstrap de Drupal et permet une personnalisation complète via SCSS.

## Caractéristiques

- Bootstrap 5.3.0
- Bootstrap Icons 1.11.0
- Compilation SASS avec Gulp
- BrowserSync pour le rechargement automatique
- Autoprefixer pour la compatibilité navigateurs
- Sourcemaps pour le debugging
- Conversion px vers rem automatique
- Minification CSS

## Prérequis

**IMPORTANT** : Les commandes npm et gulp s'exécutent sur l'hôte (votre machine locale), PAS dans DDEV.

- Node.js (version recommandée : 18.x ou supérieure)
- npm (inclus avec Node.js)
- DDEV démarré pour BrowserSync (`ddev start`)

## Installation

### Première installation

```bash
# Se placer dans le répertoire du thème
cd web/themes/custom/esokami

# Installer les dépendances npm
npm install
```

Cette commande installe tous les packages nécessaires définis dans `package.json` :
- gulp et ses plugins
- sass
- bootstrap et bootstrap-icons
- browser-sync
- etc.

## Commandes disponibles

### Mode développement (recommandé)

```bash
gulp
```

Lance le mode watch complet :
- Compile automatiquement les fichiers SCSS à chaque modification
- Lance BrowserSync sur https://esokami-drp.ddev.site/
- Recharge le navigateur automatiquement lors des changements
- Affiche les erreurs de compilation en temps réel

**Astuce** : Gardez cette commande active pendant que vous développez.

### Compilation manuelle du CSS

```bash
gulp styles
```

Compile uniquement les fichiers SCSS vers CSS sans lancer le mode watch.
Utile pour :
- Build de production
- Compiler une fois sans surveiller les changements
- CI/CD pipelines

### Copier les fichiers JavaScript

```bash
gulp js
```

Copie les fichiers JavaScript nécessaires :
- bootstrap.min.js
- popper.min.js
- base.js (depuis le thème Bootstrap contrib)

## Structure des fichiers

```
esokami/
├── scss/                          # Sources SCSS (À ÉDITER)
│   ├── variables.scss             # Variables Bootstrap personnalisées
│   ├── typography.scss            # Configuration typographique
│   ├── mixins.scss                # Mixins réutilisables
│   └── style.scss                 # Styles custom du thème
├── css/                           # Fichiers générés (NE PAS ÉDITER)
│   ├── style.css                  # CSS compilé
│   ├── style.min.css              # CSS compilé et minifié
│   ├── bootstrap.css              # Bootstrap compilé
│   └── bootstrap.min.css          # Bootstrap compilé et minifié
├── js/                            # JavaScript
│   ├── bootstrap.min.js
│   ├── popper.min.js
│   └── base.js
├── gulpfile.js                    # Configuration Gulp
├── package.json                   # Dépendances npm
└── README.txt                     # Ce fichier

```

## Workflow de développement

### 1. Démarrer l'environnement

```bash
# Dans le répertoire racine du projet
ddev start

# Dans le répertoire du thème
cd web/themes/custom/esokami
gulp
```

### 2. Éditer les fichiers SCSS

Ouvrez et modifiez les fichiers dans `scss/` :

- **variables.scss** : Personnaliser les variables Bootstrap (couleurs, espacements, breakpoints, etc.)
- **typography.scss** : Configuration des polices et de la typographie
- **mixins.scss** : Créer des mixins réutilisables
- **style.scss** : Ajouter vos styles custom

### 3. Voir les changements

- Gulp détecte automatiquement les modifications
- Le CSS est recompilé instantanément
- BrowserSync recharge le navigateur automatiquement
- Les erreurs SCSS s'affichent dans le terminal

### 4. Si les styles ne s'appliquent pas

```bash
# Vider le cache Drupal
ddev drush cr
```

Le cache Drupal peut parfois bloquer l'application des nouveaux styles.

## Configuration BrowserSync

BrowserSync est configuré pour proxifier votre site DDEV :
- URL proxy : https://esokami-drp.ddev.site/
- Recharge automatique lors des changements CSS/JS
- Synchronisation des actions entre navigateurs

Si l'URL de votre site DDEV est différente, modifiez-la dans `gulpfile.js` :

```javascript
browserSync.init({
  proxy: 'https://votre-site.ddev.site/',
});
```

## Fonctionnalités Gulp

### PostCSS Processors

Le thème utilise plusieurs processeurs PostCSS :

1. **Inline SVG** : Permet d'inclure des icônes Bootstrap Icons directement dans le CSS
2. **px to rem** : Convertit automatiquement les valeurs px en rem pour :
   - font, font-size, line-height
   - letter-spacing
   - margin et padding (toutes les variantes)

### Autoprefixer

Ajoute automatiquement les préfixes vendeurs pour supporter :
- Chrome 35+
- Firefox 38+
- Edge 12+
- Internet Explorer 10+
- iOS 8+, Safari 8+
- Android 4+
- Opera 12+

## Bonnes pratiques

### À FAIRE

- Toujours éditer les fichiers `.scss` dans le dossier `scss/`
- Utiliser le mode watch (`gulp`) pendant le développement
- Versionner les fichiers `.scss` dans Git
- Vider le cache Drupal après des changements importants
- Tester dans plusieurs navigateurs

### À NE PAS FAIRE

- Ne JAMAIS éditer directement les fichiers `.css` dans le dossier `css/`
- Ne pas versionner les fichiers `.css` générés (ils sont dans .gitignore)
- Ne pas modifier directement `node_modules/bootstrap/`
- Ne pas oublier de lancer `npm install` après un `git pull` si package.json a changé

## Personnalisation Bootstrap

### Modifier les variables Bootstrap

Éditez `scss/variables.scss` pour personnaliser Bootstrap :

```scss
// Exemple : changer les couleurs primaires
$primary: #007bff;
$secondary: #6c757d;

// Exemple : modifier les breakpoints
$grid-breakpoints: (
  xs: 0,
  sm: 576px,
  md: 768px,
  lg: 992px,
  xl: 1200px,
  xxl: 1400px
);

// Exemple : personnaliser les espacements
$spacer: 1rem;
```

### Utiliser les mixins Bootstrap

Bootstrap fournit de nombreux mixins utiles :

```scss
// Responsive
@include media-breakpoint-up(md) {
  .my-class {
    font-size: 1.5rem;
  }
}

// Flexbox
.my-container {
  @include make-container();
}
```

## Dépannage

### Erreur "gulp: command not found"

```bash
# Installer gulp globalement
npm install -g gulp-cli

# Ou utiliser npx
npx gulp
```

### Erreur "node-sass" ou "sass" binding

```bash
# Réinstaller les dépendances
rm -rf node_modules package-lock.json
npm install
```

### BrowserSync ne recharge pas

1. Vérifier que DDEV est démarré : `ddev status`
2. Vérifier l'URL proxy dans gulpfile.js
3. Vider le cache : `ddev drush cr`
4. Redémarrer gulp

### Les styles ne s'appliquent pas

1. Vérifier qu'il n'y a pas d'erreurs de compilation dans le terminal
2. Vider le cache Drupal : `ddev drush cr`
3. Vérifier que le fichier CSS est bien généré dans `css/`
4. Inspecter l'élément dans le navigateur pour voir quel CSS est appliqué

## Versioning Git

**Fichiers à versionner** :
- `scss/**/*.scss` (sources)
- `gulpfile.js`
- `package.json`
- `package-lock.json` (lockfile pour reproductibilité)

**Fichiers ignorés** (.gitignore) :
- `node_modules/` (trop volumineux, recréé avec npm install)
- `css/*.css` (générés, recréés avec gulp)
- `css/*.css.map` (sourcemaps)

## Production

### Build pour la production

Avant de déployer en production :

```bash
# Compiler le CSS
gulp styles

# Vider le cache
ddev drush cr

# Commit
git add scss/ css/
git commit -m "style: mise à jour des styles"
git push
```

**Note** : En production, les fichiers CSS sont recréés automatiquement. Assurez-vous que `node_modules/` existe en prod ou que le CSS est généré dans votre pipeline CI/CD.

## Support et documentation

### Documentation Bootstrap
- https://getbootstrap.com/docs/5.3/

### Documentation Gulp
- https://gulpjs.com/

### Documentation SASS
- https://sass-lang.com/documentation/

### Drupal Bootstrap Theme
- https://www.drupal.org/project/bootstrap

## Crédits

- Auteur : @hatuhay
- Bootstrap : https://getbootstrap.com/
- Bootstrap Icons : https://icons.getbootstrap.com/
- License : MIT

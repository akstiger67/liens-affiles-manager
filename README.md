# Liens Affiliés Manager

**Liens Affiliés Manager** est un plugin WordPress qui vous permet de gérer facilement vos liens d'affiliation via un type de contenu personnalisé. Le plugin génère automatiquement une URL réécrite basée sur un slug personnalisable et redirige vos visiteurs vers l'URL d'affiliation saisie. De plus, dans l'administration, une colonne dédiée affiche le lien réécrit avec un bouton "Copier" pour une récupération rapide.

## Fonctionnalités

- **Type de contenu personnalisé "Liens affiliés"**  
  Gérez vos liens d'affiliation directement depuis l'interface WordPress.

- **Meta Box pour l'URL d'affiliation**  
  Saisissez l'URL d'affiliation lors de la création ou de l'édition d'un lien affilié.

- **Redirection front-end**  
  Lorsqu'un visiteur accède à une URL du type `domaine.com/[slug]/[nomdusite]`, il est automatiquement redirigé vers l'URL d'affiliation définie.

- **Slug intermédiaire personnalisable**  
  Choisissez parmi une dizaine d'options (ex: `go`, `link`, `see`, `check`, `click`, `aller`, `lien`, `voir`, `regarder`, `cliquer`, `acceder`, `visiter`) pour personnaliser l'URL de vos liens affiliés via une page de réglages.

- **Colonne personnalisée dans l'administration**  
  Affiche le lien réécrit dans la liste des "Liens affiliés" avec un bouton "Copier" qui permet de récupérer facilement l'URL.

## Installation

1. **Télécharger ou cloner le dépôt**  
   Clonez ou téléchargez ce dépôt sur votre ordinateur.

2. **Copier le dossier du plugin**  
   Placez le dossier `liens-affiles-manager` dans le répertoire `/wp-content/plugins/` de votre installation WordPress.

3. **Installation via l'administration WordPress (optionnel)**  
   Vous pouvez zipper le dossier et l'installer via **Extensions > Ajouter > Téléverser une extension**.

4. **Activation**  
   Activez le plugin depuis le menu **Extensions** de l'administration WordPress.

5. **Configuration**  
   Rendez-vous dans **Réglages > Liens Affiliés Manager** pour choisir l'intermédiaire du slug.  
   **Attention :** Après modification, pensez à rafraîchir vos permaliens dans **Réglages > Permaliens**.

## Utilisation

### Création d'un lien affilié

1. Dans l'administration WordPress, accédez au menu **Liens affiliés**.
2. Cliquez sur **Ajouter Nouveau** pour créer un nouveau lien affilié.
3. Saisissez le titre (qui sera utilisé pour générer l'URL réécrite) et l'URL d'affiliation dans la meta box.
4. Enregistrez le lien.  
   L'URL réécrite sera accessible sous la forme :

Par exemple, si le slug choisi est `go` et que le titre est `mon-affilie`, l'URL sera :  


### Administration des liens

Dans la liste des "Liens affiliés", une colonne **Lien réécrit** affiche l'URL générée. Un bouton **Copier** à côté permet de copier rapidement l'URL dans le presse-papiers.

## Configuration

- **Modifier le slug intermédiaire**  
Rendez-vous dans **Réglages > Liens Affiliés Manager** pour choisir le slug intermédiaire parmi les options proposées.  
Après modification, n'oubliez pas de rafraîchir vos permaliens via **Réglages > Permaliens**.

## Contribuer

Les contributions sont les bienvenues !  
Si vous avez des suggestions, des rapports de bugs ou des améliorations, n'hésitez pas à ouvrir une [issue](https://github.com/akstiger67/liens-affiles-manager/issues) ou à soumettre une pull request.

## Auteur

Julien Web
[https://julienweb.com](https://julienweb.com)


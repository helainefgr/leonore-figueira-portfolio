# Installer le portfolio sur WordPress (OVH)

Fichier à installer : **leonore-portfolio.zip** (ne pas le décompresser).

## 1. Installer WordPress chez OVH (une seule fois)

1. Va dans ton espace client OVH, puis **Web Cloud** et **Hébergements**, et choisis ton hébergement.
2. Ouvre l'onglet **Modules en 1 clic**, clique sur **Ajouter un module** et choisis **WordPress**.
3. Choisis ton nom de domaine et valide. OVH t'envoie tes identifiants par e-mail.
4. Dans l'onglet **Multisite**, vérifie que le **SSL** est activé pour ton domaine, pour avoir un site en `https://`.

## 2. Installer le thème (2 minutes)

1. Connecte-toi à ton WordPress : `https://ton-domaine.fr/wp-admin`.
2. Va dans **Apparence**, puis **Thèmes**, **Ajouter** et **Téléverser un thème**.
3. Choisis `leonore-portfolio.zip`, puis clique sur **Installer maintenant** et ensuite sur **Activer**.

Et c'est tout. À l'activation, le thème crée automatiquement :

- les 6 pages : Projets (page d'accueil), Parcours, La serre habitée, La Palestra, Résidence jardins et Projets divers ;
- le menu PROJETS · PARCOURS · CONTACT ;
- les adresses : `/parcours/`, `/l1s2/`, `/intensif/`, `/l2s1/`, `/divers/`.

Toutes les images sont incluses dans le thème. Rien à téléverser en plus.

### Si WordPress refuse le fichier (« fichier trop volumineux »)

Envoie le thème par FTP :

1. Décompresse le zip sur ton ordinateur.
2. Avec FileZilla et tes identifiants FTP OVH (dans l'onglet **FTP-SSH** de ton hébergement), dépose le dossier `leonore-portfolio` dans `www/wp-content/themes/`.
3. Dans WordPress, va dans **Apparence**, puis **Thèmes**, et clique sur **Activer**.

## 3. Petites finitions conseillées

- Va dans **Réglages**, puis **Général**. Choisis le titre du site, qui apparaît dans l'onglet du navigateur et sur Google, par exemple « Léonore Figueira – Portfolio Architecture ».
- Dans **Pages** : WordPress crée une « Page d'exemple » à l'installation. Tu peux la mettre à la corbeille.
- Dans **Articles** : même chose pour l'article « Bonjour tout le monde ! ».
- Une fois le nouveau site en ligne, tu peux supprimer l'ancien site Wix, ou le passer en « non publié ».

## Modifier un texte plus tard

1. Va dans **Pages**, ouvre la page voulue, puis clique sur le bloc « HTML personnalisé ».
2. Cherche ton texte (Ctrl+F ou Cmd+F), modifie-le, puis clique sur **Mettre à jour**.

Ne touche pas aux valeurs `left:`, `top:` et `width:`, qui placent les éléments exactement comme sur Wix.

Remarque : dans l'éditeur, l'aperçu du bloc n'affiche pas les images. C'est normal, elles s'affichent bien sur le site.

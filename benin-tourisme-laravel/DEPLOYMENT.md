# Déploiement — bonnearrivee.bj (OVH + Nextcloud)

> Checklist opérationnelle. Suis les étapes dans l'ordre.

---

## 0. Pré-requis

- Hébergement OVH actif (idéalement « Web Pro », « Performance », ou VPS/Cloud Web)
- Domaine **bonnearrivee.bj** chez OVH (zone DNS modifiable)
- Compte Nextcloud **https://nextcloud.dahomeyinfo.fr** avec droits de création de dossier et de partage
- Compte **KKiaPay** avec clés publiques et privées de production
- Compte SMTP pour envoyer les emails magic link (OVH, Mailtrap, Mailgun, ou autre)

---

## 1. Préparation du repo local

### 1.1 — Créer le `.env.production` final

```bash
cp .env.production.example .env.production
# Éditer .env.production avec un vrai éditeur, remplir tous les <A_REMPLIR>
```

### 1.2 — Générer la clé Laravel pour la prod

```bash
php artisan key:generate --show
# Copier la valeur dans .env.production → APP_KEY
```

### 1.3 — Pousser sur GitHub

```bash
git add .
git commit -m "Prepare production deployment"
git push origin main
```

---

## 2. Base de données OVH

### 2.1 — Créer une base MySQL depuis l'espace client OVH

1. Espace client OVH → ton hébergement → onglet **Bases de données**
2. **Créer une base de données**
   - Type : MySQL
   - Version : MySQL 8.0 (ou MariaDB 10.11)
   - Nom : `bonnearrivee` (ou similaire)
   - Utilisateur : `bonnearrivee_user`
   - Mot de passe : généré par OVH (le copier)
3. Attendre 5-10 min la création

### 2.2 — Noter les 4 valeurs dans `.env.production`

- `DB_HOST` → ex: `bonnearrivee.mysql.db` (fourni par OVH)
- `DB_DATABASE` → nom de la base
- `DB_USERNAME` → user créé
- `DB_PASSWORD` → mot de passe fourni

---

## 3. Upload sur OVH

### Option A — Via SSH + Git (hébergement Pro, Performance, VPS, Cloud Web)

```bash
# Depuis ton ordi, SSH sur l'hébergement
ssh <login>@<ssh.cluster0XX.hosting.ovh.net>

# Puis sur le serveur :
cd www
git clone https://github.com/DAHOMEYDISCOVERY/bonnearrivee.git .
# Si repo privé, utiliser un deploy key SSH ou un PAT GitHub
```

### Option B — Via FTP (hébergement Perso ou sans SSH)

1. Télécharger FileZilla ou WinSCP
2. Connexion FTP OVH :
   - Hôte : `ftp.cluster0XX.hosting.ovh.net`
   - Login + mot de passe FTP OVH
3. Avant upload, exécuter en local :
   ```bash
   composer install --no-dev --optimize-autoloader
   bash tools/minify-css.sh
   php artisan optimize
   ```
4. Uploader TOUT le contenu de `benin-tourisme-laravel/` dans le dossier `www/` du serveur (sauf `node_modules/` et `.git/`)
5. Uploader séparément `.env.production` et le renommer en `.env` sur le serveur
6. Permissions : clic droit sur `storage/` et `bootstrap/cache/` → **755** (ou 775 si groupe web nécessaire)

### Dans tous les cas, après upload :

Déplacer la racine web vers `public/` :
- Via OVH : espace client → ton hébergement → **Multisite** → éditer le domaine → **Dossier racine** = `www/public`
- Ou créer un `.htaccess` à la racine qui redirige vers `/public/`

---

## 4. Première initialisation (sur le serveur)

```bash
cd ~/www

# Installer les dépendances (si upload FTP, deja fait localement)
composer install --no-dev --optimize-autoloader

# Permissions
chmod -R 775 storage bootstrap/cache

# Migrer la BDD
php artisan migrate --force

# Lier le dossier public/storage
php artisan storage:link

# Cacher config, routes, vues
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 5. Domaine + SSL

### 5.1 — Pointer `bonnearrivee.bj` sur l'hébergement OVH

Espace client → **Domaines** → `bonnearrivee.bj` → **Multisite** → **Associer** → sélectionner l'hébergement.

### 5.2 — Activer SSL Let's Encrypt (gratuit)

Espace client → ton hébergement → onglet **Multisite** → clic sur `bonnearrivee.bj` → **Modifier SSL** → cocher « Let's Encrypt ». Attente 30-60 min pour l'activation.

### 5.3 — Forcer HTTPS

Ajouter à la racine du site un fichier `.htaccess` ou vérifier qu'il contient :
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 6. Nextcloud — upload des gros fichiers

### 6.1 — Créer les dossiers partagés

Dans Nextcloud → créer :
- `/Bonne Arrivée/catalogue-wadunu/` (pour le PDF)
- `/Bonne Arrivée/archives/wikimedia/` (pour les 462 Mo d'originaux, backup)
- `/Bonne Arrivée/archives/images-src/` (pour les 176 Mo d'originaux _src/)

### 6.2 — Upload des archives

Via l'interface web Nextcloud ou le client desktop :
- Glisser-déposer `/storage/app/private/wikimedia-originals/*` vers `archives/wikimedia/`
- Glisser-déposer `/public/assets/img/_src/*` vers `archives/images-src/`

### 6.3 — PDF catalogue Wadunu

**Option simple** : partage lien privé avec mot de passe
1. Upload le PDF dans `catalogue-wadunu/catalogue.pdf`
2. Clic droit → **Partager** → **Lien de partage** → activer **mot de passe**
3. Copier le lien → coller dans `.env` → `WADUNU_PDF_SHARE_URL=https://nextcloud.dahomeyinfo.fr/s/XXXXX`
4. Le controller Laravel sera modifié pour rediriger vers ce lien (plutôt que servir le fichier local)

**Option avancée (WebDAV)** : plus propre, le PDF reste accessible via Laravel qui le streame depuis Nextcloud. À faire si tu veux garder le viewer embarqué actuel.

---

## 7. Tâches planifiées (cron)

Dans l'espace client OVH → **Tâches planifiées (cron)** → ajouter :
```
Fréquence : chaque minute
Commande : /usr/bin/php8.3 /home/<user>/www/artisan schedule:run
```

Et une tâche pour la queue des mails :
```
Fréquence : chaque minute
Commande : /usr/bin/php8.3 /home/<user>/www/artisan queue:work --stop-when-empty
```

---

## 8. Tests post-déploiement

- [ ] https://bonnearrivee.bj charge
- [ ] HTTPS actif (cadenas vert dans navigateur)
- [ ] Admin accessible : https://bonnearrivee.bj/admin
- [ ] Page restaurants : https://bonnearrivee.bj/sejourner/restaurants
- [ ] Modale KKiaPay s'ouvre
- [ ] Email magic link reçu après paiement test (en mode KKIAPAY_SANDBOX=true d'abord)
- [ ] Clic sur magic link → viewer PDF s'ouvre
- [ ] PDF téléchargeable
- [ ] Formulaires fonctionnent
- [ ] Pages pages s'affichent vite (OPcache + Laravel caches actifs)

---

## 9. Déploiements suivants (après la 1re mise en ligne)

```bash
# En local
git add .
git commit -m "..."
git push origin main

# Sur le serveur OVH (via SSH)
cd ~/www
git pull origin main
bash tools/deploy.sh
```

Pas de SSH ? Alors FTP du changement + supprime `bootstrap/cache/*.php` à la main.

---

## Aide rapide

| Problème | Solution |
|---|---|
| Page blanche après deploy | Vérifier `storage/logs/laravel.log` — probablement erreur BDD ou permission |
| CSS/JS 404 | Vérifier que le dossier racine du site pointe sur `/public/` |
| Images cassées | `php artisan storage:link` |
| Session non maintenue | Vérifier `SESSION_SECURE_COOKIE=true` et HTTPS actif |
| Mail magic link pas reçu | Tester SMTP : `php artisan tinker` → `Mail::raw('test', fn($m) => $m->to('toi@gmail.com')->subject('test'))` |

# Ressources techniques pour le suivi du tutoriel présenté aux JRES 2026 - "1,2,3 Auth IDP!"

URL de la présentation aux JRES 2026 : https://2026.jres.org/programme/#modal-152
URL du support du tutoriel : https://services.renater.fr/federation/documentation/tutoriels/jres2026-152/index

## Résumé du tutoriel

Ce tutoriel s'adresse en priorité à un public d'administrateurs qui souhaitent mettre en place un nouveau fournisseur d'identités ou remplacer celui ou ceux de son établissement qui serait vieillissant. Il va permettre de guider à toutes les étapes de l'installation, de la configuration et du déploiement y compris dans le contexte des fédérations d'identité. Le tutoriel ne conduit pas à installer un environnement de production, mais permet d'aborder tous les concepts et d'être autonome ensuite dans le contexte de son propre SI.

Il repose sur un environnement constitué de conteneurs préconfigurés sur Docker, mais les principes évoqués sont également applicables à d'autres environnements, d'autres versions, voire d'autres implémentations. Pour pouvoir être suivi dans les meilleures conditions, ce tutoriel nécessite en prérequis d'être familiarisé avec :

- l'administration en ligne de commande d'un serveur Unix ;
- d'avoir installé git et Docker (ou Docker Desktop) et de savoir utiliser les commandes de base (dont la commande "docker compose").

Nous illustrerons les déploiements de deux solutions logicielles communément utilisées dans les établissements de l'enseignement supérieur et de la recherche :

- l'IdP Shibboleth (Shibboleth Identity Provider) ;
- le serveur CAS d'Apereo.

Nous présenterons l'installation en pas à pas de chacun avec :

- une configuration minimale de l'authentification sur un annuaire LDAP de test ;
- la génération des attributs ;
- quelques personnalisations basiques de l'interface utilisateur ;
- l'intégration à une fédération d'identité SAML ;
- l'utilisation des modules permettant notamment d'interrompre l'authentification et d'ajouter des fonctionnalités supplémentaires ;
- les délégations possibles entre un IdP Shibboleth et un serveur CAS en utilisant différents protocoles à disposition ;
- le renforcement de la sécurité lors de l'authentification de type MFA en s'appuyant sur le projet communautaire Esup-OTP.

## Pré-requis

Avoir Docker installé sur votre poste.

## Conteneurs du tutoriel

Les versions des conteneurs, de CAS, de Java, de NodeJS, d'Esup-OTP,... utilisés dans le tutoriel sont définis dans le fichiers `.env`.

### Liste des conteneurs qui seront utilisés tout le tutoriel

- `ldap` : annuaire LDAP pour les besoins du tutoriel. Il ne sera pas utile de se connecter dans ce conteneur
- `sp` : SP préconfiguré pour les besoins du tutoriel. Des opérations seront nécessaires sur ce conteneur
- `cas` : serveur CAS préconfiguré pour les besoins du tutoriel. Des opérations seront nécessaires sur conteneur
- `idp-formation` : conteneur vierge, basé sur CentOS Stream 9, dans lequel l'IDP sera installé

### Liste des conteneurs pour la partie concernant le MFA avec Esup-OTP

- `esup-otp-manager` : interface web permettant à un utilisateur, à un gestionnaire et à un administrateur de manipuler les méthodes de second facteur. Des opérations seront nécessaires sur conteneur
- `esup-otp-api` : API qui calcule les jetons et autres secrets et avec laquelle communique esup-otp-manager, le serveur d'authentification (CAS ou Shibboleth). Des opérations seront nécessaires sur conteneur
- `esup-otp-db` : base de données Mongodb pour stocker toutes les données d'Esup-OTP. Il ne sera pas utile de se connecter dans ce conteneur.


### Utilisation des conteneurs du tutoriel

#### Lancer les conteneurs à partir des images docker déjà construites

```bash
docker compose -f jres2026-152.yml up -d
```

#### Se loguer dans un conteneur

```bash
# Conteneur IDP
docker exec -it idp-stagiaire-jres2026 /bin/bash
# Conteneur SP
docker exec -it sp-jres2026 /bin/bash
# Conteneur CAS
docker exec -it cas-jres2026 /bin/bash
```

#### Passer des conteneurs d'un chapitre à un autre

```bash
# Début du tutoriel
git checkout master
docker compose -f jres2026-152.yml up -d
# Chapitre 3
git checkout chap03
docker compose -f jres2026-152.yml up -d
# Chapitre 7
git checkout chap07
docker compose -f jres2026-152.yml up -d
```

### Reconstruction des images et des conteneurs du tutoriel

#### Lancer les conteneurs après les avoir reconstruits

```bash
# Build the images and containers and run them
docker compose -f jres2026-152.yml up -d --build
# Build image and containers
docker compose -f jres2026-152.yml build
# Start
docker compose -f jres2026-152.yml start
# Stop
docker compose -f jres2026-152.yml stop
```

#### Recontruire depuis zero seulement une l'image d'un conteneur et relancer les conteneurs

```bash
# Conteneur SP
docker compose -f jres2026-152.yml up -d --build sp
# Conteneur LDAP
docker compose -f jres2026-152.yml up -d --build ldap
# Conteneur CAS
docker compose -f jres2026-152.yml up -d --build cas
```

## License

Ce programme est un logiciel libre ; vous pouvez le redistribuer ou le modifier suivant les termes de la licence publique générale GNU Affero telle que publiée par la Free Software Foundation ; soit la version 3 de la licence, soit (à votre gré) toute version ultérieure.

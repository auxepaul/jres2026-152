# IGC locale

## Création de l'autorité de certification

```bash
openssl req -config openssl.cnf -x509 -newkey rsa:2048 -keyout key/formation.lab_CA.pem -out crt/formation.lab_CA.pem -nodes
echo "01" > serial && touch index.txt
```

## Génération d'un certificat

### sous forme de fichiers clé et certificats indépendants

```bash
openssl req -new -config openssl.cnf -keyout key/ldap.formation.lab.pem -out req/ldap.formation.lab.pem -nodes -subj '/CN=ldap.formation.lab' -addext 'subjectAltName=DNS:ldap.formation.lab,DNS:localhost'
openssl ca -config openssl.cnf -in req/ldap.formation.lab.pem -out crt/ldap.formation.lab.pem
```

### sous forme d'un fichier keytool

```bash
keytool -keystore keystore.jks -storepass changeit -importcert -file crt/formation.lab.pem -alias ca
keytool -keystore keystore.jks -storepass changeit -genkey -dname "CN=cas.formation.lab" -alias cas
keytool -keystore keystore.jks -storepass changeit -certreq -file req/cas.formation.lab.pem -alias cas
openssl ca -config openssl.cnf -in req/cas.formation.lab.pem -out crt/cas.formation.lab.pem
keytool -keystore keystore.jks -storepass changeit -import -file crt/cas.formation.lab.pem -alias cas
```


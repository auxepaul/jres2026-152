FROM quay.io/centos/centos:stream9

ARG LDAP_VERSION
ARG LDAP_DOCKER_IMAGE_VERSION
ENV TZ=Europe/Paris

RUN dnf upgrade -y && \
    dnf install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm && \
    dnf install -y \
        vim \
        less \
        bash-completion \
        rsyslog \
        procps \
        bind-utils \
        hostname \
        net-tools \
        openssl \
        iputils \
        openldap-servers-${LDAP_VERSION} \
        openldap-clients-${LDAP_VERSION} && \
    dnf clean all && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/dnf && \
    find /var/log -type f -name '*.log' -delete

COPY ./etc/pki/ /etc/pki/

COPY ./etc/openldap /etc/openldap

COPY ./ldap-formation.ldif /ldap-formation.ldif

RUN rm -rf  /var/lib/ldap && \
    mkdir -p /var/lib/ldap && \
    echo 'set_cachesize 0 268435456 1' > var/lib/ldap/DB_CONFIG && \
    rm -rf /etc/openldap/slapd.d && \
    slapadd -n 1 -l /ldap-formation.ldif && \
    chown -R ldap:ldap /var/lib/ldap

RUN echo "include /usr/share/nano/xml.nanorc" > /root/.nanorc && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime && \
    update-ca-trust

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${LDAP_DOCKER_IMAGE_VERSION}

EXPOSE 636
EXPOSE 389

ENTRYPOINT ["/usr/sbin/slapd", "-u", "ldap", "-h", "ldaps:/// ldap:///", "-d", "0"]
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
    CMD ldapsearch -H ldap://127.0.0.1:389 -D cn=read,dc=formation,dc=lab -w read -b ou=people,dc=formation,dc=lab

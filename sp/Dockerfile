FROM quay.io/centos/centos:stream9

ARG SP_DOCKER_IMAGE_VERSION

ENV TZ=Europe/Paris

COPY ./etc/yum.repos.d/shibboleth.repo /etc/yum.repos.d/shibboleth.repo

RUN dnf upgrade -y && \
    dnf install -y \
        vim \
        emacs-nox \
        nano \
        less \
        bash-completion \
        rsyslog \
        procps \
        bind-utils \
        hostname \
        net-tools \
        openssl \
        iputils \
        httpd \
        mod_ssl \
        php-fpm \
        php-intl \
        shibboleth && \
    dnf clean all && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/dnf && \
    find /var/log -type f -name '*.log' -delete

RUN systemctl enable httpd && \
    systemctl enable shibd && \
    systemctl enable php-fpm

COPY ./usr/local/share/test-sp/ /usr/local/share/test-sp/

COPY ./etc/shibboleth/ /etc/shibboleth/

COPY ./etc/httpd/ /etc/httpd/

COPY ./etc/pki/ /etc/pki/

RUN echo "include /usr/share/nano/xml.nanorc" > /root/.nanorc && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime && \
    update-ca-trust

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${SP_DOCKER_IMAGE_VERSION}

EXPOSE 80
EXPOSE 443

HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
    CMD curl -f https://localhost/Shibboleth.sso/Status || exit 1

CMD ["/sbin/init"]

FROM quay.io/centos/centos:stream9

ARG STAGIAIRE_DOCKER_IMAGE_VERSION

ENV TZ=Europe/Paris

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
        unzip && \
    dnf clean all && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/dnf && \
    find /var/log -type f -name '*.log' -delete

RUN echo "include /usr/share/nano/xml.nanorc" > /root/.nanorc && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${STAGIAIRE_DOCKER_IMAGE_VERSION}

EXPOSE 80
EXPOSE 443

CMD ["/sbin/init"]

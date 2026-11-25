FROM quay.io/centos/centos:stream9

ARG ESUPOTPMANAGER_REPO
ARG ESUPOTPMANAGER_VERSION
ARG NODEJS_VERSION
ARG ESUPOTPMANAGER_DOCKER_IMAGE_VERSION

ENV NODE_ENV=production \
    TZ=Europe/Paris

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
        git && \
    dnf -y module install nodejs:${NODEJS_VERSION} && \
    dnf clean all && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/dnf && \
    find /var/log -type f -name '*.log' -delete

RUN cd /opt && \
    git clone -b ${ESUPOTPMANAGER_VERSION} --single-branch ${ESUPOTPMANAGER_REPO} esup-otp-manager
RUN cd /opt/esup-otp-manager && \
    mkdir -p /opt/esup-otp-manager/log && \
    ln -sf /opt/esup-otp-manager/log /var/log/esup-otp-manager && \
    npm ci install --omit=dev --no-save

COPY ./etc/systemd/system/esup-otp-manager.service /etc/systemd/system/esup-otp-manager.service

COPY ./opt/esup-otp-manager/properties /opt/esup-otp-manager/properties

COPY ./etc/pki/tls/ /etc/pki/tls/

COPY ./etc/httpd/conf.d/ /etc/httpd/conf.d/

RUN useradd -s /bin/nologin -M esup && \
    chown -R esup:esup /opt/esup-otp-manager

RUN echo "include /usr/share/nano/xml.nanorc" > /root/.nanorc && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime

RUN systemctl enable httpd && \
    systemctl enable esup-otp-manager

EXPOSE 443 4000

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${ESUPOTPMANAGER_DOCKER_IMAGE_VERSION}

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:4000/status || exit 1

CMD ["/sbin/init"]

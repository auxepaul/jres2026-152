FROM quay.io/centos/centos:stream9

ARG ESUPOTPAPI_REPO
ARG ESUPOTPAPI_VERSION
ARG NODEJS_VERSION
ARG ESUPOTPAPI_DOCKER_IMAGE_VERSION

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
    git clone -b ${ESUPOTPAPI_VERSION} --single-branch ${ESUPOTPAPI_REPO} esup-otp-api
RUN cd /opt/esup-otp-api && \
    mkdir -p /opt/esup-otp-api/log && \
    ln -sf /opt/esup-otp-api/log /var/log/esup-otp-api && \
    npm ci install --omit=dev --no-save

COPY ./etc/systemd/system/esup-otp-api.service /etc/systemd/system/esup-otp-api.service

COPY ./opt/esup-otp-api/properties /opt/esup-otp-api/properties

COPY ./etc/pki/tls/ /etc/pki/tls/

COPY ./etc/httpd/conf.d/ /etc/httpd/conf.d/

RUN useradd -s /bin/nologin -M esup && \
    chown -R esup:esup /opt/esup-otp-api

RUN echo "include /usr/share/nano/xml.nanorc" > /root/.nanorc && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime

RUN systemctl enable httpd && \
    systemctl enable esup-otp-api

EXPOSE 443 3000

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${ESUPOTPAPI_DOCKER_IMAGE_VERSION}

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:3000/status || exit 1

CMD ["/sbin/init"]

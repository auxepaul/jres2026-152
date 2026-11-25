FROM jlesage/firefox

ARG BROWSER_DOCKER_IMAGE_VERSION

RUN apk --no-cache add ca-certificates

COPY ./formation.lab_CA.pem /usr/share/ca-certificates/mozilla/formation.lab_CA.crt

RUN echo mozilla/formation.lab_CA.crt >> /etc/ca-certificates.conf && \
    update-ca-certificates

COPY ./config/profile/ /config/profile/

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${BROWSER_DOCKER_IMAGE_VERSION}

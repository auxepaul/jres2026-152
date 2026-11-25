ARG MONGO_BASE_IMAGE

FROM ${MONGO_BASE_IMAGE}

ENV TZ=Europe/Paris

RUN rm /etc/localtime && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime

COPY ./docker-entrypoint-initdb.d/mongo-init.js /docker-entrypoint-initdb.d/mongo-init.js

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${ESUPOTPDB_DOCKER_IMAGE_VERSION}

EXPOSE 27017

ENTRYPOINT ["docker-entrypoint.sh"]

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD mongosh --eval "db.adminCommand('ping')" || exit 1

CMD ["mongod"]

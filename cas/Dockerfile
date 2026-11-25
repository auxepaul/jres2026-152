ARG CAS_BASE_IMAGE

FROM ${CAS_BASE_IMAGE} AS overlay

ARG CAS_REPO
ARG CAS_VERSION

ARG ESUPOTPCAS_DEPENDENCY
ARG ESUPOTPCAS_VERSION

RUN apt-get update && \
    apt-get install -y git --no-install-recommends && \
    git clone -b ${CAS_VERSION} --single-branch ${CAS_REPO} cas-overlay && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /cas-overlay

COPY ./cas-overlay/build.gradle build.gradle

RUN mkdir -p ~/.gradle && \
    echo "org.gradle.daemon=false" >> ~/.gradle/gradle.properties && \
    echo "org.gradle.configureondemand=true" >> ~/.gradle/gradle.properties && \
    chmod 750 ./gradlew && \
    ./gradlew --version;

RUN ./gradlew clean build --parallel --no-daemon -PESUPOTPCAS_DEPENDENCY=${ESUPOTPCAS_DEPENDENCY} -PESUPOTPCAS_VERSION=${ESUPOTPCAS_VERSION} && \
    rm -rf ~/.gradle/caches/

FROM ${CAS_BASE_IMAGE} AS cas

ARG CAS_JAVA_VERSION
ARG CAS_DOCKER_IMAGE_VERSION
ENV TZ=Europe/Paris
ENV JAVA_TOOL_OPTIONS="--enable-native-access=ALL-UNNAMED"

RUN apt-get update && \
    apt-get install -y \
        vim \
        emacs-nox \
        nano \
        less \
        bash-completion

RUN mkdir -p /etc/cas/config && \
    mkdir -p /etc/cas/services && \
    mkdir -p /etc/cas/saml && \
    mkdir -p /var/log/cas && \
    mkdir -p /cas-overlay

COPY --from=overlay cas-overlay/build/libs/cas.war cas-overlay/
COPY ./etc/cas/ /etc/cas/
COPY ./etc/pki/ /etc/pki/

RUN ln -sf /usr/share/zoneinfo/$TZ /etc/localtime && \
    ln -sf /etc/pki/tls/certs/formation.lab_CA.pem /usr/local/share/ca-certificates/formation.lab_CA.crt && \
    update-ca-certificates && \
    keytool -trustcacerts -keystore /usr/lib/jvm/zulu${CAS_JAVA_VERSION}/lib/security/cacerts -storepass changeit -noprompt -importcert -file /etc/pki/tls/certs/formation.lab_CA.pem --alias "formation.lab" && \
    rm -rf /var/lib/apt/lists/*

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${CAS_DOCKER_IMAGE_VERSION}

EXPOSE 443

ENV PATH $PATH:$JAVA_HOME/bin:.

WORKDIR /cas-overlay

ENTRYPOINT ["java", "-server", "-noverify", "-Xmx2048M", "-jar", "cas.war"]

HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
    CMD apt-get update -y && \
        apt-get install -y curl && \
        curl -f https://localhost/cas/actuator/health -s '{"status":"UP"' || exit 1


FROM quay.io/centos/centos:stream9

ARG IDP_JAVA_VERSION
ARG IDP_TOMCAT_VERSION_MAJOR
ARG IDP_TOMCAT_VERSION
ARG IDP_VERSION
ARG IDP_ENTITYID
ARG IDP_SECRET_PASSWORD
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
        unzip \
        httpd \
        mod_ssl \
        java-${IDP_JAVA_VERSION}-headless && \
    dnf clean all && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/dnf && \
    find /var/log -type f -name '*.log' -delete

# install and setup Tomcat
RUN mkdir -p /tmp/tomcat && cd /tmp/tomcat && \
    curl https://downloads.apache.org/tomcat/tomcat-${IDP_TOMCAT_VERSION_MAJOR}/v${IDP_TOMCAT_VERSION}/bin/apache-tomcat-${IDP_TOMCAT_VERSION}.tar.gz -O && \
    tar xvzf apache-tomcat-${IDP_TOMCAT_VERSION}.tar.gz -C /opt && \
    ln -s /opt/apache-tomcat-${IDP_TOMCAT_VERSION} /opt/tomcat && \
    curl https://repo1.maven.org/maven2/jakarta/servlet/jsp/jstl/jakarta.servlet.jsp.jstl-api/3.0.0/jakarta.servlet.jsp.jstl-api-3.0.0.jar -O && \
    cp jakarta.servlet.jsp.jstl-api-3.0.0.jar /opt/tomcat/lib && \
    rm -f /tmp/tomcat/apache-tomcat-${IDP_TOMCAT_VERSION}.tar.gz

COPY ./etc/profile.d/java.sh /etc/profile.d/java.sh
COPY ./etc/systemd/system/tomcat.service /etc/systemd/system/tomcat.service
COPY ./etc/sysconfig/tomcat /etc/sysconfig/tomcat
COPY ./opt/tomcat/conf/server.xml /opt/tomcat/conf/server.xml
COPY ./opt/tomcat/conf/Catalina /opt/tomcat/conf/Catalina

RUN useradd tomcat && \
    chown -R tomcat:tomcat /opt/apache-tomcat-${IDP_TOMCAT_VERSION}

# install IdP
RUN mkdir -p /tmp/shibboleth && cd /tmp/shibboleth && \
    curl https://shibboleth.net/downloads/identity-provider/archive/${IDP_VERSION}/shibboleth-identity-provider-${IDP_VERSION}.tar.gz -O && \
    tar xvzf shibboleth-identity-provider-${IDP_VERSION}.tar.gz && \
    cd shibboleth-identity-provider-${IDP_VERSION}/ && \
    ./bin/install.sh --targetDir /opt/shibboleth-idp --hostName idp-stagiaire.formation.lab --scope formation.lab --entityID ${IDP_ENTITYID} --keystorePassword ${IDP_SECRET_PASSWORD} --sealerPassword ${IDP_SECRET_PASSWORD} && \
    sed -i '/SingleLogoutService/d' /opt/shibboleth-idp/metadata/idp-metadata.xml && \
    rm -rf /tmp/shibboleth/shibboleth-identity-provider-${IDP_VERSION} && \
    rm -f /tmp/shibboleth/shibboleth-identity-provider-${IDP_VERSION}.tar.gz

# Setup IdP
COPY ./opt/shibboleth-idp/conf /opt/shibboleth-idp/conf
RUN chown -R tomcat:tomcat /opt/shibboleth-idp

RUN cd /opt/shibboleth-idp && \
    ./bin/build.sh

COPY ./etc/httpd/conf.d /etc/httpd/conf.d

COPY ./etc/pki/tls/ /etc/pki/tls/

RUN echo "include /usr/share/nano/xml.nanorc" > /root/.nanorc && \
    ln -sf /usr/share/zoneinfo/$TZ /etc/localtime

RUN systemctl enable httpd && \
    systemctl enable tomcat

LABEL org.opencontainers.image.vendor="GIP RENATER" \
      org.opencontainers.image.authors="equipe-federation@listes.renater.fr" \
      version=${STAGIAIRE_DOCKER_IMAGE_VERSION}

EXPOSE 80
EXPOSE 443

HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
    CMD curl -f https://localhost/idp/status || exit 1

CMD ["/sbin/init"]

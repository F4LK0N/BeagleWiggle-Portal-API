FROM alpine:3.17
LABEL "org.opencontainers.image.authors"="F4LK0N"
LABEL "org.opencontainers.image.version"="1.1"

########################################################################################################################
### SETUP ###
########################################################################################################################
WORKDIR /
ARG ARG_TIMEZONE
ARG ARG_OS_VERSION="3.17"
ARG ARG_OS_APK_MIRROR="https://mirror.uepg.br/alpine"

### CONTAINER ###
ENV ROOT_DATA="/data"
ENV ROOT_LOGS="/logs"

### OS ###
ENV ENV="/root/.ashrc"
ENV OS_DATA="${ROOT_DATA}/os"
ENV OS_LOGS="${ROOT_LOGS}/os"

### APACHE ###
ENV HTTPD_VERSION="2.4"
ENV HTTPD_ROOT="/etc/apache2"
ENV HTTPD_CONF="${HTTPD_ROOT}/httpd.conf"
ENV HTTPD_CONFD="${HTTPD_ROOT}/conf.d"
ENV HTTPD_MODULES="/usr/lib/apache2"
ENV HTTPD_DATA="${ROOT_DATA}/apache"
ENV HTTPD_LOGS="${ROOT_LOGS}/apache"

### PHP ###
ENV PHP_VERSION="8.1"
ENV PHP_ROOT="/etc/php81"
ENV PHP_CONF="${PHP_ROOT}/php.ini"
ENV PHP_CONFD="${PHP_ROOT}/conf.d"
ENV PHP_MODULES="/usr/lib/php81/modules"
ENV PHP_DATA="${ROOT_DATA}/php"
ENV PHP_LOGS="${ROOT_LOGS}/php"

### PHALCON ###
ENV PHALCON_VERSION="5.2.1"

### APPLICATION ###
ENV APP_ROOT="/src"

### FILESYSTEM ###
RUN set -eux; \
    mkdir --mode=777 --parents \
        # Container
        ${ROOT_DATA} \
        ${ROOT_LOGS} \
        # OS
        ${OS_DATA} \
        ${OS_LOGS} \
        # Apache
        ${HTTPD_DATA} \
        ${HTTPD_LOGS} \
        # PHP
        ${PHP_DATA} \
        ${PHP_LOGS} \
        # Application
        ${APP_ROOT} \
    ;

### PACKAGES ###
RUN set -eux; \
    # Mirrors
    rm -f /etc/apk/repositories; \
    echo "${ARG_OS_APK_MIRROR}/v${ARG_OS_VERSION}/main" >> /etc/apk/repositories; \
    echo "${ARG_OS_APK_MIRROR}/v${ARG_OS_VERSION}/community" >> /etc/apk/repositories; \
    chmod 644 /etc/apk/repositories; \
    # Update
    apk update; \
    apk upgrade; \
    # Clean
    rm -rf /var/cache/apk/*; \
    rm -rf /var/lib/apk/*; \
    rm -rf /etc/apk/cache/*;

### TIMEZONE ###
RUN set -eux; \
    # Dependencies
    apk update; \
    apk add --virtual temp_dependencies \
        tzdata \
    ; \
    # Config
        cp "/usr/share/zoneinfo/${ARG_TIMEZONE}" /etc/localtime; \
        echo "${ARG_TIMEZONE}" > /etc/timezone; \
    # Clean
    apk del temp_dependencies; \
    rm -rf /var/cache/apk/*; \
    rm -rf /var/lib/apk/*; \
    rm -rf /etc/apk/cache/*;

########################################################################################################################
### INSTALL ###
########################################################################################################################
### PACKAGES ###
RUN set -eux; \
    # Update
    apk update; \
    # Install
    apk add --no-cache \
        \
        ### OS ###
        openrc \
        iftop \
        curl \
        \
        ### APACHE ###
        apache2 \
        apache2-ssl \
        \
        ### PHP ###
        php81-apache2 \
        #php81-bcmath \
        #php81-bz2 \
        #php81-calendar \
        php81-common \
        #php81-ctype \
        php81-curl \
        php81-dom \
        #php81-iconv \
        php81-fileinfo \
        #php81-gd \
        php81-gettext \
        php81-mbstring \
        #php81-mysqli \
        #php81-mysqlnd \
        php81-openssl \
        php81-pdo \
        php81-pdo_mysql \
        #php81-pdo_pgsql \
        #php81-pdo_sqlite \
        php81-phar \
        php81-session \
        php81-tokenizer \
        php81-xml \
        php81-xmlwriter \
        php81-opcache \
        php81-pecl-psr \
        php81-pecl-xdebug \
    ; \
    # Clean
    rm -rf /var/cache/apk/*; \
    rm -rf /var/lib/apk/*; \
    rm -rf /etc/apk/cache/*;

## SOURCES ###
RUN set -eux; \
    # Dependencies
    apk update; \
    apk add --virtual temp_sources_dependencies \
        7zip \
    ; \
    # PHP - Phalcon
        # Source
        mkdir -p /tmp/phalcon/; \
        cd /tmp/phalcon/; \
        curl -LO https://github.com/phalcon/cphalcon/releases/download/v${PHALCON_VERSION}/phalcon-php8.1-nts-ubuntu-gcc-x64.zip; \
        7z x phalcon-php8.1-nts-ubuntu-gcc-x64.zip; \
        # Install
        mkdir -p ${PHP_MODULES}; \
        cp phalcon.so ${PHP_MODULES}; \
        chmod 755 ${PHP_MODULES}; \
        chmod 755 ${PHP_MODULES}/phalcon.so; \
        cd /; \
        # Clean
        rm -rf /tmp/phalcon; \
    # Clean
    apk del temp_sources_dependencies; \
    rm -rf /var/cache/apk/*; \
    rm -rf /var/lib/apk/*; \
    rm -rf /etc/apk/cache/*;

### IMPORT ###
# Shell
COPY --chmod=755 --from=f4lk0n/fkn:data "/data/shell/latest/.ashrc" "/root/.ashrc"
# Shell Functions
COPY --chmod=755 --from=f4lk0n/fkn:data "/data/shell_functions/latest/sh/*" "/usr/local/bin/"
# Adm
COPY --chmod=755 --from=f4lk0n/fkn:data "/data/adm/latest/sh/*" "/usr/local/bin/"
# Composer
COPY --chmod=755 --from=f4lk0n/fkn:data "/data/composer/latest/composer" "/usr/local/bin/composer"
# Entrypoint
COPY --chmod=755 "./usr/local/bin/*" "/usr/local/bin/"
# SSL
COPY --chmod=755 "./etc/ssl/apache2/server.key" "/etc/ssl/apache2/server.key"
COPY --chmod=755 "./etc/ssl/apache2/server.pem" "/etc/ssl/apache2/server.pem"
COPY --chmod=755 "./etc/ssl/apache2/server.pem" "/etc/ssl/certs/server.crt"
COPY --chmod=755 "./etc/ssl/apache2/server.key" "/etc/ssl/private/server.key"

########################################################################################################################
### CONFIG ###
########################################################################################################################
RUN set -eux; \
    ### APACHE ###
        # Conf File and Dir
        mkdir --mode=777 "${HTTPD_ROOT}/original"; \
        cp "${HTTPD_CONF}" "${HTTPD_ROOT}/original/httpd.conf"; \
        cp -a "${HTTPD_CONFD}/." "${HTTPD_ROOT}/original/conf.d/"; \
        rm ${HTTPD_CONF}; \
        rm ${HTTPD_CONFD}/*; \
        # Logs
        rm "/var/www/logs"; \
        rm -r "/var/log/apache2/"; \
        ln -s "${HTTPD_LOGS}" "/var/www/logs"; \
        chmod 777 "/var/www/logs"; \
        # Run
        rm "/var/www/run"; \
        rm -r "/run/apache2/"; \
        mkdir --mode=755 "${HTTPD_DATA}/run"; \
        ln -s "${HTTPD_DATA}/run" "/var/www/run"; \
        chmod 777 "/var/www/run"; \
        mkdir --mode=755 "${HTTPD_DATA}/run/mutex"; \
        # SSL
        chmod 755 "/etc/ssl/apache2/server.pem"; \
        chmod 755 "/etc/ssl/apache2/server.key"; \
        # Clear
        rm -r "/var/www/localhost/"; \
    \
    ### PHP ###
        # Conf File and Dir
        mkdir --mode=777 "${PHP_ROOT}/original"; \
        cp "${PHP_CONF}" "${PHP_ROOT}/original/php.ini"; \
        cp -a "${PHP_CONFD}/." "${PHP_ROOT}/original/conf.d/"; \
        rm ${PHP_CONF}; \
        rm ${PHP_CONFD}/*; \
        # Data
        mkdir --mode=777 "${PHP_DATA}/tmp"; \
        # Logs
        mkdir --mode=777 "${PHP_LOGS}/xdebug"; \
    \
    ### APPLICATION ###
        chmod 777 "${APP_ROOT}"; \
        mkdir --mode=777 "${APP_ROOT}/public"; \
        chmod 777 "${APP_ROOT}/public";

### IMPORT ###
# APACHE
COPY --chmod=644 ".${HTTPD_CONF}" "${HTTPD_CONF}"
COPY --chmod=644 ".${HTTPD_CONFD}/*" "${HTTPD_CONFD}/"
# PHP
COPY --chmod=644 ".${PHP_CONF}" "${PHP_CONF}"

########################################################################################################################
### DOCKER ###
########################################################################################################################
WORKDIR /src
STOPSIGNAL SIGWINCH
ENTRYPOINT ["container-entrypoint"]
CMD ["RUN"]

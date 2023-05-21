# syntax=docker/dockerfile:1.4

FROM alpine:3.15

ARG UNRAR_VERSION=6.1.7

ENV S6_SERVICES_GRACETIME=30000 \
    S6_KILL_GRACETIME=60000 \
    S6_CMD_WAIT_FOR_SERVICES_MAXTIME=0 \
    S6_SYNC_DISKS=1 \
    LANG=C.UTF-8 \
    PS1="\[\e[32m\][\[\e[m\]\[\e[36m\]\u \[\e[m\]\[\e[37m\]@ \[\e[m\]\[\e[34m\]\h\[\e[m\]\[\e[32m\]]\[\e[m\] \[\e[37;35m\]in\[\e[m\] \[\e[33m\]\w\[\e[m\] \[\e[32m\][\[\e[m\]\[\e[37m\]\d\[\e[m\] \[\e[m\]\[\e[37m\]\t\[\e[m\]\[\e[32m\]]\[\e[m\] \n\[\e[1;31m\]$ \[\e[0m\]"

COPY --from=crazymax/alpine-s6-dist:3.15 / /

RUN set -ex && \
    apk add --no-cache \
        bash \
        curl \
        ca-certificates \
        coreutils \
        jq \
        netcat-openbsd \
        procps \
        p7zip \
        shadow \
        tzdata \
        xz \
    && \
    # Install build packages
    apk add --no-cache --upgrade --virtual=build-dependencies \
        build-base \
        gcc \
        g++ \
        make \
        musl-dev \
    && \
    # Build install unrar
    mkdir /tmp/unrar && \
    curl -o \
        /tmp/unrar.tar.gz -L \
        "https://www.rarlab.com/rar/unrarsrc-${UNRAR_VERSION}.tar.gz" && \  
    tar xf \
        /tmp/unrar.tar.gz -C \
        /tmp/unrar --strip-components=1 && \
    cd /tmp/unrar && \
    make && \
    install -v -m755 unrar /usr/local/bin && \
    # Install nginx
    apk add --no-cache \
        pcre \
        nginx \
    && \
    # Install php7
    apk add --no-cache \
        php7 \
        php7-common \
        php7-fpm \
        php7-json \
        php7-pecl-imagick \
        php7-dev \
        php7-xml \
        php7-zip \
        php7-mysqli \
        php7-mysqlnd \
        php7-phar \
        php7-iconv \
        php7-mbstring \
        php7-curl \
        php7-dom \
        php7-xml \
        php7-xmlwriter \
        php7-xmlreader \
        php7-fileinfo \
        php7-tokenizer \
        php7-pdo \
        php7-bcmath \
        php7-ctype \
        php7-openssl \
        php7-json \
        php7-session \
        php7-pcntl \
        php7-pgsql \
        php7-pdo_mysql \
        php7-pdo_pgsql \
        php7-pdo_sqlite \
        php7-posix \
        php7-zlib \
        php7-opcache \
    && \
    # Install composer
    curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar && \
    chmod +x /usr/local/bin/composer && \
    # Add user
    addgroup -S smanga -g 911 && \
    adduser -S smanga -G smanga -h /app -u 911 -s /bin/bash && \
    # Log Links
    mkdir -p /logs && \
    touch /logs/nginx_access.log && \
    touch /logs/nginx_error.log && \
    # PHP Nginx settings
    sed -i "s#short_open_tag = Off#short_open_tag = On#g" /etc/php7/php.ini && \
    sed -i "s#;open_basedir =#open_basedir = /#g" /etc/php7/php.ini && \
    sed -i "s#register_argc_argv = Off#register_argc_argv = On#g" /etc/php7/php.ini && \
    mkdir -p /run/php && \
    chown -R smanga:smanga /run/php && \
    rm -rf \
        /etc/nginx/nginx.conf \
        /etc/nginx/http.d/* \
        /etc/php7/php-fpm.d/www.conf && \
    # Clear
    apk del --purge build-dependencies && \
    rm -rf \
        /var/cache/apk/* \
        /usr/share/man \
        /usr/share/php7 \
        /tmp/*

COPY --chmod=755 ./docker/rootfs_base /

ENTRYPOINT [ "/init" ]

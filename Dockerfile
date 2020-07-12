# This Dockerfile is used to see in action on PHP8-alpha.
FROM keinos/php8-jit

COPY ./src /app/src
COPY ./samples /app/samples
COPY ./composer.json /app/composer.json
COPY ./.init /app/.init
COPY ./.devcontainer/install_composer.sh /app/install_composer.sh

# Install composer
WORKDIR /app
USER root
RUN \
    apk --no-cache add tree && \
    tree /app && \
    /bin/sh /app/install_composer.sh && \
    composer install --no-dev --no-interaction && \
    rm -f /app/install_composer.sh

USER root
ENTRYPOINT [ "php", "/app/samples/Main.php" ]

FROM wordpress:php8.3-apache
# WP-CLI (do jednorazowej instalacji i treści demo)
ADD --chmod=755 https://github.com/wp-cli/wp-cli/releases/download/v2.12.0/wp-cli-2.12.0.phar /usr/local/bin/wp
RUN a2enmod expires headers deflate >/dev/null \
 && { echo 'ServerTokens Prod'; echo 'ServerSignature Off'; \
      echo '<IfModule mod_expires.c>'; echo 'ExpiresActive On'; \
      echo 'ExpiresByType text/css "access plus 30 days"'; echo 'ExpiresByType application/javascript "access plus 30 days"'; \
      echo 'ExpiresByType image/svg+xml "access plus 30 days"'; echo 'ExpiresByType application/pdf "access plus 7 days"'; echo '</IfModule>'; \
      echo 'AddOutputFilterByType DEFLATE text/html text/css application/javascript image/svg+xml'; } > /etc/apache2/conf-available/isv.conf \
 && a2enconf isv >/dev/null \
 && { echo 'opcache.memory_consumption=128'; echo 'opcache.validate_timestamps=0'; echo 'expose_php=Off'; } > /usr/local/etc/php/conf.d/isv.ini
# Motyw i mu-plugin trafiają do /usr/src/wordpress – entrypoint skopiuje je do wolumenu przy pierwszym starcie.
COPY theme/isvero-samorzad /usr/src/wordpress/wp-content/themes/isvero-samorzad
COPY mu-plugins /usr/src/wordpress/wp-content/mu-plugins
COPY init/seed.php /usr/local/share/isv/seed.php
COPY init/isv-entrypoint.sh /usr/local/bin/isv-entrypoint.sh
# Aktualizacja motywu przy każdym wdrożeniu (wolumen nie nadpisuje się sam).
RUN printf '#!/bin/bash\nset -e\nif [ -d /var/www/html/wp-content/themes ]; then rm -rf /var/www/html/wp-content/themes/isvero-samorzad /var/www/html/wp-content/mu-plugins/isvero-demo-guard.php; mkdir -p /var/www/html/wp-content/mu-plugins; cp -r /usr/src/wordpress/wp-content/themes/isvero-samorzad /var/www/html/wp-content/themes/; cp /usr/src/wordpress/wp-content/mu-plugins/isvero-demo-guard.php /var/www/html/wp-content/mu-plugins/; chown -R www-data:www-data /var/www/html/wp-content; fi\nexec /usr/local/bin/isv-entrypoint.sh "$@"\n' > /usr/local/bin/isv-start.sh && chmod +x /usr/local/bin/isv-start.sh
ENTRYPOINT ["/usr/local/bin/isv-start.sh"]
CMD ["apache2-foreground"]

FROM ping_crm-php

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

ENTRYPOINT ["php", "/usr/bin/composer"]

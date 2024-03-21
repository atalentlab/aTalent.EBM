set -aeuo pipefail

ssh -vvv user@ip 'cd /var/www/srm/ \
&& git pull \
&& cd docker \
&& docker-compose -p srm exec -T workspace composer install \
&& docker-compose -p srm exec -T workspace php artisan migrate --force'
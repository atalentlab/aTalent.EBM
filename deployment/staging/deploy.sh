set -aeuo pipefail

ssh -vvv ubuntu@161.189.73.3 'cd /var/www/srm/ \
&& git pull \
&& bash ./scripts/generate_api_docs.sh \
&& cd laradock \
&& docker-compose -p srm exec -T workspace composer install \
&& docker-compose -p srm exec -T workspace php artisan migrate --force'
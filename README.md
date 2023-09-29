## How to run:
```shell
docker compose up -d
docker compose exec -it php php /var/www/bin/console doctrine:migrations:migrate -n
docker compose exec -it php php /var/www/bin/console doctrine:fixtures:load -n
docker compose exec -it database psql --user app app -c 'SELECT id FROM survey LIMIT 1;'
browser http://localhost/survey/ID_FROM_PREVIOUS_COMMAND_RESULT
```

## Run tests:
```shell
docker compose exec -it php /var/www/vendor/bin/codecept run -c /var/www/codeception.yml
```

P.S. Questions and answers can be shuffled without disrupting the logic of the survey, but this is not implemented.
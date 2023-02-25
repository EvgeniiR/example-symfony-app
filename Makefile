.PHONY: up, stop, deps, init, check, cs_fix, psalm, app_shell, tests
up:
	docker-compose up -d --build

stop:
	docker-compose stop

app_shell:
	docker-compose exec php bash

deps:
	composer install

init:
	bin/console doctrine:database:create --if-not-exists
	bin/console doctrine:migrations:migrate -n
	bin/console doctrine:database:create --env=test --if-not-exists
	bin/console doctrine:migrations:migrate --env=test -n

cs_fix:
	./vendor/bin/php-cs-fixer fix --allow-risky=yes

cs_check:
	./vendor/bin/php-cs-fixer fix --dry-run --allow-risky=yes

psalm_check:
	./vendor/bin/psalm

tests:
	./vendor/bin/phpunit

check: cs_check psalm_check tests

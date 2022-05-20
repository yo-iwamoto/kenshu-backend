.PHONY: init
init:
	docker-compose exec web composer install && composer dump-autoload

.PHONY: init
init:
	docker-compose exec web composer install \
	&& composer dump-autoload

.PHONY: migrate-all
migrate-all:
	./scripts/execute_sql.sh migrations/202205161328_initialize.sql \
	&& ./scripts/execute_sql.sh migrations/202205161724_drop_not_null_from_profile_image_url.sql \
	&& ./scripts/execute_sql.sh migrations/202205191011_alter_password_hash_length.sql \
	&& ./scripts/execute_sql.sh migrations/202205231703_set_timezone.sql \
	&& ./scripts/execute_sql.sh migrations/202205241138_alter_tags_id_not_serial.sql \
	&& ./scripts/execute_sql.sh migrations/202205241505_set_cascade_deletion.sql

.PHONY: seed
seed:
	./scripts/execute_sql.sh fixtures/create_user.sql \
	&& ./scripts/execute_sql.sh fixtures/create_tags.sql \
	&& ./scripts/execute_sql.sh fixtures/create_posts.sql

.PHONY: reset-db
reset-db:
	docker-compose stop db \
	&& docker-compose rm db \
	&& docker volume rm kenshu-backend_postgres \
	&& docker-compose up -d db

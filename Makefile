up-dev:
	docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d

up-staging:
	docker compose -f docker-compose.yml -f docker-compose.staging.yml up -d

up-prod:
	docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d

down:
	docker compose down

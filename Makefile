cli:
	docker compose exec fpm sh

load-test:
	docker compose exec k6 k6 run --out experimental-prometheus-rw /scripts/test_homepage.js
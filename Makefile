.PHONY: rebuild
rebuild: 
	@docker compose down;
	@docker compose up --build --force-recreate;


.PHONY: up 
up:
	@docker compose up
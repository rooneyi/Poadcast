# Makefile pour gérer les containers Docker

.PHONY: build up down logs ps clean

# Construire les images
build:
	docker build -t mon-image .

# Démarrer les containers (équivalent à docker-compose up -d)
up:
	docker run -d --name mon-container -p 8080:80 mon-image

# Arrêter les containers (équivalent à docker-compose down)
down:
	docker stop mon-container
	docker rm mon-container

# Voir les logs
logs:
	docker logs -f mon-container

# Lister les containers
ps:
	docker ps -a

# Nettoyer (stop + rm)
clean: down
	docker rmi mon-image
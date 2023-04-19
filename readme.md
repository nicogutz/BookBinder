To run the server:
> symfony server:start -d

To stop the server:
> symfony server:stop

To run node:
> yarn watch

To run docker:
> docker-compose up -d

To make a new entity:
> symfony console make:entity

To make a new migration:
> symfony console make:migration
> symfony console doctrine:migrations:migrate

To query the database:
> symfony console doctrine:query:sql "SELECT * FROM vinyl_mix"
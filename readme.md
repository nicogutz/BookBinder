First - To run node:
> yarn watch

Second - To run docker:
> docker-compose up -d

Third - To run the server:
> symfony server:start -d

To stop the server:
> symfony server:stop

To make a new entity:
> symfony console make:entity

To make a new migration:
> symfony console make:migration
> symfony console doctrine:migrations:migrate

To migrate to test env:
> symfony console doctrine:migrations:migrate --env=test

To query the database:
> symfony console doctrine:query:sql "SELECT * FROM vinyl_mix"

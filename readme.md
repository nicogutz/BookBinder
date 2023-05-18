# SE & Webtech Project

## Project URL's
Provide a link to the main page of your application. Or if you have multiple parts in your website you can provide a list of links (i.e. not all pages are in your main navigation bar)
* [Main login page](https://a22web25.studev.groept.be/public)
* [Admin page](https://a22web25.studev.groept.be/public/admin)
---

## Website credentials
### Regular user
- login: user
- password: password
### Admin / Library manager / ...
- login: admin
- password: password

---

## Implemented Features
Provide a short description of the actual implemented features in your project
* User Authentication and Registration
* Advanced book search and filtering
* Book favorites
* Admin page for library employees


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
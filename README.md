# Fantasy football app

This is a simplified fantasy football app

## Requirements

- 🐳 Docker

## Installation

1. 😀 Clone this repo.

2. `Optional:` You can change the `docker-compose.yml` if necessary like ports mapping to your host

3. Run `docker compose up -d`

4. The 2 containers are deployed:

```bash
Creating fantasy-football-node-1    ... done
Creating fantasy-football-php-1   ... done
```

Access http://localhost:8000 in your browser

## Login

- **Username:** tommy@eagan.com
- **Password:** secret

> All test accounts share the same password: **secret**

## Features

- Create new account with your own team
- Bid for other team's players

> **Note:** After bidding for a team, you can login as the owner of the receiving team to accept the bid for the player
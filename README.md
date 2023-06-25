# BEAGLE WIGGLE - PORTAL API #



## DOCKER ##

### BUILD ###
```bash
docker compose build
docker compose build --progress=plain
```

### RUN ###
```bash
docker compose up -d
```

### ACCESS ###
```bash
docker compose exec -it portal-api /bin/sh
```

### BUILD RUN ACCESS ###
```bash
docker compose up -d --build; printf "\a"; docker compose exec -it portal-api /bin/sh;
```

### LOGS ###
```bash
docker compose logs portal-api
```

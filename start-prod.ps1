#!/bin/bash

git checkout main

git pull origin main

docker-compose -p videgrenier-bloc5-prod --env-file .env.prod -f docker-compose.yml up --build -d
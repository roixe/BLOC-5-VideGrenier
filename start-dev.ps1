#!/bin/bash
docker-compose -p videgrenier-bloc5-dev --env-file .env.dev -f docker-compose.yml up --build -d

#!/bin/bash

# Démarrer le serveur Symfony en arrière-plan
symfony serve -d
echo "Symfony est lancé."

# Vérifier si tailwind:build --watch est déjà en cours
TAILWIND_RUNNING=$(ps aux | grep "tailwind:build" | grep -- "--watch" | grep -v grep)

if [ -z "$TAILWIND_RUNNING" ]; then
  # Si rien n'est en cours, on le lance
  php bin/console tailwind:build --watch &
  TAILWIND_PID=$!
  echo $TAILWIND_PID > .tailwind.pid
  echo "Tailwind est lancé en watch (PID: $TAILWIND_PID)."
else
  echo "Tailwind est déjà en cours."
fi
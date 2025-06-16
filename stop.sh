#!/bin/bash

# Arrêter le serveur Symfony
symfony server:stop
echo "Symfony arrêté."

# Arrêter Tailwind
if [ -f .tailwind.pid ]; then
  TAILWIND_PID=$(cat .tailwind.pid)
  if ps -p $TAILWIND_PID > /dev/null; then
    kill -9 $TAILWIND_PID
    echo "Tailwind arrêté (PID: $TAILWIND_PID)."
  else
    echo "Le processus Tailwind était déjà arrêté."
  fi
  rm .tailwind.pid
else
  echo "Aucun fichier .tailwind.pid trouvé. Tailwind n'a peut-être pas été lancé via ce script."
fi
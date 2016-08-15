#!/bin/sh

git add .
git status
echo ""
read -p "Mensaje para el commit  : " mensaje
echo ""
git commit -m '$mensaje'
git push -u miSubidaProgramaDeAurelio trabajando


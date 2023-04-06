#!/bin/sh

#  move to directory to remove ds store.sh
#  
#
#  Created by 'https://github.com/nexuspcs' on 26/7/2022.
#  

find . -name .DS_Store -print0 | xargs -0 git rm -f --ignore-unmatch
sleep 2
echo .DS_Store >> .gitignore
sleep 2
git add .gitignore
sleep 1
git commit -m '.DS_Store removal successful'
sleep 1
git push
sleep 3
git pull
sleep 1

rm *~
rm */*~
rm */*/*~
git config --global user.name "c00cjz00"
git config --global user.email summerhill001@gmail.com
#git pull
git checkout master
#git rm init.sh~ config.php~ README.md~
git rm 000-processCheck.php
git rm run_x64.bat
git add *
git commit -m "init"
# 上傳至遠端
git push origin master

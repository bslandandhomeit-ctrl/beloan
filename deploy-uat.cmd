@echo off
setlocal enabledelayedexpansion
set REPO=D:\ProjectEASTGROUP\landhome-stack
set SRV=uat@10.3.0.99
set APP=/home/uat/SYS/landhome_uat/html
set KEY=%USERPROFILE%\.ssh\uat_key
set CONTAINER=landhome-uat-apache
set DBCONTAINER=landhome-uat-mysql
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do set DT=%%c%%a%%b
for /f "tokens=1-2 delims=: " %%a in ('time /t') do set TM=%%a%%b
set STAMP=%DT%-%TM%

cd /d %REPO%

echo ================= PRE-DEPLOY CHECK =================
echo.
echo Local branch:
git rev-parse --abbrev-ref HEAD
echo.
echo Commit to deploy:
git log --oneline -1
echo.
echo Uncommitted changes (must be empty):
git status --porcelain
echo.
echo Server currently running:
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1"
echo.
echo Files changed in this commit:
git diff --stat HEAD~1 HEAD -- html/
echo.
echo ====================================================
echo Ctrl+C to abort, any key to deploy
pause

echo.
echo == 1/8 Push to GitHub ==
git push origin uat || goto fail

echo.
echo == 2/8 Build archive ==
git archive --format=tar.gz -o "%TEMP%\beloan.tar.gz" HEAD:html || goto fail
for %%F in ("%TEMP%\beloan.tar.gz") do echo    Size: %%~zF bytes

echo.
echo == 3/8 Backup server code ==
ssh -i "%KEY%" %SRV% "cd %APP% && tar -czf /tmp/backup-code-%STAMP%.tar.gz app config resources helpers database 2>/dev/null ; ls -lh /tmp/backup-code-%STAMP%.tar.gz"

echo.
echo == 4/8 Backup DB schema ==
ssh -i "%KEY%" %SRV% "sudo -n docker exec %DBCONTAINER% mysqldump -u root -p'BSLH@2020' --no-data --skip-add-drop-table land_home > /tmp/backup-schema-%STAMP%.sql 2>/dev/null ; ls -lh /tmp/backup-schema-%STAMP%.sql"

echo.
echo == 5/8 Snapshot server state ==
ssh -i "%KEY%" %SRV% "cd %APP% && git add -A && (git commit -m 'pre-deploy snapshot %STAMP%' >/dev/null 2>&1 || true) && git rev-parse HEAD" > "%REPO%\last-rollback-point.txt"
set /p ROLLBACK=<"%REPO%\last-rollback-point.txt"
echo.
echo    *** ROLLBACK POINT: %ROLLBACK% ***
echo    (also saved to last-rollback-point.txt)

echo.
echo == 6/8 Copy and extract ==
scp -i "%KEY%" "%TEMP%\beloan.tar.gz" %SRV%:/tmp/ || goto fail
ssh -i "%KEY%" %SRV% "cd %APP% && tar -xzf /tmp/beloan.tar.gz && sudo -n chmod -R 777 storage && sudo -n rm -rf storage/framework/views/*" || goto fail

echo.
echo == 7/8 Clear caches and restart ==
ssh -i "%KEY%" %SRV% "cd %APP% && sudo -n docker exec %CONTAINER% php artisan config:clear && sudo -n docker restart %CONTAINER%" || goto fail
echo    Waiting for Apache...
timeout /t 8 /nobreak >nul

echo.
echo == 8/8 Verify ==
echo.
echo Login page:
ssh -i "%KEY%" %SRV% "curl -s -m 25 -o /dev/null -w '   HTTP %%{http_code}   bytes %%{size_download}   %%{time_total}s\n' http://localhost:8081/user/login"
echo.
echo Deployed commit + dirty file count:
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1 && echo -n '   dirty files: ' && git status --porcelain ^| wc -l"
echo.
echo Errors in container log:
ssh -i "%KEY%" %SRV% "sudo -n docker logs %CONTAINER% --tail 300 2>^&1 ^| grep -i 'local.ERROR' ^| tail -5 ; echo '   --- end ---'"
echo.
echo Laravel log files:
ssh -i "%KEY%" %SRV% "ls -la %APP%/storage/logs/ ^| grep laravel ^|^| echo '   no laravel log today (good)'"

echo.
echo ==================== SUCCESS ====================
echo Expect: HTTP 200, bytes over 5000, no local.ERROR
echo App:      http://10.3.0.99:8081
echo Rollback: .\rollback-uat.cmd %ROLLBACK%
echo Backups:  /tmp/backup-code-%STAMP%.tar.gz
echo           /tmp/backup-schema-%STAMP%.sql
goto end

:fail
echo.
echo **************** DEPLOY FAILED ****************
echo See the error above. Nothing may have changed, or
echo the deploy stopped partway.
echo.
if defined ROLLBACK echo To roll back: .\rollback-uat.cmd %ROLLBACK%

:end
echo.
pause
endlocal
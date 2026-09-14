@echo off
setlocal
set REPO=D:\ProjectEASTGROUP\landhome-stack
set SRV=uat@10.3.0.99
set APP=/home/uat/SYS/landhome_uat/html
set KEY=%USERPROFILE%\.ssh\uat_key

cd /d %REPO%

echo == Branch ==
git rev-parse --abbrev-ref HEAD

echo == Uncommitted changes (should be none) ==
git status --porcelain

echo.
echo Press Ctrl+C to abort, or
pause

echo == Push ==
git push origin uat || goto fail

echo == Build archive ==
git archive --format=tar.gz -o "%TEMP%\beloan.tar.gz" HEAD:html || goto fail

echo == Copy ==
scp -i "%KEY%" "%TEMP%\beloan.tar.gz" %SRV%:/tmp/ || goto fail

echo == Snapshot on server ==
ssh -i "%KEY%" %SRV% "cd %APP% && git add -A && (git commit -m 'pre-deploy snapshot' || true) && git rev-parse HEAD"

echo == Extract and restart ==
ssh -i "%KEY%" %SRV% "cd %APP% && tar -xzf /tmp/beloan.tar.gz && sudo -n chmod -R 777 storage && sudo -n rm -rf storage/framework/views/* && sudo -n docker exec landhome-uat-apache php artisan config:clear && sudo -n docker restart landhome-uat-apache" || goto fail

echo == Wait for Apache ==
timeout /t 8 /nobreak >nul

echo == Verify ==
ssh -i "%KEY%" %SRV% "curl -s -m 25 -o /dev/null -w 'HTTP %%{http_code}  bytes %%{size_download}\n' http://localhost:8081/user/login"

echo.
echo Deploy finished. Expect HTTP 200 with bytes over 5000.
echo App: http://10.3.0.99:8081
goto end

:fail
echo.
echo *** DEPLOY FAILED - see error above ***

:end
pause
endlocal
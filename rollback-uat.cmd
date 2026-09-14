@echo off
setlocal
set REPO=D:\ProjectEASTGROUP\landhome-stack
set SRV=uat@10.3.0.99
set APP=/home/uat/SYS/landhome_uat/html
set KEY=%USERPROFILE%\.ssh\uat_key
set CONTAINER=landhome-uat-apache

if "%~1"=="" (
    echo.
    echo Usage: rollback-uat.cmd ^<commit-hash^>
    echo.
    if exist "%REPO%\last-rollback-point.txt" (
        echo Last recorded rollback point:
        type "%REPO%\last-rollback-point.txt"
        echo.
    )
    echo Recent commits on the UAT server:
    ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -12"
    echo.
    echo Backups available on server:
    ssh -i "%KEY%" %SRV% "ls -lh /tmp/backup-* 2>/dev/null ^|^| echo '  none'"
    echo.
    pause
    exit /b 1
)

echo.
echo ================= ROLLBACK =================
echo Target commit: %~1
echo.
echo Server currently running:
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1"
echo.
echo Target commit details:
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1 %~1" || goto badhash
echo.
echo ============================================
echo Ctrl+C to abort, any key to roll back
pause

echo.
echo == 1/4 Stash any server-side changes ==
ssh -i "%KEY%" %SRV% "cd %APP% && git stash list && (git add -A && git commit -m 'state before rollback' >/dev/null 2>&1 || true)"

echo.
echo == 2/4 Checkout target ==
ssh -i "%KEY%" %SRV% "cd %APP% && git checkout %~1 2>&1 | tail -3" || goto fail

echo.
echo == 3/4 Fix permissions and clear caches ==
ssh -i "%KEY%" %SRV% "cd %APP% && sudo -n chmod -R 777 storage && sudo -n rm -rf storage/framework/views/* && sudo -n docker exec %CONTAINER% php artisan config:clear && sudo -n docker restart %CONTAINER%" || goto fail
echo    Waiting for Apache...
timeout /t 8 /nobreak >nul

echo.
echo == 4/4 Verify ==
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1"
echo.
ssh -i "%KEY%" %SRV% "curl -s -m 25 -o /dev/null -w '   HTTP %%{http_code}   bytes %%{size_download}\n' http://localhost:8081/user/login"
echo.
ssh -i "%KEY%" %SRV% "sudo -n docker logs %CONTAINER% --tail 100 2>^&1 ^| grep -i 'local.ERROR' ^| tail -3 ; echo '   --- end of errors ---'"

echo.
echo ==================== ROLLED BACK ====================
echo Expect HTTP 200 with bytes over 5000.
echo.
echo NOTE: server is now in detached HEAD. Before the next
echo deploy, either deploy normally (works fine) or run:
echo   ssh -i "%KEY%" %SRV%
echo   cd %APP% ^&^& git checkout uat
goto end

:badhash
echo.
echo *** Commit %~1 not found on the server ***
echo Run rollback-uat.cmd with no arguments to list commits.
goto end

:fail
echo.
echo *** ROLLBACK FAILED - manual intervention needed ***
echo   ssh -i "%KEY%" %SRV%
echo   cd %APP%
echo   git log --oneline -10
echo   git checkout ^<hash^>

:end
echo.
pause
endlocal
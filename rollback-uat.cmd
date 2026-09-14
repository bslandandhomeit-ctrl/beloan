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
    pause
    exit /b 1
)

echo.
echo Rolling back to %~1
echo.
echo Currently running:
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1"
echo.
echo Target:
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1 %~1" || goto badhash
echo.
echo Ctrl+C to abort, any key to continue
pause

echo.
echo == Marking log position ==
ssh -i "%KEY%" %SRV% "sudo -n docker logs %CONTAINER% 2>^&1 ^| wc -l" > "%TEMP%\logmark.txt"
set /p LOGMARK=<"%TEMP%\logmark.txt"
echo    log was %LOGMARK% lines

echo.
echo == Checkout ==
ssh -i "%KEY%" %SRV% "cd %APP% && (git add -A && git commit -m 'state before rollback' >/dev/null 2>&1 || true) && git checkout %~1 2>&1 | tail -2 && sudo -n chmod -R 777 storage" || goto fail

echo.
echo == Verify page ==
ssh -i "%KEY%" %SRV% "cd %APP% && git log --oneline -1"
ssh -i "%KEY%" %SRV% "curl -s -m 25 -o /dev/null -w '   HTTP %%{http_code}   bytes %%{size_download}   %%{time_total}s\n' http://localhost:8081/user/login"

echo.
echo == New errors since rollback ==
ssh -i "%KEY%" %SRV% "sudo -n docker logs %CONTAINER% 2>^&1 ^| tail -n +%LOGMARK% ^| grep -i 'local.ERROR\^|Fatal\^|Segmentation' ^| tail -5 ; echo '   --- end ---'"

echo.
echo == Laravel log ==
ssh -i "%KEY%" %SRV% "ls -la %APP%/storage/logs/ ^| grep laravel ^|^| echo '   no laravel log today (good)'"
ssh -i "%KEY%" %SRV% "tail -5 %APP%/storage/logs/laravel-$(date +%%Y-%%m-%%d).log 2>/dev/null ^|^| echo '   (nothing logged today)'"

echo.
echo == Dirty files on server ==
ssh -i "%KEY%" %SRV% "cd %APP% && git status --porcelain ^| wc -l"

echo.
echo ============== ROLLED BACK ==============
echo Expect: HTTP 200, bytes over 5000, no errors above.
echo App: http://10.3.0.99:8081
goto end

:badhash
echo.
echo *** Commit %~1 not found ***
goto end

:fail
echo.
echo *** ROLLBACK FAILED ***
echo   ssh -i "%KEY%" %SRV%
echo   cd %APP% ^&^& git log --oneline -10 ^&^& git checkout ^<hash^>

:end
echo.
pause
endlocal
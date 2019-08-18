@echo off

for /F "delims=" %%i in ('dir ..\locale\LC_MESSAGES /A:D /S /B') DO (
	del /S /Q %%i
)

timestamp\timestamp.exe >tmpst.txt
set /P tt=<tmpst.txt

for /F "delims=" %%i in ('dir ..\locale\*.mo /A:-D /S /B') DO (
 	copy %%i %%~dpiLC_MESSAGES\%%~ni-%tt%%%~xi >nul
 	echo Скопирован - %%~dpiLC_MESSAGES\%%~ni-%tt%%%~xi
)

del /Q tmpst.txt>nul

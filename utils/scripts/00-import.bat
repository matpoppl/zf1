@ECHO OFF

FOR %%a IN (..\..\data\sql\*.sql) DO (
	echo %%~na

	mysql --defaults-file="%~dpn0.ini" < "%%~fa"
	
	IF NOT "0" == "%ERRORLEVEL%" GOTO ERROR
)

:ERROR
pause

:EOF

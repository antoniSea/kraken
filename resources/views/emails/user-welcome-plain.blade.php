<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Witaj w serwisie</title>
</head>
<body style="background: #181818; color: #fff; font-family: 'Figtree', Arial, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 480px; margin: 40px auto; background: #181818; border: 1px solid #bcbcbc; border-radius: 18px; box-shadow: 0 0 16px #0004; padding: 32px 24px;">
        <h1 style="font-size: 2rem; font-weight: bold; text-align: center; margin-bottom: 24px; color: #fff;">Witaj {{ $user->first_name }}!</h1>
        <p style="font-size: 1.1rem; text-align: center; margin-bottom: 32px; color: #bcbcbc;">Twoje konto zostało utworzone.<br>Możesz się zalogować używając poniższych danych:</p>
        <div style="background: #232323; border-radius: 12px; padding: 20px 16px; margin-bottom: 24px;">
            <div style="margin-bottom: 12px;">
                <span style="color: #bcbcbc;">Login:</span>
                <span style="font-weight: bold; color: #fff;">{{ $user->email }}</span>
            </div>
            <div>
                <span style="color: #bcbcbc;">Hasło:</span>
                <span style="font-weight: bold; color: #fff;">{{ $password }}</span>
            </div>
        </div>
        <p style="font-size: 1rem; color: #bcbcbc; text-align: center;">Zalecamy zmianę hasła po pierwszym zalogowaniu.</p>
        <div style="margin-top: 32px; text-align: center; color: #bcbcbc; font-size: 0.95rem;">
            Pozdrawiamy,<br>Zespół
        </div>
    </div>
</body>
</html> 
<?php
if(!defined("ALLOW_INCLUDE"))	die('Access forbidden');

#language: ENGLISH

### TEMPLATE ###

#/tempalte/menu.php MENU'S HYPERLINKS
$_text['Home'] = 'Home';
$_text['News'] = 'News';
$_text['Contact'] = 'Contact';
$_text['Sign in'] = 'Sign in';
$_text['Join now'] = 'Join now';
$_text['Bookmarks'] = 'Bookmarks';
$_text['Notes'] = 'Notes';
$_text['Calendar'] = 'Calendar';
$_text['My account'] = 'My account';
$_text['Administration Panel'] = 'Administration Panel';
$_text['Log out'] = 'Log out';

#/tempalte/menu_ap.php MENU'S HYPERLINKS
$_text['Start'] = 'Start';
$_text['Settings'] = 'Settings';
$_text['News'] = 'News';
$_text['Users'] = 'Users';

#/template/header.php
$_text['sitename'] = 'Mynotes.pl';
$_text['sitetitle'] = 'MY NOTES';
$_text['sitedescription'] = 'My notebook in the Web';
$_text['AP Entry'] = 'AP Entry';

#/template/footer.php
$_text['Regulations'] = 'Regulations';
$_text['Polityka prywatności'] = 'Privacy policy';
$_text['Zgłoś błąd'] = 'Raport mistake';
$_text['Polityka cookies'] = 'This website uses cookies.<br>
Dzięki nim możemy dostosować stronę do twoich potrzeb.<br>
Jeśli nie zgadzasz się na używanie plików cookies, możesz wyłączyć je w przeglądarce.<br>
Zamykając to okno akceptujesz użycie plików cookies.';

#
$_text['Czytaj dalej'] = 'Read more';
$_text['To first page'] = 'To first page';
$_text['To last page'] = 'To last page';
$_text['Message'] = 'Message';
$_text['Send message'] = 'Send message';
$_text['Search'] = 'Search';
$_text['Version'] = 'Version';
$_text['Name'] = 'Name';
$_text['Date'] = 'Date';
$_text['Modification date'] = 'Modification date';
$_text['Tags'] = 'Tags';
$_text['Actions'] = 'Actions';
$_text['Lock'] = 'Lock';
$_text['Unlock'] = 'Unlock';
$_text['Up'] = 'Up';
$_text['Down'] = 'Down';
$_text['Open'] = 'Open';
$_text['Edit'] = 'Edit';
$_text['Delete'] = 'Delete';
$_text['Week'] = 'Week';
$_text['Next'] = 'Next';
$_text['Previous'] = 'Previous';
$_text['Add new bookmark'] = 'Add new bookmark';
$_text['Add new note'] = 'Add new note';
$_text['Add new event'] = 'Add new event';
$_text['New event'] = 'New event';
#/pages/signin.php
$_text['Forgot password?'] = 'Forgot password?';
#/pages/joinnow.php
$_text['Your Name'] = 'Your Name';
$_text['User Name'] = 'User Name';
$_text['Password'] = 'Password';
$_text['Important'] = 'Important';
$_text['Your Email'] = 'Your Email';
$_text['Accept Rules'] = 'Accept Rules';
$_text['Rules'] = 'Regulations';
$_text['tutaj'] = 'here';
$_text['Log in permanently'] = 'Log in permanently';
$_text['Log In'] = 'Log In';
$_text['Register'] = 'Register';
$_text['Login zajęty'] = 'Login reserved';
$_text['Login dostępny'] = 'Login available';

#/pages/activate.php
$_text['Formularz aktywacyjny'] = 'Activation form';
$_text['Klucz'] = 'Key';
$_text['Ustaw PIN'] = 'Set a PIN';
$_text['Numer PIN musi składać się tylko ze znaków numerycznych oraz mieć 6 znaków długości.'] = 'PIN number can have only numeric characters and length 6 characters.';
$_text['Aktywuj'] = 'Activate';

#/pages/reminder-password.php
$_text['Reminder password'] = 'Reminder password';
$_text['Reset password'] = 'Reset password';
$_text['New password'] = 'New password';
$_text['Confirm new password'] = 'Confirm new password';
$_text['Save new password'] = 'Save new password';
$_text['Token'] = 'Token';

### KOMUNIKATY ###
#alerts
$_text['danger'] = 'error';
$_text['warning'] = 'warning';
$_text['success'] = 'success';
$_text['info'] = 'info';

#database
$_text['Błąd połączenia z bazą danych.'] = 'Connection error to database.';
$_text['Przepraszamy, nie możemy pobrać zawartości strony.'] = 'Sorry, we can\'t load content of this page.';

#/pages/signin.php
$_text['Konto nie zostało aktywowane. Musisz aktywować konto.'] = 'The account has not been activated. You need to activate your account.';
$_text['Konto nie zostało aktywowane przez Administratora.'] = 'Konto nie zostało aktywowane przez Administratora. EN';
$_text['Powiadomimy Cię o tym fakcie poprzez email.'] = 'Powiadomimy Cię o tym fakcie poprzez email. EN';
$_text['Logowanie zakończone.'] = 'Login successful.';
$_text['Za chwilę zostaniesz przeniesiony na stronę główną.'] = 'In a moment you will be transferred to the main page.';
$_text['Logowanie zakończone niepowodzeniem.'] = 'Login failed.';

#/pages/joinnow.php
#pre_registration()
$_text['Nazwa imienia może składać się z małych i dużych liter, cyfr oraz spacji, podkreślnika i myślnika.'] = '';
$_text['Nazwa imienia powinna skladać się z minimum 2 znaków.'] = '';
$_text['Nazwa imienia powinna skladać się z maksimum 24 znaków.'] = '';
$_text['Nazwa użytkownika może składać się z małych i dużych liter, cyfr oraz podkreślnika i myślnika.'] = '';
$_text['Nazwa użytkownika powinien skladać się z minimum 6 znaków.'] = '';
$_text['Nazwa użytkownika powinien skladać się z maksimum 24 znaków.'] = '';
$_text['Hasło może się składać z małych i dużych liter, cyfr oraz znaków:'] = '';
$_text['Hasło użytkownika powinno skladać się z minimum 8 znaków.'] = '';
$_text['Login użytkownika powinna skladać się z maksimum 24 znaków.'] = '';
$_text['Niepoprawny adres email.'] = '';
#pre_registration()
$_text['Nie udało się zarejestrować użytkownika, spróbuj później.'] = '';
$_text['Login użytkownika lub adres mailowy zajęty.'] = '';

$_text['Password strength'] = 'Password strength';
$_text['Very Weak'] = 'Very Weak';
$_text['Weak'] = 'Weak';
$_text['Good'] = 'Good';
$_text['Strong'] = 'Strong';
$_text['Very Strong'] = 'Very Strong';

#wlasciwa strona
$_text['Zostałeś zarejestrowany. Klucz aktywacyjny został wysłany na adres mailowy wskazany podczas rejestracji.'] = '';
$_text['Wymagane wszystkie pola oraz akceptacja regulaminu.'] = '';
$_text['Rejestracja jest wyłączona.'] = '';
$_text['Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.'] = 'Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.';
$_text['You will need this password to log in. Please store it in a secure location.'] = 'You will need this password to log in. Please store it in a secure location.';
$_text['Double-check your email address before continuing.'] = 'Double-check your email address before continuing.';

#/pages/activate.php
$_text['Nieprawidłowe dane.'] = '';
$_text['Konto zostało już aktywowane.'] = '';
$_text['Konto zostało aktywowane.<br> Za chwilę zostaniesz przeniesiony na stronę logowania.'] = '';
$_text['Konto zostało aktywowane.<br> Teraz administrator strony musi akceptować Twoje konto.'] = '';
$_text['Konto nie zostało aktywowane.'] = '';
#check_PIN()
$_text['PIN może składać się tylko z cyfr.'] = '';
$_text['PIN musi mieć 6 znaków.'] = '';

#/pages/news.php
$_text['Brak newsów do wyświetlenia.'] = '';

### DATA I CZAS ###
$_text['poniedziałek'] = 'monday';
$_text['wtorek'] = 'tuesday';
$_text['środa'] = 'wednesday';
$_text['czwartek'] = 'thursday';
$_text['piątek'] = 'friday';
$_text['sobota'] = 'saturday';
$_text['niedziela'] = 'sunday';

$_text['skrot_poniedziałek'] = 'mo';
$_text['skrot_wtorek'] = 'tu';
$_text['skrot_środa'] = 'we';
$_text['skrot_czwartek'] = 'th';
$_text['skrot_piątek'] = 'fr';
$_text['skrot_sobota'] = 'sa';
$_text['skrot_niedziela'] = 'su';

$_text['January'] = 'January';
$_text['February'] = 'February';
$_text['March'] = 'March';
$_text['April'] = 'April';
$_text['May'] = 'May';
$_text['June'] = 'June';
$_text['July'] = 'July';
$_text['August'] = 'August';
$_text['September'] = 'September';
$_text['October'] = 'October';
$_text['November'] = 'November';
$_text['December'] = 'December';

$_text['Tekst na stronie głównej'] = 'Szukasz miejsca na swoje notatki i linki? Właśnie je znalazłeś. W łatwy i szybki sposób zapisuj wszystko na Mynotes.pl! Twoje notatki i linki są dostępne tylko dla Ciebie! No chyba, że chcesz je pokazać znajomym lub światu, nie ma problemu! Zapisuj co chcesz, kiedy chcesz i jak chcesz w Mynotes.pl! Zarejestruj się już teraz!';
?>
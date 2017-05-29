homeBudget jest prostą aplikacją do zarządzania budgetem domowym.

Aby móc korzystać z aplikacji, użytkownik jest zobowiązany założyć konto i zalogować się.
Po uwierzytelnieniu użytkownik może katalogować swoje comiesięczne przychody i wydatki a zadaniem aplikacji jest przeliczenie wprowadzonych kwot i przedstawienie krótkiego podsumowania.
W ramach aplikacji użytkownik może definiować własne kategorie przychodów i wydatków. Przygotowano także prosty interfejs do robienia notatek, np. o nadchodzących wydatkach.

Użytkownik ma pełną możliwość zarządzania wprowadzonymi danymi - dodawanie, edytowanie i usuwanie informacji.
Wszystkie czynności są realizowane za pośrednictwem żądań asynchronicznych.

Aplikacja zawiera dość okrojony zestaw funkcjonalności.
W pierwszej kolejności należałoby zająć się głębszą analizą danych wprowadzanych przez użytkownika (sprawozdania roczne, statystyki, wykresy, możliwość grupowania przychodów  i wydatków według kategorii).
Można byłoby również dodać możliwość współzarządzania budżetem przez partnera/rodzinę użytkownika, a także możliwość eksportu i importu danych. Konieczne jest też wykonanie dokładnych testów.

Aplikacja homeBudget została zbudowana w oparciu o framework Laravel 5.4. Do budowy interfejsu aplikacji wykorzystano Bootstrap 3.3. Testowano w Google Chrome 58 (w Firefox może nie działać np. pole input type="date").

Aplikacja znajduje się pod adresem: http://homebudget.barbwozniak.pl

@extends('layouts.app')

@section('content')

<div class="container about">
	<div class="col-sm-offset-2 col-sm-8">
		<h2>O aplikacji</h2>
		<p>
			<span class="home_em">homeBudget</span> jest prostą aplikacją do zarządzania budgetem domowym.
		</p>
		<p>
			Aby móc korzystać z aplikacji, użytkownik jest zobowiązany założyć konto i zalogować się.
			Po uwierzytelnieniu użytkownik może katalogować swoje comiesięczne przychody i wydatki a zadaniem aplikacji jest przeliczenie wprowadzonych kwot i przedstawienie krótkiego podsumowania.
			W ramach aplikacji użytkownik może definiować własne kategorie przychodów i wydatków. Przygotowano także prosty interfejs do robienia notatek, np. o nadchodzących wydatkach. Użytkownik ma pełną możliwość zarządzania wprowadzonymi danymi - dodawanie, edytowanie i usuwanie informacji.
			Wszystkie czynności są realizowane za pośrednictwem żądań asynchronicznych.
		</p>
		<p>
			Aplikacja zawiera dość okrojony zestaw funkcjonalności.
			W pierwszej kolejności należałoby zająć się głębszą analizą danych wprowadzanych przez użytkownika (sprawozdania roczne, statystyki, wykresy, możliwość grupowania przychodów  i wydatków według kategorii).
			Można byłoby również dodać możliwość współzarządzania budżetem przez partnera/rodzinę użytkownika, a także możliwość eksportu i importu danych. Konieczne jest też wykonanie dokładnych testów.
		</p>
		<p>
			Aplikacja <span class="home_em">homeBudget</span> została zbudowana w oparciu o framework Laravel 5.4. Do budowy interfejsu aplikacji wykorzystano Bootstrap 3.3.
		</p>
		
		<p> <a href="https://bitbucket.org/Silendo/laravel-application">Kod źródłowy - Bitbucket</a></p>
		<p> <span class="home_em">Dane do logowania: barb.wznk@gmail.com, hasło: test123 (przykładowy profil). </span> </p>
		
		<p> Aplikacja została zbudowana na początkowym etapie nauki frameworka Laravel. Aplikację testowano w Google Chrome 58 (w Firefox może nie działać np. pole input type="date").</p>

	</div>
</div>
@endsection

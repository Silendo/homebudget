<!DOCTYPE html>
<html lang="pl_PL">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h3>Witaj, {{$user->name}}!</h3>
 		<p>Poniżej przedstawiamy podsumowanie Twoich przychodów i wydatków w ostatnim miesiącu ({{$budgetMonthSummary[0]['date']}}):</p>
 		@foreach ($budgetMonthSummary as $budget)	
 			<h4>Przychody: {{$budget['revenues']}}</h4>
 			<h4>Wydatki: {{$budget['expenses']}}</h4>
 			<h4>Saldo: {{$budget['revenues'] - $budget['expenses']}}</h4>
 		@endforeach
 		<p>Pamiętaj, że w każdym momencie możesz edytować wprowadzone dane.</p>
 		<br/>
 		<p>Pozdrawiamy</p>
 		<p>Zespół homeBudget</p>
    </body>
</html>
<!DOCTYPE html>
<html lang="pl_PL">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h3>Witaj, {{$user->name}}!</h3>
 		<p>Poniżej przedstawiamy podsumowanie Twoich przychodów i wydatków (stan na dzień {{$now}}):</p>
 		<table>
 			<tr>
 				<td>Miesiąc</td><td>Przychody</td><td>Wydatki</td><td>Saldo</td>
 			</tr>
 			<tr>
 				@foreach ($budgetSummary as $budget)
 				<td>{{$budget['date']}}</td>
 				<td>{{$budget['revenues']}}</td>
 				<td>{{$budget['expenses']}}</td>
 				<td>{{$budget['revenues'] - $budget['expenses']}}</td>
 				@endforeach
 			</tr>
 		</table>
 		<p>Pamiętaj, że w każdym momencie możesz edytować wprowadzone dane.</p>
 		<br/>
 		<p>Pozdrawiamy</p>
 		<p>Zespół homeBudget</p>
    </body>
</html>
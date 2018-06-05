<!DOCTYPE html>
<html lang="pl_PL">
    <head>
        <meta charset="utf-8">
        <style>
        	body{
        		font-family: DejaVu Sans;
        	}
    	</style>
    </head>
    <body>
 		<p>Poniżej przedstawiamy szczegółowe podsumowanie Twoich miesięcznych przychodów i wydatków:</p>
 		@if (count($details['revenues'])>0)
 			<h4>Przychody</h4>
 			@foreach ($details['revenues'] as $revenue)
 				<p>{{$revenue->name}}: <b>{{$revenue->amount}}</b></p>
 			@endforeach
 		@endif
 		@if (count($details['expenses'])>0)
 			<h4>Wydatki</h4>
 			@foreach ($details['expenses'] as $expense)
 				<p>{{$expense->name}}: <b>{{$expense->amount}}</b></p>
 			@endforeach
 		@endif
    </body>
</html>
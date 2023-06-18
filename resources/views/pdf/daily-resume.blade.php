@php($totalPrice = 0)

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead {
        background-color: #f2f2f2;
    }

    table th,
    table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tbody tr:hover {
        background-color: #eaf4ff;
    }

    .bold-text{
        font-weight: bold;
    }
    .center-text{
        text-align: center;
    }
</style>
<h1 class="bold-text center-text">Verkoop overzicht van - {{ \Carbon\Carbon::yesterday()->format('d-m-Y') }}</h1>
<p class="bold-text">Gerechten</p>
<table>
    <thead>
    <tr>
        <th>Gerecht</th>
        <th>Hoeveelheid</th>
        <th>Prijs</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dishCounts as $dishCount)
        <tr>
            <td>{{ $dishCount->dish->name }}</td>
            <td>{{ $dishCount->count }}</td>
            <td>€{{ $dishCount->dish->price * $dishCount->count }},-</td>
        </tr>
        {{ $totalPrice += $dishCount->dish->price * $dishCount->count }}
    @endforeach
    </tbody>
</table>

<p class="bold-text">Gerecht opties</p>
<table>
    <thead>
    <tr>
        <th>Optie</th>
        <th>Hoeveelheid</th>
        <th>Prijs</th>
    </tr>
    </thead>
    <tbody>
    @foreach($optionCounts as $optionCount)
        <tr>
            <td>{{ $optionCount->option->name }}</td>
            <td>{{ $optionCount->count }}</td>
            <td>€{{ $optionCount->option->price * $optionCount->count }},-</td>
        </tr>
        {{ $totalPrice +=  $optionCount->option->price * $optionCount->count }}
    @endforeach
    </tbody>
</table>

<p class="bold-text">Totale omzet: €{{$totalPrice}},-</p>

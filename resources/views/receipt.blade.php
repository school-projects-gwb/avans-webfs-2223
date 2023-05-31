<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kassabon</title>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(base_path('resources/img/dragon-right-facing.png'))); ?>" width="50">
            </td>
            <td>
                <h1 style="color: rgb(255, 0, 0); margin-left: .25rem; margin-right: .25rem; font-size: 1.5rem;">DE GOUDEN DRAAK</h1>
            </td>
            <td>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(base_path('resources/img/dragon-left-facing.png'))); ?>" width="50">
            </td>
        </tr>
    </table>

    <h1 style="text-align: center; text-transform: uppercase; font-size: 1.25rem;">Kassabon tafelnummer {{$tableRegistration['table']['table_number']}}</h1>
    <h2 style="text-align: center; font-weight: normal; margin-top: 0 !important;">Besteldatum {{$tableRegistration['created_at']}}</h2>

    <table style="width: 100% !important;">
        <tr>
            <td style="font-weight: bold; font-size: 1.1rem;">Gerecht</td>
            <td style="font-weight: bold; font-size: 1.1rem; padding-right: .5rem; text-align: right;">Prijs</td>
        </tr>
        @foreach($tableRegistration['orderLines'] as $orderLine):
        <tr>
            <td style="padding-top: .5rem;">{{$orderLine['dish_name']}} (x{{$orderLine['amount']}})</td>
            <td style="text-align: right; padding-right: .5rem;"><b>€ {{$orderLine['combined_price']}}</b></td>
        </tr>
        @if($orderLine['option_names'] != "")
            <tr>
                <td colspan="2" style="font-size: .75rem;">
                    <span style="font-weight: bold; font-size: .75rem;">Opties: </span>
                    {{$orderLine['option_names']}}
                </td>
            </tr>
        @endif
        @endforeach
        <tr>
            <td style="font-size: 1.5rem; font-weight: bold; padding-top: 2rem; color: rgb(255, 0, 0);">
                Totaalbedrag
            </td>
            <td style="font-size: 1.5rem; font-weight: bold; padding-top: 2rem; text-align: right; padding-right: .5rem; color: rgb(255, 0, 0);">
                € {{$tableRegistration['order_totals']}}
            </td>
        </tr>
    </table>
</body>

<style>
    @page { size: 8.5cm 10cm portrait; }
    @page { margin: .5rem; }
    body { margin: .5rem; width: 100%; display: block; }

    * {
        font-size: 12px;
    }
</style>
</html>

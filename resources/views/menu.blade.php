<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Report</title>
</head>
<body style="background: #fefebe;">
    <table style="border-collapse: collapse; background: #fefebe;">
        <tbody>
            <?php $pos=1 ?>
            <tr>
                <td>
                    <span style="font-size: 2rem;">Menukaart</span><br>
                    <span style="font-size: 1.5rem;">Chinees Indische Specialiteiten</span>
                    <span style="font-size: 2rem; color: red; font-weight: bold;">De Gouden Draak</span>
                    <div style="margin-bottom: 1.5rem;">
                        <div>
                            <span style="font-weight: bold;">Openingstijden</span><br>
                            <span>
                                @foreach ($menu_data['restaurant_data']['opening_times_grouped'] as $opening_time_group)
                                    {!! $opening_time_group !!}
                                @endforeach
                            </span>
                        </div>
                        <div style="margin-top: .5rem;">
                            {!! $menu_data['restaurant_data']['menu_description'] !!}
                        </div>
                        <div style="margin-top: .5rem;">
                            <b>Allergieën? Meld het ons!</b><br>
                            <span>Onze producten kunnen kruisbesmetting bevatten.</span><br>
                            <span style="margin-top: .5rem;"><b>Telefoonnummer:</b> {{$menu_data['restaurant_data']['phone_number']}} </span><br>
                            <span><b>E-mailadres:</b> {{$menu_data['restaurant_data']['email_address']}}</span>
                        </div>
                    </div>
                </td>
            </tr>
            @foreach ($menu_data['dish_data'] as $category => $category_content)
                @if ($pos == 1)
                    <tr>
                @endif
                <td class="col">
                    <b style="font-size: 9px;">{{$category}}</b>
                    @foreach ($category_content['dishes'] as $dish)
                        <div style="border: .1px solid transparent;">
                            {{$dish["menu_number"] == null ? '' : $dish["menu_number"]}} {{ $dish["menu_addition"] == null ? '' : $dish["menu_addition"]}}{{$dish["menu_number"] != null || $dish["menu_addition"] != null ? '.' : ''}}
                            <span style="font-size: 8px;">{{ Illuminate\Support\Str::limit($dish["name"], 50) }}</span>
                            <span style="float: right;">€ {{$dish["price"]}}</span>
                            @if ($dish['description'] != null && $dish['description'] !== "")
                                <div style="font-style: italic; font-size: 8px;">
                                    ({{$dish['description']}})
                                </div>
                            @endif
                            @if ($dish['is_discount'] == true && count($dish['options']) > 0 && $dish['option_required'] != null)
                                <span style="text-decoration: underline;">Maak een keuze uit {{$dish['option_amount']}} van onderstaande keuzegerechten:</span>
                                <div>
                                    @foreach ($dish['options'] as $option)
                                        @if ($option['price'] == null)
                                            {{$option['name']. ', '}}
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </td>
                @if ($pos == 3)
                    </tr>
                    <?php $pos = 1; ?>
               @else
                <?php $pos++; ?>
               @endif
            @endforeach
            <tr style="page-break-after: always;"></tr>
            <tr>
                <td class="col" style="column-span: 3 !important;">
                    <b style="font-size: 24px; text-transform: uppercase; color: green;">Aanbiedingen</b>
                    @foreach ($menu_data['dish_discount_data'] as $dish)
                        <div style="border: .1px solid transparent;">
                            {{$dish["menu_number"] == null ? '' : $dish["menu_number"]}} {{ $dish["menu_addition"] == null ? '' : $dish["menu_addition"]}}{{$dish["menu_number"] != null || $dish["menu_addition"] != null ? '.' : ''}}
                            <span style="font-size: 12px;">{{ Illuminate\Support\Str::limit($dish["name"], 50) }}</span>
                            <span style="float: right;">€ {{$dish["price"]}}</span>
                            @if ($dish['description'] != null && $dish['description'] !== "")
                                <div style="font-style: italic;">
                                     ({{$dish['description']}})
                                </div>
                                @endif
                                @if ($dish['is_discount'] == true && count($dish['options']) > 0 && $dish['option_required'] != null)
                                <span style="text-decoration: underline;">Maak een keuze uit {{$dish['option_amount']}} van onderstaande keuzegerechten:</span>
                                <div>
                                    @foreach ($dish['options'] as $option)
                                        @if ($option['price'] == null)
                                            {{$option['name']. ', '}}
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
</body>

<style>
    * {
        font-size: 12px;
    }

    .col {
        font-size: 9px;
        width: 227px;
        padding: .25rem;
        vertical-align:top;
    }
</style>
</html>

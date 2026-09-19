{{-- Branded report template (PRD FR-RPT-03). Design-system colours; DejaVu Sans for --}}
{{-- the ₦ + masking glyphs. `$crest` is the state crest as a data URI (Dompdf runs with --}}
{{-- remote loading off); reports that do not ask for it keep the placeholder slot. --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 32px 44px 32px; }
        body { font-family: "DejaVu Sans", sans-serif; color: #181818; font-size: 11px; }

        .letterhead { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .letterhead td { vertical-align: middle; }
        .crest {
            width: 64px; height: 64px; border: 1px solid #46551F; border-radius: 6px;
            text-align: center; color: #52564A; font-size: 7px; letter-spacing: 0.08em;
        }
        .crest span { display: inline-block; padding-top: 24px; text-transform: uppercase; }
        .crest-img { width: 64px; height: auto; }
        .org { font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase; color: #46551F; }
        .title { font-size: 18px; font-weight: bold; color: #2C3512; margin: 2px 0; }
        .sub { font-size: 10px; color: #52564A; }

        .rule { height: 4px; background: #C6F135; margin: 8px 0 14px 0; }

        table.summary { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.summary td.section { width: 33%; vertical-align: top; padding: 0 22px 12px 0; }
        table.summary td.section:last-child { padding-right: 0; }
        .section-title {
            font-size: 9px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase;
            color: #46551F; border-bottom: 1px solid #C9CBC1; padding-bottom: 3px; margin-bottom: 2px;
        }
        table.items { width: 100%; border-collapse: collapse; }
        table.items td { padding: 3px 0; font-size: 10px; border-bottom: 1px solid #E2E3DD; }
        table.items td.value { text-align: right; font-weight: bold; color: #2C3512; }

        table.tiles { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        td.tile-cell { width: 25%; vertical-align: top; padding: 0 6px 8px 0; }
        .tile { border: 1px solid #E2E3DD; border-radius: 6px; padding: 7px 9px; background: #F5F5F1; }
        .tile-label { font-size: 8.5px; color: #52564A; }
        .tile-value { font-size: 16px; font-weight: bold; color: #181818; margin-top: 2px; }
        .tile-note { font-size: 8px; color: #52564A; margin-top: 1px; }

        table.figure-row { width: 100%; border-collapse: collapse; margin-bottom: 8px; page-break-inside: avoid; }
        td.figure-cell { width: 50%; vertical-align: top; padding: 0 6px 0 0; }
        td.figure-cell.wide { width: 100%; }
        .figure { border: 1px solid #E2E3DD; border-radius: 6px; padding: 9px 11px; page-break-inside: avoid; }
        .figure-title { font-size: 11.5px; font-weight: bold; color: #2C3512; }
        .figure-sub { font-size: 8.5px; color: #52564A; margin-top: 1px; }
        .figure-img { display: block; margin-top: 8px; }
        table.figure-items { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.figure-items td { font-size: 9px; padding: 2px 0; border-bottom: 1px solid #E2E3DD; }
        table.figure-items td.value { text-align: right; font-weight: bold; color: #181818; }
        .chip { display: inline-block; width: 8px; height: 8px; margin-right: 5px; border-radius: 2px; }
        .figure-note { font-size: 8px; color: #52564A; margin-top: 5px; }
        .table-title { font-size: 11.5px; font-weight: bold; color: #2C3512; margin: 6px 0; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th {
            background: #2C3512; color: #F5F5F1; text-align: left; padding: 7px 9px;
            font-size: 9px; letter-spacing: 0.06em; text-transform: uppercase;
        }
        table.data td { padding: 6px 9px; border-bottom: 1px solid #E2E3DD; font-size: 10px; }
        table.data tr:nth-child(even) td { background: #DCEDDC; }
        .num { text-align: right; }
        .empty { padding: 16px 9px; color: #52564A; }

        .foot {
            position: fixed; bottom: -28px; left: 0; right: 0;
            color: #52564A; font-size: 8px; border-top: 1px solid #E2E3DD; padding-top: 5px;
        }
    </style>
</head>
<body>
    <table class="letterhead">
        <tr>
            <td style="width: 72px;">
                @if (! empty($crest))
                    <img class="crest-img" src="{{ $crest }}" alt="Jigawa State crest">
                @else
                    <div class="crest"><span>State<br>Crest</span></div>
                @endif
            </td>
            <td>
                <div class="org">Jigawa State · Social Protection MIS</div>
                <div class="title">{{ $data->title }}</div>
                <div class="sub">{{ $data->subtitle }} · Scope: {{ $data->scopeLabel }} · Generated {{ $data->generatedAt->format('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

    @if ($data->highlights !== [])
        <table class="tiles">
            @foreach (array_chunk($data->highlights, 4) as $tiles)
                <tr>
                    @foreach ($tiles as $tile)
                        <td class="tile-cell">
                            <div class="tile">
                                <div class="tile-label">{{ $tile['label'] }}</div>
                                <div class="tile-value">{{ $tile['value'] }}</div>
                                @if (! empty($tile['note']))
                                    <div class="tile-note">{{ $tile['note'] }}</div>
                                @endif
                            </div>
                        </td>
                    @endforeach
                    @for ($i = count($tiles); $i < 4; $i++)
                        <td class="tile-cell"></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    @endif

    {{-- Chart cards, two to a row; a card is never split across a page. --}}
    @foreach ($data->figureRows() as $figures)
        <table class="figure-row">
            <tr>
                @foreach ($figures as $figure)
                    <td class="{{ $figure->wide ? 'figure-cell wide' : 'figure-cell' }}">
                        <div class="figure">
                            <div class="figure-title">{{ $figure->title }}</div>
                            @if ($figure->subtitle)
                                <div class="figure-sub">{{ $figure->subtitle }}</div>
                            @endif
                            @if ($figure->image)
                                <img class="figure-img" src="{{ $figure->image }}" width="{{ $figure->imageWidth }}" height="{{ $figure->imageHeight }}" alt="">
                            @endif
                            @if ($figure->items !== [])
                                <table class="figure-items">
                                    @foreach ($figure->items as $item)
                                        <tr>
                                            <td>
                                                @if (! empty($item['color']))
                                                    <span class="chip" style="background: {{ $item['color'] }};"></span>
                                                @endif
                                                {{ $item['label'] }}
                                            </td>
                                            <td class="value">{{ $item['value'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                            @if ($figure->note)
                                <div class="figure-note">{{ $figure->note }}</div>
                            @endif
                        </div>
                    </td>
                @endforeach
                @if (count($figures) === 1 && ! $figures[0]->wide)
                    <td class="figure-cell"></td>
                @endif
            </tr>
        </table>
    @endforeach

    {{-- A short table under the charts starts whole on the next page rather than leaving
         a few orphan rows; a long one flows across pages as usual. --}}
    @php($keepTogether = $data->figures !== [] && $data->rowCount() <= 15)
    @if ($keepTogether)
        <div style="page-break-inside: avoid;">
    @endif
    @if ($data->figures !== [] && $data->columns !== [])
        <div class="table-title">{{ $data->reportKey === 'mda-dashboard' ? 'How your programmes are doing' : 'Details' }}</div>
    @endif

    @if ($data->summary !== [])
        {{-- Three sections to a row: Dompdf lays out tables reliably, flex not at all. --}}
        <table class="summary">
            @foreach (array_chunk($data->summary, 3) as $sections)
                <tr>
                    @foreach ($sections as $section)
                        <td class="section">
                            <div class="section-title">{{ $section->title }}</div>
                            <table class="items">
                                @foreach ($section->items as $item)
                                    <tr>
                                        <td>{{ $item['label'] }}</td>
                                        <td class="value">{{ $item['value'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    @endforeach
                    @for ($i = count($sections); $i < 3; $i++)
                        <td class="section"></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    @endif

    <table class="data">
        <thead>
            <tr>
                @foreach ($data->columns as $column)
                    <th class="{{ $column->numeric ? 'num' : '' }}">{{ $column->label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($data->rows as $row)
                <tr>
                    @foreach ($data->columns as $column)
                        <td class="{{ $column->numeric ? 'num' : '' }}">{{ $data->cell($row, $column) }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td class="empty" colspan="{{ max(1, count($data->columns)) }}">No data for this scope.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($keepTogether)
        </div>
    @endif

    <div class="foot">
        SP-MIS — counts only; contains no personal records. Scoped to the requester. Confidential; not for redistribution.
    </div>
</body>
</html>

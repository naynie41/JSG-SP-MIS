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

    <div class="foot">
        SP-MIS — de-identified aggregate report, scoped to the requester. Confidential; not for redistribution.
    </div>
</body>
</html>

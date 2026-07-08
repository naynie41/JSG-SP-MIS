{{-- Branded report template (PRD FR-RPT-03). Design-system colours; DejaVu Sans for --}}
{{-- the ₦ + masking glyphs. The crest slot is a placeholder for the state crest / --}}
{{-- letterhead image — drop the crest in `.crest` when supplied by the design owner. --}}
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
        .org { font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase; color: #46551F; }
        .title { font-size: 18px; font-weight: bold; color: #2C3512; margin: 2px 0; }
        .sub { font-size: 10px; color: #52564A; }

        .rule { height: 4px; background: #C6F135; margin: 8px 0 14px 0; }

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
                <div class="crest"><span>State<br>Crest</span></div>
            </td>
            <td>
                <div class="org">Jigawa State · Social Protection MIS</div>
                <div class="title">{{ $data->title }}</div>
                <div class="sub">{{ $data->subtitle }} · Scope: {{ $data->scopeLabel }} · Generated {{ $data->generatedAt->format('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

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

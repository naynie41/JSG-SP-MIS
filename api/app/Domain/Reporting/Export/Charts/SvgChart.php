<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export\Charts;

/**
 * Charts for PDF reports, drawn as SVG and handed to Dompdf as data-URI images.
 *
 * Dompdf renders an SVG image faithfully (paths, arcs, opacity, text) but ignores inline
 * <svg> in the page, and it runs with remote loading off — so every chart travels inside
 * the HTML as an image. The marks follow the dashboard's own specs: thin marks, 2px
 * lines over a 10% wash, rounded data ends, a 2px white gap between touching fills,
 * hairline grid, and text in ink colours rather than series colours.
 *
 * Each method returns null when there is nothing to draw, so the caller can say so in
 * words instead of printing an empty frame.
 */
final class SvgChart
{
    public const INK = '#181818';

    public const MUTED = '#52564A';

    public const GRID = '#E2E3DD';

    public const AXIS = '#C9CBC1';

    public const TRACK = '#DCEDDC';

    public const SERIES = '#008300';

    public const WEAK = '#B4791E';

    private const FONT = 'DejaVu Sans';

    /**
     * One series over months: line, wash, grid, the latest value labelled at its end.
     *
     * @param  list<array{month: string, value: int|float}>  $points
     * @param  callable(float): string  $axisFormat
     * @param  callable(float): string  $valueFormat
     * @return array{uri: string, width: int, height: int}|null
     */
    public static function area(array $points, int $width, int $height, callable $axisFormat, callable $valueFormat): ?array
    {
        $points = array_slice($points, -12);
        $values = array_map(static fn (array $p): float => (float) $p['value'], $points);
        if ($values === [] || max($values) <= 0) {
            return null;
        }

        [$left, $right, $top, $bottom] = [46, 14, 18, 22];
        $innerWidth = $width - $left - $right;
        $innerHeight = $height - $top - $bottom;
        $ticks = self::niceTicks(max($values));
        $ceiling = (float) (end($ticks) ?: 1);
        $last = count($points) - 1;

        $x = static fn (int $i): float => $left + ($last === 0 ? $innerWidth / 2 : ($i / $last) * $innerWidth);
        $y = static fn (float $v): float => $top + $innerHeight - ($v / $ceiling) * $innerHeight;

        $body = '';
        foreach ($ticks as $tick) {
            $ty = self::n($y($tick));
            $body .= '<line x1="'.$left.'" y1="'.$ty.'" x2="'.($width - $right).'" y2="'.$ty.'" stroke="'.($tick == 0 ? self::AXIS : self::GRID).'" stroke-width="1"/>';
            $body .= self::text($left - 6, $y($tick) + 3, $axisFormat($tick), 8.5, self::MUTED, 'end');
        }

        $every = $innerWidth / count($points) < 30 ? 2 : 1;
        foreach ($points as $i => $point) {
            if ($i % $every === 0 || $i === $last) {
                $body .= self::text($x($i), $height - 6, self::monthShort((string) $point['month']), 8.5, self::MUTED, 'middle');
            }
        }

        $line = '';
        foreach ($values as $i => $value) {
            $line .= ($i === 0 ? 'M' : 'L').self::n($x($i)).','.self::n($y($value)).' ';
        }
        $baseline = self::n($top + $innerHeight);
        $area = $line.'L'.self::n($x($last)).','.$baseline.' L'.self::n($x(0)).','.$baseline.' Z';

        $body .= '<path d="'.$area.'" fill="'.self::SERIES.'" fill-opacity="0.1"/>';
        $body .= '<path d="'.trim($line).'" fill="none" stroke="'.self::SERIES.'" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>';
        $body .= '<circle cx="'.self::n($x($last)).'" cy="'.self::n($y($values[$last])).'" r="3.5" fill="'.self::SERIES.'" stroke="#FFFFFF" stroke-width="2"/>';
        // A latest value of zero sits on the baseline, where its label would collide with
        // the axis; the card's "Latest" line states it instead.
        if ($values[$last] > 0) {
            $body .= self::text($x($last), max(10, $y($values[$last]) - 8), $valueFormat($values[$last]), 9, self::INK, 'end', true);
        }

        return self::image($width, $height, $body);
    }

    /**
     * Part-to-whole ring with a 2px white gap between slices and one figure in its core.
     *
     * @param  list<array{value: int|float, color: string}>  $slices
     * @return array{uri: string, width: int, height: int}|null
     */
    public static function donut(array $slices, int $size, string $centerValue, string $centerLabel): ?array
    {
        $total = array_sum(array_map(static fn (array $s): float => max(0.0, (float) $s['value']), $slices));
        if ($total <= 0) {
            return null;
        }

        $center = $size / 2;
        $outer = $center - 2;
        $inner = $outer - 22;
        $cursor = 0.0;
        $body = '';

        foreach ($slices as $slice) {
            $value = (float) $slice['value'];
            if ($value <= 0) {
                continue;
            }
            $start = $cursor;
            $cursor += ($value / $total) * 360;
            $body .= '<path d="'.self::arc($center, $center, $outer, $inner, $start, $cursor).'" fill="'.$slice['color'].'" stroke="#FFFFFF" stroke-width="2"/>';
        }

        $body .= self::text($center, $center + 3, $centerValue, 17, self::INK, 'middle', true);
        $body .= self::text($center, $center + 17, $centerLabel, 8.5, self::MUTED, 'middle');

        return self::image($size, $size, $body);
    }

    /**
     * Ordered categories as columns, a value on each cap. A category name that will not
     * fit its column breaks onto two lines. A withheld group keeps a grey stub.
     *
     * @param  list<array{label: string, count: int}>  $rows
     * @return array{uri: string, width: int, height: int}|null
     */
    public static function columns(array $rows, int $width, int $height, ?int $minimum = null): ?array
    {
        if ($rows === [] || max(array_map(static fn (array $r): int => $r['count'], $rows)) <= 0) {
            return null;
        }

        $band = $width / count($rows);
        $fits = static fn (string $label): bool => ! str_contains($label, ' ') || mb_strlen($label) * 5.3 <= $band - 4;
        $wraps = count(array_filter($rows, static fn (array $r): bool => ! $fits($r['label']))) > 0;

        $top = 16;
        $bottom = $wraps ? 30 : 18;
        $innerHeight = $height - $top - $bottom;
        $columnWidth = min(22, $band * 0.55);
        $published = array_map(static fn (array $r): int => $r['count'], array_filter($rows, static fn (array $r): bool => ! self::held($r['count'], $minimum)));
        $max = max(1, ...($published === [] ? [1] : $published));
        $baseline = $top + $innerHeight;

        $body = '<line x1="0" y1="'.$baseline.'" x2="'.$width.'" y2="'.$baseline.'" stroke="'.self::AXIS.'" stroke-width="1"/>';

        foreach ($rows as $i => $row) {
            $held = self::held($row['count'], $minimum);
            $columnHeight = $held ? $innerHeight * 0.3 : ($row['count'] / $max) * $innerHeight;
            $cx = $band * $i + $band / 2;
            $y = $baseline - $columnHeight;

            if ($columnHeight > 0) {
                $body .= '<path d="'.self::columnPath($cx - $columnWidth / 2, $y, $columnWidth, $columnHeight).'" fill="'.($held ? self::AXIS : self::SERIES).'"/>';
            }
            $body .= self::text($cx, $y - 4, $held ? '< '.$minimum : number_format($row['count']), 8.5, self::INK, 'middle', true);

            if ($fits($row['label'])) {
                $body .= self::text($cx, $baseline + 12, $row['label'], 8.5, self::MUTED, 'middle');
            } else {
                $split = (int) mb_strpos($row['label'], ' ');
                $body .= self::text($cx, $baseline + 12, mb_substr($row['label'], 0, $split), 8.5, self::MUTED, 'middle');
                $body .= self::text($cx, $baseline + 23, mb_substr($row['label'], $split + 1), 8.5, self::MUTED, 'middle');
            }
        }

        return self::image($width, $height, $body);
    }

    /**
     * Ranked horizontal bars: name, bar on a neutral track, value at the end.
     *
     * @param  list<array{label: string, count: int}>  $rows
     * @return array{uri: string, width: int, height: int}|null
     */
    public static function bars(array $rows, int $width, ?int $minimum = null, ?callable $format = null): ?array
    {
        if ($rows === [] || max(array_map(static fn (array $r): int => $r['count'], $rows)) <= 0) {
            return null;
        }

        // Counts are the common case; a money bar passes a formatter, because "177,800"
        // sitting where a headcount usually sits reads as 177,800 people.
        $format ??= static fn (int $value): string => number_format($value);

        $rowHeight = 20;
        $labelWidth = (int) min(130, $width * 0.38);
        $valueWidth = 44;
        $trackX = $labelWidth + 6;
        $trackWidth = $width - $trackX - $valueWidth - 6;
        $published = array_map(static fn (array $r): int => $r['count'], array_filter($rows, static fn (array $r): bool => ! self::held($r['count'], $minimum)));
        $max = max(1, ...($published === [] ? [1] : $published));
        $maxChars = (int) floor($labelWidth / 5.4);

        $body = '';
        foreach ($rows as $i => $row) {
            $held = self::held($row['count'], $minimum);
            $cy = $i * $rowHeight + $rowHeight / 2;
            $label = mb_strlen($row['label']) > $maxChars ? mb_substr($row['label'], 0, $maxChars - 1).'…' : $row['label'];
            $fill = $held ? $trackWidth : max(2, ($row['count'] / $max) * $trackWidth);

            $body .= self::text(0, $cy + 3, $label, 9, self::INK, 'start');
            $body .= '<rect x="'.$trackX.'" y="'.self::n($cy - 3.5).'" width="'.self::n($trackWidth).'" height="7" rx="3.5" fill="'.self::GRID.'"/>';
            $body .= '<rect x="'.$trackX.'" y="'.self::n($cy - 3.5).'" width="'.self::n($fill).'" height="7" rx="3.5" fill="'.($held ? self::AXIS : self::SERIES).'"/>';
            $body .= self::text($width, $cy + 3, $held ? '< '.$minimum : $format($row['count']), 9, self::INK, 'end', true);
        }

        return self::image($width, count($rows) * $rowHeight, $body);
    }

    /**
     * Wholes divided into parts: one titled bar per group, 2px white gaps between parts.
     *
     * @param  list<array{title: string, segments: list<array{value: int, color: string}>}>  $groups
     * @return array{uri: string, width: int, height: int}|null
     */
    public static function splitBars(array $groups, int $width): ?array
    {
        $body = '';
        $y = 0;

        foreach ($groups as $group) {
            $segments = array_values(array_filter($group['segments'], static fn (array $s): bool => $s['value'] > 0));
            $total = array_sum(array_map(static fn (array $s): int => $s['value'], $segments));

            $body .= self::text(0, $y + 10, $group['title'], 9.5, self::INK, 'start', true);
            if ($total === 0) {
                $body .= self::text(0, $y + 26, 'None recorded', 9, self::MUTED, 'start');
            } else {
                $available = $width - 2 * (count($segments) - 1);
                $cursor = 0.0;
                foreach ($segments as $segment) {
                    $segmentWidth = max(3, ($segment['value'] / $total) * $available);
                    $body .= '<rect x="'.self::n($cursor).'" y="'.($y + 16).'" width="'.self::n($segmentWidth).'" height="12" fill="'.$segment['color'].'"/>';
                    $cursor += $segmentWidth + 2;
                }
            }
            $y += 40;
        }

        return $y === 0 ? null : self::image($width, $y, $body);
    }

    /**
     * Shares against 100% as rings; the weakest turns amber and says so in words.
     *
     * @param  list<array{label: string, ratio: float|null, weakest: bool}>  $meters
     * @return array{uri: string, width: int, height: int}
     */
    public static function rings(array $meters, int $width): array
    {
        $columns = $width >= 560 ? 4 : 2;
        $cellWidth = $width / $columns;
        $cellHeight = 104;
        $rows = (int) ceil(count($meters) / $columns);
        $radius = 26;

        $body = '';
        foreach ($meters as $i => $meter) {
            $cx = $cellWidth * ($i % $columns) + $cellWidth / 2;
            $cy = intdiv($i, $columns) * $cellHeight + 34;
            $ratio = $meter['ratio'];
            $color = $meter['weakest'] ? self::WEAK : self::SERIES;

            $body .= '<circle cx="'.self::n($cx).'" cy="'.$cy.'" r="'.$radius.'" fill="none" stroke="'.self::TRACK.'" stroke-width="8"/>';
            if ($ratio !== null && $ratio >= 0.999) {
                $body .= '<circle cx="'.self::n($cx).'" cy="'.$cy.'" r="'.$radius.'" fill="none" stroke="'.$color.'" stroke-width="8"/>';
            } elseif ($ratio !== null && $ratio > 0) {
                $end = $ratio * 360;
                [$sx, $sy] = self::polar($cx, $cy, $radius, 0);
                [$ex, $ey] = self::polar($cx, $cy, $radius, $end);
                $large = $end > 180 ? 1 : 0;
                $body .= '<path d="M'.self::n($sx).','.self::n($sy).' A'.$radius.','.$radius.' 0 '.$large.' 1 '.self::n($ex).','.self::n($ey).'" fill="none" stroke="'.$color.'" stroke-width="8" stroke-linecap="round"/>';
            }

            $body .= self::text($cx, $cy + 4, $ratio === null ? '—' : round($ratio * 100).'%', 11, self::INK, 'middle', true);
            $body .= self::text($cx, $cy + 46, $meter['label'], 8.5, self::INK, 'middle');
            if ($meter['weakest']) {
                $body .= self::text($cx, $cy + 58, 'Weakest', 8, '#6E4A0F', 'middle', true);
            }
        }

        return self::image($width, $rows * $cellHeight, $body);
    }

    /**
     * Areas shaded by band, projected to fit the frame. Points closer than a pixel are
     * dropped: the boundary files are survey-grade, and a report does not need that.
     *
     * @param  list<array{geometry: array<string, mixed>, color: string}>  $areas
     * @return array{uri: string, width: int, height: int}|null
     */
    public static function map(array $areas, int $width, int $height): ?array
    {
        $polygons = [];
        foreach ($areas as $index => $area) {
            $geometry = $area['geometry'];
            $coordinates = (array) ($geometry['coordinates'] ?? []);
            $parts = match ($geometry['type'] ?? null) {
                'Polygon' => [$coordinates],
                'MultiPolygon' => $coordinates,
                default => [],
            };
            foreach ($parts as $polygon) {
                $polygons[] = ['area' => $index, 'rings' => (array) $polygon];
            }
        }

        $lons = [];
        $lats = [];
        foreach ($polygons as $polygon) {
            foreach ($polygon['rings'] as $ring) {
                foreach ((array) $ring as $point) {
                    $lons[] = (float) $point[0];
                    $lats[] = (float) $point[1];
                }
            }
        }
        if ($lons === []) {
            return null;
        }

        [$minLon, $maxLon, $minLat, $maxLat] = [min($lons), max($lons), min($lats), max($lats)];
        $squash = cos(deg2rad(($minLat + $maxLat) / 2));
        $geoWidth = max(1e-6, ($maxLon - $minLon) * $squash);
        $geoHeight = max(1e-6, $maxLat - $minLat);
        $scale = min(($width - 8) / $geoWidth, ($height - 8) / $geoHeight);
        $offsetX = ($width - $geoWidth * $scale) / 2;
        $offsetY = ($height - $geoHeight * $scale) / 2;

        $paths = [];
        foreach ($polygons as $polygon) {
            $d = '';
            foreach ($polygon['rings'] as $ring) {
                $previous = null;
                $first = true;
                foreach ((array) $ring as $point) {
                    $px = $offsetX + ((float) $point[0] - $minLon) * $squash * $scale;
                    $py = $offsetY + ($maxLat - (float) $point[1]) * $scale;
                    if ($previous !== null && abs($px - $previous[0]) < 0.7 && abs($py - $previous[1]) < 0.7) {
                        continue;
                    }
                    $d .= ($first ? 'M' : 'L').self::n($px).','.self::n($py).' ';
                    $previous = [$px, $py];
                    $first = false;
                }
                $d .= 'Z ';
            }
            $paths[$polygon['area']] = ($paths[$polygon['area']] ?? '').$d;
        }

        $body = '';
        foreach ($paths as $index => $d) {
            $body .= '<path d="'.trim($d).'" fill="'.$areas[$index]['color'].'" fill-rule="evenodd" stroke="#FFFFFF" stroke-width="0.8"/>';
        }

        return self::image($width, $height, $body);
    }

    /* ------------------------------------------------------------------- utils */

    /** 1,284 · 12.9K · 4.2M — short enough for an axis tick. */
    public static function compact(float $value): string
    {
        $abs = abs($value);

        return match (true) {
            $abs >= 1e9 => self::trim($value / 1e9).'B',
            $abs >= 1e6 => self::trim($value / 1e6).'M',
            $abs >= 1e4 => self::trim($value / 1e3).'K',
            default => number_format($value),
        };
    }

    /** Kobo in, compact naira out. */
    public static function compactNaira(float $kobo): string
    {
        return '₦'.self::compact($kobo / 100);
    }

    /**
     * Clean ticks from zero: 0 / 25 / 50 / 75 / 100.
     *
     * @return list<float>
     */
    public static function niceTicks(float $max, int $count = 4): array
    {
        if ($max <= 0) {
            return [0.0, 1.0];
        }
        $raw = $max / $count;
        $magnitude = 10 ** floor(log10($raw));
        $normalised = $raw / $magnitude;
        $step = ($normalised <= 1 ? 1 : ($normalised <= 2 ? 2 : ($normalised <= 2.5 ? 2.5 : ($normalised <= 5 ? 5 : 10)))) * $magnitude;
        $ceiling = ceil($max / $step) * $step;

        $ticks = [];
        for ($tick = 0.0; $tick <= $ceiling + $step / 2; $tick += $step) {
            $ticks[] = round($tick, 3);
        }

        return $ticks;
    }

    private static function held(int $count, ?int $minimum): bool
    {
        return $minimum !== null && $minimum > 0 && $count > 0 && $count < $minimum;
    }

    private static function trim(float $value): string
    {
        return rtrim(rtrim(number_format($value, 1), '0'), '.');
    }

    private static function monthShort(string $ym): string
    {
        $month = (int) substr($ym, 5, 2);

        return $month >= 1 && $month <= 12 ? date('M', mktime(0, 0, 0, $month, 1)) : $ym;
    }

    private static function n(float $value): string
    {
        return (string) round($value, 1);
    }

    /** @return array{0: float, 1: float} */
    private static function polar(float $cx, float $cy, float $radius, float $degrees): array
    {
        $angle = deg2rad($degrees - 90);

        return [$cx + $radius * cos($angle), $cy + $radius * sin($angle)];
    }

    private static function arc(float $cx, float $cy, float $outer, float $inner, float $start, float $end): string
    {
        // A full ring cannot be one arc: its start and end points coincide.
        if ($end - $start >= 359.99) {
            return self::arc($cx, $cy, $outer, $inner, $start, $start + 180).' '.self::arc($cx, $cy, $outer, $inner, $start + 180, $start + 359.98);
        }

        [$x1, $y1] = self::polar($cx, $cy, $outer, $start);
        [$x2, $y2] = self::polar($cx, $cy, $outer, $end);
        [$x3, $y3] = self::polar($cx, $cy, $inner, $end);
        [$x4, $y4] = self::polar($cx, $cy, $inner, $start);
        $large = $end - $start > 180 ? 1 : 0;

        return 'M'.self::n($x1).','.self::n($y1).' A'.$outer.','.$outer.' 0 '.$large.' 1 '.self::n($x2).','.self::n($y2)
            .' L'.self::n($x3).','.self::n($y3).' A'.$inner.','.$inner.' 0 '.$large.' 0 '.self::n($x4).','.self::n($y4).' Z';
    }

    private static function columnPath(float $x, float $y, float $width, float $height, float $radius = 3): string
    {
        $r = min($radius, $width / 2, $height);

        return 'M'.self::n($x).','.self::n($y + $height).' V'.self::n($y + $r).' Q'.self::n($x).','.self::n($y).' '.self::n($x + $r).','.self::n($y)
            .' H'.self::n($x + $width - $r).' Q'.self::n($x + $width).','.self::n($y).' '.self::n($x + $width).','.self::n($y + $r)
            .' V'.self::n($y + $height).' Z';
    }

    private static function text(float $x, float $y, string $content, float $size, string $fill, string $anchor, bool $bold = false): string
    {
        return '<text x="'.self::n($x).'" y="'.self::n($y).'" font-family="'.self::FONT.'" font-size="'.$size.'" fill="'.$fill.'" text-anchor="'.$anchor.'"'
            .($bold ? ' font-weight="bold"' : '').'>'.htmlspecialchars($content, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</text>';
    }

    /** @return array{uri: string, width: int, height: int} */
    private static function image(int $width, int $height, string $body): array
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">'.$body.'</svg>';

        return ['uri' => 'data:image/svg+xml;base64,'.base64_encode($svg), 'width' => $width, 'height' => $height];
    }
}

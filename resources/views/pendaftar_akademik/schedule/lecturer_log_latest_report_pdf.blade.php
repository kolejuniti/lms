<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Latest Lecturer Log Timetable</title>
    <style>
        @page { margin: 10px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; color: #111; margin-bottom: 22px; }
        .page-break { page-break-after: always; }
        .header { margin-bottom: 6px; }
        .header h2 { margin: 0 0 2px 0; font-size: 13px; }
        .meta { font-size: 9px; margin: 0; }
        .lecturer { margin-top: 6px; }
        .lecturer h3 { margin: 0 0 2px 0; font-size: 11px; }
        .info { margin: 0 0 6px 0; }
        .info span { display: inline-block; margin-right: 12px; }
        .small { font-size: 10px; color: #333; }

        /* Lecturer details (per-row) */
        .lecturer-info { width: 100%; border-collapse: collapse; margin: 0 0 6px 0; }
        .lecturer-info td { padding: 1px 0; vertical-align: top; }
        .lecturer-info .label { width: 88px; font-weight: bold; }

        /* Footer (bottom-right on every page) */
        .footer {
            position: fixed;
            bottom: 6px;
            right: 10px;
            font-size: 9px;
            color: #333;
            text-align: right;
        }

        /* Timetable grid (calendar-like) */
        .timetable { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .timetable th, .timetable td { border: 1px solid #999; vertical-align: top; }
        .timetable th { background: #f2f2f2; font-size: 9px; padding: 3px; text-align: center; }
        .time-col { width: 56px; background: #fafafa; font-size: 8px; padding: 3px; text-align: center; }
        .slot { height: 18px; padding: 1px; }
        .event {
            background: #fff;
            color: #111;
            border-radius: 4px;
            padding: 4px;
            line-height: 1.25;
            position: relative;
            border: none;
        }
        .event .time { font-weight: bold; font-size: 8px; }
        .event .title { font-weight: bold; font-size: 8px; margin-top: 1px; }
        .event .desc { font-size: 7px; opacity: 0.95; margin-top: 1px; }
        .event .programs { font-size: 7px; font-weight: bold; margin-top: 3px; text-align: center; }
    </style>
</head>
<body>
    @php($generatedAtRaw = ($data['generated_at'] ?? now()))
    @php($generatedAt = $generatedAtRaw instanceof \DateTimeInterface ? $generatedAtRaw : \Illuminate\Support\Carbon::parse($generatedAtRaw))
    <div class="footer">Generated: {{ $generatedAt->format('d M Y H:i') }}</div>

    @php($lecturersSorted = collect($data['lecturers'] ?? [])->sortBy(fn ($l) => mb_strtolower($l['name'] ?? ''))->values())
    @forelse($lecturersSorted as $index => $lct)
        <div class="lecturer">
            <h3>{{ $lct['name'] ?? '-' }}</h3>
            <table class="lecturer-info">
                <tr>
                    <td class="label">IC</td>
                    <td>{{ $lct['ic'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Staff No.</td>
                    <td>{{ $lct['no_staf'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td>{{ $lct['email'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Latest Published</td>
                    <td>
                        @if(!empty($lct['latest_date']))
                            {{ date('d M Y', strtotime($lct['latest_date'])) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>

            @php($grid = $lct['grid'] ?? null)

            @if(empty($grid) || empty($grid['times']) || empty($grid['days']))
                <p class="small">No logged timetable found for this lecturer.</p>
            @else
                <table class="timetable">
                    <thead>
                        <tr>
                            <th class="time-col">Time</th>
                            @foreach(($grid['days'] ?? []) as $dayIso => $dayName)
                                <th>{{ $dayName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($grid['times'] ?? []) as $timeIndex => $timeLabel)
                            <tr>
                                <td class="time-col">{{ $timeLabel }}</td>
                                @foreach(array_keys(($grid['days'] ?? [])) as $dayIso)
                                    @php($cell = $grid['cells'][$timeIndex][$dayIso] ?? ['skip' => false, 'rowspan' => 1, 'event' => null])
                                    @if(!empty($cell['skip']))
                                        @continue
                                    @endif

                                    <td class="slot" @if(!empty($cell['rowspan']) && ($cell['rowspan'] ?? 1) > 1) rowspan="{{ $cell['rowspan'] }}" @endif>
                                        @if(!empty($cell['event']))
                                            @php($ev = $cell['event'])
                                            <div class="event">
                                                <div class="time">{{ $ev['start'] ?? '' }} - {{ $ev['end'] ?? '' }}</div>
                                                <div class="title">{{ $ev['title'] ?? '' }}</div>
                                                <div class="desc">
                                                    {{ $ev['programs'] ?? '' }} ({{ $ev['session'] ?? '' }})<br>
                                                    {{ $ev['room'] ?? '' }} | Total Student: {{ $ev['total_student'] ?? 0 }}
                                                </div>
                                                @if(!empty($ev['programs']))
                                                    <div class="programs">Programs: {{ $ev['programs'] }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <p>No lecturer log timetable found.</p>
    @endforelse
</body>
</html>

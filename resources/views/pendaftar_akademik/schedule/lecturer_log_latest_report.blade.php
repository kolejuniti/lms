@extends('layouts.pendaftar_akademik')

@section('main')

<style>
    /* Match Log Timetable (schedule3) sizing */
    .fc-timegrid-slot {
        height: 60px !important;
    }
    .fc-timegrid-event {
        min-height: 1px !important;
    }
    .fc-timegrid-slot-label .fc-timegrid-slot-label-cushion {
        height: 60px !important;
        line-height: 30px !important;
        display: flex;
        align-items: start;
    }
    .fc-event .program-info {
        text-align: center;
        font-size: smaller;
        font-weight: bold;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Schedule</li>
                                <li class="breadcrumb-item active" aria-current="page">Lecturer Latest Timetable</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-10">
                        <h4 class="mb-0 fw-500">Latest Timetable</h4>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <form method="GET" action="{{ route('pendaftar_akademik.schedule.log.latestLecturer') }}" class="m-0">
                                <select name="faculty" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="all" @selected(($data['selected_faculty'] ?? 'all') === 'all')>All Faculties</option>
                                    @foreach(($data['faculties'] ?? []) as $fcl)
                                        <option value="{{ $fcl->id }}" @selected((string)($data['selected_faculty'] ?? 'all') === (string)$fcl->id)>{{ $fcl->facultyname }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <a class="btn btn-sm btn-danger" href="{{ route('pendaftar_akademik.schedule.log.latestLecturer.pdf', ['faculty' => ($data['selected_faculty'] ?? 'all')]) }}" target="_blank">
                                Export PDF
                            </a>
                        </div>
                    </div>

                    @php($rowsSorted = collect($data['rows'] ?? [])->sortBy(fn ($r) => mb_strtolower($r->name ?? ''))->values())
                    @forelse($rowsSorted as $i => $row)
                        <div class="box">
                            <div class="box-body">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h4 class="box-title mb-0 fw-500">{{ $row->name }}</h4>
                                        <div class="text-muted">
                                            <div><strong>IC:</strong> {{ $row->ic }}</div>
                                            <div><strong>Staff No.:</strong> {{ $row->no_staf }}</div>
                                            <div><strong>Email:</strong> {{ $row->email }}</div>
                                            <div>
                                                <strong>Latest Published:</strong>
                                                @if(!empty($row->latest_date))
                                                    {{ date('d M Y', strtotime($row->latest_date)) }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        {{-- Single view not used for this report --}}
                                    </div>
                                </div>

                                <hr>
                                <div id="calendar-{{ preg_replace('/[^A-Za-z0-9_\\-]/', '_', $row->ic) }}" style="width: 100%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="box">
                            <div class="box-body text-center">No lecturer log timetable found.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>

<!-- FullCalendar CSS/JS -->
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.css' />
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.js'></script>

<script>
    function getRandomColor() {
        const colors = ['blue', 'green', 'purple', 'orange', 'pink', 'cyan', 'magenta', '#34ebc9', '#eb34df'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    document.addEventListener('DOMContentLoaded', function () {
        const lecturers = @json($rowsSorted);
        const hiddenDays = [0, 6];

        lecturers.forEach((lct) => {
            const safeId = String(lct.ic).replace(/[^A-Za-z0-9_\-]/g, '_');
            const el = document.getElementById('calendar-' + safeId);
            if (!el) return;

            const latestDate = lct.latest_date;
            const ic = lct.ic;

            const calendar = new FullCalendar.Calendar(el, {
                initialView: 'timeGridWeek',
                headerToolbar: { left: '', center: 'title', right: 'timeGridWeek,timeGridDay' },
                hiddenDays: hiddenDays,
                slotMinTime: '08:30:00',
                slotMaxTime: '18:00:00',
                slotDuration: '00:30:00',
                slotLabelInterval: '00:30:00',
                height: 'auto',
                aspectRatio: 1.35,
                allDaySlot: false,
                eventDidMount: function(info) {
                    if (info.event.title) {
                        const programInfo = info.event.extendedProps && info.event.extendedProps.programInfo
                            ? info.event.extendedProps.programInfo
                            : '';
                        if (programInfo) {
                            const programDiv = document.createElement('div');
                            programDiv.classList.add('program-info');
                            programDiv.style.position = 'absolute';
                            programDiv.style.bottom = '0';
                            programDiv.style.width = '100%';
                            programDiv.style.padding = '5px';
                            programDiv.textContent = 'Programs: ' + programInfo;
                            info.el.appendChild(programDiv);
                        }
                    }
                },
                eventContent: function(arg) {
                    const titleElement = document.createElement('div');
                    titleElement.classList.add('event-title');
                    titleElement.style.fontWeight = 'bold';
                    titleElement.textContent = arg.event.title || '';

                    const timeElement = document.createElement('div');
                    timeElement.classList.add('event-time');
                    timeElement.textContent = arg.timeText || '';

                    const arrayOfDomNodes = [timeElement, titleElement];

                    const desc = arg.event.extendedProps ? arg.event.extendedProps.description : null;
                    if (desc) {
                        const descriptionElement = document.createElement('div');
                        descriptionElement.classList.add('event-description');
                        descriptionElement.style.fontSize = '0.7rem';
                        descriptionElement.style.opacity = '0.8';
                        descriptionElement.style.whiteSpace = 'normal';
                        descriptionElement.style.overflow = 'visible';
                        descriptionElement.style.lineHeight = '1.3';
                        descriptionElement.innerHTML = desc;
                        arrayOfDomNodes.push(descriptionElement);
                    }

                    return { domNodes: arrayOfDomNodes };
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch(`/AR/schedule/log/${encodeURIComponent(ic)}/fetch?idS=${encodeURIComponent(latestDate || '')}`)
                        .then(response => response.json())
                        .then(data => {
                            const coloredEvents = (data || []).map(event => ({
                                ...event,
                                color: getRandomColor()
                            }));
                            successCallback(coloredEvents);
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                        });
                }
            });

            calendar.render();
        });
    });
</script>

@endsection

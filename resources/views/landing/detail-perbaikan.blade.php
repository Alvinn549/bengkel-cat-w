<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ asset('assets/dashboard/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/landing/css/detail-perbaikan.css') }}" rel="stylesheet">

    <title>Detail Perbaikan</title>
</head>

<body>
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Detail Perbaikan</h5>
                        <hr>
                        <center>
                            @if ($perbaikan->foto)
                                <img src="{{ asset('storage/' . $perbaikan->foto) }}" class="img-fluid rounded"
                                    alt="">
                            @else
                                <img src="{{ asset('assets/dashboard/img/repair.png') }}" alt="Default"
                                    class="col-md-6 img-fluid">
                            @endif
                        </center>
                        <table class="table mt-3">
                            <tr>
                                <th>Kode</th>
                                <td>
                                    <span class="badge bg-secondary" style="font-size: 1rem;">
                                        {{ $perbaikan->kode_unik ?? '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Perbaikan</th>
                                <td>{{ $perbaikan->nama ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $perbaikan->keterangan ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Biaya</th>
                                <td>Rp. {{ number_format($perbaikan->biaya) ?? '-' }}</td>
                            </tr>
                            <tr>
                                @php
                                    if ($perbaikan->durasi) {
                                        $durations = explode(' to ', $perbaikan->durasi);
                                        $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $durations[0]);
                                        $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $durations[1]);

                                        $days = $startDate->diffInDays($endDate);
                                    }
                                @endphp
                                <th>Durasi</th>
                                <td>
                                    @if ($perbaikan->durasi)
                                        {{ $days ?? '-' }} Hari <br> {{ $perbaikan->durasi ?? '-' }}
                                    @else
                                        {{ $perbaikan->durasi ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Masuk</th>
                                <td>{{ $perbaikan->created_at ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Selesai</th>
                                <td>{{ $perbaikan->tgl_selesai ?? '-' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $badge_bg = null;

                                    if ($perbaikan->status == 'Selesai') {
                                        $badge_bg = 'bg-success';
                                    } elseif ($perbaikan->status == 'Dalam Proses') {
                                        $badge_bg = 'bg-info';
                                    } elseif ($perbaikan->status == 'Ditunda') {
                                        $badge_bg = 'bg-secondary';
                                    } elseif ($perbaikan->status == 'Dibatalkan') {
                                        $badge_bg = 'bg-warning';
                                    } elseif ($perbaikan->status == 'Tidak Dapat Diperbaiki') {
                                        $badge_bg = 'bg-danger';
                                    }
                                @endphp
                                <th>Status</th>
                                <td><span class="badge {{ $badge_bg }}"
                                        style="font-size: 1rem;">{{ $perbaikan->status ?? '-' }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-body">
                        <svg display="none">
                            <symbol id="arrow">
                                <polyline points="7 10,12 15,17 10" fill="none" stroke="currentcolor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                            </symbol>
                        </svg>
                        <h5 class="card-title">Progres Perbaikan</h5>
                        <hr>

                        @if ($perbaikan->progres->count() > 0)
                            <div id="timeline" class="timeline">
                                <div class="btn-group">
                                    <button class="btn" type="button" data-action="expand">Expand All</button>
                                    <button class="btn" type="button" data-action="collapse">Collapse All</button>
                                </div>
                                @foreach ($perbaikan->progres->sortByDesc('id') as $progres)
                                    @php
                                        $date = \Carbon\Carbon::parse($progres->created_at)->locale('id');
                                        $formattedDate = $date->format('d-m-Y');
                                        $formattedTime = $date->format('H:i');
                                        $diffForHumans = $date->diffForHumans();
                                    @endphp
                                    <div class="timeline__item">
                                        <div class="timeline__item-header">
                                            <button class="timeline__arrow" type="button" id="item{{ $progres->id }}"
                                                aria-labelledby="item{{ $progres->id }}-name" aria-expanded="false"
                                                aria-controls="item{{ $progres->id }}-ctrld" aria-haspopup="true"
                                                data-item="{{ $progres->id }}">
                                                <svg class="timeline__arrow-icon" viewBox="0 0 24 24" width="24px"
                                                    height="24px">
                                                    <use href="#arrow" />
                                                </svg>
                                            </button>
                                            <span class="timeline__dot"></span>
                                            <span id="item1-name" class="timeline__meta">
                                                <time class="timeline__date"
                                                    datetime="{{ $progres->created_at->format('Y-m-d') }}">
                                                    {{ $diffForHumans }} <br>
                                                    {{ $formattedDate . ' ' . $formattedTime ?? '-' }} <br>
                                                    Oleh : {{ $progres->pekerja->nama ?? '-' }}
                                                </time>
                                            </span>
                                        </div>
                                        <div class="timeline__item-body" id="item{{ $progres->id }}-ctrld"
                                            role="region" aria-labelledby="item{{ $progres->id }}"
                                            aria-hidden="true">
                                            <div class="timeline__item-body-content">
                                                <p class="timeline__item-p">{{ $progres->keterangan ?? '-' }}</p>

                                                @if ($progres->foto)
                                                    <img src="{{ asset('storage/' . $progres->foto) }}"
                                                        class="img-fluid rounded" alt="">
                                                @else
                                                    <img src="{{ asset('assets/dashboard/img/spray-gun.png') }}"
                                                        class="img-fluid rounded" alt="">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <center>
                                <img src="{{ asset('assets/landing/img/business.png') }}" class="img-fluid rounded"
                                    alt="">
                            </center>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const ctl = new CollapsibleTimeline("#timeline");
        });

        class CollapsibleTimeline {
            constructor(el) {
                this.el = document.querySelector(el);

                this.init();
            }
            init() {
                this.el?.addEventListener("click", this.itemAction.bind(this));
            }
            animateItemAction(button, ctrld, contentHeight, shouldCollapse) {
                const expandedClass = "timeline__item-body--expanded";
                const animOptions = {
                    duration: 300,
                    easing: "cubic-bezier(0.65,0,0.35,1)"
                };

                if (shouldCollapse) {
                    button.ariaExpanded = "false";
                    ctrld.ariaHidden = "true";
                    ctrld.classList.remove(expandedClass);
                    animOptions.duration *= 2;
                    this.animation = ctrld.animate([{
                            height: `${contentHeight}px`
                        },
                        {
                            height: `${contentHeight}px`
                        },
                        {
                            height: "0px"
                        }
                    ], animOptions);
                } else {
                    button.ariaExpanded = "true";
                    ctrld.ariaHidden = "false";
                    ctrld.classList.add(expandedClass);
                    this.animation = ctrld.animate([{
                            height: "0px"
                        },
                        {
                            height: `${contentHeight}px`
                        }
                    ], animOptions);
                }
            }
            itemAction(e) {
                const {
                    target
                } = e;
                const action = target?.getAttribute("data-action");
                const item = target?.getAttribute("data-item");

                if (action) {
                    const targetExpanded = action === "expand" ? "false" : "true";
                    const buttons = Array.from(this.el?.querySelectorAll(`[aria-expanded="${targetExpanded}"]`));
                    const wasExpanded = action === "collapse";

                    for (let button of buttons) {
                        const buttonID = button.getAttribute("data-item");
                        const ctrld = this.el?.querySelector(`#item${buttonID}-ctrld`);
                        const contentHeight = ctrld.firstElementChild?.offsetHeight;

                        this.animateItemAction(button, ctrld, contentHeight, wasExpanded);
                    }

                } else if (item) {
                    const button = this.el?.querySelector(`[data-item="${item}"]`);
                    const expanded = button?.getAttribute("aria-expanded");

                    if (!expanded) return;

                    const wasExpanded = expanded === "true";
                    const ctrld = this.el?.querySelector(`#item${item}-ctrld`);
                    const contentHeight = ctrld.firstElementChild?.offsetHeight;

                    this.animateItemAction(button, ctrld, contentHeight, wasExpanded);
                }
            }
        }
    </script>

    <script src="{{ asset('assets/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</html>

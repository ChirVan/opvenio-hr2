<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Promotion Offers - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f5f7f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-header {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        .card {
            border: none;
            border-radius: 14px;
            transition: all 0.25s ease;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        }
        .offer-card {
            border-left: 4px solid #f59e0b;
            position: relative;
            overflow: hidden;
        }
        .offer-card.pending {
            border-left-color: #f59e0b;
        }
        .offer-card.accepted {
            border-left-color: #10b981;
        }
        .offer-card.declined {
            border-left-color: #ef4444;
        }
        .offer-card.promoted {
            border-left-color: #6366f1;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .status-declined {
            background: #fee2e2;
            color: #991b1b;
        }
        .status-promoted {
            background: #e0e7ff;
            color: #3730a3;
        }
        .btn-accept {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 24px;
        }
        .btn-accept:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .btn-decline {
            background: transparent;
            border: 2px solid #ef4444;
            color: #ef4444;
            font-weight: 600;
            border-radius: 10px;
            padding: 8px 24px;
        }
        .btn-decline:hover {
            background: #ef4444;
            color: white;
        }
        .arrow-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ecfdf5;
            color: #059669;
            font-size: 1.1rem;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container" style="margin-top: 84px; padding-bottom: 40px;">
        <!-- Header -->
        <div class="page-header mb-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bx bx-rocket" style="font-size: 2rem;"></i>
                <div>
                    <h4 class="fw-bold mb-0">Promotion Offers</h4>
                    <small style="opacity: 0.85;">Review and respond to your promotion opportunities</small>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($offers->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <i class="bx bx-briefcase-alt"></i>
                    <h5 class="fw-bold text-muted">No Promotion Offers</h5>
                    <p class="text-muted" style="font-size: 0.9rem;">You don't have any promotion offers at the moment. Check back later!</p>
                    <a href="{{ route('ess.dashboard') }}" class="btn btn-outline-success btn-sm rounded-3">
                        <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        @else
            @foreach($offers as $offer)
                @php
                    $isPending = $offer->status === 'pending_acceptance';
                    $isAccepted = ($offer->status === 'approved' && ($offer->employee_response ?? null) === 'accepted');
                    $isDeclined = ($offer->employee_response ?? null) === 'declined';
                    $isPromoted = $offer->status === 'promoted';

                    if ($isPending) $cardClass = 'pending';
                    elseif ($isPromoted) $cardClass = 'promoted';
                    elseif ($isAccepted) $cardClass = 'accepted';
                    elseif ($isDeclined) $cardClass = 'declined';
                    else $cardClass = 'accepted';
                @endphp
                <div class="card offer-card {{ $cardClass }} mb-3">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Offer Info -->
                            <div class="col-md-7">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($isPending)
                                        <span class="status-badge status-pending"><i class="bx bx-time-five"></i> Awaiting Your Response</span>
                                    @elseif($isPromoted)
                                        <span class="status-badge status-promoted"><i class="bx bx-check-double"></i> Promoted</span>
                                    @elseif($isAccepted)
                                        <span class="status-badge status-approved"><i class="bx bx-check-circle"></i> Accepted</span>
                                    @elseif($isDeclined)
                                        <span class="status-badge status-declined"><i class="bx bx-x-circle"></i> Declined</span>
                                    @else
                                        <span class="status-badge status-approved"><i class="bx bx-check"></i> {{ ucfirst($offer->status) }}</span>
                                    @endif
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($offer->created_at)->format('M d, Y') }}</small>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Current Position</small>
                                        <span class="fw-bold" style="font-size: 0.95rem;">{{ $offer->job_title }}</span>
                                    </div>
                                    <span class="arrow-icon">
                                        <i class="bx bx-right-arrow-alt"></i>
                                    </span>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Proposed Position</small>
                                        <span class="fw-bold text-success" style="font-size: 0.95rem;">{{ $offer->potential_job }}</span>
                                    </div>
                                </div>

                                @if($offer->assessment_score)
                                    <small class="text-muted">
                                        <i class="bx bx-bar-chart-alt-2 text-primary"></i>
                                        Assessment Score: <strong>{{ $offer->assessment_score }}</strong>
                                    </small>
                                @endif

                                @if($offer->employee_response_note ?? null)
                                    <div class="mt-2 p-2 rounded-3" style="background: #f9fafb; font-size: 0.85rem;">
                                        <i class="bx bx-message-rounded-detail text-muted me-1"></i>
                                        <em>{{ $offer->employee_response_note }}</em>
                                    </div>
                                @endif

                                @if($offer->employee_responded_at ?? null)
                                    <div class="mt-1">
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            Responded on {{ \Carbon\Carbon::parse($offer->employee_responded_at)->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                @if($isPending)
                                    <div class="d-flex gap-2 justify-content-md-end">
                                        <button class="btn btn-accept" onclick="respondToOffer({{ $offer->id }}, 'accepted', '{{ addslashes($offer->potential_job) }}')">
                                            <i class="bx bx-check me-1"></i> Accept Promotion
                                        </button>
                                        <button class="btn btn-decline" onclick="respondToOffer({{ $offer->id }}, 'declined', '{{ addslashes($offer->potential_job) }}')">
                                            <i class="bx bx-x me-1"></i> Decline
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                                        <i class="bx bx-info-circle"></i> Please review carefully before responding
                                    </small>
                                @elseif($isAccepted)
                                    <div class="d-flex align-items-center justify-content-md-end gap-2">
                                        <i class="bx bx-check-circle text-success" style="font-size: 1.5rem;"></i>
                                        <span class="fw-bold text-success" style="font-size: 0.9rem;">You accepted this offer</span>
                                    </div>
                                @elseif($isDeclined)
                                    <div class="d-flex align-items-center justify-content-md-end gap-2">
                                        <i class="bx bx-x-circle text-danger" style="font-size: 1.5rem;"></i>
                                        <span class="fw-bold text-danger" style="font-size: 0.9rem;">You declined this offer</span>
                                    </div>
                                @elseif($isPromoted)
                                    <div class="d-flex align-items-center justify-content-md-end gap-2">
                                        <i class="bx bx-trophy text-primary" style="font-size: 1.5rem;"></i>
                                        <span class="fw-bold text-primary" style="font-size: 0.9rem;">Promotion Completed!</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function respondToOffer(offerId, response, jobTitle) {
            const isAccept = response === 'accepted';

            Swal.fire({
                title: isAccept ? 'Accept Promotion?' : 'Decline Promotion?',
                html: isAccept
                    ? `<p class="mb-3">You are about to accept the promotion to <strong>${jobTitle}</strong>.</p>
                       <div class="text-start">
                           <label class="form-label fw-bold" style="font-size:0.85rem;">Add a note (optional):</label>
                           <textarea id="swal-note" class="form-control" rows="2" placeholder="e.g. Thank you for this opportunity..." style="border-radius:10px;font-size:0.85rem;"></textarea>
                       </div>`
                    : `<p class="mb-3">Are you sure you want to decline the promotion to <strong>${jobTitle}</strong>?</p>
                       <div class="text-start">
                           <label class="form-label fw-bold" style="font-size:0.85rem;">Reason (optional):</label>
                           <textarea id="swal-note" class="form-control" rows="2" placeholder="e.g. Personal reasons..." style="border-radius:10px;font-size:0.85rem;"></textarea>
                       </div>`,
                icon: isAccept ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: isAccept ? '<i class="bx bx-check"></i> Yes, Accept' : '<i class="bx bx-x"></i> Yes, Decline',
                confirmButtonColor: isAccept ? '#10b981' : '#ef4444',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    const note = document.getElementById('swal-note')?.value || '';
                    try {
                        const res = await fetch(`/ess/promotion-offers/${offerId}/respond`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ response: response, note: note })
                        });
                        const data = await res.json();
                        if (!data.success) throw new Error(data.message || 'Something went wrong');
                        return data;
                    } catch (err) {
                        Swal.showValidationMessage(err.message);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Swal.fire({
                        title: isAccept ? 'Promotion Accepted!' : 'Offer Declined',
                        text: result.value.message,
                        icon: isAccept ? 'success' : 'info',
                        confirmButtonColor: '#198754',
                        confirmButtonText: 'OK'
                    }).then(() => location.reload());
                }
            });
        }
    </script>
</body>
</html>

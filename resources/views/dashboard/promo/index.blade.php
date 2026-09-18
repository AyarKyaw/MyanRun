@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.promo_codes.index') }}">Promo Codes</a></li>
                    <li class="breadcrumb-item active">Groups</li>
                </ol>
            </nav>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h4 class="card-title mb-0">Promo Code Groups</h4>
            <a href="{{ route('admin.promo_codes.create') }}" class="btn btn-primary btn-sm">+ Generate Promo Group</a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.promo_codes.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by Group Name or Code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.promo_codes.index') }}" class="btn btn-light">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Group Accordions -->
        <div class="accordion" id="promoGroupsAccordion">
            @forelse($groupedPromoCodes as $groupName => $codes)
                @php
                    $groupId = 'group_' . Str::slug($groupName);
                    $totalCount = $codes->count();
                    $usedCount = $codes->where('is_used', true)->count();
                    $availableCount = $totalCount - $usedCount;
                    $firstCode = $codes->first();

                    // Sort codes so 'is_used' comes first
                    $sortedCodes = $codes->sortByDesc('is_used');
                @endphp

                <div class="card mb-3 border">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 flex-wrap gap-2" id="heading_{{ $groupId }}">
                        <div>
                            <h5 class="mb-1 text-primary fw-bold">{{ $groupName }}</h5>
                            <small class="text-muted">
                                Event: <strong>{{ $firstCode->event->name ?? 'N/A' }}</strong> |
                                Discount: <strong>{{ number_format($firstCode->amount) }} MMK</strong>
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge border border-dark text-dark">{{ $totalCount }} Total</span>
                            <span class="badge bg-success">{{ $availableCount }} Available</span>
                            <span class="badge bg-danger">{{ $usedCount }} Used</span>
                            
                            <!-- Export Group CSV Button -->
                            <a href="{{ route('admin.promo_codes.export_group', ['group_name' => $groupName]) }}" class="btn btn-sm btn-outline-success">
                                <i class="fa fa-download me-1"></i> Export CSV
                            </a>

                            <!-- Delete Group Modal Trigger -->
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmGroupDelete('{{ addslashes($groupName) }}')">
                                Delete Group
                            </button>

                            <!-- Accordion Toggle Button -->
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $groupId }}" aria-expanded="false" aria-controls="collapse_{{ $groupId }}">
                                View Codes
                            </button>
                        </div>
                    </div>

                    <div id="collapse_{{ $groupId }}" class="collapse" aria-labelledby="heading_{{ $groupId }}" data-bs-parent="#promoGroupsAccordion">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped text-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Ticket Category</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sortedCodes as $index => $promo)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <span class="badge border border-dark text-dark font-monospace fs-6">{{ $promo->code }}</span>
                                                        <button type="button" 
                                                                class="btn btn-xs btn-outline-secondary copy-btn" 
                                                                onclick="copyToClipboard('{{ $promo->code }}', this)" 
                                                                title="Copy Code">
                                                            <i class="fa fa-copy"></i> Copy
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($promo->eventTicketTypes->isNotEmpty())
                                                        @foreach($promo->eventTicketTypes as $ticketType)
                                                            <span class="badge bg-info text-white me-1">{{ $ticketType->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-secondary">All Categories</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($promo->is_used)
                                                        <span class="badge bg-danger">Used</span>
                                                    @else
                                                        <span class="badge bg-success">Available</span>
                                                    @endif
                                                </td>
                                                <td>{{ $promo->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @if(!$promo->is_used)
                                                        <!-- Delete Single Code Modal Trigger -->
                                                        <button type="button" 
                                                                class="btn btn-danger btn-xs shadow" 
                                                                onclick="confirmCodeDelete('{{ $promo->id }}', '{{ $promo->code }}')">
                                                            Delete
                                                        </button>
                                                    @else
                                                        <span class="text-muted fs-7">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        No promo code groups found.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal: Single Code Delete Confirmation -->
<div class="modal fade" id="deleteCodeModal" tabindex="-1" aria-labelledby="deleteCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="MyanRun Logo" style="height: 28px; width: auto;" onerror="this.style.display='none';">
                <h5 class="modal-title mb-0" id="deleteCodeModalLabel">Delete Promo Code</h5>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="MyanRun" class="img-fluid" style="max-height: 48px;" onerror="this.style.display='none';">
                </div>
                <p class="mb-0 fs-6">Are you sure you want to delete promo code <strong id="deleteCodeTarget" class="text-danger font-monospace"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCodeForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Code</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Group Delete Confirmation -->
<div class="modal fade" id="deleteGroupModal" tabindex="-1" aria-labelledby="deleteGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteGroupForm" method="POST" action="{{ route('admin.promo_codes.destroy_group') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="group_name" id="deleteGroupNameInput" value="">

                <div class="modal-header d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="MyanRun Logo" style="height: 28px; width: auto;" onerror="this.style.display='none';">
                    <h5 class="modal-title text-danger mb-0" id="deleteGroupModalLabel">Delete Entire Promo Group</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-4">
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="MyanRun" class="img-fluid" style="max-height: 48px;" onerror="this.style.display='none';">
                    </div>
                    
                    <p class="text-center mb-1 fs-6">Are you sure you want to delete ALL promo codes in group <strong id="deleteGroupTarget" class="text-danger"></strong>?</p>
                    <p class="text-center text-muted small mb-3">This action cannot be undone.</p>

                    <div class="mb-3">
                        <label for="adminPassword" class="form-label fw-bold">Admin Password</label>
                        <input type="password" name="password" id="adminPassword" class="form-control" placeholder="Enter your password to confirm" required>
                        <div id="passwordError" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm & Delete Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Deletion Success Notice -->
<div class="modal fade" id="deleteSuccessModal" tabindex="-1" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center py-3">
            <div class="modal-body">
                <div class="mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="MyanRun" class="img-fluid" style="max-height: 55px;" onerror="this.style.display='none';">
                </div>
                <div class="mb-2">
                    <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold text-dark">Group Deleted Successfully!</h4>
                <p class="text-muted mb-4" id="deletedSuccessMessage"></p>
                <button type="button" class="btn btn-primary px-4" onclick="window.location.reload();">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check text-success"></i> Copied!';
        btn.classList.replace('btn-outline-secondary', 'btn-outline-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.replace('btn-outline-success', 'btn-outline-secondary');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy code: ', err);
    });
}

function confirmCodeDelete(id, code) {
    const deleteForm = document.getElementById('deleteCodeForm');
    deleteForm.action = `/admin/promo-codes/${id}`;
    document.getElementById('deleteCodeTarget').textContent = code;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteCodeModal'));
    modal.show();
}

function confirmGroupDelete(groupName) {
    document.getElementById('deleteGroupNameInput').value = groupName;
    document.getElementById('deleteGroupTarget').textContent = groupName;
    document.getElementById('adminPassword').value = '';
    
    const passwordError = document.getElementById('passwordError');
    if (passwordError) passwordError.textContent = '';
    
    const passwordInput = document.getElementById('adminPassword');
    if (passwordInput) passwordInput.classList.remove('is-invalid');
    
    const modal = new bootstrap.Modal(document.getElementById('deleteGroupModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->has('password'))
        const modal = new bootstrap.Modal(document.getElementById('deleteGroupModal'));
        modal.show();
    @endif

    // Handle Group Deletion AJAX Submit
    const deleteGroupForm = document.getElementById('deleteGroupForm');
    if (deleteGroupForm) {
        deleteGroupForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const passwordInput = document.getElementById('adminPassword');
            const passwordError = document.getElementById('passwordError');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Deleting...';
            if (passwordInput) passwordInput.classList.remove('is-invalid');
            if (passwordError) passwordError.textContent = '';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'DELETE',
                    group_name: document.getElementById('deleteGroupNameInput').value,
                    password: passwordInput ? passwordInput.value : ''
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw data;
                }
                return data;
            })
            .then(data => {
                // Hide password modal
                const groupModalEl = document.getElementById('deleteGroupModal');
                const groupModal = bootstrap.Modal.getInstance(groupModalEl);
                if (groupModal) groupModal.hide();

                // Populate and display success modal
                document.getElementById('deletedSuccessMessage').textContent = data.message;
                const successModal = new bootstrap.Modal(document.getElementById('deleteSuccessModal'));
                successModal.show();
            })
            .catch(err => {
                if (err.message && passwordInput && passwordError) {
                    passwordInput.classList.add('is-invalid');
                    passwordError.textContent = err.message;
                } else if (err.errors && err.errors.password && passwordInput && passwordError) {
                    passwordInput.classList.add('is-invalid');
                    passwordError.textContent = err.errors.password[0];
                } else {
                    alert('An unexpected error occurred. Please try again.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirm & Delete Group';
            });
        });
    }
});
</script>
@endsection
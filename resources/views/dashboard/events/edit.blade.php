@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('events.index', 'coming') }}">Events</a></li>
                    <li class="breadcrumb-item active">Edit Event</li>
                </ol>
            </nav>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Event Details</h4></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $event->name) }}" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Company / Organizer</label>
                                    <input type="text" name="company" class="form-control" value="{{ old('company', $event->company) }}" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Event Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '' }}" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">YouTube Video ID</label>
                                    <input type="text" name="video_url" class="form-control" value="{{ old('video_url', $event->video_url) }}">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Registration Limit</label>
                                    <select name="ticket_limit_type" id="ticket_limit_type" class="form-control default-select" onchange="toggleLimitInput()">
                                        <option value="unlimited" {{ is_null($event->total_max_slots) ? 'selected' : '' }}>Unlimited Tickets</option>
                                        <option value="limited" {{ !is_null($event->total_max_slots) ? 'selected' : '' }}>Limited Tickets</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6" id="limit_input_container" style="{{ is_null($event->total_max_slots) ? 'display: none;' : '' }}">
                                    <label class="form-label text-black fw-bold">Total Event Capacity</label>
                                    <input type="number" name="total_max_slots" class="form-control" 
                                        value="{{ old('total_max_slots', $event->total_max_slots) }}" 
                                        placeholder="Enter total slots (e.g. 500)">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-warning fw-bold">Global Early Bird Limit (Total Event)</label>
                                    <input type="number" name="early_bird_limit" class="form-control border-warning" 
                                        value="{{ old('early_bird_limit', $event->early_bird_limit) }}" 
                                        placeholder="e.g. 200 (First 200 runners get discount)">
                                    <small class="text-muted">This limit applies to the sum of all ticket types for this event.</small>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Description</label>
                                    <textarea name="description" class="form-control" rows="5">{{ old('description', $event->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label text-black fw-bold">Display Status</label>
                                <select name="is_active" class="form-control default-select">
                                    <option value="2" {{ $event->is_active == 2 ? 'selected' : '' }}>Coming Event</option>
                                    <option value="1" {{ $event->is_active == 1 ? 'selected' : '' }}>Now Event (Live)</option>
                                    <option value="0" {{ $event->is_active == 0 ? 'selected' : '' }}>Past Event (Archived)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-black fw-bold">Athlete Registration Editing</label>

                                <div class="border rounded p-3 bg-light">
                                    <div class="form-check form-switch mb-3">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="allow_athlete_edit"
                                            value="1"
                                            id="allow_athlete_edit"
                                            {{ old('allow_athlete_edit', $event->allow_athlete_edit) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label fw-bold" for="allow_athlete_edit">
                                            Allow athletes to edit registration
                                        </label>
                                    </div>

                                    <div id="athlete-edit-sections"
                                        class="{{ old('allow_athlete_edit', $event->allow_athlete_edit) ? '' : 'd-none' }}">

                                        <small class="text-muted d-block mb-2">
                                            Select which registration sections athletes can edit.
                                        </small>

                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input athlete-edit-section"
                                                type="checkbox"
                                                name="allow_edit_required"
                                                value="1"
                                                id="allow_edit_required"
                                                {{ old('allow_edit_required', $event->allow_edit_required) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="allow_edit_required">
                                                <strong>01</strong> — Required Information
                                            </label>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input athlete-edit-section"
                                                type="checkbox"
                                                name="allow_edit_personal"
                                                value="1"
                                                id="allow_edit_personal"
                                                {{ old('allow_edit_personal', $event->allow_edit_personal) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="allow_edit_personal">
                                                <strong>02</strong> — Personal Information
                                            </label>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input athlete-edit-section"
                                                type="checkbox"
                                                name="allow_edit_contact"
                                                value="1"
                                                id="allow_edit_contact"
                                                {{ old('allow_edit_contact', $event->allow_edit_contact) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="allow_edit_contact">
                                                <strong>03</strong> — Contact Information
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input
                                                class="form-check-input athlete-edit-section"
                                                type="checkbox"
                                                name="allow_edit_participant"
                                                value="1"
                                                id="allow_edit_participant"
                                                {{ old('allow_edit_participant', $event->allow_edit_participant) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="allow_edit_participant">
                                                <strong>04</strong> — Participant's Info
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-black fw-bold">Event Banner</label>
                                @if($event->image_path)
                                    <img src="{{ asset('storage/' . $event->image_path) }}" class="img-fluid rounded mb-2 shadow-sm" style="max-height: 150px; width: 100%; object-fit: cover;">
                                @endif
                                <input type="file" name="image" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-2">Update Event</button>
                            <a href="{{ route('events.index', 'coming') }}" class="btn btn-light w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
    <label class="form-label font-w600">Update Assigned Admins</label>
    <select name="admin_ids[]" class="form-control default-select" multiple>
        @foreach($eventAdmins as $admin)
            <option value="{{ $admin->id }}" 
                {{ $event->admins->contains($admin->id) ? 'selected' : '' }}>
                {{ $admin->email }}
            </option>
        @endforeach
    </select>
</div>
{{-- New Agent Selection --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label font-w600 text-warning">Assign Support Agents</label>
            <select name="agent_ids[]" class="form-control default-select" multiple>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" 
                        {{ $event->agents->contains($agent->id) ? 'selected' : '' }}>
                        {{ $agent->email }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Ticket Types</h4>
                    <button type="button" onclick="addTicket()" class="btn btn-sm btn-dark">+ Add New Ticket Type</button>
                </div>
                <div class="card-body">
                    <div id="ticket-types">
                        @foreach($event->ticketTypes as $index => $type)
                        <div class="card shadow-sm mb-4 p-3 ticket-row border border-light" id="ticket-row-{{ $type->id }}">
                            <input type="hidden" name="tickets[{{ $index }}][id]" value="{{ $type->id }}">
                            
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Name</label>
                                    <input type="text" name="tickets[{{ $index }}][name]" class="form-control" value="{{ $type->name }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-danger">Gender BIB?</label>
                                    <select name="tickets[{{ $index }}][has_gender_bib]" class="form-control">
                                        <option value="0" {{ !$type->has_gender_bib ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ $type->has_gender_bib ? 'selected' : '' }}>Yes (M/F Prefix)</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-primary">Ticket PNG (Template)</label>
                                    <input type="file" name="tickets[{{ $index }}][ticket_png]" class="form-control">
                                    @if($type->ticket_png)
                                        <small class="text-success d-block">✓ Template exists</small>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Prefix</label>
                                    <input type="text" name="tickets[{{ $index }}][prefix]" class="form-control" value="{{ $type->prefix }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Start No.</label>
                                    <input type="number" name="tickets[{{ $index }}][start_number]" class="form-control" value="{{ $type->start_number }}">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label small fw-bold">Type</label>
                                    <select name="tickets[{{ $index }}][type]" class="form-control">
                                        <option value="solo" {{ $type->type == 'solo' ? 'selected' : '' }}>Solo</option>
                                        <option value="relay" {{ $type->type == 'relay' ? 'selected' : '' }}>Relay</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">National (MMK)</label>
                                    <input type="number" name="tickets[{{ $index }}][national_price]" class="form-control" value="{{ $type->national_price }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Foreign (MMK)</label>
                                    <input type="number" name="tickets[{{ $index }}][foreign_price]" class="form-control" value="{{ $type->foreign_price }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-warning">Early Bird Limit</label>
                                    <input type="number" name="tickets[{{ $index }}][early_bird_limit]" class="form-control" value="{{ $type->early_bird_limit }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-warning">EB Discount</label>
                                    <input type="number" name="tickets[{{ $index }}][early_bird_discount]" class="form-control" value="{{ $type->early_bird_discount }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Slots</label>
                                    <input type="number" name="tickets[{{ $index }}][max_slots]" class="form-control" value="{{ $type->max_slots }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Category</label>
                                    <input type="text" name="tickets[{{ $index }}][category]" class="form-control" value="{{ $type->category }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">National Display Image (Front Page)</label>
                                    <input type="file" name="tickets[{{ $index }}][national_image]" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Foreign Display Image (Front Page)</label>
                                    <input type="file" name="tickets[{{ $index }}][foreign_image]" class="form-control">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let ticketIndex = {{ $event->ticketTypes->count() }};

document.addEventListener('DOMContentLoaded', function () {
    const masterToggle = document.getElementById('allow_athlete_edit');
    const sections = document.getElementById('athlete-edit-sections');
    const sectionCheckboxes = document.querySelectorAll('.athlete-edit-section');

    function updateAthleteEditSections() {
        if (masterToggle.checked) {
            sections.classList.remove('d-none');

            sectionCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = false;
            });
        } else {
            sections.classList.add('d-none');

            sectionCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = true;
                checkbox.checked = false;
            });
        }
    }

    masterToggle.addEventListener('change', updateAthleteEditSections);

    updateAthleteEditSections();
});

function addTicket() {
    const container = document.getElementById('ticket-types');
    const html = `
        <div class="card shadow-sm mb-4 p-3 ticket-row border border-primary bg-light">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label small fw-bold">Name</label><input type="text" name="tickets[${ticketIndex}][name]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold text-danger">Gender BIB?</label><select name="tickets[${ticketIndex}][has_gender_bib]" class="form-control"><option value="0">No</option><option value="1">Yes</option></select></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Ticket PNG</label><input type="file" name="tickets[${ticketIndex}][ticket_png]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Prefix</label><input type="text" name="tickets[${ticketIndex}][prefix]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Start No.</label><input type="number" name="tickets[${ticketIndex}][start_number]" class="form-control" value="1"></div>
                <div class="col-md-1"><label class="form-label small fw-bold">Type</label><select name="tickets[${ticketIndex}][type]" class="form-control"><option value="solo">Solo</option><option value="relay">Relay</option></select></div>
                
                <div class="col-md-2"><label class="form-label small fw-bold">National MMK</label><input type="number" name="tickets[${ticketIndex}][national_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Foreign MMK</label><input type="number" name="tickets[${ticketIndex}][foreign_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold text-warning">EB Limit</label><input type="number" name="tickets[${ticketIndex}][early_bird_limit]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold text-warning">EB Discount</label><input type="number" name="tickets[${ticketIndex}][early_bird_discount]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Slots</label><input type="number" name="tickets[${ticketIndex}][max_slots]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Category</label><input type="text" name="tickets[${ticketIndex}][category]" class="form-control"></div>
                
                <div class="col-md-6"><label class="form-label small fw-bold">National Display Image</label><input type="file" name="tickets[${ticketIndex}][national_image]" class="form-control"></div>
                <div class="col-md-6"><label class="form-label small fw-bold">Foreign Display Image</label><input type="file" name="tickets[${ticketIndex}][foreign_image]" class="form-control"></div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    ticketIndex++;
}

function toggleLimitInput() {
    const type = document.getElementById('ticket_limit_type').value;
    const container = document.getElementById('limit_input_container');
    
    if (type === 'limited') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        const input = container.querySelector('input');
        if(input) input.value = '';
    }
}
</script>
@endsection
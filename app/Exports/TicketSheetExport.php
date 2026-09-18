<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketSheetExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $category;
    protected $status;
    protected $printStatus; // 1. Added property
    protected $gender;
    protected $title;
    protected $eventId;

    // 2. Updated Constructor to accept printStatus
    public function __construct($category, $status, $printStatus, $gender, $title, $eventId = null)
    {
        $this->category = $category;
        $this->status = $status;
        $this->printStatus = $printStatus;
        $this->gender = $gender;
        $this->title = $title;
        $this->eventId = $eventId;
    }

    public function title(): string { return $this->title; }

    public function headings(): array
    {
        return ['Ticket ID', 'Email', 'Full Name','First Name','Middle Name','Last Name', 'BIB Name', 'BIB Number', 'ID No.', 'Phone no.', "Date of Birth", 'Gender', 'Category', 'T-Shirt size', 'Blood Type', 'Price', 'Status', 'Is Printed', 'Purchase Date', 'Division', 'Nationality', 'Address', 'ITRA'];
    }

    public function collection()
    {
        $query = Ticket::query()->with(['athlete.user']);

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        if ($this->category !== 'all') {
            $query->where('category', 'LIKE', '%' . $this->category . '%');
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // 3. NEW: Filter by Print Status
        if ($this->printStatus === 'printed') {
            $query->where('is_printed', true);
        } elseif ($this->printStatus === 'not_printed') {
            $query->where('is_printed', false);
        }

        if ($this->gender !== 'all') {
            $query->whereHas('athlete', function($q) {
                $q->where('gender', $this->gender);
            });
        }

        return $query->orderBy('created_at', 'asc')->get()->map(function($t) {
            $athlete = $t->athlete;
            $user = $athlete?->user;
            
            $fullName = $user ? trim("{$user->first_name} {$user->middle_name} {$user->last_name}") : 'Guest';

            return [
                str_pad($t->id, 5, '0', STR_PAD_LEFT),
                $user?->email ?? 'N/A',
                $fullName,
                $athlete?->first_name ?? 'N/A',
                $user?->middle_name ?? 'N/A',
                $athlete?->last_name ?? 'N/A',
                $t->bib_name ?? 'N/A',
                $t->bib_number ?? '0000',
                $athlete?->id_number ?? 'N/A',
                $user?->phone ?? 'N/A',
                $athlete?->dob ?? 'N/A',
                ucfirst($athlete?->gender ?? 'N/A'),
                $t->category ?? 'N/A',
                $t->t_shirt_size ?? 'N/A',
                $athlete?->blood_type ?? 'N/A',
                number_format((float)$t->price) . ' MMK',
                ucfirst($t->status),
                $t->is_printed ? 'Printed' : 'Not Printed', // Export column for clarity
                $t->created_at ? $t->created_at->format('d/m/Y H:i') : 'N/A',
                $athlete?->state ?? 'N/A',
                $athlete?->nationality ?? 'N/A',
                $athlete?->address ?? 'N/A',
                $athlete?->itra_details ?? 'N/A',
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KalenderController extends Controller
{
    public function index(Request $request) {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        $agendas = Agenda::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with(['division', 'creator'])
            ->get();
            
        $upcomingAgendas = Agenda::where('date', '>=', date('Y-m-d'))
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->with('division')
            ->get();
            
        $divisions = Division::all();

        return view('kalender.index', compact('agendas', 'upcomingAgendas', 'currentDate', 'divisions'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'category' => 'required|in:Kegiatan,Rapat,Deadline,Event,Forum Rutin,Program Kerja',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        Agenda::create([
            'title' => $request->title,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'description' => $request->description,
            'category' => $request->category,
            'division_id' => $request->division_id,
            'status' => 'Terjadwal',
            'created_by' => auth()->id()
        ]);

        return back()->with('success', 'Agenda berhasil ditambahkan!');
    }

    public function update(Request $request, Agenda $agenda) {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'category' => 'required|in:Kegiatan,Rapat,Deadline,Event,Forum Rutin,Program Kerja',
            'division_id' => 'nullable|exists:divisions,id',
            'status' => 'required|in:Terjadwal,Berlangsung,Selesai,Dibatalkan'
        ]);

        $agenda->update($request->all());

        return back()->with('success', 'Agenda berhasil diperbarui!');
    }

    public function destroy(Agenda $agenda) {
        $agenda->delete();
        return back()->with('success', 'Agenda berhasil dihapus!');
    }
}

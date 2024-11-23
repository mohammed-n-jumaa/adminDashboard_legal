<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Lawyer;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('user', 'lawyer')->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $users = User::all();
        $lawyers = Lawyer::all();
        return view('admin.appointments.create', compact('users', 'lawyers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'lawyer_id' => 'required|exists:lawyers,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        Appointment::create($request->all());
        return redirect()->route('admin.appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        $users = User::all();
        $lawyers = Lawyer::all();
        return view('admin.appointments.edit', compact('appointment', 'users', 'lawyers'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'lawyer_id' => 'required|exists:lawyers,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $appointment->update($request->all());
        return redirect()->route('admin.appointments.index');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Reviews;

class DocsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        $doctor = Auth::user();
        $appointments = Appointments::where('doc_id', $doctor->id)->where('status', 'upcoming')->get();
        $reviews = Reviews::where('doc_id', $doctor->id)->where('status', 'active')->get();

        return view('dashboard')->with(['doctor'=>$doctor, 'appointments'=>$appointments,'reviews'=>$reviews]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Almacena los detalles de la reservación para la aplicación
        $reviews = new Reviews();
        //actualiza el estado de la reservación
        $appointment = Appointments::where('id', $request->get('appointment_id'))->first();

        //guarda los ratings y reviews
        $reviews->user_id = Auth::user()->id;
        $reviews->doc_id = $request->get('doc_id');
        $reviews->ratings = $request->get('ratings');
        $reviews->reviews = $request->get('reviews');
        $reviews->reviewed_by = Auth::user()->name;
        $reviews->status = 'active';
        $reviews->save();

        //actualiza el estado de la reservación
        $appointment->status = 'complete';
        $appointment->save();

        return response()->json([
            'success' => 'The appointment has been completed and reviewed succesfully!',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
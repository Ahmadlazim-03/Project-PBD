<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViewRetur;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Transaksi/ReturBarang',[
            "TableRetur" => ViewRetur::all()
        ]);
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
        DB::statement("CALL insert_retur($request->idpenerimaan,$request->iduser)");
        return redirect()->back();
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
        DB::statement("CALL update_retur($id, $request->idpenerimaan,$request->iduser)");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::statement("CALL delete_retur($id)");
        return redirect()->back();
    }
}

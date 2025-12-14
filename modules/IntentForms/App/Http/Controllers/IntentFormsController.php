<?php

namespace Modules\IntentForms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\IntentForms\App\Models\Intentform;
use Illuminate\Support\Facades\Auth;

class IntentFormsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $intentforms = Intentform::get();


        return view('intentforms::index', [
            'intentforms' => $intentforms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('intentforms::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('intentforms::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('intentforms::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    public function print($id)
    {
        return view('intentforms::print');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

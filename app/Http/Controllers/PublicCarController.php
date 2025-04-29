<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class PublicCarController extends Controller
{
    public function show(Car $car)
{
    return view('car.show', compact('car'));
}
}

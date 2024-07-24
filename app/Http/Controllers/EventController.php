<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Auth;

class EventController extends Controller
{
    public function index()
    {
        return view('event.home');
    }
    public function show($slug = null)
    {

        $event = Event::where('slug', $slug)->first();

        if (!$event)
            return redirect()->route('events');
        
        if(!Auth::user()->isAllowedToViewEvent($event->id)){
            return view('event.denied');
        }

        return view('event.show', ['event' => $event]);
    }
}

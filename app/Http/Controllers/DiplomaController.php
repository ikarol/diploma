<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiplomaCreateRequest;
use App\Models\Diploma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiplomaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('diploma.create');
    }

    public function store(DiplomaCreateRequest $request)
    {
        $diploma = Diploma::create([
            'professor_id' => Auth::user()->professor->id,
            'title' => $request['title'],
            'description' => $request['description'],
            'technologies' => empty($request['technologies']) ? '' : $request['technologies'],
        ]);

        // Auth::user()->professor()->diploma()->save($diploma);

        return redirect('/dashboard');

    }

    public function index()
    {
        $diplomas = Diploma::latest();

        if ($month = request('month')) {
            $diplomas->whereMonth('created_at', Carbon::parse($month)->month);
        }
        if ($year = request('year')) {
            $diplomas->whereYear('created_at', $year);
        }

        $diplomas = $diplomas->get();

        $archives = Diploma::archives();

        // foreach ($archives as &$stats) {
        //     $stats['month'] = \DateTime::createFromFormat('!m', $stats['month'])
        //         ->format('F');
        // }

        return view('diploma.index', compact('diplomas'));
    }
}

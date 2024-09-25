<?php

namespace App\Http\Controllers;

use App\Imports\CoursesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Course;

class CourseController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new CoursesImport, $request->file('file'));

        return redirect()->back()->with('success', 'Cursos importados correctamente.');
    }
    public function index()
    {
        return Course::all();
    }
}

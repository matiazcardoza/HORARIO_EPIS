<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CoursesImport;
use App\Exports\CoursesExport;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('admin/dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import(Request $request)
    {
        // Validar que el archivo está presente y es de un tipo permitido
        $request->validate([
            'document' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Requiere archivo de tipo Excel
        ]);

        // Obtener el archivo subido
        $file = $request->file('document');

        // Importar el archivo usando la clase CoursesImport
        Excel::import(new CoursesImport, $file);

        return redirect()->back()->with('success', 'Archivo importado exitosamente!'); // Redirigir con mensaje de éxito
    }

    public function export(){
        return Excel::download(new CoursesExport, 'courses.xlsx');
    }
}

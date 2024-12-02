<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['create' , 'store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['edit' , 'update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections' , compact('sections'));
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
    public function store(Request $request){
        $validated = $request->validate([
            'section_name' => 'required|unique:sections|min:3',
            'description' => 'required',
        ] , [
            'section_name.required' => 'حقل اسم القسم فارغ',
            'section_name.unique' => 'اسم القسم موجود بالفعل',
            'description.required' => 'حقل وصف القسم فارغ',
        ]);
        Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name,
            ]);

            session()->flash('Add' , 'تم إضافة القسم بنجاح');
            return redirect('/sections');
        }


    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $id = $request->id;
        $validated = $request->validate([
            'section_name' => 'required|min:3|unique:sections,section_name,'.$id,
            'description' => 'required',
        ] , [
            'section_name.required' => 'حقل اسم القسم فارغ',
            'section_name.unique' => 'اسم القسم موجود بالفعل',
            'description.required' => 'حقل وصف القسم فارغ',
        ]);
        $section = Section::find($id);
        $section->update([
                'section_name' => $request->section_name,
                'description' => $request->description,
        ]);

            session()->flash('edit' , 'تم تعديل القسم بنجاح');
            return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->id;
        $section = Section::find($id);
        $section->delete();
        session()->flash('delete' , 'تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}


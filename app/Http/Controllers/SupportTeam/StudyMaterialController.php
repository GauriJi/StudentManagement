<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use App\Models\MyClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudyMaterialController extends Controller
{
    public function index()
    {
        if (Qs::userIsTeamSA() || Qs::userIsTeacher()) {
            if (Qs::userIsTeacher()) {
                $materials = StudyMaterial::where('teacher_id', Auth::user()->id)->with(['my_class', 'subject', 'teacher'])->latest()->get();
            } else {
                $materials = StudyMaterial::with(['my_class', 'subject', 'teacher'])->latest()->get();
            }
        } elseif (Qs::userIsStudent()) {
            $student = Auth::user()->student_record;
            $materials = StudyMaterial::where('my_class_id', $student->my_class_id)
                ->with(['my_class', 'subject', 'teacher'])
                ->latest()
                ->get();
        } elseif (Qs::userIsParent()) {
            $children = Auth::user()->student_record()->get();
            $class_ids = $children->pluck('my_class_id')->unique();
            $materials = StudyMaterial::whereIn('my_class_id', $class_ids)->with(['my_class', 'subject', 'teacher'])->latest()->get();
        } else {
            $materials = collect();
        }

        $my_classes = MyClass::orderBy('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('pages.support_team.study_materials.index', compact('materials', 'my_classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'my_class_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->except(['file']);
        $data['teacher_id'] = Auth::user()->id;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $f_name = 'material_' . time() . '.' . $file->getClientOriginalExtension();
            $data['file_path'] = $file->storeAs('public/study_materials', $f_name);
        }

        StudyMaterial::create($data);

        return back()->with('flash_success', 'Study Material uploaded successfully');
    }

    public function download($id)
    {
        $material = StudyMaterial::findOrFail($id);
        
        if ($material->file_path && Storage::exists($material->file_path)) {
            return Storage::download($material->file_path);
        }

        return back()->with('flash_danger', 'File not found');
    }

    public function destroy($id)
    {
        $material = StudyMaterial::findOrFail($id);
        
        if (Qs::userIsSuperAdmin() || Auth::user()->id == $material->teacher_id) {
            if ($material->file_path && Storage::exists($material->file_path)) {
                Storage::delete($material->file_path);
            }
            $material->delete();
            return back()->with('flash_success', 'Material deleted successfully');
        }

        return back()->with('flash_danger', 'Unauthorized action');
    }
}

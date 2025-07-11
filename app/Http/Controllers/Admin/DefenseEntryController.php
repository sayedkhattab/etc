<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefenseEntry;
use App\Models\CaseModel;
use App\Models\User;
use App\Models\DefenseAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DefenseEntryController extends Controller
{
    public function index()
    {
        $entries = DefenseEntry::with(['case', 'student'])->paginate(20);
        return view('admin.defense_entries.index', compact('entries'));
    }

    public function create()
    {
        $cases = CaseModel::all();
        $students = User::whereHas('role', fn($q)=>$q->where('name','student'))->get();
        return view('admin.defense_entries.create', compact('cases','students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id'   => 'required|exists:cases,id',
            'student_id'=> 'required|exists:users,id',
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'status'    => 'required|in:draft,submitted,reviewed',
            'attachments.*'=>'file|max:10240',
        ]);

        if($validated['status']==='submitted') $validated['submitted_at']=now();

        $entry = DefenseEntry::create($validated);

        if($request->hasFile('attachments')){
            foreach($request->file('attachments') as $file){
                $path = $file->store('defense_attachments/'.$validated['case_id'].'/'.$entry->id,'public');
                DefenseAttachment::create([
                    'defense_entry_id'=>$entry->id,
                    'file_path'=>$path,
                    'file_name'=>$file->getClientOriginalName(),
                    'file_type'=>$file->getClientMimeType(),
                    'file_size'=>$file->getSize(),
                ]);
            }
        }
        return redirect()->route('admin.defense-entries.show',$entry)->with('success','تم إنشاء المذكرة بنجاح');
    }

    public function show(DefenseEntry $defense_entry)
    {
        $defense_entry->load(['case','student','attachments']);
        return view('admin.defense_entries.show', ['entry'=>$defense_entry]);
    }

    public function destroy(DefenseEntry $defense_entry)
    {
        foreach($defense_entry->attachments as $att){
            if(Storage::disk('public')->exists($att->file_path)){
                Storage::disk('public')->delete($att->file_path);
            }
        }
        $defense_entry->delete();
        return redirect()->route('admin.defense-entries.index')->with('success','تم حذف المذكرة');
    }
} 
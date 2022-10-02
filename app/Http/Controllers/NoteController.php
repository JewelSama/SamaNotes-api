<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // return Note::get();
        return auth()->user()->notes()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'note' => 'required',
            'category' => 'required',
        ]);
        Note::create([
            'note' => $request->note,
            'category'=> $request->category,
            'user_id' => auth()->id()
        ]);
        return response('Note created', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note =  Note::find($id);
        return $note;
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
        $note = Note::find($id);
        $note->update([
            'category' => $request->category,
            'note' => $request->note,
        ]);
        return $note;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::find($id);
        $note->destroy($id);
    }

    /**
     * Search resource from storage.
     *
     * @param  int  $note
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Note::where('note', 'like',  '%'.$name.'%')->orWhere('category', 'like',  '%'.$name.'%')->get();
        
    }
}

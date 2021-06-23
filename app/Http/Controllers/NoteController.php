<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotesModel;
use Exception;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    /**
     * @OA\Post(
     ** path="/api/auth/addNotes",
     *   tags={"Add Notes"},
     *   summary="Add notes",
     *   operationId="AddNotes",
     * 
     *  
     *  @OA\Parameter(
     *      name="Note Title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function createNote(Request $request)
    {
        try {
            $note = new NotesModel;
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->user_id = Auth::user()->id;
            $note->save();
        } catch (Exception $e) {
            return response()->json(['status' => 404, 'message' => 'Invalid authorization token is invalid'], 404);
        }
        return response()->json(['status' => 200, 'message' => 'Note created']);
    }

    /**
     * @OA\Get(
     ** path="/api/auth/getNotes",
     *   tags={"Get Notes"},
     *   summary="Get notes",
     *   operationId="GetNotes",
     * 
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotes()
    {
        try {
            $notes = new NotesModel();
            $notes->user_id = auth()->id();
            return DB::table('notes')->select('id', 'title', 'description')->where('user_id', $notes->user_id)->get();
        } catch (Exception $e) {
            return response()->json(['status' => 201, 'message' => 'Invalid token']);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/auth/updateNote",
     *   tags={"Update Notes"},
     *   summary="Update Notes",
     *   operationId="updateNote",
     * 
     *  @OA\Parameter(
     *      name="Note id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function updateNote(Request $request)
    {
        $id = $request->input('id');
        try {
            $note = NotesModel::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['status' => 422, 'message' => "Notes are not available with that id"], 422);
        }
        if ($note->user_id == auth()->id()) {
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->save();
            return response()->json(['status' => 200, "message" => "Note Updated!"]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/auth/deleteNote",
     *   tags={"Delete Note"},
     *   summary="Delete Note",
     *   operationId="deleteNote",
     *
     *  @OA\Parameter(
     *      name="Note id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteNote(Request $request)
    {
        $id = $request->input('id');
        try {
            $note = NotesModel::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['status' => 422, 'message' => "Invalid note id"], 422);
        }
        if ($note->user_id == auth()->id()) {
            if ($note->delete()) {
                return response()->json(['status' => 200, 'message' => 'Note Deleted!']);
            }
        }
    }
}

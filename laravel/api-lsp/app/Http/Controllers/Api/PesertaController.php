<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Validator;

 // Pastikan Anda membuat Mailable yang sesuai

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //
        $peserta = Peserta::all();
        // dd($produkid);
        return response()->json([
            'success'   => true,
            'message'   => 'List Peserta',
            'peserta'  => $peserta
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPesertaId(Request $request)
    {
        //
         $authenticatedUser = $request->user();

        // If you want to get additional details from the database
        $pesertaDetails = Peserta::find($authenticatedUser->id);

        return response()->json([
            'success' => true,
            'user' => $authenticatedUser,
            'details' => $pesertaDetails,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  


   public function updateProfile(Request $request, $id)
{   

     $request->validate([
        'user_name'     => 'required|string',
        // 'user_email'    => 'nullable|email|unique:peserta,user_email,' . $id,
        // 'no_hp'         => 'nullable|string',
        // 'nik'           => 'nullable|string|unique:peserta,nik,' . $id,
        // 'tgl_lahir'     => 'nullable|date',
        // 'user_password' => 'nullable|min:6',
        // 'kampus'        => 'nullable|string',
        // 'status'        => 'nullable|string',
        // 'tempat'        => 'nullable|string',
        // 'date'          => 'nullable|date',
    ]);


    $peserta = Peserta::find($id);

    // if (!$peserta) {
    //     return response()->json(['message' => 'Peserta tidak ditemukan.'], 404);
    // }

   

    $peserta->user_name = $request->user_name;
    // $peserta->user_email = $request->filled('user_email') ? $request->input('user_email') : $peserta->user_email;
    // $peserta->no_hp = $request->filled('no_hp') ? $request->input('no_hp') : $peserta->no_hp;
    // $peserta->nik = $request->filled('nik') ? $request->input('nik') : $peserta->nik;
    // $peserta->tgl_lahir = $request->filled('tgl_lahir') ? $request->input('tgl_lahir') : $peserta->tgl_lahir;
    // $peserta->user_password = $request->filled('user_password') ? $request->input('user_password') : $peserta->user_password;
    // $peserta->kampus = $request->filled('kampus') ? $request->input('kampus') : $peserta->kampus;
    // $peserta->status = $request->filled('status') ? $request->input('status') : $peserta->status;
    // $peserta->tempat = $request->filled('tempat') ? $request->input('tempat') : $peserta->tempat;
    // $peserta->date = $request->filled('date') ? $request->input('date') : $peserta->date;

    $peserta->update();

    return response()->json([
        'message' => 'Profile updated successfully.',
        'data' => $peserta
    ],200);
}


 public function showProfile(Request $request, $id)
{
    $peserta = Peserta::find($id);

    if (!$peserta) {
        return response()->json(['message' => 'Peserta tidak ditemukan.'], 404);
    }


    return response()->json([
        'message' => 'Show peserta.',
        'data' => $peserta
    ]);
}

// public function updateProfile(Request $request, $id)
// {
//     $peserta = Peserta::find($id);

//     if (!$peserta) {
//         return response()->json(['message' => 'User not found.'], 404);
//     }

//     $this->validate($request, [
//         'user_name' => 'required|string|max:255',
//         'user_email' => 'required|string|email|max:255|unique:peserta,user_email,' . $id,
//         // Add other fields as needed
//     ]);

//     $peserta->user_name = $request->input('user_name');
//     $peserta->user_email = $request->input('user_email');
//     // Update other fields as needed

//     $peserta->save();

//     return response()->json(['message' => 'Profile updated successfully.']);
// }


public function update(Request $request, Peserta $peserta, $id)
{

    // $id = $this->route('id'); // Get the 'id' parameter from the route

       
      
    $rules = [
       
            'user_name' => 'required|string',
            'user_email' => 'required|email|unique:peserta,user_email,' . $id,
            'no_hp' => 'required|string',
            // 'nik' => 'nullable|string|unique:pesertas,nik,' . $id, // Adjust as needed
            'tgl_lahir' => 'nullable|string', // Adjust as needed
            'user_password' => 'nullable|string|min:6', // Adjust as needed
            // Add other rules for additional fields as needed
    ];

    $messages = [
        'user_name.required' => 'Username is required.',
        // Add other custom error messages as needed
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        $messages = $validator->messages();
        return response()->json(["messages" => $messages], 500);
    }

    $peserta = Peserta::findOrFail($id);

    $peserta->update([
        'user_name' => $request->input('user_name'),
        'user_email' => $request->input('user_email'),
        'no_hp' => $request->input('no_hp'),
        'nik' => $request->input('nik'),
        'tgl_lahir' => $request->input('tgl_lahir'),
        'user_password' => md5($request->input('user_password')), // MD5 hash the password
        // Add other fields as needed
    ]);

    return response()->json([
        "message" => "Peserta has been updated successfully",
        "peserta" => $peserta,
        
    ], 200);
}

}

<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Peserta;



class AuthController extends Controller
{
    /**
     * Register a new Peserta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'user_name'     => 'required|string',
            'user_email'    => 'required|email|unique:peserta',
            'no_hp'         => 'required|string',
            // 'nik'           => 'required|string|unique:peserta',
            // 'tgl_lahir'     => 'required|date',
            // 'user_password' => 'required|min:6',
            // 'user_level'    => 'required|string',
            // 'kampus'        => 'required|string',
            // 'id_skema'      => 'required|integer',
            // 'status'        => 'required|string',
            // 'tempat'        => 'required|string',
            // 'date'          => 'required|date',
        ]);

      $peserta = Peserta::create([
        'user_name'     => $request->user_name ?? null,
        'user_email'    => $request->user_email ?? null,
        'no_hp'         => $request->no_hp ?? null,
        'nik'           => $request->nik ?? null,
        'tgl_lahir'     => $request->tgl_lahir ?? null,
        'user_password' => md5($request->user_password), // Use md5 function for hashing
        'user_level'    => $request->user_level ?? 1,
        'kampus'        => $request->kampus ?? null,
        'id_skema'      => $request->id_skema ?? null,
        'status'        => $request->status ?? null,
        'tempat'        => $request->tempat ?? null,
        'date'          => $request->date ?? null,
    ]);


        $token = $peserta->createToken('API Token')->plainTextToken;
        $code = 201;
        return response()->json([
            'message' => 'Registration successful', 
            'peserta' => $peserta,
            'token' => $token
        ], $code);
    }

    /**
     * Log in a Peserta and return an access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    


public function login(Request $request)
{
    // set validation
    $validator = Validator::make($request->all(), [
        'user_email'    => 'required|email',
        'user_password' => 'required',
    ]);

    // if validation fails
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // get Peserta by email
    $peserta = Peserta::where('user_email', $request->user_email)->first();

    // if Peserta not found or password is incorrect
    if (!$peserta || md5($request->user_password) !== $peserta->user_password) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau Password Anda salah',
        ], 401);
    }

    // attempt to log in the Peserta using the 'api' guard
    if (Auth::guard('pesertas')) {
        // generate token for Peserta
        $token = $peserta->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user'    => $peserta,
            'token'   => $token
        ], 200);
    } else {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}


public function register_kampus(Request $request)
    {
        $request->validate([
            'user_name'     => 'required|string',
            'user_email'    => 'required|email|unique:peserta',
            'no_hp'         => 'required|string',
            'nik'           => 'required|string|unique:peserta',
            // 'tgl_lahir'     => 'required|date',
            // 'user_password' => 'required|min:6',
            // 'user_level'    => 'required|string',
            'kampus'        => 'required|string',
            // 'id_skema'      => 'required|integer',
            // 'status'        => 'required|string',
            // 'tempat'        => 'required|string',
            // 'date'          => 'required|date',
        ]);

      $peserta = Peserta::create([
        'user_name'     => $request->user_name ?? null,
        'user_email'    => $request->user_email ?? null,
        'no_hp'         => $request->no_hp ?? null,
        'nik'           => $request->nik ?? null,
        'tgl_lahir'     => $request->tgl_lahir ?? null,
        'user_password' => md5($request->user_password), // Use md5 function for hashing
        'user_level'    => $request->user_level ?? 3,
        'kampus'        => $request->kampus ?? null,
        'id_skema'      => $request->id_skema ?? null,
        'status'        => $request->status ?? null,
        'tempat'        => $request->tempat ?? null,
        'date'          => $request->date ?? null,
    ]);

        $token = $peserta->createToken('API Token')->plainTextToken;
        $code = 201;
        return response()->json([
            'message' => 'Registration successful', 
            'peserta' => $peserta,
            'token' => $token
        ], $code);
    }


    public function login_mahasiswa(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'user_email'    => 'required|email',
            'user_password' => 'required',
            'kampus' => 'required',
        ]);

        // if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // get Peserta by email
        $peserta = Peserta::where('user_email', $request->user_email)->first();

        // if Peserta not found or password is incorrect
        if (!$peserta || md5($request->user_password) !== $peserta->user_password) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah',
            ], 401);
        }

        // Check if the Peserta belongs to a specific Kampus
        $kampus = $request->input('kampus'); // assuming you send kampus_id in the login request

        if ($peserta->kampus != $kampus) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kampus ini. Silakan pilih kampus yang sesuai.',
            ], 403);
        }

        // attempt to log in the Peserta using the 'api' guard
        if (Auth::guard('pesertas')) {
            
            // generate token for Peserta
            $token = $peserta->createToken('AppName')->accessToken;

            return response()->json([
                'success' => true,
                'user'    => $peserta,
                'token'   => $token,
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }


    public function logout(Request $request)
    {
        $peserta = $request->user();

        // Revoke the user's current token
        $peserta->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }


    public function update(Request $request, $id)
{
    $peserta = Peserta::find($id);

    if (!$peserta) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    $validator = Validator::make($request->all(), [
        'user_name' => 'required|string|max:255',
        'user_email' => 'required|string|email|max:255|unique:peserta,user_email,' . $id,
        // Add other fields as needed
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    $peserta->user_name = $request->input('user_name');
    $peserta->user_email = $request->input('user_email');
    // Update other fields as needed

    $peserta->save();

    return response()->json(
        ['message' => 'Profile updated successfully.']
    );
}


  public function verifikasi_peserta_mahasiswa(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            // 'tgl_lahir' => 'required|date',
        ]);

        $nik = $request->input('nik');
        //$tanggal_lahir = $request->input('tgl_lahir');

        // Lakukan verifikasi berdasarkan NIK dan tanggal lahir
        $peserta = Peserta::where('nik', $nik)->first();

            if ($peserta) {
            // Data valid, lakukan sesuatu di sini
            $token = $peserta->createToken('password-setup-token')->plainTextToken;

            return response()->json([
                'message' => 'Verifikasi berhasil',
                'token' => $token,
                'user' => $peserta,
            ], 200);
            } else {
            // Data tidak valid
            return response()->json(['message' => 'Verifikasi gagal Silahkan Hubungi Admin'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    
     public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required',
            'user_email' => 'required',
            // 'password' => 'required|confirmed|min:8',
        ]);

        $peserta = Peserta::where('nik', $request->nik)
                    ->where('user_email', $request->user_email)
                    ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Invalid NIK or Email.'], 404);
        }

        // Reset password
        $peserta->user_password = md5($request->input('user_password'));
        // $user->password_reset_token = null;
        $peserta->save();

        return response()->json(['message' => 'Password reset successful.']);
    }


    public function buatPassword(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required',
            'user_email' => 'required',
            // 'password' => 'required|confirmed|min:8',
        ]);

        $peserta = Peserta::where('nik', $request->nik)
                    ->where('user_email', $request->user_email)
                    ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Invalid NIK or Email.'], 404);
        }

        // Reset password
        $peserta->user_password = md5($request->input('user_password'));
        // $user->password_reset_token = null;
        $peserta->save();

        return response()->json(['message' => 'Create Password successful.']);
    }

}



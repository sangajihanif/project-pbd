<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ValidateOtpRequest;
use App\Mail\SendMessage;
use App\Models\User;
use App\Models\VUser;
use Brick\Math\BigInteger;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Seshac\Otp\Otp;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $user = VUser::query()
            ->when($q != '', fn($query) => $query->where('nama_user', 'like', "%{$q}%"))
            ->orderBy('nama_user')
            ->paginate(10)
            ->withQueryString();

        return view('user.index', compact('user', 'q'));
    }
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function LoginStore(LoginRequest $request)
    {
        $payload = $request->validated();
        $user = User::where('email', $payload['email'])->first();

        if (!$user || !Hash::check($payload['password'], $user->password)) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        if ($user->email_verified_at == null) {
            return redirect()->route('otp', ['id' => $user->id])->with('warning', 'Please verify your email first.');
        }

        Auth::login($user);
        request()->session()->regenerate();
        return redirect()->route($user->homeRoute())->with('success', 'Anda berhasil login!');
    }

    public function logout()
    {

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('login');
    }

    public function generateOtp(string $identifier)
    {
        $result = Otp::setValidity(30)->setLength(6)->setMaximumOtpsAllowed(10)
            ->setOnlyDigits(true)->setUseSameToken(false)->generate($identifier);

        \Seshac\Otp\Models\Otp::where('identifier', $identifier)->update(['no_times_attempted' => 0]);
        return $result;
    }

    public function sendOtpVerification(int $userid)
    {
        $user = User::find($userid);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        if ($user->email_verified_at != null) {
            return redirect()->back()->with('warning', 'Email already verified');
        }

        $token = $this->generateOtp($user->email);
        if (!$token->status) {
            return redirect()->back()->with('error', $token->message);
        }
        try {
            Mail::to($user->email)->send(
                new SendMessage($token->token, url('/otp/' . $user->id))
            );
        } catch (\Throwable $e) {
            report($e);
            return redirect('/otp/' . $user->id)
                ->with('error', 'Email OTP gagal dikirim. Tunggu beberapa detik lalu klik "Kirim Ulang OTP".');
        }
    }

    public function resendOtp(int $userid)
    {

        if ($response = $this->sendOtpVerification($userid)) {
            return $response;
        }
        return redirect()->back()->with('success', 'OTP sent successfully');
    }

    public function verifyOtp($identifier, $token)
    {
        $verify = Otp::setAllowedAttempts(10) // number of times they can allow to attempt with wrong token
            ->validate($identifier, $token);
        return $verify;
    }

    public function checkOtp(array $payload, int $userid)
    {
      
        $otp = implode('', $payload);

        $user = User::find($userid);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $verify = $this->verifyOtp($user->email, $otp);
        if ($verify->status == false) {
            return redirect()->back()->with('error', 'OTP is not valid, please try again');
        }

        $user->email_verified_at = Carbon::now();
        $user->save();
        Auth::login($user);
        request()->session()->regenerate();
        return redirect()->route($user->homeRoute())->with('success', 'Akun berhasil diverifikasi!');
    }

    public function hideEmail($email)
    {
        $email = explode('@', $email);
        $email[0] = substr($email[0], 0, 3) . '****';
        return implode('@', $email);
    }

    public function viewOtp(int $userid)
    {
        $user = User::find($userid);
        if (!$user) {
            return redirect()->back()->with(key: 'error', value: 'User not found');
        }
        if ($user->email_verified_at != null) {
            return redirect()->back()->with(key: 'warning', value: 'Email already verified');
        }
        $email = $this->hideEmail($user->email);
        return view('auth.otp', data: [
            'email' => $email,
            'id' => $userid
        ]);
    }

    public function validateOtp(ValidateOtpRequest $request)
    {
        $payload = $request->validated();
        $userid = request()->route('id');
        return $this->checkOtp($payload, $userid);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function storeRegister(RegisterRequest $request)
    {
        $payload = $request->validated();
        $payload['password'] = Hash::make($payload['password']);

        $payload['idrole'] = 4;

        $user = User::create($payload);
        if ($response = $this->sendOtpVerification($user->id)) {
            return $response;
        }
        return redirect('/otp/' . $user->id)->with('success', 'Please check your email for OTP verification');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request,  $id)
    {
        $payload = $request->validated();

        User::findOrFail($id)->update($payload);

        return redirect()->route('user.index')->with('success', 'Perubahan berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang dipakai.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            return back()->with('error', "User \"{$user->name}\" tidak dapat dihapus: " . $this->pesanDb($e));
        }

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;

use App\Http\Requests;

use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function send(Request $request,User $user)
    {
        $email=$request->input(['email_address']);
        if($user->where(['user_email'=>$email])){
            $user_name=$user->where(['user_email'=>$email])->value('user_name');
            $user_id=$user->where(['user_email'=>$email])->value('user_id');
            $data = ['email'=>$email, 'name'=>'DINO', 'user_name'=>$user_name,'user_id'=>$user_id];
            Mail::send('Mail.sendEmail', $data, function($message) use($data)
            {
                $message->to($data['email'], $data['name'])->subject('DINO');
            });
            return view('auth/passwords/email',['msg'=>"1"]);
        }
        else{
            return view('auth/passwords/email',['msg'=>"0"]);
        }

    }
    public function resetPassword(Request $request,User $user){
        $request->validate([
            'user_id'=>'required',
        ]);
        $user_id=$request->input('user_id');
        if(!empty($request->input('password'))){
            $map=[
              'user_id'=>$user_id
            ];
            $update_data=[
                'user_password'=>$request->input('password')
            ];
            try {
                //开始事务
                DB::beginTransaction();
                $user->edit($map,$update_data);
                //提交事务
                DB::commit();
                return response()->json(['msg' => "Password reseted!"]);

            } catch(QueryException $ex) {
                //回滚事务
                DB::rollback();
                return response()->json(['msg' => "Network is busy,try again later"]);
            }
        }else{
        return view('Mail/resetPassword',['user_id'=>$user_id]);}
    }
}

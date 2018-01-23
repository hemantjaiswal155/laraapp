<?php

namespace App\Http\Controllers;
use App\Blog;
use App\User;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * function for fetch all blogs
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
	    $blog = Blog::all();
	    return response()->json($blog);
    }


    /**
     * function for save blog
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBlog($id)
    {
        $blog = Blog::find($id);
        return response()->json($blog);
    }

    public function saveBlog(Request $request)
    {
        try {
            $input = $request->all();
            $blog = new Blog();
            $blog->title = $input['title'];
            $blog->category = $input['categories'];
            $blog->content = $input['content'];
            $blog->save();
            return response()->json([$blog]);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function deleteBlog($id)
    {
        $blog = Blog::find($id)->delete();
        return response()->json([$blog]);
    }


    public function saveUser(Request $request)
    {
        try {
          $input = $request->all();

          $validator = \Validator::make($input, User::validationRulesForAddUser(), User::$validationMessages);

          if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error'=>$error], 201);
            }
          }

          $user = new User();
          $user->name         = $input['first_name']." ".$input['last_name'];
          $user->first_name   = $input['first_name'];
          $user->last_name    = $input['last_name'];
          $user->email        = $input['email'];
          $user->contact_number = $input['contact_number'];
          $user->address        = $input['address'];
          $user->password     = bcrypt($input['password']);


          //Save user details
          if ($user->save()) {
              //Assign role to registered user
              $user->attachRole(config('app.roles.user.id'));
            }
            return response()->json([$user]);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 201);
        }
    }

    public function updateUser(Request $request)
    {
        try {
          $input = $request->all();

          $validator = \Validator::make($input, User::validationRulesForUpdateUser(), User::$validationMessages);

          if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error'=>$error], 201);
            }
          }

          $user = User::find($input['id']);
          $user->name         = $input['first_name']." ".$input['last_name'];
          $user->first_name   = $input['first_name'];
          $user->last_name    = $input['last_name'];
          $user->email        = $input['email'];
          $user->contact_number = $input['contact_number'];
          $user->address        = $input['address'];
          $user->save();
       
            return response()->json(['success'=>'Profile updated successfully', 'userInfo'=>$user]);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 201);
        }
    }

    public function loginUser(Request $request)
    {
      try {
        $input = $request->all();
        if (\Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {

          return response()->json(['success'=>'login successfull', 'userInfo'=>Auth::user()]);
        }

        return response()->json(['error'=>'Please enter valid username password'], 201);
      } catch (\Exception $e) {
          return response()->json(['error'=>$e->getMessage()], 201);
      }
    }

    public function forgotPassword(Request $request)
    {
      try {
        $input = $request->all();
        $user = User::where('email',$input['email'])->first();
        if (!empty($user)) {

          return response()->json(['success'=>'Forgot password link send successfully']);
        }

        return response()->json(['error'=>'Your email address does not exists in our database.'], 201);
      } catch (\Exception $e) {
          return response()->json(['error'=>$e->getMessage()], 201);
      }
    }

    public function changePassword(Request $request)
    {
      try {
          $input = $request->all();
          $validator = \Validator::make($input, User::passwordRules(), User::$validationMessages);
          if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error'=>$error], 201);
            }
          }
          $user = User::find($input['id']);
          if($user){
            $user->password     = bcrypt($input['password']);
            $user->save();
            return response()->json(['success'=>'Your password has been updated successfully.']);
          }
          return response()->json(['error'=>'Some error occured, Please try again.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 201);
        }
    }

    public function logoutUser()
    {
      try {
        
        if (\Auth::logout()) {
          return response()->json(['success'=>'logout successfull']);
        }

        return response()->json(['error'=>'Some error in logout, please try again.']);
      } catch (\Exception $e) {
          return response()->json(['error'=>$e->getMessage()], 201);
      }
    }

    public function allUsers()
    {
      try {
        $userId = Auth::user();
        $users = User::where('id','<>', $userId)->where('name','<>','Super Admin')->get(['id','name','email']);
        if ($users) {
          $users = $users->toArray();
          array_unshift($users, array('id'=>'', 'name'=>'Select User', 'email'=>''));
          return response()->json($users);
        }

      } catch (\Exception $e) {
          return response()->json(['error'=>$e->getMessage()], 201);
      }
    }

    public function sendMessage(Request $request)
    {
        try {
          $input = $request->all();

          $message = new Message();
          $message->message_from = $userId
          $message->message_to   = $input['sender'];
          $message->message    = $input['message'];
         
          if ($message->save()) {
             return response()->json(['success'=>'Message sent successfully']);
          }
          return response()->json(['error'=>'Some error in message sending'], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 201);
        }
    }
}

<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\User;
use Storage;

class RegistrationController extends Controller
{
    // public function create()
    // {
    //     return view('registration.create');
    // }
    public function store(Request $request)
    { 

      
        // // $file = $request->file('file');
        // // // $file->move($destinationPath);
        // // Storage::disk('local')->put($file, 'Contents');
        // $file = $request->file('file');

        // // generate a new filename. getClientOriginalExtension() for the file extension
        // $filename = 'file-' . time() . '.' . $file->getClientOriginalExtension();
    
        // // save to storage/app/photos as the new $filename
        // $path = $file->storeAs('files', $filename);
        // dd($path);
        $this->validate(request(), [
          'name' => 'required',
          'surname' => 'required',
          'age' => 'required',
          // "flie" => 'required',
      ]);
      $ralative_path = null;
      if ($request->hasFile('file')) {
        $imageTempName = $request->file('file')->getPathname();
        $imageName = $request->file('file')->getClientOriginalName();
        //echo $imageName; die;
        $path = base_path() . '/public/uploads/';
        $request->file('file')->move($path , $imageName);
        $data=array('media'=>$imageName);
        $ralative_path = '/public/uploads/'  . $data['media'];
      }
     
        $user = User::create([
          'name' =>$request['name'],
          'surname' => $request['surname'],
          'age' => $request['age'],
          'cv' => $ralative_path
        ]);
        
        
        return redirect()->to('/get-users');

    }
    public function get_users(Request $request)
    {
      $allUsers = User::get()->toArray();
      return view('allUsers', ['allUsers' => $allUsers]);
    }
    public function delete_user(Request $request)
    {
      $user_id = $request->id;
      $user = User::find($user_id);
      $user->delete();
      return \Response::json(['error' => false]);

    }

    public function search_user(Request $request)
    {
      $id = $request-> id;
      $allUsers = User::select('name', 'surname', 'id', "cv", 'age')->where('name','LIKE','%'.$id.'%')->get()->toArray();
      return \Response::json(['error' => false, "data" =>  $allUsers]);


      // $user_id = $request->id;
      // $user = User::find($user_id);
      // $user->delete();
      // return \Response::json(['error' => false]);

    }
    
}

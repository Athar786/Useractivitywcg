<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{
    // set index page view
	public function index(Request $request) {
		return view('home');
	}

	// handle fetch all eamployees ajax request
	public function fetchAll() {
		$emps = User::role(0)->get();
		// $emps = Employee::all();
		$output = '';
		if ($emps->count() > 0) {
			$output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Post</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
			foreach ($emps as $emp) {
				$output .= '<tr>
                <td>' . $emp->id . '</td>
                <td><img src="storage/images/' . $emp->avatar . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>' . $emp->first_name . ' ' . $emp->last_name . '</td>
                <td>' . $emp->email . '</td>
                <td>' . $emp->post . '</td>
                <td>' . $emp->phone . '</td>
                <td>
                  <a href="#" id="' . $emp->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $emp->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
			}
			$output .= '</tbody></table>';
			echo $output;
		} else {
			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}
	}

	// handle insert a new employee ajax request
	public function store(Request $request) {
		// $validator = Validator::make($request->all(), [
        //     'fname' => 'required|alpha',
		// 	'lname' => 'required|alpha',
		// 	'phone' => 'required|digits:10',
		// 	'avatar' => 'required|image|mimes:png,jpg,jpeg',
        // ]);
  
        // if ($validator->fails()) {
        //     return response()->json([
		// 		'error' => $validator->errors()->all()
		// 	]);
        // }

		$file = $request->file('avatar');
		// $file = $request->get('https://loremflickr.com/240/320/boy');
		// dd($file);
		// $imageResponse = Http::get('https://loremflickr.com/240/320/boy');
		// dd($imageResponse);

		// if ($imageResponse->successful()) {
		// 	// Generate a unique filename with an extension (e.g., jpg)
		// 	$filename = uniqid() . '.jpg';
	
		// 	// Store the image in the public disk
		// 	Storage::disk('public')->put('images/' . $filename, $imageResponse->body());
	
		// 	return 'Image downloaded and stored as ' . $filename;
		// }


		$fileName = time() . '.' . $file->getClientOriginalExtension();
		$file->storeAs('public/images', $fileName);
		$randomPassword = Str::random(10);

        $hashedPassword = Hash::make($randomPassword);

		$empData = ['first_name' => $request->fname, 'last_name' => $request->lname, 'email' => $request->email, 'phone' => $request->phone, 'avatar' => $fileName,'password' => $hashedPassword];
		// Employee::create($empData);
		User::create($empData);
		return response()->json([
			'status' => 200,
			'success' => 'User created successfully.'
		]);
	}

	// handle edit an employee ajax request
	public function edit(Request $request) {
		$id = $request->id;
		// $emp = Employee::find($id);
		$emp = User::find($id);
		return response()->json($emp);
	}

	// handle update an employee ajax request
	public function update(Request $request) {
		// $validated = $request->validate([
		// 	'fname' => 'required|alpha',
		// 	'lname' => 'required|alpha',
		// 	'phone' => 'required|digits:10',
		// 	'avatar' => 'required|image|mimes:png,jpg,jpeg',
		// ]);
		$fileName = '';
		$emp = User::find($request->emp_id);
		if ($request->hasFile('avatar')) { //https://loremflickr.com/240/320/boy
			$file = $request->file('avatar');
			$fileName = time() . '.' . $file->getClientOriginalExtension();
			$file->storeAs('public/images', $fileName);
			if ($emp->avatar) {
				Storage::delete('public/images/' . $emp->avatar);
			}
		} else {
			$fileName = $request->emp_avatar;
		}

		$empData = ['first_name' => $request->fname, 'last_name' => $request->lname, 'email' => $request->email, 'phone' => $request->phone, 'avatar' => $fileName];

		$emp->update($empData);
		return response()->json([
			'status' => 200,
			'success' => 'User updated successfully.'
		]);
	}

	// handle delete an employee ajax request
	public function delete(Request $request) {
		$id = $request->id;
		$emp = User::find($id);
		if (Storage::delete('public/images/' . $emp->avatar)) {
			User::destroy($id);
		}
	}

}

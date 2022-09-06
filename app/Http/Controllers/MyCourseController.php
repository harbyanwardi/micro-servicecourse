<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\MyCourse;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $mycourses = MyCourse::query()->with('course');

        $user_id = $request->query('user_id');

        $mycourses->when($user_id, function ($query) use ($user_id) {
            return $query->where("user_id", "=", $user_id);
        });
        return response()->json([
            'status' => 'success',
            'data' => $mycourses->get()
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer',
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
            ], 400);
        }


        //helper get dari service user
        $user_id = $request->input('user_id');
        $user = getUser($user_id);

        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message'],

            ], $user['http_code']);
        }

        $isExistMyCourse = MyCourse::where('course_id', '=', $courseId)
            ->where('user_id', '=', $user_id)
            ->exists();

        if ($isExistMyCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'User already take this course',
            ], 409);
        }

        if ($course->type === 'premium') {
            if($course->price === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Price can\'t be 0',
                ], 400);
            }
            //helper hit api order payment
            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);
            if($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message'],
                ], $order['http_code']);
            }
            return response()->json([
                'status' => $order['status'],
                'data' => $order['data'],

            ], 200);
        } else {
            $myCourse = MyCourse::create($data);
            return response()->json([
                'status' => 'success',
                'data' => $myCourse,
            ], 400);
        }
    }

    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Course;
use App\Lesson;
use App\ImageCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    public function index(Request $request)
    {
        $imagecourse = ImageCourse::query();
        
        $course_id = $request->query('course_id');
        
        $imagecourse->when($course_id, function($query) use ($course_id) {
            return $query->where("course_id", "=", $course_id);
        });
        return response()->json([
            'status' => 'success',
            'data' => $imagecourse->get()
        ]);

    }

    public function show($id)
    {
        $imagecourse = ImageCourse::find($id);
        if(!$imagecourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'User Not Found'
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $imagecourse
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'image' => 'required|string',
            'course_id' => 'required|integer'
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
                'message' => 'course not found',
            ], 400);
        }

        $imagecourse = ImageCourse::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $imagecourse
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'image' => 'required|string',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $imagecourse = ImageCourse::find($id);
        if (!$imagecourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image course not found',
            ], 400);
        }

        $courseId = $request->input('course_id');
        if ($courseId) {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'course not found',
                ], 400);
            }
        }


        $imagecourse->fill($data);
        $imagecourse->save();
        return response()->json([
            'status' => 'success',
            'data' => $imagecourse
        ]);
    }

    public function destroy($id)
    {
        $imagecourse = ImageCourse::find($id);
        if(!$imagecourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'imagecourse Not Found'
            ],404);
        }
        $imagecourse->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted'
        ]);
    }
}

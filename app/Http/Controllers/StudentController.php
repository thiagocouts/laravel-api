<?php

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http"},
 *     host="http://localhost:8000",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Couts API",
 *         description="Projeto API da Treina Web",
 *         @SWG\Contact(
 *             email="thiagogc1987@gmail.com"
 *         ),
 *     )
 * )
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\StudentCollection;

class StudentController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/students",
     *      operationId="getStudentsList",
     *      tags={"students"},
     *      summary="Get list of students",
     *      description="Returns list of students",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
     * Returns list of students
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Student::get()->makeHidden('room_id'); esconde nessa chamada o atributo
        return (new StudentCollection(Student::paginate(15)))
                ->response()
                ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        try {
            return Student::create($request->all());
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @SWG\Get(
     *      path="/students/{id}",
     *      operationId="getStudentById",
     *      tags={"students"},
     *      summary="Get student information",
     *      description="Returns student data",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=404, description="Resource Not Found"),
     *      security={
     *         {
     *             "oauth2_security_example": {"write:students", "read:students"}
     *         }
     *     },
     * )
     *
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        if (request()->header("Accept") === "application/xml") {
            return $this->getStudentXmlResponse($student);
        }

        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->all());

        return [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return [];
    }

    public function getStudentXmlResponse($student)
    {
        $student = $student->toArray();

        $xml = new \SimpleXMLElement('<student/>');

        array_walk_recursive($student, function($value, $key) use($xml) {
            $xml->addChild($key, $value);
        });

        return response($xml->asXML(), 200)->header("Content-Type", "application/xml");
    }
}

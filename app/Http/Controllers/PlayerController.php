<?php

namespace App\Http\Controllers;

use App\Http\Dto\Responses\HttpResponse404;
use App\Http\Dto\Responses\HttpResponse422;
use App\Http\Dto\Responses\HttpResponse500;
use App\Interfaces\iPlayerService;
use App\Utils\IdValidatorUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    use IdValidatorUtil;
    
    protected $playersService;

    /**
     * A player controller.
     * 
     * @param iPlayerService $playersService
     */
    public function __construct(iPlayerService $playersService)
    {
        $this->playersService = $playersService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $responseData = $this->playersService->readAll();

            return response()->json($responseData);
        }
        catch(\Throwable $error){
            error_log($error);
            $errorResponse = new HttpResponse500();

            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id Player ID
     * @return \Illuminate\Http\jsonResponse
     */
    public function show(string $id)
    {
        try{
            $id = $this->validateId($id);

            if(empty($id)){
                $httpResponse = new HttpResponse422();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }
    
            $dataResponse = $this->playersService->readOne($id);
    
            if(empty($dataResponse)){
                $httpResponse = new HttpResponse404();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }
    
            return response()->json($dataResponse);
        }
        catch(\Throwable $error){
            error_log($error);
            $errorResponse = new HttpResponse500();

            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $validation = $this->validateDtoPlayer($request);

            if ($validation->fails()) {
                $errors = $validation->getMessageBag()->all();
                $errorResponse = new HttpResponse422();
                $errorResponse->setMessage($errors);
    
                return response()->json($errorResponse->parse(), $errorResponse->getCode());
            }
    
            $dataValidated = $validation->validated();
    
            $newPlayer = $this->playersService->create(
                $dataValidated['name'],
                (int)$dataValidated['level'],
                $dataValidated['skills'],
                $dataValidated['genre']
            );
    
            if(empty($newPlayer)){
                $errorResponse = new HttpResponse500("New player isn't created!");
    
                return response()->json($errorResponse->parse(), $errorResponse->getCode());
            }
    
            return response()->json(["new_player" => $newPlayer], 200);
        }
        catch(\Throwable $error){
            error_log($error);
            $errorResponse = new HttpResponse500();

            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id Player ID
     * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(string $id)
    {
        try{
            $id = $this->validateId($id);

            if(empty($id)){
                $httpResponse = new HttpResponse422();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }
    
            $dataResponse = $this->playersService->delete($id);
            $codeResponse = 204;
    
            if(empty($dataResponse)){
                $httpResponse = new HttpResponse404();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }
    
            return response()->json($dataResponse, $codeResponse);
        }
        catch(\Throwable $error){
            error_log($error);
            $errorResponse = new HttpResponse500();

            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Validate data request for a player. 
     * Ex.: { "name":"Marolio", "level":100, "skills": { "strength":10, "velocity":"10", "reaction":"10" }, "genre":"male" }
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validateDtoPlayer(Request $request){
        return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:players',
                'level' => 'required|int|min:0|max:100',
                'skills' => 'required|array',
                'skills.strength' => ['int','min:1', 'max:10', Rule::requiredIf($request->genre === 'male')],
                'skills.velocity' => ['int', 'min:1', 'max:10', Rule::requiredIf($request->genre === 'male')],
                'skills.reaction' => ['int', 'min:1', 'max:10', Rule::requiredIf($request->genre === 'female')],
                'genre' => ['required', Rule::in(['female', 'male'])]
            ]
        );
    }
}

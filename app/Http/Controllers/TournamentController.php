<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidAlreadySimulatedTournamentException;
use App\Exceptions\InvalidBase2PlayersListException;
use App\Exceptions\InvalidTournamentPlayersGenreException;
use App\Http\Dto\Responses\HttpResponse404;
use App\Http\Dto\Responses\HttpResponse412;
use App\Http\Dto\Responses\HttpResponse422;
use App\Http\Dto\Responses\HttpResponse500;
use App\Interfaces\iTournamentService;
use App\Interfaces\iTennisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Utils\IdValidatorUtil;

class TournamentController extends Controller
{
    use IdValidatorUtil;

    protected iTournamentService $tournamentService;
    protected iTennisService $tennisService;

    /**
     * Create tournamente controller
     *
     * @return void
     */
    public function __construct(iTournamentService $tournamentService, iTennisService $tennisService)
    {
        $this->tournamentService = $tournamentService;
        $this->tennisService = $tennisService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $responseData = $this->tournamentService->readAll();
            return response()->json($responseData);
        } catch(\Throwable $error) {
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
        try {
            $id = $this->validateId($id);

            if (empty($id)) {
                $httpResponse = new HttpResponse422();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }

            $dataResponse = $this->tournamentService->readOne($id);

            if (empty($dataResponse)) {
                $httpResponse = new HttpResponse404();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }

            return response()->json($dataResponse);
        } catch(\Throwable $error) {
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
        try {
            $validation = $this->validateDtoCreateTournament($request);

            if ($validation->fails()) {
                $errors = $validation->getMessageBag()->all();
                $errorResponse = new HttpResponse422();
                $errorResponse->setMessage($errors);

                return response()->json($errorResponse->parse(), $errorResponse->getCode());
            }

            $dataValidated = $validation->validated();

            $newTournament = $this->tournamentService->create(
                $dataValidated['name'],
                $dataValidated['players'],
                $dataValidated['genre']
            );


            if (empty($newTournament)) {
                $errorResponse = new HttpResponse500("Error in tournament's creation!");
                return response()->json($errorResponse->parse(), $errorResponse->getCode());
            }

            if ($dataValidated['simulate'] === true) {
                $this->tennisService->play($newTournament);
            }

            return response()->json(["new_tournament" => $newTournament], 200);
        } catch(InvalidBase2PlayersListException $ex) {
            $errorResponse = new HttpResponse422($ex->getMessage());
            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        } catch(InvalidTournamentPlayersGenreException $ex) {
            $errorResponse = new HttpResponse422($ex->getMessage());
            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        } catch(\Throwable $error) {
            error_log($error);
            $errorResponse = new HttpResponse500();
            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Simulate a tournament and update resource.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function play(string $id)
    {
        try {
            $id = $this->validateId($id);

            if (empty($id)) {
                $httpResponse = new HttpResponse422();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }

            $tournament = $this->tournamentService->readOne($id);

            if (empty($tournament)) {
                $httpResponse = new HttpResponse404();
                return response()->json($httpResponse->parse(), $httpResponse->getCode());
            }

            if (!$this->tennisService->play($tournament)) {
                $httpResponse = new HttpResponse500("The server doesn't simulate tournament. This endpoint is temporarily having issues.");
                return response()->json();
            }

            return response()->json($tournament);
        } catch(InvalidAlreadySimulatedTournamentException $ex) {
            $errorResponse = new HttpResponse412($ex->getMessage());
            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        } catch(\Throwable $error) {
            error_log($error);
            $errorResponse = new HttpResponse500();
            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Display a listing of the filtered resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        try {
            $validation = $this->validateDtoFilterTournament($request);

            if ($validation->fails()) {
                $errors = $validation->getMessageBag()->all();
                $errorResponse = new HttpResponse422();
                $errorResponse->setMessage($errors);

                return response()->json($errorResponse->parse(), $errorResponse->getCode());
            }

            $dataValidated = $validation->validated();
            $tournamentsFounded = $this->tournamentService->readWithFilters($dataValidated);

            return response()->json($tournamentsFounded);
        } catch(\Throwable $error) {
            error_log($error);
            $errorResponse = new HttpResponse500();
            return response()->json($errorResponse->parse(), $errorResponse->getCode());
        }
    }

    /**
     * Validate data request to creation for a tournament.
     * Ex.: { "name":"MAROLIO'S TENNIS CUP", "players": [1,3,5,10], "genre":"male", "simulate": true }
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validateDtoCreateTournament(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'players' => 'required|array|exists:players,id',
                'genre' => ['required', Rule::in(['female', 'male'])],
                'simulate' => 'required|bool'
            ]
        );
    }

    /**
     * Validate data request to filter for a tournament.
     * Ex.: { "name":"MAROLIO'S TENNIS CUP", "players": [1,3,5,10], "genre":"male", "simulate": true }
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validateDtoFilterTournament(Request $request)
    {
        return Validator::make(
            $request->query(),
            [
                'name' => 'string|max:255',
                'genre' => [Rule::in(['female', 'male'])],
                'created_at' => 'date|date_format:Y-m-d',
                'simulated' => 'bool',
                'champion' => 'integer'
            ]
        );
    }
}

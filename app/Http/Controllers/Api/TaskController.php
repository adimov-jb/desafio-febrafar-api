<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskPostRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     * path="/task",
     * summary="Return tasks",
     * description="Return tasks",
     * operationId="task-index",
     * tags={"Task"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="true"),
        *       @OA\Property(property="data", type="object",
        *           @OA\Property(property="types", type="array",
        *               @OA\Items(
        *                   @OA\Property(property="id", type="integer", example="1"),
        *                   @OA\Property(property="type_id", type="integer", example="1"),
        *                   @OA\Property(property="user_id", type="integer", example="1"),
        *                   @OA\Property(property="title", type="string", example="custom"),
        *                   @OA\Property(property="description", type="string", example="custom"),
        *                   @OA\Property(property="start_date", type="date", example="custom"),
        *                   @OA\Property(property="deadline", type="date", example="custom"),
        *                   @OA\Property(property="finish_date", type="date", example="custom"),
        *                   @OA\Property(property="status", type="string", example="custom"),
        *               )
        *           )
        *       ),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Wrong error",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="false"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong error. Please try again")
     *        )
     *     ),
     *  )
     * )
     */
    public function index()
    {
        $tasks = Task::orderBy('deadline')->get();
        return response()->json(['data' => ['tasks' => $tasks ], 'status' => 'success','message' => 'List of tasks!' ]);
    }

    /**
     * @OA\Post(
     * path="/task",
     * summary="Create task",
     * description="Create task",
     * operationId="task-store",
     * tags={"Task"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create task",
     *    @OA\JsonContent(
     *       required={"type_id", "title", "description", "start_date", "deadline", "status"},
    *           @OA\Property(property="type_id", type="integer", example="1"),
    *           @OA\Property(property="title", type="string", example="custom"),
    *           @OA\Property(property="description", type="string", example="custom"),
    *           @OA\Property(property="start_date", type="date", example="custom"),
    *           @OA\Property(property="deadline", type="date", example="custom"),
    *           @OA\Property(property="finish_date", type="date", example="custom"),
    *           @OA\Property(property="status", type="string", example="open or concluded"),
     *    ),
     * ),
    * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *          @OA\Property(property="type", type="object",
    *           @OA\Property(property="id", type="integer", example="1"),
    *           @OA\Property(property="type_id", type="integer", example="1"),
    *           @OA\Property(property="user_id", type="integer", example="1"),
    *           @OA\Property(property="title", type="string", example="custom"),
    *           @OA\Property(property="description", type="string", example="custom"),
    *           @OA\Property(property="start_date", type="date", example="custom"),
    *           @OA\Property(property="deadline", type="date", example="custom"),
    *           @OA\Property(property="finish_date", type="date", example="custom"),
    *           @OA\Property(property="status", type="string", example="open"),
     *          )
     *       ),
     *       @OA\Property(property="success", type="boolean", example="true"),
     *       @OA\Property(property="message", type="string", example="Task created with success"),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Wrong error",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="false"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong error. Please try again!")
     *        )
     *     ),
     *  )
     * )
     */
    public function store(TaskPostRequest $request)
    {
        try {
            $data = (new TaskService())->store($request->validated());
            if($task = Task::create($data)){
                return response()->json(['data' => ['task' => $task ], 'status' => 'success','message' => 'Task created with success!' ]);
            }
            return response()->json(["data" => [] , "status" => 'error', 'message' => 'Sorry, wrong error. Please try again', ], 204);
        } catch (\Exception $e) {
            return response()->json(["data" => [] , "status" => 'error', 'message' => $e->getMessage(), ], 400);
        }


    }

/**
     * @OA\Get(
     * path="/task/{id}",
     * summary="Receive a task",
     * description="Receive a task",
     * operationId="task-show",
     * tags={"Task"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *      description="id",
     *      in="path",
     *      name="id",
     *      required=true,
     *      example="1",
     *      @OA\Schema(
     *          type="integer",
     *          format="int64"
     *      )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *          @OA\Property(property="type", type="object",
    *           @OA\Property(property="id", type="integer", example="1"),
    *           @OA\Property(property="type_id", type="integer", example="1"),
    *           @OA\Property(property="user_id", type="integer", example="1"),
    *           @OA\Property(property="title", type="string", example="custom"),
    *           @OA\Property(property="description", type="string", example="custom"),
    *           @OA\Property(property="start_date", type="date", example="custom"),
    *           @OA\Property(property="deadline", type="date", example="custom"),
    *           @OA\Property(property="finish_date", type="date", example="custom"),
    *           @OA\Property(property="status", type="string", example="open"),
     *          )
     *       ),
     *       @OA\Property(property="success", type="boolean", example="true"),
     *       @OA\Property(property="message", type="string", example="Comment updated"),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Wrong error",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="false"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong error. Please try again")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        if($task = Task::find($id)){
            return response()->json(['data' => ['task' => $task ], 'status' => 'success','message' => 'Show Task!' ]);
        }
        return response()->json(["data" => [] , "status" => 'error', 'message' => 'Sorry, wrong error. Please try again', ], 400);
    }

    /**
     * @OA\Put(
     * path="/task",
     * summary="Update task",
     * description="Update task",
     * operationId="task-put",
     * tags={"Task"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *      description="id",
     *      in="path",
     *      name="id",
     *      required=true,
     *      example="1",
     *      @OA\Schema(
     *          type="integer",
     *          format="int64"
     *      )
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Update type",
     *    @OA\JsonContent(
     *       required={},
    *           @OA\Property(property="type_id", type="integer", example="1"),
    *           @OA\Property(property="title", type="string", example="custom"),
    *           @OA\Property(property="description", type="string", example="custom"),
    *           @OA\Property(property="start_date", type="date", example="custom"),
    *           @OA\Property(property="deadline", type="date", example="custom"),
    *           @OA\Property(property="finish_date", type="date", example="custom"),
    *           @OA\Property(property="status", type="string", example="open or concluded"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *          @OA\Property(property="type", type="object",
    *           @OA\Property(property="id", type="integer", example="1"),
    *           @OA\Property(property="type_id", type="integer", example="1"),
    *           @OA\Property(property="user_id", type="integer", example="1"),
    *           @OA\Property(property="title", type="string", example="custom"),
    *           @OA\Property(property="description", type="string", example="custom"),
    *           @OA\Property(property="start_date", type="date", example="custom"),
    *           @OA\Property(property="deadline", type="date", example="custom"),
    *           @OA\Property(property="finish_date", type="date", example="custom"),
    *           @OA\Property(property="status", type="string", example="open"),
     *          )
     *       ),
     *       @OA\Property(property="success", type="boolean", example="true"),
     *       @OA\Property(property="message", type="string", example="Type created with success"),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Wrong error",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="false"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong error. Please try again!")
     *        )
     *     ),
     *  )
     * )
     */
    public function update(TaskUpdateRequest $request, $id)
    {
        $task = Task::find($id);
        if( $task->update($request->validated()) ){
            return response()->json(['data' => ['task' => $task ], 'status' => 'success','message' => 'Task updated with success!' ]);
        }
        return response()->json(["data" => [] , "status" => 'error', 'message' => 'Sorry, wrong error. Please try again', ], 204);
    }

    /**
     * @OA\Delete(
     * path="/task/{id}",
     * summary="Delete task",
     * description="Delete task",
     * operationId="task-delete",
     * tags={"Task"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *      description="id",
     *      in="path",
     *      name="id",
     *      required=true,
     *      example="1",
     *      @OA\Schema(
     *          type="integer",
     *          format="int64"
     *      )
     *  ),
     * @OA\Response(
     *      response=200,
     *      description="The resource was deleted successfully",
     *      @OA\JsonContent(
     *           @OA\Property(property="success", type="boolean", example="true"),
     *           @OA\Property(property="message", type="string", example="The resource was deleted successfully")
     *      )
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Wrong error",
     *      @OA\JsonContent(
     *          @OA\Property(property="success", type="boolean", example="false"),
     *          @OA\Property(property="message", type="string", example="Sorry, wrong error. Please try again")
     *      )
     *  ),
     * )
     */
    public function destroy($id)
    {
        if( $task = Task::find($id) ){
            $task->delete();
            return response()->json(['data' => ['task' => [] ], 'status' => 'success','message' => 'The resource was deleted successfully!' ]);
        }
        return response()->json(["data" => [] , "status" => 'error', 'message' => 'Sorry, wrong error. Please try again', ], 204);
    }
}

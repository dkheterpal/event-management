<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use \App\Models\Event;
use Illuminate\Support\Facades\Gate;
//use App\Http\Controllers\Api\AuthorizationExceptio;
class EventController extends Controller
{
    use CanLoadRelationships;
    private array $relations = ['user', 'attendees', 'attendees.user'];

    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum')->except(['index', 'show']);
    //     $this->authorizeResource(Event::class, 'event');
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = $this->loadRelationships(Event::query());


        // foreach($relations as $relation) {
        //     $query = $query->when(
        //         $this->shouldIncludeRelation($relation),
        //         fn ($q) => $q->with($relation)
        //     );
        // }
        // return Event::all();
        // return EventResource::collection(Event::all());
        // return EventResource::collection(Event::with('user')->get());


        //return EventResource::collection(Event::with('user')->paginate());

        return EventResource::collection(
            $query->latest()->paginate()
        );

    }


    // protected function shouldIncludeRelation(string $relation): bool
    // {
    //     $include = request()->query('include');
    //     if (!$include) {
    //         return false;
    //     }

    //     $relations = array_map('trim', explode(',', $include));
    //     return in_array($relation, $relations);

    // }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $event = Event::create([
            ...$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]),
             'user_id' => $request->user()->id
        ]);

        // return $event;
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        // return $event;
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // if(Gate::denies('update-event', $event)) {
        //     abort(403, 'You are not authorized to update this event');
        // }
        Gate::authorize('update-event', $event); // Use Gate::authorize()
        //Gate::authorize('update', $event);
        //Gate::authorize('update', $event);
        // try {
        //     // $this->authorize('update-event', $event);
        //     Gate::authorize('update', $event);
        // } catch (AuthorizationException $e) {
        //     abort(403, 'You are not authorized to update this event');
        // }
        // $this->authorize('update-event', $event);
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
                ]
            )
        );
        // return $event;
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        // return response()->json([
        //     'message'=> 'Event deleted successfully!'
        // ]);

        return response(status: 204);
    }
}

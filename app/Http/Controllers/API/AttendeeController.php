<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Event;
use App\Models\Attendee;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class AttendeeController extends Controller
{
    use CanLoadRelationships;
    // private array $relations = ['user', 'attendees', 'attendees.user'];
    private array $relations = ['user'];

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        //List all attendees of a specific event
        // $attendees = $event->attendees()->latest();
        //$query = $this->loadRelationships(Event::query());
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection($attendees->paginate());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        // $attendee = $event->attendees()->create([
        //     'user_id' => 1
        // ]);

        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
        );

        return new AttendeeResource($attendee);
    }

    /**
     * Display `the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        // return new AttendeeResource($attendee);
        return new AttendeeResource($this->loadRelationships($attendee));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        Gate::authorize('delete-attendee', [$event, $attendee]); // Use Gate::authorize()
        $attendee->delete();
        return response(status: 204);
    }
}

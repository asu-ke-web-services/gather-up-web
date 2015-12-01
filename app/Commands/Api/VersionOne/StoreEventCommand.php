<?php

namespace GatherUp\Commands\Api\VersionOne;

use GatherUp\Models\Event;
use GatherUp\Commands\Command;

use Illuminate\Http\Request;
use Illuminate\Contracts\Bus\SelfHandling;

class StoreEventCommand extends Command implements SelfHandling
{
    private $request;
    private $userId;
    private $teamId;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Request $request, $userId, $teamId)
    {
        $this->request = $request;
        $this->userId = $userId;
        $this->teamId = $teamId;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $eventObject = [
            'title' => $this->request->title,
            'user_id' => $this->userId,
            'team_id' => $this->teamId
        ];

        if (! empty($this->request->started_at))
        {
            $eventObject['started_at'] = $this->request->started_at;
        }

        if (! empty($this->request->notes))
        {
            $eventObject['notes'] = $this->request->notes;
        }

        $event = Event::create($eventObject);

        if ($event !== null && $event->id !== null)
        {
            return $event->id;    
        }
        else
        {
            return null;
        }
        
    }
}

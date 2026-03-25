<?php

namespace App\Services\Agent;

use App\Models\User;

class AgentReassignmentService
{
    /**
     * Reassign users from the System Agent to a newly available local agent.
     */
    public function reassignFromSystemAgent(User $newAgent): void
    {
        if (!$newAgent->hasRole('agent') || !$newAgent->district_id || !$newAgent->upazilla_id) {
            return;
        }

        $systemAgent = User::role('agent')->where('email', 'agent@luckydraw.com')->first();

        if (!$systemAgent || $systemAgent->id === $newAgent->id) {
            return;
        }

        // Find users currently assigned to System Agent who match the new agent's location
        User::where('agent_id', $systemAgent->id)
            ->where('district_id', $newAgent->district_id)
            ->where('upazilla_id', $newAgent->upazilla_id)
            ->update(['agent_id' => $newAgent->id]);
    }
}

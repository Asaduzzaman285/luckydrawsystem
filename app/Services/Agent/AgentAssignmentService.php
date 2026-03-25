<?php

namespace App\Services\Agent;

use App\Models\User;
use App\Models\District;

class AgentAssignmentService
{
    /**
     * Assign an agent to a user based on district.
     * Randomly picks one agent from the agents assigned to the district.
     */
    public function getAgentForDistrict(int $districtId): ?int
    {
        $district = District::with('agents')->find($districtId);

        if (!$district || $district->agents->isEmpty()) {
            return null;
        }

        // Randomly pick an agent
        return $district->agents->random()->id;
    }

    /**
     * Get the fallback System Agent.
     */
    public function getSystemAgent(): ?User
    {
        return User::role('agent')->where('email', 'agent@luckydraw.com')->first();
    }

    /**
     * Round-robin style (pick agent with least assigned users in that location)
     * Granular match on District AND Upazilla. Fallback to System Agent.
     */
    public function getAgentForLocation(int $districtId, int $upazillaId): ?int
    {
        $agent = User::role('agent')
            ->where('district_id', $districtId)
            ->where('upazilla_id', $upazillaId)
            ->withCount('assignedUsers')
            ->orderBy('assigned_users_count', 'asc')
            ->first();

        if ($agent) {
            return $agent->id;
        }

        // Fallback to System Agent
        $systemAgent = $this->getSystemAgent();
        return $systemAgent ? $systemAgent->id : null;
    }
}

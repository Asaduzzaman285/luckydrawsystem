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
     * Round-robin style (pick agent with least assigned users in that district)
     */
    public function getAgentForDistrictRoundRobin(int $districtId): ?int
    {
        $district = District::find($districtId);

        if (!$district) {
            return null;
        }

        $agent = User::role('agent')
            ->whereHas('districts', function ($query) use ($districtId) {
                $query->where('districts.id', $districtId);
            })
            ->withCount('assignedUsers')
            ->orderBy('assigned_users_count', 'asc')
            ->first();

        return $agent ? $agent->id : null;
    }
}

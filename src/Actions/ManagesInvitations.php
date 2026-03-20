<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Invitation;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesInvitations
{
    /**
     * Accept a group member invitation.
     *
     * @param  int  $invitationId
     * @return \Zaimea\SDK\Groups\Resources\Invitation
     */
    public function acceptGroupInvitation(int $invitationId): Invitation
    {
        return new Invitation(
            $this->get("group-invitations/accept", ['invitationId' => $invitationId])['data'], $this
        );
    }
    
    /**
     * Delete a group member invitation.
     *
     * @param  int  $invitationId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroupInvitation(int $invitationId)
    {
        return new Response($this->delete("group-invitations/delete", [
            'invitationId' => $invitationId,
        ]), $this);
    }
}
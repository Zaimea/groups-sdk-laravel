<?php

declare(strict_types=1);

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
    public function acceptMemberInvitation(int $invitationId): Invitation
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
    public function deleteMemberInvitation(int $invitationId)
    {
        return new Response($this->delete("group-invitations/delete", [
            'invitationId' => $invitationId,
        ]), $this);
    }

    /**
     * Accept a group client member invitation.
     *
     * @param  int  $invitationId
     * @return \Zaimea\SDK\Groups\Resources\Invitation
     */
    public function acceptClientInvitation(int $invitationId): Invitation
    {
        return new Invitation(
            $this->get("clients/accept/invitation", ['invitationId' => $invitationId])['data'], $this
        );
    }
    
    /**
     * Delete the given group client member invitation.
     *
     * @param  int  $invitationId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteClientInvitation(int $invitationId)
    {
        return new Response($this->delete("clients/delete/invitation", [
            'invitationId' => $invitationId,
        ]), $this);
    }
}
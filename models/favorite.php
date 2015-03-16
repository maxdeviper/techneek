<?php
class Favorite extends SObject{
	protected $inviter_id;
	protected $invitee_id;
	protected $time_of_invite;
	protected $time_of_acceptance;
	protected $blocked=false;
}
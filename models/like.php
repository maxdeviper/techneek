<?php
class Like extends SObject{
	protected what;
	protected who;
	protected time;
	protected type;
	public function byWho(){
		return $this->who;
	}
	public function what(){
		return $this->what;
	}
	public function at(){
		return $this->time();
	}
	public getType(){
		return $this->type;
	}
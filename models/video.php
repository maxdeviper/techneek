<?php
class Video extends SObject{
	protected $id:"",
	protected $owner_id;
	$tags={[{
		startTime:"",
		endTime:"",
		tagInfo:"",
		tagBox:{x,y,width,height},
	}]};
	protected $time_posted;
	protected $time_modified;
}